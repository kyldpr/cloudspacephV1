<?php
header("Content-Type: application/json");

// ── Include JWT helper for logging ──
require_once __DIR__ . '/../jwt-helper.php';

// ── Rate limiting helper ──
function checkRateLimit($key, $maxAttempts = 10, $timeWindow = 60) {
    $rateFile = sys_get_temp_dir() . '/rate_' . md5($key) . '.json';
    $data = [];
    if (file_exists($rateFile)) {
        $data = json_decode(file_get_contents($rateFile), true);
        if (!is_array($data)) $data = [];
    }
    $now = time();
    $data = array_filter($data, function($t) use ($now, $timeWindow) {
        return ($now - $t) < $timeWindow;
    });
    if (count($data) >= $maxAttempts) {
        return false;
    }
    $data[] = $now;
    file_put_contents($rateFile, json_encode($data));
    return true;
}

// ── Validation helpers ──
function isValidPostId($id) {
    return preg_match('/^post_\d+$/', $id) === 1;
}

function isValidUsername($name) {
    return preg_match('/^[a-zA-Z0-9._@-]+$/', $name) === 1;
}

// ── Output sanitization helper ──
function safe($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// ── Config ──
$postsDir  = __DIR__ . '/post/';
$commentsDir = __DIR__ . '/commnents/';
$byAuthorDir = $commentsDir . 'by_author/';
$byCommenterDir = $commentsDir . 'by_commenter/';
$imagesDir = __DIR__ . '/../../storage/images/';

foreach ([$postsDir, $commentsDir, $byAuthorDir, $byCommenterDir, $imagesDir] as $dir) {
    if (!file_exists($dir)) mkdir($dir, 0755, true);
}

// ── Helpers ──
function generateId() {
    return 'c_' . bin2hex(random_bytes(8));
}

function readJson($path) {
    if (!file_exists($path)) return null;
    $data = file_get_contents($path);
    if ($data === false) return null;
    $decoded = json_decode($data, true);
    return is_array($decoded) ? $decoded : null;
}

function writeJson($path, $data) {
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}

function commentFileForPost($postId) {
    global $commentsDir;
    return $commentsDir . $postId . '.json';
}

function authorCommentsFile($authorUsername) {
    global $byAuthorDir;
    return $byAuthorDir . strtolower($authorUsername) . '.json';
}

function commenterCommentsFile($commenterUsername) {
    global $byCommenterDir;
    return $byCommenterDir . strtolower($commenterUsername) . '.json';
}

// ── Index update helpers for comments ──
function updateCommentInIndex($file, $commentId, $newContent, $newTimestamp) {
    $data = readJson($file);
    if (!is_array($data)) return;
    foreach ($data as &$item) {
        if ($item['comment_id'] === $commentId) {
            $item['content'] = $newContent;
            $item['timestamp'] = $newTimestamp;
            break;
        }
    }
    writeJson($file, $data);
}

function removeCommentFromIndex($file, $commentId) {
    $data = readJson($file);
    if (!is_array($data)) return;
    $data = array_filter($data, function($item) use ($commentId) {
        return $item['comment_id'] !== $commentId;
    });
    writeJson($file, array_values($data));
}

// ── Parse request ──
$method = $_SERVER['REQUEST_METHOD'];
$action = null;
$input = [];

if ($method === 'POST') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'multipart/form-data') !== false) {
        // Multipart request – read action from $_POST
        $action = $_POST['action'] ?? null;
        // Other fields will be read from $_POST or $_FILES as needed
    } else {
        // JSON request – parse php://input
        $raw = file_get_contents("php://input");
        $input = json_decode($raw, true);
        if (is_array($input)) {
            $action = $input['action'] ?? null;
        }
    }
}

// If not found in POST, fallback to GET
if (!$action) {
    $action = $_GET['action'] ?? '';
}

// ─── SWITCH CASES ───
switch ($action) {

    // ── LIST POSTS (with optional author filter) ──
    case 'list_posts':
        $authorFilter = $_GET['author'] ?? '';
        if ($authorFilter && !isValidUsername($authorFilter)) {
            echo json_encode(["status" => "error", "message" => "Invalid author filter."]);
            exit;
        }

        // Get current user from token (if any) for liked status
        $currentUser = null;
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
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        if ($token) {
            $currentUser = JWTSecurity::validateToken($token);
        }

        $files = glob($postsDir . '*.json');
        $posts = [];
        foreach ($files as $file) {
            $post = readJson($file);
            if ($post && isset($post['id'])) {
                // If author filter is set, skip posts not matching the author
                if ($authorFilter && strtolower($post['author']) !== strtolower($authorFilter)) {
                    continue;
                }
                // Sanitize output fields to prevent XSS
                $post['title'] = safe($post['title']);
                $post['content'] = safe($post['content']);

                // Ensure views, likes exist
                if (!isset($post['views'])) $post['views'] = 0;
                if (!isset($post['likes'])) $post['likes'] = 0;
                if (!isset($post['liked_by'])) $post['liked_by'] = [];
                $post['liked'] = $currentUser ? in_array(strtolower($currentUser), array_map('strtolower', $post['liked_by'])) : false;
                unset($post['liked_by']); // not needed in output
                unset($post['viewed_by']); // hide viewed_by from output

                $commentsFile = commentFileForPost($post['id']);
                $comments = readJson($commentsFile);
                $post['comment_count'] = is_array($comments) ? count($comments) : 0;
                $posts[] = $post;
            }
        }
        usort($posts, function($a, $b) {
            return strcmp($b['timestamp'] ?? '', $a['timestamp'] ?? '');
        });
        echo json_encode(["status" => "success", "posts" => $posts]);
        break;

    // ── GET SINGLE POST ──
    case 'get_post':
        $postId = $input['post_id'] ?? $_GET['post_id'] ?? '';
        if (!$postId) {
            echo json_encode(["status" => "error", "message" => "Post ID is required."]);
            exit;
        }
        if (!isValidPostId($postId)) {
            echo json_encode(["status" => "error", "message" => "Invalid post ID format."]);
            exit;
        }
        $postFile = $postsDir . $postId . '.json';
        $post = readJson($postFile);
        if (!$post) {
            echo json_encode(["status" => "error", "message" => "Post not found."]);
            exit;
        }
        // Sanitize post title and content
        $post['title'] = safe($post['title']);
        $post['content'] = safe($post['content']);

        // Add views & likes
        if (!isset($post['views'])) $post['views'] = 0;
        if (!isset($post['likes'])) $post['likes'] = 0;
        if (!isset($post['liked_by'])) $post['liked_by'] = [];

        // Check if current user liked (if token provided)
        $liked = false;
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
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        if ($token) {
            $user = JWTSecurity::validateToken($token);
            if ($user) {
                $liked = in_array(strtolower($user), array_map('strtolower', $post['liked_by']));
            }
        }
        $post['liked'] = $liked;
        unset($post['liked_by']); // remove list, only keep count
        unset($post['viewed_by']); // hide viewed_by

        $commentsFile = commentFileForPost($postId);
        $comments = readJson($commentsFile);
        if (!$comments) $comments = [];

        // Sanitize comments
        foreach ($comments as &$c) {
            $c['content'] = safe($c['content']);
            $c['post_title'] = safe($c['post_title']);
        }
        unset($c);

        echo json_encode([
            "status" => "success",
            "post" => $post,
            "comments" => $comments
        ]);
        break;

    // ── ADD COMMENT (authenticated, rate-limited) ──
    case 'add_comment':
        // ── 1. Authenticate via Bearer token ──
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
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        if (!$token) {
            echo json_encode(["status" => "error", "message" => "Authorization token required."]);
            exit;
        }
        $username = JWTSecurity::validateToken($token);
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
            exit;
        }

        // Rate limit check
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if (!checkRateLimit($ip . '_write', 10, 60)) {
            echo json_encode(["status" => "error", "message" => "Too many write attempts. Please wait a minute."]);
            exit;
        }

        // ── 2. Read input; author is taken from token ──
        $postId    = trim($input['post_id'] ?? '');
        $content   = trim($input['content'] ?? '');
        if (!$postId || !$content) {
            echo json_encode(["status" => "error", "message" => "post_id and content are required."]);
            exit;
        }
        if (!isValidPostId($postId)) {
            echo json_encode(["status" => "error", "message" => "Invalid post ID format."]);
            exit;
        }

        $postFile = $postsDir . $postId . '.json';
        $post = readJson($postFile);
        if (!$post) {
            echo json_encode(["status" => "error", "message" => "Post not found."]);
            exit;
        }

        $timestamp = date('c');
        $commentId = generateId();
        $comment = [
            "comment_id"  => $commentId,
            "post_id"     => $postId,
            "post_title"  => $post['title'],   // raw title stored as is (will be escaped on output)
            "author"      => $username,
            "content"     => $content,
            "timestamp"   => $timestamp
        ];

        // Save to post comments
        $commentsFile = commentFileForPost($postId);
        $comments = readJson($commentsFile);
        if (!is_array($comments)) $comments = [];
        $comments[] = $comment;
        writeJson($commentsFile, $comments);

        // Save to post author's comment index
        $postAuthor = $post['author'];
        $authorFile = authorCommentsFile($postAuthor);
        $authorComments = readJson($authorFile);
        if (!is_array($authorComments)) $authorComments = [];
        $authorComments[] = $comment;
        writeJson($authorFile, $authorComments);

        // Save to commenter's own index
        $commenterFile = commenterCommentsFile($username);
        $commenterComments = readJson($commenterFile);
        if (!is_array($commenterComments)) $commenterComments = [];
        $commenterComments[] = $comment;
        writeJson($commenterFile, $commenterComments);

        echo json_encode([
            "status" => "success",
            "message" => "Comment added successfully.",
            "comment" => $comment
        ]);
        break;

    // ── GET COMMENTS BY AUTHOR ──
    case 'get_comments_by_author':
        $username = $input['username'] ?? $_GET['username'] ?? '';
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Username is required."]);
            exit;
        }
        if (!isValidUsername($username)) {
            echo json_encode(["status" => "error", "message" => "Invalid username format."]);
            exit;
        }
        $authorFile = authorCommentsFile($username);
        $comments = readJson($authorFile);
        if (!is_array($comments)) $comments = [];
        // Sanitize comments output
        foreach ($comments as &$c) {
            $c['content'] = safe($c['content']);
            $c['post_title'] = safe($c['post_title']);
        }
        unset($c);
        usort($comments, function($a, $b) {
            return strcmp($b['timestamp'] ?? '', $a['timestamp'] ?? '');
        });
        echo json_encode(["status" => "success", "comments" => $comments]);
        break;

    // ── GET COMMENTS BY COMMENTING USER ──
    case 'get_comments_by_user':
        $username = $input['username'] ?? $_GET['username'] ?? '';
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Username is required."]);
            exit;
        }
        if (!isValidUsername($username)) {
            echo json_encode(["status" => "error", "message" => "Invalid username format."]);
            exit;
        }
        $commenterFile = commenterCommentsFile($username);
        $comments = readJson($commenterFile);
        if (!is_array($comments)) $comments = [];
        foreach ($comments as &$c) {
            $c['content'] = safe($c['content']);
            $c['post_title'] = safe($c['post_title']);
        }
        unset($c);
        usort($comments, function($a, $b) {
            return strcmp($b['timestamp'] ?? '', $a['timestamp'] ?? '');
        });
        echo json_encode(["status" => "success", "comments" => $comments]);
        break;

    // ── CREATE NEW POST (multipart or JSON) ──
    case 'create_post':
        // Authenticate via JWT (Bearer token)
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
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        if (!$token) {
            echo json_encode(["status" => "error", "message" => "Authorization token required."]);
            exit;
        }
        $username = JWTSecurity::validateToken($token);
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
            exit;
        }

        // Rate limit check for write operations
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if (!checkRateLimit($ip . '_write', 10, 60)) {
            echo json_encode(["status" => "error", "message" => "Too many write attempts. Please wait a minute."]);
            exit;
        }

        // Read title and content from either $_POST (multipart) or $input (JSON)
        $title = trim($_POST['title'] ?? $input['title'] ?? '');
        $content = trim($_POST['content'] ?? $input['content'] ?? '');
        if (!$title || !$content) {
            echo json_encode(["status" => "error", "message" => "Title and content are required."]);
            exit;
        }

        $postId = 'post_' . time();
        $imageFilename = null;

        // Handle image upload (only for multipart) – with magic byte validation
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['image']['tmp_name'];
            $originalName = basename($_FILES['image']['name']);
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            // Magic byte validation
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fileTmp);
            finfo_close($finfo);
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mimeType, $allowedMimes)) {
                error_log("Invalid MIME type: " . $mimeType);
                echo json_encode(["status" => "error", "message" => "Invalid image type."]);
                exit;
            }

            // Verify extension matches MIME
            $extMap = [
                'image/jpeg' => ['jpg', 'jpeg'],
                'image/png'  => ['png'],
                'image/gif'  => ['gif'],
                'image/webp' => ['webp']
            ];
            if (!in_array($ext, $extMap[$mimeType] ?? [])) {
                echo json_encode(["status" => "error", "message" => "File extension does not match image type."]);
                exit;
            }

            // Use a secure new filename (random)
            $random = bin2hex(random_bytes(16));
            $imageFilename = $random . '.' . $extMap[$mimeType][0]; // e.g., a1b2c3.jpg
            $targetPath = $imagesDir . $imageFilename;

            if (move_uploaded_file($fileTmp, $targetPath)) {
                error_log("Image uploaded securely: " . $imageFilename);
            } else {
                error_log("Failed to move uploaded file.");
                echo json_encode(["status" => "error", "message" => "Failed to save image."]);
                exit;
            }
        }

        $post = [
            "id"        => $postId,
            "title"     => $title,
            "content"   => $content,
            "author"    => $username,
            "timestamp" => date('c'),
            "image"     => $imageFilename,
            "views"     => 0,
            "likes"     => 0,
            "liked_by"  => [],
            "viewed_by" => []  // ← new field for unique views
        ];

        $postFile = $postsDir . $postId . '.json';
        if (file_exists($postFile)) {
            echo json_encode(["status" => "error", "message" => "Post already exists (duplicate time)."]);
            exit;
        }

        writeJson($postFile, $post);
        JWTSecurity::logUserAction($username, "Created a new forum thread entry: '" . $title . "'");

        echo json_encode([
            "status" => "success",
            "message" => "Post created successfully.",
            "post" => $post
        ]);
        break;

    // ── UPDATE POST (edit) ──
    case 'update_post':
        // Authenticate
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
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        if (!$token) {
            echo json_encode(["status" => "error", "message" => "Authorization token required."]);
            exit;
        }
        $username = JWTSecurity::validateToken($token);
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
            exit;
        }

        // Read from both $_POST and $input for multipart/JSON compatibility
        $postId = trim($_POST['post_id'] ?? $input['post_id'] ?? '');
        $newTitle = trim($_POST['title'] ?? $input['title'] ?? '');
        $newContent = trim($_POST['content'] ?? $input['content'] ?? '');
        if (!$postId || !$newTitle || !$newContent) {
            echo json_encode(["status" => "error", "message" => "post_id, title, and content are required."]);
            exit;
        }
        if (!isValidPostId($postId)) {
            echo json_encode(["status" => "error", "message" => "Invalid post ID format."]);
            exit;
        }

        $postFile = $postsDir . $postId . '.json';
        $post = readJson($postFile);
        if (!$post) {
            echo json_encode(["status" => "error", "message" => "Post not found."]);
            exit;
        }
        // Verify ownership
        if (strtolower($post['author']) !== strtolower($username)) {
            echo json_encode(["status" => "error", "message" => "You are not the author of this post."]);
            exit;
        }

        // ── Handle optional image upload ──
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['image']['tmp_name'];
            $originalName = basename($_FILES['image']['name']);
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            // Magic byte validation (same as create_post)
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fileTmp);
            finfo_close($finfo);
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mimeType, $allowedMimes)) {
                echo json_encode(["status" => "error", "message" => "Invalid image type."]);
                exit;
            }
            $extMap = [
                'image/jpeg' => ['jpg', 'jpeg'],
                'image/png'  => ['png'],
                'image/gif'  => ['gif'],
                'image/webp' => ['webp']
            ];
            if (!in_array($ext, $extMap[$mimeType] ?? [])) {
                echo json_encode(["status" => "error", "message" => "File extension does not match image type."]);
                exit;
            }

            // Delete old image if exists
            if (!empty($post['image'])) {
                $oldImage = $imagesDir . $post['image'];
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            // Generate new random filename
            $random = bin2hex(random_bytes(16));
            $newImageFilename = $random . '.' . $extMap[$mimeType][0];
            $targetPath = $imagesDir . $newImageFilename;
            if (move_uploaded_file($fileTmp, $targetPath)) {
                $post['image'] = $newImageFilename;
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to save new image."]);
                exit;
            }
        }

        // Update fields
        $post['title'] = $newTitle;
        $post['content'] = $newContent;
        $post['timestamp'] = date('c');

        writeJson($postFile, $post);
        JWTSecurity::logUserAction($username, "Updated forum thread: '" . $newTitle . "'");
        echo json_encode(["status" => "success", "message" => "Post updated.", "post" => $post]);
        break;

    // ── DELETE POST ──
    case 'delete_post':
        // Authenticate
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
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        if (!$token) {
            echo json_encode(["status" => "error", "message" => "Authorization token required."]);
            exit;
        }
        $username = JWTSecurity::validateToken($token);
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
            exit;
        }

        $postId = trim($input['post_id'] ?? '');
        if (!$postId) {
            echo json_encode(["status" => "error", "message" => "post_id is required."]);
            exit;
        }
        if (!isValidPostId($postId)) {
            echo json_encode(["status" => "error", "message" => "Invalid post ID format."]);
            exit;
        }

        $postFile = $postsDir . $postId . '.json';
        $post = readJson($postFile);
        if (!$post) {
            echo json_encode(["status" => "error", "message" => "Post not found."]);
            exit;
        }
        if (strtolower($post['author']) !== strtolower($username)) {
            echo json_encode(["status" => "error", "message" => "You are not the author of this post."]);
            exit;
        }

        // Delete the post JSON
        if (!unlink($postFile)) {
            echo json_encode(["status" => "error", "message" => "Failed to delete post."]);
            exit;
        }

        // Optionally delete associated image
        if (!empty($post['image'])) {
            $imagePath = $imagesDir . $post['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Delete all comments for this post
        $commentsFile = commentFileForPost($postId);
        if (file_exists($commentsFile)) {
            unlink($commentsFile);
        }

        JWTSecurity::logUserAction($username, "Deleted forum thread: '" . $post['title'] . "'");
        echo json_encode(["status" => "success", "message" => "Post deleted."]);
        break;

    // ── VIEW POST (increment view count, one per user) ──
    case 'view_post':
        // Authenticate
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
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        if (!$token) {
            echo json_encode(["status" => "error", "message" => "Authorization token required."]);
            exit;
        }
        $username = JWTSecurity::validateToken($token);
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
            exit;
        }

        $postId = trim($input['post_id'] ?? '');
        if (!$postId || !isValidPostId($postId)) {
            echo json_encode(["status" => "error", "message" => "Invalid post ID."]);
            exit;
        }
        $postFile = $postsDir . $postId . '.json';
        $post = readJson($postFile);
        if (!$post) {
            echo json_encode(["status" => "error", "message" => "Post not found."]);
            exit;
        }

        // Initialize viewed_by if not exist
        if (!isset($post['viewed_by'])) $post['viewed_by'] = [];

        // Check if this user already viewed
        $user = strtolower($username);
        if (!in_array($user, array_map('strtolower', $post['viewed_by']))) {
            $post['views'] = isset($post['views']) ? $post['views'] + 1 : 1;
            $post['viewed_by'][] = $user;
            writeJson($postFile, $post);
        }

        echo json_encode(["status" => "success", "views" => $post['views']]);
        break;

    // ── LIKE / UNLIKE POST ──
    case 'like_post':
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
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        if (!$token) {
            echo json_encode(["status" => "error", "message" => "Authorization token required."]);
            exit;
        }
        $username = JWTSecurity::validateToken($token);
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
            exit;
        }

        $postId = trim($input['post_id'] ?? '');
        if (!$postId || !isValidPostId($postId)) {
            echo json_encode(["status" => "error", "message" => "Invalid post ID."]);
            exit;
        }
        $postFile = $postsDir . $postId . '.json';
        $post = readJson($postFile);
        if (!$post) {
            echo json_encode(["status" => "error", "message" => "Post not found."]);
            exit;
        }
        // Initialize like fields if not exist
        if (!isset($post['likes'])) $post['likes'] = 0;
        if (!isset($post['liked_by'])) $post['liked_by'] = [];

        // Toggle like
        $user = strtolower($username);
        if (in_array($user, $post['liked_by'])) {
            // Unlike
            $post['liked_by'] = array_values(array_filter($post['liked_by'], function($u) use ($user) { return strtolower($u) !== $user; }));
            $post['likes'] = max(0, $post['likes'] - 1);
            $liked = false;
        } else {
            // Like
            $post['liked_by'][] = $user;
            $post['likes']++;
            $liked = true;
        }
        writeJson($postFile, $post);
        echo json_encode(["status" => "success", "likes" => $post['likes'], "liked" => $liked]);
        break;

    // ── EDIT COMMENT ──
    case 'edit_comment':
        // Authenticate
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
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        if (!$token) {
            echo json_encode(["status" => "error", "message" => "Authorization token required."]);
            exit;
        }
        $username = JWTSecurity::validateToken($token);
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
            exit;
        }

        $postId = trim($input['post_id'] ?? '');
        $commentId = trim($input['comment_id'] ?? '');
        $newContent = trim($input['content'] ?? '');
        if (!$postId || !$commentId || !$newContent) {
            echo json_encode(["status" => "error", "message" => "post_id, comment_id, and content are required."]);
            exit;
        }
        if (!isValidPostId($postId)) {
            echo json_encode(["status" => "error", "message" => "Invalid post ID format."]);
            exit;
        }

        $postFile = $postsDir . $postId . '.json';
        $post = readJson($postFile);
        if (!$post) {
            echo json_encode(["status" => "error", "message" => "Post not found."]);
            exit;
        }

        // Load comments for this post
        $commentsFile = commentFileForPost($postId);
        $comments = readJson($commentsFile);
        if (!is_array($comments)) {
            echo json_encode(["status" => "error", "message" => "No comments found."]);
            exit;
        }

        $found = false;
        foreach ($comments as &$c) {
            if ($c['comment_id'] === $commentId) {
                // Check author
                if (strtolower($c['author']) !== strtolower($username)) {
                    echo json_encode(["status" => "error", "message" => "Not your comment."]);
                    exit;
                }
                $c['content'] = $newContent;
                $c['timestamp'] = date('c');
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo json_encode(["status" => "error", "message" => "Comment not found."]);
            exit;
        }
        writeJson($commentsFile, $comments);

        // Update by_author and by_commenter files
        $newTimestamp = date('c');
        updateCommentInIndex(authorCommentsFile($post['author']), $commentId, $newContent, $newTimestamp);
        updateCommentInIndex(commenterCommentsFile($username), $commentId, $newContent, $newTimestamp);

        echo json_encode(["status" => "success", "message" => "Comment updated."]);
        break;

    // ── DELETE COMMENT ──
    case 'delete_comment':
        // Authenticate
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
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }
        if (!$token) {
            echo json_encode(["status" => "error", "message" => "Authorization token required."]);
            exit;
        }
        $username = JWTSecurity::validateToken($token);
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
            exit;
        }

        $postId = trim($input['post_id'] ?? '');
        $commentId = trim($input['comment_id'] ?? '');
        if (!$postId || !$commentId) {
            echo json_encode(["status" => "error", "message" => "post_id and comment_id are required."]);
            exit;
        }
        if (!isValidPostId($postId)) {
            echo json_encode(["status" => "error", "message" => "Invalid post ID format."]);
            exit;
        }

        $postFile = $postsDir . $postId . '.json';
        $post = readJson($postFile);
        if (!$post) {
            echo json_encode(["status" => "error", "message" => "Post not found."]);
            exit;
        }

        $commentsFile = commentFileForPost($postId);
        $comments = readJson($commentsFile);
        if (!is_array($comments)) {
            echo json_encode(["status" => "error", "message" => "No comments found."]);
            exit;
        }

        $found = false;
        $comments = array_filter($comments, function($c) use ($commentId, $username, &$found) {
            if ($c['comment_id'] === $commentId) {
                if (strtolower($c['author']) !== strtolower($username)) {
                    $found = 'unauthorized';
                    return false;
                }
                $found = true;
                return false; // remove
            }
            return true;
        });
        if ($found === 'unauthorized') {
            echo json_encode(["status" => "error", "message" => "Not your comment."]);
            exit;
        }
        if (!$found) {
            echo json_encode(["status" => "error", "message" => "Comment not found."]);
            exit;
        }
        writeJson($commentsFile, array_values($comments));

        // Remove from indexes
        removeCommentFromIndex(authorCommentsFile($post['author']), $commentId);
        removeCommentFromIndex(commenterCommentsFile($username), $commentId);

        echo json_encode(["status" => "success", "message" => "Comment deleted."]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action specified."]);
        break;
}