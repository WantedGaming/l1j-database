<?php
// Get relative path prefix based on if we're in admin or not
$path_prefix = isset($is_admin) && $is_admin ? '../' : '';
?>
<nav class="site-navigation navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= $path_prefix ?>index.php">
            <i class="fas fa-database me-2"></i> L1J Database
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($page_title == 'Home') ? 'active' : '' ?>" href="<?= $path_prefix ?>index.php">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDatabase" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-database me-1"></i> Database
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownDatabase">
                        <?php foreach (getCategories() as $category): ?>
                        <li>
                            <a class="dropdown-item" href="<?= $path_prefix ?>category.php?id=<?= $category['id'] ?>">
                                <i class="<?= getCategoryIcon($category['id']) ?> me-2"></i> <?= $category['name'] ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?= $path_prefix . (isset($is_admin) && $is_admin ? '' : 'admin/') ?>">
                        <i class="fas fa-cog me-1"></i> <?= isset($is_admin) && $is_admin ? 'Admin Dashboard' : 'Admin' ?>
                    </a>
                </li>
            </ul>
            
            <form class="d-flex search-form" action="<?= $path_prefix ?>search.php" method="get">
                <div class="input-group">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search..." aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>

<?php
/**
 * Get appropriate icon for category
 * 
 * @param string $categoryId Category identifier
 * @return string FontAwesome icon class
 */
function getCategoryIcon($categoryId) {
    switch ($categoryId) {
        case 'weapons': return 'fa-solid fa-khanda';
        case 'armor': return 'fas fa-shield-alt';
        case 'items': return 'fas fa-flask';
        case 'monsters': return 'fa-solid fa-skull';
        case 'maps': return 'fas fa-map';
        case 'dolls': return 'fas fa-ghost';
        case 'npcs': return 'fas fa-user';
        case 'skills': return 'fas fa-bolt';
        case 'polymorph': return 'fas fa-exchange-alt';
        default: return 'fas fa-database';
    }
}
?>
