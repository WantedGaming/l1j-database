<?php
/**
 * Search Results Page
 * Displays search results from multiple tables based on query
 */

// Include database connection
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Get search query from URL
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

// Set page title
$pageTitle = 'Search Results: ' . htmlspecialchars($searchQuery);
$showHero = false;

// Include header
include 'includes/header.php';
?>

<div class="mb-6">
    <h1 class="section-title">Search Results: "<?php echo htmlspecialchars($searchQuery); ?>"</h1>
    
    <div class="search-container mb-6">
        <form action="/search.php" method="GET" class="search-form">
            <input type="text" name="q" class="search-input" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search items, monsters, etc..." required>
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>
    
    <?php
    // If search query is empty, show an error message
    if (empty($searchQuery)):
    ?>
    <div class="card p-6 text-center">
        <p>Please enter a search term to find items, monsters, maps, and more.</p>
    </div>
    
    <?php
    // If search query is too short, show an error message
    elseif (strlen($searchQuery) < 2):
    ?>
    <div class="card p-6 text-center">
        <p>Please enter at least 2 characters to search.</p>
    </div>
    
    <?php
    // If search query is valid, perform search
    else:
        // Define tables to search in
        $searchTables = ['weapon', 'armor', 'etcitem', 'npc'];
        
        // Search across tables
        $searchResults = searchDatabase($pdo, $searchQuery, $searchTables);
        
        // Group results by category
        $groupedResults = [];
        foreach ($searchResults as $result) {
            $category = $result['source'];
            if (!isset($groupedResults[$category])) {
                $groupedResults[$category] = [];
            }
            $groupedResults[$category][] = $result;
        }
        
        // Define category labels
        $categoryLabels = [
            'weapon' => 'Weapons',
            'armor' => 'Armor',
            'etcitem' => 'Items',
            'npc' => 'NPCs/Monsters'
        ];
        
        // If no results were found, show a message
        if (empty($searchResults)):
    ?>
    <div class="card p-6 text-center">
        <p>No results found for "<?php echo htmlspecialchars($searchQuery); ?>". Please try a different search term.</p>
    </div>
    
    <?php
        // If results were found, display them
        else:
            // Display the total number of results
            $totalResults = count($searchResults);
    ?>
    <p class="mb-4">Found <?php echo $totalResults; ?> result<?php echo $totalResults !== 1 ? 's' : ''; ?> for "<?php echo htmlspecialchars($searchQuery); ?>"</p>
    
    <?php
            // Loop through each category and display its results
            foreach ($groupedResults as $category => $results):
                $categoryTitle = isset($categoryLabels[$category]) ? $categoryLabels[$category] : ucfirst($category);
    ?>
    <div class="mb-6">
        <h2 class="mb-4"><?php echo $categoryTitle; ?> (<?php echo count($results); ?>)</h2>
        <div class="list-container">
            <?php foreach ($results as $item): ?>
            <div class="list-row">
                <?php
                // Set item type and ID for image path
                $itemType = $category;
                if ($category === 'npc') {
                    $itemType = isset($item['type']) && $item['type'] === 'npc' ? 'npc' : 'monster';
                    $itemId = $item['npcid'];
                    $detailUrl = '/' . $itemType . 's/detail.php?id=' . $itemId;
                } else {
                    $itemId = $item['item_id'];
                    $detailUrl = '/' . $category . '/detail.php?id=' . $itemId;
                }
                
                // Get image path
                $imagePath = getImagePath($itemType, isset($item['iconId']) ? $item['iconId'] : $itemId);
                ?>
                
                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($item['desc_en']); ?>" class="item-icon">
                
                <div class="item-details">
                    <a href="<?php echo $detailUrl; ?>" class="item-name"><?php echo htmlspecialchars($item['desc_en']); ?></a>
                    <div class="item-info">
                        <?php 
                        // Display different info based on item type
                        if ($category === 'weapon'): 
                            echo 'DMG: ' . $item['dmg_small'] . '-' . $item['dmg_large'];
                        elseif ($category === 'armor'): 
                            echo 'AC: ' . $item['ac'];
                        elseif ($category === 'npc'): 
                            echo 'Level: ' . $item['level'];
                        endif;
                        ?>
                    </div>
                </div>
                
                <a href="<?php echo $detailUrl; ?>" class="btn btn-small">View Details</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
            endforeach;
        endif;
    endif;
    ?>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>
