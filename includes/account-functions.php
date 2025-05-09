<?php
/**
 * Account & Character Management Functions
 * Contains functions for working with accounts and characters data
 */

/**
 * Get list of accounts with pagination
 * 
 * @param mysqli $conn Database connection
 * @param int $page Current page number
 * @param int $pageSize Number of items per page
 * @param string $searchTerm Optional search term
 * @return array Array containing accounts data and pagination info
 */
function getAccounts($conn, $page = 1, $pageSize = 12, $searchTerm = '') {
    // Ensure parameters are valid
    $page = max(1, (int)$page);
    $pageSize = max(1, (int)$pageSize);
    
    // Calculate offset for pagination
    $offset = ($page - 1) * $pageSize;
    
    // Base query
    $sql = "SELECT * FROM accounts";
    
    // Add search condition if search term is provided
    if (!empty($searchTerm)) {
        $searchTerm = $conn->real_escape_string($searchTerm);
        $sql .= " WHERE login LIKE '%$searchTerm%' OR ip LIKE '%$searchTerm%'";
    }
    
    // Add order and pagination
    $sql .= " ORDER BY lastactive DESC LIMIT $offset, $pageSize";
    
    // Execute query with error handling
    $result = executeQuery($sql, $conn);
    
    if (!$result) {
        error_log("Failed to fetch accounts: " . $conn->error);
        return [
            'accounts' => [],
            'total' => 0,
            'pages' => 0,
            'current_page' => $page,
            'error' => "Database error: " . $conn->error
        ];
    }
    
    // Get total count for pagination
    $countSql = "SELECT COUNT(*) as total FROM accounts";
    if (!empty($searchTerm)) {
        $countSql .= " WHERE login LIKE '%$searchTerm%' OR ip LIKE '%$searchTerm%'";
    }
    
    $countResult = executeQuery($countSql, $conn);
    
    if (!$countResult) {
        error_log("Failed to get accounts count: " . $conn->error);
        $totalCount = 0;
    } else {
        $totalCount = $countResult->fetch_assoc()['total'];
    }
    
    $totalPages = ceil($totalCount / $pageSize);
    
    // Build result array
    $accounts = [];
    while ($row = $result->fetch_assoc()) {
        // Ensure required fields are present
        if (!isset($row['login'])) {
            error_log("Incomplete account data found: " . json_encode($row));
            continue;
        }
        
        // Add account to results
        $accounts[] = $row;
    }
    
    return [
        'accounts' => $accounts,
        'total' => $totalCount,
        'pages' => $totalPages,
        'current_page' => $page
    ];
}

/**
 * Get characters for a specific account
 * 
 * @param mysqli $conn Database connection
 * @param string $accountName Account name
 * @return array Array of characters for this account
 */
function getAccountCharacters($conn, $accountName) {
    $accountName = $conn->real_escape_string($accountName);
    
    $sql = "SELECT * FROM characters WHERE account_name = '$accountName' ORDER BY level DESC";
    $result = executeQuery($sql, $conn);
    
    $characters = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $characters[] = $row;
        }
    }
    
    return $characters;
}

/**
 * Get character details by ID
 * 
 * @param mysqli $conn Database connection
 * @param int $characterId Character ID
 * @return array|null Character details or null if not found
 */
function getCharacterById($conn, $characterId) {
    $characterId = (int)$characterId;
    
    $sql = "SELECT * FROM characters WHERE objid = $characterId";
    $result = executeQuery($sql, $conn);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

/**
 * Get class name from class ID
 * 
 * @param int $classId Class ID
 * @param int $gender Gender (0 = male, 1 = female)
 * @return string Class name
 */
function getClassName($classId, $gender = 0) {
    $classMap = [
        0 => 'Royal',
        37 => 'Elf',
        48 => 'Knight',
        2079 => 'Wizard',
        2796 => 'DarkElf',
        6650 => 'Illusionist',
        6661 => 'DragonKnight',
        18499 => 'Fencer',
        19299 => 'Lancer',
        20577 => 'Warrior'
    ];
    
    $genderPrefix = ($gender == 0) ? '[M] ' : '[F] ';
    
    if (isset($classMap[$classId])) {
        return $genderPrefix . $classMap[$classId];
    }
    
    return $genderPrefix . 'Unknown (' . $classId . ')';
}

/**
 * Get class image path
 * 
 * @param int $classId Class ID
 * @param int $gender Gender (0 = male, 1 = female)
 * @return string Path to class image
 */
function getClassImagePath($classId, $gender = 0) {
    $baseUrl = '/assets/img/placeholders/class/';
    $defaultImage = $baseUrl . 'default.png';
    
    // Try to find the exact class image
    $classImage = $baseUrl . $classId . '_' . $gender . '.png';
    
    // If classId is a valid class, return the corresponding image path
    $validClasses = [0, 37, 48, 2079, 2796, 6650, 6661, 18499, 19299, 20577];
    
    if (in_array($classId, $validClasses)) {
        return $classImage;
    }
    
    return $defaultImage;
}

/**
 * Get account status
 * 
 * @param array $account Account data
 * @return array Status with label and class
 */
function getAccountStatus($account) {
    if ($account['banned'] > 0) {
        return [
            'label' => 'Banned',
            'class' => 'status-banned'
        ];
    }
    
    // Check if account was active within the last 7 days
    $lastActive = strtotime($account['lastactive']);
    $sevenDaysAgo = strtotime('-7 days');
    
    if ($lastActive > $sevenDaysAgo) {
        return [
            'label' => 'Active',
            'class' => 'status-active'
        ];
    }
    
    // Check if account was active within the last 30 days
    $thirtyDaysAgo = strtotime('-30 days');
    if ($lastActive > $thirtyDaysAgo) {
        return [
            'label' => 'Inactive',
            'class' => 'status-inactive'
        ];
    }
    
    return [
        'label' => 'Dormant',
        'class' => 'status-dormant'
    ];
}

/**
 * Format date/time to be more readable
 * 
 * @param string $dateTime Date/time string
 * @return string Formatted date/time
 */
function formatDateTime($dateTime) {
    if (empty($dateTime)) {
        return 'Never';
    }
    
    $timestamp = strtotime($dateTime);
    $now = time();
    $diff = $now - $timestamp;
    
    if ($diff < 86400) { // Less than 24 hours
        return date('H:i:s', $timestamp) . ' today';
    } else if ($diff < 172800) { // Less than 48 hours
        return date('H:i:s', $timestamp) . ' yesterday';
    } else if ($diff < 604800) { // Less than 7 days
        return date('l H:i:s', $timestamp); // Day of week
    } else {
        return date('M j, Y H:i:s', $timestamp); // Full date
    }
}

/**
 * Convert access level to role name
 * 
 * @param int $accessLevel Access level
 * @return string Role name
 */
function getAccessLevelName($accessLevel) {
    switch ($accessLevel) {
        case 0:
            return 'Player';
        case 1:
            return 'Monitor';
        case 2:
            return 'Game Master';
        case 3:
            return 'Admin';
        case 4:
            return 'Super Admin';
        default:
            return 'Player (' . $accessLevel . ')';
    }
}
