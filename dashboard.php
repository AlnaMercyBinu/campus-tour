<?php


// Get user activity data
$stmt = $pdo->prepare("SELECT 
    tours_completed, 
    favorite_location, 
    total_time_spent,
    last_tour_date,
    last_activity
    FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$userData = $stmt->fetch();

// Calculate completion percentage (example: 10 sections total)
$sectionsTotal = 10;
$completionPercent = min(100, ($userData['tours_completed'] / $sectionsTotal) * 100);
?>


</head>
<body>
    <!-- Include your existing header/navigation -->

    <main>
        <h2>My Tour Progress</h2>
        
        <div class="dashboard-widgets">
            <div class="widget">
                <h3>Completion Progress</h3>
                <div class="progress-container">
                    <div><?= round($completionPercent) ?>% complete</div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $completionPercent ?>%"></div>
                    </div>
                    <small><?= $userData['tours_completed'] ?> of <?= $sectionsTotal ?> sections visited</small>
                </div>
            </div>
            
            <div class="widget">
                <h3>Time Spent</h3>
                <p><?= ceil($userData['total_time_spent'] / 60) ?> hours exploring</p>
            </div>
            
            <div class="widget">
                <h3>Favorite Location</h3>
                <p><?= $userData['favorite_location'] ? htmlspecialchars($userData['favorite_location']) : 'Not set yet' ?></p>
            </div>
        </div>
        
        <div class="widget activity-feed">
            <h3>Recent Activity</h3>
            <div class="activity-item">
                Last tour section: <?= $userData['last_tour_date'] ? date('M j, Y g:i a', strtotime($userData['last_tour_date'])) : 'Never' ?>
            </div>
            <div class="activity-item">
                Last active: <?= $userData['last_activity'] ? date('M j, Y g:i a', strtotime($userData['last_activity'])) : 'Never' ?>
            </div>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>