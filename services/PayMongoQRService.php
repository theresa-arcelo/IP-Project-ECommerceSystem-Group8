<?php
require_once __DIR__ . '/../config.php';

class PayMongoQRService
{
    public static function generateQR($amount, $description)
    {
        $intentPayload = [
            'data' => [
                'attributes' => [
                    'amount' => (int) round($amount * 100),
                    'currency' => 'PHP',
                    'payment_method_allowed' => ['qrph'],
                    'description' => $description,
                ],
            ],
        ];
        $intent = self::request('/payment_intents', $intentPayload, PAYMONGO_SECRET_KEY);
        if (!$intent['success']) {
            return $intent;
        }

        $intentId = $intent['data']['id'];
        $clientKey = $intent['data']['attributes']['client_key'];

        $methodPayload = [
            'data' => [
                'attributes' => [
                    'type' => 'qrph',
                ],
            ],
        ];
        $method = self::request('/payment_methods', $methodPayload, PAYMONGO_PUBLIC_KEY);
        if (!$method['success']) {
            return $method;
        }

        $methodId = $method['data']['id'];

        $attachPayload = [
            'data' => [
                'attributes' => [
                    'payment_method' => $methodId,
                    'client_key' => $clientKey,
                ],
            ],
        ];
        $attached = self::request("/payment_intents/{$intentId}/attach", $attachPayload, PAYMONGO_SECRET_KEY);
        if (!$attached['success']) {
            return $attached;
        }

        $imageUrl = $attached['data']['attributes']['next_action']['code']['image_url'] ?? null;
        if (empty($imageUrl)) {
            return ['success' => false, 'error' => 'QR image not returned by PayMongo.'];
        }

        return [
            'success' => true,
            'intent_id' => $intentId,
            'qr_image' => $imageUrl,
        ];
    }

    private static function request($endpoint, $payload, $apiKey)
    {
        $ch = curl_init(PAYMONGO_API_URL . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($result === false) {
            return ['success' => false, 'error' => 'PayMongo connection failed.'];
        }

        $decoded = json_decode($result, true);
        if ($httpCode >= 300) {
            return [
                'success' => false,
                'error' => $decoded['errors'][0]['detail'] ?? 'PayMongo API error.',
            ];
        }

        return ['success' => true] + $decoded;
    }
}