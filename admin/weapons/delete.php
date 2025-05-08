<?php
// Include database connection
require_once __DIR__ . '/../../includes/config/database.php';
require_once __DIR__ . '/../../includes/functions/common.php';

// Check if ID parameter exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('/admin/weapons/');
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
    
    redirect('/admin/weapons/');
}

// Set page variables
$pageTitle = 'Delete Weapon: ' . $weapon['name'];
$pageSubtitle = 'Permanently remove this weapon from the database';
$showHero = true;
$showSearch = false;
$pageSection = 'Admin Weapons';
$itemName = $weapon['name'];

// Generate CSRF token
$csrfToken = generateCsrfToken();

// Process form submission
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $errors[] = 'Invalid form submission. Please try again.';
    } else {
        // Check confirmation
        if (!isset($_POST['confirm_delete']) || $_POST['confirm_delete'] !== '1') {
            $errors[] = 'You must confirm the deletion by checking the confirmation box.';
        } else {
            try {
                // Begin transaction
                $db->beginTransaction();
                
                // Delete related weapon skills
                $skillDeleteStmt = $db->prepare("DELETE FROM weapon_skill WHERE weapon_id = ?");
                $skillDeleteStmt->bindParam(1, $weaponId);
                $skillDeleteStmt->execute();
                
                // Delete weapon
                $weaponDeleteStmt = $db->prepare("DELETE FROM weapon WHERE item_id = ?");
                $weaponDeleteStmt->bindParam(1, $weaponId);
                $weaponDeleteStmt->execute();
                
                // Commit transaction
                $db->commit();
                
                // Set success message
                $_SESSION['flash_message'] = "Weapon '" . htmlspecialchars($weapon['name']) . "' has been deleted successfully.";
                $_SESSION['flash_type'] = 'success';
                
                // Redirect back to weapons list
                redirect('/admin/weapons/');
            } catch (PDOException $e) {
                // Rollback transaction on error
                $db->rollBack();
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Include admin header
require_once __DIR__ . '/../../includes/layouts/admin_header.php';
?>

<div class="admin-header-actions">
    <h2>Delete Weapon: <?php echo htmlspecialchars($weapon['name']); ?></h2>
    
    <div class="admin-actions">
        <a href="/admin/weapons/view.php?id=<?php echo $weaponId; ?>" class="btn btn-secondary">Cancel</a>
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

<div class="admin-form">
    <div class="alert alert-error">
        <strong>Warning!</strong> You are about to delete the weapon "<?php echo htmlspecialchars($weapon['name']); ?>" (ID: <?php echo $weapon['item_id']; ?>).
        This action cannot be undone. All related data will be permanently removed from the database.
    </div>
    
    <h3>Weapon Details</h3>
    
    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label">ID:</span>
            <?php echo $weapon['item_id']; ?>
        </div>
        <div class="detail-item">
            <span class="detail-label">Name:</span>
            <?php echo htmlspecialchars($weapon['name']); ?>
        </div>
        <div class="detail-item">
            <span class="detail-label">Type:</span>
            <?php echo ucfirst($weapon['weapon_type']); ?>
        </div>
        <div class="detail-item">
            <span class="detail-label">Level Requirement:</span>
            <?php echo $weapon['level_min']; ?>
        </div>
        <div class="detail-item">
            <span class="detail-label">Damage:</span>
            <?php echo $weapon['dmg_small'] . '/' . $weapon['dmg_large']; ?>
        </div>
    </div>
    
    <?php
    // Check if weapon has related skills
    $skillStmt = $db->prepare("SELECT COUNT(*) FROM weapon_skill WHERE weapon_id = ?");
    $skillStmt->bindParam(1, $weaponId);
    $skillStmt->execute();
    $skillCount = $skillStmt->fetchColumn();
    
    if ($skillCount > 0):
    ?>
    <div class="alert alert-error" style="margin-top: 1rem;">
        <strong>Note:</strong> This weapon has <?php echo $skillCount; ?> associated skill(s) that will also be deleted.
    </div>
    <?php endif; ?>
    
    <form method="POST" action="/admin/weapons/delete.php?id=<?php echo $weaponId; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
        
        <div class="form-group checkbox-group">
            <div class="checkbox-container">
                <input type="checkbox" id="confirm_delete" name="confirm_delete" value="1">
                <label for="confirm_delete">I confirm that I want to permanently delete this weapon and all associated data.</label>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-danger">Delete Weapon</button>
            <a href="/admin/weapons/view.php?id=<?php echo $weaponId; ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php
// Include admin footer
require_once __DIR__ . '/../../includes/layouts/admin_footer.php';
?>