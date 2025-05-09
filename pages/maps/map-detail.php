<?php
/**
 * Map Detail Page (Public)
 * Displays detailed information about a specific map
 */

// Include required files
require_once '../../includes/config.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/map-functions.php';

// Set current page for navigation highlighting
$currentPage = 'maps';

// Get map ID from URL
$mapId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get map details
$map = getMapById($conn, $mapId);

// If map not found, redirect to the map list
if (!$map) {
    header("Location: " . $baseUrl . "pages/maps/map-list.php");
    exit;
}

// Set page title
$pageTitle = $map['locationname'] . ' - Map';

// Get monsters in this map
$monsters = getMapMonsters($conn, $mapId);

// Get NPCs in this map
$npcs = getMapNpcs($conn, $mapId);

// Include header
include '../../includes/header.php';
?>

<!-- Map Detail Page Content -->
<section class="main-content">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb">
            <a href="<?php echo $baseUrl; ?>">Home</a> &raquo;
            <a href="<?php echo $baseUrl; ?>pages/maps/map-list.php">Maps</a> &raquo;
            <span><?php echo htmlspecialchars($map['locationname']); ?></span>
        </div>
        
        <!-- Map Header Section -->
        <div class="detail-header">
            <img src="<?php echo $baseUrl; ?>assets/img/maps/<?php echo $mapId; ?>.jpg" 
                 alt="<?php echo htmlspecialchars($map['locationname']); ?>" 
                 class="detail-image"
                 onerror="this.src='<?php echo $baseUrl; ?>assets/img/map-placeholder.jpg'">
            
            <div class="detail-title-section">
                <h1 class="detail-title"><?php echo htmlspecialchars($map['locationname']); ?></h1>
                <p class="detail-subtitle"><?php echo htmlspecialchars($map['desc_kr']); ?></p>
                
                <div class="detail-tags">
                    <span class="detail-tag">Map ID: <?php echo $map['mapid']; ?></span>
                    <span class="detail-tag">
                        <?php echo getMapTypeName($map['underwater'], $map['beginZone'], $map['redKnightZone']); ?>
                    </span>
                    <?php if ($map['markable'] == 1): ?>
                        <span class="detail-tag">Markable</span>
                    <?php endif; ?>
                    <?php if ($map['teleportable'] == 1): ?>
                        <span class="detail-tag">Teleportable</span>
                    <?php endif; ?>
                    <?php if ($map['escapable'] == 1): ?>
                        <span class="detail-tag">Escapable</span>
                    <?php endif; ?>
                    <?php if ($map['resurrection'] == 1): ?>
                        <span class="detail-tag">Resurrection Allowed</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Map Detail Content -->
        <div class="detail-content">
            <!-- Left Column - Main Info -->
            <div class="detail-main">
                <!-- Map Coordinates Section -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Map Coordinates</h2>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-label">Start X</div>
                            <div class="stat-value"><?php echo $map['startX']; ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">End X</div>
                            <div class="stat-value"><?php echo $map['endX']; ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Start Y</div>
                            <div class="stat-value"><?php echo $map['startY']; ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">End Y</div>
                            <div class="stat-value"><?php echo $map['endY']; ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Monsters Section -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Monsters in This Map</h2>
                    <?php if (empty($monsters)): ?>
                        <p>No monsters found in this map.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Monster Name</th>
                                        <th>Level</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($monsters as $monster): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo $baseUrl; ?>pages/monsters/monster-detail.php?id=<?php echo $monster['npcid']; ?>">
                                                    <?php echo htmlspecialchars($monster['desc_kr']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo $monster['lvl']; ?></td>
                                            <td><?php echo $monster['count']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Right Column - Additional Info -->
            <div class="detail-sidebar">
                <!-- Map Properties Section -->
                <div class="detail-section">
                    <h2 class="detail-section-title">Map Properties</h2>
                    <ul class="detail-properties-list">
                        <li>
                            <span class="property-label">Underwater:</span>
                            <span class="property-value"><?php echo $map['underwater'] ? 'Yes' : 'No'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Beginner Zone:</span>
                            <span class="property-value"><?php echo $map['beginZone'] ? 'Yes' : 'No'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Red Knight Zone:</span>
                            <span class="property-value"><?php echo $map['redKnightZone'] ? 'Yes' : 'No'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Monster Density:</span>
                            <span class="property-value"><?php echo $map['monster_amount']; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Drop Rate:</span>
                            <span class="property-value"><?php echo $map['drop_rate']; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Markable:</span>
                            <span class="property-value"><?php echo $map['markable'] ? 'Yes' : 'No'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Teleportable:</span>
                            <span class="property-value"><?php echo $map['teleportable'] ? 'Yes' : 'No'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Escapable:</span>
                            <span class="property-value"><?php echo $map['escapable'] ? 'Yes' : 'No'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Resurrection:</span>
                            <span class="property-value"><?php echo $map['resurrection'] ? 'Allowed' : 'Not Allowed'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Pain Wand:</span>
                            <span class="property-value"><?php echo $map['painwand'] ? 'Allowed' : 'Not Allowed'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Penalty:</span>
                            <span class="property-value"><?php echo $map['penalty'] ? 'Yes' : 'No'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Take Pets:</span>
                            <span class="property-value"><?php echo $map['take_pets'] ? 'Allowed' : 'Not Allowed'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Recall Pets:</span>
                            <span class="property-value"><?php echo $map['recall_pets'] ? 'Allowed' : 'Not Allowed'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Usable Items:</span>
                            <span class="property-value"><?php echo $map['usable_item'] ? 'Allowed' : 'Not Allowed'; ?></span>
                        </li>
                        <li>
                            <span class="property-label">Usable Skills:</span>
                            <span class="property-value"><?php echo $map['usable_skill'] ? 'Allowed' : 'Not Allowed'; ?></span>
                        </li>
                    </ul>
                </div>
                
                <!-- NPCs Section -->
                <div class="detail-section">
                    <h2 class="detail-section-title">NPCs in This Map</h2>
                    <?php if (empty($npcs)): ?>
                        <p>No NPCs found in this map.</p>
                    <?php else: ?>
                        <ul class="npc-list">
                            <?php foreach ($npcs as $npc): ?>
                                <li>
                                    <a href="<?php echo $baseUrl; ?>pages/npcs/npc-detail.php?id=<?php echo $npc['npcid']; ?>">
                                        <?php echo htmlspecialchars($npc['desc_kr']); ?>
                                    </a>
                                    <span class="npc-location">(<?php echo $npc['locx']; ?>, <?php echo $npc['locy']; ?>)</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include '../../includes/footer.php';
?>