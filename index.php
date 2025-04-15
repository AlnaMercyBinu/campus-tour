<?php
session_start();
// Add these at the very top of your file (before any output)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect to home if not logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: home.php");
    exit();
}

require_once 'db.php';

// Fetch user data
$user = [];
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}

// Handle success/error messages
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// In index.php, before HTML:
  $events = $pdo->query("SELECT * FROM events WHERE event_date > NOW() ORDER BY event_date LIMIT 3")->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Virtual Campus Tour - Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Background Video -->
  <video autoplay muted loop id="bgVideo">
    <source src="video2.mp4" type="video/mp4" />
    Your browser does not support the video tag.
  </video>

  <header>
    <h1>Welcome to Virtual Campus Tour</h1>
    <div class="auth-buttons">
      <span class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="logout.php" class="auth-button">Logout</a>
    </div>
  </header>

  <?php if ($success): ?>
    <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  
  <?php if ($error): ?>
    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <!-- Dashboard Widgets -->
  <section class="dashboard-widgets">
    <div class="widget">
      <h3>Your Recent Activity</h3>
      <ul>
        <li>Last login: <?php echo isset($user['last_login']) ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'First login'; ?></li>
        <li>Tours completed: <?php echo isset($user['tours_completed']) ? $user['tours_completed'] : '0'; ?></li>
      </ul>
    </div>
    
    <div class="widget">
      <h3>Quick Links</h3>
      <a href="map.php" class="quick-link">🗺️ Interactive Map</a>
      <br> 
      <a href="assets/guides/Pillai_Campus_Guide.pdf" 
   class="download-btn" 
   download="Pillai_Tour_Guide.pdf">
   📖 Download Tour Guide
</a>

    </div>
 
 
  </section>


  <!-- Main Menu -->
  <main>
    <section id="mainMenu" class="main-menu">
      <h2>Explore Our Campus</h2>

      

     <!-- Change the buttons in index.php -->
<div class="menu-options">
  <button class="menu-button" onclick="openCollegePage('Pillai College of Engineering (PCE)')">
    Pillai College of Engineering (PCE)
  </button>
  <button class="menu-button" onclick="openCollegePage('Pillai College of Arts, Commerce and Science (PCACS)')">
    Pillai College of Arts, Commerce and Science (PCACS)
  </button>
  <button class="menu-button" onclick="openCollegePage('Pillai College of Architecture (PiCA)')">
    Pillai College of Architecture (PiCA)
  </button>
  <button class="menu-button" onclick="openCollegePage('Pillai Institute of Management Studies and Research (PIMSR)')">
    Pillai Institute of Management Studies and Research (PIMSR)
  </button>
  <button class="menu-button" onclick="openCollegePage('Common Areas')">
    Common Areas
  </button>
</div>
      


    </section>

    <!-- Sub Menu -->
    <section class="sub-menu" id="subMenu">
      <button class="back-button" onclick="showMainMenu()">← Back</button>
      <h2 id="selectedCollege"></h2>
      <div class="menu-options" id="subMenuOptions"></div>
    </section>

    <!-- Gallery -->
    <section class="gallery" id="gallery"></section>

    <section class="dashboard-widgets" style="margin-top: 2rem;">
  <!-- Calendar Widget -->
  <div class="widget">
    <h3>Academic Calendar</h3>
    <div id="calendar"></div>
    <div class="calendar-events">
      <?php foreach($events as $event): ?>
        <div class="calendar-event">
          <strong><?= date('M j', strtotime($event['event_date'])) ?></strong>
          <span><?= htmlspecialchars($event['title']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Notice Board Widget -->
  <div class="widget">
    <h3>Notice Board</h3>
    <div class="notice-board">
      <?php if(empty($notices)): ?>
        <p>No current notices</p>
      <?php else: ?>
        <ul class="notice-list">
          <?php foreach($notices as $notice): ?>
            <li>
              <h4><?= htmlspecialchars($notice['title']) ?></h4>
              <p><?= htmlspecialchars($notice['content']) ?></p>
              <small>Posted: <?= date('M j, Y', strtotime($notice['created_at'])) ?></small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</section>


  </main>

  <!-- Modal -->
  <div class="modal" id="imageModal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImg" />
  </div>





  <script src="script.js"></script>
</body>
</html>