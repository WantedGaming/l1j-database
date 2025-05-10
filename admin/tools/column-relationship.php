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
                // Store both original and normalized names
                $normalizedName = normalizeColumnName($columnName);
                
                $tableColumns[] = [
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
            // Search for specific term - use special normalization for search term
            $normalizedSearch = normalizeColumnName($searchTerm, true);
            
            foreach ($tableColumns as $column) {
                $similarity = calculateSimilarity($normalizedSearch, $column['normalized']);
                
                if ($similarity >= $minSimilarity) {
                    if (!isset($columnRelationships[$similarity])) {
                        $columnRelationships[$similarity] = [];
                    }
                    
                    $columnRelationships[$similarity][] = $column;
                }
            }
            
            // Sort by similarity (descending)
            krsort($columnRelationships);
            
            // Convert to desired format
            $formattedRelationships = [];
            foreach ($columnRelationships as $similarity => $columns) {
                foreach ($columns as $column) {
                    $normalizedForGroup = $column['normalized'];
                    
                    if (!isset($formattedRelationships[$normalizedForGroup])) {
                        $formattedRelationships[$normalizedForGroup] = [
                            'columns' => [],
                            'similarity' => $similarity,
                            'normalized' => $normalizedForGroup
                        ];
                    }
                    
                    $formattedRelationships[$normalizedForGroup]['columns'][] = $column;
                }
            }
            
            $columnRelationships = $formattedRelationships;
            
            $resultCount = 0;
            foreach ($columnRelationships as $relationship) {
                $resultCount += count($relationship['columns']);
            }
            
            $message = $resultCount . " potential column relationships found for '{$searchTerm}'";
            $messageType = 'info';
        } else {
            // Analyze all potential relationships
            $analyzed = [];
            
            // Group columns by their normalized names
            $normalizedGroups = [];
            foreach ($tableColumns as $column) {
                $normalizedName = $column['normalized'];
                
                if (!isset($normalizedGroups[$normalizedName])) {
                    $normalizedGroups[$normalizedName] = [];
                }
                
                $normalizedGroups[$normalizedName][] = $column;
            }
            
            // Compare groups
            $normalizedNames = array_keys($normalizedGroups);
            $count = count($normalizedNames);
            
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $name1 = $normalizedNames[$i];
                    $name2 = $normalizedNames[$j];
                    
                    // Calculate similarity between the normalized names
                    $similarity = calculateSimilarity($name1, $name2);
                    
                    if ($similarity >= $minSimilarity) {
                        $pairKey = ($name1 < $name2) ? "{$name1}-{$name2}" : "{$name2}-{$name1}";
                        
                        if (!isset($columnRelationships[$pairKey])) {
                            $columnRelationships[$pairKey] = [
                                'name1' => $name1,
                                'name2' => $name2,
                                'columns1' => $normalizedGroups[$name1],
                                'columns2' => $normalizedGroups[$name2],
                                'similarity' => $similarity
                            ];
                        }
                    }
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
 * Performs intelligent normalization while preserving key components
 * 
 * @param string $columnName Column name to normalize
 * @param bool $isSearchTerm Whether this is a user search term
 * @return string Normalized column name
 */
function normalizeColumnName($columnName, $isSearchTerm = false) {
    // Convert to lowercase
    $name = strtolower($columnName);
    
    // For search terms, preserve the important parts
    if ($isSearchTerm) {
        // Keep underscores for exact matching but standardize camelCase to snake_case
        $name = preg_replace('/([a-z])([A-Z])/', '$1_$2', $name);
        $name = strtolower($name);
        
        // Don't strip suffixes from search terms
        return $name;
    }
    
    // For database columns:
    // Convert camelCase to snake_case for standardized comparison
    $name = preg_replace('/([a-z])([A-Z])/', '$1_$2', $name);
    $name = strtolower($name);
    
    // Remove common prefixes
    $prefixes = ['tbl_', 'fk_', 'pk_', 'ix_', 'col_'];
    foreach ($prefixes as $prefix) {
        if (strpos($name, $prefix) === 0) {
            $name = substr($name, strlen($prefix));
        }
    }
    
    return $name;
}

/**
 * Calculate similarity between two strings
 * Improved to handle different column naming conventions
 * 
 * @param string $str1 First string
 * @param string $str2 Second string
 * @return int Similarity percentage (0-100)
 */
function calculateSimilarity($str1, $str2) {
    // Direct match check (after normalization)
    if ($str1 === $str2) {
        return 100;
    }
    
    // Extract parts for structural comparison (split by underscore)
    $parts1 = explode('_', $str1);
    $parts2 = explode('_', $str2);
    
    // Compare if columns have the same "base" parts
    $commonParts = array_intersect($parts1, $parts2);
    $allParts = array_unique(array_merge($parts1, $parts2));
    $partsSimilarity = count($commonParts) / count($allParts) * 100;
    
    // Levenshtein distance for overall similarity
    $levenshtein = levenshtein($str1, $str2);
    $maxLength = max(strlen($str1), strlen($str2));
    
    if ($maxLength === 0) {
        return 100; // Both strings are empty
    }
    
    $levenshteinSimilarity = 100 - (($levenshtein / $maxLength) * 100);
    
    // Partial word matching
    // Check if one string is a direct substring of the other
    if (strpos($str1, $str2) !== false || strpos($str2, $str1) !== false) {
        $substringSimilarity = 90; // High score for substrings
    } else {
        // Otherwise check for common prefixes/chunks
        $minLength = min(strlen($str1), strlen($str2));
        $matchingChars = 0;
        
        for ($i = 0; $i < $minLength; $i++) {
            if ($str1[$i] === $str2[$i]) {
                $matchingChars++;
            } else {
                // Break on first mismatch for prefix checking
                break;
            }
        }
        
        $prefixSimilarity = ($matchingChars / $maxLength) * 100;
        $substringSimilarity = $prefixSimilarity;
    }
    
    // Calculate soundex similarity
    $soundex1 = soundex($str1);
    $soundex2 = soundex($str2);
    $soundexSimilarity = ($soundex1 === $soundex2) ? 100 : 0;
    
    // Weighted average - adjusted to prioritize structural similarity
    return (int)((0.4 * $levenshteinSimilarity) + (0.4 * $partsSimilarity) + (0.1 * $substringSimilarity) + (0.1 * $soundexSimilarity));
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
                <a href="<?php echo $adminBaseUrl; ?>tools/tools-index.php" class="btn btn-secondary">
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
    <div class="admin-form-container results-container">
        <div class="results-header">
            <div class="results-title">Column Relationship Results</div>
            <?php
                $resultsCount = !empty($searchTerm) 
                    ? array_sum(array_map(function($r) { return count($r['columns']); }, $columnRelationships)) 
                    : count($columnRelationships);
            ?>
            <div class="results-count"><?php echo $resultsCount; ?> Matches Found</div>
        </div>
        
        <?php if (!empty($searchTerm)): ?>
        <!-- Results for specific search term -->
        <div class="table-responsive">
            <table class="enhanced-table">
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
                                    <div class="similarity-bar-enhanced">
                                        <div class="similarity-fill-enhanced" style="width: <?php echo $relationship['similarity']; ?>%"></div>
                                        <span class="similarity-text-enhanced"><?php echo $relationship['similarity']; ?>%</span>
                                    </div>
                                </td>
                                <td><span class="column-name-cell"><?php echo htmlspecialchars($normalizedName); ?></span></td>
                                <td><span class="table-cell"><?php echo htmlspecialchars($column['table']); ?></span></td>
                                <td><span class="column-name-cell"><?php echo htmlspecialchars($column['column']); ?></span></td>
                                <td><span class="type-cell"><?php echo htmlspecialchars($column['type']); ?></span></td>
                                <td>
                                    <?php if (!empty($column['key'])): ?>
                                        <?php if ($column['key'] === 'PRI'): ?>
                                            <span class="key-primary">PRIMARY</span>
                                        <?php elseif ($column['key'] === 'MUL'): ?>
                                            <span class="key-foreign">FOREIGN</span>
                                        <?php else: ?>
                                            <span class="key-cell"><?php echo htmlspecialchars($column['key']); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <!-- Results for full analysis with enhanced cards -->
        <div class="relationship-cards-enhanced">
            <?php $index = 0; ?>
            <?php foreach ($columnRelationships as $pairKey => $relationship): ?>
                <div class="relationship-card-enhanced" style="animation-delay: <?php echo $index * 0.05; ?>s">
                    <div class="relationship-header-enhanced">
                        <h3 class="relationship-title-enhanced">
                            <?php echo htmlspecialchars($relationship['name1']); ?> ~ <?php echo htmlspecialchars($relationship['name2']); ?>
                        </h3>
                        <div class="similarity-badge-enhanced">
                            <?php echo $relationship['similarity']; ?>% Match
                        </div>
                    </div>
                    
                    <div class="relationship-columns-enhanced">
                        <div class="column-group-enhanced">
                            <h4 class="column-group-title-enhanced"><?php echo htmlspecialchars($relationship['name1']); ?></h4>
                            <ul class="column-list">
                                <?php foreach ($relationship['columns1'] as $column): ?>
                                <li class="column-item">
                                    <span class="column-table"><?php echo htmlspecialchars($column['table']); ?></span>
                                    <span class="column-name"><?php echo htmlspecialchars($column['column']); ?></span>
                                    <?php if (!empty($column['type'])): ?>
                                        <span class="column-type"><?php echo htmlspecialchars($column['type']); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($column['key'])): ?>
                                        <?php if ($column['key'] === 'PRI'): ?>
                                            <span class="column-key">PRIMARY</span>
                                        <?php elseif ($column['key'] === 'MUL'): ?>
                                            <span class="column-key">FOREIGN</span>
                                        <?php else: ?>
                                            <span class="column-key"><?php echo htmlspecialchars($column['key']); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="column-separator-enhanced">
                            <div class="similarity-bar-enhanced vertical">
                                <div class="similarity-fill-enhanced vertical" style="height: <?php echo $relationship['similarity']; ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="column-group-enhanced">
                            <h4 class="column-group-title-enhanced"><?php echo htmlspecialchars($relationship['name2']); ?></h4>
                            <ul class="column-list">
                                <?php foreach ($relationship['columns2'] as $column): ?>
                                <li class="column-item">
                                    <span class="column-table"><?php echo htmlspecialchars($column['table']); ?></span>
                                    <span class="column-name"><?php echo htmlspecialchars($column['column']); ?></span>
                                    <?php if (!empty($column['type'])): ?>
                                        <span class="column-type"><?php echo htmlspecialchars($column['type']); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($column['key'])): ?>
                                        <?php if ($column['key'] === 'PRI'): ?>
                                            <span class="column-key">PRIMARY</span>
                                        <?php elseif ($column['key'] === 'MUL'): ?>
                                            <span class="column-key">FOREIGN</span>
                                        <?php else: ?>
                                            <span class="column-key"><?php echo htmlspecialchars($column['key']); ?></span>
                                        <?php endif; ?>
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
                <?php $index++; ?>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modal = document.getElementById('joinQueryModal');
    const joinQueryTextarea = document.getElementById('joinQuery');
    const joinQueryButtons = document.querySelectorAll('.create-join-query');
    
    // Initialize similarity bars
    initSimilarityBars();
    
    // Animation on page load
    animateItems();
    
    joinQueryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const columns1 = JSON.parse(this.dataset.columns1);
            const columns2 = JSON.parse(this.dataset.columns2);
            
            // Generate JOIN query
            const query = generateJoinQuery(columns1, columns2);
            joinQueryTextarea.value = query;
            
            // Show modal
            showModal();
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
            query += '-- You may need to use CAST or CONVERT in your JOIN condition\n`;
        }
        
        return query;
    }
    
    // Show modal with animation
    function showModal() {
        modal.classList.add('show');
        setTimeout(() => {
            modal.querySelector('.modal-content').style.opacity = '1';
            modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        }, 10);
    }
    
    // Close modal
    window.closeModal = function() {
        modal.querySelector('.modal-content').style.opacity = '0';
        modal.querySelector('.modal-content').style.transform = 'translateY(20px)';
        setTimeout(() => {
            modal.classList.remove('show');
        }, 300);
    };
    
    // Copy query to clipboard
    window.copyQuery = function() {
        joinQueryTextarea.select();
        document.execCommand('copy');
        
        // Show copy feedback
        const copyButton = document.querySelector('.modal-footer .btn-primary');
        const originalText = copyButton.innerHTML;
        copyButton.innerHTML = '<i class="fas fa-check"></i> Copied!';
        copyButton.classList.add('copy-success');
        
        setTimeout(function() {
            copyButton.innerHTML = originalText;
            copyButton.classList.remove('copy-success');
        }, 2000);
    };
    
    // Initialize similarity bars with animation
    function initSimilarityBars() {
        // Horizontal bars - animate after small delay
        document.querySelectorAll('.similarity-fill-enhanced:not(.vertical)').forEach(bar => {
            const finalWidth = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = finalWidth;
            }, 300);
        });
        
        // Vertical bars
        document.querySelectorAll('.similarity-fill-enhanced.vertical').forEach(bar => {
            const finalHeight = bar.style.height;
            bar.style.height = '0';
            setTimeout(() => {
                bar.style.height = finalHeight;
            }, 300);
        });
    }
    
    // Animate relationship cards on page load - already handled by CSS animation
    function animateItems() {
        // Cards are now animated with CSS using animation-delay for staggered effect
    }
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
});
</script>

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

/* Results Container */
.results-container {
    margin-top: 30px;
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Results Header */
.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    border-bottom: 1px solid var(--secondary);
    padding-bottom: 15px;
    padding-left: 25px;
    padding-right: 25px;
    padding-top: 20px;
}

.results-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text);
    position: relative;
    padding-left: 28px;
}

.results-title::before {
    content: '\f0b0';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    color: var(--accent);
    font-size: 20px;
}

.results-count {
    background-color: var(--accent);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 2px 8px rgba(249, 75, 31, 0.3);
    transition: all 0.3s ease;
}

.results-count:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(249, 75, 31, 0.4);
}

.results-count::before {
    content: '\f201';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    margin-right: 8px;
}

/* Enhanced Table Layout */
.table-responsive {
    overflow-x: auto;
    margin: 0 25px 25px 25px;
}

.enhanced-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    background-color: var(--primary);
}

.enhanced-table thead th {
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

.enhanced-table thead th:first-child {
    border-top-left-radius: var(--border-radius);
}

.enhanced-table thead th:last-child {
    border-top-right-radius: var(--border-radius);
}

.enhanced-table tbody tr {
    transition: all 0.3s ease;
}

.enhanced-table tbody tr:nth-child(odd) {
    background-color: rgba(255, 255, 255, 0.02);
}

.enhanced-table tbody tr:hover {
    background-color: rgba(249, 75, 31, 0.05);
    transform: translateX(5px);
}

.enhanced-table td {
    padding: 14px 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 14px;
    transition: all 0.3s ease;
}

.enhanced-table tr:last-child td:first-child {
    border-bottom-left-radius: var(--border-radius);
}

.enhanced-table tr:last-child td:last-child {
    border-bottom-right-radius: var(--border-radius);
}

/* Column Name Styling */
.column-name-cell {
    font-weight: 600;
    color: var(--accent);
    background-color: rgba(249, 75, 31, 0.05);
    border-radius: 4px;
    padding: 5px 10px;
    display: inline-block;
}

.table-cell {
    color: var(--text);
    position: relative;
    padding-left: 25px;
    font-weight: 500;
    display: inline-block;
}

.table-cell::before {
    content: '\f0ce';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    left: 0;
    color: var(--text-muted);
    font-size: 14px;
}

.type-cell {
    font-family: 'Courier New', monospace;
    color: #17a2b8;
    font-size: 13px;
    background-color: rgba(23, 162, 184, 0.05);
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: 500;
    display: inline-block;
}

.key-cell {
    font-weight: 600;
}

.key-primary {
    color: #ffc107;
    background-color: rgba(255, 193, 7, 0.1);
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.key-foreign {
    color: #6f42c1;
    background-color: rgba(111, 66, 193, 0.1);
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
    border: 1px solid rgba(111, 66, 193, 0.2);
}

/* Enhanced Similarity Visualization */
.similarity-bar-enhanced {
    position: relative;
    width: 100%;
    height: 24px;
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
}

.similarity-fill-enhanced {
    position: absolute;
    height: 100%;
    width: 0; /* Start at 0 for animation */
    background: linear-gradient(to right, 
        rgba(220, 53, 69, 0.8) 0%, 
        rgba(255, 193, 7, 0.8) 50%, 
        rgba(40, 167, 69, 0.8) 100%);
    border-radius: 12px;
    transition: width 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    box-shadow: 0 0 10px rgba(249, 75, 31, 0.3);
}

.similarity-fill-enhanced.vertical {
    width: 100%;
    height: 0; /* Start at 0 for animation */
    background: linear-gradient(to top, 
        rgba(220, 53, 69, 0.8) 0%, 
        rgba(255, 193, 7, 0.8) 50%, 
        rgba(40, 167, 69, 0.8) 100%);
    transition: height 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.similarity-text-enhanced {
    position: absolute;
    width: 100%;
    text-align: center;
    font-size: 13px;
    font-weight: bold;
    line-height: 24px;
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.7);
    z-index: 2;
}

.similarity-bar-enhanced.vertical {
    width: 10px;
    height: 100%;
    min-height: 80px;
    border-radius: 5px;
}

/* Enhanced Relationship Cards */
.relationship-cards-enhanced {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(550px, 1fr));
    gap: 25px;
    margin: 0 25px 25px 25px;
}

.relationship-card-enhanced {
    background-color: var(--primary);
    border-radius: var(--border-radius);
    overflow: hidden;
    border: 1px solid var(--secondary);
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    position: relative;
    transform: translateY(20px);
    opacity: 0;
    animation: cardAppear 0.5s forwards;
}

@keyframes cardAppear {
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.relationship-card-enhanced:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    border-color: var(--accent);
    z-index: 10;
}

.relationship-header-enhanced {
    padding: 18px 22px;
    background-color: var(--secondary);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #1a1a1a;
    position: relative;
    overflow: hidden;
}

.relationship-header-enhanced::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: var(--accent);
}

.relationship-title-enhanced {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text);
    position: relative;
    padding-left: 28px;
}

.relationship-title-enhanced::before {
    content: '\f362';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    left: 0;
    color: var(--accent);
}

.similarity-badge-enhanced {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: bold;
    background-color: rgba(249, 75, 31, 0.1);
    color: var(--accent);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(249, 75, 31, 0.2);
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.similarity-badge-enhanced:hover {
    background-color: rgba(249, 75, 31, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.similarity-badge-enhanced::before {
    content: '\f201';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    margin-right: 8px;
}

/* Enhanced Column Relationship Layout */
.relationship-columns-enhanced {
    display: flex;
    padding: 22px;
    background: linear-gradient(to bottom, var(--primary), rgba(8, 8, 8, 0.95));
}

.column-group-enhanced {
    flex: 1;
    position: relative;
    padding: 5px;
}

.column-group-title-enhanced {
    margin: 0 0 15px 0;
    font-size: 15px;
    font-weight: 600;
    color: var(--accent);
    display: flex;
    align-items: center;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--secondary);
    position: relative;
}

.column-group-title-enhanced::before {
    content: '\f0ce';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    margin-right: 10px;
    font-size: 14px;
}

.column-separator-enhanced {
    width: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 8px;
    position: relative;
}

.column-separator-enhanced::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 1px;
    background: linear-gradient(to bottom, 
        transparent, 
        rgba(249, 75, 31, 0.5), 
        transparent);
    height: 80%;
}

/* Column List Styles from Original CSS with Enhancements */
.column-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.column-item {
    padding: 12px 15px;
    margin-bottom: 8px;
    background-color: var(--secondary);
    border-radius: var(--border-radius);
    font-size: 14px;
    transition: all 0.3s ease;
    position: relative;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    border-left: 3px solid var(--accent);
}

.column-item:hover {
    background-color: #0f0f0f;
    transform: translateX(5px);
}

.column-table {
    font-weight: 600;
    margin-right: 8px;
    color: var(--text);
    position: relative;
    padding-left: 20px;
}

.column-table::before {
    content: '\f0ce';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    margin-right: 6px;
    position: absolute;
    left: 0;
    color: var(--text-muted);
}

.column-name {
    color: var(--accent);
    font-weight: 500;
    margin-right: 8px;
}

.column-type {
    font-size: 12px;
    color: var(--text-muted);
    background-color: rgba(255, 255, 255, 0.05);
    padding: 3px 8px;
    border-radius: 4px;
    margin-left: auto;
}

.column-key {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    background-color: rgba(249, 75, 31, 0.15);
    color: var(--accent);
    margin-left: 8px;
    font-weight: 600;
    border: 1px solid rgba(249, 75, 31, 0.3);
}

.relationship-actions {
    padding: 15px 20px;
    border-top: 1px solid var(--secondary);
    text-align: right;
    background-color: var(--secondary);
}

/* Modal Styles */
.modal-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    backdrop-filter: blur(2px);
}

.modal-backdrop.show {
    display: flex;
    opacity: 1;
}

.modal-content {
    background-color: var(--primary);
    border-radius: var(--border-radius);
    width: 100%;
    max-width: 700px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    transform: translateY(20px);
    opacity: 0;
    transition: all 0.3s ease;
}

.modal-backdrop.show .modal-content {
    transform: translateY(0);
    opacity: 1;
}

.modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid #1a1a1a;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: var(--secondary);
}

.modal-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text);
}

.modal-title::before {
    content: '\f121';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    margin-right: 10px;
    color: var(--accent);
}

.modal-close {
    background: none;
    border: none;
    font-size: 22px;
    color: var(--text-muted);
    cursor: pointer;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background-color: var(--secondary);
    color: var(--text);
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    padding: 15px 25px;
    border-top: 1px solid #1a1a1a;
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    background-color: var(--secondary);
}

.code-editor {
    font-family: 'Courier New', monospace;
    white-space: pre;
    background-color: var(--secondary);
    color: var(--text);
    border: 1px solid #333;
    border-radius: var(--border-radius);
    padding: 15px;
    font-size: 14px;
    line-height: 1.5;
    overflow: auto;
    max-height: 400px;
    resize: none;
}

/* Success Animation */
@keyframes checkmark {
    0% { transform: scale(0); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.copy-success {
    color: var(--success) !important;
    animation: checkmark 0.5s ease-in-out;
}

/* Responsive Adjustments */
@media (max-width: 991px) {
    .relationship-cards-enhanced {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .hero-header, .results-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .hero-actions, .results-count {
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
    
    .relationship-columns-enhanced {
        flex-direction: column;
    }
    
    .column-separator-enhanced {
        height: 40px;
        width: 100%;
        margin: 10px 0;
    }
    
    .column-separator-enhanced::before {
        width: 80%;
        height: 1px;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        background: linear-gradient(to right, 
            transparent, 
            rgba(249, 75, 31, 0.5), 
            transparent);
    }
    
    .similarity-bar-enhanced.vertical {
        width: 100%;
        height: 10px;
        min-height: 0;
    }
    
    .similarity-fill-enhanced.vertical {
        width: 0;
        height: 100%;
        transition: width 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .table-responsive {
        margin: 0 15px 15px 15px;
    }
    
    .relationship-cards-enhanced {
        margin: 0 15px 15px 15px;
    }
}
</style>