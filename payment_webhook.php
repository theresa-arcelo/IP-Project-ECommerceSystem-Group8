<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/services/EmailService.php';

$rawPayload = file_get_contents('php://input');
$signatureHeader = $_SERVER['HTTP_PAYMONGO_SIGNATURE'] ?? '';

$parts = [];
foreach (explode(',', $signatureHeader) as $segment) {
    $pair = explode('=', trim($segment), 2);
    if (count($pair) === 2) {
        $parts[$pair[0]] = $pair[1];
    }
}
$timestamp = $parts['t'] ?? '';
$testSig = $parts['te'] ?? ($parts['li'] ?? '');
$valid = false;
if ($timestamp !== '' && $testSig !== '') {
    $expected = hash_hmac('sha256', $timestamp . '.' . $rawPayload, PAYMONGO_WEBHOOK_SECRET);
    $valid = hash_equals($expected, $testSig);
}
if (!$valid) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid signature']);
    exit();
}

$event = json_decode($rawPayload, true);
$type = $event['data']['attributes']['type'] ?? '';
$resource = $event['data']['attributes']['data']['attributes'] ?? [];

if ($type === 'payment.paid' || $type === 'checkout_session.payment.paid') {
    $remarks = $resource['remarks'] ?? '';
    $amount = ($resource['amount'] ?? 0) / 100;
    $orderID = (int) preg_replace('/[^0-9]/', '', $remarks);

    if ($orderID > 0) {
        $now = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("UPDATE orders SET status='Completed', completeddatetime=?, proofofpayment=1 WHERE orderID=?");
        $stmt->bind_param("si", $now, $orderID);
        $stmt->execute();

        $u = $conn->prepare("SELECT u.fullname, u.email FROM users u INNER JOIN orders o ON o.userID=u.userID WHERE o.orderID=?");
        $u->bind_param("i", $orderID);
        $u->execute();
        $r = $u->get_result();
        if ($r && $r->num_rows > 0) {
            $row = $r->fetch_assoc();
            if (!empty($row['email'])) {
                EmailService::sendPaymentReceipt($row['email'], $row['fullname'], $orderID, $amount);
            }
        }
    }
}

if ($type === 'payment.failed') {
    $remarks = $resource['remarks'] ?? '';
    $orderID = (int) preg_replace('/[^0-9]/', '', $remarks);
    if ($orderID > 0) {
        $stmt = $conn->prepare("UPDATE orders SET status='Cancelled' WHERE orderID=? AND status='Pending'");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
    }
}

http_response_code(200);
echo json_encode(['received' => true]);