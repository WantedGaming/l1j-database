<?php
/**
 * Authentication check for admin pages
 * Include this file at the top of all admin pages that require authentication
 */
require_once '../includes/functions.php';

// Check if user is logged in as admin
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Optional: Check session timeout (e.g., 30 minutes of inactivity)
$timeout = 1800; // 30 minutes in seconds
if (isset($_SESSION['admin_last_activity']) && (time() - $_SESSION['admin_last_activity'] > $timeout)) {
    // Session has expired
    endAdminSession();
    
    // Redirect to login with timeout message
    header('Location: login.php?timeout=1');
    exit;
}

// Update last activity time
$_SESSION['admin_last_activity'] = time();
