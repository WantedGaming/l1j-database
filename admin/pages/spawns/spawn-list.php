<?php
/**
 * Admin Spawn List Page
 * Displays all spawns categorized by type
 */

// Include required files
require_once '../../../includes/db_connect.php';
require_once '../../includes/admin-config.php';
require_once '../../includes/spawn-functions.php';

// Set current page for navigation highlighting
$currentAdminPage = 'spawns';
$pageTitle = 'Spawn Management';

// Get page number from URL, default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Get search term if any
$searchTerm = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Get spawn statistics
$spawnStats = getSpawnStatistics($conn);

// Include admin header
include '../../includes/admin-header.php';
?>

<div class="page-container">
    <!-- Breadcrumb navigation -->
    <div class="breadcrumb">
        <a href="<?php echo $adminBaseUrl; ?>">Dashboard</a>
        <span class="separator">/</span>
        <span class="current">Spawn Management</span>
    </div>

    <!-- Enhanced Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Spawn Management</h1>
                <p class="admin-hero-subtitle">Manage all spawn types in the game world</p>
            </div>
        </div>
        
        <div class="hero-controls">
            <form action="<?php echo $adminBaseUrl; ?>pages/spawns/spawn-list.php" method="GET" class="hero-search-form">
                <div class="search-input-group">
                    <input type="text" name="search" placeholder="Search spawns by name..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <div class="hero-actions">
                <a href="<?php echo $adminBaseUrl; ?>pages/spawns/spawn-edit.php?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Spawn
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-section mb-5">
        <div class="stats-cards">
            <!-- Total Spawns Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dragon"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= number_format($spawnStats['total']) ?></div>
                    <div class="stat-label">Total Spawns</div>
                </div>
            </div>
            
            <!-- Regular Spawns Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= number_format($spawnStats['regular']) ?></div>
                    <div class="stat-label">Regular Spawns</div>
                </div>
            </div>
            
            <!-- Boss Spawns Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= number_format($spawnStats['boss']) ?></div>
                    <div class="stat-label">Boss Spawns</div>
                </div>
            </div>
            
            <!-- UB Spawns Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-fort-awesome"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?= number_format($spawnStats['ub']) ?></div>
                    <div class="stat-label">UB Spawns</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Spawn Tables Tabs -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">All Spawns</div>
        </div>
        <div class="card-body">
            <div class="tabbed-card">
                <div class="tabs-container">
                    <div class="tab-buttons">
                        <button class="tab-button active" data-tab="regular">Regular</button>
                        <button class="tab-button" data-tab="boss">Boss</button>
                        <button class="tab-button" data-tab="ub">UB</button>
                        <button class="tab-button" data-tab="worldwar">World War</button>
                        <button class="tab-button" data-tab="other">Other</button>
                    </div>
                </div>
                <div class="tab-content-container">
                    <!-- Regular Spawns Tab -->
                    <div class="tab-content active" id="regular">
                        <?php 
                        $regularSpawns = getSpawnsByType($conn, 'spawnlist', $page, 15, $searchTerm);
                        ?>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>NPC ID</th>
                                        <th>Count</th>
                                        <th>Location</th>
                                        <th>Respawn</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($regularSpawns['data'] as $spawn): ?>
                                    <tr>
                                        <td><?= $spawn['id'] ?></td>
                                        <td><?= htmlspecialchars($spawn['name']) ?></td>
                                        <td><?= $spawn['npc_templateid'] ?></td>
                                        <td><?= $spawn['count'] ?></td>
                                        <td>
                                            <?= $spawn['locx'] ?>, <?= $spawn['locy'] ?> 
                                            (Map: <?= $spawn['mapid'] ?>)
                                        </td>
                                        <td>
                                            <?= $spawn['min_respawn_delay']/60 ?>-<?= $spawn['max_respawn_delay']/60 ?> min
                                        </td>
                                        <td>
                                            <a href="<?= $adminBaseUrl ?>pages/spawns/spawn-edit.php?id=<?= $spawn['id'] ?>&type=regular" class="btn-outline-small">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn-outline-small danger" onclick="confirmDelete(<?= $spawn['id'] ?>, 'regular')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($regularSpawns['pages'] > 1): ?>
                        <div class="pagination-container">
                            <?php 
                            $urlPattern = $adminBaseUrl . 'pages/spawns/spawn-list.php?page=%d' . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '') . '#regular';
                            echo generateAdminPagination($page, $regularSpawns['pages'], $regularSpawns['total'], $urlPattern);
                            ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Other tabs would follow the same pattern -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Confirm Deletion</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this spawn? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn btn-primary danger" id="confirmDeleteBtn">Delete</button>
        </div>
    </div>
</div>

<script>
// Tab functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        // Remove active class from all buttons and tabs
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        
        // Add active class to clicked button and corresponding tab
        button.classList.add('active');
        const tabId = button.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
});

// Delete confirmation
let spawnToDelete = null;
let spawnTypeToDelete = null;

function confirmDelete(id, type) {
    spawnToDelete = id;
    spawnTypeToDelete = type;
    document.getElementById('deleteModal').classList.add('show');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
    if (spawnToDelete && spawnTypeToDelete) {
        window.location.href = `<?= $adminBaseUrl ?>pages/spawns/spawn-delete.php?id=${spawnToDelete}&type=${spawnTypeToDelete}`;
    }
});
</script>

<?php include '../../includes/admin-footer.php'; ?>