<?php
/**
 * Header Template
 * This file contains the header HTML used across standard site pages
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
    if (strpos($_SERVER['PHP_SELF'], '/weapons/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/armor/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/items/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/monsters/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/maps/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/npcs/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/skills/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/dolls/') !== false || 
        strpos($_SERVER['PHP_SELF'], '/polymorph/') !== false) {
        $rootPath = "../";
    }
    ?>
    <link rel="stylesheet" href="<?php echo $rootPath; ?>assets/css/styles.css">
    <?php if (isset($extraCSS)): ?>
        <?php foreach ($extraCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo (strpos($css, 'http') === 0) ? $css : $rootPath . ltrim(str_replace('../', '', $css), '/'); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <a href="<?php echo $rootPath; ?>index.php" class="logo">L1J <span>Database</span></a>
            <nav class="nav">
                <!-- Home -->
                <div class="nav-item">
                    <a href="<?php echo $rootPath; ?>index.php" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/nav/8339.png" alt="Home" class="nav-icon">
                        Home
                    </a>
                </div>
                
                <!-- Database Dropdown -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/nav/8593.png" alt="Database" class="nav-icon">
                        Database
                    </a>
                    <div class="dropdown-content">
                        <a href="<?php echo $rootPath; ?>weapons/" class="dropdown-item">
                            <img src="<?php echo $rootPath; ?>assets/img/nav/weapons.png" alt="Weapons" class="dropdown-icon">
                            Weapons
                        </a>
                        <a href="<?php echo $rootPath; ?>armor/" class="dropdown-item">
                            <img src="<?php echo $rootPath; ?>assets/img/nav/armor.png" alt="Armor" class="dropdown-icon">
                            Armor
                        </a>
                        <a href="<?php echo $rootPath; ?>items/" class="dropdown-item">
                            <img src="<?php echo $rootPath; ?>assets/img/nav/items.png" alt="Items" class="dropdown-icon">
                            Items
                        </a>
                        <a href="<?php echo $rootPath; ?>maps/" class="dropdown-item">
                            <img src="<?php echo $rootPath; ?>assets/img/nav/maps.png" alt="Maps" class="dropdown-icon">
                            Maps
                        </a>
                        <a href="<?php echo $rootPath; ?>monsters/" class="dropdown-item">
                            <img src="<?php echo $rootPath; ?>assets/img/nav/monsters.png" alt="Monsters" class="dropdown-icon">
                            Monsters
                        </a>
                        <a href="<?php echo $rootPath; ?>dolls/" class="dropdown-item">
                            <img src="<?php echo $rootPath; ?>assets/img/nav/11353.png" alt="Magic Dolls" class="dropdown-icon">
                            Magic Dolls
                        </a>
                        <a href="<?php echo $rootPath; ?>npcs/" class="dropdown-item">
                            <img src="<?php echo $rootPath; ?>assets/img/nav/npc.png" alt="NPCs" class="dropdown-icon">
                            NPCs
                        </a>
                        <a href="<?php echo $rootPath; ?>skills/" class="dropdown-item">
                            <img src="<?php echo $rootPath; ?>assets/img/nav/skills.png" alt="Skills" class="dropdown-icon">
                            Skills
                        </a>
                        <a href="<?php echo $rootPath; ?>polymorph/" class="dropdown-item">
                            <img src="<?php echo $rootPath; ?>assets/img/nav/polymorph.png" alt="Polymorph" class="dropdown-icon">
                            Polymorph
                        </a>
                    </div>
                </div>
                
                <!-- Admin (direct link) -->
                <div class="nav-item">
                    <a href="<?php echo $rootPath; ?>admin/" class="nav-link">
                        <img src="<?php echo $rootPath; ?>assets/img/nav/10252.png" alt="Admin" class="nav-icon">
                        Admin
                    </a>
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