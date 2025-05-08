<?php
// Include database connection
require_once __DIR__ . '/../includes/config/database.php';
require_once __DIR__ . '/../includes/functions/common.php';

// Set page variables
$pageTitle = 'Weapons Database';
$pageSubtitle = 'Browse and search all weapons in L1J Remastered';
$showHero = true;
$showSearch = true;
$searchCategory = 'weapons';
$searchAction = '/weapons/';
$pageSection = 'Weapons';

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

// Include header
require_once __DIR__ . '/../includes/layouts/header.php';
?>

<div class="container">
    <div class="list-view">
        <div class="list-header">
            <h2>Weapons List</h2>
            
            <div class="list-filters">
                <form action="/weapons/" method="GET">
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
        
        <table class="list-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Level</th>
                    <th>Damage (S/L)</th>
                    <th>Material</th>
                    <th>Weight</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($weapons) > 0): ?>
                    <?php foreach ($weapons as $weapon): ?>
                    <tr data-url="/weapons/view.php?id=<?php echo $weapon['item_id']; ?>">
                        <td><?php echo $weapon['item_id']; ?></td>
                        <td><?php echo htmlspecialchars($weapon['name']); ?></td>
                        <td><?php echo ucfirst($weapon['weapon_type']); ?></td>
                        <td><?php echo $weapon['level_min']; ?></td>
                        <td><?php echo $weapon['dmg_small'] . '/' . $weapon['dmg_large']; ?></td>
                        <td><?php echo ucfirst($weapon['material']); ?></td>
                        <td><?php echo $weapon['weight']; ?></td>
                        <td class="action-links">
                            <a href="/weapons/view.php?id=<?php echo $weapon['item_id']; ?>">View</a>
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
    </div>
    
    <?php
    // Generate pagination links
    $paginationUrl = '/weapons/?page=%d';
    
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
    
    <div class="additional-info">
        <h3>Weapons Information</h3>
        <p>
            L1J Remastered features a diverse array of weapons, each with unique stats and abilities.
            This database provides detailed information about each weapon, including base statistics,
            required levels, and special properties.
        </p>
        <p>
            You can filter the weapons by category, type, and level requirements using the filters above.
            Click on a weapon's name or "View" button to see complete details including skills and effects.
        </p>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/../includes/layouts/footer.php';
?>