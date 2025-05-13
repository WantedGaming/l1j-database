<?php
/**
 * Admin Dashboard Home
 * Displays statistics and quick access to different database sections
 */

// Include admin configuration and database connection
require_once '../includes/db_connect.php';
require_once 'includes/admin-config.php';

// Set current page for navigation highlighting
$currentAdminPage = 'dashboard';
$pageTitle = 'Admin Dashboard';

// Get database statistics
$stats = [];

// Get total weapons count
$weaponQuery = "SELECT COUNT(*) as count FROM weapon";
$weaponResult = executeQuery($weaponQuery, $conn);
$stats['weapons'] = $weaponResult ? $weaponResult->fetch_assoc()['count'] : 0;

// Get total armor count
$armorQuery = "SELECT COUNT(*) as count FROM armor";
$armorResult = executeQuery($armorQuery, $conn);
$stats['armor'] = $armorResult ? $armorResult->fetch_assoc()['count'] : 0;

// Get total items count
$itemQuery = "SELECT COUNT(*) as count FROM etcitem";
$itemResult = executeQuery($itemQuery, $conn);
$stats['items'] = $itemResult ? $itemResult->fetch_assoc()['count'] : 0;

// Get total monsters count
$monsterQuery = "SELECT COUNT(*) as count FROM npc WHERE impl = 'L1Monster'";
$monsterResult = executeQuery($monsterQuery, $conn);
$stats['monsters'] = $monsterResult ? $monsterResult->fetch_assoc()['count'] : 0;

// Get total maps count
$mapQuery = "SELECT COUNT(*) as count FROM mapids";
$mapResult = executeQuery($mapQuery, $conn);
$stats['maps'] = $mapResult ? $mapResult->fetch_assoc()['count'] : 0;

// Get total dolls count
$dollQuery = "SELECT COUNT(*) as count FROM magicdoll_info";
$dollResult = executeQuery($dollQuery, $conn);
$stats['dolls'] = $dollResult ? $dollResult->fetch_assoc()['count'] : 0;

// Get total NPCs count
$npcQuery = "SELECT COUNT(*) as count FROM npc WHERE impl = 'L1Npc' OR impl = 'L1Merchant'";
$npcResult = executeQuery($npcQuery, $conn);
$stats['npcs'] = $npcResult ? $npcResult->fetch_assoc()['count'] : 0;

// Get total skills count
$skillQuery = "SELECT COUNT(*) as count FROM skills";
$skillResult = executeQuery($skillQuery, $conn);
$stats['skills'] = $skillResult ? $skillResult->fetch_assoc()['count'] : 0;

// Get total polymorph count
$polyQuery = "SELECT COUNT(*) as count FROM polymorphs";
$polyResult = executeQuery($polyQuery, $conn);
$stats['polymorph'] = $polyResult ? $polyResult->fetch_assoc()['count'] : 0;

// Get total accounts count
$accountQuery = "SELECT COUNT(*) as count FROM accounts";
$accountResult = executeQuery($accountQuery, $conn);
$stats['accounts'] = $accountResult ? $accountResult->fetch_assoc()['count'] : 0;

// Get total drops count
$dropQuery = "SELECT COUNT(*) as count FROM droplist";
$dropResult = executeQuery($dropQuery, $conn);
$stats['drops'] = $dropResult ? $dropResult->fetch_assoc()['count'] : 0;

// Get total spawns count
$spawnTables = [
    'spawnlist',
    'spawnlist_ub',
    'spawnlist_unicorntemple',
    'spawnlist_worldwar',
    'spawnlist_other',
    'spawnlist_ruun',
    'spawnlist_boss'
];

$totalSpawns = 0;

foreach ($spawnTables as $table) {
    $query = "SELECT COUNT(*) as count FROM $table";
    $result = executeQuery($query, $conn);
    if ($result) {
        $totalSpawns += $result->fetch_assoc()['count'];
    }
}

$stats['total_spawns'] = $totalSpawns;

// Calculate total database entries
$totalEntries = array_sum($stats);

// Include header
include 'includes/admin-header.php';
?>

<div class="container">
    <!-- Admin Hero Section -->
    <section class="admin-hero">
        <div class="admin-hero-content">
            <h1 class="admin-hero-title">Lineage II Database Administration</h1>
            <p class="admin-hero-subtitle">Manage game content across <?php echo number_format($totalEntries); ?> database entries</p>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-sword"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo number_format($stats['weapons']); ?></div>
                    <div class="stat-label">Weapons</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo number_format($stats['armor']); ?></div>
                    <div class="stat-label">Armor</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo number_format($stats['items']); ?></div>
                    <div class="stat-label">Items</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dragon"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo number_format($stats['monsters']); ?></div>
                    <div class="stat-label">Monsters</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Database Management Cards -->
    <section class="admin-sections">
        <h2 class="section-title mb-4">Database Management</h2>
        <div class="admin-card-grid">
            <!-- Weapons Management Card -->
            <a href="<?php echo $adminBaseUrl; ?>pages/weapons/admin-weapon-list.php" class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Weapons</h2>
                </div>
                <div class="admin-card-img">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/weapon.png" alt="Weapon" width="80" height="80">
                </div>
                <div class="admin-card-body">
                    <div class="admin-card-count"><?php echo number_format($stats['weapons']); ?> Items</div>
                </div>
            </a>
            
            <!-- Armor Management Card -->
            <a href="<?php echo $adminBaseUrl; ?>pages/armor/admin-armor-list.php" class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Armor</h2>
                </div>
                <div class="admin-card-img">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/armor.png" alt="Armor" width="80" height="80">
                </div>
                <div class="admin-card-body">
                    <div class="admin-card-count"><?php echo number_format($stats['armor']); ?> Items</div>
                </div>
            </a>
            
            <!-- Items Management Card -->
            <a href="<?php echo $adminBaseUrl; ?>pages/items/admin-item-list.php" class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Items</h2>
                </div>
                <div class="admin-card-img">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/items.png" alt="Items" width="80" height="80">
                </div>
                <div class="admin-card-body">
                    <div class="admin-card-count"><?php echo number_format($stats['items']); ?> Items</div>
                </div>
            </a>
            
            <!-- Monsters Management Card -->
            <a href="<?php echo $adminBaseUrl; ?>pages/monsters/admin-monster-list.php" class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Monsters</h2>
                </div>
                <div class="admin-card-img">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/monsters.png" alt="Monsters" width="80" height="80">
                </div>
                <div class="admin-card-body">
                    <div class="admin-card-count"><?php echo number_format($stats['monsters']); ?> Entries</div>
                </div>
            </a>
            
            <!-- Maps Management Card -->
            <a href="<?php echo $adminBaseUrl; ?>pages/maps/admin-map-list.php" class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Maps</h2>
                </div>
                <div class="admin-card-img">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/maps.png" alt="Maps" width="80" height="80">
                </div>
                <div class="admin-card-body">
                    <div class="admin-card-count"><?php echo number_format($stats['maps']); ?> Entries</div>
                </div>
            </a>
            
            <!-- Dolls Management Card -->
            <a href="<?php echo $adminBaseUrl; ?>pages/dolls/admin-doll-list.php" class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Magic Dolls</h2>
                </div>
                <div class="admin-card-img">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/magic_dolls.png" alt="Magic Dolls" width="80" height="80">
                </div>
                <div class="admin-card-body">
                    <div class="admin-card-count"><?php echo number_format($stats['dolls']); ?> Items</div>
                </div>
            </a>
            
            <!-- NPCs Management Card -->
            <a href="<?php echo $adminBaseUrl; ?>pages/npcs/admin-npc-list.php" class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">NPCs</h2>
                </div>
                <div class="admin-card-img">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/npc.png" alt="NPCs" width="80" height="80">
                </div>
                <div class="admin-card-body">
                    <div class="admin-card-count"><?php echo number_format($stats['npcs']); ?> Characters</div>
                </div>
            </a>
            
            <!-- Skills Management Card -->
            <a href="<?php echo $adminBaseUrl; ?>pages/skills/admin-skill-list.php" class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Skills</h2>
                </div>
                <div class="admin-card-img">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/skills.png" alt="Skills" width="80" height="80">
                </div>
                <div class="admin-card-body">
                    <div class="admin-card-count"><?php echo number_format($stats['skills']); ?> Abilities</div>
                </div>
            </a>
            
            <!-- Polymorph Management Card -->
            <a href="<?php echo $adminBaseUrl; ?>pages/polymorph/admin-polymorph-list.php" class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Polymorph</h2>
                </div>
                <div class="admin-card-img">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/polymorphs.png" alt="Polymorph" width="80" height="80">
                </div>
                <div class="admin-card-body">
                    <div class="admin-card-count"><?php echo number_format($stats['polymorph']); ?> Forms</div>
                </div>
            </a>
            
            <!-- Accounts & Character Management Card -->
            <a href="<?php echo $adminBaseUrl; ?>pages/accounts/account-list.php" class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">Accounts</h2>
                </div>
                <div class="admin-card-img">
                    <img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/accounts.png" alt="Accounts" width="80" height="80">
                </div>
                <div class="admin-card-body">
                    <div class="admin-card-count"><?php echo number_format($stats['accounts']); ?> Users</div>
                </div>
            </a>
        </div>
    </section>
	
	<!-- Current structure with separate admin-card-grid divs for each card -->
		<section class="admin-sections">
			<h2 class="section-title mb-4">Insert Spawn & Drops</h2>
			<div class="admin-card-grid equal-width-cards">
				<a href="<?php echo $adminBaseUrl; ?>pages/spawns/spawn-list.php" class="admin-card">
					<div class="admin-card-header">
						<h2 class="admin-card-title">Spawns</h2>
					</div>
					<div class="admin-card-img">
						<img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/spawns.png" alt="Spawns" width="80" height="80">
					</div>
					<div class="admin-card-body">
						<div class="admin-card-count"><?php echo number_format($stats['total_spawns']); ?> Items</div>
					</div>
				</a>
				
				<a href="<?php echo $adminBaseUrl; ?>pages/drops/drop-list.php" class="admin-card">
					<div class="admin-card-header">
						<h2 class="admin-card-title">Drops</h2>
					</div>
					<div class="admin-card-img">
						<img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/drops.png" alt="Drops" width="80" height="80">
					</div>
					<div class="admin-card-body">
						<div class="admin-card-count"><?php echo number_format($stats['drops']); ?> Items</div>
					</div>
				</a>
				
				<a href="<?php echo $adminBaseUrl; ?>pages/monsters/admin-monster-list.php" class="admin-card">
					<div class="admin-card-header">
						<h2 class="admin-card-title">Monsters</h2>
					</div>
					<div class="admin-card-img">
						<img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/monsters2.png" alt="Monsters" width="80" height="80">
					</div>
					<div class="admin-card-body">
						<div class="admin-card-count"><?php echo number_format($stats['monsters']); ?> Items</div>
					</div>
				</a>
			</div>
			
			<div class="admin-card-grid equal-width-cards mt-4">
					<a href="<?php echo $adminBaseUrl; ?>pages/npcs/npc_list.php" class="admin-card">
						<div class="admin-card-header">
							<h2 class="admin-card-title">NPC Data</h2>
						</div>
						<div class="admin-card-img">
							<img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/npc.png" alt="NPC" width="80" height="80">
						</div>
						<div class="admin-card-body">
							<div class="admin-card-count">NPC Data</div>
						</div>
					</a>
					
					<a href="<?php echo $adminBaseUrl; ?>tools/tools-index.php" class="admin-card">
						<div class="admin-card-header">
							<h2 class="admin-card-title">DB Tools</h2>
						</div>
						<div class="admin-card-img">
							<img src="<?php echo $baseUrl; ?>assets/img/placeholders/admin_dashboard/settings.png" alt="Setting" width="80" height="80">
						</div>
						<div class="admin-card-body">
							<div class="admin-card-count">Database Tools</div>
						</div>
					</a>
				</div>
			
		</section>
    
    <!-- Quick Actions Section -->
    <section class="admin-sections">
        <h2 class="section-title mb-4">Quick Actions</h2>
        <div class="quick-actions-container">
            <div class="form-container">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="alert alert-info">
                                <div class="alert-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div>
                                    <strong>Welcome to the admin dashboard!</strong>
                                    <p>From here you can manage all aspects of the Lineage II database.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Quick Search</label>
                            <div class="flex gap-2">
                                <input type="text" class="form-control" placeholder="Search database...">
                                <button class="btn btn-primary">
                                    <i class="fas fa-search btn-icon"></i>
                                    Search
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="flex gap-2">
                                <a href="<?php echo $adminBaseUrl; ?>tools/backup.php" class="btn btn-secondary">
                                    <i class="fas fa-database btn-icon"></i>
                                    Backup Database
                                </a>
                                <a href="<?php echo $adminBaseUrl; ?>tools/maintenance.php" class="btn btn-secondary">
                                    <i class="fas fa-wrench btn-icon"></i>
                                    Maintenance
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
// Include footer
include 'includes/admin-footer.php';
?>