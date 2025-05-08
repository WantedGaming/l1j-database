<?php
/**
 * Common functions for the L1J Database website
 */
require_once __DIR__ . '/db_connect.php';

/**
 * Get all categories with their details
 * 
 * @return array Array of category information
 */
function getCategories() {
    return [
        [
            'id' => 'weapons',
            'name' => 'Weapons',
            'icon' => 'assets/img/placeholders/weapons.png',
            'description' => 'Browse all weapons in the game',
            'tables' => ['weapon.sql', 'weapon_skill.sql', 'weapon_skill_model.sql', 'weapons_skill_spell_def.sql']
        ],
        [
            'id' => 'armor',
            'name' => 'Armor',
            'icon' => 'assets/img/placeholders/armor.png',
            'description' => 'Browse all armor in the game',
            'tables' => ['armor.sql', 'armor_set.sql']
        ],
        [
            'id' => 'items',
            'name' => 'Items',
            'icon' => 'assets/img/placeholders/items.png',
            'description' => 'Browse all items in the game',
            'tables' => ['etcitem.sql']
        ],
        [
            'id' => 'monsters',
            'name' => 'Monsters',
            'icon' => 'assets/img/placeholders/monsters.png',
            'description' => 'Browse all monsters in the game',
            'tables' => ['npc.sql', 'mobskill.sql', 'mobgroup.sql']
        ],
        [
            'id' => 'maps',
            'name' => 'Maps',
            'icon' => 'assets/img/placeholders/maps.png',
            'description' => 'Browse all maps in the game',
            'tables' => ['mapids.sql']
        ],
        [
            'id' => 'dolls',
            'name' => 'Dolls',
            'icon' => 'assets/img/placeholders/dolls.png',
            'description' => 'Browse all magic dolls in the game',
            'tables' => ['npc.sql', 'magicdoll_info.sql', 'magicdoll_potential.sql']
        ],
        [
            'id' => 'npcs',
            'name' => 'NPCs',
            'icon' => 'assets/img/placeholders/npc.png',
            'description' => 'Browse all NPCs in the game',
            'tables' => ['npc.sql']
        ],
        [
            'id' => 'skills',
            'name' => 'Skills',
            'icon' => 'assets/img/placeholders/skill.png',
            'description' => 'Browse all skills in the game',
            'tables' => ['skills.sql', 'skills_hanlder.sql', 'skills_info.sql', 'skills_passive.sql']
        ],
        [
            'id' => 'polymorph',
            'name' => 'Polymorph',
            'icon' => 'assets/img/placeholders/poly.png',
            'description' => 'Browse all polymorph options in the game',
            'tables' => ['polymorphs.sql']
        ]
    ];
}

/**
 * Get database statistics for dashboard display
 * 
 * @return array Array of statistics
 */
function getDatabaseStats() {
    $conn = getDbConnection();
    $stats = [];
    
    // Get counts from each main table
    $stats['weapons'] = getTableCount($conn, 'weapon');
    $stats['armor'] = getTableCount($conn, 'armor');
    $stats['items'] = getTableCount($conn, 'etcitem');
    
    // For monsters, filter by impl types
    $monsterQuery = "SELECT COUNT(*) as count FROM npc WHERE impl IN ('L1Monster', 'L1Doppelganger')";
    $result = $conn->query($monsterQuery);
    $stats['monsters'] = $result ? $result->fetch_assoc()['count'] : 0;
    
    $stats['maps'] = getTableCount($conn, 'mapids');
    
    // For dolls, count from magicdoll_info
    $stats['dolls'] = getTableCount($conn, 'magicdoll_info');
    
    // For NPCs, filter by impl types
    $npcQuery = "SELECT COUNT(*) as count FROM npc WHERE impl IN ('L1Blackknight', 'L1Dwarf', 'L1Guard', 'L1HouseKeeper', 'L1Merchant', 'L1Npc', 'L1Teleporter')";
    $result = $conn->query($npcQuery);
    $stats['npcs'] = $result ? $result->fetch_assoc()['count'] : 0;
    
    $stats['skills'] = getTableCount($conn, 'skills');
    $stats['polymorph'] = getTableCount($conn, 'polymorphs');
    
    return $stats;
}

/**
 * Get count of records in a table
 * 
 * @param mysqli $conn Database connection
 * @param string $table Table name
 * @return int Record count
 */
function getTableCount($conn, $table) {
    $query = "SELECT COUNT(*) as count FROM $table";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc()['count'];
    }
    
    return 0;
}

/**
 * Search the database for items
 * 
 * @param string $searchTerm The search term
 * @param string $category Optional category to limit search
 * @return array Search results
 */
function searchDatabase($searchTerm, $category = null) {
    $conn = getDbConnection();
    $results = [];
    $searchTerm = $conn->real_escape_string($searchTerm);
    
    // Define tables to search based on category
    $searchTables = [];
    
    if ($category) {
        switch ($category) {
            case 'weapons':
                $searchTables[] = ['table' => 'weapon', 'id' => 'item_id', 'name' => 'desc_en'];
                break;
            case 'armor':
                $searchTables[] = ['table' => 'armor', 'id' => 'item_id', 'name' => 'desc_en'];
                break;
            case 'items':
                $searchTables[] = ['table' => 'etcitem', 'id' => 'item_id', 'name' => 'desc_en'];
                break;
            case 'monsters':
                $searchTables[] = ['table' => 'npc', 'id' => 'npcid', 'name' => 'desc_en', 
                                  'where' => "impl IN ('L1Monster', 'L1Doppelganger')"];
                break;
            case 'maps':
                $searchTables[] = ['table' => 'mapids', 'id' => 'mapid', 'name' => 'locationname'];
                break;
            case 'dolls':
                $searchTables[] = ['table' => 'magicdoll_info', 'id' => 'itemId', 'name' => 'name'];
                break;
            case 'npcs':
                $searchTables[] = ['table' => 'npc', 'id' => 'npcid', 'name' => 'desc_en', 
                                  'where' => "impl IN ('L1Blackknight', 'L1Dwarf', 'L1Guard', 'L1HouseKeeper', 'L1Merchant', 'L1Npc', 'L1Teleporter')"];
                break;
            case 'skills':
                $searchTables[] = ['table' => 'skills', 'id' => 'skill_id', 'name' => 'name'];
                $searchTables[] = ['table' => 'skills_passive', 'id' => 'passive_id', 'name' => 'name'];
                break;
            case 'polymorph':
                $searchTables[] = ['table' => 'polymorphs', 'id' => 'id', 'name' => 'name'];
                break;
        }
    } else {
        // Search all main tables
        $searchTables = [
            ['table' => 'weapon', 'id' => 'item_id', 'name' => 'desc_en'],
            ['table' => 'armor', 'id' => 'item_id', 'name' => 'desc_en'],
            ['table' => 'etcitem', 'id' => 'item_id', 'name' => 'desc_en'],
            ['table' => 'npc', 'id' => 'npcid', 'name' => 'desc_en'],
            ['table' => 'mapids', 'id' => 'mapid', 'name' => 'locationname'],
            ['table' => 'skills', 'id' => 'skill_id', 'name' => 'name'],
            ['table' => 'polymorphs', 'id' => 'id', 'name' => 'name']
        ];
    }
    
    // Perform search
    foreach ($searchTables as $tableInfo) {
        $table = $tableInfo['table'];
        $idField = $tableInfo['id'];
        $nameField = $tableInfo['name'];
        
        $whereClause = isset($tableInfo['where']) ? $tableInfo['where'] . " AND " : "";
        $whereClause .= "($nameField LIKE '%$searchTerm%' OR $idField LIKE '%$searchTerm%'";
        
        // Also search in Korean name if available
        if ($table == 'weapon' || $table == 'armor' || $table == 'etcitem' || $table == 'npc' || $table == 'skills') {
            $whereClause .= " OR desc_kr LIKE '%$searchTerm%'";
        }
        
        $whereClause .= ")";
        
        $query = "SELECT * FROM $table WHERE $whereClause LIMIT 100";
        $result = $conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['table'] = $table;
                $row['category'] = getCategoryFromTable($table);
                $results[] = $row;
            }
        }
    }
    
    return $results;
}

/**
 * Get category name from table name
 * 
 * @param string $table Table name
 * @return string Category name
 */
function getCategoryFromTable($table) {
    switch ($table) {
        case 'weapon':
            return 'weapons';
        case 'armor':
            return 'armor';
        case 'etcitem':
            return 'items';
        case 'npc':
            return 'monsters'; // Note: This is simplified, actual implementation would check impl
        case 'mapids':
            return 'maps';
        case 'magicdoll_info':
            return 'dolls';
        case 'skills':
        case 'skills_passive':
            return 'skills';
        case 'polymorphs':
            return 'polymorph';
        default:
            return 'unknown';
    }
}

/**
 * Authentication related functions
 */

/**
 * Authenticate a user based on login credentials
 * 
 * @param string $username Username (login field in accounts table)
 * @param string $password Password
 * @return array|false User data if authenticated, false otherwise
 */
function authenticateUser($username, $password) {
    $conn = getDbConnection();
    
    // Sanitize inputs
    $username = $conn->real_escape_string($username);
    
    // Query the accounts table
    $query = "SELECT * FROM accounts WHERE login = '$username' LIMIT 1";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Password verification
        // Note: This is simplified and assumes passwords are stored in plaintext
        // In a real application, you should use password_hash and password_verify
        if ($user['password'] === $password) {
            return $user;
        }
    }
    
    return false;
}

/**
 * Check if the current user is logged in as admin
 * 
 * @return bool True if user is logged in as admin
 */
function isAdminLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Check if user has admin access level
 * 
 * @param array $user User data from database
 * @return bool True if user has admin access level
 */
function hasAdminAccess($user) {
    // Admin users should have access_level of 1 or higher
    return isset($user['access_level']) && $user['access_level'] >= 1;
}

/**
 * Create an admin session for the user
 * 
 * @param array $user User data from database
 * @return void
 */
function createAdminSession($user) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user_id'] = $user['login'];
    $_SESSION['admin_access_level'] = $user['access_level'];
    $_SESSION['admin_last_activity'] = time();
}

/**
 * End the admin session
 * 
 * @return void
 */
function endAdminSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
}

/**
 * Require admin authentication or redirect to login
 * 
 * @return void
 */
function requireAdminAuth() {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}