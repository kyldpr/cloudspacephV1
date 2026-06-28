<?php
// api/forums/image.php
// Serves forum images with JWT authentication

require_once __DIR__ . '/../jwt-helper.php';

$token = $_GET['token'] ?? '';
if (!$token) {
    http_response_code(401);
    die("Missing token");
}

$username = JWTSecurity::validateToken($token);
if (!$username) {
    http_response_code(401);
    die("Invalid or expired token");
}

$file = $_GET['file'] ?? '';
if (!$file) {
    http_response_code(400);
    die("Missing file parameter");
}

// Security: only allow alphanumeric, dot, underscore, hyphen
if (!preg_match('/^[a-zA-Z0-9._-]+$/', $file)) {
    http_response_code(400);
    die("Invalid filename");
}

$imagePath = __DIR__ . '/images/' . $file;
if (!file_exists($imagePath)) {
    http_response_code(404);
    die("Image not found");
}

// Determine MIME type
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$mimeTypes = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'webp' => 'image/webp'
];
$mime = $mimeTypes[$ext] ?? 'application/octet-stream';

header("Content-Type: $mime");
header("Cache-Control: public, max-age=86400"); // cache for a day
readfile($imagePath);
exit;