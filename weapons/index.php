<?php
/**
 * Weapons List Page
 * Displays a paginated list of all weapons
 */

// Include database connection
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Get current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1

// Set page title
$pageTitle = 'Weapons';
$showHero = false;

// Set items per page
$itemsPerPage = 20;

// Get all weapons with pagination
$result = getItemsWithPagination(
    $pdo,
    'weapon',
    ['item_id', 'name', 'desc_en', 'type', 'material', 'weight', 'dmg_small', 'dmg_large', 'iconId'],
    '',
    [],
    $page,
    $itemsPerPage
);

$weapons = $result['items'];
$pagination = $result['pagination'];

// Include header
include '../includes/header.php';
?>

<div class="mb-6">
    <h1 class="section-title">Weapons</h1>
    
    <div class="mb-4">
        <p>Browse all weapons in the L1J database. Click on a weapon to view detailed information.</p>
    </div>
    
    <div class="card p-4 mb-6">
        <div class="flex flex-wrap items-center">
            <div class="mr-4 mb-2">
                <label for="weaponTypeFilter" class="mr-2">Filter by Type:</label>
                <select id="weaponTypeFilter" class="form-select" onchange="filterTable('weaponTypeFilter', 'weaponsTable')">
                    <option value="">All Types</option>
                    <option value="sword">Swords</option>
                    <option value="dagger">Daggers</option>
                    <option value="bow">Bows</option>
                    <option value="staff">Staves</option>
                    <option value="axe">Axes</option>
                </select>
            </div>
            
            <div class="mr-4 mb-2">
                <label for="searchInput" class="mr-2">Search:</label>
                <input type="text" id="searchInput" class="form-input" placeholder="Search weapons..." onkeyup="filterTable('searchInput', 'weaponsTable')">
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <table id="weaponsTable" class="data-table">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Material</th>
                    <th>Damage</th>
                    <th>Weight</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($weapons as $weapon): ?>
                <tr>
                    <td>
                        <img src="<?php echo getImagePath('weapon', $weapon['iconId']); ?>" alt="<?php echo htmlspecialchars($weapon['desc_en']); ?>" class="item-icon">
                    </td>
                    <td><?php echo htmlspecialchars($weapon['desc_en']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($weapon['type'])); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($weapon['material'])); ?></td>
                    <td><?php echo $weapon['dmg_small'] . '-' . $weapon['dmg_large']; ?></td>
                    <td><?php echo formatNumber($weapon['weight'] / 1000); ?></td>
                    <td>
                        <a href="/weapons/detail.php?id=<?php echo $weapon['item_id']; ?>" class="btn btn-small">View Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($pagination['total_pages'] > 1): ?>
    <div class="pagination">
        <?php if ($pagination['current_page'] > 1): ?>
        <div class="page-item">
            <a href="?page=<?php echo $pagination['current_page'] - 1; ?>" class="page-link">←</a>
        </div>
        <?php endif; ?>
        
        <?php
        // Calculate range of pages to show
        $start = max(1, $pagination['current_page'] - 2);
        $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
        
        // Always show first page
        if ($start > 1) {
            echo '<div class="page-item"><a href="?page=1" class="page-link">1</a></div>';
            if ($start > 2) {
                echo '<div class="page-item">...</div>';
            }
        }
        
        // Show page links
        for ($i = $start; $i <= $end; $i++) {
            echo '<div class="page-item">';
            echo '<a href="?page=' . $i . '" class="page-link' . ($i == $pagination['current_page'] ? ' active' : '') . '">' . $i . '</a>';
            echo '</div>';
        }
        
        // Always show last page
        if ($end < $pagination['total_pages']) {
            if ($end < $pagination['total_pages'] - 1) {
                echo '<div class="page-item">...</div>';
            }
            echo '<div class="page-item"><a href="?page=' . $pagination['total_pages'] . '" class="page-link">' . $pagination['total_pages'] . '</a></div>';
        }
        ?>
        
        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
        <div class="page-item">
            <a href="?page=<?php echo $pagination['current_page'] + 1; ?>" class="page-link">→</a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php
// Include footer
include '../includes/footer.php';
?>
