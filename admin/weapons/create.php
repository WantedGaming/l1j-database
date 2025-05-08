<?php
// Include database connection
require_once __DIR__ . '/../../includes/config/database.php';
require_once __DIR__ . '/../../includes/functions/common.php';

// Set page variables
$pageTitle = 'Create New Weapon';
$pageSubtitle = 'Add a new weapon to the database';
$showHero = true;
$showSearch = false;
$pageSection = 'Admin Weapons';

// Include admin header
require_once __DIR__ . '/../../includes/layouts/admin_header.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Generate CSRF token
$csrfToken = generateCsrfToken();

// Process form submission
$errors = [];
$success = false;
$formData = [
    'item_id' => '',
    'name' => '',
    'name_id' => '',
    'type' => 'weapon',
    'weapon_type' => '',
    'material' => 'iron',
    'weight' => 0,
    'dmg_small' => 0,
    'dmg_large' => 0,
    'level_min' => 0,
    'durability' => 0,
    'hit_modifier' => 0,
    'critical_modifier' => 0,
    'range' => 0,
    'safenchant' => 0,
    'add_str' => 0,
    'add_dex' => 0,
    'add_con' => 0,
    'add_int' => 0,
    'add_wis' => 0,
    'add_cha' => 0,
    'add_hp' => 0,
    'add_mp' => 0,
    'magic_level' => 0,
    'magic_item' => 0,
    'haste_item' => 0,
    'can_damage' => 1,
    'bless' => 0,
    'trade' => 1,
    'tradable' => 1,
    'deletable' => 1,
    'warehouse' => 1,
    'npc_sell' => 1,
    'min_lvl' => 0,
    'max_lvl' => 0,
    'description' => '',
    'icon_id' => 0
];

// Fetch available weapon types
$weaponTypesStmt = $db->query("SELECT DISTINCT weapon_type FROM weapon ORDER BY weapon_type");
$weaponTypes = $weaponTypesStmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch available materials
$materialsStmt = $db->query("SELECT DISTINCT material FROM weapon ORDER BY material");
$materials = $materialsStmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $errors[] = 'Invalid form submission. Please try again.';
    } else {
        // Get and sanitize form data
        foreach ($formData as $key => $value) {
            if (isset($_POST[$key])) {
                if (is_numeric($value)) {
                    $formData[$key] = (int)$_POST[$key];
                } else {
                    $formData[$key] = sanitize($_POST[$key]);
                }
            }
        }
        
        // Handle checkbox fields (they will only be set if checked)
        $booleanFields = ['magic_item', 'haste_item', 'can_damage', 'bless', 'trade', 'tradable', 'deletable', 'warehouse', 'npc_sell'];
        foreach ($booleanFields as $field) {
            $formData[$field] = isset($_POST[$field]) ? 1 : 0;
        }
        
        // Validate required fields
        if (empty($formData['item_id'])) {
            $errors[] = 'Item ID is required.';
        }
        
        if (empty($formData['name'])) {
            $errors[] = 'Name is required.';
        }
        
        if (empty($formData['weapon_type'])) {
            $errors[] = 'Weapon type is required.';
        }
        
        // Check if item ID already exists
        if (!empty($formData['item_id'])) {
            $checkStmt = $db->prepare("SELECT COUNT(*) FROM weapon WHERE item_id = ?");
            $checkStmt->bindParam(1, $formData['item_id']);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() > 0) {
                $errors[] = 'A weapon with this Item ID already exists.';
            }
        }
        
        // If no errors, insert new weapon
        if (empty($errors)) {
            try {
                // Prepare SQL statement
                $sql = "INSERT INTO weapon (
                    item_id, name, name_id, type, weapon_type, material, weight,
                    dmg_small, dmg_large, level_min, durability, hit_modifier,
                    critical_modifier, range, safenchant, add_str, add_dex, add_con,
                    add_int, add_wis, add_cha, add_hp, add_mp, magic_level,
                    magic_item, haste_item, can_damage, bless, trade, tradable,
                    deletable, warehouse, npc_sell, min_lvl, max_lvl, description,
                    icon_id
                ) VALUES (
                    :item_id, :name, :name_id, :type, :weapon_type, :material, :weight,
                    :dmg_small, :dmg_large, :level_min, :durability, :hit_modifier,
                    :critical_modifier, :range, :safenchant, :add_str, :add_dex, :add_con,
                    :add_int, :add_wis, :add_cha, :add_hp, :add_mp, :magic_level,
                    :magic_item, :haste_item, :can_damage, :bless, :trade, :tradable,
                    :deletable, :warehouse, :npc_sell, :min_lvl, :max_lvl, :description,
                    :icon_id
                )";
                
                // Prepare and execute statement
                $stmt = $db->prepare($sql);
                
                // Bind parameters
                foreach ($formData as $key => $value) {
                    $stmt->bindValue(':' . $key, $value);
                }
                
                $stmt->execute();
                
                // Set success message
                $_SESSION['flash_message'] = "Weapon created successfully.";
                $_SESSION['flash_type'] = 'success';
                
                // Redirect to view the new weapon
                redirect('/admin/weapons/view.php?id=' . $formData['item_id']);
            } catch (PDOException $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<div class="admin-header-actions">
    <h2>Create New Weapon</h2>
    
    <div class="admin-actions">
        <a href="/admin/weapons/" class="btn btn-secondary">Back to Weapons</a>
    </div>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-error">
    <strong>Please fix the following errors:</strong>
    <ul>
        <?php foreach ($errors as $error): ?>
        <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form class="admin-form" method="POST" action="/admin/weapons/create.php">
    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
    
    <div class="form-row">
        <div class="form-group">
            <label for="item_id" class="form-label">Item ID *</label>
            <input type="number" id="item_id" name="item_id" class="form-control" value="<?php echo htmlspecialchars($formData['item_id']); ?>" required>
            <div class="form-text">Unique identifier for this weapon</div>
        </div>
        
        <div class="form-group">
            <label for="icon_id" class="form-label">Icon ID</label>
            <input type="number" id="icon_id" name="icon_id" class="form-control" value="<?php echo htmlspecialchars($formData['icon_id']); ?>">
            <div class="form-text">Icon identifier for this weapon</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="name" class="form-label">Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($formData['name']); ?>" required>
            <div class="form-text">Display name for this weapon</div>
        </div>
        
        <div class="form-group">
            <label for="name_id" class="form-label">Name ID</label>
            <input type="text" id="name_id" name="name_id" class="form-control" value="<?php echo htmlspecialchars($formData['name_id']); ?>">
            <div class="form-text">Internal name identifier (optional)</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="weapon_type" class="form-label">Weapon Type *</label>
            <select id="weapon_type" name="weapon_type" class="form-control" required>
                <option value="">Select Weapon Type</option>
                <?php foreach ($weaponTypes as $type): ?>
                <option value="<?php echo $type; ?>" <?php echo $formData['weapon_type'] === $type ? 'selected' : ''; ?>>
                    <?php echo ucfirst($type); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <div class="form-text">Type of weapon (sword, dagger, etc.)</div>
        </div>
        
        <div class="form-group">
            <label for="material" class="form-label">Material</label>
            <select id="material" name="material" class="form-control">
                <?php foreach ($materials as $material): ?>
                <option value="<?php echo $material; ?>" <?php echo $formData['material'] === $material ? 'selected' : ''; ?>>
                    <?php echo ucfirst($material); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <div class="form-text">Material the weapon is made from</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="weight" class="form-label">Weight</label>
            <input type="number" id="weight" name="weight" class="form-control" value="<?php echo htmlspecialchars($formData['weight']); ?>">
            <div class="form-text">Weight of the weapon</div>
        </div>
        
        <div class="form-group">
            <label for="level_min" class="form-label">Level Requirement</label>
            <input type="number" id="level_min" name="level_min" class="form-control" value="<?php echo htmlspecialchars($formData['level_min']); ?>">
            <div class="form-text">Minimum level required to use this weapon</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="dmg_small" class="form-label">Damage (Small)</label>
            <input type="number" id="dmg_small" name="dmg_small" class="form-control" value="<?php echo htmlspecialchars($formData['dmg_small']); ?>">
            <div class="form-text">Base damage against small creatures</div>
        </div>
        
        <div class="form-group">
            <label for="dmg_large" class="form-label">Damage (Large)</label>
            <input type="number" id="dmg_large" name="dmg_large" class="form-control" value="<?php echo htmlspecialchars($formData['dmg_large']); ?>">
            <div class="form-text">Base damage against large creatures</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="hit_modifier" class="form-label">Hit Modifier</label>
            <input type="number" id="hit_modifier" name="hit_modifier" class="form-control" value="<?php echo htmlspecialchars($formData['hit_modifier']); ?>">
            <div class="form-text">Bonus/penalty to hit accuracy</div>
        </div>
        
        <div class="form-group">
            <label for="critical_modifier" class="form-label">Critical Modifier</label>
            <input type="number" id="critical_modifier" name="critical_modifier" class="form-control" value="<?php echo htmlspecialchars($formData['critical_modifier']); ?>">
            <div class="form-text">Bonus/penalty to critical hit chance</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="range" class="form-label">Range</label>
            <input type="number" id="range" name="range" class="form-control" value="<?php echo htmlspecialchars($formData['range']); ?>">
            <div class="form-text">Attack range (0 for melee weapons)</div>
        </div>
        
        <div class="form-group">
            <label for="durability" class="form-label">Durability</label>
            <input type="number" id="durability" name="durability" class="form-control" value="<?php echo htmlspecialchars($formData['durability']); ?>">
            <div class="form-text">Weapon durability/uses before breaking</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="safenchant" class="form-label">Safe Enchant</label>
            <input type="number" id="safenchant" name="safenchant" class="form-control" value="<?php echo htmlspecialchars($formData['safenchant']); ?>">
            <div class="form-text">Maximum safe enchantment level</div>
        </div>
        
        <div class="form-group">
            <label for="magic_level" class="form-label">Magic Level</label>
            <input type="number" id="magic_level" name="magic_level" class="form-control" value="<?php echo htmlspecialchars($formData['magic_level']); ?>">
            <div class="form-text">Magical power level of the weapon</div>
        </div>
    </div>
    
    <h3>Stat Modifiers</h3>
    
    <div class="form-row">
        <div class="form-group">
            <label for="add_str" class="form-label">STR Modifier</label>
            <input type="number" id="add_str" name="add_str" class="form-control" value="<?php echo htmlspecialchars($formData['add_str']); ?>">
            <div class="form-text">Strength bonus/penalty</div>
        </div>
        
        <div class="form-group">
            <label for="add_dex" class="form-label">DEX Modifier</label>
            <input type="number" id="add_dex" name="add_dex" class="form-control" value="<?php echo htmlspecialchars($formData['add_dex']); ?>">
            <div class="form-text">Dexterity bonus/penalty</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="add_con" class="form-label">CON Modifier</label>
            <input type="number" id="add_con" name="add_con" class="form-control" value="<?php echo htmlspecialchars($formData['add_con']); ?>">
            <div class="form-text">Constitution bonus/penalty</div>
        </div>
        
        <div class="form-group">
            <label for="add_int" class="form-label">INT Modifier</label>
            <input type="number" id="add_int" name="add_int" class="form-control" value="<?php echo htmlspecialchars($formData['add_int']); ?>">
            <div class="form-text">Intelligence bonus/penalty</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="add_wis" class="form-label">WIS Modifier</label>
            <input type="number" id="add_wis" name="add_wis" class="form-control" value="<?php echo htmlspecialchars($formData['add_wis']); ?>">
            <div class="form-text">Wisdom bonus/penalty</div>
        </div>
        
        <div class="form-group">
            <label for="add_cha" class="form-label">CHA Modifier</label>
            <input type="number" id="add_cha" name="add_cha" class="form-control" value="<?php echo htmlspecialchars($formData['add_cha']); ?>">
            <div class="form-text">Charisma bonus/penalty</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="add_hp" class="form-label">HP Modifier</label>
            <input type="number" id="add_hp" name="add_hp" class="form-control" value="<?php echo htmlspecialchars($formData['add_hp']); ?>">
            <div class="form-text">HP bonus/penalty</div>
        </div>
        
        <div class="form-group">
            <label for="add_mp" class="form-label">MP Modifier</label>
            <input type="number" id="add_mp" name="add_mp" class="form-control" value="<?php echo htmlspecialchars($formData['add_mp']); ?>">
            <div class="form-text">MP bonus/penalty</div>
        </div>
    </div>
    
    <h3>Weapon Properties</h3>
    
    <div class="form-row">
        <div class="form-group">
            <label for="min_lvl" class="form-label">Min Level</label>
            <input type="number" id="min_lvl" name="min_lvl" class="form-control" value="<?php echo htmlspecialchars($formData['min_lvl']); ?>">
            <div class="form-text">Minimum character level</div>
        </div>
        
        <div class="form-group">
            <label for="max_lvl" class="form-label">Max Level</label>
            <input type="number" id="max_lvl" name="max_lvl" class="form-control" value="<?php echo htmlspecialchars($formData['max_lvl']); ?>">
            <div class="form-text">Maximum character level (0 for no limit)</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group checkbox-group">
            <div class="checkbox-container">
                <input type="checkbox" id="magic_item" name="magic_item" <?php echo $formData['magic_item'] ? 'checked' : ''; ?>>
                <label for="magic_item">Magic Item</label>
            </div>
            <div class="form-text">Weapon is considered magical</div>
        </div>
        
        <div class="form-group checkbox-group">
            <div class="checkbox-container">
                <input type="checkbox" id="haste_item" name="haste_item" <?php echo $formData['haste_item'] ? 'checked' : ''; ?>>
                <label for="haste_item">Haste Item</label>
            </div>
            <div class="form-text">Weapon provides haste effect</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group checkbox-group">
            <div class="checkbox-container">
                <input type="checkbox" id="can_damage" name="can_damage" <?php echo $formData['can_damage'] ? 'checked' : ''; ?>>
                <label for="can_damage">Can Damage</label>
            </div>
            <div class="form-text">Weapon can inflict damage</div>
        </div>
        
        <div class="form-group checkbox-group">
            <div class="checkbox-container">
                <input type="checkbox" id="bless" name="bless" <?php echo $formData['bless'] ? 'checked' : ''; ?>>
                <label for="bless">Blessed</label>
            </div>
            <div class="form-text">Weapon is blessed</div>
        </div>
    </div>
    
    <h3>Item Flags</h3>
    
    <div class="form-row">
        <div class="form-group checkbox-group">
            <div class="checkbox-container">
                <input type="checkbox" id="trade" name="trade" <?php echo $formData['trade'] ? 'checked' : ''; ?>>
                <label for="trade">Trade</label>
            </div>
            <div class="form-text">Can be traded between players</div>
        </div>
        
        <div class="form-group checkbox-group">
            <div class="checkbox-container">
                <input type="checkbox" id="tradable" name="tradable" <?php echo $formData['tradable'] ? 'checked' : ''; ?>>
                <label for="tradable">Sellable</label>
            </div>
            <div class="form-text">Can be sold to NPCs</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group checkbox-group">
            <div class="checkbox-container">
                <input type="checkbox" id="deletable" name="deletable" <?php echo $formData['deletable'] ? 'checked' : ''; ?>>
                <label for="deletable">Deletable</label>
            </div>
            <div class="form-text">Can be deleted by players</div>
        </div>
        
        <div class="form-group checkbox-group">
            <div class="checkbox-container">
                <input type="checkbox" id="warehouse" name="warehouse" <?php echo $formData['warehouse'] ? 'checked' : ''; ?>>
                <label for="warehouse">Storage</label>
            </div>
            <div class="form-text">Can be stored in warehouse</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group checkbox-group">
            <div class="checkbox-container">
                <input type="checkbox" id="npc_sell" name="npc_sell" <?php echo $formData['npc_sell'] ? 'checked' : ''; ?>>
                <label for="npc_sell">NPC Sell</label>
            </div>
            <div class="form-text">Can be purchased from NPCs</div>
        </div>
    </div>
    
    <div class="form-group">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" class="form-control" rows="5"><?php echo htmlspecialchars($formData['description']); ?></textarea>
        <div class="form-text">Detailed description of the weapon</div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn">Create Weapon</button>
        <a href="/admin/weapons/" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php
// Include admin footer
require_once __DIR__ . '/../../includes/layouts/admin_footer.php';
?>