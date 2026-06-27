<?php
header("Content-Type: application/json");

// ── Include JWT helper for logging ──
require_once __DIR__ . '/../jwt-helper.php';

// ── Config ──
$postsDir  = __DIR__ . '/post/';
$commentsDir = __DIR__ . '/commnents/';
$byAuthorDir = $commentsDir . 'by_author/';
$byCommenterDir = $commentsDir . 'by_commenter/';

// ── Ensure directories exist ──
foreach ([$postsDir, $commentsDir, $byAuthorDir, $byCommenterDir] as $dir) {
    if (!file_exists($dir)) mkdir($dir, 0777, true);
}

// ── Helper: random ID ──
function generateId() {
    return 'c_' . bin2hex(random_bytes(8));
}

// ── Helper: read JSON file safely ──
function readJson($path) {
    if (!file_exists($path)) return null;
    $data = file_get_contents($path);
    if ($data === false) return null;
    $decoded = json_decode($data, true);
    return is_array($decoded) ? $decoded : null;
}

// ── Helper: write JSON file ──
function writeJson($path, $data) {
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}

// ── Helper: get comment file path for a post ──
function commentFileForPost($postId) {
    global $commentsDir;
    return $commentsDir . $postId . '.json';
}

// ── Helper: get author comments file ──
function authorCommentsFile($authorUsername) {
    global $byAuthorDir;
    return $byAuthorDir . strtolower($authorUsername) . '.json';
}

// ── Helper: get commenter comments file ──
function commenterCommentsFile($commenterUsername) {
    global $byCommenterDir;
    return $byCommenterDir . strtolower($commenterUsername) . '.json';
}

// ── Parse request ──
$method = $_SERVER['REQUEST_METHOD'];
$input = [];
if ($method === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!is_array($input)) $input = [];
}

$action = $input['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ────────────────────────────────────────────
    // LIST POSTS
    // ────────────────────────────────────────────
    case 'list_posts':
        $files = glob($postsDir . '*.json');
        $posts = [];
        foreach ($files as $file) {
            $post = readJson($file);
            if ($post && isset($post['id'])) {
                // Attach comment count
                $commentsFile = commentFileForPost($post['id']);
                $comments = readJson($commentsFile);
                $post['comment_count'] = is_array($comments) ? count($comments) : 0;
                $posts[] = $post;
            }
        }
        // Sort by timestamp descending (newest first)
        usort($posts, function($a, $b) {
            return strcmp($b['timestamp'] ?? '', $a['timestamp'] ?? '');
        });
        echo json_encode(["status" => "success", "posts" => $posts]);
        break;

    // ────────────────────────────────────────────
    // GET SINGLE POST WITH COMMENTS
    // ────────────────────────────────────────────
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

        // Load comments for this post
        $commentsFile = commentFileForPost($postId);
        $comments = readJson($commentsFile);
        if (!$comments) $comments = [];

        echo json_encode([
            "status" => "success",
            "post" => $post,
            "comments" => $comments
        ]);
        break;

    // ────────────────────────────────────────────
    // POST A COMMENT
    // ────────────────────────────────────────────
    case 'add_comment':
        $postId    = trim($input['post_id'] ?? '');
        $author    = trim($input['author'] ?? '');    // the person commenting
        $content   = trim($input['content'] ?? '');

        if (!$postId || !$author || !$content) {
            echo json_encode(["status" => "error", "message" => "post_id, author, and content are required."]);
            exit;
        }

        // Verify post exists
        $postFile = $postsDir . $postId . '.json';
        $post = readJson($postFile);
        if (!$post) {
            echo json_encode(["status" => "error", "message" => "Post not found."]);
            exit;
        }

        $timestamp = date('c'); // ISO 8601
        $commentId = generateId();

        $comment = [
            "comment_id"  => $commentId,
            "post_id"     => $postId,
            "post_title"  => $post['title'],
            "author"      => $author,
            "content"     => $content,
            "timestamp"   => $timestamp
        ];

        // 1. Save to post-specific comments file
        $commentsFile = commentFileForPost($postId);
        $comments = readJson($commentsFile);
        if (!is_array($comments)) $comments = [];
        $comments[] = $comment;
        writeJson($commentsFile, $comments);

        // 2. Save to author's comments file (so post author can see all comments on their posts)
        $postAuthor = $post['author'];
        $authorFile = authorCommentsFile($postAuthor);
        $authorComments = readJson($authorFile);
        if (!is_array($authorComments)) $authorComments = [];
        $authorComments[] = $comment;
        writeJson($authorFile, $authorComments);

        // 3. Save to commenter's comments file (so commenter can see their own comments)
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

    // ────────────────────────────────────────────
    // GET COMMENTS BY POST AUTHOR
    // ────────────────────────────────────────────
    case 'get_comments_by_author':
        $username = $input['username'] ?? $_GET['username'] ?? '';
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Username is required."]);
            exit;
        }

        $authorFile = authorCommentsFile($username);
        $comments = readJson($authorFile);
        if (!is_array($comments)) $comments = [];

        // Sort newest first
        usort($comments, function($a, $b) {
            return strcmp($b['timestamp'] ?? '', $a['timestamp'] ?? '');
        });

        echo json_encode(["status" => "success", "comments" => $comments]);
        break;

    // ────────────────────────────────────────────
    // GET COMMENTS BY COMMENTING USER
    // ────────────────────────────────────────────
    case 'get_comments_by_user':
        $username = $input['username'] ?? $_GET['username'] ?? '';
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Username is required."]);
            exit;
        }

        $commenterFile = commenterCommentsFile($username);
        $comments = readJson($commenterFile);
        if (!is_array($comments)) $comments = [];

        // Sort newest first
        usort($comments, function($a, $b) {
            return strcmp($b['timestamp'] ?? '', $a['timestamp'] ?? '');
        });

        echo json_encode(["status" => "success", "comments" => $comments]);
        break;

    // ────────────────────────────────────────────
    // CREATE A NEW POST (for testing/demo)
    // ────────────────────────────────────────────
    case 'create_post':
        $title   = trim($input['title'] ?? '');
        $content = trim($input['content'] ?? '');
        $author  = trim($input['author'] ?? '');

        if (!$title || !$content || !$author) {
            echo json_encode(["status" => "error", "message" => "title, content, and author are required."]);
            exit;
        }

        $postId = 'post_' . time();
        $post = [
            "id"        => $postId,
            "title"     => $title,
            "content"   => $content,
            "author"    => $author,
            "timestamp" => date('c')
        ];

        $postFile = $postsDir . $postId . '.json';
        if (file_exists($postFile)) {
            echo json_encode(["status" => "error", "message" => "Post already exists (duplicate time)."]);
            exit;
        }

        writeJson($postFile, $post);

        // ── ADD LOGGING AFTER SUCCESSFUL POST CREATION ──
        JWTSecurity::logUserAction($author, "Created a new forum thread entry: '" . $title . "'");

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
