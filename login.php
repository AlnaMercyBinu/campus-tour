<?php
session_start();

// At top of login.php
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] > 5) {
  header("Location: login.php?error=Too many login attempts. Try again later.");
  exit();
}

// In auth.php after failed login
$_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to index if already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Virtual Campus Tour</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Background Video -->
  <video autoplay muted loop id="bgVideo">
    <source src="video2.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <header>
    <h1>Login to Virtual Campus Tour</h1>
  </header>

  <main>
    <div class="form-container">
      <?php if (isset($_GET['error'])): ?>
        <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>
      
      <?php if (isset($_GET['success'])): ?>
        <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
      <?php endif; ?>

      <form action="auth.php" method="POST">
        <input type="hidden" name="action" value="login">
        
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="form-submit">Login</button>
      </form>
      
      <div class="form-footer">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p><a href="index.php">Back to Home</a></p>
      </div>
      <div class="form-footer">
  <p>Forgot your password? <a href="forgot-password.php">Reset it here</a></p>
  <p>Don't have an account? <a href="register.php">Register here</a></p>
  <p><a href="index.php">Back to Home</a></p>
</div>
    </div>
  </main>

  <script src="script.js"></script>
</body>
</html>
