<?php
require_once __DIR__ . '/../config.php';

class LocationService
{
    public static function geocodeAddress($address)
    {
        $url = NOMINATIM_URL . '?' . http_build_query([
            'q' => $address,
            'format' => 'json',
            'limit' => 1,
            'countrycodes' => 'ph',
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, NOMINATIM_USER_AGENT);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === false) {
            return ['success' => false, 'error' => 'Geocoding service unavailable.'];
        }

        $decoded = json_decode($result, true);
        if (!is_array($decoded) || empty($decoded[0]['lat'])) {
            return ['success' => false, 'error' => 'Address could not be geocoded.'];
        }

        return [
            'success' => true,
            'lat' => (float) $decoded[0]['lat'],
            'lng' => (float) $decoded[0]['lon'],
            'formatted' => $decoded[0]['display_name'] ?? $address,
        ];
    }

    public static function isWithinServiceArea($address)
    {
        $geo = self::geocodeAddress($address);
        if (!$geo['success']) {
            return $geo;
        }

        $distance = self::haversineDistance(
            STORE_LATITUDE, STORE_LONGITUDE,
            $geo['lat'], $geo['lng']
        );

        return [
            'success' => true,
            'within_area' => $distance <= STORE_SERVICE_RADIUS_KM,
            'distance_km' => round($distance, 2),
            'formatted_address' => $geo['formatted'],
        ];
    }

    private static function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}