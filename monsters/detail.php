<?php
/**
 * Monster Detail Page
 * Displays detailed information about a specific monster
 */

// Include database connection
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Get monster ID from URL
$monsterId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If no ID provided, redirect to monsters list
if ($monsterId <= 0) {
    header('Location: /monsters/');
    exit;
}

// Get monster details
$monster = getItemById($pdo, 'npc', 'npcid', $monsterId);

// If monster not found, show error
if (!$monster || $monster['type'] !== 'monster') {
    // Set page title
    $pageTitle = 'Monster Not Found';
    $showHero = false;
    
    // Include header
    include '../includes/header.php';
    
    echo '<div class="card p-6 text-center">';
    echo '<h1 class="section-title">Monster Not Found</h1>';
    echo '<p>The monster you are looking for could not be found. It may have been removed or you may have followed an incorrect link.</p>';
    echo '<a href="/monsters/" class="btn btn-primary mt-4">Back to Monsters List</a>';
    echo '</div>';
    
    // Include footer
    include '../includes/footer.php';
    exit;
}

// Get monster drops
$drops = getMonsterDrops($pdo, $monsterId);

// Get monster spawns
$spawns = getMonsterSpawns($pdo, $monsterId);

// Set page title
$pageTitle = htmlspecialchars($monster['desc_en']);
$showHero = false;

// Include header
include '../includes/header.php';
?>

<div class="mb-6">
    <div class="flex items-center mb-4">
        <a href="/monsters/" class="btn btn-small mr-4">← Back to Monsters</a>
        <h1 class="section-title mb-0"><?php echo htmlspecialchars($monster['desc_en']); ?></h1>
    </div>
    
    <div class="detail-container">
        <div class="detail-image">
            <div class="card p-6 text-center">
                <img src="<?php echo getImagePath('monster', $monster['npcid']); ?>" alt="<?php echo htmlspecialchars($monster['desc_en']); ?>" style="width: 128px; height: 128px; margin: 0 auto;">
                <h2 class="mt-4 mb-0"><?php echo htmlspecialchars($monster['desc_en']); ?></h2>
                <p class="text-light">Level <?php echo $monster['level']; ?> Monster</p>
            </div>
        </div>
        
        <div class="detail-content">
            <h2 class="section-title">Basic Information</h2>
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value"><?php echo htmlspecialchars($monster['desc_en']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Level:</div>
                <div class="info-value"><?php echo $monster['level']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">HP:</div>
                <div class="info-value"><?php echo formatNumber($monster['hp']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">MP:</div>
                <div class="info-value"><?php echo formatNumber($monster['mp']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">AC:</div>
                <div class="info-value"><?php echo $monster['ac']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Alignment:</div>
                <div class="info-value"><?php echo $monster['alignment']; ?></div>
            </div>
            
            <h2 class="section-title mt-6">Stats</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="info-row">
                    <div class="info-label">STR:</div>
                    <div class="info-value"><?php echo $monster['str']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">DEX:</div>
                    <div class="info-value"><?php echo $monster['dex']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">CON:</div>
                    <div class="info-value"><?php echo $monster['con']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">INT:</div>
                    <div class="info-value"><?php echo $monster['int']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">WIS:</div>
                    <div class="info-value"><?php echo $monster['wis']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">CHA:</div>
                    <div class="info-value"><?php echo $monster['cha']; ?></div>
                </div>
            </div>
            
            <h2 class="section-title mt-6">Combat Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="info-row">
                    <div class="info-label">Agro:</div>
                    <div class="info-value"><?php echo $monster['agro'] ? 'Yes' : 'No'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Passive:</div>
                    <div class="info-value"><?php echo $monster['passive'] ? 'Yes' : 'No'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Undead:</div>
                    <div class="info-value"><?php echo $monster['undead'] ? 'Yes' : 'No'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Teleport:</div>
                    <div class="info-value"><?php echo $monster['teleport'] ? 'Yes' : 'No'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Hard:</div>
                    <div class="info-value"><?php echo $monster['hard'] ? 'Yes' : 'No'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tam:</div>
                    <div class="info-value"><?php echo $monster['tam'] ? 'Yes' : 'No'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Is Boss:</div>
                    <div class="info-value"><?php echo $monster['is_boss'] ? 'Yes' : 'No'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Can Poly:</div>
                    <div class="info-value"><?php echo $monster['cant_poly'] ? 'No' : 'Yes'; ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6">
        <h2 class="section-title">Item Drops</h2>
        
        <?php if (count($drops) > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Item Name</th>
                        <th>Type</th>
                        <th>Drop Chance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($drops as $drop): ?>
                    <tr>
                        <td>
                            <img src="<?php echo getImagePath($drop['item_type'], $drop['iconId']); ?>" alt="<?php echo htmlspecialchars($drop['item_name']); ?>" class="item-icon">
                        </td>
                        <td><?php echo htmlspecialchars($drop['item_name']); ?></td>
                        <td><?php echo ucfirst($drop['item_type']); ?></td>
                        <td><?php echo number_format(($drop['chance'] / 10000) * 100, 4); ?>%</td>
                        <td>
                            <a href="/<?php echo $drop['item_type']; ?>s/detail.php?id=<?php echo $drop['itemId']; ?>" class="btn btn-small">View Item</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="card p-6 text-center">
            <p>This monster does not drop any items.</p>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="mt-6">
        <h2 class="section-title">Spawn Locations</h2>
        
        <?php 
        $hasSpawns = (!empty($spawns['regular']) || !empty($spawns['boss']));
        
        if ($hasSpawns): 
        ?>
        
        <?php if (!empty($spawns['regular'])): ?>
        <div class="card p-6 mb-6">
            <h3 class="mb-4">Regular Spawns</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Map</th>
                            <th>Count</th>
                            <th>Location</th>
                            <th>Min Respawn</th>
                            <th>Max Respawn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($spawns['regular'] as $spawn): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($spawn['map_name'] ?? "Map ID: {$spawn['mapid']}"); ?></td>
                            <td><?php echo $spawn['count']; ?></td>
                            <td>
                                <?php 
                                // Show average location if specific location isn't available
                                if (isset($spawn['locx1']) && isset($spawn['locy1']) && 
                                    isset($spawn['locx2']) && isset($spawn['locy2'])) {
                                    $avgX = (int)(($spawn['locx1'] + $spawn['locx2']) / 2);
                                    $avgY = (int)(($spawn['locy1'] + $spawn['locy2']) / 2);
                                    echo "Around ({$avgX}, {$avgY})";
                                } else {
                                    echo "Unknown";
                                }
                                ?>
                            </td>
                            <td><?php echo isset($spawn['min_respawn_delay']) ? floor($spawn['min_respawn_delay'] / 60) . ' min' : 'N/A'; ?></td>
                            <td><?php echo isset($spawn['max_respawn_delay']) ? floor($spawn['max_respawn_delay'] / 60) . ' min' : 'N/A'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($spawns['boss'])): ?>
        <div class="card p-6">
            <h3 class="mb-4">Boss Spawns</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Map</th>
                            <th>Location</th>
                            <th>Respawn Time</th>
                            <th>Group ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($spawns['boss'] as $spawn): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($spawn['map_name'] ?? "Map ID: {$spawn['mapid']}"); ?></td>
                            <td>
                                <?php 
                                if (isset($spawn['loc_x']) && isset($spawn['loc_y'])) {
                                    echo "({$spawn['loc_x']}, {$spawn['loc_y']})";
                                } else {
                                    echo "Unknown";
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if (isset($spawn['respawn_time'])) {
                                    $hours = floor($spawn['respawn_time'] / 3600);
                                    $minutes = floor(($spawn['respawn_time'] % 3600) / 60);
                                    if ($hours > 0) {
                                        echo "{$hours}h {$minutes}m";
                                    } else {
                                        echo "{$minutes}m";
                                    }
                                } else {
                                    echo "Unknown";
                                }
                                ?>
                            </td>
                            <td><?php echo isset($spawn['group_id']) ? $spawn['group_id'] : 'N/A'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="card p-6 text-center">
            <p>No spawn information available for this monster.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
include '../includes/footer.php';
?>
