<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


function requireGuest() {
    if (isset($_SESSION['logged_in'])) {
        header("Location: index.php");
        exit();
    }
}
// Add to session.php
function logout() {
    session_start();
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
    exit();
}function requireLogin() {
    session_start();
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        // Store requested URL for redirect after login
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header("Location: login.php?error=Please login first");
        exit();
    }
    
    // Regenerate session ID periodically for security
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) { // 30 minutes
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}