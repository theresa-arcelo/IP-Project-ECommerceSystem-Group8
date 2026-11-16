<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    public static function sendOrderConfirmation($toEmail, $customerName, $orderID, $total, $items = [])
    {
        $subject = 'TalaTrade Order Confirmation #' . $orderID;
        $body = self::buildOrderConfirmationBody($customerName, $orderID, $total, $items);
        return self::send($toEmail, $customerName, $subject, $body);
    }

    public static function sendStatusUpdate($toEmail, $customerName, $orderID, $newStatus)
    {
        $subject = 'TalaTrade Order #' . $orderID . ' Status Update';
        $body = '<p>Dear ' . htmlspecialchars($customerName) . ',</p>'
            . '<p>Your order <strong>#' . htmlspecialchars($orderID) . '</strong> status is now: <strong>'
            . htmlspecialchars($newStatus) . '</strong>.</p>'
            . '<p>Thank you for shopping with TalaTrade.</p>';
        return self::send($toEmail, $customerName, $subject, $body);
    }

    public static function sendPaymentReceipt($toEmail, $customerName, $orderID, $amount)
    {
        $subject = 'TalaTrade Payment Receipt #' . $orderID;
        $body = '<p>Dear ' . htmlspecialchars($customerName) . ',</p>'
            . '<p>Payment of <strong>₱' . number_format($amount, 2) . '</strong> received for order <strong>#'
            . htmlspecialchars($orderID) . '</strong>.</p>';
        return self::send($toEmail, $customerName, $subject, $body);
    }

    private static function buildOrderConfirmationBody($name, $orderID, $total, $items)
    {
        $html = '<p>Dear ' . htmlspecialchars($name) . ',</p>';
        $html .= '<p>Order <strong>#' . htmlspecialchars($orderID) . '</strong> received. Summary:</p>';
        if (!empty($items)) {
            $html .= '<table style="border-collapse:collapse;width:100%;">'
                . '<tr><th style="border:1px solid #ddd;padding:8px;">Item</th>'
                . '<th style="border:1px solid #ddd;padding:8px;">Qty</th>'
                . '<th style="border:1px solid #ddd;padding:8px;">Subtotal</th></tr>';
            foreach ($items as $item) {
                $html .= '<tr><td style="border:1px solid #ddd;padding:8px;">'
                    . htmlspecialchars($item['name']) . '</td>'
                    . '<td style="border:1px solid #ddd;padding:8px;text-align:center;">'
                    . (int) $item['qty'] . '</td>'
                    . '<td style="border:1px solid #ddd;padding:8px;text-align:right;">₱'
                    . number_format($item['subtotal'], 2) . '</td></tr>';
            }
            $html .= '</table>';
        }
        $html .= '<p><strong>Total: ₱' . number_format($total, 2) . '</strong></p>';
        return $html;
    }

    private static function send($toEmail, $toName, $subject, $htmlBody)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
            $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}