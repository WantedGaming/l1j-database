<?php
/**
 * SQL Query Explorer
 * Advanced tool for executing and analyzing custom SQL queries
 */

// Include required configuration files
require_once '../../includes/db_connect.php';
require_once '../includes/admin-config.php';

// Set current page for navigation highlighting
$currentAdminPage = 'tools';
$pageTitle = 'SQL Query Explorer';

// Initialize variables
$query = isset($_POST['query']) ? $_POST['query'] : '';
$queryHistory = isset($_SESSION['query_history']) ? $_SESSION['query_history'] : [];
$results = null;
$error = '';
$affectedRows = 0;
$executionTime = 0;
$hasResults = false;
$columns = [];
$numRows = 0;
$csvData = [];

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute_query']) && !empty($query)) {
    // Start measuring execution time
    $startTime = microtime(true);
    
    // Execute the query
    try {
        // Prevent multiple queries
        if (preg_match('/;[\s]*[^-]+/', $query)) {
            throw new Exception("Multiple queries are not allowed for security reasons. Please execute one query at a time.");
        }
        
        // Check if it's a SELECT query
        $isSelectQuery = preg_match('/^\s*SELECT\s/i', $query);
        
        // Execute query
        $queryResult = $conn->query($query);
        
        // End measuring execution time
        $executionTime = microtime(true) - $startTime;
        
        if ($queryResult === false) {
            throw new Exception("Query execution failed: " . $conn->error);
        }
        
        if ($isSelectQuery) {
            $hasResults = true;
            $results = [];
            $numRows = $queryResult->num_rows;
            
            // Get column names
            $columns = [];
            $fields = $queryResult->fetch_fields();
            foreach ($fields as $field) {
                $columns[] = $field->name;
            }
            
            // Store results
            while ($row = $queryResult->fetch_assoc()) {
                $results[] = $row;
                
                // For CSV export
                $csvData[] = array_values($row);
            }
            
            $queryResult->free();
        } else {
            $affectedRows = $conn->affected_rows;
        }
        
        // Save query to history (avoiding duplicates)
        if (!in_array($query, $queryHistory)) {
            array_unshift($queryHistory, $query);
            $queryHistory = array_slice($queryHistory, 0, 10); // Keep only last 10 queries
            $_SESSION['query_history'] = $queryHistory;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Handle CSV export
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_csv']) && !empty($csvData)) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="query_results_' . date('Y-m-d_H-i-s') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Add column headers
    fputcsv($output, $columns);
    
    // Add data rows
    foreach ($csvData as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

// Common query templates
$queryTemplates = [
    'select_all' => "SELECT * FROM [table_name] LIMIT 100;",
    'table_structure' => "DESCRIBE [table_name];",
    'database_tables' => "SELECT table_name, table_rows, data_length/1024/1024 as size_mb 
FROM information_schema.tables 
WHERE table_schema = '" . $db_name . "' 
ORDER BY table_rows DESC;",
    'column_search' => "SELECT table_name, column_name, data_type, column_type
FROM information_schema.columns
WHERE table_schema = '" . $db_name . "'
AND column_name LIKE '%[search_term]%'
ORDER BY table_name, column_name;",
    'find_foreign_keys' => "SELECT 
    table_name, column_name, 
    referenced_table_name, referenced_column_name
FROM information_schema.key_column_usage
WHERE table_schema = '" . $db_name . "'
AND referenced_table_name IS NOT NULL
ORDER BY table_name;",
    'count_records' => "SELECT COUNT(*) as total FROM [table_name];",
    'database_size' => "SELECT 
    table_schema as 'Database', 
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as 'Size (MB)' 
FROM information_schema.tables 
GROUP BY table_schema;"
];

// Get list of tables for autocomplete
$tables = [];
$tablesResult = $conn->query("SHOW TABLES");
if ($tablesResult) {
    while ($row = $tablesResult->fetch_row()) {
        $tables[] = $row[0];
    }
}

// Include admin header
include '../includes/admin-header.php';
?>

<div class="container">
    <!-- Admin Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">SQL Query Explorer</h1>
                <p class="admin-hero-subtitle">Execute custom SQL queries and analyze results</p>
            </div>
            <div class="hero-actions">
                <a href="<?php echo $adminBaseUrl; ?>tools/tools-index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left btn-icon"></i> Back to Tools
                </a>
            </div>
        </div>
    </div>
    
    <div class="admin-form-container">
        <div class="admin-form-title">Query Editor</div>
        
        <div class="query-editor-toolbar">
            <button type="button" class="btn btn-sm btn-secondary" id="btn-history">
                <i class="fas fa-history"></i> History
            </button>
            <button type="button" class="btn btn-sm btn-secondary" id="btn-templates">
                <i class="fas fa-file-code"></i> Templates
            </button>
            <button type="button" class="btn btn-sm btn-secondary" id="btn-tables">
                <i class="fas fa-table"></i> Tables
            </button>
            <span class="query-helper-text">Press Ctrl+Enter to execute query</span>
        </div>
        
        <form method="POST" action="" id="query-form">
            <div class="form-group">
                <textarea name="query" id="query-editor" class="form-control code-editor" rows="10" placeholder="Enter your SQL query here..."><?php echo htmlspecialchars($query); ?></textarea>
            </div>
            
            <div class="form-buttons">
                <button type="submit" name="execute_query" class="btn btn-primary">
                    <i class="fas fa-play"></i> Execute Query
                </button>
                <button type="button" id="clear-query" class="btn btn-secondary">
                    <i class="fas fa-trash"></i> Clear
                </button>
            </div>
        </form>
        
        <!-- Query History Dropdown -->
        <div id="history-dropdown" class="editor-dropdown">
            <div class="dropdown-header">
                <h4>Query History</h4>
                <button type="button" class="dropdown-close">&times;</button>
            </div>
            <div class="dropdown-content">
                <?php if (empty($queryHistory)): ?>
                <p>No query history available.</p>
                <?php else: ?>
                <ul class="history-list">
                    <?php foreach ($queryHistory as $historyItem): ?>
                    <li class="history-item">
                        <button type="button" class="history-query" data-query="<?php echo htmlspecialchars($historyItem); ?>">
                            <?php echo htmlspecialchars(substr($historyItem, 0, 100) . (strlen($historyItem) > 100 ? '...' : '')); ?>
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Query Templates Dropdown -->
        <div id="templates-dropdown" class="editor-dropdown">
            <div class="dropdown-header">
                <h4>Query Templates</h4>
                <button type="button" class="dropdown-close">&times;</button>
            </div>
            <div class="dropdown-content">
                <ul class="template-list">
                    <?php foreach ($queryTemplates as $key => $template): ?>
                    <li class="template-item">
                        <button type="button" class="template-query" data-query="<?php echo htmlspecialchars($template); ?>">
                            <?php echo ucwords(str_replace('_', ' ', $key)); ?>
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        
        <!-- Tables List Dropdown -->
        <div id="tables-dropdown" class="editor-dropdown">
            <div class="dropdown-header">
                <h4>Database Tables</h4>
                <button type="button" class="dropdown-close">&times;</button>
            </div>
            <div class="dropdown-content">
                <input type="text" id="table-search" class="form-control" placeholder="Search tables...">
                <ul class="table-list">
                    <?php foreach ($tables as $table): ?>
                    <li class="table-item">
                        <button type="button" class="table-query" data-table="<?php echo $table; ?>">
                            <?php echo $table; ?>
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle alert-icon"></i>
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($hasResults || $affectedRows > 0): ?>
    <div class="admin-form-container">
        <div class="admin-form-title">
            Query Results
            <div class="query-stats">
                <?php if ($hasResults): ?>
                <span class="stat-item"><i class="fas fa-list"></i> <?php echo $numRows; ?> rows</span>
                <?php else: ?>
                <span class="stat-item"><i class="fas fa-edit"></i> <?php echo $affectedRows; ?> affected rows</span>
                <?php endif; ?>
                <span class="stat-item"><i class="fas fa-clock"></i> <?php echo number_format($executionTime * 1000, 2); ?> ms</span>
            </div>
        </div>
        
        <?php if ($hasResults): ?>
        <div class="results-toolbar">
            <form method="POST" action="" id="export-form" class="inline-form">
                <input type="hidden" name="query" value="<?php echo htmlspecialchars($query); ?>">
                <button type="submit" name="export_csv" class="btn btn-sm btn-secondary">
                    <i class="fas fa-download"></i> Export CSV
                </button>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="data-table results-table">
                <thead>
                    <tr>
                        <?php foreach ($columns as $column): ?>
                        <th><?php echo htmlspecialchars($column); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                    <tr>
                        <?php foreach ($row as $value): ?>
                        <td><?php echo htmlspecialchars($value); ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.code-editor {
    font-family: monospace;
    white-space: pre;
    resize: vertical;
    min-height: 200px;
}

.query-editor-toolbar {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
}

.query-helper-text {
    margin-left: auto;
    font-size: 12px;
    color: #999;
}

.editor-dropdown {
    position: absolute;
    width: 100%;
    max-width: 500px;
    background-color: var(--primary);
    border: 1px solid var(--secondary);
    border-radius: 4px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    z-index: 100;
    display: none;
}

.editor-dropdown.show {
    display: block;
}

.dropdown-header {
    padding: 10px 15px;
    border-bottom: 1px solid var(--secondary);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dropdown-header h4 {
    margin: 0;
    font-size: 16px;
}

.dropdown-close {
    background: none;
    border: none;
    font-size: 20px;
    color: var(--text);
    cursor: pointer;
}

.dropdown-content {
    padding: 10px 15px;
    max-height: 300px;
    overflow-y: auto;
}

.history-list,
.template-list,
.table-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.history-item,
.template-item,
.table-item {
    margin-bottom: 5px;
}

.history-query,
.template-query,
.table-query {
    width: 100%;
    text-align: left;
    background: none;
    border: none;
    padding: 8px 10px;
    color: var(--text);
    border-radius: 4px;
    cursor: pointer;
    font-family: monospace;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.history-query:hover,
.template-query:hover,
.table-query:hover {
    background-color: var(--secondary);
}

#table-search {
    margin-bottom: 10px;
}

.query-stats {
    display: flex;
    gap: 15px;
    font-size: 14px;
}

.stat-item {
    color: #bbb;
}

.results-toolbar {
    margin-bottom: 15px;
}

.inline-form {
    display: inline-block;
}

.results-table {
    font-size: 14px;
}

.results-table th {
    position: sticky;
    top: 0;
    background-color: var(--secondary);
    z-index: 10;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Query editor
    const queryEditor = document.getElementById('query-editor');
    const queryForm = document.getElementById('query-form');
    const clearButton = document.getElementById('clear-query');
    
    // Dropdowns
    const historyButton = document.getElementById('btn-history');
    const templatesButton = document.getElementById('btn-templates');
    const tablesButton = document.getElementById('btn-tables');
    
    const historyDropdown = document.getElementById('history-dropdown');
    const templatesDropdown = document.getElementById('templates-dropdown');
    const tablesDropdown = document.getElementById('tables-dropdown');
    
    const dropdownCloseButtons = document.querySelectorAll('.dropdown-close');
    
    // Execute query with Ctrl+Enter
    queryEditor.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            queryForm.submit();
        }
    });
    
    // Clear button
    clearButton.addEventListener('click', function() {
        queryEditor.value = '';
        queryEditor.focus();
    });
    
    // Show history dropdown
    historyButton.addEventListener('click', function() {
        closeAllDropdowns();
        historyDropdown.classList.add('show');
        historyDropdown.style.top = (historyButton.offsetTop + historyButton.offsetHeight + 5) + 'px';
        historyDropdown.style.left = historyButton.offsetLeft + 'px';
    });
    
    // Show templates dropdown
    templatesButton.addEventListener('click', function() {
        closeAllDropdowns();
        templatesDropdown.classList.add('show');
        templatesDropdown.style.top = (templatesButton.offsetTop + templatesButton.offsetHeight + 5) + 'px';
        templatesDropdown.style.left = templatesButton.offsetLeft + 'px';
    });
    
    // Show tables dropdown
    tablesButton.addEventListener('click', function() {
        closeAllDropdowns();
        tablesDropdown.classList.add('show');
        tablesDropdown.style.top = (tablesButton.offsetTop + tablesButton.offsetHeight + 5) + 'px';
        tablesDropdown.style.left = tablesButton.offsetLeft + 'px';
    });
    
    // Close dropdowns
    dropdownCloseButtons.forEach(button => {
        button.addEventListener('click', closeAllDropdowns);
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.editor-dropdown') && 
            !e.target.closest('#btn-history') && 
            !e.target.closest('#btn-templates') && 
            !e.target.closest('#btn-tables')) {
            closeAllDropdowns();
        }
    });
    
    function closeAllDropdowns() {
        historyDropdown.classList.remove('show');
        templatesDropdown.classList.remove('show');
        tablesDropdown.classList.remove('show');
    }
    
    // Handle history query click
    const historyQueries = document.querySelectorAll('.history-query');
    historyQueries.forEach(button => {
        button.addEventListener('click', function() {
            queryEditor.value = this.dataset.query;
            closeAllDropdowns();
            queryEditor.focus();
        });
    });
    
    // Handle template query click
    const templateQueries = document.querySelectorAll('.template-query');
    templateQueries.forEach(button => {
        button.addEventListener('click', function() {
            queryEditor.value = this.dataset.query;
            closeAllDropdowns();
            queryEditor.focus();
        });
    });
    
    // Handle table query click
    const tableQueries = document.querySelectorAll('.table-query');
    tableQueries.forEach(button => {
        button.addEventListener('click', function() {
            const tableName = this.dataset.table;
            queryEditor.value = `SELECT * FROM ${tableName} LIMIT 100;`;
            closeAllDropdowns();
            queryEditor.focus();
        });
    });
    
    // Table search
    const tableSearch = document.getElementById('table-search');
    const tableItems = document.querySelectorAll('.table-item');
    
    tableSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        tableItems.forEach(item => {
            const tableName = item.querySelector('.table-query').dataset.table.toLowerCase();
            
            if (tableName.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

<?php
// Include admin footer
include '../includes/admin-footer.php';
?>