<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


// Redirect if already logged in
if (isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit();
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Virtual Campus Tour</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Background Video -->
  <video autoplay muted loop id="bgVideo">
    <source src="video2.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <header>
    <h1>Register for Virtual Campus Tour</h1>
  </header>

  <main>
    <div class="form-container">
      <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      
      <form action="auth.php" method="POST" onsubmit="return validateRegistration()">
        <input type="hidden" name="action" value="register">
        
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
          <small>Minimum 8 characters, at least one letter and one number</small>
        </div>
        
        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="form-submit">Register</button>
      </form>
      
      <div class="form-footer">
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <p><a href="index.php">Back to Home</a></p>
      </div>
    </div>
  </main>

  <script>
    function validateRegistration() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      
      // Password validation
      if (password.length < 8) {
        alert('Password must be at least 8 characters long');
        return false;
      }
      
      if (!/[a-zA-Z]/.test(password) || !/[0-9]/.test(password)) {
        alert('Password must contain at least one letter and one number');
        return false;
      }
      
      if (password !== confirmPassword) {
        alert('Passwords do not match');
        return false;
      }
      
      return true;
    }
  </script>
</body>
</html>