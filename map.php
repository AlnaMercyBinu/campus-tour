<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Virtual Campus Tour - Map</title>
  <link rel="stylesheet" href="style.css">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
  <header>
    <h1>Campus Location Features</h1>
    <div class="auth-buttons">
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <span class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="logout.php" class="auth-button">Logout</a>
      <?php else: ?>
        <a href="login.php" class="auth-button">Login</a>
        <a href="register.php" class="auth-button">Register</a>
      <?php endif; ?>
    </div>
  </header>

  <?php if ($success): ?>
    <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  
  <?php if ($error): ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <main>
    <!-- Geolocation Info -->
    <section style="text-align:center;">
      <h2>📍 Your Location</h2>
      <button class="menu-button" onclick="getLocation()">Get My Location</button>
      <div id="geoOutput"></div>
    </section>

    <!-- Distance Calculator -->
    <section style="text-align:center;">
      <h2>📏 Distance to Pillai Campus</h2>
      <button class="menu-button" onclick="calculateDistance()">Calculate Distance</button>
      <div id="distanceOutput"></div>
    </section>

    <!-- 360 View -->
    <section style="text-align:center;">
      <h2>🌐 360° View</h2>
      <iframe
        src="https://www.google.com/maps/embed?pb=!4v1712833927385!6m8!1m7!1sCAoSLEFGMVFpcFBnclhOZVF4N3dOaGhKbFRLY2k3c0RrM19jYUNrZm9oSUZpWnR4!2m2!1d18.9898!2d73.1278!3f0!4f0!5f0.7820865974627469"
        width="90%" height="300" style="border:0; border-radius:20px; box-shadow:0 0 20px cyan;"
        allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </section>

    <!-- Mini Map -->
    <section style="text-align:center;">
      <h2>🗺️ Campus Location Map</h2>
      <div id="map"></div>
    </section>

    <!-- Info Popups -->
    <section>
      <div class="popup-box">The Pillai Campus houses multiple institutions like PCE, PCACS, PiCA, and PIMSR.</div>
      <div class="popup-box">Our campus is located in New Panvel, well connected to rail and road transport.</div>
      <div class="popup-box">Visit us and experience top-notch infrastructure and vibrant student life!</div>
    </section>

    <section style="text-align: center; margin-top: 1rem;">
      <button class="menu-button" onclick="goBack()">← Back to Home</button>
    </section>
  </main>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="script.js"></script>
</body>
</html>