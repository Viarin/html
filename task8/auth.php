<?php
session_start();

// Set session timeout to 30 minutes
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);

// File to store users
$users_file = 'users.json';

// Create users file if it doesn't exist
if (!file_exists($users_file)) {
    $initial_data = [
        'admin' => [
            'username' => 'Administrator',
            'password' => password_hash('admin', PASSWORD_DEFAULT)
        ]
    ];
    file_put_contents($users_file, json_encode($initial_data));
}

// Handle all requests
$action = $_GET['action'] ?? '';

switch($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $users = json_decode(file_get_contents($users_file), true);
            
            if (isset($users[$email]) && password_verify($password, $users[$email]['password'])) {
                $_SESSION['user_id'] = $email;
                $_SESSION['username'] = $users[$email]['username'];
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
            
            $users = json_decode(file_get_contents($users_file), true);
            
            if (isset($users[$email])) {
                echo json_encode(['success' => false, 'message' => 'Email already exists']);
            } else {
                $users[$email] = [
                    'username' => $username,
                    'password' => $password
                ];
                file_put_contents($users_file, json_encode($users));
                
                // Create session for new user
                $_SESSION['user_id'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['last_activity'] = time();
                
                echo json_encode(['success' => true, 'username' => $username]);
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
            echo json_encode([
                'logged_in' => true, 
                'username' => $_SESSION['username']
            ]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
