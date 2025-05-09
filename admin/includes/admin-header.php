<?php
/**
 * Header component for admin section
 * Includes admin-specific styling and navigation
 */

// Check if admin is logged in (placeholder for authentication)
// In a real application, you would implement proper authentication
$isLoggedIn = true; // For demo purposes
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Admin Dashboard' : 'Admin Dashboard'; ?></title>
    <link rel="stylesheet" href="<?php echo $adminBaseUrl; ?>/assets/css/admin-style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="container admin-header-container">
            <div class="admin-logo">
                <a href="<?php echo $adminBaseUrl; ?>index.php">
                    <span><img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/11888.png" alt="Sword Icon" width="28" height="28"></span>
                    L1J-R DB Admin
                </a>
            </div>
            <nav class="admin-nav">
				<div class="admin-nav-item">
                    <a href="<?php echo $baseUrl; ?>index.php" class="<?php echo $currentAdminPage === 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Main Website
                    </a>
                </div>
                <div class="admin-nav-item">
                    <a href="<?php echo $adminBaseUrl; ?>index.php" class="<?php echo $currentAdminPage === 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </div>
                <div class="admin-user">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/admin.png" alt="Admin User" width="28" height="28">
                    <span class="admin-user-name">Admin</span>
                </div>
            </nav>
        </div>
    </header>
