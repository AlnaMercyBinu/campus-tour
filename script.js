// Canvas Animation with logo and lines
window.addEventListener("load", () => {
  const canvas = document.getElementById("tourCanvas");
  if (canvas) {
    const ctx = canvas.getContext("2d");
    const logo = new Image();
    logo.src = "pillai.png";

    let angle = 0;

    function drawVR() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.save();
      ctx.translate(canvas.width / 2, canvas.height / 2);
      ctx.rotate(angle);

      // Draw animated lines
      for (let i = 0; i < 10; i++) {
        ctx.beginPath();
        ctx.moveTo(0, 0);
        ctx.lineTo(0, 150 - i * 10);
        ctx.strokeStyle = `hsl(${angle * 100 + i * 36}, 100%, 70%)`;
        ctx.lineWidth = 2;
        ctx.stroke();
        ctx.rotate(Math.PI / 5);
      }

      ctx.restore();

      // Draw logo in center
      const logoSize = 150;
      ctx.drawImage(
        logo,
        canvas.width / 2 - logoSize / 2,
        canvas.height / 2 - logoSize / 2,
        logoSize,
        logoSize
      );

      angle += 0.01;
      requestAnimationFrame(drawVR);
    }

    logo.onload = drawVR;
  }
});

// Gallery Logic
function openModal(imgSrc) {
  document.getElementById('imageModal').style.display = 'block';
  document.getElementById('modalImg').src = imgSrc;
}

function closeModal() {
  document.getElementById('imageModal').style.display = 'none';
}

const collegeOptions = {
  regular: ['Labs', 'Classrooms', 'Libraries'],
  common: ['Canteen', 'Quad', 'Atrium', 'Old Canteen', 'Auditorium', 'Conclave', 'Grounds and Gymkhana']
};

function showSubMenu(college) {
  document.getElementById('mainMenu').style.display = 'none';
  document.getElementById('subMenu').style.display = 'block';
  document.getElementById('selectedCollege').textContent = college;

  const subMenuOptions = document.getElementById('subMenuOptions');
  subMenuOptions.innerHTML = '';












  function showGallery(college, section) {
    const gallery = document.getElementById('gallery');
    gallery.innerHTML = '<div class="loading">Loading images...</div>';
    
    // Format names consistently
    const folderName = college.toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')  // Convert to kebab-case
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '');
    
    const sectionName = section.toLowerCase();
  
    // Create image container
    gallery.innerHTML = '';
    
    // Load 3 images for the section
    for (let i = 1; i <= 3; i++) {
      const imgPath = `images/${folderName}/${sectionName}${i}.jpg`;
      const img = new Image();
      
      img.onload = function() {
        const item = document.createElement('div');
        item.className = 'gallery-item';
        item.innerHTML = `
          <img src="${imgPath}" 
               alt="${college} ${section} ${i}" 
               onclick="openModal('${imgPath}')">
          <div class="gallery-content">
            <h3>${section} ${i}</h3>
            <p>${getDescription(folderName, sectionName, i)}</p>
          </div>
        `;
        gallery.appendChild(item);
      };
      
      img.onerror = function() {
        console.error("Failed to load image:", imgPath);
        // Display placeholder if image fails to load
        if (i === 1) {  // Only show one placeholder per section
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
  
  // Helper function for descriptions
  function getDescription(college, section, index) {
    const descriptions = {
      'pillai-college-of-engineering-pce': {
        'labs': [
          'Computer Lab with 50 workstations',
          'Electrical Engineering Lab',
          'Mechanical Workshop'
        ],
        'classrooms': [
          'Lecture Hall A',
          'Seminar Room B',
          'Tutorial Room C'
        ]
      }
      // Add more descriptions as needed
    };
    
    return descriptions[college]?.[section]?.[index-1] || 
           `Image of ${section} at ${college.replace(/-/g, ' ')}`;
  }



  function logout() {
    fetch('logout.php', {
        method: 'POST',
        credentials: 'same-origin'
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        }
    });
}

// Add click handler to logout buttons
document.querySelectorAll('.logout-button').forEach(button => {
    button.addEventListener('click', logout);
});











// Add these functions to script.js
function openCollegePage(college) {
  const encodedCollege = encodeURIComponent(college);
  window.open(`college.php?college=${encodedCollege}`, '_blank');
}

function formatName(name) {
  return name.toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/-+/g, '-')
    .replace(/^-|-$/g, '');
}




















  collegeOptions.regular.forEach(option => {
    const button = document.createElement('button');
    button.className = 'menu-button';
    button.textContent = option;
    button.onclick = () => showGallery(college, option);
    subMenuOptions.appendChild(button);
  });
}

function showCommonAreas() {
  document.getElementById('mainMenu').style.display = 'none';
  document.getElementById('subMenu').style.display = 'block';
  document.getElementById('selectedCollege').textContent = 'Common Areas';

  const subMenuOptions = document.getElementById('subMenuOptions');
  subMenuOptions.innerHTML = '';

  collegeOptions.common.forEach(option => {
    const button = document.createElement('button');
    button.className = 'menu-button';
    button.textContent = option;
    button.onclick = () => showGallery('Common', option);
    subMenuOptions.appendChild(button);
  });
}

function showMainMenu() {
  document.getElementById('mainMenu').style.display = 'block';
  document.getElementById('subMenu').style.display = 'none';
  document.getElementById('gallery').innerHTML = '';
}

function formatName(name) {
  return name.toLowerCase().replace(/[^a-z0-9]/g, '');
}

function showGallery(college, section) {
  const gallery = document.getElementById('gallery');
  gallery.innerHTML = '';

  for (let i = 1; i <= 3; i++) {
    const item = document.createElement('div');
    item.className = 'gallery-item';
    item.innerHTML = `
      <img src="images/${formatName(college)}/${section.toLowerCase()}${i}.jpg" 
           alt="${college} ${section} ${i}" 
           onclick="openModal(this.src)">
      <div class="gallery-content">
        <h3>${section} ${i}</h3>
        <p>Description of ${college} ${section} ${i}</p>
      </div>
    `;
    gallery.appendChild(item);
  }
}

// Map Page Functions
function goBack() {
  window.location.href = "index.php";
}

const campusLat = 18.9898;
const campusLng = 73.1278;

function calculateDistance() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((pos) => {
      const lat = pos.coords.latitude;
      const lon = pos.coords.longitude;

      const R = 6371;
      const dLat = (campusLat - lat) * Math.PI / 180;
      const dLon = (campusLng - lon) * Math.PI / 180;
      const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat * Math.PI / 180) * Math.cos(campusLat * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      const d = R * c;

      document.getElementById("distanceOutput").innerHTML =
        `<p>You are <strong>${d.toFixed(2)} km</strong> away from Pillai Campus.</p>`;
    });
  }
}

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((pos) => {
      document.getElementById("geoOutput").innerHTML =
        `<p><strong>Latitude:</strong> ${pos.coords.latitude} <br><strong>Longitude:</strong> ${pos.coords.longitude}</p>`;
    });
  } else {
    document.getElementById("geoOutput").innerHTML = "Geolocation not supported.";
  }
}

// Initialize Leaflet Map if on map page
if (document.getElementById("map")) {
  document.addEventListener("DOMContentLoaded", function () {
    const map = L.map("map").setView([campusLat, campusLng], 17);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    L.marker([campusLat, campusLng])
      .addTo(map)
      .bindPopup("📍 Dr. K. M. Vasudevan Pillai Campus")
      .openPopup();
  });
}
// Add this to your existing script.js
document.addEventListener('DOMContentLoaded', function() {
  // Display any messages for 5 seconds then fade out
  const messages = document.querySelectorAll('.success-message, .error-message');
  messages.forEach(message => {
    setTimeout(() => {
      message.style.transition = 'opacity 1s';
      message.style.opacity = '0';
      setTimeout(() => message.remove(), 1000);
    }, 5000);
  });
});

// Add this to your script.js
function checkPasswordStrength(password) {
  // At least 8 characters, contains letters and numbers
  const hasLetters = /[a-zA-Z]/.test(password);
  const hasNumbers = /[0-9]/.test(password);
  const isLongEnough = password.length >= 8;
  
  if (isLongEnough && hasLetters && hasNumbers) {
    if (password.length >= 12) return 'strong';
    return 'medium';
  }
  return 'weak';
}

document.addEventListener('DOMContentLoaded', function() {
  // Password strength indicator
  const passwordInputs = document.querySelectorAll('input[type="password"][name="password"]');
  passwordInputs.forEach(input => {
    const strengthDiv = document.createElement('div');
    strengthDiv.className = 'password-strength';
    strengthDiv.innerHTML = '<span></span>';
    input.parentNode.insertBefore(strengthDiv, input.nextSibling);
    
    input.addEventListener('input', function() {
      const strength = checkPasswordStrength(this.value);
      strengthDiv.className = 'password-strength ' + strength;
    });
  });
});
// Track tour completion
function logTourCompletion() {
  fetch('update-activity.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'action=complete_tour'
  });
}

// Track favorite locations
function setFavoriteLocation(location) {
  fetch('update-activity.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `action=set_favorite&location=${encodeURIComponent(location)}`
  });
}

// Add these near your existing tracking functions in script.js

// Track when a tour section is viewed
function trackTourSection(sectionName) {
  setFavoriteLocation(sectionName); // Track as potential favorite
  logTourCompletion(); // Count as tour progress
  
  // Update UI
  document.querySelectorAll('.menu-button').forEach(btn => {
    if (btn.textContent.trim() === sectionName) {
      btn.classList.add('completed');
      btn.innerHTML += ' <span class="checkmark">✓</span>';
    }
  });
}

// Call this when a section is opened
function showGallery(college, section) {
  trackTourSection(section); // Track that this section was viewed
  // ... rest of your existing showGallery code ...
}

// Enhanced time tracking with pause/resume
let timeSpentInterval;
let isTrackingTime = false;

function startTimeTracking() {
  if (isTrackingTime) return;
  
  isTrackingTime = true;
  let minutes = 0;
  
  timeSpentInterval = setInterval(() => {
    minutes++;
    if (minutes % 5 === 0) {
      updateTimeSpent(5);
    }
  }, 60000); // 1 minute interval
}

function pauseTimeTracking() {
  clearInterval(timeSpentInterval);
  isTrackingTime = false;
}

function updateTimeSpent(minutes) {
  fetch('update-activity.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `action=update_time&minutes=${minutes}`
  }).catch(err => console.error('Time tracking error:', err));
}

// Start/stop tracking when page gains/loses focus
document.addEventListener('visibilitychange', () => {
  if (document.visibilityState === 'visible') {
    startTimeTracking();
  } else {
    pauseTimeTracking();
  }
});

// Initialize tracking when DOM loads
document.addEventListener('DOMContentLoaded', () => {
  startTimeTracking();
  
  // Add click handlers to all menu buttons
  document.querySelectorAll('.menu-button').forEach(button => {
    button.addEventListener('click', function() {
      const sectionName = this.textContent.trim();
      trackTourSection(sectionName);
    });
  });
});



// Start tracking when page loads
document.addEventListener('DOMContentLoaded', startTimeTracking);

// Add to script.js
document.querySelectorAll('.password-toggle').forEach(toggle => {
  toggle.addEventListener('click', function() {
      const input = this.previousElementSibling;
      input.type = input.type === 'password' ? 'text' : 'password';
      this.textContent = input.type === 'password' ? '👁️' : '👁️‍🗨️';
  });
});

// Add this function to script.js
function openCollegePage(college) {
  // Encode the college name for URL
  const encodedCollege = encodeURIComponent(college);
  // Open in new tab
  window.open(`college.php?college=${encodedCollege}`, '_blank');
}

function openCollegePage(college, initialSection = null) {
  let url = `college.php?college=${encodeURIComponent(college)}`;
  if (initialSection) {
    url += `&section=${encodeURIComponent(initialSection)}`;
  }
  window.open(url, '_blank');
}






// Update the modal functions in college.php
let currentModalImg = null;

function openModal(imgSrc) {
  const modal = document.getElementById('imageModal');
  const modalImg = document.getElementById('modalImg');
  
  modal.style.display = 'block';
  modalImg.src = imgSrc;
  currentModalImg = imgSrc;
  
  // Enable fullscreen toggle on click
  modal.onclick = function() {
    toggleFullscreen();
  };
  
  // Close modal when clicking close button
  document.querySelector('.close').onclick = function(e) {
    e.stopPropagation();
    closeModal();
  };
  
  // Close modal with ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeModal();
    }
  });
}

function closeModal() {
  document.getElementById('imageModal').style.display = 'none';
  exitFullscreen();
  currentModalImg = null;
}

function toggleFullscreen() {
  const modal = document.getElementById('imageModal');
  const modalImg = document.getElementById('modalImg');
  
  if (modal.classList.contains('fullscreen')) {
    exitFullscreen();
  } else {
    enterFullscreen();
  }
}

function enterFullscreen() {
  const modal = document.getElementById('imageModal');
  modal.classList.add('fullscreen');
  
  // For browsers that support fullscreen API
  if (modal.requestFullscreen) {
    modal.requestFullscreen();
  } else if (modal.webkitRequestFullscreen) { /* Safari */
    modal.webkitRequestFullscreen();
  } else if (modal.msRequestFullscreen) { /* IE11 */
    modal.msRequestFullscreen();
  }
}

function exitFullscreen() {
  const modal = document.getElementById('imageModal');
  modal.classList.remove('fullscreen');
  
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.webkitExitFullscreen) { /* Safari */
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) { /* IE11 */
    document.msExitFullscreen();
  }
}

// Add this at the top with other variables
let currentCollege = null;
let currentSection = null;

// Replace your existing showGallery function with this:
function showGallery(college, section) {
  currentCollege = college;
  currentSection = section;
  
  // Track this view
  trackTourProgress(college, section);
  
  // Rest of your existing gallery code...
  document.getElementById('mainMenu').style.display = 'none';
  document.getElementById('subMenu').style.display = 'none';
  document.getElementById('gallery').style.display = 'block';
  
  // Load gallery content...
}

// Add this new function to handle progress tracking
function trackTourProgress(college, section) {
  // Mark as completed in UI
  markSectionCompleted(section);
  
  // Send to server
  fetch('update-activity.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `action=complete_section&college=${encodeURIComponent(college)}&section=${encodeURIComponent(section)}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      updateProgressUI(data.progress);
    }
  })
  .catch(error => console.error('Tracking error:', error));
}

// Add these helper functions
function markSectionCompleted(section) {
  document.querySelectorAll('.menu-button').forEach(button => {
    if (button.textContent.trim() === section) {
      button.classList.add('completed');
      if (!button.querySelector('.checkmark')) {
        const checkmark = document.createElement('span');
        checkmark.className = 'checkmark';
        checkmark.textContent = ' ✓';
        button.appendChild(checkmark);
      }
    }
  });
}

function updateProgressUI(progress) {
  const progressElement = document.getElementById('tour-progress');
  if (progressElement) {
    progressElement.textContent = `Progress: ${progress.completed}/${progress.total} sections`;
    const progressBar = document.querySelector('.progress-fill');
    if (progressBar) {
      const percentage = (progress.completed / progress.total) * 100;
      progressBar.style.width = `${percentage}%`;
    }
  }
}

// Initialize progress tracking when page loads
document.addEventListener('DOMContentLoaded', () => {
  // Load initial progress
  fetch('update-activity.php?action=get_progress')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        updateProgressUI(data.progress);
        // Mark completed sections
        data.completed_sections.forEach(section => {
          markSectionCompleted(section);
        });
      }
    });
});

// Add these functions to your script.js
function trackTourProgress(college, section) {
  fetch('update-activity.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `action=complete_section&college=${encodeURIComponent(college)}&section=${encodeURIComponent(section)}`
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          updateProgressUI(data.progress);
      }
  });
}

function updateProgressUI(progress) {
  const progressElement = document.getElementById('tour-progress');
  if (progressElement) {
      progressElement.textContent = `Progress: ${progress.completed}/${progress.total} sections`;
  }
  
  const progressBar = document.querySelector('.progress-fill');
  if (progressBar) {
      const percentage = (progress.completed / progress.total) * 100;
      progressBar.style.width = `${percentage}%`;
  }
}

// Initialize progress when page loads
document.addEventListener('DOMContentLoaded', function() {
  // Load initial progress
  fetch('update-activity.php?action=get_progress')
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              updateProgressUI(data.progress);
          }
      });
});

// Modify your showGallery function to track progress
function showGallery(college, section) {
  trackTourProgress(college, section);
  // ... rest of your existing showGallery code ...
}
// Add this to your script.js
function initCalendar() {
  const calendarEl = document.createElement('table');
  calendarEl.id = 'calendar';
  
  // Get current date
  const date = new Date();
  const year = date.getFullYear();
  const month = date.getMonth();
  const today = date.getDate();
  
  // Create calendar header
  const monthNames = ["January", "February", "March", "April", "May", "June",
                     "July", "August", "September", "October", "November", "December"];
  
  const header = document.createElement('thead');
  const headerRow = document.createElement('tr');
  const headerCell = document.createElement('th');
  headerCell.colSpan = 7;
  headerCell.textContent = `${monthNames[month]} ${year}`;
  headerRow.appendChild(headerCell);
  header.appendChild(headerRow);
  calendarEl.appendChild(header);
  
  // Create day names row
  const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  const dayRow = document.createElement('tr');
  dayNames.forEach(day => {
    const th = document.createElement('th');
    th.textContent = day;
    dayRow.appendChild(th);
  });
  calendarEl.appendChild(dayRow);
  
  // Create calendar body
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  
  let dateCell = 1;
  const tbody = document.createElement('tbody');
  
  for (let i = 0; i < 6; i++) {
    // Stop if we've run out of days
    if (dateCell > daysInMonth) break;
    
    const row = document.createElement('tr');
    
    for (let j = 0; j < 7; j++) {
      const cell = document.createElement('td');
      
      if (i === 0 && j < firstDay) {
        // Empty cells before first day
        cell.textContent = '';
      } else if (dateCell > daysInMonth) {
        // Empty cells after last day
        cell.textContent = '';
      } else {
        // Date cells
        cell.textContent = dateCell;
        if (dateCell === today) {
          cell.classList.add('today');
        }
        dateCell++;
      }
      
      row.appendChild(cell);
    }
    
    tbody.appendChild(row);
  }
  
  calendarEl.appendChild(tbody);
  
  // Insert calendar into the page
  const calendarContainer = document.querySelector('#calendar');
  if (calendarContainer) {
    calendarContainer.innerHTML = '';
    calendarContainer.appendChild(calendarEl);
  }
}

// Initialize calendar when page loads
document.addEventListener('DOMContentLoaded', function() {
  initCalendar();
  
  // Add auto-scroll to notice board
  const noticeBoard = document.querySelector('.notice-board');
  if (noticeBoard) {
    let scrollPosition = 0;
    setInterval(() => {
      if (scrollPosition < noticeBoard.scrollHeight - noticeBoard.clientHeight) {
        scrollPosition += 1;
        noticeBoard.scrollTop = scrollPosition;
      } else {
        scrollPosition = 0;
        noticeBoard.scrollTop = 0;
      }
    }, 100);
  }
});
