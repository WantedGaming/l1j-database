<?php
/**
 * Header component for public section
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - L1J-R DB' : 'L1J-R DB'; ?></title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">
                <a href="<?php echo $baseUrl; ?>">
                    <img src="<?php echo $baseUrl; ?>assets/img/favicon/favicon.ico" alt="L1J-R DB">
                    <span>L1J-R DB</span>
                </a>
            </div>
            <nav>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="<?php echo $baseUrl; ?>" class="nav-link <?php echo $currentPage === 'home' ? 'active' : ''; ?>">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?php echo in_array($currentPage, ['weapons', 'armor', 'items', 'monsters', 'maps', 'dolls', 'npcs', 'skills', 'polymorph']) ? 'active' : ''; ?>">
                            Database <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <a href="<?php echo $baseUrl; ?>pages/weapons/weapon-list.php" class="dropdown-link <?php echo $currentPage === 'weapons' ? 'active' : ''; ?>">Weapons</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="<?php echo $baseUrl; ?>pages/armor/armor-list.php" class="dropdown-link <?php echo $currentPage === 'armor' ? 'active' : ''; ?>">Armor</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="<?php echo $baseUrl; ?>pages/items/item-list.php" class="dropdown-link <?php echo $currentPage === 'items' ? 'active' : ''; ?>">Items</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="<?php echo $baseUrl; ?>pages/monsters/monster-list.php" class="dropdown-link <?php echo $currentPage === 'monsters' ? 'active' : ''; ?>">Monsters</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="<?php echo $baseUrl; ?>pages/maps/map-list.php" class="dropdown-link <?php echo $currentPage === 'maps' ? 'active' : ''; ?>">Maps</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="<?php echo $baseUrl; ?>pages/dolls/doll-list.php" class="dropdown-link <?php echo $currentPage === 'dolls' ? 'active' : ''; ?>">Dolls</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="<?php echo $baseUrl; ?>pages/npcs/npc-list.php" class="dropdown-link <?php echo $currentPage === 'npcs' ? 'active' : ''; ?>">NPCs</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="<?php echo $baseUrl; ?>pages/skills/skill-list.php" class="dropdown-link <?php echo $currentPage === 'skills' ? 'active' : ''; ?>">Skills</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="<?php echo $baseUrl; ?>pages/polymorph/polymorph-list.php" class="dropdown-link <?php echo $currentPage === 'polymorph' ? 'active' : ''; ?>">Polymorph</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo $baseUrl; ?>admin/index.php" class="nav-link <?php echo $currentPage === 'admin' ? 'active' : ''; ?>">Admin</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>