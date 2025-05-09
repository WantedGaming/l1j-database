<?php
/**
 * Admin Dashboard
 * Main administration page for managing the L1J Database
 */

// Start the session
session_start();

// Check if logged in and has admin access, redirect to login page if not
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if user has admin access level
if (!isset($_SESSION['admin_access_level']) || $_SESSION['admin_access_level'] < 1) {
    header('Location: login.php?error=insufficient_permissions');
    exit;
}

// Include database connection
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Set page title
$pageTitle = 'Admin Dashboard';
$showHero = false;

// Extra CSS for admin
$extraCSS = ['../assets/css/admin.css'];

// Include admin header instead of regular header
include '../includes/admin-header.php';

// Get total counts for each section
$weaponsCount = $pdo->query("SELECT COUNT(*) FROM weapon")->fetchColumn();
$armorCount = $pdo->query("SELECT COUNT(*) FROM armor")->fetchColumn();
$itemsCount = $pdo->query("SELECT COUNT(*) FROM etcitem")->fetchColumn();
// Use the 'impl' column to distinguish between monster and NPC types
$monstersCount = $pdo->query("SELECT COUNT(*) FROM npc WHERE impl LIKE '%Monster%'")->fetchColumn();
$npcsCount = $pdo->query("SELECT COUNT(*) FROM npc WHERE impl NOT LIKE '%Monster%'")->fetchColumn();
$mapsCount = $pdo->query("SELECT COUNT(*) FROM mapids")->fetchColumn();
$skillsCount = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
?>

<div class="admin-header mb-6 p-4">
    <div class="container">
        <h1 class="section-title">Admin Dashboard</h1>
        <p>Welcome to the L1J Database administration panel. From here, you can manage all aspects of the database.</p>
    </div>
</div>

<div class="mb-6">
    <h2 class="section-title">Database Overview</h2>
    
    <div class="grid grid-cols-3 gap-6">
        <div class="admin-card">
            <h3>Weapons</h3>
            <p class="text-accent"><?php echo formatNumber($weaponsCount); ?> items</p>
            <div class="mt-4">
                <a href="weapons/" class="btn">Manage Weapons</a>
            </div>
        </div>
        
        <div class="admin-card">
            <h3>Armor</h3>
            <p class="text-accent"><?php echo formatNumber($armorCount); ?> items</p>
            <div class="mt-4">
                <a href="armor/" class="btn">Manage Armor</a>
            </div>
        </div>
        
        <div class="admin-card">
            <h3>Items</h3>
            <p class="text-accent"><?php echo formatNumber($itemsCount); ?> items</p>
            <div class="mt-4">
                <a href="items/" class="btn">Manage Items</a>
            </div>
        </div>
        
        <div class="admin-card">
            <h3>Monsters</h3>
            <p class="text-accent"><?php echo formatNumber($monstersCount); ?> entries</p>
            <div class="mt-4">
                <a href="monsters/" class="btn">Manage Monsters</a>
            </div>
        </div>
        
        <div class="admin-card">
            <h3>Maps</h3>
            <p class="text-accent"><?php echo formatNumber($mapsCount); ?> entries</p>
            <div class="mt-4">
                <a href="maps/" class="btn">Manage Maps</a>
            </div>
        </div>
        
        <div class="admin-card">
            <h3>NPCs</h3>
            <p class="text-accent"><?php echo formatNumber($npcsCount); ?> entries</p>
            <div class="mt-4">
                <a href="npcs/" class="btn">Manage NPCs</a>
            </div>
        </div>
        
        <div class="admin-card">
            <h3>Skills</h3>
            <p class="text-accent"><?php echo formatNumber($skillsCount); ?> entries</p>
            <div class="mt-4">
                <a href="skills/" class="btn">Manage Skills</a>
            </div>
        </div>
        
        <div class="admin-card">
            <h3>Droplist</h3>
            <p class="text-accent">Manage item drops</p>
            <div class="mt-4">
                <a href="droplist/" class="btn">Manage Drops</a>
            </div>
        </div>
        
        <div class="admin-card">
            <h3>Spawns</h3>
            <p class="text-accent">Manage monster spawns</p>
            <div class="mt-4">
                <a href="spawns/" class="btn">Manage Spawns</a>
            </div>
        </div>
    </div>
</div>

<div class="mb-6">
    <h2 class="section-title">Recent Activity</h2>
    
    <div class="admin-card">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Item</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // This would normally be populated from a database table
                    // We're using placeholder data for now
                    $activities = [
                        [
                            'date' => '2025-05-08 14:23:15',
                            'action' => 'Added',
                            'item' => 'Diamond Sword (Weapon ID: 3421)',
                            'user' => 'Admin'
                        ],
                        [
                            'date' => '2025-05-08 11:45:32',
                            'action' => 'Updated',
                            'item' => 'Orc (Monster ID: 45)',
                            'user' => 'Admin'
                        ],
                        [
                            'date' => '2025-05-07 16:12:08',
                            'action' => 'Deleted',
                            'item' => 'Crystal Plate (Armor ID: 2032)',
                            'user' => 'Admin'
                        ],
                        [
                            'date' => '2025-05-07 09:55:41',
                            'action' => 'Updated',
                            'item' => 'Giran Castle (Map ID: 4)',
                            'user' => 'Admin'
                        ],
                        [
                            'date' => '2025-05-06 17:30:22',
                            'action' => 'Added',
                            'item' => 'Elven Bow (Weapon ID: 3589)',
                            'user' => 'Admin'
                        ]
                    ];
                    
                    foreach ($activities as $activity):
                    ?>
                    <tr>
                        <td><?php echo date('M j, Y H:i', strtotime($activity['date'])); ?></td>
                        <td><?php echo $activity['action']; ?></td>
                        <td><?php echo htmlspecialchars($activity['item']); ?></td>
                        <td><?php echo htmlspecialchars($activity['user']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mb-6">
    <h2 class="section-title">Quick Actions</h2>
    
    <div class="grid grid-cols-2 gap-6">
        <div class="admin-card">
            <h3 class="mb-4">Backup Database</h3>
            <p class="mb-4">Create a backup of the current database state.</p>
            <a href="backup.php" class="btn">Create Backup</a>
        </div>
        
        <div class="admin-card">
            <h3 class="mb-4">Server Statistics</h3>
            <p class="mb-4">View server performance and database statistics.</p>
            <a href="stats.php" class="btn">View Statistics</a>
        </div>
    </div>
</div>

<?php
// Include admin footer instead of regular footer
include '../includes/admin-footer.php';
?>