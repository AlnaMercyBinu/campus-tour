
<?php
session_start();
require_once 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        if ($action === 'login') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                header("Location: login.php?error=Email and password are required");
                exit();
            }
            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['logged_in'] = true;
                
                // Update last login
                $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")
                    ->execute([$user['id']]);
                
                header("Location: index.php?success=Login successful");
                exit();
            } else {
                header("Location: login.php?error=Invalid email or password");
                exit();
            }
        } 
        elseif ($action === 'register') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validation
            $errors = [];
            if (empty($name)) $errors[] = "Name is required";
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
            if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters";
            if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
                $errors[] = "Password must contain letters and numbers";
            }
            if ($password !== $confirm_password) $errors[] = "Passwords do not match";
            
            if (!empty($errors)) {
                header("Location: register.php?error=" . urlencode(implode(", ", $errors)));
                exit();
            }
            
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                header("Location: register.php?error=Email already registered");
                exit();
            }
            
            // Insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashedPassword]);
            
            // Auto-login
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['logged_in'] = true;
            
            header("Location: index.php?success=Registration successful! You are now logged in.");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Auth error: " . $e->getMessage());
        header("Location: login.php?error=Database error occurred");
        exit();
    }
}

// After successful login:
$pdo->prepare("UPDATE users SET 
    last_login = NOW(),
    login_count = IFNULL(login_count, 0) + 1
WHERE id = ?")->execute([$user['id']]);

// After successful login:
$pdo->prepare("UPDATE users SET last_login = NOW(), login_count = login_count + 1 WHERE id = ?")
    ->execute([$_SESSION['user_id']]);


header("Location: index.php");
exit();
?>