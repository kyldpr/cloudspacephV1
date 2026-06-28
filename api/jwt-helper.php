<?php
// api/jwt-helper.php

class JWTSecurity {
    // Fallback included directly via getenv configuration
    private static function getSecret() {
        // Read from your native .env file settings
        $secret = getenv('JWT_SECRET') ?: ($_ENV['JWT_SECRET'] ?? null);
        
        if (!$secret) {
            // Secure local development fallback case if .env wasn't parsed properly yet
            $envPath = __DIR__ . '/../.env';
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
            // Emergency fallback string if config reading fails completely
            return "fallback_cloudspace_default_structural_key_9912"; 
        }
        return $secret;
    }

    public static function generateToken($username) {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'username' => $username,
            'exp' => time() + (3600 * 24) // 24-Hour Expiration Window
        ]);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        // Modified to use self::getSecret() instead of static string attribute
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::getSecret(), true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function authenticate() {
        // Fallback for servers that do not support getallheaders() natively
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

        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $jwt = $matches[1];
            $tokenParts = explode('.', $jwt);
            if (count($tokenParts) === 3) {
                $header = base64_decode(self::base64UrlDecode($tokenParts[0]));
                $payload = base64_decode(self::base64UrlDecode($tokenParts[1]));
                $signatureProvided = $tokenParts[2];

                // Verify Signature Integrity using dynamic config reader
                $base64UrlHeader = $tokenParts[0];
                $base64UrlPayload = $tokenParts[1];
                $signatureCheck = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::getSecret(), true);
                $base64UrlSignatureCheck = self::base64UrlEncode($signatureCheck);

                if ($base64UrlSignatureCheck === $signatureProvided) {
                    $payloadData = json_decode($payload, true);
                    if ($payloadData['exp'] > time()) {
                        return $payloadData['username']; // Token verified successfully
                    }
                }
            }
        }
        
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Unauthorized access. Invalid or expired token signature."]);
        exit;
    }

    public static function logUserAction($username, $actionDescription) {
        // Use an absolute canonical path pointing from the api directory safely
        $logsDir = __DIR__ . '/profiles/users/logs/';
        
        if (!is_dir($logsDir)) {
            // Create the directory using recursive flag with proper permissions
            mkdir($logsDir, 0755, true);
        }

        // Sanitize username to ensure safe filenames
        $safeUsername = preg_replace('/[^a-zA-Z0-9_\-\.\@]/', '_', $username);
        $logFile = $logsDir . $safeUsername . '.json';

        $currentLogs = [];
        if (file_exists($logFile)) {
            $currentLogs = json_decode(file_get_contents($logFile), true) ?: [];
        }

        $newLogEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN',
            'action' => $actionDescription
        ];

        array_unshift($currentLogs, $newLogEntry);
        file_put_contents($logFile, json_encode($currentLogs, JSON_PRETTY_PRINT));
    }

    private static function base64UrlEncode($text) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
    }

    private static function base64UrlDecode($text) {
        $remainder = strlen($text) % 4;
        if ($remainder) {
            $text .= str_repeat('=', 4 - $remainder);
        }
        return str_replace(['-', '_'], ['+', '/'], $text);
    }
}