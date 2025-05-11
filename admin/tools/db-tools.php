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

// Get the current tool from request - default will always be column-analyzer
$currentTool = 'column-analyzer';

// Process the tool-specific form submissions
$results = [];
$message = '';
$messageType = '';

// Fetch all table names for dropdown
function getAllTables($conn) {
    $tables = [];
    
    $query = "SELECT TABLE_NAME 
              FROM information_schema.TABLES 
              WHERE TABLE_SCHEMA = '{$conn->real_escape_string($GLOBALS['db_name'])}'
              ORDER BY TABLE_NAME";
    
    $result = $conn->query($query);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $tables[] = $row['TABLE_NAME'];
        }
    }
    
    return $tables;
}

// Get all tables for dropdown
$allTables = getAllTables($conn);

// Processing for Column Analyzer tool
if ($currentTool == 'column-analyzer' && isset($_POST['analyze_table'])) {
    $tableName = isset($_POST['table_name']) ? sanitizeInput($_POST['table_name']) : '';
    
    if (empty($tableName)) {
        $message = "Please select a table name.";
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

.search-input-group {
    display: flex;
    gap: 10px;
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
.admin-table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 25px;
    background-color: var(--secondary);
    border-bottom: 1px solid #1a1a1a;
}

.admin-table-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.admin-table-actions {
    display: flex;
    gap: 10px;
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

/* Detail Section Styles */
.detail-section {
    padding: 0 25px 20px;
    margin-bottom: 20px;
}

.detail-section-title {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 15px 0;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--secondary);
}

/* Stats Grid */
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

/* Badge Styles */
.badge {
    display: inline-block;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 20px;
    text-transform: uppercase;
}

.bg-primary {
    background-color: rgba(0, 123, 255, 0.2);
    color: #007bff;
}

.bg-secondary {
    background-color: rgba(108, 117, 125, 0.2);
    color: #6c757d;
}

.bg-success {
    background-color: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.bg-danger {
    background-color: rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.bg-warning {
    background-color: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.bg-info {
    background-color: rgba(23, 162, 184, 0.2);
    color: #17a2b8;
}

/* Accordion Styles */
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

.ml-2 {
    margin-left: 8px;
}

.mt-4 {
    margin-top: 20px;
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
    
    .admin-table-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .admin-table-actions {
        margin-top: 10px;
    }
    
    .col-md-6, .col-md-4, .col-md-2 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .search-input-group {
        flex-direction: column;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="container">
    <!-- Enhanced Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Database Tools</h1>
                <p class="admin-hero-subtitle">Analyze and manage your database structure</p>
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
    
    <!-- Tool Content -->
    <div class="admin-form-container">
        <h2 class="admin-form-title">Column Analyzer</h2>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="table_name" class="form-label">Select Table</label>
                <div class="search-input-group">
                    <select id="table_name" name="table_name" class="form-control" required>
                        <option value="">-- Select a table --</option>
                        <?php foreach ($allTables as $table): ?>
                            <option value="<?php echo htmlspecialchars($table); ?>" <?php echo (isset($_POST['table_name']) && $_POST['table_name'] === $table) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($table); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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
    </div>
</div>

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
    
    // Initialize select2 for enhanced dropdown if available
    if (typeof $.fn.select2 !== 'undefined') {
        $('#table_name').select2({
            placeholder: "Select a table",
            width: '100%'
        });
    }
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