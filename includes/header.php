<?php
/**
 * Header Template
 * This file contains the header HTML that will be used across all pages
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - L1J Database' : 'L1J Database'; ?></title>
    <?php 
    // Determine if we're in a subdirectory
    $rootPath = "";
    if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
        $rootPath = "../";
    }
    ?>
    <link rel="stylesheet" href="<?php echo $rootPath; ?>assets/css/styles.css">
    <?php if (isset($extraCSS)): ?>
        <?php foreach ($extraCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <a href="<?php echo $rootPath; ?>index.php" class="logo">L1J <span>Database</span></a>
            <nav class="nav">
                <div class="nav-item dropdown">
                    <a href="<?php echo $rootPath; ?>weapons/" class="nav-link">Weapons</a>
                    <div class="dropdown-content">
                        <a href="<?php echo $rootPath; ?>weapons/swords.php" class="dropdown-item">Swords</a>
                        <a href="<?php echo $rootPath; ?>weapons/daggers.php" class="dropdown-item">Daggers</a>
                        <a href="<?php echo $rootPath; ?>weapons/bows.php" class="dropdown-item">Bows</a>
                        <a href="<?php echo $rootPath; ?>weapons/staves.php" class="dropdown-item">Staves</a>
                        <a href="<?php echo $rootPath; ?>weapons/axes.php" class="dropdown-item">Axes</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="<?php echo $rootPath; ?>armor/" class="nav-link">Armor</a>
                    <div class="dropdown-content">
                        <a href="<?php echo $rootPath; ?>armor/helmets.php" class="dropdown-item">Helmets</a>
                        <a href="<?php echo $rootPath; ?>armor/armors.php" class="dropdown-item">Armors</a>
                        <a href="<?php echo $rootPath; ?>armor/shields.php" class="dropdown-item">Shields</a>
                        <a href="<?php echo $rootPath; ?>armor/gloves.php" class="dropdown-item">Gloves</a>
                        <a href="<?php echo $rootPath; ?>armor/boots.php" class="dropdown-item">Boots</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="<?php echo $rootPath; ?>items/" class="nav-link">Items</a>
                    <div class="dropdown-content">
                        <a href="<?php echo $rootPath; ?>items/scrolls.php" class="dropdown-item">Scrolls</a>
                        <a href="<?php echo $rootPath; ?>items/potions.php" class="dropdown-item">Potions</a>
                        <a href="<?php echo $rootPath; ?>items/jewels.php" class="dropdown-item">Jewels</a>
                        <a href="<?php echo $rootPath; ?>items/misc.php" class="dropdown-item">Miscellaneous</a>
                    </div>
                </div>
                <div class="nav-item">
                    <a href="<?php echo $rootPath; ?>monsters/" class="nav-link">Monsters</a>
                </div>
                <div class="nav-item">
                    <a href="<?php echo $rootPath; ?>maps/" class="nav-link">Maps</a>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link">More</a>
                    <div class="dropdown-content">
                        <a href="<?php echo $rootPath; ?>dolls/" class="dropdown-item">Magic Dolls</a>
                        <a href="<?php echo $rootPath; ?>npcs/" class="dropdown-item">NPCs</a>
                        <a href="<?php echo $rootPath; ?>skills/" class="dropdown-item">Skills</a>
                        <a href="<?php echo $rootPath; ?>polymorph/" class="dropdown-item">Polymorph</a>
                    </div>
                </div>
                <div class="nav-item">
                    <a href="<?php echo $rootPath; ?>admin/" class="nav-link">Admin</a>
                </div>
            </nav>
        </div>
    </header>
    
    <?php if (isset($showHero) && $showHero): ?>
    <section class="hero">
        <div class="container">
            <h1 class="hero-title">L1J Database</h1>
            <p class="hero-subtitle">The definitive resource for Lineage 1 server information</p>
            <div class="search-container">
            <form action="<?php echo $rootPath; ?>search.php" method="GET" class="search-form">
                    <input type="text" name="q" class="search-input" placeholder="Search items, monsters, etc..." required>
                    <button type="submit" class="search-button">Search</button>
                </form>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <main class="container">
