<?php
/**
 * Admin Account List Page
 * Displays all accounts and their associated characters
 */

// Include required files
require_once '../../../includes/db_connect.php';
require_once '../../includes/admin-config.php';
require_once '../../includes/account-functions.php';

// Set current page for navigation highlighting
$currentAdminPage = 'accounts';
$pageTitle = 'Account Management';

// Get page number from URL, default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Get search term if any
$searchTerm = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Get accounts data with pagination
$accountsData = getAccounts($conn, $page, 12, $searchTerm); // 12 accounts per page
$accounts = $accountsData['accounts'];
$totalPages = $accountsData['pages'];
$totalAccounts = $accountsData['total'];

// Include admin header
include '../../includes/admin-header.php';
?>

<link rel="stylesheet" href="<?php echo $adminBaseUrl; ?>assets/css/account-styles.css">

<div class="container">
    <!-- Enhanced Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Account Management</h1>
                <p class="admin-hero-subtitle">Manage user accounts and character data - <?php echo number_format($totalAccounts); ?> accounts found</p>
            </div>
        </div>
        
        <div class="hero-controls">
            <form action="<?php echo $adminBaseUrl; ?>pages/accounts/account-list.php" method="GET" class="hero-search-form">
                <div class="search-input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search accounts by login or IP..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <div class="hero-filter-controls">
                <select class="form-control" id="account-status-filter">
                    <option value="all">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="dormant">Dormant</option>
                    <option value="banned">Banned</option>
                </select>
            </div>
        </div>
    </div>
    
    <?php if (empty($accounts)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle alert-icon"></i>
            No accounts found<?php echo !empty($searchTerm) ? ' matching "' . htmlspecialchars($searchTerm) . '"' : ''; ?>.
        </div>
    <?php else: ?>
        <!-- Accounts Grid -->
        <div class="account-grid">
            <?php foreach ($accounts as $account): 
                // Get status for this account
                $status = getAccountStatus($account);
                
                // Get characters for this account
                $characters = getAccountCharacters($conn, $account['login']);
            ?>
                <div class="account-card" data-account-status="<?php echo $status['class']; ?>">
                    <div class="account-header">
                        <h3 class="account-title"><?php echo htmlspecialchars($account['login']); ?></h3>
                        <span class="account-status <?php echo $status['class']; ?>"><?php echo $status['label']; ?></span>
                    </div>
                    
                    <div class="account-details">
                        <!-- Modified to use a horizontal layout with both items in the same row -->
                        <div class="account-info-row">
                            <div class="account-info-item">
                                <div class="account-info-label">Last Active</div>
                                <div class="account-info-value"><?php echo formatTimeUSA($account['lastactive']); ?></div>
                            </div>
                            <div class="account-info-item">
                                <div class="account-info-label">Access Level</div>
                                <div class="account-info-value">
                                    <span class="account-access"><?php echo getAccessLevelName($account['access_level']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="characters-section">
                        <div class="section-header">
                            <h4>Characters (<?php echo count($characters); ?>)</h4>
                        </div>
                        
                        <?php if (empty($characters)): ?>
                            <div class="empty-characters">
                                No characters for this account.
                            </div>
                        <?php else: ?>
                            <ul class="character-list">
                                <?php foreach ($characters as $character): ?>
                                    <li class="character-item">
                                        <div class="character-level-badge"><?php echo $character['level']; ?></div>
                                        <img src="<?php echo getClassImagePath($character['Class'], $character['gender']); ?>" 
                                             alt="<?php echo getClassName($character['Class'], $character['gender']); ?>" 
                                             class="character-icon">
                                        <div class="character-info">
                                            <div class="character-name">
                                                <a href="<?php echo $adminBaseUrl; ?>pages/accounts/character-detail.php?id=<?php echo $character['objid']; ?>">
                                                    <?php echo htmlspecialchars($character['char_name']); ?>
                                                </a>
                                                <?php /* Removed clan name display since we're not fetching it */ ?>
                                            </div>
                                            <div class="character-class">
                                                <?php echo getClassName($character['Class'], $character['gender']); ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    
                    <div class="account-ip">
                        IP: <span class="account-ip-value"><?php echo htmlspecialchars($account['ip']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php 
            $urlPattern = $adminBaseUrl . 'pages/accounts/account-list.php?page=%d' . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '');
            echo generateAdminPagination($page, $totalPages, $totalAccounts, $urlPattern);
        ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Account status filter
    const statusFilter = document.getElementById('account-status-filter');
    const accountCards = document.querySelectorAll('.account-card');
    
    statusFilter.addEventListener('change', function() {
        const filterValue = this.value;
        
        accountCards.forEach(card => {
            if (filterValue === 'all' || card.dataset.accountStatus.includes(filterValue)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>

<?php
// Include admin footer
include '../../includes/admin-footer.php';
?>
