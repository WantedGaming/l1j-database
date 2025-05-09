<?php
/**
 * Admin Header Template
 * This file contains the header HTML used across admin pages
 */

// Security check - prevent unauthorized access
if (!isset($_SESSION['admin_logged_in']) && 
    basename($_SERVER['PHP_SELF']) != 'login.php' && 
    !strpos($_SERVER['REQUEST_URI'], 'login.php')) {
    header('Location: ../admin/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - L1J Database Admin' : 'L1J Database Admin'; ?></title>
    <?php 
    // Determine if we're in a subdirectory within admin
    $rootPath = "";
    $adminPath = "";
    if (strpos($_SERVER['PHP_SELF'], '/admin/weapons/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/admin/armor/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/admin/items/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/admin/monsters/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/admin/maps/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/admin/npcs/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/admin/skills/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/admin/dolls/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/admin/polymorph/') !== false) {
        $rootPath = "../../";
        $adminPath = "../";
    } else {
        $rootPath = "../";
        $adminPath = "";
    }
    ?>
    <link rel="stylesheet" href="<?php echo $rootPath; ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo $rootPath; ?>assets/css/admin.css">
    <?php if (isset($extraCSS)): ?>
        <?php foreach ($extraCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo (strpos($css, 'http') === 0) ? $css : $rootPath . ltrim(str_replace('../', '', $css), '/'); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="admin-body">
    <header class="admin-header header">
        <div class="container header-container">
            <div class="admin-logo-container">
                <a href="<?php echo $rootPath; ?>index.php" class="logo">L1J <span>Database</span></a>
                <span class="admin-badge">Admin Panel</span>
            </div>
            
            <nav class="admin-nav">
                <div class="nav-item">
                    <a href="<?php echo $adminPath; ?>index.php" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/icons/icons/40128.png" alt="Dashboard" class="nav-icon">
                        Dashboard
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="<?php echo $adminPath; ?>weapons/" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/icons/icons/47.png" alt="Weapons" class="nav-icon">
                        Weapons
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="<?php echo $adminPath; ?>armor/" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/icons/icons/20322.png" alt="Armor" class="nav-icon">
                        Armor
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="<?php echo $adminPath; ?>items/" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/icons/icons/40308.png" alt="Items" class="nav-icon">
                        Items
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="<?php echo $adminPath; ?>monsters/" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/icons/icons/7.png" alt="Monsters" class="nav-icon">
                        Monsters
                    </a>
                </div>
                
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/icons/icons/40126.png" alt="More" class="nav-icon">
                        More
                    </a>
                    <div class="dropdown-content">
                        <a href="<?php echo $adminPath; ?>maps/" class="dropdown-item">Maps</a>
                        <a href="<?php echo $adminPath; ?>npcs/" class="dropdown-item">NPCs</a>
                        <a href="<?php echo $adminPath; ?>skills/" class="dropdown-item">Skills</a>
                        <a href="<?php echo $adminPath; ?>dolls/" class="dropdown-item">Magic Dolls</a>
                        <a href="<?php echo $adminPath; ?>polymorph/" class="dropdown-item">Polymorph</a>
                    </div>
                </div>
                
                <div class="nav-item">
                    <a href="<?php echo $adminPath; ?>logout.php" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/icons/icons/42040.png" alt="Logout" class="nav-icon">
                        Logout
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="<?php echo $rootPath; ?>index.php" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/icons/icons/40373.png" alt="View Site" class="nav-icon">
                        View Site
                    </a>
                </div>
            </nav>
        </div>
    </header>
    
    <main class="admin-main container">