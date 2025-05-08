<?php
// Include database connection
require_once __DIR__ . '/../includes/config/database.php';
require_once __DIR__ . '/../includes/functions/common.php';

// Check if ID parameter exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('/weapons/');
}

$weaponId = (int)$_GET['id'];

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Fetch weapon data
$stmt = $db->prepare("SELECT * FROM weapon WHERE item_id = ?");
$stmt->bindParam(1, $weaponId);
$stmt->execute();

$weapon = $stmt->fetch(PDO::FETCH_ASSOC);

// If weapon not found, redirect to weapons list
if (!$weapon) {
    // Set flash message
    startSession();
    $_SESSION['flash_message'] = "Weapon with ID $weaponId not found.";
    $_SESSION['flash_type'] = 'error';
    
    redirect('/weapons/');
}

// Fetch weapon skills
$skillStmt = $db->prepare("SELECT * FROM weapon_skill WHERE weapon_id = ?");
$skillStmt->bindParam(1, $weaponId);
$skillStmt->execute();
$weaponSkills = $skillStmt->fetchAll(PDO::FETCH_ASSOC);

// Set page variables
$pageTitle = $weapon['name'];
$pageSubtitle = 'Weapon Details';
$showHero = true;
$showSearch = false;
$pageSection = 'Weapons';
$itemName = $weapon['name'];

// Include header
require_once __DIR__ . '/../includes/layouts/header.php';
?>

<div class="container">
    <div class="detail-view">
        <div class="detail-header">
            <h2><?php echo htmlspecialchars($weapon['name']); ?></h2>
            <a href="/weapons/" class="back-link">Back to Weapons List</a>
        </div>
        
        <div class="detail-content">
            <div class="detail-section">
                <h3>Basic Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">ID:</span>
                        <?php echo $weapon['item_id']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Name ID:</span>
                        <?php echo htmlspecialchars($weapon['name_id']); ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Type:</span>
                        <?php echo ucfirst($weapon['weapon_type']); ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Material:</span>
                        <?php echo ucfirst($weapon['material']); ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Weight:</span>
                        <?php echo $weapon['weight']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Sellable:</span>
                        <?php echo $weapon['tradable'] ? 'Yes' : 'No'; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Deletable:</span>
                        <?php echo $weapon['deletable'] ? 'Yes' : 'No'; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Storage:</span>
                        <?php echo $weapon['warehouse'] ? 'Yes' : 'No'; ?>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3>Weapon Properties</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Damage (Small):</span>
                        <?php echo $weapon['dmg_small']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Damage (Large):</span>
                        <?php echo $weapon['dmg_large']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Level Requirement:</span>
                        <?php echo $weapon['level_min'] > 0 ? $weapon['level_min'] : 'None'; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Durability:</span>
                        <?php echo $weapon['durability']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Hit Modifier:</span>
                        <?php echo ($weapon['hit_modifier'] >= 0 ? '+' : '') . $weapon['hit_modifier']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Critical Modifier:</span>
                        <?php echo $weapon['critical_modifier']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Range:</span>
                        <?php echo $weapon['range']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Safe Enchant:</span>
                        <?php echo $weapon['safenchant']; ?>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3>Status Effects</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">STR:</span>
                        <?php echo ($weapon['add_str'] >= 0 ? '+' : '') . $weapon['add_str']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">DEX:</span>
                        <?php echo ($weapon['add_dex'] >= 0 ? '+' : '') . $weapon['add_dex']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">CON:</span>
                        <?php echo ($weapon['add_con'] >= 0 ? '+' : '') . $weapon['add_con']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">INT:</span>
                        <?php echo ($weapon['add_int'] >= 0 ? '+' : '') . $weapon['add_int']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">WIS:</span>
                        <?php echo ($weapon['add_wis'] >= 0 ? '+' : '') . $weapon['add_wis']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">CHA:</span>
                        <?php echo ($weapon['add_cha'] >= 0 ? '+' : '') . $weapon['add_cha']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">HP:</span>
                        <?php echo ($weapon['add_hp'] >= 0 ? '+' : '') . $weapon['add_hp']; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">MP:</span>
                        <?php echo ($weapon['add_mp'] >= 0 ? '+' : '') . $weapon['add_mp']; ?>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3>Magic Effects</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Magic Level:</span>
                        <?php echo $weapon['magic_level'] ?: 'None'; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Magic Item:</span>
                        <?php echo $weapon['magic_item'] ? 'Yes' : 'No'; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Haste Item:</span>
                        <?php echo $weapon['haste_item'] ? 'Yes' : 'No'; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Can Damage:</span>
                        <?php echo $weapon['can_damage'] ? 'Yes' : 'No'; ?>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($weaponSkills)): ?>
            <div class="detail-section">
                <h3>Weapon Skills</h3>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Skill ID</th>
                            <th>Skill Name</th>
                            <th>Probability</th>
                            <th>Effects</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($weaponSkills as $skill): ?>
                        <tr>
                            <td><?php echo $skill['skill_id']; ?></td>
                            <td><?php echo htmlspecialchars($skill['skill_name']); ?></td>
                            <td><?php echo $skill['probability']; ?>%</td>
                            <td><?php echo htmlspecialchars($skill['effect_description'] ?: 'None'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            
            <div class="detail-section">
                <h3>Game Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Bless:</span>
                        <?php echo $weapon['bless'] ? 'Yes' : 'No'; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Trade:</span>
                        <?php echo $weapon['trade'] ? 'Yes' : 'No'; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">NPC Sell:</span>
                        <?php echo $weapon['npc_sell'] ? 'Yes' : 'No'; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Min Level:</span>
                        <?php echo $weapon['min_lvl'] ?: 'None'; ?>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Max Level:</span>
                        <?php echo $weapon['max_lvl'] ?: 'None'; ?>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3>Description</h3>
                <div class="detail-description">
                    <?php if (!empty($weapon['description'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($weapon['description'])); ?></p>
                    <?php else: ?>
                        <p><em>No description available for this weapon.</em></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/../includes/layouts/footer.php';
?>