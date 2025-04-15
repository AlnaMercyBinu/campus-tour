
<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to index if already logged in
if (isset($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit();
}

// Handle success/error messages
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Virtual Campus Tour</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Background Video -->
  <video autoplay muted loop id="bgVideo">
    <source src="video2.mp4" type="video/mp4" />
    Your browser does not support the video tag.
  </video>

  <header>
    <h1>Virtual Campus Tour</h1>
    <p>Explore your future in VR style!</p>
    <div class="auth-buttons">
      <a href="login.php" class="auth-button">Login</a>
      <a href="register.php" class="auth-button">Register</a>
    </div>
  </header>

  <?php if ($success): ?>
    <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  
  <?php if ($error): ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <!-- VR Canvas -->
  <canvas id="tourCanvas" width="300" height="200"></canvas>

  <!-- Campus Tour Video -->
  <section style="text-align: center; margin: 2rem 0;">
    <h2>Campus Overview Video</h2>
    <video width="200" controls autoplay muted loop style="border-radius: 20px; box-shadow: 0 0 20px cyan;">
      <source src="video.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>

    <section style="text-align:center; margin-top: 2rem;">
      <a href="map.php">
        <button class="menu-button">📍 Explore Location Features</button>
      </a>
    </section>
  </section>

  <main>
    <section class="features">
      <h2>Discover Our Campus</h2>
      <div class="feature-grid">
        <div class="feature-card">
          <h3>Virtual Tours</h3>
          <p>Explore our campus from anywhere in the world with immersive 360° views.</p>
        </div>
        <div class="feature-card">
          <h3>Interactive Map</h3>
          <p>Navigate our campus with our detailed interactive map.</p>
        </div>
        <div class="feature-card">
          <h3>Facility Information</h3>
          <p>Learn about our state-of-the-art facilities and resources.</p>
        </div>
      </div>
    </section>

    <section class="cta">
      <h2>Ready to Explore?</h2>
      <div class="cta-buttons">
        <a href="register.php" class="cta-button">Create Account</a>
        <a href="login.php" class="cta-button">Login</a>
      </div>
    </section>
  </main>

  <script src="script.js"></script>
</body>
</html>