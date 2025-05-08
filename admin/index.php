<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Require admin authentication
requireAdminAuth();

$page_title = 'Admin Dashboard';
$is_admin = true;

// Get database statistics for display
$stats = getDatabaseStats();

// Recent activity - example data (would be from database in production)
$recent_activity = [
    [
        'type' => 'edit',
        'item' => 'Sword of Destruction',
        'table' => 'weapon',
        'time' => '2025-05-08 10:15:22',
        'user' => 'admin'
    ],
    [
        'type' => 'add',
        'item' => 'Dragon Scale Armor',
        'table' => 'armor',
        'time' => '2025-05-08 09:30:14',
        'user' => 'admin'
    ],
    [
        'type' => 'delete',
        'item' => 'Weak Healing Potion',
        'table' => 'etcitem',
        'time' => '2025-05-07 16:45:10',
        'user' => 'moderator'
    ],
    [
        'type' => 'edit',
        'item' => 'Giran Castle Map',
        'table' => 'mapids',
        'time' => '2025-05-07 14:22:33',
        'user' => 'admin'
    ],
    [
        'type' => 'add',
        'item' => 'Orc Fighter',
        'table' => 'npc',
        'time' => '2025-05-07 11:18:45',
        'user' => 'admin'
    ]
];

// Define stat icons for visual enhancement
$statIcons = [
    'weapons' => 'sword',
    'armor' => 'shield-alt',
    'items' => 'flask',
    'monsters' => 'dragon',
    'maps' => 'map',
    'dolls' => 'ghost',
    'npcs' => 'user',
    'skills' => 'bolt',
    'polymorph' => 'exchange-alt'
];

// Define image paths for categories - only used in Database Overview
$statImages = [
    'weapons' => '../assets/img/placeholders/weapons.png',
    'armor' => '../assets/img/placeholders/armor.png',
    'items' => '../assets/img/placeholders/items.png',
    'monsters' => '../assets/img/placeholders/monsters.png',
    'maps' => '../assets/img/placeholders/maps.png',
    'dolls' => '../assets/img/placeholders/dolls.png',
    'npcs' => '../assets/img/placeholders/npc.png',
    'skills' => '../assets/img/placeholders/skill.png',
    'polymorph' => '../assets/img/placeholders/poly.png'
];
?>

<?php include '../includes/header.php'; ?>

<!-- Admin Header with Back Button -->
<header class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1><i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard</h1>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex justify-content-md-end">
                    <span class="me-3 d-flex align-items-center">
                        <i class="fas fa-user-circle me-2"></i> Welcome, <?= $_SESSION['admin_user_id'] ?>
                    </span>
                    <a href="logout.php" class="btn btn-outline-danger me-2">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                    <a href="../index.php" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Site
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Admin Navigation -->
<nav class="admin-navigation">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="databaseDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-database me-1"></i> Database
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="databaseDropdown">
                        <?php foreach (getCategories() as $category): ?>
                        <li>
                            <a class="dropdown-item" href="manage.php?category=<?= $category['id'] ?>">
                                <i class="fas fa-<?= $statIcons[$category['id']] ?? 'database' ?> me-2"></i> <?= $category['name'] ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">
                        <i class="fas fa-cog me-1"></i> Settings
                    </a>
                </li>
            </ul>
            
            <form class="d-flex search-form" action="search.php" method="get">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Search database" aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>

<div class="container py-4">
    <!-- Admin Welcome Card -->
    <div class="dashboard-card mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-3 text-accent">Welcome to the Admin Dashboard</h2>
                    <p class="mb-0">Manage your L1J Remastered database content, monitor statistics, and perform administrative tasks. Use the quick actions below or navigate through the categories.</p>
                </div>
                <div class="col-md-4 text-md-end text-center mt-3 mt-md-0">
                    <div class="btn-group">
                        <a href="backup.php" class="btn btn-outline-primary">
                            <i class="fas fa-database me-2"></i> Backup
                        </a>
                        <a href="import.php" class="btn btn-outline-primary">
                            <i class="fas fa-file-import me-2"></i> Import
                        </a>
                        <a href="export.php" class="btn btn-outline-primary">
                            <i class="fas fa-file-export me-2"></i> Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Overview -->
    <h3 class="mb-4"><i class="fas fa-chart-line me-2"></i> Database Overview</h3>
    <div class="row mb-4">
        <?php foreach ($stats as $category => $count): 
            $imagePath = $statImages[$category] ?? '../assets/img/placeholders/items.png';
        ?>
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="admin-stat-card position-relative">
                <div class="stat-value"><?= number_format($count) ?></div>
                <div class="stat-label"><?= ucfirst($category) ?></div>
                <img src="<?= $imagePath ?>" alt="<?= ucfirst($category) ?>" class="stat-icon">
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Quick Actions and Recent Activity -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-md-5 mb-4">
            <div class="dashboard-card h-100">
                <div class="card-header">
                    <h3 class="mb-0"><i class="fas fa-bolt me-2"></i> Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="add.php?type=weapon" class="btn btn-outline-primary d-block">
                            <i class="fas fa-sword"></i> Add New Weapon
                        </a>
                        <a href="add.php?type=armor" class="btn btn-outline-primary d-block">
                            <i class="fas fa-shield-alt"></i> Add New Armor
                        </a>
                        <a href="add.php?type=item" class="btn btn-outline-primary d-block">
                            <i class="fas fa-flask"></i> Add New Item
                        </a>
                        <a href="add.php?type=monster" class="btn btn-outline-primary d-block">
                            <i class="fas fa-dragon"></i> Add New Monster
                        </a>
                        <a href="add.php?type=npc" class="btn btn-outline-primary d-block">
                            <i class="fas fa-user"></i> Add New NPC
                        </a>
                        <a href="backup.php" class="btn btn-outline-warning d-block">
                            <i class="fas fa-database"></i> Backup Database
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="col-md-7 mb-4">
            <div class="dashboard-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-history me-2"></i> Recent Activity</h3>
                    <a href="activity.php" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <ul class="recent-activity-list">
                        <?php foreach ($recent_activity as $activity): ?>
                        <li class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="activity-type <?= $activity['type'] ?>"><?= $activity['type'] ?></span>
                                <strong><?= $activity['item'] ?></strong> in 
                                <span class="badge bg-secondary"><?= $activity['table'] ?></span> 
                                by <span class="text-accent"><?= $activity['user'] ?></span>
                            </div>
                            <span class="activity-time"><?= $activity['time'] ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Database Categories Management -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-folder me-2"></i> Database Categories</h3>
                    <a href="import.php" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-file-import me-1"></i> Import Data
                    </a>
                </div>
                <div class="card-body">
                    <div class="row category-management">
                        <?php foreach (getCategories() as $category): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-<?= $statIcons[$category['id']] ?? 'database' ?> me-2"></i>
                                        <?= $category['name'] ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong class="text-accent">Tables:</strong>
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            <?php foreach ($category['tables'] as $table): ?>
                                            <span class="badge bg-secondary"><?= $table ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <strong class="text-accent">Records:</strong> 
                                        <span class="badge bg-primary"><?= number_format($stats[$category['id']] ?? 0) ?></span>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between">
                                        <a href="manage.php?category=<?= $category['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-cog me-1"></i> Manage
                                        </a>
                                        <a href="export.php?category=<?= $category['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-file-export me-1"></i> Export
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Status -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="mb-0"><i class="fas fa-server me-2"></i> System Status</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="text-accent mb-3">Database Information</h5>
                                <div class="table-responsive">
                                    <table class="table table-dark table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th style="width: 40%;">Database Name</th>
                                                <td><?= DB_NAME ?></td>
                                            </tr>
                                            <tr>
                                                <th>Total Tables</th>
                                                <td>35</td>
                                            </tr>
                                            <tr>
                                                <th>Total Records</th>
                                                <td><?= number_format(array_sum($stats)) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Last Backup</th>
                                                <td>2025-05-07 23:00:00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="text-accent mb-3">Server Information</h5>
                                <div class="table-responsive">
                                    <table class="table table-dark table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th style="width: 40%;">PHP Version</th>
                                                <td><?= phpversion() ?></td>
                                            </tr>
                                            <tr>
                                                <th>Server Software</th>
                                                <td><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Current Time</th>
                                                <td><?= date('Y-m-d H:i:s') ?></td>
                                            </tr>
                                            <tr>
                                                <th>Memory Limit</th>
                                                <td><?= ini_get('memory_limit') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>