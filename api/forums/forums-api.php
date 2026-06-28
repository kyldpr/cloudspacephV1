<?php
header("Content-Type: application/json");

// ── Include JWT helper for logging ──
require_once __DIR__ . '/../jwt-helper.php';

// ── Config ──
$postsDir  = __DIR__ . '/post/';
$commentsDir = __DIR__ . '/commnents/';
$byAuthorDir = $commentsDir . 'by_author/';
$byCommenterDir = $commentsDir . 'by_commenter/';
$imagesDir = __DIR__ . '/images/';

foreach ([$postsDir, $commentsDir, $byAuthorDir, $byCommenterDir, $imagesDir] as $dir) {
    if (!file_exists($dir)) mkdir($dir, 0755, true);
}

// ── Helpers (unchanged) ──
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
        $files = glob($postsDir . '*.json');
        $posts = [];
        foreach ($files as $file) {
            $post = readJson($file);
            if ($post && isset($post['id'])) {
                // If author filter is set, skip posts not matching the author
                if ($authorFilter && strtolower($post['author']) !== strtolower($authorFilter)) {
                    continue;
                }
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
        $postFile = $postsDir . $postId . '.json';
        $post = readJson($postFile);
        if (!$post) {
            echo json_encode(["status" => "error", "message" => "Post not found."]);
            exit;
        }
        $commentsFile = commentFileForPost($postId);
        $comments = readJson($commentsFile);
        if (!$comments) $comments = [];
        echo json_encode([
            "status" => "success",
            "post" => $post,
            "comments" => $comments
        ]);
        break;

    // ── ADD COMMENT ──
    case 'add_comment':
        $postId    = trim($input['post_id'] ?? '');
        $author    = trim($input['author'] ?? '');
        $content   = trim($input['content'] ?? '');
        if (!$postId || !$author || !$content) {
            echo json_encode(["status" => "error", "message" => "post_id, author, and content are required."]);
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
            "post_title"  => $post['title'],
            "author"      => $author,
            "content"     => $content,
            "timestamp"   => $timestamp
        ];
        // Save to post comments
        $commentsFile = commentFileForPost($postId);
        $comments = readJson($commentsFile);
        if (!is_array($comments)) $comments = [];
        $comments[] = $comment;
        writeJson($commentsFile, $comments);
        // Save to author's comments
        $postAuthor = $post['author'];
        $authorFile = authorCommentsFile($postAuthor);
        $authorComments = readJson($authorFile);
        if (!is_array($authorComments)) $authorComments = [];
        $authorComments[] = $comment;
        writeJson($authorFile, $authorComments);
        // Save to commenter's comments
        $commenterFile = commenterCommentsFile($author);
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
        $authorFile = authorCommentsFile($username);
        $comments = readJson($authorFile);
        if (!is_array($comments)) $comments = [];
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
        $commenterFile = commenterCommentsFile($username);
        $comments = readJson($commenterFile);
        if (!is_array($comments)) $comments = [];
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

        // Read title and content from either $_POST (multipart) or $input (JSON)
        $title = trim($_POST['title'] ?? $input['title'] ?? '');
        $content = trim($_POST['content'] ?? $input['content'] ?? '');
        if (!$title || !$content) {
            echo json_encode(["status" => "error", "message" => "Title and content are required."]);
            exit;
        }

        $postId = 'post_' . time();
        $imageFilename = null;

        // Handle image upload (only for multipart)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['image']['tmp_name'];
            $originalName = basename($_FILES['image']['name']);
            $ext = pathinfo($originalName, PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array(strtolower($ext), $allowed)) {
                $imageFilename = $postId . '.' . $ext;
                $targetPath = $imagesDir . $imageFilename;
                if (move_uploaded_file($fileTmp, $targetPath)) {
                    error_log("Image uploaded: " . $imageFilename);
                } else {
                    error_log("Failed to move uploaded file.");
                }
            } else {
                error_log("Invalid image type: " . $ext);
            }
        }

        $post = [
            "id"        => $postId,
            "title"     => $title,
            "content"   => $content,
            "author"    => $username,
            "timestamp" => date('c'),
            "image"     => $imageFilename
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

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action specified."]);
        break;
}
