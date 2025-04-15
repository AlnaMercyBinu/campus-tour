<?php
session_start();
require_once 'db.php';
requireLogin();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $start_date = $_POST['start_date'] ?? date('Y-m-d');
    $end_date = $_POST['end_date'] ?? null;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO notices (title, content, start_date, end_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $start_date, $end_date]);
        $success = "Notice added successfully!";
    } catch (PDOException $e) {
        $error = "Error adding notice: " . $e->getMessage();
    }
}

// Get all notices
$notices = $pdo->query("SELECT * FROM notices ORDER BY start_date DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Notices</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Manage Notices</h1>
    </header>
    
    <main>
        <div class="form-container">
            <h2>Add New Notice</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="form-group">
                    <label for="end_date">End Date (optional)</label>
                    <input type="date" id="end_date" name="end_date">
                </div>
                
                <button type="submit" class="form-submit">Add Notice</button>
            </form>
        </div>
        
        <div class="widget" style="margin-top: 2rem;">
            <h2>Current Notices</h2>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding:8px; background:rgba(0,255,255,0.1);">Title</th>
                        <th style="padding:8px; background:rgba(0,255,255,0.1);">Dates</th>
                        <th style="padding:8px; background:rgba(0,255,255,0.1);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($notices as $notice): ?>
                    <tr>
                        <td style="padding:8px; border-bottom:1px solid rgba(0,255,255,0.2);"><?= htmlspecialchars($notice['title']) ?></td>
                        <td style="padding:8px; border-bottom:1px solid rgba(0,255,255,0.2);">
                            <?= date('M j, Y', strtotime($notice['start_date'])) ?>
                            <?= $notice['end_date'] ? ' - '.date('M j, Y', strtotime($notice['end_date'])) : '' ?>
                        </td>
                        <td style="padding:8px; border-bottom:1px solid rgba(0,255,255,0.2);">
                            <a href="edit-notice.php?id=<?= $notice['id'] ?>">Edit</a> |
                            <a href="delete-notice.php?id=<?= $notice['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>