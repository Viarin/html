<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'login_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Set session timeout to 30 minutes
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);

// Handle all requests
$action = $_GET['action'] ?? '';

switch($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['last_activity'] = time();
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
            }
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $password]);
                echo json_encode(['success' => true]);
            } catch(PDOException $e) {
                $message = $e->getCode() == 23000 ? 'Email already exists' : 'Registration failed';
                echo json_encode(['success' => false, 'message' => $message]);
            }
        }
        break;

    case 'logout':
        session_destroy();
        echo json_encode(['success' => true]);
        break;

    case 'check_session':
        if (!isset($_SESSION['user_id']) || 
            (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800))) {
            session_destroy();
            echo json_encode(['logged_in' => false]);
        } else {
            $_SESSION['last_activity'] = time();
            $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            echo json_encode(['logged_in' => true, 'username' => $user['username']]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
