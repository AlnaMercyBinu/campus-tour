<?php
session_start();
require_once 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$college = $_GET['college'] ?? '';
$section = $_GET['section'] ?? '';

$validColleges = [
    'Pillai College of Engineering (PCE)',
    'Pillai College of Arts, Commerce and Science (PCACS)',
    'Pillai College of Architecture (PiCA)',
    'Pillai Institute of Management Studies and Research (PIMSR)',
    'Common Areas'
];

if (!in_array($college, $validColleges)) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($college); ?> - Virtual Campus Tour</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .sub-menu {
      display: block !important;
    }
    .gallery-item img {
      transition: transform 0.3s ease;
    }
    .gallery-item:hover img {
      transform: scale(1.05);
    }
  </style>
</head>
<body>
  <video autoplay muted loop id="bgVideo">
    <source src="video2.mp4" type="video/mp4">
  </video>

  <header>
    <h1><?php echo htmlspecialchars($college); ?></h1>
    <div class="auth-buttons">
      <span class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="logout.php" class="auth-button">Logout</a>
    </div>
  </header>

  <main>
    <section class="sub-menu" id="subMenu">
      <button class="back-button" onclick="window.history.back();">← Back</button>
      <h2>Explore <?php echo htmlspecialchars($college); ?></h2>
      <div class="menu-options" id="subMenuOptions"></div>
    </section>

    <section class="gallery" id="gallery"></section>
  </main>

  <div class="modal" id="imageModal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImg">
    <div class="modal-nav">
      <button class="nav-button prev" onclick="navigateGallery(-1)">❮</button>
      <button class="nav-button next" onclick="navigateGallery(1)">❯</button>
    </div>
  </div>

  <script>
    // College data
    const collegeData = {
      college: "<?php echo $college; ?>",
      options: {
        regular: ['Labs', 'Classrooms', 'Libraries'],
        common: ['Canteen', 'Quad', 'Atrium', 'Old Canteen', 'Auditorium', 'Conclave', 'Grounds and Gymkhana']
      },
      currentImages: [],
      currentIndex: 0
    };

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      loadSubMenu();
      <?php if (!empty($section)): ?>
        showGallery(collegeData.college, "<?php echo $section; ?>");
      <?php endif; ?>
    });

    function loadSubMenu() {
      const subMenuOptions = document.getElementById('subMenuOptions');
      subMenuOptions.innerHTML = '';
      
      const options = collegeData.college === 'Common Areas' ? 
        collegeData.options.common : collegeData.options.regular;
      
      options.forEach(option => {
        const button = document.createElement('button');
        button.className = 'menu-button';
        button.textContent = option;
        button.onclick = () => showGallery(collegeData.college, option);
        subMenuOptions.appendChild(button);
      });
    }

    function showGallery(college, section) {
      const gallery = document.getElementById('gallery');
      gallery.innerHTML = '<div class="loading">Loading images...</div>';
      
      const folderName = formatName(college);
      const sectionName = section.toLowerCase();
      collegeData.currentImages = [];
      
      gallery.innerHTML = '';
      
      // Load 3 images for the section
      for (let i = 1; i <= 3; i++) {
        const imgPath = `images/${folderName}/${sectionName}${i}.jpg`;
        collegeData.currentImages.push(imgPath);
        
        const img = new Image();
        img.onload = function() {
          const item = document.createElement('div');
          item.className = 'gallery-item';
          item.innerHTML = `
            <img src="${imgPath}" 
                 alt="${college} ${section} ${i}" 
                 onclick="openModal('${imgPath}', ${i-1})">
            <div class="gallery-content">
              <h3>${section} ${i}</h3>
              <p>${getDescription(folderName, sectionName, i)}</p>
            </div>
          `;
          gallery.appendChild(item);
        };
        img.onerror = function() {
          console.error("Image not found:", imgPath);
          if (i === 1) {
            gallery.innerHTML = `
              <div class="error-message">
                Images not found for ${college} - ${section}.<br>
                Expected path: images/${folderName}/${sectionName}1-3.jpg
              </div>
            `;
          }
        };
        img.src = imgPath;
      }
    }

    function formatName(name) {
      return name.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
    }

    function getDescription(college, section, index) {
      const descriptions = {
        'pillai-college-of-engineering-pce': {
          'labs': [
            'Computer Lab with 50 workstations',
            'Electrical Engineering Lab',
            'Mechanical Workshop'
          ]
        }
      };
      return descriptions[college]?.[section]?.[index-1] || 
             `${section} at ${college.replace(/-/g, ' ')}`;
    }

    function openModal(imgSrc, index = 0) {
      const modal = document.getElementById('imageModal');
      const modalImg = document.getElementById('modalImg');
      
      collegeData.currentIndex = index;
      modal.style.display = 'block';
      modalImg.src = imgSrc;
      
      modal.onclick = function(e) {
        if (e.target === modal || e.target === modalImg) {
          toggleFullscreen();
        }
      };
      
      document.addEventListener('keydown', handleKeyPress);
    }

    function closeModal() {
      document.getElementById('imageModal').style.display = 'none';
      exitFullscreen();
      document.removeEventListener('keydown', handleKeyPress);
    }

    function handleKeyPress(e) {
      if (e.key === 'Escape') closeModal();
      if (e.key === 'ArrowRight') navigateGallery(1);
      if (e.key === 'ArrowLeft') navigateGallery(-1);
    }

    function navigateGallery(direction) {
      if (collegeData.currentImages.length === 0) return;
      
      collegeData.currentIndex = (collegeData.currentIndex + direction + collegeData.currentImages.length) % collegeData.currentImages.length;
      document.getElementById('modalImg').src = collegeData.currentImages[collegeData.currentIndex];
    }

    function toggleFullscreen() {
      const modal = document.getElementById('imageModal');
      if (modal.classList.contains('fullscreen')) {
        exitFullscreen();
      } else {
        enterFullscreen();
      }
    }

    function enterFullscreen() {
      const modal = document.getElementById('imageModal');
      modal.classList.add('fullscreen');
      if (modal.requestFullscreen) modal.requestFullscreen();
    }

    function exitFullscreen() {
      const modal = document.getElementById('imageModal');
      modal.classList.remove('fullscreen');
      if (document.exitFullscreen) document.exitFullscreen();
    }
  </script>
</body>
</html>