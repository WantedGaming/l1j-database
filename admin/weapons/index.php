<?php
// Set page variables
$pageTitle = 'Manage Weapons';
$pageSubtitle = 'View, edit, and delete weapons data';
$showHero = true;
$showSearch = true;
$searchCategory = 'weapons';
$searchAction = '/admin/weapons/';
$pageSection = 'Admin Weapons';

// Include database connection and functions
require_once __DIR__ . '/../../includes/config/database.php';
require_once __DIR__ . '/../../includes/functions/common.php';

// Include admin header
require_once __DIR__ . '/../../includes/layouts/admin_header.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize pagination variables
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 20;
$offset = ($page - 1) * $recordsPerPage;

// Initialize search/filter variables
$search = isset($_GET['q']) ? sanitize($_GET['q']) : '';
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$type = isset($_GET['type']) ? sanitize($_GET['type']) : '';
$minLevel = isset($_GET['min_level']) ? (int)$_GET['min_level'] : 0;
$maxLevel = isset($_GET['max_level']) ? (int)$_GET['max_level'] : 0;
$orderBy = isset($_GET['order_by']) ? sanitize($_GET['order_by']) : 'name';
$orderDir = isset($_GET['order_dir']) && strtolower($_GET['order_dir']) === 'desc' ? 'DESC' : 'ASC';

// Build the base SQL query
$sql = "SELECT * FROM weapon WHERE 1=1";
$countSql = "SELECT COUNT(*) AS total FROM weapon WHERE 1=1";

// Add search conditions
$params = [];
if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR name_id LIKE ?)";
    $countSql .= " AND (name LIKE ? OR name_id LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Add filter conditions
if (!empty($category)) {
    $sql .= " AND type = ?";
    $countSql .= " AND type = ?";
    $params[] = $category;
}

if (!empty($type)) {
    $sql .= " AND weapon_type = ?";
    $countSql .= " AND weapon_type = ?";
    $params[] = $type;
}

if ($minLevel > 0) {
    $sql .= " AND level_min >= ?";
    $countSql .= " AND level_min >= ?";
    $params[] = $minLevel;
}

if ($maxLevel > 0) {
    $sql .= " AND level_min <= ?";
    $countSql .= " AND level_min <= ?";
    $params[] = $maxLevel;
}

// Add ordering
$sql .= " ORDER BY $orderBy $orderDir";

// Add pagination
$sql .= " LIMIT $offset, $recordsPerPage";

// Prepare and execute count query
$countStmt = $db->prepare($countSql);
for ($i = 0; $i < count($params); $i++) {
    $countStmt->bindParam($i + 1, $params[$i]);
}
$countStmt->execute();
$totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Prepare and execute main query
$stmt = $db->prepare($sql);
for ($i = 0; $i < count($params); $i++) {
    $stmt->bindParam($i + 1, $params[$i]);
}
$stmt->execute();
$weapons = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get weapon categories for filter
$categoriesStmt = $db->query("SELECT DISTINCT type FROM weapon ORDER BY type");
$weaponCategories = $categoriesStmt->fetchAll(PDO::FETCH_COLUMN);

// Get weapon types for filter
$typesStmt = $db->query("SELECT DISTINCT weapon_type FROM weapon ORDER BY weapon_type");
$weaponTypes = $typesStmt->fetchAll(PDO::FETCH_COLUMN);

// Process bulk actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action']) && isset($_POST['selected_ids'])) {
    $bulkAction = $_POST['bulk_action'];
    $selectedIds = $_POST['selected_ids'];
    
    if (is_array($selectedIds) && count($selectedIds) > 0) {
        // Generate placeholders for IN clause
        $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
        
        if ($bulkAction === 'delete') {
            try {
                // Delete selected weapons
                $bulkDeleteStmt = $db->prepare("DELETE FROM weapon WHERE item_id IN ($placeholders)");
                foreach ($selectedIds as $i => $id) {
                    $bulkDeleteStmt->bindValue($i + 1, (int)$id);
                }
                $bulkDeleteStmt->execute();
                
                // Set success message
                $_SESSION['flash_message'] = count($selectedIds) . " weapons have been deleted successfully.";
                $_SESSION['flash_type'] = 'success';
                
                // Redirect to refresh the page
                redirect('/admin/weapons/');
            } catch (PDOException $e) {
                $_SESSION['flash_message'] = "Error deleting weapons: " . $e->getMessage();
                $_SESSION['flash_type'] = 'error';
            }
        }
    }
}
?>

<div class="admin-header-actions">
    <h2>Weapons Management</h2>
    
    <div class="admin-actions">
        <a href="/admin/weapons/create.php" class="btn">Add New Weapon</a>
    </div>
</div>

<div class="list-view">
    <div class="list-header">
        <h3>Weapons List</h3>
        
        <div class="list-filters">
            <form action="/admin/weapons/" method="GET">
                <?php if (!empty($search)): ?>
                <input type="hidden" name="q" value="<?php echo htmlspecialchars($search); ?>">
                <?php endif; ?>
                
                <select name="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($weaponCategories as $cat): ?>
                    <option value="<?php echo $cat; ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                        <?php echo ucfirst($cat); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                
                <select name="type" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <?php foreach ($weaponTypes as $t): ?>
                    <option value="<?php echo $t; ?>" <?php echo $type === $t ? 'selected' : ''; ?>>
                        <?php echo ucfirst($t); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                
                <input type="number" name="min_level" placeholder="Min Level" value="<?php echo $minLevel > 0 ? $minLevel : ''; ?>">
                <input type="number" name="max_level" placeholder="Max Level" value="<?php echo $maxLevel > 0 ? $maxLevel : ''; ?>">
                
                <select name="order_by" onchange="this.form.submit()">
                    <option value="name" <?php echo $orderBy === 'name' ? 'selected' : ''; ?>>Name</option>
                    <option value="level_min" <?php echo $orderBy === 'level_min' ? 'selected' : ''; ?>>Level</option>
                    <option value="dmg_large" <?php echo $orderBy === 'dmg_large' ? 'selected' : ''; ?>>Damage</option>
                    <option value="item_id" <?php echo $orderBy === 'item_id' ? 'selected' : ''; ?>>ID</option>
                </select>
                
                <select name="order_dir" onchange="this.form.submit()">
                    <option value="asc" <?php echo $orderDir === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                    <option value="desc" <?php echo $orderDir === 'DESC' ? 'selected' : ''; ?>>Descending</option>
                </select>
                
                <button type="submit">Filter</button>
                <button type="button" class="filter-reset">Reset</button>
            </form>
        </div>
    </div>
    
    <form method="POST" action="/admin/weapons/">
        <div class="bulk-actions">
            <select name="bulk_action" id="bulk-action">
                <option value="">Bulk Actions</option>
                <option value="delete">Delete</option>
            </select>
            <button type="submit" class="btn btn-secondary">Apply</button>
            <span class="selected-count">0</span> items selected
        </div>
        
        <table class="list-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Level</th>
                    <th>Damage (S/L)</th>
                    <th>Material</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($weapons) > 0): ?>
                    <?php foreach ($weapons as $weapon): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_ids[]" value="<?php echo $weapon['item_id']; ?>"></td>
                        <td><?php echo $weapon['item_id']; ?></td>
                        <td><?php echo htmlspecialchars($weapon['name']); ?></td>
                        <td><?php echo ucfirst($weapon['weapon_type']); ?></td>
                        <td><?php echo $weapon['level_min']; ?></td>
                        <td><?php echo $weapon['dmg_small'] . '/' . $weapon['dmg_large']; ?></td>
                        <td><?php echo ucfirst($weapon['material']); ?></td>
                        <td class="action-links">
                            <a href="/admin/weapons/view.php?id=<?php echo $weapon['item_id']; ?>">View</a>
                            <a href="/admin/weapons/edit.php?id=<?php echo $weapon['item_id']; ?>">Edit</a>
                            <a href="/admin/weapons/delete.php?id=<?php echo $weapon['item_id']; ?>" data-confirm="Are you sure you want to delete this weapon?">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty-table">No weapons found matching your criteria.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
    
    <?php
    // Generate pagination links
    $paginationUrl = '/admin/weapons/?page=%d';
    
    // Add existing GET parameters to pagination URL
    $queryParams = [];
    if (!empty($search)) $queryParams[] = 'q=' . urlencode($search);
    if (!empty($category)) $queryParams[] = 'category=' . urlencode($category);
    if (!empty($type)) $queryParams[] = 'type=' . urlencode($type);
    if ($minLevel > 0) $queryParams[] = 'min_level=' . $minLevel;
    if ($maxLevel > 0) $queryParams[] = 'max_level=' . $maxLevel;
    if ($orderBy !== 'name') $queryParams[] = 'order_by=' . urlencode($orderBy);
    if ($orderDir !== 'ASC') $queryParams[] = 'order_dir=desc';
    
    if (!empty($queryParams)) {
        $paginationUrl .= '&' . implode('&', $queryParams);
    }
    
    echo pagination($page, $totalPages, $paginationUrl);
    ?>
</div>

<?php
// Include admin footer
require_once __DIR__ . '/../../includes/layouts/admin_footer.php';
?>