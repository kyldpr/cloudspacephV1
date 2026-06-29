<?php
// api/forums/image.php
// Serves forum images with JWT authentication and security headers

require_once __DIR__ . '/../jwt-helper.php';

// 1. Authenticate
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

// 2. Validate file parameter
$file = $_GET['file'] ?? '';
if (!$file) {
    http_response_code(400);
    die("Missing file parameter");
}
if (!preg_match('/^[a-zA-Z0-9._-]+$/', $file)) {
    http_response_code(400);
    die("Invalid filename");
}

// 3. Build path to secure storage (outside web root)
$storageDir = __DIR__ . '/../../storage/images/';
$imagePath = $storageDir . $file;
if (!file_exists($imagePath)) {
    http_response_code(404);
    die("Image not found");
}

// 4. Determine MIME type from magic bytes (not extension)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $imagePath);
finfo_close($finfo);
if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
    http_response_code(400);
    die("Invalid image content");
}

// 5. Security headers
header("Content-Type: $mime");
header("X-Content-Type-Options: nosniff");
header("Cache-Control: public, max-age=86400");

// 6. Output image
readfile($imagePath);
exit;