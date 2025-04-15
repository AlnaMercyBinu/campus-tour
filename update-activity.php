<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$response = ['success' => false];

try {
    switch ($action) {
    // Add these cases to your existing switch statement
case 'complete_section':
    $college = $_POST['college'] ?? '';
    $section = $_POST['section'] ?? '';
    
    // Record section visit if not already visited
    $stmt = $pdo->prepare("INSERT IGNORE INTO user_progress (user_id, college, section) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $college, $section]);
    
    // Get updated progress count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_progress WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $progressCount = $stmt->fetchColumn();
    
    // Update user's tour count
    $pdo->prepare("UPDATE users SET tours_completed = ?, last_tour_date = NOW() WHERE id = ?")
        ->execute([$progressCount, $_SESSION['user_id']]);
    
    $response = [
        'success' => true,
        'progress' => [
            'completed' => $progressCount,
            'total' => 15 // Adjust this to your total number of sections
        ]
    ];
    break;

case 'get_progress':
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_progress WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $progressCount = $stmt->fetchColumn();
    
    $response = [
        'success' => true,
        'progress' => [
            'completed' => $progressCount,
            'total' => 15
        ],
        'completed_sections' => [] // You can populate this if needed
    ];
    break;
    }
} catch (PDOException $e) {
    error_log("Activity error: " . $e->getMessage());
    $response['error'] = 'Database error';
}


echo json_encode($response);

function getProgressData($pdo, $userId) {
    // Get total sections available (adjust this number as needed)
    $totalSections = 15;
    
    // Get user's completed sections
    $stmt = $pdo->prepare("SELECT section FROM user_progress WHERE user_id = ?");
    $stmt->execute([$userId]);
    $completed = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    return [
        'progress' => [
            'completed' => count($completed),
            'total' => $totalSections
        ],
        'completed_sections' => $completed
    ];
}