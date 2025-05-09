<?php
/**
 * Map-specific utility functions
 * Contains functions for working with map data
 */

/**
 * Get list of maps with pagination
 * 
 * @param mysqli $conn Database connection
 * @param int $page Current page number
 * @param int $pageSize Number of items per page
 * @param string $searchTerm Optional search term
 * @return array Array containing maps data and pagination info
 */
function getMaps($conn, $page = 1, $pageSize = 20, $searchTerm = '') {
    // Ensure parameters are valid
    $page = max(1, (int)$page);
    $pageSize = max(1, (int)$pageSize);
    
    // Calculate offset for pagination
    $offset = ($page - 1) * $pageSize;
    
    // Base query with pngId included
    $sql = "SELECT mapid, locationname, desc_kr, underwater, beginZone, redKnightZone, 
            startX, endX, startY, endY, pngId 
            FROM mapids";
    
    // Add search condition if search term is provided
    if (!empty($searchTerm)) {
        $searchTerm = $conn->real_escape_string($searchTerm);
        $sql .= " WHERE locationname LIKE '%$searchTerm%' OR desc_kr LIKE '%$searchTerm%'";
    }
    
    // Add order and pagination
    $sql .= " ORDER BY mapid LIMIT $offset, $pageSize";
    
    // Execute query with error handling
    $result = executeQuery($sql, $conn);
    
    if (!$result) {
        error_log("Failed to fetch maps: " . $conn->error);
        return [
            'maps' => [],
            'total' => 0,
            'pages' => 0,
            'current_page' => $page,
            'error' => "Database error: " . $conn->error
        ];
    }
    
    // Get total count for pagination
    $countSql = "SELECT COUNT(*) as total FROM mapids";
    if (!empty($searchTerm)) {
        $countSql .= " WHERE locationname LIKE '%$searchTerm%' OR desc_kr LIKE '%$searchTerm%'";
    }
    
    $countResult = executeQuery($countSql, $conn);
    
    if (!$countResult) {
        error_log("Failed to get map count: " . $conn->error);
        $totalCount = 0;
    } else {
        $totalCount = $countResult->fetch_assoc()['total'];
    }
    
    $totalPages = ceil($totalCount / $pageSize);
    
    // Build result array
    $maps = [];
    while ($row = $result->fetch_assoc()) {
        // Ensure all required fields are present
        if (!isset($row['mapid']) || !isset($row['locationname'])) {
            error_log("Incomplete map data found: " . json_encode($row));
            continue;
        }
        
        // Add map to results
        $maps[] = $row;
    }
    
    return [
        'maps' => $maps,
        'total' => $totalCount,
        'pages' => $totalPages,
        'current_page' => $page
    ];
}

/**
 * Get the image path for a map based on its pngId
 * 
 * @param int $pngId The PNG ID associated with the map
 * @return string URL to the map image or placeholder if not found
 */
function getMapImagePath($pngId) {
    // Base paths
    $mapImagesBaseDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/icons/maps/';
    $mapImagesBaseUrl = '/assets/img/icons/maps/';
    $placeholderImageUrl = '/assets/img/icons/placeholders/map-placeholder.png';
    
    // If no pngId is provided, return placeholder
    if (!$pngId) {
        return $placeholderImageUrl;
    }
    
    // Check for map image in various formats (case insensitive)
    $formats = ['jpg', 'jpeg', 'png'];
    foreach ($formats as $format) {
        // Check for exact match with pngId
        $exactPath = $mapImagesBaseDir . $pngId . '.' . $format;
        if (file_exists($exactPath)) {
            return $mapImagesBaseUrl . $pngId . '.' . $format;
        }
        
        // Check in subfolder with pngId name
        $subfolderPath = $mapImagesBaseDir . $pngId . '/' . $pngId . '.' . $format;
        if (file_exists($subfolderPath)) {
            return $mapImagesBaseUrl . $pngId . '/' . $pngId . '.' . $format;
        }
    }
    
    // No image found, return placeholder
    return $placeholderImageUrl;
}

/**
 * Get map details by ID
 * 
 * @param mysqli $conn Database connection
 * @param int $mapId Map ID
 * @return array|null Map details or null if not found
 */
function getMapById($conn, $mapId) {
    $mapId = (int)$mapId;
    $sql = "SELECT * FROM mapids WHERE mapid = $mapId";
    $result = executeQuery($sql, $conn);
    
    if ($result && $result->num_rows > 0) {
        $map = $result->fetch_assoc();
        
        // Set default values for fields that might not exist
        $defaultFields = [
            'ruunCastleZone' => 0,
            'interWarZone' => 0,
            'dungeon' => 0,
            'decreaseHp' => 0,
            'dominationTeleport' => 0,
            'geradBuffZone' => 0,
            'growBuffZone' => 0,
            'interKind' => 0,
            'script' => '',
            'pngId' => 0
        ];
        
        foreach ($defaultFields as $field => $defaultValue) {
            if (!isset($map[$field])) {
                $map[$field] = $defaultValue;
            }
        }
        
        // Get map image path
        $map['image_url'] = getMapImagePath($map['pngId'] ?? 0);
        
        return $map;
    }
    
    return null;
}

/**
 * Get monsters in a specific map
 * 
 * @param mysqli $conn Database connection
 * @param int $mapId Map ID
 * @return array Array of monsters in the map
 */
function getMapMonsters($conn, $mapId) {
    $mapId = (int)$mapId;
    $sql = "SELECT n.npcid, n.name, n.desc_kr, n.lvl, s.count
            FROM spawnlist s
            JOIN npc n ON s.npc_templateid = n.npcid
            WHERE s.mapid = $mapId AND n.impl = 'L1Monster'
            GROUP BY n.npcid
            ORDER BY n.lvl DESC";
    
    $result = executeQuery($sql, $conn);
    
    $monsters = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $monsters[] = $row;
        }
    }
    
    return $monsters;
}

/**
 * Get NPCs in a specific map
 * 
 * @param mysqli $conn Database connection
 * @param int $mapId Map ID
 * @return array Array of NPCs in the map
 */
function getMapNpcs($conn, $mapId) {
    $mapId = (int)$mapId;
    
    // Query for standard NPCs
    $sql = "SELECT n.npcid, n.name, n.desc_kr, s.locx, s.locy
            FROM spawnlist_npc s
            JOIN npc n ON s.npc_templateid = n.npcid
            WHERE s.mapid = $mapId AND (n.impl = 'L1Npc' OR n.impl = 'L1Merchant')
            ORDER BY n.name";
    
    $result = executeQuery($sql, $conn);
    
    $npcs = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $npcs[] = $row;
        }
    }
    
    // Query for shop NPCs
    $sqlShops = "SELECT npc_id as npcid, name, title, locx, locy
                FROM spawnlist_npc_shop
                WHERE mapid = $mapId
                ORDER BY name";
    
    $resultShops = executeQuery($sqlShops, $conn);
    
    if ($resultShops) {
        while ($row = $resultShops->fetch_assoc()) {
            $row['desc_kr'] = $row['title']; // Use title as description
            $npcs[] = $row;
        }
    }
    
    // Query for cash shop NPCs
    $sqlCashShops = "SELECT npc_id as npcid, name, title, locx, locy
                    FROM spawnlist_npc_cash_shop
                    WHERE mapid = $mapId
                    ORDER BY name";
    
    $resultCashShops = executeQuery($sqlCashShops, $conn);
    
    if ($resultCashShops) {
        while ($row = $resultCashShops->fetch_assoc()) {
            $row['desc_kr'] = 'Cash Shop: ' . $row['title']; // Identify as cash shop
            $npcs[] = $row;
        }
    }
    
    return $npcs;
}

/**
 * Get map type name
 * 
 * @param int $underwater Underwater flag
 * @param int $beginZone Beginner zone flag
 * @param int $redKnightZone Red Knight zone flag
 * @return string Map type description
 */
function getMapTypeName($underwater, $beginZone, $redKnightZone) {
    if ($underwater == 1) {
        return 'Underwater Map';
    } elseif ($beginZone == 1) {
        return 'Beginner Zone';
    } elseif ($redKnightZone == 1) {
        return 'Red Knight Zone';
    }
    return 'Normal Map';
}

/**
 * Update map information
 * 
 * @param mysqli $conn Database connection
 * @param int $mapId Map ID
 * @param array $data Map data to update
 * @return bool True if successful, false otherwise
 */
function updateMap($conn, $mapId, $data) {
    $mapId = (int)$mapId;
    
    // Sanitize input data
    $locationname = $conn->real_escape_string($data['locationname']);
    $desc_kr = $conn->real_escape_string($data['desc_kr']);
    $underwater = (int)$data['underwater'];
    $beginZone = (int)$data['beginZone'];
    $redKnightZone = (int)$data['redKnightZone'];
    $startX = (int)$data['startX'];
    $endX = (int)$data['endX'];
    $startY = (int)$data['startY'];
    $endY = (int)$data['endY'];
    $monster_amount = (float)$data['monster_amount'];
    $drop_rate = (float)$data['drop_rate'];
    $markable = (int)$data['markable'];
    $teleportable = (int)$data['teleportable'];
    $escapable = (int)$data['escapable'];
    $resurrection = (int)$data['resurrection'];
    $painwand = (int)$data['painwand'];
    $penalty = (int)$data['penalty'];
    $take_pets = (int)$data['take_pets'];
    $recall_pets = (int)$data['recall_pets'];
    $usable_item = (int)$data['usable_item'];
    $usable_skill = (int)$data['usable_skill'];
    
    // Additional fields 
    $dungeon = isset($data['dungeon']) ? (int)$data['dungeon'] : 0;
    $decreaseHp = isset($data['decreaseHp']) ? (int)$data['decreaseHp'] : 0;
    $dominationTeleport = isset($data['dominationTeleport']) ? (int)$data['dominationTeleport'] : 0;
    $ruunCastleZone = isset($data['ruunCastleZone']) ? (int)$data['ruunCastleZone'] : 0;
    $interWarZone = isset($data['interWarZone']) ? (int)$data['interWarZone'] : 0;
    $geradBuffZone = isset($data['geradBuffZone']) ? (int)$data['geradBuffZone'] : 0;
    $growBuffZone = isset($data['growBuffZone']) ? (int)$data['growBuffZone'] : 0;
    $interKind = isset($data['interKind']) ? (int)$data['interKind'] : 0;
    $script = isset($data['script']) ? $conn->real_escape_string($data['script']) : '';
    $pngId = isset($data['pngId']) ? (int)$data['pngId'] : 0;
    
    // Build the SQL update statement
    $sql = "UPDATE mapids SET 
            locationname = '$locationname',
            desc_kr = '$desc_kr',
            underwater = $underwater,
            beginZone = $beginZone,
            redKnightZone = $redKnightZone,
            startX = $startX,
            endX = $endX,
            startY = $startY,
            endY = $endY,
            monster_amount = $monster_amount,
            drop_rate = $drop_rate,
            markable = $markable,
            teleportable = $teleportable,
            escapable = $escapable,
            resurrection = $resurrection,
            painwand = $painwand,
            penalty = $penalty,
            take_pets = $take_pets,
            recall_pets = $recall_pets,
            usable_item = $usable_item,
            usable_skill = $usable_skill";
    
    // Add additional fields if they exist in the table
    $columnsCheck = executeQuery("SHOW COLUMNS FROM mapids", $conn);
    $columns = [];
    
    if ($columnsCheck) {
        while ($column = $columnsCheck->fetch_assoc()) {
            $columns[] = $column['Field'];
        }
        
        // Add additional fields if they exist in the table
        $additionalFields = [
            'dungeon' => $dungeon,
            'decreaseHp' => $decreaseHp,
            'dominationTeleport' => $dominationTeleport,
            'ruunCastleZone' => $ruunCastleZone,
            'interWarZone' => $interWarZone,
            'geradBuffZone' => $geradBuffZone,
            'growBuffZone' => $growBuffZone,
            'interKind' => $interKind,
            'script' => "'$script'",
            'pngId' => $pngId
        ];
        
        foreach ($additionalFields as $field => $value) {
            if (in_array($field, $columns)) {
                $sql .= ", $field = " . $value;
            }
        }
    }
    
    $sql .= " WHERE mapid = $mapId";
    
    // Execute the update query and return result
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Failed to update map: " . $conn->error . " in query: " . $sql);
    }
    
    return $result;
}

/**
 * Create a new map
 * 
 * @param mysqli $conn Database connection
 * @param array $data Map data to insert
 * @return int|bool New map ID if successful, false otherwise
 */
function createMap($conn, $data) {
    // Sanitize input data
    $mapid = (int)$data['mapid'];
    $locationname = $conn->real_escape_string($data['locationname']);
    $desc_kr = $conn->real_escape_string($data['desc_kr']);
    $underwater = (int)$data['underwater'];
    $beginZone = (int)$data['beginZone'];
    $redKnightZone = (int)$data['redKnightZone'];
    $startX = (int)$data['startX'];
    $endX = (int)$data['endX'];
    $startY = (int)$data['startY'];
    $endY = (int)$data['endY'];
    $monster_amount = (float)$data['monster_amount'];
    $drop_rate = (float)$data['drop_rate'];
    $markable = (int)$data['markable'];
    $teleportable = (int)$data['teleportable'];
    $escapable = (int)$data['escapable'];
    $resurrection = (int)$data['resurrection'];
    $painwand = (int)$data['painwand'];
    $penalty = (int)$data['penalty'];
    $take_pets = (int)$data['take_pets'];
    $recall_pets = (int)$data['recall_pets'];
    $usable_item = (int)$data['usable_item'];
    $usable_skill = (int)$data['usable_skill'];
    
    // Additional fields
    $dungeon = isset($data['dungeon']) ? (int)$data['dungeon'] : 0;
    $decreaseHp = isset($data['decreaseHp']) ? (int)$data['decreaseHp'] : 0;
    $dominationTeleport = isset($data['dominationTeleport']) ? (int)$data['dominationTeleport'] : 0;
    $ruunCastleZone = isset($data['ruunCastleZone']) ? (int)$data['ruunCastleZone'] : 0;
    $interWarZone = isset($data['interWarZone']) ? (int)$data['interWarZone'] : 0;
    $geradBuffZone = isset($data['geradBuffZone']) ? (int)$data['geradBuffZone'] : 0;
    $growBuffZone = isset($data['growBuffZone']) ? (int)$data['growBuffZone'] : 0;
    $interKind = isset($data['interKind']) ? (int)$data['interKind'] : 0;
    $script = isset($data['script']) ? $conn->real_escape_string($data['script']) : '';
    $pngId = isset($data['pngId']) ? (int)$data['pngId'] : 0;
    
    // Get table columns to ensure we only insert fields that exist
    $columnsCheck = executeQuery("SHOW COLUMNS FROM mapids", $conn);
    $columns = [];
    
    if ($columnsCheck) {
        while ($column = $columnsCheck->fetch_assoc()) {
            $columns[] = $column['Field'];
        }
    }
    
    // Build column and value lists for the INSERT query
    $columnList = [];
    $valueList = [];
    
    // Basic fields
    $basicFields = [
        'mapid' => $mapid,
        'locationname' => "'$locationname'",
        'desc_kr' => "'$desc_kr'",
        'underwater' => $underwater,
        'beginZone' => $beginZone,
        'redKnightZone' => $redKnightZone,
        'startX' => $startX,
        'endX' => $endX,
        'startY' => $startY,
        'endY' => $endY,
        'monster_amount' => $monster_amount,
        'drop_rate' => $drop_rate,
        'markable' => $markable,
        'teleportable' => $teleportable,
        'escapable' => $escapable,
        'resurrection' => $resurrection,
        'painwand' => $painwand,
        'penalty' => $penalty,
        'take_pets' => $take_pets,
        'recall_pets' => $recall_pets,
        'usable_item' => $usable_item,
        'usable_skill' => $usable_skill
    ];
    
    // Add additional fields
    $additionalFields = [
        'dungeon' => $dungeon,
        'decreaseHp' => $decreaseHp,
        'dominationTeleport' => $dominationTeleport,
        'ruunCastleZone' => $ruunCastleZone,
        'interWarZone' => $interWarZone,
        'geradBuffZone' => $geradBuffZone,
        'growBuffZone' => $growBuffZone,
        'interKind' => $interKind,
        'script' => "'$script'",
        'pngId' => $pngId
    ];
    
    // Combine fields and filter to only include fields that exist in the table
    $allFields = array_merge($basicFields, $additionalFields);
    
    foreach ($allFields as $field => $value) {
        if (in_array($field, $columns)) {
            $columnList[] = $field;
            $valueList[] = $value;
        }
    }
    
    // Combine column and value lists
    $columnStr = implode(', ', $columnList);
    $valueStr = implode(', ', $valueList);
    
    // Create SQL query
    $sql = "INSERT INTO mapids ($columnStr) VALUES ($valueStr)";
    
    if ($conn->query($sql)) {
        return $mapid;
    }
    
    error_log("Failed to create map: " . $conn->error . " in query: " . $sql);
    return false;
}

/**
 * Delete a map
 * 
 * @param mysqli $conn Database connection
 * @param int $mapId Map ID
 * @return bool True if successful, false otherwise
 */
function deleteMap($conn, $mapId) {
    $mapId = (int)$mapId;
    $sql = "DELETE FROM mapids WHERE mapid = $mapId";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Failed to delete map: " . $conn->error . " in query: " . $sql);
    }
    
    return $result;
}
