<?php
/**
 * Monsters List Page
 * Displays a paginated list of all monsters
 */

// Include database connection
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Get current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1

// Set page title
$pageTitle = 'Monsters';
$showHero = false;

// Set items per page
$itemsPerPage = 20;

// Get monsters with pagination
$result = getItemsWithPagination(
    $pdo,
    'npc',
    ['npcid', 'desc_en', 'type', 'level', 'hp', 'mp', 'ac', 'str', 'con', 'dex', 'int', 'wis', 'cha'],
    'type = :type',
    [':type' => 'monster'],
    $page,
    $itemsPerPage
);

$monsters = $result['items'];
$pagination = $result['pagination'];

// Include header
include '../includes/header.php';
?>

<div class="mb-6">
    <h1 class="section-title">Monsters</h1>
    
    <div class="mb-4">
        <p>Browse all monsters in the L1J database. Click on a monster to view detailed information including drops and spawn locations.</p>
    </div>
    
    <div class="card p-4 mb-6">
        <div class="flex flex-wrap items-center">
            <div class="mr-4 mb-2">
                <label for="levelFilter" class="mr-2">Filter by Level:</label>
                <select id="levelFilter" class="form-select">
                    <option value="">All Levels</option>
                    <option value="1-10">Level 1-10</option>
                    <option value="11-20">Level 11-20</option>
                    <option value="21-30">Level 21-30</option>
                    <option value="31-40">Level 31-40</option>
                    <option value="41-50">Level 41-50</option>
                    <option value="51+">Level 51+</option>
                </select>
            </div>
            
            <div class="mr-4 mb-2">
                <label for="searchInput" class="mr-2">Search:</label>
                <input type="text" id="searchInput" class="form-input" placeholder="Search monsters..." onkeyup="filterTable('searchInput', 'monstersTable')">
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <table id="monstersTable" class="data-table">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Level</th>
                    <th>HP</th>
                    <th>MP</th>
                    <th>AC</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monsters as $monster): ?>
                <tr data-level="<?php echo $monster['level']; ?>">
                    <td>
                        <img src="<?php echo getImagePath('monster', $monster['npcid']); ?>" alt="<?php echo htmlspecialchars($monster['desc_en']); ?>" class="item-icon">
                    </td>
                    <td><?php echo htmlspecialchars($monster['desc_en']); ?></td>
                    <td><?php echo $monster['level']; ?></td>
                    <td><?php echo formatNumber($monster['hp']); ?></td>
                    <td><?php echo formatNumber($monster['mp']); ?></td>
                    <td><?php echo $monster['ac']; ?></td>
                    <td>
                        <a href="/monsters/detail.php?id=<?php echo $monster['npcid']; ?>" class="btn btn-small">View Details</a>
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
document.addEventListener('DOMContentLoaded', function() {
    const levelFilter = document.getElementById('levelFilter');
    const table = document.getElementById('monstersTable');
    const rows = table.getElementsByTagName('tr');
    
    levelFilter.addEventListener('change', function() {
        const filterValue = this.value;
        
        // Skip header row
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const level = parseInt(row.getAttribute('data-level'));
            
            if (!filterValue) {
                // Show all
                row.style.display = '';
            } else if (filterValue === '51+') {
                // Level 51+
                row.style.display = (level >= 51) ? '' : 'none';
            } else {
                // Level ranges like "1-10"
                const [min, max] = filterValue.split('-').map(Number);
                row.style.display = (level >= min && level <= max) ? '' : 'none';
            }
        }
    });
});
</script>

<?php
// Include footer
include '../includes/footer.php';
?>
