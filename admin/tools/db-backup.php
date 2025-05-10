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

<div class="container">
    <!-- Admin Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Database Backup & Restore</h1>
                <p class="admin-hero-subtitle">Create and manage database backups</p>
            </div>
            <div class="hero-actions">
                <a href="<?php echo $adminBaseUrl; ?>tools/index.php" class="btn btn-secondary">
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
                                <?php echo ucfirst($prefix); ?> Tables (<?php echo count($groupTables); ?>)
                            </label>
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
        <p>No backups found.</p>
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
            const expanded = content.classList.contains('expanded');
            
            if (expanded) {
                content.classList.remove('expanded');
            } else {
                content.classList.add('expanded');
            }
        });
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