<?php
/**
 * Utility Functions
 * This file contains utility functions used throughout the website
 */

/**
 * Get a list of items from any table with pagination
 * 
 * @param PDO $pdo Database connection
 * @param string $table Table name
 * @param array $columns Columns to select
 * @param string $where WHERE clause (optional)
 * @param array $params Parameters for prepared statement (optional)
 * @param int $page Current page number
 * @param int $itemsPerPage Number of items per page
 * @return array Array containing items and pagination info
 */
function getItemsWithPagination($pdo, $table, $columns = ['*'], $where = '', $params = [], $page = 1, $itemsPerPage = 20) {
    // Calculate offset
    $offset = ($page - 1) * $itemsPerPage;
    
    // Prepare column selection
    $columnsStr = implode(', ', $columns);
    
    // Build the query
    $query = "SELECT $columnsStr FROM $table";
    
    if (!empty($where)) {
        $query .= " WHERE $where";
    }
    
    // Add limit and offset
    $query .= " LIMIT :limit OFFSET :offset";
    
    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    // Bind any additional parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $items = $stmt->fetchAll();
    
    // Get total count for pagination
    $countQuery = "SELECT COUNT(*) as total FROM $table";
    if (!empty($where)) {
        $countQuery .= " WHERE $where";
    }
    
    $countStmt = $pdo->prepare($countQuery);
    
    // Bind any parameters for the count query
    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    
    $countStmt->execute();
    $totalCount = $countStmt->fetch()['total'];
    
    // Calculate total pages
    $totalPages = ceil($totalCount / $itemsPerPage);
    
    return [
        'items' => $items,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'items_per_page' => $itemsPerPage,
            'total_items' => $totalCount
        ]
    ];
}

/**
 * Get a single item from any table
 * 
 * @param PDO $pdo Database connection
 * @param string $table Table name
 * @param string $idColumn Column name for the ID
 * @param mixed $id ID value
 * @param array $columns Columns to select
 * @return array|bool Item data or false if not found
 */
function getItemById($pdo, $table, $idColumn, $id, $columns = ['*']) {
    // Prepare column selection
    $columnsStr = implode(', ', $columns);
    
    // Build the query
    $query = "SELECT $columnsStr FROM $table WHERE $idColumn = :id LIMIT 1";
    
    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    
    return $stmt->fetch();
}

/**
 * Get monster drops by monster ID
 * 
 * @param PDO $pdo Database connection
 * @param int $monsterId Monster ID
 * @return array Drops for the monster
 */
function getMonsterDrops($pdo, $monsterId) {
    $query = "SELECT d.*, 
                     COALESCE(w.desc_en, a.desc_en, e.desc_en) as item_name,
                     COALESCE(w.iconId, a.iconId, e.iconId) as iconId,
                     CASE 
                         WHEN w.item_id IS NOT NULL THEN 'weapon' 
                         WHEN a.item_id IS NOT NULL THEN 'armor' 
                         ELSE 'etcitem' 
                     END as item_type
              FROM droplist d
              LEFT JOIN weapon w ON d.itemId = w.item_id
              LEFT JOIN armor a ON d.itemId = a.item_id
              LEFT JOIN etcitem e ON d.itemId = e.item_id
              WHERE d.mobId = :monsterId";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':monsterId', $monsterId);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Get monster spawns by monster ID
 * 
 * @param PDO $pdo Database connection
 * @param int $monsterId Monster ID
 * @return array Spawn locations for the monster
 */
function getMonsterSpawns($pdo, $monsterId) {
    // Check regular spawns
    $query = "SELECT s.*, m.desc_en as map_name 
              FROM spawnlist s
              LEFT JOIN mapids m ON s.mapid = m.mapid
              WHERE s.npc_templateid = :monsterId";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':monsterId', $monsterId);
    $stmt->execute();
    $regularSpawns = $stmt->fetchAll();
    
    // Check boss spawns
    $query = "SELECT s.*, m.desc_en as map_name 
              FROM spawnlist_boss s
              LEFT JOIN mapids m ON s.mapid = m.mapid
              WHERE s.npcid = :monsterId";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':monsterId', $monsterId);
    $stmt->execute();
    $bossSpawns = $stmt->fetchAll();
    
    // Combine all spawns
    return [
        'regular' => $regularSpawns,
        'boss' => $bossSpawns
    ];
}

/**
 * Search across multiple tables
 * 
 * @param PDO $pdo Database connection
 * @param string $searchTerm Search term
 * @param array $tables Tables to search in
 * @return array Search results
 */
function searchDatabase($pdo, $searchTerm, $tables = ['weapon', 'armor', 'etcitem', 'npc']) {
    $results = [];
    $searchTerm = '%' . $searchTerm . '%';
    
    foreach ($tables as $table) {
        $idColumn = ($table === 'npc') ? 'npcid' : 'item_id';
        
        $query = "SELECT *, '$table' as source FROM $table WHERE desc_en LIKE :searchTerm LIMIT 20";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':searchTerm', $searchTerm);
        $stmt->execute();
        
        $tableResults = $stmt->fetchAll();
        $results = array_merge($results, $tableResults);
    }
    
    return $results;
}

/**
 * Get image path based on item type and ID
 * 
 * @param string $type Item type (weapon, armor, monster, etc.)
 * @param int $id Icon or sprite ID
 * @return string Path to the image
 */
function getImagePath($type, $id) {
    switch ($type) {
        case 'weapon':
        case 'armor':
        case 'item':
            return "assets/img/icons/icons/{$id}.png";
        
        case 'map':
            return "assets/img/icons/maps/{$id}.jpeg";
            
        case 'doll':
            return "assets/img/icons/dolls/{$id}.png";
            
        case 'npc':
            return "assets/img/icons/npcs/{$id}.png";
            
        case 'skill':
            return "assets/img/icons/skills/{$id}.png";
            
        case 'polymorph':
            return "assets/img/icons/poly/{$id}.png";
            
        case 'monster':
            return "assets/img/icons/monsters/ms{$id}.png";
            
        default:
            return "assets/img/placeholders/default.png";
    }
}

/**
 * Format number with commas for thousands
 * 
 * @param int $number Number to format
 * @return string Formatted number
 */
function formatNumber($number) {
    return number_format($number);
}

/**
 * Truncate text to a certain length
 * 
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $ending String to append if truncated
 * @return string Truncated text
 */
function truncateText($text, $length = 100, $ending = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $ending;
}
