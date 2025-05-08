<?php
/**
 * Weapon Detail Page
 * Displays detailed information about a specific weapon
 */

// Include database connection
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Get weapon ID from URL
$weaponId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If no ID provided, redirect to weapons list
if ($weaponId <= 0) {
    header('Location: /weapons/');
    exit;
}

// Get weapon details
$weapon = getItemById($pdo, 'weapon', 'item_id', $weaponId);

// If weapon not found, show error
if (!$weapon) {
    // Set page title
    $pageTitle = 'Weapon Not Found';
    $showHero = false;
    
    // Include header
    include '../includes/header.php';
    
    echo '<div class="card p-6 text-center">';
    echo '<h1 class="section-title">Weapon Not Found</h1>';
    echo '<p>The weapon you are looking for could not be found. It may have been removed or you may have followed an incorrect link.</p>';
    echo '<a href="/weapons/" class="btn btn-primary mt-4">Back to Weapons List</a>';
    echo '</div>';
    
    // Include footer
    include '../includes/footer.php';
    exit;
}

// Set page title
$pageTitle = htmlspecialchars($weapon['desc_en']);
$showHero = false;

// Include header
include '../includes/header.php';
?>

<div class="mb-6">
    <div class="flex items-center mb-4">
        <a href="/weapons/" class="btn btn-small mr-4">← Back to Weapons</a>
        <h1 class="section-title mb-0"><?php echo htmlspecialchars($weapon['desc_en']); ?></h1>
    </div>
    
    <div class="detail-container">
        <div class="detail-image">
            <div class="card p-6 text-center">
                <img src="<?php echo getImagePath('weapon', $weapon['iconId']); ?>" alt="<?php echo htmlspecialchars($weapon['desc_en']); ?>" style="width: 128px; height: 128px; margin: 0 auto;">
                <h2 class="mt-4 mb-0"><?php echo htmlspecialchars($weapon['desc_en']); ?></h2>
                <p class="text-light"><?php echo htmlspecialchars(ucfirst($weapon['type'])); ?> Weapon</p>
            </div>
        </div>
        
        <div class="detail-content">
            <h2 class="section-title">Basic Information</h2>
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value"><?php echo htmlspecialchars($weapon['desc_en']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Type:</div>
                <div class="info-value"><?php echo htmlspecialchars(ucfirst($weapon['type'])); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Material:</div>
                <div class="info-value"><?php echo htmlspecialchars(ucfirst($weapon['material'])); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Damage:</div>
                <div class="info-value"><?php echo $weapon['dmg_small'] . '-' . $weapon['dmg_large']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Weight:</div>
                <div class="info-value"><?php echo formatNumber($weapon['weight'] / 1000); ?> Weight</div>
            </div>
            
            <h2 class="section-title mt-6">Stats & Requirements</h2>
            <div class="info-row">
                <div class="info-label">STR Bonus:</div>
                <div class="info-value"><?php echo $weapon['str_bonus']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">DEX Bonus:</div>
                <div class="info-value"><?php echo $weapon['dex_bonus']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">CON Bonus:</div>
                <div class="info-value"><?php echo $weapon['con_bonus']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">INT Bonus:</div>
                <div class="info-value"><?php echo $weapon['int_bonus']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">WIS Bonus:</div>
                <div class="info-value"><?php echo $weapon['wis_bonus']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">CHA Bonus:</div>
                <div class="info-value"><?php echo $weapon['cha_bonus']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">HP Bonus:</div>
                <div class="info-value"><?php echo $weapon['hp_bonus']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">MP Bonus:</div>
                <div class="info-value"><?php echo $weapon['mp_bonus']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Magic Level:</div>
                <div class="info-value"><?php echo $weapon['magic_level']; ?></div>
            </div>
            
            <h2 class="section-title mt-6">Additional Information</h2>
            <div class="info-row">
                <div class="info-label">Level Required:</div>
                <div class="info-value"><?php echo $weapon['level_required']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Bless:</div>
                <div class="info-value"><?php echo $weapon['bless'] == 1 ? 'Yes' : 'No'; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Trade:</div>
                <div class="info-value"><?php echo $weapon['trade'] == 0 ? 'Not Tradeable' : 'Tradeable'; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Durability:</div>
                <div class="info-value"><?php echo $weapon['durability']; ?></div>
            </div>
            
            <?php if (!empty($weapon['note'])): ?>
            <h2 class="section-title mt-6">Notes</h2>
            <div class="card p-4">
                <?php echo nl2br(htmlspecialchars($weapon['note'])); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-6">
        <h2 class="section-title">Where to Find</h2>
        
        <?php
        // Query to find NPCs/Monsters that drop this weapon
        $dropQuery = "SELECT d.*, n.desc_en as monster_name, n.level as monster_level, n.npcid 
                     FROM droplist d 
                     JOIN npc n ON d.mobId = n.npcid 
                     WHERE d.itemId = :weaponId";
        
        $dropStmt = $pdo->prepare($dropQuery);
        $dropStmt->bindValue(':weaponId', $weaponId);
        $dropStmt->execute();
        $drops = $dropStmt->fetchAll();
        
        if (count($drops) > 0):
        ?>
        <div class="card p-6">
            <h3 class="mb-4">Monster Drops</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Monster</th>
                            <th>Level</th>
                            <th>Drop Chance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($drops as $drop): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($drop['monster_name']); ?></td>
                            <td><?php echo $drop['monster_level']; ?></td>
                            <td><?php echo number_format(($drop['chance'] / 10000) * 100, 4); ?>%</td>
                            <td>
                                <a href="/monsters/detail.php?id=<?php echo $drop['npcid']; ?>" class="btn btn-small">View Monster</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="card p-6 text-center">
            <p>No monsters drop this weapon. It may be acquired through other means such as shops, quests, or crafting.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
include '../includes/footer.php';
?>
