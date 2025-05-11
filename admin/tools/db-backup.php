<?php
/**
 * Database Backup & Restore Tool
 * Provides functionality for creating and restoring database backups
 */

// Include required configuration files
require_once '../../includes/db_connect.php';
require_once '../includes/admin-config.php';

// Set current page for navigation highlighting
$currentAdminPage = 'tools';
$pageTitle = 'Database Backup & Restore';

// Process form submissions
$message = '';
$messageType = '';
$backupResult = null;

// Directory for storing backups
$backupDir = __DIR__ . '/../../backups/';

// Create backup directory if it doesn't exist
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Get list of existing backups
$backups = [];
if (is_dir($backupDir)) {
    if ($dh = opendir($backupDir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
                $backups[] = [
                    'name' => $file,
                    'path' => $backupDir . $file,
                    'size' => filesize($backupDir . $file),
                    'date' => filemtime($backupDir . $file)
                ];
            }
        }
        closedir($dh);
    }
}

// Sort backups by date (newest first)
usort($backups, function($a, $b) {
    return $b['date'] - $a['date'];
});

// Backup operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_backup'])) {
    $tables = isset($_POST['tables']) ? $_POST['tables'] : [];
    $backupName = isset($_POST['backup_name']) ? sanitizeInput($_POST['backup_name']) : '';
    
    if (empty($backupName)) {
        $backupName = 'backup_' . date('Y-m-d_H-i-s');
    } else {
        // Ensure filename is safe
        $backupName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $backupName);
    }
    
    // Add .sql extension if not present
    if (substr($backupName, -4) !== '.sql') {
        $backupName .= '.sql';
    }
    
    $backupPath = $backupDir . $backupName;
    
    if (empty($tables)) {
        $message = "Please select at least one table to backup.";
        $messageType = 'warning';
    } else {
        // Create backup
        $backupResult = createBackup($conn, $tables, $backupPath);
        
        if ($backupResult['success']) {
            $message = "Backup created successfully: {$backupName}";
            $messageType = 'success';
            
            // Refresh backup list
            $backups[] = [
                'name' => $backupName,
                'path' => $backupPath,
                'size' => filesize($backupPath),
                'date' => time()
            ];
            
            // Re-sort backups
            usort($backups, function($a, $b) {
                return $b['date'] - $a['date'];
            });
        } else {
            $message = "Error creating backup: " . $backupResult['error'];
            $messageType = 'danger';
        }
    }
}

// Restore operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_backup'])) {
    $backupFile = isset($_POST['backup_file']) ? sanitizeInput($_POST['backup_file']) : '';
    
    if (empty($backupFile) || !file_exists($backupDir . $backupFile)) {
        $message = "Invalid backup file.";
        $messageType = 'danger';
    } else {
        // Restore backup
        $restoreResult = restoreBackup($conn, $backupDir . $backupFile);
        
        if ($restoreResult['success']) {
            $message = "Backup restored successfully.";
            $messageType = 'success';
        } else {
            $message = "Error restoring backup: " . $restoreResult['error'];
            $messageType = 'danger';
        }
    }
}

// Delete backup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_backup'])) {
    $backupFile = isset($_POST['backup_file']) ? sanitizeInput($_POST['backup_file']) : '';
    
    if (empty($backupFile) || !file_exists($backupDir . $backupFile)) {
        $message = "Invalid backup file.";
        $messageType = 'danger';
    } else {
        if (unlink($backupDir . $backupFile)) {
            $message = "Backup deleted successfully.";
            $messageType = 'success';
            
            // Remove from the list
            foreach ($backups as $key => $backup) {
                if ($backup['name'] === $backupFile) {
                    unset($backups[$key]);
                    break;
                }
            }
        } else {
            $message = "Error deleting backup.";
            $messageType = 'danger';
        }
    }
}

// Get list of tables
$tables = [];
$tablesResult = $conn->query("SHOW TABLES");
if ($tablesResult) {
    while ($row = $tablesResult->fetch_row()) {
        $tables[] = $row[0];
    }
}

// Group tables by prefix
$tableGroups = [];
foreach ($tables as $table) {
    $prefix = strtok($table, '_');
    if (!isset($tableGroups[$prefix])) {
        $tableGroups[$prefix] = [];
    }
    $tableGroups[$prefix][] = $table;
}

// Sort groups alphabetically
ksort($tableGroups);

// Include admin header
include '../includes/admin-header.php';

/**
 * Create database backup
 * 
 * @param mysqli $conn Database connection
 * @param array $tables Tables to backup
 * @param string $backupPath Path to save backup file
 * @return array Result with success flag and error message if applicable
 */
function createBackup($conn, $tables, $backupPath) {
    try {
        $output = "-- Database Backup\n";
        $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- --------------------------------------------------------\n\n";
        
        // Add drop table statements first
        foreach ($tables as $table) {
            $output .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
        }
        
        $output .= "\n-- --------------------------------------------------------\n\n";
        
        // Add create table and insert statements
        foreach ($tables as $table) {
            // Get create table statement
            $result = $conn->query("SHOW CREATE TABLE `" . $table . "`");
            if (!$result) {
                return ['success' => false, 'error' => "Error getting table structure: " . $conn->error];
            }
            
            $row = $result->fetch_row();
            $output .= $row[1] . ";\n\n";
            
            // Get table data
            $result = $conn->query("SELECT * FROM `" . $table . "`");
            if (!$result) {
                return ['success' => false, 'error' => "Error getting table data: " . $conn->error];
            }
            
            if ($result->num_rows > 0) {
                $numFields = $result->field_count;
                
                // Start insert statement
                $output .= "INSERT INTO `" . $table . "` VALUES\n";
                $rowCount = 0;
                
                while ($row = $result->fetch_row()) {
                    $output .= "(";
                    
                    for ($i = 0; $i < $numFields; $i++) {
                        if (isset($row[$i])) {
                            $row[$i] = addslashes($row[$i]);
                            $row[$i] = str_replace("\n", "\\n", $row[$i]);
                            $output .= "'" . $row[$i] . "'";
                        } else {
                            $output .= "NULL";
                        }
                        
                        if ($i < ($numFields - 1)) {
                            $output .= ",";
                        }
                    }
                    
                    $rowCount++;
                    if ($rowCount < $result->num_rows) {
                        $output .= "),\n";
                    } else {
                        $output .= ");\n\n";
                    }
                    
                    // Write to file in chunks to avoid memory issues
                    if ($rowCount % 100 === 0) {
                        file_put_contents($backupPath, $output, $rowCount === 100 ? 0 : FILE_APPEND);
                        $output = '';
                    }
                }
                
                // Write remaining output
                if (!empty($output)) {
                    file_put_contents($backupPath, $output, FILE_APPEND);
                }
            }
            
            $output = "\n-- --------------------------------------------------------\n\n";
        }
        
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Restore database backup
 * 
 * @param mysqli $conn Database connection
 * @param string $backupPath Path to backup file
 * @return array Result with success flag and error message if applicable
 */
function restoreBackup($conn, $backupPath) {
    try {
        $sql = file_get_contents($backupPath);
        if (!$sql) {
            return ['success' => false, 'error' => "Unable to read backup file."];
        }
        
        // Split SQL by semicolons
        $queries = explode(';', $sql);
        
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                $result = $conn->query($query);
                if (!$result) {
                    return ['success' => false, 'error' => "Error executing query: " . $conn->error];
                }
            }
        }
        
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
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

.search-form {
    padding: 25px;
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

.form-group {
    margin-bottom: 20px;
    padding: 0 25px;
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

.btn-danger {
    color: #fff;
    background-color: var(--danger);
    border-color: var(--danger);
}

.btn-danger:hover, .btn-danger:focus {
    background-color: #c82333;
    border-color: #bd2130;
    transform: translateY(-1px);
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

.alert-success {
    background-color: rgba(40, 167, 69, 0.15);
    border-left: 4px solid var(--success);
}

.alert-warning {
    background-color: rgba(255, 193, 7, 0.15);
    border-left: 4px solid var(--warning);
}

/* Results Container */
.results-container {
    margin-top: 30px;
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Enhanced Table Layout */
.table-responsive {
    overflow-x: auto;
    margin: 0 25px 25px 25px;
}

.admin-table {
    padding: 0 25px 25px 25px;
}

.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    background-color: var(--primary);
}

.data-table thead th {
    background-color: var(--secondary);
    color: var(--text);
    font-weight: 600;
    text-align: left;
    padding: 16px;
    font-size: 14px;
    position: sticky;
    top: 0;
    z-index: 10;
    transition: background-color 0.3s;
    border-bottom: 2px solid #1a1a1a;
}

.data-table tbody tr {
    transition: all 0.3s ease;
}

.data-table tbody tr:nth-child(odd) {
    background-color: rgba(255, 255, 255, 0.02);
}

.data-table tbody tr:hover {
    background-color: rgba(249, 75, 31, 0.05);
    transform: translateX(5px);
}

.data-table td {
    padding: 14px 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 14px;
    transition: all 0.3s ease;
}

.table-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.inline-form {
    display: inline-block;
}

/* Table Group Styles for DB Backup */
.table-group {
    margin-bottom: 10px;
    border: 1px solid var(--secondary);
    border-radius: 8px;
    overflow: hidden;
}

.table-group-header {
    cursor: pointer;
    padding: 10px 15px;
    background-color: var(--primary);
    border-bottom: 1px solid var(--secondary);
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-group-header:hover {
    background-color: var(--secondary);
}

.table-group-header label {
    display: block;
    width: 100%;
    cursor: pointer;
    font-weight: 600;
    margin-bottom: 0;
    font-size: 1.05em;
    color: var(--text);
}

.table-group-header .toggle-icon {
    margin-left: 10px;
    transition: transform 0.2s ease;
}

.table-group-header .toggle-icon.collapsed {
    transform: rotate(-90deg);
}

.table-group-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
    background-color: var(--primary);
    padding: 0 15px;
}

.table-group-content.expanded {
    max-height: 1000px;
    padding: 10px 15px;
    transition: max-height 0.5s ease-in;
}

.checkbox-item {
    margin-bottom: 5px;
    padding-left: 15px;
    border-left: 2px solid var(--secondary);
    padding: 6px 8px;
    transition: background-color 0.2s;
}

.checkbox-item:nth-child(odd) {
    background-color: var(--secondary);
}

.checkbox-item:hover {
    background-color: #1a1a1a;
}

.checkbox-header {
    margin-bottom: 15px;
    padding: 10px;
    background-color: var(--primary);
    border-radius: 4px;
    font-weight: bold;
    border: 1px solid var(--secondary);
}

.group-table-count {
    margin-left: 5px;
    padding: 2px 6px;
    background-color: var(--accent);
    color: var(--text);
    border-radius: 10px;
    font-size: 0.8em;
    font-weight: normal;
}

.checkbox-group {
    margin-bottom: 15px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .hero-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .hero-actions {
        margin-top: 15px;
        align-self: flex-start;
    }
    
    .col-md-6, .col-md-4, .col-md-2 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .form-buttons-bottom {
        margin-top: 15px;
    }
    
    .table-responsive {
        margin: 0 15px 15px 15px;
    }
}
</style>

<div class="container">
    <!-- Admin Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Database Backup & Restore</h1>
                <p class="admin-hero-subtitle">Create and manage database backups</p>
            </div>
            <div class="hero-actions">
                <a href="<?php echo $adminBaseUrl; ?>tools/tools-index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left btn-icon"></i> Back to Tools
                </a>
            </div>
        </div>
    </div>
    
    <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?>">
        <i class="fas fa-info-circle alert-icon"></i>
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <div class="admin-form-container">
        <div class="admin-form-title">Create New Backup</div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="backup_name" class="form-label">Backup Name (optional)</label>
                <input type="text" id="backup_name" name="backup_name" class="form-control" placeholder="e.g., my_backup_2025_05_10">
                <small>If not provided, a name will be generated automatically.</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Select Tables to Backup</label>
                
                <div class="checkbox-group">
                    <div class="checkbox-header">
                        <label>
                            <input type="checkbox" id="select-all-tables"> Select All Tables
                        </label>
                    </div>
                    
                    <?php foreach ($tableGroups as $prefix => $groupTables): ?>
                    <div class="table-group">
                        <div class="table-group-header">
                            <label>
                                <input type="checkbox" class="group-selector" data-group="<?php echo $prefix; ?>"> 
                                <?php echo ucfirst($prefix); ?> Tables 
                                <span class="group-table-count"><?php echo count($groupTables); ?></span>
                            </label>
                            <span class="toggle-icon">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </div>
                        
                        <div class="table-group-content">
                            <?php foreach ($groupTables as $table): ?>
                            <div class="checkbox-item">
                                <label>
                                    <input type="checkbox" name="tables[]" value="<?php echo $table; ?>" class="table-checkbox" data-group="<?php echo $prefix; ?>"> 
                                    <?php echo $table; ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="form-buttons">
                <button type="submit" name="create_backup" class="btn btn-primary">
                    <i class="fas fa-download"></i> Create Backup
                </button>
            </div>
        </form>
    </div>
    
    <div class="admin-form-container">
        <div class="admin-form-title">Existing Backups</div>
        
        <?php if (empty($backups)): ?>
        <p style="padding: 20px 25px;">No backups found.</p>
        <?php else: ?>
        <div class="admin-table">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Backup Name</th>
                        <th>Date</th>
                        <th>Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($backups as $backup): ?>
                    <tr>
                        <td><?php echo $backup['name']; ?></td>
                        <td><?php echo date('Y-m-d H:i:s', $backup['date']); ?></td>
                        <td><?php echo formatFileSize($backup['size']); ?></td>
                        <td class="table-actions">
                            <form method="POST" action="" class="inline-form">
                                <input type="hidden" name="backup_file" value="<?php echo $backup['name']; ?>">
                                <button type="submit" name="restore_backup" class="btn btn-sm btn-secondary" onclick="return confirm('Are you sure you want to restore this backup? This will overwrite your current database.');">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                                <a href="<?php echo $adminBaseUrl; ?>tools/download-backup.php?file=<?php echo urlencode($backup['name']); ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <button type="submit" name="delete_backup" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this backup?');">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all tables checkbox
    const selectAllCheckbox = document.getElementById('select-all-tables');
    const tableCheckboxes = document.querySelectorAll('.table-checkbox');
    const groupSelectors = document.querySelectorAll('.group-selector');
    
    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        
        // Check/uncheck all table checkboxes
        tableCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        
        // Check/uncheck all group selectors
        groupSelectors.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        
        // Expand all groups if checked, collapse if unchecked
        const tableGroupContents = document.querySelectorAll('.table-group-content');
        tableGroupContents.forEach(content => {
            if (isChecked) {
                content.classList.add('expanded');
                content.previousElementSibling.querySelector('.toggle-icon i').classList.remove('collapsed');
            } else {
                content.classList.remove('expanded');
                content.previousElementSibling.querySelector('.toggle-icon i').classList.add('collapsed');
            }
        });
    });
    
    // Group selector checkboxes
    groupSelectors.forEach(groupSelector => {
        groupSelector.addEventListener('change', function() {
            const isChecked = this.checked;
            const group = this.dataset.group;
            
            // Check/uncheck all checkboxes in this group
            document.querySelectorAll(`.table-checkbox[data-group="${group}"]`).forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            
            // Expand group content when checked
            const content = this.closest('.table-group-header').nextElementSibling;
            if (isChecked && !content.classList.contains('expanded')) {
                content.classList.add('expanded');
                this.closest('.table-group-header').querySelector('.toggle-icon i').classList.remove('collapsed');
            }
            
            // Update select all checkbox
            updateSelectAllCheckbox();
        });
    });
    
    // Individual table checkboxes
    tableCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const group = this.dataset.group;
            const groupCheckboxes = document.querySelectorAll(`.table-checkbox[data-group="${group}"]`);
            const groupSelector = document.querySelector(`.group-selector[data-group="${group}"]`);
            
            // Check if all checkboxes in this group are checked
            const allChecked = [...groupCheckboxes].every(cb => cb.checked);
            const anyChecked = [...groupCheckboxes].some(cb => cb.checked);
            
            // Update group selector
            if (groupSelector) {
                groupSelector.checked = allChecked;
                groupSelector.indeterminate = anyChecked && !allChecked;
            }
            
            // Update select all checkbox
            updateSelectAllCheckbox();
        });
    });
    
    function updateSelectAllCheckbox() {
        const allChecked = [...tableCheckboxes].every(cb => cb.checked);
        const anyChecked = [...tableCheckboxes].some(cb => cb.checked);
        
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = anyChecked && !allChecked;
    }
    
    // Expand/collapse table groups
    const tableGroupHeaders = document.querySelectorAll('.table-group-header');
    
    tableGroupHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            // Don't toggle if clicking the checkbox
            if (e.target.type === 'checkbox') return;
            
            const content = this.nextElementSibling;
            const toggleIcon = this.querySelector('.toggle-icon i');
            const expanded = content.classList.contains('expanded');
            
            if (expanded) {
                content.classList.remove('expanded');
                toggleIcon.classList.add('collapsed');
            } else {
                content.classList.add('expanded');
                toggleIcon.classList.remove('collapsed');
            }
        });
        
        // Initialize all groups as collapsed
        const content = header.nextElementSibling;
        const toggleIcon = header.querySelector('.toggle-icon i');
        content.classList.remove('expanded');
        toggleIcon.classList.add('collapsed');
    });
});
</script>

<?php
/**
 * Format file size for display
 * 
 * @param int $bytes File size in bytes
 * @return string Formatted file size
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    
    while ($bytes > 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    
    return round($bytes, 2) . ' ' . $units[$i];
}
?>

<?php
// Include admin footer
include '../includes/admin-footer.php';
?>