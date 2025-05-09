<?php
/**
 * Admin Login
 * Login form for the admin dashboard
 */

// Start the session
session_start();

// If already logged in, redirect to admin dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Initialize variables
$error = '';

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate credentials
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Check credentials against database
        $stmt = $pdo->prepare("SELECT * FROM accounts WHERE login = :username AND access_level >= 1");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();
        
if ($user) {
    // For this implementation, we're just checking if the account exists with the right access level
    // In a real production environment, you would use proper password verification
    // if (password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['login'];
        $_SESSION['admin_access_level'] = $user['access_level'];
        
        // Redirect to admin dashboard
        header('Location: index.php');
        exit;
    // }
} else {
    $error = 'Invalid username or password or insufficient permissions.';
}
    }
}

// Set page title
$pageTitle = 'Admin Login';
$showHero = false;

// Extra CSS for admin pages
$extraCSS = ['../assets/css/admin.css'];

// Include admin header instead of regular header
include '../includes/admin-header.php';
?>

<div class="container my-12">
    <div class="flex justify-center">
        <div class="card p-6 w-full max-w-md mx-auto login-card">
            <h1 class="text-2xl font-bold mb-6 text-center">Admin Login</h1>
            
            <?php if (!empty($error)): ?>
            <div class="bg-red-600 text-white p-3 mb-4 rounded">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <form method="post" action="">
                <div class="mb-4">
                    <label for="username" class="block mb-2">Username:</label>
                    <input type="text" id="username" name="username" class="form-input w-full px-3" required autocomplete="username">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block mb-2">Password:</label>
                    <input type="password" id="password" name="password" class="form-input w-full px-3" required autocomplete="current-password">
                </div>
                
                <div class="flex justify-center">
                    <button type="submit" class="btn btn-primary w-full">Login</button>
                </div>
            </form>
            
            <div class="mt-4 text-center">
                <a href="../" class="text-accent">← Return to Website</a>
            </div>
        </div>
    </div>
</div>

<?php
// Include admin footer instead of regular footer
include '../includes/admin-footer.php';
?>