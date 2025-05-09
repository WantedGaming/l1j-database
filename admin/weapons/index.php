<?php
/**
 * Admin Weapons Management
 * List and manage all weapons in the database
 */

// Include database connection
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

// Set page title
$pageTitle = 'Admin - Manage Weapons';
$showHero = false;

// Extra CSS for admin
$extraCSS = ['../../assets/css/admin.css'];

// Get current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1

// Set items per page
$itemsPerPage = 20;

// Get all weapons with pagination
$result = getItemsWithPagination(
    $pdo,
    'weapon',
    ['item_id', 'desc_en', 'type', 'material', 'weight', 'dmg_small', 'dmg_large', 'iconId'],
    '',
    [],
    $page,
    $itemsPerPage
);

$weapons = $result['items'];
$pagination = $result['pagination'];

// Process delete action if requested
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $weaponId = (int)$_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM weapon WHERE item_id = :id");
        $stmt->bindValue(':id', $weaponId);
        $stmt->execute();
        
        // Redirect back to weapons list after deletion
        header('Location: ./index.php?deleted=true');
        exit;
    } catch (PDOException $e) {
        $errorMessage = "Error deleting weapon: " . $e->getMessage();
    }
}

// Include admin header instead of regular header
include '../../includes/admin-header.php';
?>

<div class="admin-page-header mb-6 p-4">
    <div class="container">
        <div class="flex items-center justify-between">
            <h1 class="section-title">Manage Weapons</h1>
            <div>
                <a href="../" class="btn btn-small mr-2">← Dashboard</a>
                <a href="add.php" class="btn btn-primary">+ Add New Weapon</a>
            </div>
        </div>
    </div>
</div>

<div class="mb-6">
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] === 'true'): ?>
    <div class="card p-4 mb-4 bg-accent">
        <p>Weapon successfully deleted.</p>
    </div>
    <?php endif; ?>
    
    <?php if (isset($errorMessage)): ?>
    <div class="card p-4 mb-4" style="background-color: #ff4a4a;">
        <p><?php echo htmlspecialchars($errorMessage); ?></p>
    </div>
    <?php endif; ?>
    
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
                    <th>ID</th>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Damage</th>
                    <th>Material</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($weapons as $weapon): ?>
                <tr>
                    <td><?php echo $weapon['item_id']; ?></td>
                    <td>
                        <img src="<?php echo getImagePath('weapon', $weapon['iconId']); ?>" alt="<?php echo htmlspecialchars($weapon['desc_en']); ?>" class="item-icon">
                    </td>
                    <td><?php echo htmlspecialchars($weapon['desc_en']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($weapon['type'])); ?></td>
                    <td><?php echo $weapon['dmg_small'] . '-' . $weapon['dmg_large']; ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($weapon['material'])); ?></td>
                    <td>
                        <div class="flex">
                            <a href="../../weapons/detail.php?id=<?php echo $weapon['item_id']; ?>" class="btn btn-small mr-2" title="View Details">View</a>
                            <a href="edit.php?id=<?php echo $weapon['item_id']; ?>" class="btn btn-small mr-2" title="Edit Weapon">Edit</a>
                            <a href="#" onclick="confirmDelete(<?php echo $weapon['item_id']; ?>, '<?php echo addslashes($weapon['desc_en']); ?>')" class="btn btn-small" title="Delete Weapon">Delete</a>
                        </div>
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

<script>
function confirmDelete(id, name) {
    if (confirm(`Are you sure you want to delete the weapon "${name}"? This action cannot be undone.`)) {
        window.location.href = `./index.php?action=delete&id=${id}`;
    }
}
</script>

<?php
// Include admin footer instead of regular footer
include '../../includes/admin-footer.php';
?>