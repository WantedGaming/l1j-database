<?php
/**
 * Database Tools for Lineage II Database
 * 
 * A collection of tools to analyze and manage database tables.
 * Initial functionality includes column analysis and categorization.
 */

// Include required configuration files
require_once '../../includes/db_connect.php';
require_once '../includes/admin-config.php';

// Set current page for navigation highlighting
$currentAdminPage = 'db-tools';
$pageTitle = 'Database Tools';

// Get the current tool from request
$currentTool = isset($_GET['tool']) ? sanitizeInput($_GET['tool']) : 'column-analyzer';

// Available tools array - add new tools here
$availableTools = [
    'column-analyzer' => [
        'name' => 'Column Analyzer',
        'description' => 'Analyze table columns, count them, and categorize by purpose',
        'icon' => 'fa-table-columns'
    ],
    // Add more tools as needed
    // 'table-comparator' => [
    //     'name' => 'Table Comparator',
    //     'description' => 'Compare structure of different tables',
    //     'icon' => 'fa-code-compare'
    // ],
];

// Process the tool-specific form submissions
$results = [];
$message = '';
$messageType = '';

// Processing for Column Analyzer tool
if ($currentTool == 'column-analyzer' && isset($_POST['analyze_table'])) {
    $tableName = isset($_POST['table_name']) ? sanitizeInput($_POST['table_name']) : '';
    
    if (empty($tableName)) {
        $message = "Please enter a table name.";
        $messageType = 'warning';
    } else {
        $results = analyzeTableColumns($conn, $tableName);
        if (isset($results['error'])) {
            $message = $results['error'];
            $messageType = 'danger';
        } else {
            $message = "Table '{$tableName}' successfully analyzed.";
            $messageType = 'success';
        }
    }
}

// Include admin header
include '../includes/admin-header.php';

/**
 * Analyze table columns, count them, and categorize by purpose
 * 
 * @param mysqli $conn Database connection
 * @param string $tableName Name of the table to analyze
 * @return array Analysis results or error message
 */
function analyzeTableColumns($conn, $tableName) {
    // Sanitize table name to prevent SQL injection
    $tableName = $conn->real_escape_string($tableName);
    
    // Check if table exists
    $tableExistQuery = "SELECT 1 FROM information_schema.TABLES 
                        WHERE TABLE_SCHEMA = '{$conn->real_escape_string($GLOBALS['db_name'])}' 
                        AND TABLE_NAME = '{$tableName}'";
    $tableExistResult = $conn->query($tableExistQuery);
    
    if (!$tableExistResult || $tableExistResult->num_rows == 0) {
        return ['error' => "Table '{$tableName}' does not exist."];
    }
    
    // Get table columns information
    $columnsQuery = "SELECT COLUMN_NAME, DATA_TYPE, COLUMN_TYPE, IS_NULLABLE, 
                     COLUMN_DEFAULT, EXTRA, COLUMN_COMMENT
                     FROM information_schema.COLUMNS 
                     WHERE TABLE_SCHEMA = '{$conn->real_escape_string($GLOBALS['db_name'])}'
                     AND TABLE_NAME = '{$tableName}'
                     ORDER BY ORDINAL_POSITION";
    
    $columnsResult = $conn->query($columnsQuery);
    
    if (!$columnsResult) {
        return ['error' => "Error fetching columns: " . $conn->error];
    }
    
    // Prepare the results
    $columns = [];
    $columnCount = 0;
    $categories = [];
    
    // Column category patterns and purposes
    $categoryPatterns = [
        'id' => [
            'pattern' => '/(^id$|_id$|^objid$|^npcid$|^itemid$|^mapid$)/i',
            'purpose' => 'Primary or foreign key identifier'
        ],
        'name' => [
            'pattern' => '/(^name$|_name$|^char_name$|^login$|^title$|^locationname$)/i',
            'purpose' => 'Display name or title'
        ],
        'description' => [
            'pattern' => '/(^desc|_desc|description)/i',
            'purpose' => 'Descriptive text'
        ],
        'flag' => [
            'pattern' => '/(^is_|^has_|^can_|_flag$|^markable$|^teleportable$|^escapable$)/i',
            'purpose' => 'Boolean flag or indicator'
        ],
        'count' => [
            'pattern' => '/(^count$|_count$|^amount$|_amount$)/i',
            'purpose' => 'Numeric counter or quantity'
        ],
        'date' => [
            'pattern' => '/(^date|_date|_time$|^time$|^created|^updated|^lastactive$)/i',
            'purpose' => 'Date or timestamp'
        ],
        'level' => [
            'pattern' => '/(^level$|_level$|^lvl$)/i',
            'purpose' => 'Level or tier indicator'
        ],
        'location' => [
            'pattern' => '/(^loc|position|coordinate|^x$|^y$|^z$|^locx$|^locy$)/i',
            'purpose' => 'Spatial position or location'
        ],
        'stat' => [
            'pattern' => '/(^str$|^dex$|^con$|^cha$|^int$|^wis$|^ac$|^hp$|^mp$)/i',
            'purpose' => 'Character or entity statistic'
        ],
        'rate' => [
            'pattern' => '/(^rate$|_rate$|^chance$|_chance$)/i',
            'purpose' => 'Probability or rate value'
        ],
        'type' => [
            'pattern' => '/(^type$|_type$|^category$|_category$)/i',
            'purpose' => 'Classification or categorization'
        ],
        'zone' => [
            'pattern' => '/(^zone$|_zone$|Zone$)/i',
            'purpose' => 'Area or region designation'
        ],
        'access' => [
            'pattern' => '/(^access|_access|^permission|_permission|_level$)/i',
            'purpose' => 'Access control or permission setting'
        ],
        'image' => [
            'pattern' => '/(^img|_img|^image|_image|^icon|_icon|^gfx|_gfx|^png|_png$)/i',
            'purpose' => 'Visual representation or resource identifier'
        ]
    ];
    
    // Process each column
    while ($column = $columnsResult->fetch_assoc()) {
        $columnCount++;
        $columnName = $column['COLUMN_NAME'];
        $column['category'] = 'other'; // Default category
        $column['purpose'] = 'Miscellaneous data'; // Default purpose
        
        // Categorize the column based on name patterns
        foreach ($categoryPatterns as $category => $info) {
            if (preg_match($info['pattern'], $columnName)) {
                $column['category'] = $category;
                $column['purpose'] = $info['purpose'];
                
                // Initialize category if not exists
                if (!isset($categories[$category])) {
                    $categories[$category] = [
                        'name' => ucfirst($category),
                        'purpose' => $info['purpose'],
                        'count' => 0,
                        'columns' => []
                    ];
                }
                
                // Add column to this category
                $categories[$category]['count']++;
                $categories[$category]['columns'][] = $columnName;
                break;
            }
        }
        
        // Add to the other category if not matched
        if ($column['category'] === 'other' && !isset($categories['other'])) {
            $categories['other'] = [
                'name' => 'Other',
                'purpose' => 'Miscellaneous data',
                'count' => 0,
                'columns' => []
            ];
        }
        
        // Add to other if not matched with specific patterns
        if ($column['category'] === 'other') {
            $categories['other']['count']++;
            $categories['other']['columns'][] = $columnName;
        }
        
        $columns[] = $column;
    }
    
    // Sort categories alphabetically
    ksort($categories);
    
    // Move 'other' category to the end
    if (isset($categories['other'])) {
        $other = $categories['other'];
        unset($categories['other']);
        $categories['other'] = $other;
    }
    
    return [
        'table_name' => $tableName,
        'column_count' => $columnCount,
        'columns' => $columns,
        'categories' => $categories
    ];
}

/**
 * Generate column category badge with appropriate styling
 * 
 * @param string $category Category name
 * @return string HTML for styled badge
 */
function getCategoryBadge($category) {
    $colors = [
        'id' => 'primary',
        'name' => 'success',
        'description' => 'info',
        'flag' => 'warning',
        'count' => 'secondary',
        'date' => 'danger',
        'level' => 'info',
        'location' => 'warning',
        'stat' => 'primary',
        'rate' => 'success',
        'type' => 'info',
        'zone' => 'warning',
        'access' => 'danger',
        'image' => 'secondary',
        'other' => 'secondary'
    ];
    
    $color = isset($colors[$category]) ? $colors[$category] : 'secondary';
    return '<span class="badge bg-' . $color . '">' . ucfirst($category) . '</span>';
}
?>

<div class="container">
    <!-- Enhanced Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Database Tools</h1>
                <p class="admin-hero-subtitle">Analyze and manage your database structure</p>
            </div>
        </div>
    </div>
    
    <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?>">
        <i class="fas fa-info-circle alert-icon"></i>
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <!-- Tools Navigation -->
    <div class="admin-card-grid">
        <?php foreach ($availableTools as $toolId => $tool): ?>
        <a href="<?php echo $adminBaseUrl; ?>pages/tools/db-tools.php?tool=<?php echo $toolId; ?>" 
           class="admin-card <?php echo $currentTool === $toolId ? 'active' : ''; ?>">
            <div class="admin-card-header">
                <h2 class="admin-card-title">
                    <i class="fas <?php echo $tool['icon']; ?>"></i> 
                    <?php echo $tool['name']; ?>
                </h2>
            </div>
            <div class="admin-card-body">
                <p><?php echo $tool['description']; ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    
    <!-- Tool Content -->
    <div class="admin-form-container">
        <?php if ($currentTool === 'column-analyzer'): ?>
            <h2 class="admin-form-title">Column Analyzer</h2>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="table_name" class="form-label">Table Name</label>
                    <div class="search-input-group">
                        <input type="text" id="table_name" name="table_name" class="form-control" placeholder="Enter table name (e.g., characters, weapon, mapids)" required>
                        <button type="submit" name="analyze_table" class="btn btn-primary">
                            <i class="fas fa-search"></i> Analyze
                        </button>
                    </div>
                </div>
            </form>
            
            <?php if (!empty($results) && !isset($results['error'])): ?>
                <div class="admin-table-header">
                    <h3 class="admin-table-title">
                        Analysis Results for '<?php echo htmlspecialchars($results['table_name']); ?>'
                    </h3>
                    <div class="admin-table-actions">
                        <span class="badge bg-primary">Total Columns: <?php echo $results['column_count']; ?></span>
                    </div>
                </div>
                
                <!-- Category Summary -->
                <div class="detail-section">
                    <h3 class="detail-section-title">Column Categories</h3>
                    <div class="stats-grid">
                        <?php foreach ($results['categories'] as $category => $data): ?>
                        <div class="stat-item">
                            <div class="stat-label"><?php echo getCategoryBadge($category); ?></div>
                            <div class="stat-value"><?php echo $data['count']; ?> columns</div>
                            <div class="stat-meta"><?php echo $data['purpose']; ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Detailed Column List -->
                <div class="admin-table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Column Name</th>
                                <th>Data Type</th>
                                <th>Nullable</th>
                                <th>Default</th>
                                <th>Extra</th>
                                <th>Category</th>
                                <th>Purpose</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results['columns'] as $column): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($column['COLUMN_NAME']); ?></strong></td>
                                <td><?php echo htmlspecialchars($column['COLUMN_TYPE']); ?></td>
                                <td><?php echo $column['IS_NULLABLE'] === 'YES' ? 'Yes' : 'No'; ?></td>
                                <td><?php echo $column['COLUMN_DEFAULT'] !== null ? htmlspecialchars($column['COLUMN_DEFAULT']) : 'NULL'; ?></td>
                                <td><?php echo htmlspecialchars($column['EXTRA']); ?></td>
                                <td><?php echo getCategoryBadge($column['category']); ?></td>
                                <td><?php echo htmlspecialchars($column['purpose']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Category Breakdown -->
                <div class="admin-form-container">
                    <h3 class="admin-form-title">Columns by Category</h3>
                    
                    <div class="accordion">
                        <?php foreach ($results['categories'] as $category => $data): ?>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <h4>
                                    <?php echo getCategoryBadge($category); ?> 
                                    <span class="ml-2">(<?php echo $data['count']; ?> columns)</span>
                                </h4>
                                <span class="accordion-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="accordion-content">
                                <p><strong>Purpose:</strong> <?php echo $data['purpose']; ?></p>
                                <div class="column-tags">
                                    <?php foreach ($data['columns'] as $columnName): ?>
                                    <span class="column-tag"><?php echo htmlspecialchars($columnName); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Export Options -->
                <div class="form-buttons mt-4">
                    <button type="button" class="btn btn-secondary" onclick="printAnalysis()">
                        <i class="fas fa-print"></i> Print Analysis
                    </button>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</div>

<style>
/* Additional styles for the DB Tools page */
.accordion {
    margin-bottom: 20px;
}

.accordion-item {
    margin-bottom: 10px;
    background-color: var(--primary);
    border-radius: 8px;
    overflow: hidden;
}

.accordion-header {
    padding: 15px;
    background-color: var(--secondary);
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.accordion-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 500;
    display: flex;
    align-items: center;
}

.accordion-icon {
    transition: transform 0.3s ease;
}

.accordion-item.active .accordion-icon i {
    transform: rotate(180deg);
}

.accordion-content {
    padding: 0 15px;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
}

.accordion-item.active .accordion-content {
    padding: 15px;
    max-height: 500px;
}

.column-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.column-tag {
    padding: 5px 10px;
    background-color: var(--secondary);
    border-radius: 4px;
    font-size: 14px;
    font-family: monospace;
}

.admin-card.active {
    border: 2px solid var(--accent);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.stat-item {
    padding: 15px;
    background-color: var(--secondary);
    border-radius: 8px;
}

.stat-label {
    margin-bottom: 8px;
}

.stat-value {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 5px;
}

.stat-meta {
    font-size: 13px;
    color: #bbb;
}

.ml-2 {
    margin-left: 8px;
}

.mt-4 {
    margin-top: 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Accordion functionality
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        
        header.addEventListener('click', function() {
            item.classList.toggle('active');
        });
    });
    
    // Activate all accordions initially
    setTimeout(() => {
        accordionItems.forEach(item => {
            item.classList.add('active');
        });
    }, 500);
});

// Print analysis function
function printAnalysis() {
    // Create a new window with just the analysis results
    const printWindow = window.open('', '_blank');
    
    // Table name and basic info
    const tableName = document.querySelector('.admin-table-title').innerText;
    const columnCount = document.querySelector('.admin-table-actions .badge').innerText;
    
    // Create print content
    let printContent = `
        <html>
        <head>
            <title>Database Analysis: ${tableName}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1, h2, h3 { color: #333; }
                table { border-collapse: collapse; width: 100%; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .badge { 
                    display: inline-block;
                    padding: 3px 8px;
                    border-radius: 4px;
                    background-color: #6c757d;
                    color: white;
                    font-size: 12px;
                }
                .category { margin-bottom: 20px; }
                .category-header { 
                    background-color: #f8f9fa;
                    padding: 10px;
                    border-radius: 4px;
                    margin-bottom: 10px;
                }
                .column-list {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                }
                .column-item {
                    background-color: #f1f1f1;
                    padding: 5px 10px;
                    border-radius: 4px;
                    font-family: monospace;
                }
            </style>
        </head>
        <body>
            <h1>${tableName}</h1>
            <p>${columnCount}</p>
            
            <h2>Column Categories</h2>
    `;
    
    // Add categories
    const categories = document.querySelectorAll('.accordion-item');
    categories.forEach(category => {
        const header = category.querySelector('.accordion-header h4').innerText;
        const purpose = category.querySelector('.accordion-content p').innerText;
        const columns = Array.from(category.querySelectorAll('.column-tag')).map(tag => tag.innerText);
        
        printContent += `
            <div class="category">
                <div class="category-header">
                    <h3>${header}</h3>
                    <p>${purpose}</p>
                </div>
                <div class="column-list">
                    ${columns.map(col => `<span class="column-item">${col}</span>`).join('')}
                </div>
            </div>
        `;
    });
    
    // Add detailed table
    printContent += `<h2>Column Details</h2>`;
    
    // Clone the table content
    const table = document.querySelector('.admin-table table').cloneNode(true);
    printContent += `<table>${table.innerHTML}</table>`;
    
    // Close the HTML
    printContent += `
            <p><small>Generated by Lineage II Database Admin Tools on ${new Date().toLocaleString()}</small></p>
        </body>
        </html>
    `;
    
    // Write content to new window and print
    printWindow.document.open();
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Trigger print after content is loaded
    setTimeout(() => {
        printWindow.print();
    }, 500);
}
</script>

<?php
// Include admin footer
include '../includes/admin-footer.php';
?>