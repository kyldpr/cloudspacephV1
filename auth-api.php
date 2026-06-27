<?php
// Enable strict error reporting to handle any hidden hosting issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

// 1. Native Environment File (.env) Parser Logic
$envPath = __DIR__ . '/.env';

if (!file_exists($envPath)) {
    echo json_encode(["status" => "error", "message" => "Critical: Configuration profile initialization failed (.env file missing)."]);
    exit;
}

$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    
    list($name, $value) = explode('=', $line, 2);
    $name = trim($name);
    $value = trim($value);
    
    if (!array_key_exists($name, $_ENV)) {
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
    }
}

// 2. Database Connection Hookup
$host     = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbname   = $_ENV['DB_NAME'] ?? '';
$username = $_ENV['DB_USER'] ?? '';
$password = $_ENV['DB_PASS'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database connection failure: " . $e->getMessage()]);
    exit;
}

// 3. Define the Local Profile Backup Storage Settings
$baseDir = __DIR__ . '/api/profiles/users/';
if (!file_exists($baseDir)) {
    mkdir($baseDir, 0777, true); // Create directory safely if it drops out
}

// 4. Reject direct browser entry page loads (GET)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

// 5. Process raw stream data payload input
$inputData = json_decode(file_get_contents("php://input"), true);
$action = $inputData['action'] ?? '';

switch ($action) {
    case 'register':
        $userIn = trim($inputData['username'] ?? '');
        $passIn = trim($inputData['password'] ?? '');
        
        $phoneIn       = isset($inputData['phone_number']) ? intval($inputData['phone_number']) : null;
        $accountTypeIn = isset($inputData['account_type']) ? intval($inputData['account_type']) : 1; 
        $emailIn       = trim($inputData['email'] ?? '');

        if (empty($userIn) || empty($passIn)) {
            echo json_encode(["status" => "error", "message" => "Username and Password are required variables."]);
            exit;
        }

        try {
            $checkStmt = $pdo->prepare("SELECT id FROM cloudspaceph_users WHERE LOWER(username) = LOWER(?)");
            $checkStmt->execute([$userIn]);
            if ($checkStmt->fetch()) {
                echo json_encode(["status" => "error", "message" => "Username already taken."]);
                exit;
            }

            $hashedPassword = password_hash($passIn, PASSWORD_BCRYPT);
            $currentDate = date("Y-m-d");

            $insertSql = "INSERT INTO cloudspaceph_users 
                (username, passoword, phone_number, date_created, date_updated, account_type, email) 
                VALUES (?, ?, ?, ?, NULL, ?, ?)";
            
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->execute([
                $userIn, 
                $hashedPassword, 
                $phoneIn, 
                $currentDate, 
                $accountTypeIn, 
                $emailIn
            ]);

            // Formulate data storage matrix completely excluding the password hash
            $userCleanData = [
                "username"     => $userIn,
                "email"        => $emailIn,
                "phone_number" => $phoneIn,
                "account_type" => $accountTypeIn,
                "date_created" => $currentDate
            ];

            // Save JSON Profile without password using plain username filename
            $userFilePath = $baseDir . strtolower($userIn) . '.json';
            file_put_contents($userFilePath, json_encode($userCleanData, JSON_PRETTY_PRINT));

            echo json_encode([
                "status" => "success",
                "message" => "Registration successful. User database sync and JSON profiles created.",
                "user" => $userCleanData
            ]);

        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database operational failure: " . $e->getMessage()]);
        }
        break;

    case 'login':
        $userIn = trim($inputData['username'] ?? '');
        $passIn = trim($inputData['password'] ?? '');

        if (empty($userIn) || empty($passIn)) {
            echo json_encode(["status" => "error", "message" => "All fields are required."]);
            exit;
        }

        try {
            $loginStmt = $pdo->prepare("SELECT id, username, passoword, phone_number, account_type, email, date_created FROM cloudspaceph_users WHERE LOWER(username) = LOWER(?)");
            $loginStmt->execute([$userIn]);
            $userRow = $loginStmt->fetch();

            if ($userRow && password_verify($passIn, $userRow['passoword'])) {
                // Strip password parameter immediately
                unset($userRow['passoword']);
                
                // Keep the fetched user cache file up to date for later lookups
                $userFilePath = $baseDir . strtolower($userIn) . '.json';
                file_put_contents($userFilePath, json_encode($userRow, JSON_PRETTY_PRINT));
                
                echo json_encode([
                    "status" => "success", 
                    "message" => "Login successful.",
                    "user" => $userRow
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid username or password configuration."]);
            }

        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database verification exception occurred: " . $e->getMessage()]);
        }
        break;

    // ─── NEW: CHANGE PASSWORD ───
    case 'change_password':
        $username = trim($inputData['username'] ?? '');
        $current  = trim($inputData['current_password'] ?? '');
        $new      = trim($inputData['new_password'] ?? '');

        if (empty($username) || empty($current) || empty($new)) {
            echo json_encode(["status" => "error", "message" => "All fields are required."]);
            exit;
        }

        // Enforce minimum length (optional)
        if (strlen($new) < 8) {
            echo json_encode(["status" => "error", "message" => "New password must be at least 8 characters."]);
            exit;
        }

        try {
            $stmt = $pdo->prepare("SELECT passoword FROM cloudspaceph_users WHERE LOWER(username) = LOWER(?)");
            $stmt->execute([$username]);
            $row = $stmt->fetch();

            if (!$row || !password_verify($current, $row['passoword'])) {
                echo json_encode(["status" => "error", "message" => "Current password is incorrect."]);
                exit;
            }

            $newHash = password_hash($new, PASSWORD_BCRYPT);
            $update = $pdo->prepare("UPDATE cloudspaceph_users SET passoword = ? WHERE LOWER(username) = LOWER(?)");
            $update->execute([$newHash, $username]);

            // Optionally update the JSON profile (but we don't store password there)
            // so no need to touch the file.

            echo json_encode(["status" => "success", "message" => "Password updated successfully."]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action specified."]);
        break;
}