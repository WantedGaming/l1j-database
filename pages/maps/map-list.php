<?php
/**
 * Map List Page (Public)
 * Displays a list of all maps in the game
 */

// Include required files
require_once '../../includes/config.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/map-functions.php';

// Set current page for navigation highlighting
$currentPage = 'maps';
$pageTitle = 'Maps';

// Get page number from URL, default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Get search term if any
$searchTerm = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Get maps data with pagination
$mapsData = getMaps($conn, $page, $defaultPageSize, $searchTerm);
$maps = $mapsData['maps'];
$totalPages = $mapsData['pages'];

// Include header
include '../../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Lineage II Maps</h1>
        <p>Explore all the maps and territories in the world of Lineage II</p>
        <div class="search-box">
            <form action="<?php echo $baseUrl; ?>pages/maps/map-list.php" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Search maps by name..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
</section>

<!-- Maps List Content -->
<section class="main-content">
    <div class="container">
        <div class="list-header">
            <h2 class="list-title">Maps</h2>
            <div class="list-filter">
                <select class="filter-select" id="map-type-filter">
                    <option value="all">All Map Types</option>
                    <option value="normal">Normal Maps</option>
                    <option value="underwater">Underwater Maps</option>
                    <option value="beginner">Beginner Zones</option>
                    <option value="redknight">Red Knight Zones</option>
                </select>
            </div>
        </div>

        <?php if (empty($maps)): ?>
            <div class="no-results">
                <p>No maps found<?php echo !empty($searchTerm) ? ' matching "' . htmlspecialchars($searchTerm) . '"' : ''; ?>.</p>
            </div>
        <?php else: ?>
            <div class="item-list">
                <?php foreach ($maps as $map): ?>
                    <div class="item-row" data-map-type="<?php 
                        if ($map['underwater'] == 1) echo 'underwater';
                        elseif ($map['beginZone'] == 1) echo 'beginner';
                        elseif ($map['redKnightZone'] == 1) echo 'redknight';
                        else echo 'normal';
                    ?>">
                        <img src="<?php echo $baseUrl; ?>assets/img/map-icon.png" alt="Map" class="item-icon">
                        <div class="item-details">
                            <h3 class="item-name">
                                <a href="<?php echo $baseUrl; ?>pages/maps/map-detail.php?id=<?php echo $map['mapid']; ?>">
                                    <?php echo htmlspecialchars($map['locationname']); ?>
                                </a>
                            </h3>
                            <p class="item-description"><?php echo htmlspecialchars($map['desc_kr']); ?></p>
                        </div>
                        <div class="item-stats">
                            <span class="item-stat">ID: <?php echo $map['mapid']; ?></span>
                            <span class="item-stat"><?php echo getMapTypeName($map['underwater'], $map['beginZone'], $map['redKnightZone']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php 
                $urlPattern = $baseUrl . 'pages/maps/map-list.php?page=%d' . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '');
                echo generatePagination($page, $totalPages, $urlPattern); 
            ?>
        <?php endif; ?>
    </div>
</section>

<script>
// Simple filter functionality for map types
document.addEventListener('DOMContentLoaded', function() {
    const filterSelect = document.getElementById('map-type-filter');
    const mapRows = document.querySelectorAll('.item-row');
    
    filterSelect.addEventListener('change', function() {
        const filterValue = this.value;
        
        mapRows.forEach(row => {
            if (filterValue === 'all' || row.dataset.mapType === filterValue) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>

<?php
// Include footer
include '../../includes/footer.php';
?>