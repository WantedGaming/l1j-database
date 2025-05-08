<?php
// Set page variables
$pageTitle = 'Admin Dashboard';
$pageSubtitle = 'Manage and update game data';
$showHero = true;
$pageSection = 'Admin';

// Include header
require_once __DIR__ . '/../includes/layouts/admin_header.php';

// For demonstration purposes, mock database statistics
$stats = [
    'weapons' => 250,
    'armor' => 420,
    'items' => 735,
    'monsters' => 310,
    'maps' => 45,
    'dolls' => 30,
    'npcs' => 180,
    'skills' => 125,
    'polymorph' => 85
];

// Recent activity (would normally come from database)
$recentActivity = [
    [
        'type' => 'create',
        'title' => 'Created new weapon: Flaming Sword of Doom',
        'user' => 'admin',
        'time' => '2 hours ago'
    ],
    [
        'type' => 'edit',
        'title' => 'Updated monster stats for Giant Spider',
        'user' => 'admin',
        'time' => '4 hours ago'
    ],
    [
        'type' => 'delete',
        'title' => 'Deleted duplicate armor item',
        'user' => 'admin',
        'time' => '1 day ago'
    ],
    [
        'type' => 'create',
        'title' => 'Added new skill: Ice Storm',
        'user' => 'admin',
        'time' => '2 days ago'
    ],
    [
        'type' => 'edit',
        'title' => 'Updated drop rates for Ancient Dragon',
        'user' => 'admin',
        'time' => '3 days ago'
    ]
];
?>

<div class="admin-header-actions">
    <h2>Dashboard Overview</h2>
</div>

<div class="stats-grid">
    <?php foreach ($stats as $category => $count): ?>
    <div class="stat-card">
        <div class="stat-number"><?php echo number_format($count); ?></div>
        <div class="stat-label"><?php echo ucfirst($category); ?></div>
    </div>
    <?php endforeach; ?>
</div>

<div class="admin-panels">
    <div class="admin-panel">
        <div class="admin-panel-header">
            <h3>Recent Activity</h3>
        </div>
        <div class="admin-panel-body">
            <ul class="activity-list">
                <?php foreach ($recentActivity as $activity): ?>
                <li class="activity-item">
                    <div class="activity-icon <?php echo $activity['type']; ?>">
                        <?php if ($activity['type'] === 'create'): ?>+
                        <?php elseif ($activity['type'] === 'edit'): ?>✎
                        <?php elseif ($activity['type'] === 'delete'): ?>×
                        <?php endif; ?>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title"><?php echo $activity['title']; ?></div>
                        <div class="activity-time">
                            by <?php echo $activity['user']; ?> - <?php echo $activity['time']; ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    
    <div class="admin-panel">
        <div class="admin-panel-header">
            <h3>Quick Links</h3>
        </div>
        <div class="admin-panel-body">
            <ul class="quick-links">
                <li><a href="/admin/weapons/create.php">Add New Weapon</a></li>
                <li><a href="/admin/monsters/create.php">Add New Monster</a></li>
                <li><a href="/admin/items/create.php">Add New Item</a></li>
                <li><a href="/admin/skills/create.php">Add New Skill</a></li>
                <li><a href="/admin/maps/create.php">Add New Map</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="admin-panels">
    <div class="admin-panel">
        <div class="admin-panel-header">
            <h3>Database Categories</h3>
        </div>
        <div class="admin-panel-body">
            <div class="cards-grid">
                <div class="card">
                    <div class="card-image">
                        <img src="/public/images/icons/sword.svg" alt="Weapons Icon">
                    </div>
                    <div class="card-content">
                        <h2 class="card-title">Weapons</h2>
                        <p class="card-text">Manage all weapons including stats, skills and effects.</p>
                        <a href="/admin/weapons/" class="card-link">Manage</a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-image">
                        <img src="/public/images/icons/shield.svg" alt="Armor Icon">
                    </div>
                    <div class="card-content">
                        <h2 class="card-title">Armor</h2>
                        <p class="card-text">Edit armor pieces and sets with their protective values.</p>
                        <a href="/admin/armor/" class="card-link">Manage</a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-image">
                        <img src="/public/images/icons/potion.svg" alt="Items Icon">
                    </div>
                    <div class="card-content">
                        <h2 class="card-title">Items</h2>
                        <p class="card-text">Update potions, scrolls, and other items.</p>
                        <a href="/admin/items/" class="card-link">Manage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/../includes/layouts/admin_footer.php';
?>