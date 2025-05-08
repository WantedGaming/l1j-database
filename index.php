<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Home';
$is_admin = false;

// Get database statistics for display
$stats = getDatabaseStats();
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navigation.php'; ?>

<!-- Hero Section with Search -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">L1J Remastered Database</h1>
        <p class="hero-subtitle">Explore the comprehensive database of L1J Remastered game with detailed information on items, monsters, maps and more</p>
        
        <div class="hero-search">
            <form class="search-form" action="search.php" method="get">
                <div class="input-group">
                    <input type="search" class="form-control form-control-lg" name="q" placeholder="Search for weapons, armor, items, monsters, etc...">
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="fas fa-search me-2"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center section-title">Database Categories</h2>
        
        <div class="row">
            <?php 
            $categories = getCategories();
            $count = 0;
            
            foreach ($categories as $category):
                // Start a new row after every 3 categories
                if ($count > 0 && $count % 3 === 0):
                    echo '</div><div class="row">';
                endif;
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card category-card h-100">
                        <img src="<?= $category['icon'] ?>" class="card-img-top" alt="<?= $category['name'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $category['name'] ?></h5>
                            <p class="card-text"><?= $category['description'] ?></p>
                            <div class="mt-3">
                                <span class="badge bg-secondary me-1">
                                    <i class="fas fa-database me-1"></i> <?= number_format($stats[$category['id']] ?? 0) ?>
                                </span>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-table me-1"></i> <?= count($category['tables']) ?> tables
                                </span>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="category.php?id=<?= $category['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Browse <?= $category['name'] ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                $count++;
            endforeach; 
            ?>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<!-- Statistics Section -->
<section class="py-5 bg-secondary-custom">
    <div class="container">
        <h2 class="text-center section-title">Database Statistics</h2>
        
        <div class="row">
            <?php 
            // Using the same image paths as in category definitions
            $statImages = [
                'weapons' => 'assets/img/placeholders/weapons.png',
                'armor' => 'assets/img/placeholders/armor.png',
                'items' => 'assets/img/placeholders/items.png',
                'monsters' => 'assets/img/placeholders/monsters.png',
                'maps' => 'assets/img/placeholders/maps.png',
                'dolls' => 'assets/img/placeholders/dolls.png',
                'npcs' => 'assets/img/placeholders/npc.png',
                'skills' => 'assets/img/placeholders/skill.png',
                'polymorph' => 'assets/img/placeholders/poly.png'
            ];
            
            foreach ($stats as $category => $count): 
                $imagePath = $statImages[$category] ?? 'assets/img/placeholders/items.png';
            ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-value"><?= number_format($count) ?></div>
                        <div class="stat-label"><?= ucfirst($category) ?></div>
                        <img src="<?= $imagePath ?>" class="position-absolute end-0 bottom-0 m-3 opacity-25" style="width: 40px; height: 40px;" alt="<?= ucfirst($category) ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center section-title">Database Features</h2>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-accent">
                            <i class="fas fa-search fa-3x"></i>
                        </div>
                        <h5 class="card-title">Advanced Search</h5>
                        <p class="card-text">Find items, monsters, and more with our powerful search functionality. Filter by categories, attributes, and more.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-accent">
                            <i class="fas fa-sync-alt fa-3x"></i>
                        </div>
                        <h5 class="card-title">Real-time Updates</h5>
                        <p class="card-text">Our database is regularly updated to match the latest game data, ensuring you always have accurate information.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-accent">
                            <i class="fas fa-mobile-alt fa-3x"></i>
                        </div>
                        <h5 class="card-title">Mobile Friendly</h5>
                        <p class="card-text">Access the database on any device with our responsive design. Perfect for desktop, tablet, or mobile gaming sessions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 bg-primary-custom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 mb-4 mb-md-0">
                <h2 class="mb-3">Ready to explore the database?</h2>
                <p class="lead mb-0">Start searching or browsing categories to find the information you need.</p>
            </div>
            <div class="col-md-4 text-md-end text-center">
                <a href="search.php" class="btn btn-lg btn-primary me-2 mb-2">
                    <i class="fas fa-search me-2"></i> Search
                </a>
                <a href="#" class="btn btn-lg btn-outline-primary mb-2">
                    <i class="fas fa-info-circle me-2"></i> Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
