<?php
require_once 'config.php';
require_once 'includes/functions.php';

$page_title = 'Search Results';
$is_admin = false;

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

<?php include 'includes/header.php'; ?>
<?php include 'includes/navigation.php'; ?>

<!-- Hero Section with Search -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Search Results</h1>
        
        <div class="hero-search">
            <form class="search-form" action="search.php" method="get">
                <div class="input-group">
                    <input type="search" class="form-control form-control-lg" name="q" placeholder="Search for weapons, armor, items, monsters, etc..." value="<?= htmlspecialchars($search_query) ?>">
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
                
                <?php if (!empty($category)): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
                <p class="mt-2 text-light">
                    Filtering by: <?= htmlspecialchars($category_name) ?> 
                    <a href="search.php?q=<?= urlencode($search_query) ?>" class="text-accent">(Remove filter)</a>
                </p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php if (empty($search_query)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Please enter a search term.
            </div>
        <?php elseif (empty($search_results)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> No results found for "<?= htmlspecialchars($search_query) ?>".
                <?php if (!empty($category)): ?>
                Try searching without the category filter.
                <?php endif; ?>
            </div>
        <?php else: ?>
            <h2 class="mb-4">
                Found <?= count($search_results) ?> results for "<?= htmlspecialchars($search_query) ?>"
                <?php if (!empty($category)): ?>
                in <?= htmlspecialchars($category_name) ?>
                <?php endif; ?>
            </h2>
            
            <!-- Filter options -->
            <?php if (empty($category)): ?>
            <div class="mb-4">
                <div class="card bg-primary-custom">
                    <div class="card-header">
                        <h5 class="mb-0">Filter by Category</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <?php foreach (getCategories() as $cat): ?>
                            <a href="search.php?q=<?= urlencode($search_query) ?>&category=<?= $cat['id'] ?>" 
                               class="btn btn-sm <?= $category === $cat['id'] ? 'btn-accent' : 'btn-outline-secondary' ?> me-2 mb-2">
                                <?= $cat['name'] ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Results table -->
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Actions</th>
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
                            <td>
                                <a href="item.php?id=<?= $item[$id_field] ?? 0 ?>&category=<?= $item['category'] ?? 'unknown' ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
