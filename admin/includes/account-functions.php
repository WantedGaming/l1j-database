<?php
/**
 * Account Functions
 * Contains functions for managing accounts and characters
 */

/**
 * Get accounts with pagination
 * 
 * @param mysqli $conn Database connection
 * @param int $page Current page number
 * @param int $perPage Number of accounts per page
 * @param string $searchTerm Optional search term
 * @return array Array containing accounts, total pages, and total count
 */
function getAccounts($conn, $page = 1, $perPage = 10, $searchTerm = '') {
    // Calculate offset for pagination
    $offset = ($page - 1) * $perPage;
    
    // Build search query if search term provided
    $searchCondition = '';
    if (!empty($searchTerm)) {
        $searchTerm = $conn->real_escape_string($searchTerm);
        $searchCondition = "WHERE login LIKE '%$searchTerm%' OR ip LIKE '%$searchTerm%'";
    }
    
    // Get total count of accounts matching search
    $countQuery = "SELECT COUNT(*) as total FROM accounts $searchCondition";
    $countResult = $conn->query($countQuery);
    $totalAccounts = $countResult->fetch_assoc()['total'];
    
    // Calculate total pages
    $totalPages = ceil($totalAccounts / $perPage);
    
    // Get accounts with pagination
    $query = "SELECT * FROM accounts $searchCondition ORDER BY lastactive DESC LIMIT $offset, $perPage";
    $result = $conn->query($query);
    
    $accounts = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $accounts[] = $row;
        }
    }
    
    return [
        'accounts' => $accounts,
        'pages' => $totalPages,
        'total' => $totalAccounts
    ];
}

/**
 * Get characters for a specific account
 * 
 * @param mysqli $conn Database connection
 * @param string $accountName Account login name
 * @return array Array of characters
 */
function getAccountCharacters($conn, $accountName) {
    $accountName = $conn->real_escape_string($accountName);
    
    // Modified query to remove clan_data join that was causing the error
    $query = "SELECT * FROM characters 
              WHERE account_name = '$accountName' 
              ORDER BY level DESC, char_name ASC";
    
    $result = $conn->query($query);
    
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
 * @return array|false Character details or false if not found
 */
function getCharacterById($conn, $characterId) {
    $characterId = (int)$characterId;
    
    // Modified query to remove clan_data join that was causing the error
    $query = "SELECT * FROM characters 
              WHERE objid = $characterId";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return false;
}

/**
 * Get account status based on last activity
 * 
 * @param array $account Account data
 * @return array Status with label and class
 */
function getAccountStatus($account) {
    // Check if account is banned
    if (isset($account['banned']) && $account['banned'] == 1) {
        return [
            'label' => 'Banned',
            'class' => 'banned'
        ];
    }
    
    // Check last activity
    $lastActive = strtotime($account['lastactive']);
    $now = time();
    $diffDays = floor(($now - $lastActive) / (60 * 60 * 24));
    
    if ($diffDays <= 7) {
        return [
            'label' => 'Active',
            'class' => 'active'
        ];
    } elseif ($diffDays <= 30) {
        return [
            'label' => 'Inactive',
            'class' => 'inactive'
        ];
    } else {
        return [
            'label' => 'Dormant',
            'class' => 'dormant'
        ];
    }
}

/**
 * Get access level name based on level ID
 * 
 * @param int $accessLevel Access level ID
 * @return string Access level name
 */
function getAccessLevelName($accessLevel) {
    switch ((int)$accessLevel) {
        case 200:
            return 'Admin';
        case 100:
            return 'GM';
        case 50:
            return 'Support';
        case 0:
        default:
            return 'Player';
    }
}

/**
 * Get class image path based on class ID and gender
 * 
 * @param int $classId Class ID
 * @param int $gender Gender (0 = male, 1 = female)
 * @return string Path to class image
 */
function getClassImagePath($classId, $gender) {
    $classId = (int)$classId;
    $gender = (int)$gender;
    
    $baseUrl = '/assets/img/icons/class_icon/';
    
    // Default image if class not found
    $imagePath = $baseUrl . 'unknown.png';
    
    // Class mapping with unique IDs - aligned with getClassName function
    switch ($classId) {
        case 0: // Royal
            $imagePath = $baseUrl . ($gender == 0 ? '10591.png' : '10592.png');
            break;
        case 48: // Knight
            $imagePath = $baseUrl . ($gender == 0 ? '10593.png' : '10594.png');
            break;
        case 37: // Elf
            $imagePath = $baseUrl . ($gender == 0 ? '10599.png' : '10600.png');
            break;
        case 2079: // Wizard
            $imagePath = $baseUrl . ($gender == 0 ? '10597.png' : '10598.png');
            break;
        case 2769: // Dark Elf
            $imagePath = $baseUrl . ($gender == 0 ? '10595.png' : '10596.png');
            break;
        case 6661: // Dragon Knight
            $imagePath = $baseUrl . ($gender == 0 ? '10601.png' : '10602.png');
            break;
        case 6650: // Illusionist (corrected from 18499)
            $imagePath = $baseUrl . ($gender == 0 ? '10607.png' : '10608.png');
            break;
        case 18499: // Fencer (corrected from 19278)
            $imagePath = $baseUrl . ($gender == 0 ? '10589.png' : '10590.png');
            break;
        case 19299: // Lancer (corrected from 19988)
            $imagePath = $baseUrl . ($gender == 0 ? '10603.png' : '10604.png');
            break;
        case 20577: // Warrior (corrected from 20344)
            $imagePath = $baseUrl . ($gender == 0 ? '10605.png' : '10606.png');
            break;    
    }
    
    return $imagePath;
}

/**
 * Get class name based on class ID and gender
 * 
 * @param int $classId Class ID
 * @param int $gender Gender (0 = male, 1 = female)
 * @return string Class name
 */
function getClassName($classId, $gender) {
    $classId = (int)$classId;
    
    switch ($classId) {
        case 0:
            return 'Royal';
        case 37:
            return 'Elf';
        case 48:
            return 'Knight';
        case 2079:
            return 'Wizard';
        case 2769:
            return 'Dark Elf';
        case 6661:
            return 'Dragon Knight';
        case 6650:
            return 'Illusionist';
		case 18499:
            return 'Fencer';
		case 19299:
            return 'Lancer';
		case 20577:
            return 'Warrior';	
        default:
            return 'Unknown';
    }
}

/**
 * Format date/time to readable format
 * 
 * @param string $datetime Date/time string
 * @return string Formatted date/time
 */
function formatDateTime($datetime) {
    if (empty($datetime) || $datetime == '0000-00-00 00:00:00') {
        return 'Never';
    }
    
    $timestamp = strtotime($datetime);
    return date('Y-m-d H:i:s', $timestamp);
}

/**
 * Format time in USA standard format (12-hour with AM/PM)
 * 
 * @param string $datetime Date/time string
 * @return string Formatted date/time in USA format
 */
function formatTimeUSA($datetime) {
    if (empty($datetime) || $datetime == '0000-00-00 00:00:00') {
        return 'Never';
    }
    
    $timestamp = strtotime($datetime);
    return date('m/d/Y g:i A', $timestamp); // USA format: MM/DD/YYYY h:MM AM/PM
}

?>