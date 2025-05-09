<?php
/**
 * Home page (public section)
 * Displays a card-based layout with links to different categories
 */

// Include configuration and database connection
require_once 'includes/config.php';
require_once 'includes/db_connect.php';

// Set current page for navigation highlighting
$currentPage = 'home';
$pageTitle = 'Home';

// Include header
include 'includes/header.php';

// Get counts for each category for display
$categoryCounts = [];

// Get weapon count
$weaponQuery = "SELECT COUNT(*) as count FROM weapon";
$weaponResult = executeQuery($weaponQuery, $conn);
$categoryCounts['weapons'] = $weaponResult ? $weaponResult->fetch_assoc()['count'] : 0;

// Get armor count
$armorQuery = "SELECT COUNT(*) as count FROM armor";
$armorResult = executeQuery($armorQuery, $conn);
$categoryCounts['armor'] = $armorResult ? $armorResult->fetch_assoc()['count'] : 0;

// Get item count (etcitem)
$itemQuery = "SELECT COUNT(*) as count FROM etcitem";
$itemResult = executeQuery($itemQuery, $conn);
$categoryCounts['items'] = $itemResult ? $itemResult->fetch_assoc()['count'] : 0;

// Get monster count (npc table with is_bossmonster=false)
$monsterQuery = "SELECT COUNT(*) as count FROM npc WHERE impl = 'L1Monster'";
$monsterResult = executeQuery($monsterQuery, $conn);
$categoryCounts['monsters'] = $monsterResult ? $monsterResult->fetch_assoc()['count'] : 0;

// Get map count
$mapQuery = "SELECT COUNT(*) as count FROM mapids";
$mapResult = executeQuery($mapQuery, $conn);
$categoryCounts['maps'] = $mapResult ? $mapResult->fetch_assoc()['count'] : 0;

// Get doll count (from magicdoll_info)
$dollQuery = "SELECT COUNT(*) as count FROM magicdoll_info";
$dollResult = executeQuery($dollQuery, $conn);
$categoryCounts['dolls'] = $dollResult ? $dollResult->fetch_assoc()['count'] : 0;

// Get NPC count (npc table with impl = L1Npc or other non-monster types)
$npcQuery = "SELECT COUNT(*) as count FROM npc WHERE impl = 'L1Npc' OR impl = 'L1Merchant'";
$npcResult = executeQuery($npcQuery, $conn);
$categoryCounts['npcs'] = $npcResult ? $npcResult->fetch_assoc()['count'] : 0;

// Get skill count
$skillQuery = "SELECT COUNT(*) as count FROM skills";
$skillResult = executeQuery($skillQuery, $conn);
$categoryCounts['skills'] = $skillResult ? $skillResult->fetch_assoc()['count'] : 0;

// Get polymorph count
$polyQuery = "SELECT COUNT(*) as count FROM polymorphs";
$polyResult = executeQuery($polyQuery, $conn);
$categoryCounts['polymorph'] = $polyResult ? $polyResult->fetch_assoc()['count'] : 0;
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Lineage II Database</h1>
        <p>Comprehensive database for Lineage II players. Find detailed information about weapons, armor, items, monsters, maps, and more.</p>
        <div class="search-box">
            <form action="<?php echo $baseUrl; ?>search.php" method="GET">
                <input type="text" name="q" class="search-input" placeholder="Search for weapons, armor, monsters...">
                <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
</section>

<!-- Main Content - Categories Cards -->
<section class="main-content">
    <div class="container">
        <div class="card-grid">
            <!-- Weapons Card -->
            <a href="<?php echo $baseUrl; ?>pages/weapons/weapon-list.php" class="card">
                <div class="card-header">
                    <h2 class="card-title">Weapons</h2>
                </div>
                <img src="<?php echo $baseUrl; ?>assets/img/placeholders/weapons.png alt="Weapons" class="card-img">
                <div class="card-body">
                    <p class="card-text">Browse through a comprehensive collection of Lineage II weapons including swords, daggers, staves, and more.</p>
                    <div class="card-count"><?php echo number_format($categoryCounts['weapons']); ?> Weapons</div>
                </div>
            </a>
            
            <!-- Armor Card -->
            <a href="<?php echo $baseUrl; ?>pages/armor/armor-list.php" class="card">
                <div class="card-header">
                    <h2 class="card-title">Armor</h2>
                </div>
                <img src="<?php echo $baseUrl; ?>assets/img/placeholders/armor.png" alt="Armor" class="card-img">
                <div class="card-body">
                    <p class="card-text">Discover all types of armor including helmets, body armor, gloves, boots, and shields.</p>
                    <div class="card-count"><?php echo number_format($categoryCounts['armor']); ?> Armor Pieces</div>
                </div>
            </a>
            
            <!-- Items Card -->
            <a href="<?php echo $baseUrl; ?>pages/items/item-list.php" class="card">
                <div class="card-header">
                    <h2 class="card-title">Items</h2>
                </div>
                <img src="<?php echo $baseUrl; ?>assets/img/placeholders/items.png" alt="Items" class="card-img">
                <div class="card-body">
                    <p class="card-text">Explore consumables, quest items, crafting materials, and other special items.</p>
                    <div class="card-count"><?php echo number_format($categoryCounts['items']); ?> Items</div>
                </div>
            </a>
            
            <!-- Monsters Card -->
            <a href="<?php echo $baseUrl; ?>pages/monsters/monster-list.php" class="card">
                <div class="card-header">
                    <h2 class="card-title">Monsters</h2>
                </div>
                <img src="<?php echo $baseUrl; ?>assets/img/placeholders/monsters.png" alt="Monsters" class="card-img">
                <div class="card-body">
                    <p class="card-text">Information about all monsters in the world of Lineage II, including bosses, regular monsters, and their drop tables.</p>
                    <div class="card-count"><?php echo number_format($categoryCounts['monsters']); ?> Monsters</div>
                </div>
            </a>
            
            <!-- Maps Card -->
            <a href="<?php echo $baseUrl; ?>pages/maps/map-list.php" class="card">
                <div class="card-header">
                    <h2 class="card-title">Maps</h2>
                </div>
                <img src="<?php echo $baseUrl; ?>assets/img/placeholders/maps.png" alt="Maps" class="card-img">
                <div class="card-body">
                    <p class="card-text">Detailed maps of the Lineage II world, including cities, dungeons, and hunting grounds.</p>
                    <div class="card-count"><?php echo number_format($categoryCounts['maps']); ?> Maps</div>
                </div>
            </a>
            
            <!-- Dolls Card -->
            <a href="<?php echo $baseUrl; ?>pages/dolls/doll-list.php" class="card">
                <div class="card-header">
                    <h2 class="card-title">Magic Dolls</h2>
                </div>
                <img src="<?php echo $baseUrl; ?>assets/img/placeholders/dolls.png" alt="Magic Dolls" class="card-img">
                <div class="card-body">
                    <p class="card-text">Find information about magic dolls and their special abilities and bonuses.</p>
                    <div class="card-count"><?php echo number_format($categoryCounts['dolls']); ?> Magic Dolls</div>
                </div>
            </a>
            
            <!-- NPCs Card -->
            <a href="<?php echo $baseUrl; ?>pages/npcs/npc-list.php" class="card">
                <div class="card-header">
                    <h2 class="card-title">NPCs</h2>
                </div>
                <img src="<?php echo $baseUrl; ?>assets/img/placeholders/npc.png" alt="NPCs" class="card-img">
                <div class="card-body">
                    <p class="card-text">Information about all non-player characters, including merchants, quest givers, and trainers.</p>
                    <div class="card-count"><?php echo number_format($categoryCounts['npcs']); ?> NPCs</div>
                </div>
            </a>
            
            <!-- Skills Card -->
            <a href="<?php echo $baseUrl; ?>pages/skills/skill-list.php" class="card">
                <div class="card-header">
                    <h2 class="card-title">Skills</h2>
                </div>
                <img src="<?php echo $baseUrl; ?>assets/img/placeholders/skill.png" alt="Skills" class="card-img">
                <div class="card-body">
                    <p class="card-text">Detailed information about all active and passive skills available to different character classes.</p>
                    <div class="card-count"><?php echo number_format($categoryCounts['skills']); ?> Skills</div>
                </div>
            </a>
            
            <!-- Polymorph Card -->
            <a href="<?php echo $baseUrl; ?>pages/polymorph/polymorph-list.php" class="card">
                <div class="card-header">
                    <h2 class="card-title">Polymorph</h2>
                </div>
                <img src="<?php echo $baseUrl; ?>assets/img/placeholders/poly.png" alt="Polymorph" class="card-img">
                <div class="card-body">
                    <p class="card-text">Information about polymorph transformations and their special abilities.</p>
                    <div class="card-count"><?php echo number_format($categoryCounts['polymorph']); ?> Polymorph Forms</div>
                </div>
            </a>
			</div>
    </div>
</section>

<?php
// Include footer
include 'includes/footer.php';
?>