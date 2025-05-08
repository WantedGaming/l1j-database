<?php
// Include common functions and authentication
require_once __DIR__ . '/../includes/functions/common.php';
require_once __DIR__ . '/../includes/functions/auth.php';

// Start session
startSession();

// Check if already logged in
if (isLoggedIn()) {
    // Redirect to dashboard or requested page
    if (isset($_SESSION['redirect_after_login'])) {
        $redirect = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);
        redirect($redirect);
    } else {
        redirect('/admin/');
    }
}

// Generate CSRF token
$csrfToken = generateCsrfToken();

// Process login form submission
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $error = 'Invalid form submission. Please try again.';
    } else {
        // Get form data
        $username = isset($_POST['username']) ? sanitize($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        // Authenticate user
        if (authenticate($username, $password)) {
            // Redirect to dashboard or requested page
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                redirect($redirect);
            } else {
                redirect('/admin/');
            }
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | L1J Remastered DB</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/admin.css">
</head>
<body class="admin-body">
    <div class="login-container">
        <div class="login-form">
            <div class="login-header">
                <h1>L1J Remastered</h1>
                <p>Admin Login</p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>
            
            <div class="login-footer">
                <p><a href="/">Back to Site</a></p>
            </div>
        </div>
    </div>
    
    <script src="/public/js/main.js"></script>
</body>
</html>
