<?php
// Include common functions if not already included
require_once __DIR__ . '/../functions/common.php';

// Get the current page for navigation highlighting
$currentPage = $_SERVER['PHP_SELF'];

// Get page title
$section = isset($pageSection) ? $pageSection : '';
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
    <?php if (isset($extraStyles) && !empty($extraStyles)): ?>
        <?php foreach ($extraStyles as $style): ?>
            <link rel="stylesheet" href="<?php echo $style; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>L1J Remastered DB</h1>
            </div>
            
            <ul class="main-menu">
                <li>
                    <a href="/" <?php echo $currentPage == '/index.php' ? 'class="active"' : ''; ?>>Home</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Database</a>
                    <ul class="dropdown-menu">
                        <li><a href="/weapons/" <?php echo contains($currentPage, '/weapons/') ? 'class="active"' : ''; ?>>Weapons</a></li>
                        <li><a href="/armor/" <?php echo contains($currentPage, '/armor/') ? 'class="active"' : ''; ?>>Armor</a></li>
                        <li><a href="/items/" <?php echo contains($currentPage, '/items/') ? 'class="active"' : ''; ?>>Items</a></li>
                        <li><a href="/monsters/" <?php echo contains($currentPage, '/monsters/') ? 'class="active"' : ''; ?>>Monsters</a></li>
                        <li><a href="/maps/" <?php echo contains($currentPage, '/maps/') ? 'class="active"' : ''; ?>>Maps</a></li>
                        <li><a href="/dolls/" <?php echo contains($currentPage, '/dolls/') ? 'class="active"' : ''; ?>>Dolls</a></li>
                        <li><a href="/npcs/" <?php echo contains($currentPage, '/npcs/') ? 'class="active"' : ''; ?>>NPCs</a></li>
                        <li><a href="/skills/" <?php echo contains($currentPage, '/skills/') ? 'class="active"' : ''; ?>>Skills</a></li>
                        <li><a href="/polymorph/" <?php echo contains($currentPage, '/polymorph/') ? 'class="active"' : ''; ?>>Polymorph</a></li>
                    </ul>
                </li>
                <li>
                    <a href="/admin/" <?php echo contains($currentPage, '/admin/') ? 'class="active"' : ''; ?>>Admin</a>
                </li>
            </ul>
        </nav>
    </header>
    
    <main>
        <?php if (isset($pageTitle) || isset($showHero) && $showHero): ?>
        <section class="hero-section">
            <div class="container">
                <h1 class="hero-title"><?php echo isset($pageTitle) ? $pageTitle : 'L1J Remastered Database'; ?></h1>
                <?php if (isset($pageSubtitle)): ?>
                <p class="hero-subtitle"><?php echo $pageSubtitle; ?></p>
                <?php endif; ?>
                
                <?php if (isset($showSearch) && $showSearch): ?>
                <div class="search-container">
                    <form action="<?php echo isset($searchAction) ? $searchAction : '/search.php'; ?>" method="GET">
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
