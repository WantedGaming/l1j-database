<?php
/**
 * Authentication Functions
 * Handles user authentication for L1J Remastered Database Browser admin area
 */

/**
 * Start a session if not already started
 */
function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Check if a user is logged in
 * @return bool True if logged in, false otherwise
 */
function isLoggedIn() {
    startSession();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Authenticate a user with username and password
 * @param string $username Username
 * @param string $password Password
 * @return bool True if authentication successful, false otherwise
 */
function authenticate($username, $password) {
    // For demonstration purposes, hard-coded credentials
    // In a production environment, this should use database authentication
    $validUsername = 'admin';
    $validPasswordHash = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Check if username is valid
    if ($username !== $validUsername) {
        return false;
    }
    
    // Verify password
    if (password_verify($password, $validPasswordHash)) {
        // Set session variables
        startSession();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_last_active'] = time();
        
        return true;
    }
    
    return false;
}

/**
 * Log out the current user
 */
function logout() {
    startSession();
    
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    redirect('/admin/login.php');
}

/**
 * Require authentication for protected pages
 * If not authenticated, redirect to login page
 */
function requireLogin() {
    if (!isLoggedIn()) {
        // Store the requested URL for redirection after login
        startSession();
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        
        // Redirect to login page
        redirect('/admin/login.php');
    }
    
    // Check for session timeout
    if (isset($_SESSION['admin_last_active']) && (time() - $_SESSION['admin_last_active'] > 3600)) {
        // Session expired (1 hour of inactivity)
        logout();
    }
    
    // Update last active time
    $_SESSION['admin_last_active'] = time();
}

/**
 * Check if the current user has a specific permission
 * @param string $permission Permission to check
 * @return bool True if has permission, false otherwise
 */
function hasPermission($permission) {
    // For demonstration purposes, all authenticated users have all permissions
    return isLoggedIn();
}

/**
 * Get the currently logged in username
 * @return string|null Username if logged in, null otherwise
 */
function getCurrentUsername() {
    startSession();
    return isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : null;
}

/**
 * Generate a CSRF token and store it in the session
 * @return string CSRF token
 */
function generateCsrfToken() {
    startSession();
    
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    
    return $token;
}

/**
 * Validate a CSRF token against the stored token
 * @param string $token Token to validate
 * @return bool True if valid, false otherwise
 */
function validateCsrfToken($token) {
    startSession();
    
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    $valid = hash_equals($_SESSION['csrf_token'], $token);
    
    // Consume the token after validation
    unset($_SESSION['csrf_token']);
    
    return $valid;
}
?>