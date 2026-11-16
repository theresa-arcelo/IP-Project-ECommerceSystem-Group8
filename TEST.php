<?php
require_once 'services/LocationService.php';
require_once 'services/EmailService.php';

header('Content-Type: text/plain');

echo "=== LOCATION TEST ===\n";
$addresses = ['Poblacion, Liloan, Cebu', 'Cebu City', 'Manila', 'Davao City'];
foreach ($addresses as $addr) {
    $r = LocationService::isWithinServiceArea($addr);
    if (!empty($r['success'])) {
        $status = $r['within_area'] ? 'IN RANGE' : 'OUT OF RANGE';
        echo "$addr => $status ({$r['distance_km']} km)\n";
    } else {
        echo "$addr => ERROR: " . ($r['error'] ?? 'unknown') . "\n";
    }
}

echo "\n=== EMAIL TEST ===\n";
$to = '20100707@usc.edu.ph';
$name = 'Test User';

$ok1 = EmailService::sendOrderConfirmation($to, $name, 9999, 500.00, [
    ['name' => 'Sample Item', 'qty' => 2, 'subtotal' => 500.00]
]);
echo "Order confirmation: " . ($ok1 ? 'SENT' : 'FAILED') . "\n";

$ok2 = EmailService::sendStatusUpdate($to, $name, 9999, 'Completed');
echo "Status update: " . ($ok2 ? 'SENT' : 'FAILED') . "\n";

$ok3 = EmailService::sendPaymentReceipt($to, $name, 9999, 500.00);
echo "Payment receipt: " . ($ok3 ? 'SENT' : 'FAILED') . "\n";

echo "\nDone. Check $to inbox and spam folder.\n";