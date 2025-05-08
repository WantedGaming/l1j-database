<?php
/**
 * Admin Edit Weapon
 * Edit an existing weapon in the database
 */

// Include database connection
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

// Set page title
$pageTitle = 'Admin - Edit Weapon';
$showHero = false;

// Extra CSS for admin
$extraCSS = ['../../assets/css/admin.css'];

// Get weapon ID from URL
$weaponId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If no ID provided, redirect to weapons list
if ($weaponId <= 0) {
    header('Location: ./index.php');
    exit;
}

// Process form submission
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data with validation
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $descEn = isset($_POST['desc_en']) ? trim($_POST['desc_en']) : '';
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        $material = isset($_POST['material']) ? trim($_POST['material']) : '';
        $weight = isset($_POST['weight']) ? (int)$_POST['weight'] : 0;
        $dmgSmall = isset($_POST['dmg_small']) ? (int)$_POST['dmg_small'] : 0;
        $dmgLarge = isset($_POST['dmg_large']) ? (int)$_POST['dmg_large'] : 0;
        $iconId = isset($_POST['icon_id']) ? (int)$_POST['icon_id'] : 0;
        $strBonus = isset($_POST['str_bonus']) ? (int)$_POST['str_bonus'] : 0;
        $dexBonus = isset($_POST['dex_bonus']) ? (int)$_POST['dex_bonus'] : 0;
        $conBonus = isset($_POST['con_bonus']) ? (int)$_POST['con_bonus'] : 0;
        $intBonus = isset($_POST['int_bonus']) ? (int)$_POST['int_bonus'] : 0;
        $wisBonus = isset($_POST['wis_bonus']) ? (int)$_POST['wis_bonus'] : 0;
        $chaBonus = isset($_POST['cha_bonus']) ? (int)$_POST['cha_bonus'] : 0;
        $hpBonus = isset($_POST['hp_bonus']) ? (int)$_POST['hp_bonus'] : 0;
        $mpBonus = isset($_POST['mp_bonus']) ? (int)$_POST['mp_bonus'] : 0;
        $magicLevel = isset($_POST['magic_level']) ? (int)$_POST['magic_level'] : 0;
        $levelRequired = isset($_POST['level_required']) ? (int)$_POST['level_required'] : 0;
        $bless = isset($_POST['bless']) ? (int)$_POST['bless'] : 0;
        $trade = isset($_POST['trade']) ? (int)$_POST['trade'] : 0;
        $durability = isset($_POST['durability']) ? (int)$_POST['durability'] : 0;
        $note = isset($_POST['note']) ? trim($_POST['note']) : '';
        
        // Validate required fields
        if (empty($name) || empty($descEn) || empty($type)) {
            throw new Exception("Name, Description, and Type are required fields.");
        }
        
        // Update the weapon in the database
        $sql = "UPDATE weapon SET
                name = :name, desc_en = :desc_en, type = :type,
                material = :material, weight = :weight, dmg_small = :dmg_small,
                dmg_large = :dmg_large, iconId = :icon_id, str_bonus = :str_bonus,
                dex_bonus = :dex_bonus, con_bonus = :con_bonus, int_bonus = :int_bonus,
                wis_bonus = :wis_bonus, cha_bonus = :cha_bonus, hp_bonus = :hp_bonus,
                mp_bonus = :mp_bonus, magic_level = :magic_level, level_required = :level_required,
                bless = :bless, trade = :trade, durability = :durability, note = :note
                WHERE item_id = :item_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':desc_en', $descEn);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':material', $material);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':dmg_small', $dmgSmall);
        $stmt->bindParam(':dmg_large', $dmgLarge);
        $stmt->bindParam(':icon_id', $iconId);
        $stmt->bindParam(':str_bonus', $strBonus);
        $stmt->bindParam(':dex_bonus', $dexBonus);
        $stmt->bindParam(':con_bonus', $conBonus);
        $stmt->bindParam(':int_bonus', $intBonus);
        $stmt->bindParam(':wis_bonus', $wisBonus);
        $stmt->bindParam(':cha_bonus', $chaBonus);
        $stmt->bindParam(':hp_bonus', $hpBonus);
        $stmt->bindParam(':mp_bonus', $mpBonus);
        $stmt->bindParam(':magic_level', $magicLevel);
        $stmt->bindParam(':level_required', $levelRequired);
        $stmt->bindParam(':bless', $bless);
        $stmt->bindParam(':trade', $trade);
        $stmt->bindParam(':durability', $durability);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':item_id', $weaponId);
        
        $stmt->execute();
        
        $successMessage = "Weapon successfully updated.";
    } catch (Exception $e) {
        $errorMessage = "Error updating weapon: " . $e->getMessage();
    }
}

// Get weapon details
$weapon = getItemById($pdo, 'weapon', 'item_id', $weaponId);

// If weapon not found, show error
if (!$weapon) {
    header('Location: ./index.php');
    exit;
}

// Include header
include '../../includes/header.php';
?>

<div class="admin-header mb-6 p-4">
    <div class="container">
        <div class="flex items-center justify-between">
            <h1 class="section-title">Edit Weapon</h1>
            <div>
                <a href="./index.php" class="btn btn-small">← Back to Weapons</a>
            </div>
        </div>
    </div>
</div>

<div class="mb-6">
    <?php if (!empty($successMessage)): ?>
    <div class="status-message status-success mb-4">
        <p><?php echo htmlspecialchars($successMessage); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($errorMessage)): ?>
    <div class="status-message status-error mb-4">
        <p><?php echo htmlspecialchars($errorMessage); ?></p>
    </div>
    <?php endif; ?>
    
    <form method="POST" class="admin-form">
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="name" class="form-label">Name (JP)</label>
                    <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($weapon['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="desc_en" class="form-label">Description (EN)</label>
                    <input type="text" id="desc_en" name="desc_en" class="form-input" value="<?php echo htmlspecialchars($weapon['desc_en']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="type" class="form-label">Type</label>
                    <select id="type" name="type" class="form-select" required>
                        <option value="sword" <?php echo $weapon['type'] === 'sword' ? 'selected' : ''; ?>>Sword</option>
                        <option value="dagger" <?php echo $weapon['type'] === 'dagger' ? 'selected' : ''; ?>>Dagger</option>
                        <option value="bow" <?php echo $weapon['type'] === 'bow' ? 'selected' : ''; ?>>Bow</option>
                        <option value="staff" <?php echo $weapon['type'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="spear" <?php echo $weapon['type'] === 'spear' ? 'selected' : ''; ?>>Spear</option>
                        <option value="axe" <?php echo $weapon['type'] === 'axe' ? 'selected' : ''; ?>>Axe</option>
                        <option value="blunt" <?php echo $weapon['type'] === 'blunt' ? 'selected' : ''; ?>>Blunt</option>
                        <option value="claw" <?php echo $weapon['type'] === 'claw' ? 'selected' : ''; ?>>Claw</option>
                        <option value="edoryu" <?php echo $weapon['type'] === 'edoryu' ? 'selected' : ''; ?>>Edoryu</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="material" class="form-label">Material</label>
                    <select id="material" name="material" class="form-select">
                        <option value="iron" <?php echo $weapon['material'] === 'iron' ? 'selected' : ''; ?>>Iron</option>
                        <option value="steel" <?php echo $weapon['material'] === 'steel' ? 'selected' : ''; ?>>Steel</option>
                        <option value="wood" <?php echo $weapon['material'] === 'wood' ? 'selected' : ''; ?>>Wood</option>
                        <option value="leather" <?php echo $weapon['material'] === 'leather' ? 'selected' : ''; ?>>Leather</option>
                        <option value="bone" <?php echo $weapon['material'] === 'bone' ? 'selected' : ''; ?>>Bone</option>
                        <option value="silver" <?php echo $weapon['material'] === 'silver' ? 'selected' : ''; ?>>Silver</option>
                        <option value="gold" <?php echo $weapon['material'] === 'gold' ? 'selected' : ''; ?>>Gold</option>
                        <option value="platinum" <?php echo $weapon['material'] === 'platinum' ? 'selected' : ''; ?>>Platinum</option>
                        <option value="mithril" <?php echo $weapon['material'] === 'mithril' ? 'selected' : ''; ?>>Mithril</option>
                        <option value="oriharukon" <?php echo $weapon['material'] === 'oriharukon' ? 'selected' : ''; ?>>Oriharukon</option>
                        <option value="dragonknightboots" <?php echo $weapon['material'] === 'dragonknightboots' ? 'selected' : ''; ?>>Dragon Knight</option>
                        <option value="crystal" <?php echo $weapon['material'] === 'crystal' ? 'selected' : ''; ?>>Crystal</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="weight" class="form-label">Weight</label>
                    <input type="number" id="weight" name="weight" class="form-input" value="<?php echo $weapon['weight']; ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="dmg_small" class="form-label">Damage (Small)</label>
                            <input type="number" id="dmg_small" name="dmg_small" class="form-input" value="<?php echo $weapon['dmg_small']; ?>">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="dmg_large" class="form-label">Damage (Large)</label>
                            <input type="number" id="dmg_large" name="dmg_large" class="form-input" value="<?php echo $weapon['dmg_large']; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="icon_id" class="form-label">Icon ID</label>
                    <input type="number" id="icon_id" name="icon_id" class="form-input" value="<?php echo $weapon['iconId']; ?>">
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="str_bonus" class="form-label">STR Bonus</label>
                            <input type="number" id="str_bonus" name="str_bonus" class="form-input" value="<?php echo $weapon['str_bonus']; ?>">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="dex_bonus" class="form-label">DEX Bonus</label>
                            <input type="number" id="dex_bonus" name="dex_bonus" class="form-input" value="<?php echo $weapon['dex_bonus']; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="con_bonus" class="form-label">CON Bonus</label>
                            <input type="number" id="con_bonus" name="con_bonus" class="form-input" value="<?php echo $weapon['con_bonus']; ?>">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="int_bonus" class="form-label">INT Bonus</label>
                            <input type="number" id="int_bonus" name="int_bonus" class="form-input" value="<?php echo $weapon['int_bonus']; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="wis_bonus" class="form-label">WIS Bonus</label>
                            <input type="number" id="wis_bonus" name="wis_bonus" class="form-input" value="<?php echo $weapon['wis_bonus']; ?>">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="cha_bonus" class="form-label">CHA Bonus</label>
                            <input type="number" id="cha_bonus" name="cha_bonus" class="form-input" value="<?php echo $weapon['cha_bonus']; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="hp_bonus" class="form-label">HP Bonus</label>
                            <input type="number" id="hp_bonus" name="hp_bonus" class="form-input" value="<?php echo $weapon['hp_bonus']; ?>">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="mp_bonus" class="form-label">MP Bonus</label>
                            <input type="number" id="mp_bonus" name="mp_bonus" class="form-input" value="<?php echo $weapon['mp_bonus']; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="magic_level" class="form-label">Magic Level</label>
                            <input type="number" id="magic_level" name="magic_level" class="form-input" value="<?php echo $weapon['magic_level']; ?>">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="level_required" class="form-label">Level Required</label>
                            <input type="number" id="level_required" name="level_required" class="form-input" value="<?php echo $weapon['level_required']; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="bless" class="form-label">Bless</label>
                            <select id="bless" name="bless" class="form-select">
                                <option value="0" <?php echo $weapon['bless'] == 0 ? 'selected' : ''; ?>>No</option>
                                <option value="1" <?php echo $weapon['bless'] == 1 ? 'selected' : ''; ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="trade" class="form-label">Trade</label>
                            <select id="trade" name="trade" class="form-select">
                                <option value="0" <?php echo $weapon['trade'] == 0 ? 'selected' : ''; ?>>Not Tradeable</option>
                                <option value="1" <?php echo $weapon['trade'] == 1 ? 'selected' : ''; ?>>Tradeable</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="durability" class="form-label">Durability</label>
                    <input type="number" id="durability" name="durability" class="form-input" value="<?php echo $weapon['durability']; ?>">
                </div>
                
                <div class="form-group">
                    <label for="note" class="form-label">Notes</label>
                    <textarea id="note" name="note" class="form-textarea"><?php echo htmlspecialchars($weapon['note']); ?></textarea>
                </div>
            </div>
        </div>
        
        <div class="form-footer">
            <a href="./index.php" class="btn">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<?php
// Include footer
include '../../includes/footer.php';
?>
