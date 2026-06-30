<?php
// api/jwt-helper.php

class JWTSecurity {

    private static function getSecret() {
        // 1. Try native environment variables
        $secret = getenv('JWT_SECRET') ?: ($_ENV['JWT_SECRET'] ?? null);
        
        if (!$secret) {
            // 2. Explicitly point to the absolute root file directory path where .env resides
            $envPath = dirname(__DIR__) . '/.env'; 
            if (file_exists($envPath)) {
                $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) continue;
                    if (strpos($line, '=') !== false) {
                        list($name, $value) = explode('=', $line, 2);
                        if (trim($name) === 'JWT_SECRET') {
                            return trim($value);
                        }
                    }
                }
            }
        }
        return $secret;
    }

    public static function generateToken($username) {
        $secret = self::getSecret();
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode([
            'username' => $username,
            'exp' => time() + (3600 * 24) // 24-hour expiration token window
        ]);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function authenticate() {
        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        }

        $authHeader = $headers['Authorization'] ?? 
                      $headers['authorization'] ?? 
                      $_SERVER['HTTP_AUTHORIZATION'] ?? 
                      $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? 
                      '';

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $jwt = $matches[1];
            $tokenParts = explode('.', $jwt);
            if (count($tokenParts) === 3) {
                $base64UrlHeader = $tokenParts[0];
                $base64UrlPayload = $tokenParts[1];
                $signatureProvided = $tokenParts[2];

                $payload = base64_decode(self::base64UrlDecode($base64UrlPayload));

                // Verify Signature using the safe root secret lookups
                $secret = self::getSecret();
                $signatureCheck = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
                $base64UrlSignatureCheck = self::base64UrlEncode($signatureCheck);

                // Use hash_equals to avoid timing attacks and mismatch anomalies
                if (hash_equals($base64UrlSignatureCheck, $signatureProvided)) {
                    $payloadData = json_decode($payload, true);
                    if (isset($payloadData['exp']) && $payloadData['exp'] > time()) {
                        return $payloadData['username']; 
                    }
                }
            }
        }
        
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Unauthorized access. Invalid or expired token signature."]);
        exit;
    }

    // ── Helper to get country code from IP using ip-api.com ──
    private static function getCountryFromIP($ip) {
        // Use ip-api.com free service (no API key required)
        $url = "http://ip-api.com/json/{$ip}?fields=status,countryCode";
        $response = @file_get_contents($url);
        if ($response) {
            $data = json_decode($response, true);
            if ($data['status'] === 'success') {
                return $data['countryCode'] ?? 'Unknown';
            }
        }
        return 'Unknown';
    }

    // ── Enhanced logUserAction with user_agent and country ──
    public static function logUserAction($username, $actionDescription) {
        $logsDir = dirname(__DIR__) . '/api/profiles/users/logs/';
        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }
        $safeUsername = preg_replace('/[^a-zA-Z0-9_\-\.\@]/', '_', $username);
        $logFile = $logsDir . $safeUsername . '.json';
        
        $currentLogs = [];
        if (file_exists($logFile)) {
            $currentLogs = json_decode(file_get_contents($logFile), true) ?: [];
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
        $country = self::getCountryFromIP($ip);

        array_unshift($currentLogs, [
            'timestamp'   => date('Y-m-d H:i:s'),
            'ip_address'  => $ip,
            'user_agent'  => $userAgent,
            'country'     => $country,
            'action'      => $actionDescription
        ]);
        file_put_contents($logFile, json_encode($currentLogs, JSON_PRETTY_PRINT));
    }

    private static function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private static function base64UrlDecode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return str_replace(['-', '_'], ['+', '/'], $data);
    }

    // ── NEW: Validate a token string directly ──
    public static function validateToken($token) {
        $tokenParts = explode('.', $token);
        if (count($tokenParts) !== 3) return false;

        $signatureProvided = $tokenParts[2];
        $base64UrlHeader = $tokenParts[0];
        $base64UrlPayload = $tokenParts[1];

        $signatureCheck = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::getSecret(), true);
        $base64UrlSignatureCheck = self::base64UrlEncode($signatureCheck);

        if ($base64UrlSignatureCheck !== $signatureProvided) {
            return false;
        }

        $payload = json_decode(base64_decode(self::base64UrlDecode($base64UrlPayload)), true);
        if (!isset($payload['exp']) || $payload['exp'] <= time()) {
            return false;
        }

        return $payload['username'] ?? false;
    }
}