<?php
/**
 * Admin Tools Dashboard
 * Main entry point for database administration tools
 */

// Include required configuration files
require_once '../../includes/db_connect.php';
require_once '../includes/admin-config.php';

// Set current page for navigation highlighting
$currentAdminPage = 'tools';
$pageTitle = 'Database Administration Tools';

// Get available tools from the directory
$toolsDirectory = __DIR__;
$tools = [];

// Exclude index.php and non-PHP files
$excludeFiles = ['index.php', '.', '..'];

// Define essential tools that should always appear
$essentialTools = [
    [
        'name' => 'db-backup',
        'title' => 'Database Backup & Restore',
        'file' => 'db-backup.php',
        'icon' => 'fa-save',
        'description' => 'Create and restore database backups with selective table options'
    ],
    [
        'name' => 'sql-explorer',
        'title' => 'SQL Query Explorer',
        'file' => 'sql-explorer.php',
        'icon' => 'fa-code',
        'description' => 'Execute and analyze custom SQL queries with history and templates'
    ],
    [
        'name' => 'column-relationships',
        'title' => 'Column Relationship Finder',
        'file' => 'column-relationship.php',
        'icon' => 'fa-sitemap',
        'description' => 'Find similarly named columns across tables (like item_id and itemId)'
    ],
    [
        'name' => 'db-tools',
        'title' => 'Database Analyzer',
        'file' => 'db-tools.php',
        'icon' => 'fa-database',
        'description' => 'Analyze database structure, tables, and column categories'
    ]
];

// Add essential tools to the list
foreach ($essentialTools as $tool) {
    $tools[$tool['name']] = $tool;
}

// Scan directory for additional tools
if ($handle = opendir($toolsDirectory)) {
    while (false !== ($entry = readdir($handle))) {
        if (!in_array($entry, $excludeFiles) && pathinfo($entry, PATHINFO_EXTENSION) == 'php') {
            $toolName = pathinfo($entry, PATHINFO_FILENAME);
            
            // Skip essential tools already added
            if (isset($tools[$toolName])) {
                continue;
            }
            
            $toolTitle = ucwords(str_replace('-', ' ', $toolName));
            
            // Set default icon based on tool name
            $icon = 'fa-tools';
            
            // Specific icons for known tools
            if (strpos($toolName, 'db') !== false) {
                $icon = 'fa-database';
            } elseif (strpos($toolName, 'map') !== false) {
                $icon = 'fa-map';
            } elseif (strpos($toolName, 'item') !== false || strpos($toolName, 'weapon') !== false) {
                $icon = 'fa-box';
            } elseif (strpos($toolName, 'account') !== false || strpos($toolName, 'user') !== false) {
                $icon = 'fa-user';
            } elseif (strpos($toolName, 'backup') !== false) {
                $icon = 'fa-save';
            } elseif (strpos($toolName, 'log') !== false) {
                $icon = 'fa-file-alt';
            }
            
            $tools[$toolName] = [
                'name' => $toolName,
                'title' => $toolTitle,
                'file' => $entry,
                'icon' => $icon,
                'description' => 'Additional database administration tool'
            ];
        }
    }
    closedir($handle);
}

// Sort tools alphabetically by title (except for essential tools that stay at the top)
uasort($tools, function($a, $b) use ($essentialTools) {
    $aIsEssential = in_array($a['name'], array_column($essentialTools, 'name'));
    $bIsEssential = in_array($b['name'], array_column($essentialTools, 'name'));
    
    if ($aIsEssential && !$bIsEssential) {
        return -1;
    } elseif (!$aIsEssential && $bIsEssential) {
        return 1;
    } else {
        return strcmp($a['title'], $b['title']);
    }
});

// Include admin header
include '../includes/admin-header.php';
?>

<div class="container">
    <!-- Admin Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Database Administration Tools</h1>
                <p class="admin-hero-subtitle">Manage and analyze your database structure and content</p>
            </div>
            <div class="hero-actions">
                <a href="<?php echo $adminBaseUrl; ?>index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left btn-icon"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
    
    <!-- Tools Grid -->
    <div class="admin-card-grid">
        <?php if (empty($tools)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle alert-icon"></i>
                No tools available. Create PHP files in the tools directory to add tools.
            </div>
        <?php else: ?>
            <?php foreach ($tools as $tool): ?>
                <a href="<?php echo $adminBaseUrl; ?>tools/<?php echo $tool['file']; ?>" class="admin-card">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">
                            <i class="fas <?php echo $tool['icon']; ?>"></i> 
                            <?php echo $tool['title']; ?>
                        </h2>
                    </div>
                    <div class="admin-card-body">
                        <p class="admin-card-desc"><?php echo $tool['description']; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Additional Tools Information -->
    <div class="admin-form-container">
        <h3 class="admin-form-title">Working with Database Tools</h3>
        <p>The tools provided here will help you manage and maintain your Lineage II database effectively.</p>
        
        <div class="section-content">
            <h4 class="section-subtitle">Available Tools</h4>
            <ul class="feature-list">
                <li><strong>Database Backup & Restore</strong> - Create selective backups of your database and restore when needed</li>
                <li><strong>SQL Query Explorer</strong> - Run custom SQL queries with saved history and useful query templates</li>
                <li><strong>Column Relationship Finder</strong> - Identify similarly named columns (e.g., item_id and itemId) across tables</li>
                <li><strong>Database Analyzer</strong> - Analyze table structures and column categorization</li>
            </ul>
        </div>
        
        <div class="section-content">
            <h4 class="section-subtitle">Usage Guidelines</h4>
            <ul class="feature-list">
                <li>Always create a backup before making significant changes to the database</li>
                <li>Run analysis tools to understand the database structure before modifications</li>
                <li>Use the Column Relationship Finder to identify potential foreign key relationships</li>
                <li>Review changes carefully before committing them to production</li>
            </ul>
        </div>
    </div>
</div>

<?php
// Include admin footer
include '../includes/admin-footer.php';
?>