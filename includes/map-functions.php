<?php
/**
 * Map Functions
 * Helper functions for map management
 */

/**
 * Get map by ID
 * 
 * @param mysqli $conn Database connection
 * @param int $mapId Map ID
 * @return array|false Map data or false if not found
 */
function getMapById($conn, $mapId) {
    $query = "SELECT * FROM mapids WHERE mapid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $mapId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $map = $result->fetch_assoc();
        
        // Set image URL
        $map['image_url'] = getMapImagePath($map['pngId']);
        
        return $map;
    }
    
    return false;
}

/**
 * Get maps with pagination
 * 
 * @param mysqli $conn Database connection
 * @param int $page Current page
 * @param int $perPage Items per page
 * @param string $searchTerm Optional search term
 * @return array Maps data with pagination info
 */
function getMaps($conn, $page = 1, $perPage = 20, $searchTerm = '') {
    $offset = ($page - 1) * $perPage;
    
    // Base query
    $query = "SELECT * FROM mapids";
    $countQuery = "SELECT COUNT(*) as total FROM mapids";
    
    // Add search condition if provided
    if (!empty($searchTerm)) {
        $searchCondition = " WHERE locationname LIKE ? OR desc_kr LIKE ?";
        $query .= $searchCondition;
        $countQuery .= $searchCondition;
        $searchParam = "%$searchTerm%";
    }
    
    // Add ordering and limit
    $query .= " ORDER BY mapid ASC LIMIT ?, ?";
    
    // Prepare and execute count query
    if (!empty($searchTerm)) {
        $countStmt = $conn->prepare($countQuery);
        $countStmt->bind_param("ss", $searchParam, $searchParam);
    } else {
        $countStmt = $conn->prepare($countQuery);
    }
    
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalCount = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalCount / $perPage);
    
    // Prepare and execute main query
    $stmt = $conn->prepare($query);
    
    if (!empty($searchTerm)) {
        $stmt->bind_param("ssii", $searchParam, $searchParam, $offset, $perPage);
    } else {
        $stmt->bind_param("ii", $offset, $perPage);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $maps = [];
    while ($row = $result->fetch_assoc()) {
        // Add image path to each map
        $row['image_url'] = getMapImagePath($row['pngId']);
        $maps[] = $row;
    }
    
    return [
        'maps' => $maps,
        'total' => $totalCount,
        'pages' => $totalPages,
        'current' => $page
    ];
}

/**
 * Create a new map
 * 
 * @param mysqli $conn Database connection
 * @param array $data Map data
 * @return int|false The new map ID or false on failure
 */
function createMap($conn, $data) {
    $query = "INSERT INTO mapids (
        mapid, locationname, desc_kr, underwater, beginZone, redKnightZone, ruunCastleZone, interWarZone,
        startX, endX, startY, endY, monster_amount, drop_rate, markable, teleportable, escapable, 
        resurrection, painwand, penalty, take_pets, recall_pets, usable_item, usable_skill, dungeon,
        decreaseHp, dominationTeleport, geradBuffZone, growBuffZone, interKind, script, pngId
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "issiiiiiiiiiddiiiiiiiiiiiiiisi",
        $data['mapid'], $data['locationname'], $data['desc_kr'], $data['underwater'], $data['beginZone'], 
        $data['redKnightZone'], $data['ruunCastleZone'], $data['interWarZone'], $data['startX'], $data['endX'], 
        $data['startY'], $data['endY'], $data['monster_amount'], $data['drop_rate'], $data['markable'], 
        $data['teleportable'], $data['escapable'], $data['resurrection'], $data['painwand'], 
        $data['penalty'], $data['take_pets'], $data['recall_pets'], $data['usable_item'], 
        $data['usable_skill'], $data['dungeon'], $data['decreaseHp'], $data['dominationTeleport'], 
        $data['geradBuffZone'], $data['growBuffZone'], $data['interKind'], $data['script'], $data['pngId']
    );
    
    if ($stmt->execute()) {
        return $data['mapid'];
    }
    
    return false;
}

/**
 * Update an existing map
 * 
 * @param mysqli $conn Database connection
 * @param int $mapId Map ID
 * @param array $data Map data
 * @return bool Success or failure
 */
function updateMap($conn, $mapId, $data) {
    $query = "UPDATE mapids SET 
        locationname = ?, desc_kr = ?, underwater = ?, beginZone = ?, redKnightZone = ?, 
        ruunCastleZone = ?, interWarZone = ?, startX = ?, endX = ?, startY = ?, endY = ?, 
        monster_amount = ?, drop_rate = ?, markable = ?, teleportable = ?, escapable = ?, 
        resurrection = ?, painwand = ?, penalty = ?, take_pets = ?, recall_pets = ?, 
        usable_item = ?, usable_skill = ?, dungeon = ?, decreaseHp = ?, dominationTeleport = ?, 
        geradBuffZone = ?, growBuffZone = ?, interKind = ?, script = ?, pngId = ?
    WHERE mapid = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "ssiiiiiiiiddiiiiiiiiiiiiiiiisi", 
        $data['locationname'], $data['desc_kr'], $data['underwater'], $data['beginZone'], 
        $data['redKnightZone'], $data['ruunCastleZone'], $data['interWarZone'], $data['startX'], 
        $data['endX'], $data['startY'], $data['endY'], $data['monster_amount'], $data['drop_rate'], 
        $data['markable'], $data['teleportable'], $data['escapable'], $data['resurrection'], 
        $data['painwand'], $data['penalty'], $data['take_pets'], $data['recall_pets'], 
        $data['usable_item'], $data['usable_skill'], $data['dungeon'], $data['decreaseHp'], 
        $data['dominationTeleport'], $data['geradBuffZone'], $data['growBuffZone'], 
        $data['interKind'], $data['script'], $data['pngId'], $mapId
    );
    
    return $stmt->execute();
}

/**
 * Delete a map
 * 
 * @param mysqli $conn Database connection
 * @param int $mapId Map ID
 * @return bool Success or failure
 */
function deleteMap($conn, $mapId) {
    $query = "DELETE FROM mapids WHERE mapid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $mapId);
    
    return $stmt->execute();
}

/**
 * Get map type name
 * 
 * @param int $underwater Underwater flag
 * @param int $beginZone Beginner zone flag
 * @param int $redKnightZone Red Knight zone flag
 * @return string Map type name
 */
function getMapTypeName($underwater, $beginZone, $redKnightZone) {
    if ($underwater) {
        return "Underwater";
    } elseif ($beginZone) {
        return "Beginner Zone";
    } elseif ($redKnightZone) {
        return "Red Knight Zone";
    } else {
        return "Normal";
    }
}

/**
 * Get map image path
 * 
 * @param int $pngId PNG ID
 * @return string Image path
 */
function getMapImagePath($pngId) {
    $baseUrl = '/l1j-database/';
    
    if (empty($pngId) || $pngId <= 0) {
        // Use the correct placeholder path in the maps folder
        return $baseUrl . 'assets/img/icons/maps/map-placeholder.png';
    }
    
    return $baseUrl . "assets/img/icons/maps/{$pngId}.jpeg";
}

// Removed duplicated sanitizeInput() function - use the one from config.php instead
?>