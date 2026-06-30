<?php
// Disable HTML error output so JSON doesn't break
error_reporting(0);
ini_set('display_errors', 0);

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

// 2. Define the Local Profile Backup Storage Settings (needed for all actions)
$baseDir = __DIR__ . '/api/profiles/users/';
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0755, true);
}

// ── Always include JWT helper ──
require_once __DIR__ . '/api/jwt-helper.php';

// 3. Reject direct browser entry page loads (GET)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

// 4. Process raw stream data payload input
$inputData = json_decode(file_get_contents("php://input"), true);
$action = $inputData['action'] ?? '';

// ── Helper: get database connection (only when needed) ──
function getDbConnection() {
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
        return $pdo;
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database connection failure: " . $e->getMessage()]);
        exit;
    }
}

// ── Rate limiting helper ──
function checkRateLimit($key, $maxAttempts = 5, $timeWindow = 60) {
    $rateFile = sys_get_temp_dir() . '/rate_' . md5($key) . '.json';
    $data = [];
    if (file_exists($rateFile)) {
        $data = json_decode(file_get_contents($rateFile), true);
        if (!is_array($data)) $data = [];
    }
    $now = time();
    // Clean old attempts
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

// ── Username validation helper ──
function isValidUsername($name) {
    return preg_match('/^[a-zA-Z0-9._@-]+$/', $name) === 1;
}

// ─── SWITCH CASES ───
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

        if (!isValidUsername($userIn)) {
            echo json_encode(["status" => "error", "message" => "Username contains invalid characters."]);
            exit;
        }

        try {
            $pdo = getDbConnection(); // connect only for this action

            $checkStmt = $pdo->prepare("SELECT id FROM cloudspaceph_users WHERE LOWER(username) = LOWER(?)");
            $checkStmt->execute([$userIn]);
            if ($checkStmt->fetch()) {
                echo json_encode(["status" => "error", "message" => "Username already taken."]);
                exit;
            }

            // Check if email already exists
            $emailStmt = $pdo->prepare("SELECT id FROM cloudspaceph_users WHERE LOWER(email) = LOWER(?)");
            $emailStmt->execute([$emailIn]);
            if ($emailStmt->fetch()) {
                echo json_encode(["status" => "error", "message" => "Email already registered."]);
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

        if (!isValidUsername($userIn)) {
            echo json_encode(["status" => "error", "message" => "Invalid username format."]);
            exit;
        }

        // Rate limiting for login
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if (!checkRateLimit($ip . '_login', 5, 60)) {
            echo json_encode(["status" => "error", "message" => "Too many attempts. Please wait a minute."]);
            exit;
        }

        try {
            $pdo = getDbConnection(); // connect only for this action

            $loginStmt = $pdo->prepare("SELECT id, username, passoword, phone_number, account_type, email, date_created FROM cloudspaceph_users WHERE LOWER(username) = LOWER(?)");
            $loginStmt->execute([$userIn]);
            $userRow = $loginStmt->fetch();

            if ($userRow && password_verify($passIn, $userRow['passoword'])) {
                // Strip password parameter immediately
                unset($userRow['passoword']);
                
                // Keep the fetched user cache file up to date for later lookups
                $userFilePath = $baseDir . strtolower($userIn) . '.json';
                file_put_contents($userFilePath, json_encode($userRow, JSON_PRETTY_PRINT));

                // Add JWT and logging
                $token = JWTSecurity::generateToken($userRow['username']);
                JWTSecurity::logUserAction($userRow['username'], "User logged into account successfully via web portal.");

                echo json_encode([
                    "status" => "success", 
                    "message" => "Login successful.",
                    "token" => $token,
                    "user" => $userRow
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid username or password configuration."]);
            }

        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database verification exception occurred: " . $e->getMessage()]);
        }
        break;

    // ─── CHANGE PASSWORD (secured with JWT + rate limit) ───
    case 'change_password':
        // 1. Authenticate via token (same as get_profile)
        $username = JWTSecurity::authenticate(); // returns username or exits with 401

        if (!isValidUsername($username)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Invalid username in token."]);
            exit;
        }

        // Rate limiting for password change
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if (!checkRateLimit($ip . '_change_password', 5, 60)) {
            echo json_encode(["status" => "error", "message" => "Too many attempts. Please wait a minute."]);
            exit;
        }

        // 2. Read input (now we know the token user is valid)
        $current = trim($inputData['current_password'] ?? '');
        $new     = trim($inputData['new_password'] ?? '');
        // (do not use $inputData['username'] – use $username from token)

        if (empty($current) || empty($new)) {
            echo json_encode(["status" => "error", "message" => "Current and new password are required."]);
            exit;
        }
        if (strlen($new) < 8) {
            echo json_encode(["status" => "error", "message" => "New password must be at least 8 characters."]);
            exit;
        }

        try {
            $pdo = getDbConnection();
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

            echo json_encode(["status" => "success", "message" => "Password updated successfully."]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
        break;

    // ─── GET PROFILE (no DB needed) ───
    case 'get_profile':
        // Authenticate via JWT – returns username or exits with 401
        $username = JWTSecurity::authenticate();

        if (!isValidUsername($username)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Invalid username in token."]);
            exit;
        }

        $profileFile = $baseDir . strtolower($username) . '.json';
        if (!file_exists($profileFile)) {
            echo json_encode(["status" => "error", "message" => "Profile not found."]);
            exit;
        }

        $userData = json_decode(file_get_contents($profileFile), true);
        if (!is_array($userData)) {
            echo json_encode(["status" => "error", "message" => "Invalid profile data."]);
            exit;
        }

        // Filter out sensitive/internal fields
        $safeData = [
            'username'      => $userData['username'] ?? $username,
            'email'         => $userData['email'] ?? '',
            'phone_number'  => $userData['phone_number'] ?? null,
            'date_created'  => $userData['date_created'] ?? ''
        ];

        echo json_encode([
            "status" => "success",
            "user"   => $safeData
        ]);
        break;

    // ─── GET LOGS (no DB needed) ───
    case 'get_logs':
        $token = $inputData['token'] ?? '';
        if (!$token) {
            echo json_encode(["status" => "error", "message" => "Token required."]);
            exit;
        }
        $username = JWTSecurity::validateToken($token);
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
            exit;
        }

        if (!isValidUsername($username)) {
            echo json_encode(["status" => "error", "message" => "Invalid username in token."]);
            exit;
        }

        $logsFile = $baseDir . 'logs/' . strtolower($username) . '.json';
        $logs = [];
        if (file_exists($logsFile)) {
            $content = file_get_contents($logsFile);
            $logs = json_decode($content, true);
            if (!is_array($logs)) $logs = [];
        }

        // Filter out logs older than 3 days
        $threeDaysAgo = strtotime('-3 days');
        $filteredLogs = array_filter($logs, function($log) use ($threeDaysAgo) {
            $timestamp = strtotime($log['timestamp']);
            return $timestamp >= $threeDaysAgo;
        });
        $filteredLogs = array_values($filteredLogs); // re-index

        // Save filtered logs back (auto-purge)
        file_put_contents($logsFile, json_encode($filteredLogs, JSON_PRETTY_PRINT));

        echo json_encode([
            "status" => "success",
            "logs"   => $filteredLogs
        ]);
        break;

    // ─── DELETE A SPECIFIC LOG ENTRY ───
    case 'delete_log':
        $token = $inputData['token'] ?? '';
        if (!$token) {
            echo json_encode(["status" => "error", "message" => "Token required."]);
            exit;
        }
        $username = JWTSecurity::validateToken($token);
        if (!$username) {
            echo json_encode(["status" => "error", "message" => "Invalid or expired token."]);
            exit;
        }
        if (!isValidUsername($username)) {
            echo json_encode(["status" => "error", "message" => "Invalid username in token."]);
            exit;
        }

        $timestamp = $inputData['timestamp'] ?? '';
        if (!$timestamp) {
            echo json_encode(["status" => "error", "message" => "Timestamp is required."]);
            exit;
        }

        $logsFile = $baseDir . 'logs/' . strtolower($username) . '.json';
        if (!file_exists($logsFile)) {
            echo json_encode(["status" => "error", "message" => "No logs found."]);
            exit;
        }

        $logs = json_decode(file_get_contents($logsFile), true);
        if (!is_array($logs)) {
            echo json_encode(["status" => "error", "message" => "Invalid log data."]);
            exit;
        }

        // Filter out the log with the given timestamp
        $newLogs = array_filter($logs, function($log) use ($timestamp) {
            return $log['timestamp'] !== $timestamp;
        });

        if (count($newLogs) === count($logs)) {
            echo json_encode(["status" => "error", "message" => "Log entry not found."]);
            exit;
        }

        // Re-index and save
        file_put_contents($logsFile, json_encode(array_values($newLogs), JSON_PRETTY_PRINT));

        echo json_encode(["status" => "success", "message" => "Log entry deleted."]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action specified."]);
        break;
}