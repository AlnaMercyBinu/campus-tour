<?php
session_start();
require_once 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
$token = $_GET['token'] ?? '';

// Redirect if already logged in
if (isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($token) || empty($password) || empty($confirm_password)) {
        header("Location: reset-password.php?token=$token&error=All fields are required");
        exit();
    }
    
    if ($password !== $confirm_password) {
        header("Location: reset-password.php?token=$token&error=Passwords do not match");
        exit();
    }
    
    if (strlen($password) < 8) {
        header("Location: reset-password.php?token=$token&error=Password must be at least 8 characters");
        exit();
    }
    
    try {
        // Verify token
        $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();
        
        if (!$reset) {
            header("Location: reset-password.php?token=$token&error=Invalid or expired token");
            exit();
        }
        
        // Update password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ? WHERE email = ?")
            ->execute([$hashedPassword, $reset['email']]);
            
        // Delete the used token
        $pdo->prepare("DELETE FROM password_resets WHERE token = ?")
            ->execute([$token]);
            
        header("Location: login.php?success=Password has been reset successfully");
        exit();
        
    } catch (PDOException $e) {
        error_log("Reset password error: " . $e->getMessage());
        header("Location: reset-password.php?token=$token&error=Database error occurred");
        exit();
    }
}

// Verify token for GET request
if (!empty($token)) {
    try {
        $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();
        
        if (!$reset) {
            $error = "Invalid or expired token";
            $token = ''; // Clear invalid token
        }
    } catch (PDOException $e) {
        error_log("Token verification error: " . $e->getMessage());
        $error = "Error verifying token";
        $token = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Virtual Campus Tour</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Background Video -->
  <video autoplay muted loop id="bgVideo">
    <source src="video.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <header>
    <h1>Reset Password</h1>
  </header>

  <main>
    <div class="form-container">
      <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
      <?php endif; ?>
      
      <?php if ($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
      <?php endif; ?>

      <?php if (!empty($token)): ?>
        <form action="reset-password.php" method="POST">
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
          
          <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" required>
            <small>Minimum 8 characters, at least one letter and one number</small>
          </div>
          
          <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
          </div>
          
          <button type="submit" class="form-submit">Reset Password</button>
        </form>
      <?php else: ?>
        <div class="info-message">
          <p>Please request a new password reset link from the <a href="forgot-password.php">forgot password</a> page.</p>
        </div>
      <?php endif; ?>
      
      <div class="form-footer">
        <p>Remember your password? <a href="login.php">Login here</a></p>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p><a href="index.php">Back to Home</a></p>
      </div>
    </div>
  </main>

  <script src="script.js"></script>
</body>
</html>