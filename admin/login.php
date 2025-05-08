<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = 'Admin Login';
$is_admin = true;
$error_message = '';

// Check if session timed out
if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    $error_message = 'Your session has expired. Please log in again.';
}

// Check if already logged in
if (isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($username) || empty($password)) {
        $error_message = 'Please enter both username and password.';
    } else {
        $user = authenticateUser($username, $password);
        
        if ($user && hasAdminAccess($user)) {
            createAdminSession($user);
            
            // Update last active timestamp
            $conn = getDbConnection();
            $username = $conn->real_escape_string($username);
            $updateQuery = "UPDATE accounts SET lastactive = NOW() WHERE login = '$username'";
            $conn->query($updateQuery);
            
            // Redirect to admin dashboard
            header('Location: index.php');
            exit;
        } else {
            $error_message = 'Invalid credentials or insufficient permissions.';
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="dashboard-card">
                <div class="card-header text-center">
                    <h2 class="mb-0"><i class="fas fa-lock me-2"></i> Admin Login</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i> <?= $error_message ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="post" action="login.php" class="admin-form">
                        <div class="mb-4">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary-custom border-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Enter your username" required autofocus>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary-custom border-0">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Enter your password" required>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <a href="../index.php" class="text-accent">
                        <i class="fas fa-arrow-left me-1"></i> Back to Site
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
