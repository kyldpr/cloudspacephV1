<?php
// api/jwt-helper.php

class JWTSecurity {
    private static $secret = "YOUR_SUPER_SECRET_KEY_CHANGE_THIS"; // Set this to a secure random string

    public static function generateToken($username) {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'username' => $username,
            'exp' => time() + (3600 * 24) // 24-Hour Expiration Window
        ]);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function authenticate() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $jwt = $matches[1];
            $tokenParts = explode('.', $jwt);
            if (count($tokenParts) === 3) {
                $header = base64_decode(self::base64UrlDecode($tokenParts[0]));
                $payload = base64_decode(self::base64UrlDecode($tokenParts[1]));
                $signatureProvided = $tokenParts[2];

                // Verify Signature Integrity
                $base64UrlHeader = $tokenParts[0];
                $base64UrlPayload = $tokenParts[1];
                $signatureCheck = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret, true);
                $base64UrlSignatureCheck = self::base64UrlEncode($signatureCheck);

                if ($base64UrlSignatureCheck === $signatureProvided) {
                    $payloadData = json_decode($payload, true);
                    if ($payloadData['exp'] > time()) {
                        return $payloadData['username']; // Token is completely verified
                    }
                }
            }
        }
        
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Unauthorized access. Invalid or expired token signature."]);
        exit;
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
