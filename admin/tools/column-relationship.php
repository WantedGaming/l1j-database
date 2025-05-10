<?php
/**
 * Column Relationship Finder
 * Identifies semantically similar column names across different tables
 */

// Include required configuration files
require_once '../../includes/db_connect.php';
require_once '../includes/admin-config.php';

// Set current page for navigation highlighting
$currentAdminPage = 'tools';
$pageTitle = 'Column Relationship Finder';

// Initialize variables
$searchTerm = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$minSimilarity = isset($_GET['min_similarity']) ? (int)$_GET['min_similarity'] : 70; // Default 70%
$columnRelationships = [];
$tableColumns = [];
$error = '';
$message = '';
$messageType = '';

// Process the analysis
if (!empty($searchTerm) || isset($_GET['analyze_all'])) {
    try {
        // Get all tables in the database
        $tablesResult = $conn->query("SHOW TABLES");
        if (!$tablesResult) {
            throw new Exception("Error fetching tables: " . $conn->error);
        }
        
        $tables = [];
        while ($tableRow = $tablesResult->fetch_row()) {
            $tables[] = $tableRow[0];
        }
        
        // Get columns for each table and organize by column name
        foreach ($tables as $table) {
            $columnsResult = $conn->query("SHOW COLUMNS FROM `{$table}`");
            if (!$columnsResult) {
                continue; // Skip tables with errors
            }
            
            while ($columnRow = $columnsResult->fetch_assoc()) {
                $columnName = $columnRow['Field'];
                $normalizedName = normalizeColumnName($columnName);
                
                if (!isset($tableColumns[$normalizedName])) {
                    $tableColumns[$normalizedName] = [];
                }
                
                $tableColumns[$normalizedName][] = [
                    'table' => $table,
                    'column' => $columnName,
                    'type' => $columnRow['Type'],
                    'key' => $columnRow['Key'],
                    'normalized' => $normalizedName
                ];
            }
        }
        
        // Analyze potential relationships
        if (!empty($searchTerm)) {
            // Search for specific term
            $normalizedSearch = normalizeColumnName($searchTerm);
            
            foreach ($tableColumns as $normalizedName => $columns) {
                $similarity = calculateSimilarity($normalizedSearch, $normalizedName);
                
                if ($similarity >= $minSimilarity) {
                    $columnRelationships[$normalizedName] = [
                        'columns' => $columns,
                        'similarity' => $similarity,
                        'normalized' => $normalizedName
                    ];
                }
            }
            
            // Sort by similarity (descending)
            uasort($columnRelationships, function($a, $b) {
                return $b['similarity'] - $a['similarity'];
            });
            
            $message = count($columnRelationships) . " potential column relationships found for '{$searchTerm}'";
            $messageType = 'info';
        } else {
            // Analyze all potential relationships
            $analyzed = [];
            
            foreach ($tableColumns as $name1 => $columns1) {
                foreach ($tableColumns as $name2 => $columns2) {
                    // Skip comparing with self or already analyzed pairs
                    $pair = [$name1, $name2];
                    sort($pair);
                    $pairKey = implode('-', $pair);
                    
                    if ($name1 === $name2 || isset($analyzed[$pairKey])) {
                        continue;
                    }
                    
                    $similarity = calculateSimilarity($name1, $name2);
                    
                    if ($similarity >= $minSimilarity) {
                        if (!isset($columnRelationships[$pairKey])) {
                            $columnRelationships[$pairKey] = [
                                'name1' => $name1,
                                'name2' => $name2,
                                'columns1' => $columns1,
                                'columns2' => $columns2,
                                'similarity' => $similarity
                            ];
                        }
                    }
                    
                    $analyzed[$pairKey] = true;
                }
            }
            
            // Sort by similarity (descending)
            uasort($columnRelationships, function($a, $b) {
                return $b['similarity'] - $a['similarity'];
            });
            
            $message = count($columnRelationships) . " potential column relationships found";
            $messageType = 'info';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

/**
 * Normalize column name for comparison
 * Removes common prefixes/suffixes, converts to lowercase, etc.
 * 
 * @param string $columnName Column name to normalize
 * @return string Normalized column name
 */
function normalizeColumnName($columnName) {
    // Convert to lowercase
    $name = strtolower($columnName);
    
    // Replace underscores with empty string
    $name = str_replace('_', '', $name);
    
    // Remove common prefixes
    $prefixes = ['tbl', 'fk', 'pk', 'ix', 'col'];
    foreach ($prefixes as $prefix) {
        if (strpos($name, $prefix) === 0) {
            $name = substr($name, strlen($prefix));
        }
    }
    
    // Remove common suffixes
    $suffixes = ['id', 'key', 'num', 'name', 'code'];
    foreach ($suffixes as $suffix) {
        if (substr($name, -strlen($suffix)) === $suffix) {
            $name = substr($name, 0, -strlen($suffix));
        }
    }
    
    return $name;
}

/**
 * Calculate similarity between two strings
 * 
 * @param string $str1 First string
 * @param string $str2 Second string
 * @return int Similarity percentage (0-100)
 */
function calculateSimilarity($str1, $str2) {
    // Levenshtein distance
    $levenshtein = levenshtein($str1, $str2);
    $maxLength = max(strlen($str1), strlen($str2));
    
    if ($maxLength === 0) {
        return 100; // Both strings are empty
    }
    
    $levenshteinSimilarity = 100 - (($levenshtein / $maxLength) * 100);
    
    // Similar substring
    $minLength = min(strlen($str1), strlen($str2));
    $matchingChars = 0;
    
    for ($i = 0; $i < $minLength; $i++) {
        if ($str1[$i] === $str2[$i]) {
            $matchingChars++;
        }
    }
    
    $substringSimilarity = ($matchingChars / $maxLength) * 100;
    
    // Calculate soundex similarity
    $soundex1 = soundex($str1);
    $soundex2 = soundex($str2);
    $soundexSimilarity = ($soundex1 === $soundex2) ? 100 : 0;
    
    // Weighted average
    return (int)((0.5 * $levenshteinSimilarity) + (0.3 * $substringSimilarity) + (0.2 * $soundexSimilarity));
}

// Include admin header
include '../includes/admin-header.php';
?>

<div class="container">
    <!-- Admin Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Column Relationship Finder</h1>
                <p class="admin-hero-subtitle">Identify semantically similar column names across different tables</p>
            </div>
            <div class="hero-actions">
                <a href="<?php echo $adminBaseUrl; ?>tools/index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left btn-icon"></i> Back to Tools
                </a>
            </div>
        </div>
    </div>
    
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle alert-icon"></i>
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?>">
        <i class="fas fa-info-circle alert-icon"></i>
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <div class="admin-form-container">
        <div class="admin-form-title">Search for Column Relationships</div>
        
        <form method="GET" action="" class="search-form">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="search" class="form-label">Column Name</label>
                    <input type="text" id="search" name="search" class="form-control" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Enter column name (e.g., item_id, userId)">
                </div>
                
                <div class="form-group col-md-4">
                    <label for="min_similarity" class="form-label">Minimum Similarity (%)</label>
                    <input type="number" id="min_similarity" name="min_similarity" class="form-control" value="<?php echo $minSimilarity; ?>" min="1" max="100">
                </div>
                
                <div class="form-group col-md-2 form-buttons-bottom">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-12">
                    <div class="form-text">
                        Leave the column name empty and click "Analyze All Columns" to find all potential relationships.
                    </div>
                </div>
            </div>
            
            <div class="form-buttons">
                <button type="submit" name="analyze_all" value="1" class="btn btn-secondary">
                    <i class="fas fa-database"></i> Analyze All Columns
                </button>
            </div>
        </form>
    </div>
    
    <?php if (!empty($columnRelationships)): ?>
    <div class="admin-form-container">
        <div class="admin-form-title">Column Relationship Results</div>
        
        <?php if (!empty($searchTerm)): ?>
        <!-- Results for specific search term -->
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Similarity</th>
                        <th>Normalized Name</th>
                        <th>Table</th>
                        <th>Column</th>
                        <th>Type</th>
                        <th>Key</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($columnRelationships as $normalizedName => $relationship): ?>
                        <?php foreach ($relationship['columns'] as $column): ?>
                            <tr>
                                <td>
                                    <div class="similarity-bar">
                                        <div class="similarity-fill" style="width: <?php echo $relationship['similarity']; ?>%"></div>
                                        <span class="similarity-text"><?php echo $relationship['similarity']; ?>%</span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($normalizedName); ?></td>
                                <td><?php echo htmlspecialchars($column['table']); ?></td>
                                <td><?php echo htmlspecialchars($column['column']); ?></td>
                                <td><?php echo htmlspecialchars($column['type']); ?></td>
                                <td><?php echo htmlspecialchars($column['key']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <!-- Results for full analysis -->
        <div class="relationship-cards">
            <?php foreach ($columnRelationships as $pairKey => $relationship): ?>
                <div class="relationship-card">
                    <div class="relationship-header">
                        <h3 class="relationship-title">
                            <?php echo htmlspecialchars($relationship['name1']); ?> ~ <?php echo htmlspecialchars($relationship['name2']); ?>
                        </h3>
                        <div class="similarity-badge">
                            <?php echo $relationship['similarity']; ?>% Match
                        </div>
                    </div>
                    
                    <div class="relationship-columns">
                        <div class="column-group">
                            <h4 class="column-group-title"><?php echo htmlspecialchars($relationship['name1']); ?></h4>
                            <ul class="column-list">
                                <?php foreach ($relationship['columns1'] as $column): ?>
                                <li class="column-item">
                                    <span class="column-table"><?php echo htmlspecialchars($column['table']); ?></span>
                                    <span class="column-name"><?php echo htmlspecialchars($column['column']); ?></span>
                                    <span class="column-type">(<?php echo htmlspecialchars($column['type']); ?>)</span>
                                    <?php if (!empty($column['key'])): ?>
                                    <span class="column-key"><?php echo htmlspecialchars($column['key']); ?></span>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="column-separator">
                            <div class="similarity-bar vertical">
                                <div class="similarity-fill" style="height: <?php echo $relationship['similarity']; ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="column-group">
                            <h4 class="column-group-title"><?php echo htmlspecialchars($relationship['name2']); ?></h4>
                            <ul class="column-list">
                                <?php foreach ($relationship['columns2'] as $column): ?>
                                <li class="column-item">
                                    <span class="column-table"><?php echo htmlspecialchars($column['table']); ?></span>
                                    <span class="column-name"><?php echo htmlspecialchars($column['column']); ?></span>
                                    <span class="column-type">(<?php echo htmlspecialchars($column['type']); ?>)</span>
                                    <?php if (!empty($column['key'])): ?>
                                    <span class="column-key"><?php echo htmlspecialchars($column['key']); ?></span>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="relationship-actions">
                        <button type="button" class="btn btn-sm btn-secondary create-join-query" 
                                data-columns1='<?php echo json_encode($relationship["columns1"]); ?>' 
                                data-columns2='<?php echo json_encode($relationship["columns2"]); ?>'>
                            <i class="fas fa-code"></i> Generate JOIN Query
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Generated JOIN Query Modal -->
    <div class="modal-backdrop" id="joinQueryModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Generated JOIN Query</h3>
                <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="joinQuery" class="form-label">SQL Query</label>
                    <textarea id="joinQuery" class="form-control code-editor" rows="10" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                <button type="button" class="btn btn-primary" onclick="copyQuery()">Copy to Clipboard</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
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

.similarity-bar {
    position: relative;
    width: 100%;
    height: 20px;
    background-color: var(--secondary);
    border-radius: 4px;
    overflow: hidden;
}

.similarity-fill {
    position: absolute;
    height: 100%;
    background: linear-gradient(to right, var(--danger), var(--accent), var(--success));
    border-radius: 4px;
}

.similarity-text {
    position: absolute;
    width: 100%;
    text-align: center;
    font-size: 12px;
    font-weight: bold;
    line-height: 20px;
    color: white;
    text-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
}

.relationship-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.relationship-card {
    background-color: var(--primary);
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--secondary);
}

.relationship-header {
    padding: 15px;
    background-color: var(--secondary);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.relationship-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.similarity-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    background-color: var(--primary);
    color: var(--accent);
}

.relationship-columns {
    display: flex;
    padding: 15px;
}

.column-group {
    flex: 1;
}

.column-group-title {
    margin: 0 0 10px 0;
    font-size: 14px;
    font-weight: 600;
    color: var(--accent);
}

.column-separator {
    width: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.similarity-bar.vertical {
    width: 8px;
    height: 100%;
    min-height: 100px;
}

.similarity-bar.vertical .similarity-fill {
    width: 100%;
    bottom: 0;
}

.column-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.column-item {
    padding: 8px 10px;
    margin-bottom: 5px;
    background-color: var(--secondary);
    border-radius: 4px;
    font-size: 14px;
}

.column-table {
    font-weight: 600;
    margin-right: 5px;
}

.column-name {
    color: var(--accent);
}

.column-type {
    font-size: 12px;
    color: #999;
    margin-left: 5px;
}

.column-key {
    display: inline-block;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 10px;
    background-color: rgba(249, 75, 31, 0.2);
    color: var(--accent);
    margin-left: 5px;
}

.relationship-actions {
    padding: 10px 15px;
    border-top: 1px solid var(--secondary);
    text-align: right;
}

.modal-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-backdrop.show {
    display: flex;
}

.modal-content {
    background-color: var(--primary);
    border-radius: 8px;
    width: 100%;
    max-width: 700px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid var(--secondary);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    font-size: 18px;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    color: var(--text);
    cursor: pointer;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid var(--secondary);
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.code-editor {
    font-family: monospace;
    white-space: pre;
    background-color: var(--secondary);
    color: var(--text);
    border: 1px solid #333;
    border-radius: 4px;
    padding: 10px;
}

@media (max-width: 768px) {
    .relationship-cards {
        grid-template-columns: 1fr;
    }
    
    .col-md-6, .col-md-4, .col-md-2 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .form-buttons-bottom {
        margin-top: 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modal = document.getElementById('joinQueryModal');
    const joinQueryTextarea = document.getElementById('joinQuery');
    const joinQueryButtons = document.querySelectorAll('.create-join-query');
    
    joinQueryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const columns1 = JSON.parse(this.dataset.columns1);
            const columns2 = JSON.parse(this.dataset.columns2);
            
            // Generate JOIN query
            const query = generateJoinQuery(columns1, columns2);
            joinQueryTextarea.value = query;
            
            // Show modal
            modal.classList.add('show');
        });
    });
    
    // Generate JOIN query based on columns
    function generateJoinQuery(columns1, columns2) {
        if (!columns1.length || !columns2.length) {
            return '-- No columns available to generate JOIN query';
        }
        
        // Get the first column from each group
        const table1 = columns1[0].table;
        const column1 = columns1[0].column;
        const table2 = columns2[0].table;
        const column2 = columns2[0].column;
        
        // Build query
        let query = '-- Generated JOIN query for potential column relationship\n';
        query += `SELECT \n`;
        query += `    a.*,\n`;
        query += `    b.*\n`;
        query += `FROM \n`;
        query += `    ${table1} AS a\n`;
        query += `    JOIN ${table2} AS b ON a.${column1} = b.${column2};\n\n`;
        
        // Add alternative
        query += '-- Alternative JOIN direction\n';
        query += `SELECT \n`;
        query += `    a.*,\n`;
        query += `    b.*\n`;
        query += `FROM \n`;
        query += `    ${table2} AS a\n`;
        query += `    JOIN ${table1} AS b ON a.${column2} = b.${column1};\n\n`;
        
        // Add note for different column data types
        if (columns1[0].type !== columns2[0].type) {
            query += '-- Note: These columns have different data types:\n';
            query += `-- ${table1}.${column1}: ${columns1[0].type}\n`;
            query += `-- ${table2}.${column2}: ${columns2[0].type}\n`;
            query += '-- You may need to use CAST or CONVERT in your JOIN condition\n';
        }
        
        return query;
    }
    
    // Close modal
    window.closeModal = function() {
        modal.classList.remove('show');
    };
    
    // Copy query to clipboard
    window.copyQuery = function() {
        joinQueryTextarea.select();
        document.execCommand('copy');
        
        // Show copy feedback
        const copyButton = document.querySelector('.modal-footer .btn-primary');
        const originalText = copyButton.innerHTML;
        copyButton.innerHTML = '<i class="fas fa-check"></i> Copied!';
        
        setTimeout(function() {
            copyButton.innerHTML = originalText;
        }, 2000);
    };
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
});
</script>

<?php
// Include admin footer
include '../includes/admin-footer.php';
?>