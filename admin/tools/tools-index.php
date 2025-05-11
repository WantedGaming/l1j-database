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
$excludeFiles = ['index.php', '.', '..', 'download-backup.php'];

// Define essential tools that should always appear
$essentialTools = [
    [
        'name' => 'db-backup',
        'title' => 'Database Backup & Restore',
        'file' => 'db-backup.php',
        'icon' => 'fa-save',
        'custom_icon' => 'assets/img/tool-icons/backup.png',
        'description' => 'Create and restore database backups with selective table options'
    ],
    [
        'name' => 'sql-explorer',
        'title' => 'SQL Query Explorer',
        'file' => 'sql-explorer.php',
        'icon' => 'fa-code',
        'custom_icon' => 'assets/img/tool-icons/sql.png',
        'description' => 'Execute and analyze custom SQL queries with history and templates'
    ],
    [
        'name' => 'column-relationship',
        'title' => 'Column Relationship Finder',
        'file' => 'column-relationship.php',
        'icon' => 'fa-sitemap',
        'custom_icon' => 'assets/img/tool-icons/relationship.png',
        'description' => 'Find similarly named columns across tables (like item_id and itemId)'
    ],
    [
        'name' => 'db-tools',
        'title' => 'Database Analyzer',
        'file' => 'db-tools.php',
        'icon' => 'fa-database',
        'custom_icon' => 'assets/img/tool-icons/analyzer.png',
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
            $customIcon = '';
            
            // Specific icons for known tools
            if (strpos($toolName, 'db') !== false) {
                $icon = 'fa-database';
                $customIcon = 'assets/img/tool-icons/database.png';
            } elseif (strpos($toolName, 'map') !== false) {
                $icon = 'fa-map';
                $customIcon = 'assets/img/tool-icons/map.png';
            } elseif (strpos($toolName, 'item') !== false || strpos($toolName, 'weapon') !== false) {
                $icon = 'fa-box';
                $customIcon = 'assets/img/tool-icons/item.png';
            } elseif (strpos($toolName, 'account') !== false || strpos($toolName, 'user') !== false) {
                $icon = 'fa-user';
                $customIcon = 'assets/img/tool-icons/user.png';
            } elseif (strpos($toolName, 'backup') !== false) {
                $icon = 'fa-save';
                $customIcon = 'assets/img/tool-icons/backup.png';
            } elseif (strpos($toolName, 'log') !== false) {
                $icon = 'fa-file-alt';
                $customIcon = 'assets/img/tool-icons/log.png';
            }
            
            $tools[$toolName] = [
                'name' => $toolName,
                'title' => $toolTitle,
                'file' => $entry,
                'icon' => $icon,
                'custom_icon' => $customIcon,
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

<style>
/* Base Variables - Updated to match the site's color scheme */
:root {
    --text: #ffffff;
    --background: #030303;
    --primary: #080808;
    --secondary: #0a0a0a;
    --accent: #f94b1f;
    --accent-hover: #ff6b40;
    --text-muted: #a0a0a0;
    --success: #28a745;
    --info: #17a2b8;
    --warning: #ffc107;
    --danger: #dc3545;
    --border-radius: 8px;
}

/* Form Layout */
.form-row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}

.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding-right: 15px;
    padding-left: 15px;
}

.col-md-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
    padding-right: 15px;
    padding-left: 15px;
}

.col-md-2 {
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
    padding-right: 15px;
    padding-left: 15px;
}

.col-md-12 {
    flex: 0 0 100%;
    max-width: 100%;
    padding-right: 15px;
    padding-left: 15px;
}

.form-buttons-bottom {
    display: flex;
    align-items: flex-end;
}

.form-text {
    color: #999;
    font-size: 14px;
    margin-top: 5px;
}

/* Admin Hero Section */
.admin-hero {
    background-color: var(--primary);
    border-radius: var(--border-radius);
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    transition: all 0.3s ease;
}

.admin-hero:hover {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    transform: translateY(-2px);
}

.hero-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 30px;
    flex-wrap: wrap;
}

.admin-hero-title {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
    position: relative;
    color: var(--text);
}

.admin-hero-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -8px;
    width: 50px;
    height: 3px;
    background-color: var(--accent);
    border-radius: 3px;
}

.admin-hero-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    margin: 15px 0 0 0;
    max-width: 600px;
}

.hero-actions {
    margin-top: 10px;
}

/* Form Container */
.admin-form-container {
    background-color: var(--primary);
    border-radius: var(--border-radius);
    margin-bottom: 25px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.admin-form-title {
    padding: 18px 25px;
    font-size: 18px;
    font-weight: 600;
    background-color: var(--secondary);
    border-bottom: 1px solid #1a1a1a;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 14px;
}

.form-control {
    display: block;
    width: 100%;
    padding: 12px 15px;
    font-size: 15px;
    line-height: 1.5;
    color: var(--text);
    background-color: var(--secondary);
    border: 1px solid #333;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--accent);
    outline: 0;
    box-shadow: 0 0 0 3px rgba(249, 75, 31, 0.2);
}

.form-buttons {
    padding: 10px 0;
    text-align: right;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.5;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.btn-icon {
    margin-right: 8px;
}

.btn-primary {
    color: #fff;
    background-color: var(--accent);
    border-color: var(--accent);
}

.btn-primary:hover, .btn-primary:focus {
    background-color: var(--accent-hover);
    border-color: var(--accent-hover);
    transform: translateY(-1px);
}

.btn-secondary {
    color: var(--text);
    background-color: var(--secondary);
    border-color: #333;
}

.btn-secondary:hover, .btn-secondary:focus {
    background-color: #1a1a1a;
    border-color: #444;
    transform: translateY(-1px);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 4px;
}

/* Alert Styles */
.alert {
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.alert-icon {
    margin-right: 15px;
    font-size: 18px;
}

.alert-info {
    background-color: rgba(23, 162, 184, 0.15);
    border-left: 4px solid var(--info);
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.15);
    border-left: 4px solid var(--danger);
}

/* Tools Index Specific Styles */
.admin-tools-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.tool-card {
    background-color: var(--primary);
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    text-decoration: none;
}

.tool-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.tool-card-header {
    padding: 15px;
    background-color: var(--secondary);
    border-bottom: 1px solid var(--accent);
    display: flex;
    align-items: center;
    gap: 15px;
}

.tool-card-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(249, 75, 31, 0.1);
    border-radius: 8px;
    flex-shrink: 0;
}

.tool-card-icon img {
    max-width: 32px;
    max-height: 32px;
}

.tool-card-icon i {
    color: var(--accent);
    font-size: 24px;
}

.tool-card-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text);
}

.tool-card-body {
    padding: 15px;
    flex-grow: 1;
}

.tool-card-desc {
    margin: 0;
    color: #bbb;
    line-height: 1.5;
}

.section-content {
    padding: 0 25px 20px;
}

.section-subtitle {
    font-size: 16px;
    font-weight: 600;
    margin: 20px 0 15px;
    color: var(--text);
}

.feature-list {
    padding-left: 20px;
    margin-bottom: 20px;
    color: var(--text-muted);
}

.feature-list li {
    margin-bottom: 10px;
    line-height: 1.5;
}

.feature-list li strong {
    color: var(--text);
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .admin-tools-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hero-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .hero-actions {
        margin-top: 15px;
        align-self: flex-start;
    }
}

@media (max-width: 576px) {
    .admin-tools-grid {
        grid-template-columns: 1fr;
    }
}
</style>

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
    <div class="admin-tools-grid">
        <?php if (empty($tools)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle alert-icon"></i>
                No tools available. Create PHP files in the tools directory to add tools.
            </div>
        <?php else: ?>
            <?php foreach ($tools as $tool): ?>
                <a href="<?php echo $adminBaseUrl; ?>tools/<?php echo $tool['file']; ?>" class="tool-card">
                    <div class="tool-card-header">
                        <div class="tool-card-icon">
                            <?php if (!empty($tool['custom_icon']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $tool['custom_icon'])): ?>
                                <img src="<?php echo '/' . $tool['custom_icon']; ?>" alt="<?php echo $tool['title']; ?> Icon">
                            <?php else: ?>
                                <i class="fas <?php echo $tool['icon']; ?>"></i>
                            <?php endif; ?>
                        </div>
                        <h2 class="tool-card-title"><?php echo $tool['title']; ?></h2>
                    </div>
                    <div class="tool-card-body">
                        <p class="tool-card-desc"><?php echo $tool['description']; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Additional Tools Information -->
    <div class="admin-form-container">
        <h3 class="admin-form-title">Working with Database Tools</h3>
        <div class="section-content">
            <p>The tools provided here will help you manage and maintain your Lineage II database effectively.</p>
            
            <h4 class="section-subtitle">Available Tools</h4>
            <ul class="feature-list">
                <li><strong>Database Backup & Restore</strong> - Create selective backups of your database and restore when needed</li>
                <li><strong>SQL Query Explorer</strong> - Run custom SQL queries with saved history and useful query templates</li>
                <li><strong>Column Relationship Finder</strong> - Identify similarly named columns (e.g., item_id and itemId) across tables</li>
                <li><strong>Database Analyzer</strong> - Analyze table structures and column categorization</li>
            </ul>
            
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