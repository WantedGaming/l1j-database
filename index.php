<?php
// Set page variables
$pageTitle = 'L1J Remastered Database Browser';
$pageSubtitle = 'Browse and explore game data with ease';
$showHero = true;
$showSearch = true;

// Include header
require_once __DIR__ . '/includes/layouts/header.php';

// Define categories
$categories = [
    [
        'id' => 'weapons',
        'title' => 'Weapons',
        'description' => 'Browse all weapons including stats, skills and effects.',
        'icon' => 'sword',
        'tables' => ['weapon.sql', 'weapon_skill.sql', 'weapon_skill_model.sql', 'weapons_skill_spell_def.sql']
    ],
    [
        'id' => 'armor',
        'title' => 'Armor',
        'description' => 'Explore armor pieces and sets with their protective values.',
        'icon' => 'shield',
        'tables' => ['armor.sql', 'armor_set.sql']
    ],
    [
        'id' => 'items',
        'title' => 'Items',
        'description' => 'Search through potions, scrolls, and other items.',
        'icon' => 'potion',
        'tables' => ['etcitem.sql']
    ],
    [
        'id' => 'monsters',
        'title' => 'Monsters',
        'description' => 'Learn about monster stats, skills, and drops.',
        'icon' => 'dragon',
        'tables' => ['npc.sql (filter by impl "L1Monster" + "L1Doppelganger")', 'mobskill.sql', 'mobgroup.sql']
    ],
    [
        'id' => 'maps',
        'title' => 'Maps',
        'description' => 'Discover the world map and teleport locations.',
        'icon' => 'map',
        'tables' => ['mapids.sql']
    ],
    [
        'id' => 'dolls',
        'title' => 'Dolls',
        'description' => 'View magical dolls and their unique abilities.',
        'icon' => 'doll',
        'tables' => ['npc.sql (filter by impl "L1Doll")', 'magicdoll_info.sql', 'magicdoll_potential.sql']
    ],
    [
        'id' => 'npcs',
        'title' => 'NPCs',
        'description' => 'Find information about shopkeepers, guards, and quest givers.',
        'icon' => 'person',
        'tables' => ['npc.sql (filter by impl "L1Blackknight", "L1Dwarf", "L1Guard", "L1HouseKeeper", "L1Merchant", "L1Npc", "L1Teleporter")']
    ],
    [
        'id' => 'skills',
        'title' => 'Skills',
        'description' => 'Learn about active and passive skills for all classes.',
        'icon' => 'magic',
        'tables' => ['skills.sql', 'skills_hanlder.sql', 'skills_info.sql', 'skills_passive.sql']
    ],
    [
        'id' => 'polymorph',
        'title' => 'Polymorph',
        'description' => 'Explore transformation options and their effects.',
        'icon' => 'transform',
        'tables' => ['polymorphs.sql']
    ]
];
?>

<div class="container">
    <div class="cards-grid">
        <?php foreach ($categories as $category): ?>
        <div class="card">
            <div class="card-image">
                <img src="/public/images/icons/<?php echo $category['icon']; ?>.svg" alt="<?php echo $category['title']; ?> Icon">
            </div>
            <div class="card-content">
                <h2 class="card-title"><?php echo $category['title']; ?></h2>
                <p class="card-text"><?php echo $category['description']; ?></p>
                <a href="/<?php echo $category['id']; ?>/" class="card-link">Browse</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="about-section">
        <h2>About L1J Remastered Database Browser</h2>
        <p>
            This database browser provides easy access to L1J Remastered game data, allowing players and administrators
            to quickly search, filter, and explore game information. Use the category cards above to navigate to specific
            sections of interest.
        </p>
        <p>
            The browser includes detailed information about weapons, armor, items, monsters, and more, all organized in a
            clean and accessible format. For administrators, full CRUD functionality is available to manage and update
            game data as needed.
        </p>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/includes/layouts/footer.php';
?>