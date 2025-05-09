<?php
/**
 * Admin Map List Page - Card View
 * Displays maps in a responsive card grid with images
 */

// Include required files
require_once '../../../includes/db_connect.php';
require_once '../../../includes/map-functions.php';
require_once '../../includes/admin-config.php';

// Set current page for navigation highlighting
$currentAdminPage = 'maps';
$pageTitle = 'Map Management';

// Get page number from URL, default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Get search term if any
$searchTerm = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Handle map type filter if any
$mapTypeFilter = isset($_GET['map_type']) ? sanitizeInput($_GET['map_type']) : 'all';

// Handle map deletion if requested
$message = '';
$messageType = '';

if (isset($_POST['delete_map']) && isset($_POST['map_id'])) {
    $deleteMapId = (int)$_POST['map_id'];
    
    if (deleteMap($conn, $deleteMapId)) {
        $message = "Map #$deleteMapId has been deleted successfully.";
        $messageType = 'success';
    } else {
        $message = "Error deleting map #$deleteMapId. " . $conn->error;
        $messageType = 'danger';
    }
}

// Get maps data with pagination
$mapsData = getMaps($conn, $page, 20, $searchTerm); // Fixed at 20 per page
$maps = $mapsData['maps'];
$totalCount = $mapsData['total'];
$totalPages = $mapsData['pages'];

// Include admin header
include '../../includes/admin-header.php';
?>


<div class="container">
    <!-- Enhanced Hero Section with Search and Actions -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Map Management</h1>
                <p class="admin-hero-subtitle">Manage game world maps, locations, and their properties</p>
            </div>
            <div class="hero-actions">
                <a href="<?php echo $adminBaseUrl; ?>pages/maps/admin-map-detail.php?action=create" class="btn btn-primary">
                    <i class="fas fa-plus-circle btn-icon"></i> Add New Map
                </a>
            </div>
        </div>
        
        <div class="hero-controls">
            <form action="<?php echo $adminBaseUrl; ?>pages/maps/admin-map-list.php" method="GET" class="hero-search-form">
                <div class="search-input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search maps by name or description..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <div class="hero-filter-controls">
                <span class="filter-label">Filter by:</span>
                <select class="form-control" id="map-type-filter" onchange="filterMaps(this.value)">
                    <option value="all" <?php echo $mapTypeFilter == 'all' ? 'selected' : ''; ?>>All Map Types</option>
                    <option value="underwater" <?php echo $mapTypeFilter == 'underwater' ? 'selected' : ''; ?>>Underwater Maps</option>
                    <option value="beginner" <?php echo $mapTypeFilter == 'beginner' ? 'selected' : ''; ?>>Beginner Zones</option>
                    <option value="redknight" <?php echo $mapTypeFilter == 'redknight' ? 'selected' : ''; ?>>Red Knight Zones</option>
                    <option value="dungeon" <?php echo $mapTypeFilter == 'dungeon' ? 'selected' : ''; ?>>Dungeons</option>
                </select>
            </div>
        </div>
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <i class="fas fa-info-circle alert-icon"></i>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <!-- Maps Card Grid -->
    <?php if (empty($maps)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle alert-icon"></i>
            No maps found<?php echo !empty($searchTerm) ? ' matching "' . htmlspecialchars($searchTerm) . '"' : ''; ?>.
        </div>
    <?php else: ?>
        <div class="admin-card-grid">
            <?php foreach ($maps as $map): ?>
                <div class="admin-map-card" data-map-type="<?php 
                    if (isset($map['underwater']) && $map['underwater'] == 1) echo 'underwater';
                    elseif (isset($map['beginZone']) && $map['beginZone'] == 1) echo 'beginner';
                    elseif (isset($map['redKnightZone']) && $map['redKnightZone'] == 1) echo 'redknight';
                    elseif (isset($map['dungeon']) && $map['dungeon'] == 1) echo 'dungeon';
                    else echo 'normal';
                ?>">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title"><?php echo htmlspecialchars($map['locationname']); ?></h3>
                    </div>
                    <div class="admin-card-img">
                        <img src="<?php echo isset($map['pngId']) ? getMapImagePath($map['pngId']) : getMapImagePath(0); ?>" alt="<?php echo htmlspecialchars($map['locationname']); ?>">
                    </div>
                    <div class="admin-card-info">
                        <div class="admin-card-id">ID: <?php echo $map['mapid']; ?></div>
                        <div class="admin-card-desc"><?php echo isset($map['desc_kr']) ? htmlspecialchars($map['desc_kr']) : ''; ?></div>
                        <div class="admin-card-type"><?php 
                            if (function_exists('getMapTypeName')) {
                                echo getMapTypeName(
                                    isset($map['underwater']) ? $map['underwater'] : 0, 
                                    isset($map['beginZone']) ? $map['beginZone'] : 0, 
                                    isset($map['redKnightZone']) ? $map['redKnightZone'] : 0
                                );
                            } else {
                                if (isset($map['underwater']) && $map['underwater'] == 1) echo "Underwater";
                                elseif (isset($map['beginZone']) && $map['beginZone'] == 1) echo "Beginner Zone";
                                elseif (isset($map['redKnightZone']) && $map['redKnightZone'] == 1) echo "Red Knight Zone";
                                else echo "Normal";
                            }
                        ?></div>
                    </div>
                    <div class="admin-card-actions">
                        <a href="<?php echo $adminBaseUrl; ?>pages/maps/admin-map-detail.php?id=<?php echo $map['mapid']; ?>&action=edit" class="btn btn-sm btn-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $map['mapid']; ?>, '<?php echo addslashes($map['locationname']); ?>')">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- Pagination -->
    <?php 
        $urlPattern = $adminBaseUrl . 'pages/maps/admin-map-list.php?page=%d' . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '') . (!empty($mapTypeFilter) && $mapTypeFilter != 'all' ? '&map_type=' . urlencode($mapTypeFilter) : '');
        if (function_exists('generateAdminPagination')) {
            echo generateAdminPagination($page, $totalPages, $totalCount, $urlPattern);
        } else {
            // Simple pagination if the function doesn't exist
            echo '<div class="admin-pagination">';
            if ($page > 1) {
                echo '<a href="' . sprintf($urlPattern, $page - 1) . '" class="btn btn-secondary">&laquo; Previous</a>';
            }
            if ($page < $totalPages) {
                echo '<a href="' . sprintf($urlPattern, $page + 1) . '" class="btn btn-secondary">Next &raquo;</a>';
            }
            echo '</div>';
        }
    ?>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Deletion</h3>
                <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the map "<span id="deleteMapName"></span>" (ID: <span id="deleteMapId"></span>)?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form action="" method="POST">
                    <input type="hidden" name="map_id" id="deleteMapIdInput" value="">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" name="delete_map" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Map type filter functionality
function filterMaps(filterValue) {
    // Redirect with the filter applied through URL
    window.location.href = '<?php echo $adminBaseUrl; ?>pages/maps/admin-map-list.php?map_type=' + filterValue + 
        '<?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>';
}

// For client-side filtering without page reload (for immediate visual feedback)
document.addEventListener('DOMContentLoaded', function() {
    const filterSelect = document.getElementById('map-type-filter');
    const mapCards = document.querySelectorAll('.admin-map-card');
    
    // Apply initial filtering based on URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const mapTypeParam = urlParams.get('map_type');
    
    if (mapTypeParam && mapTypeParam !== 'all') {
        mapCards.forEach(card => {
            if (card.dataset.mapType !== mapTypeParam) {
                card.style.display = 'none';
            }
        });
    }
    
    // Event listener for immediate visual feedback
    filterSelect.addEventListener('change', function() {
        const filterValue = this.value;
        
        mapCards.forEach(card => {
            if (filterValue === 'all' || card.dataset.mapType === filterValue) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Modal functions
function confirmDelete(mapId, mapName) {
    document.getElementById('deleteMapId').textContent = mapId;
    document.getElementById('deleteMapName').textContent = mapName;
    document.getElementById('deleteMapIdInput').value = mapId;
    document.getElementById('deleteModal').classList.add('show');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('show');
}
</script>

<?php
// Include admin footer
include '../../includes/admin-footer.php';
?>