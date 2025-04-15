<?php
session_start();
require_once 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

// Redirect if already logged in
if (isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    if (empty($email)) {
        header("Location: forgot-password.php?error=Email is required");
        exit();
    }
    
    try {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            header("Location: forgot-password.php?error=Email not found");
            exit();
        }
        
        // Generate token and expiration
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Delete any existing tokens for this email
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")
            ->execute([$email]);
            
        // Insert new token
        $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)")
            ->execute([$email, $token, $expires]);
        
        // Send email (in a real app, you would send an actual email)
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset-password.php?token=$token";
        
        // For demo purposes, we'll just show the link
        $success = "Password reset link has been generated. For demo purposes, here's your link: <a href='$resetLink'>Reset Password</a>";
        
    } catch (PDOException $e) {
        error_log("Forgot password error: " . $e->getMessage());
        header("Location: forgot-password.php?error=Database error occurred");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - Virtual Campus Tour</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Background Video -->
  <video autoplay muted loop id="bgVideo">
    <source src="video.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <header>
    <h1>Forgot Password</h1>
  </header>

  <main>
    <div class="form-container">
      <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
      <?php endif; ?>
      
      <?php if ($success): ?>
        <div class="success-message"><?php echo $success; ?></div>
      <?php endif; ?>

      <form action="forgot-password.php" method="POST">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required>
        </div>
        
        <button type="submit" class="form-submit">Send Reset Link</button>
      </form>
      
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