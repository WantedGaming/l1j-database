<?php
/**
 * Home Page
 * Main landing page for the L1J Database
 */

// Include database connection
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Set page title and hero visibility
$pageTitle = 'Home';
$showHero = true;

// Include header
include 'includes/header.php';
?>

<section class="mb-8">
    <h2 class="section-title">Welcome to the L1J Database</h2>
    <p>The L1J Database is a comprehensive resource for Lineage 1 server data, providing detailed information about weapons, armor, items, monsters, maps, and more. Use the search bar above or explore categories below to find what you're looking for.</p>
</section>

<section class="mb-8">
    <h2 class="section-title">Browse Categories</h2>
    <div class="grid grid-cols-3 gap-8">
        <a href="weapons/" class="card category-card">
            <img src="<?php echo getImagePath('item', 47); ?>" alt="Weapons" class="category-icon">
            <h3 class="category-title">Weapons</h3>
            <p class="card-description">Explore swords, daggers, bows, staves, and other weapons</p>
        </a>
        
        <a href="armor/" class="card category-card">
            <img src="<?php echo getImagePath('item', 20322); ?>" alt="Armor" class="category-icon">
            <h3 class="category-title">Armor</h3>
            <p class="card-description">Browse helmets, shields, armor, gloves, and boots</p>
        </a>
        
        <a href="items/" class="card category-card">
            <img src="<?php echo getImagePath('item', 40308); ?>" alt="Items" class="category-icon">
            <h3 class="category-title">Items</h3>
            <p class="card-description">Find scrolls, potions, jewels, and other miscellaneous items</p>
        </a>
        
        <a href="monsters/" class="card category-card">
            <img src="<?php echo getImagePath('monster', 45); ?>" alt="Monsters" class="category-icon">
            <h3 class="category-title">Monsters</h3>
            <p class="card-description">Learn about all monsters, their drops, and spawn locations</p>
        </a>
        
        <a href="maps/" class="card category-card">
            <img src="<?php echo getImagePath('map', 4); ?>" alt="Maps" class="category-icon">
            <h3 class="category-title">Maps</h3>
            <p class="card-description">Explore game regions, cities, dungeons, and other areas</p>
        </a>
        
        <a href="skills/" class="card category-card">
            <img src="<?php echo getImagePath('skill', 1); ?>" alt="Skills" class="category-icon">
            <h3 class="category-title">Skills</h3>
            <p class="card-description">Discover character skills, spells, and abilities</p>
        </a>
    </div>
</section>

<section class="mb-8">
    <h2 class="section-title">Recent Updates</h2>
    <div class="list-container">
        <?php
        // Get recent updates (placeholder query - would be replaced with actual data)
        $updates = [
            [
                'date' => '2025-05-01',
                'title' => 'New Weapons Added',
                'description' => 'Added 10 new weapons to the database.'
            ],
            [
                'date' => '2025-04-28',
                'title' => 'Monster Spawn Updates',
                'description' => 'Updated spawn locations for several monsters.'
            ],
            [
                'date' => '2025-04-22',
                'title' => 'New Map Information',
                'description' => 'Added detailed information for the Dragon Valley map.'
            ],
            [
                'date' => '2025-04-15',
                'title' => 'Item Search Improved',
                'description' => 'Enhanced search functionality for items.'
            ]
        ];
        
        foreach ($updates as $update):
        ?>
        <div class="list-row">
            <div class="item-details">
                <div class="item-name"><?php echo $update['title']; ?></div>
                <div class="item-info">
                    <?php echo $update['description']; ?>
                </div>
            </div>
            <div class="text-right">
                <div class="text-light"><?php echo date('M j, Y', strtotime($update['date'])); ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section>
    <h2 class="section-title">Contribute</h2>
    <div class="card p-6">
        <p class="mb-4">The L1J Database is a community-driven project. If you'd like to contribute by adding or updating information, please contact the administrator or use the admin section to submit changes.</p>
        <a href="contact.php" class="btn btn-primary">Contact Us</a>
    </div>
</section>

<?php
// Include footer
include 'includes/footer.php';
?>
