<?php
// Include common functions and authentication
require_once __DIR__ . '/../functions/common.php';
require_once __DIR__ . '/../functions/auth.php';

// Require authentication for all admin pages
requireLogin();

// Get the current page for navigation highlighting
$currentPage = $_SERVER['PHP_SELF'];

// Get page title
$section = isset($pageSection) ? $pageSection : 'Admin';
$itemName = isset($itemName) ? $itemName : null;
$title = pageTitle($section, $itemName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/admin.css">
    <?php if (isset($extraStyles) && !empty($extraStyles)): ?>
        <?php foreach ($extraStyles as $style): ?>
            <link rel="stylesheet" href="<?php echo $style; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="admin-body">
    <header class="admin-header">
        <nav class="navbar">
            <div class="logo">
                <h1>L1J Admin</h1>
            </div>
            
            <ul class="main-menu">
                <li>
                    <a href="/admin/" <?php echo $currentPage == '/admin/index.php' ? 'class="active"' : ''; ?>>Dashboard</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Database</a>
                    <ul class="dropdown-menu">
                        <li><a href="/admin/weapons/" <?php echo contains($currentPage, '/admin/weapons/') ? 'class="active"' : ''; ?>>Weapons</a></li>
                        <li><a href="/admin/armor/" <?php echo contains($currentPage, '/admin/armor/') ? 'class="active"' : ''; ?>>Armor</a></li>
                        <li><a href="/admin/items/" <?php echo contains($currentPage, '/admin/items/') ? 'class="active"' : ''; ?>>Items</a></li>
                        <li><a href="/admin/monsters/" <?php echo contains($currentPage, '/admin/monsters/') ? 'class="active"' : ''; ?>>Monsters</a></li>
                        <li><a href="/admin/maps/" <?php echo contains($currentPage, '/admin/maps/') ? 'class="active"' : ''; ?>>Maps</a></li>
                        <li><a href="/admin/dolls/" <?php echo contains($currentPage, '/admin/dolls/') ? 'class="active"' : ''; ?>>Dolls</a></li>
                        <li><a href="/admin/npcs/" <?php echo contains($currentPage, '/admin/npcs/') ? 'class="active"' : ''; ?>>NPCs</a></li>
                        <li><a href="/admin/skills/" <?php echo contains($currentPage, '/admin/skills/') ? 'class="active"' : ''; ?>>Skills</a></li>
                        <li><a href="/admin/polymorph/" <?php echo contains($currentPage, '/admin/polymorph/') ? 'class="active"' : ''; ?>>Polymorph</a></li>
                    </ul>
                </li>
                <li>
                    <a href="/" class="back-to-site">Back to Site</a>
                </li>
                <li>
                    <a href="/admin/logout.php">Logout</a>
                </li>
            </ul>
        </nav>
    </header>
    
    <main>
        <?php if (isset($pageTitle) || isset($showHero) && $showHero): ?>
        <section class="hero-section admin-hero">
            <div class="container">
                <h1 class="hero-title"><?php echo isset($pageTitle) ? $pageTitle : 'Admin Dashboard'; ?></h1>
                <?php if (isset($pageSubtitle)): ?>
                <p class="hero-subtitle"><?php echo $pageSubtitle; ?></p>
                <?php endif; ?>
                
                <?php if (isset($showSearch) && $showSearch): ?>
                <div class="search-container">
                    <form action="<?php echo isset($searchAction) ? $searchAction : '/admin/search.php'; ?>" method="GET">
                        <input type="text" name="q" placeholder="Search..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <?php if (isset($searchCategory)): ?>
                        <input type="hidden" name="category" value="<?php echo $searchCategory; ?>">
                        <?php endif; ?>
                        <button type="submit">Search</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <div class="admin-container">
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['flash_type'] ?? 'success'; ?>">
                    <?php echo $_SESSION['flash_message']; ?>
                </div>
                <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
            <?php endif; ?>
