<?php
require_once '../config.php';
require_once '../includes/functions.php';

$page_title = 'Admin Search';
$is_admin = true;

// Get search query
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : null;

// Perform search if query is not empty
$search_results = [];
if (!empty($search_query)) {
    $search_results = searchDatabase($search_query, $category);
}

// Get category name if category filter is applied
$category_name = '';
if ($category) {
    foreach (getCategories() as $cat) {
        if ($cat['id'] === $category) {
            $category_name = $cat['name'];
            break;
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<!-- Admin Header -->
<header class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Admin Search</h1>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="fas fa-tachometer-alt"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</header>

<div class="container py-4">
    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-primary-custom">
                <div class="card-body">
                    <form class="search-form" action="search.php" method="get">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <input type="search" class="form-control form-control-lg" name="q" 
                                       placeholder="Search database..." value="<?= htmlspecialchars($search_query) ?>">
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="form-select form-select-lg">
                                    <option value="">All Categories</option>
                                    <?php foreach (getCategories() as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $category === $cat['id'] ? 'selected' : '' ?>>
                                        <?= $cat['name'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-lg w-100" type="submit">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Results -->
    <div class="row">
        <div class="col-md-12">
            <?php if (empty($search_query)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Enter a search term to find items in the database.
                </div>
            <?php elseif (empty($search_results)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> No results found for "<?= htmlspecialchars($search_query) ?>".
                </div>
            <?php else: ?>
                <div class="card bg-primary-custom">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            Search Results (<?= count($search_results) ?>)
                        </h3>
                        <div>
                            <a href="index.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table admin-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">ID</th>
                                        <th>Name</th>
                                        <th style="width: 120px;">Category</th>
                                        <th style="width: 200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($search_results as $item): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            // Determine ID field based on table
                                            $id_field = 'id';
                                            switch ($item['table']) {
                                                case 'weapon':
                                                case 'armor':
                                                case 'etcitem':
                                                    $id_field = 'item_id';
                                                    break;
                                                case 'npc':
                                                    $id_field = 'npcid';
                                                    break;
                                                case 'mapids':
                                                    $id_field = 'mapid';
                                                    break;
                                                case 'skills':
                                                case 'skills_passive':
                                                    $id_field = isset($item['skill_id']) ? 'skill_id' : 'passive_id';
                                                    break;
                                            }
                                            echo $item[$id_field] ?? 'N/A';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            // Determine name field based on table
                                            $name_field = 'name';
                                            switch ($item['table']) {
                                                case 'weapon':
                                                case 'armor':
                                                case 'etcitem':
                                                case 'npc':
                                                    $name_field = 'desc_en';
                                                    break;
                                                case 'mapids':
                                                    $name_field = 'locationname';
                                                    break;
                                            }
                                            echo $item[$name_field] ?? 'N/A';
                                            ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= ucfirst($item['category'] ?? 'unknown') ?>
                                            </span>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="../item.php?id=<?= $item[$id_field] ?? 0 ?>&category=<?= $item['category'] ?? 'unknown' ?>" 
                                               class="btn btn-sm btn-view" target="_blank">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="edit.php?id=<?= $item[$id_field] ?? 0 ?>&category=<?= $item['category'] ?? 'unknown' ?>" 
                                               class="btn btn-sm btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="#" class="btn btn-sm btn-delete" 
                                               data-action="delete" 
                                               data-item-name="<?= htmlspecialchars($item[$name_field] ?? 'this item') ?>"
                                               onclick="confirmDelete('<?= htmlspecialchars($item[$name_field] ?? 'this item') ?>', 'delete.php?id=<?= $item[$id_field] ?? 0 ?>&category=<?= $item['category'] ?? 'unknown' ?>')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
