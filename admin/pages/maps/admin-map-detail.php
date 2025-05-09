<?php
/**
 * Admin Map Detail Page
 * Displays a form to create or edit a map
 */

// Include required files
require_once '../../../includes/db_connect.php';
require_once '../../../includes/map-functions.php';
require_once '../../includes/admin-config.php';

// Set current page for navigation highlighting
$currentAdminPage = 'maps';

// Get action type (create or edit)
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

// Get map ID if editing
$mapId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Initialize message variables
$message = '';
$messageType = '';

// Set page title based on action
if ($action === 'create') {
    $pageTitle = 'Create New Map';
    $map = [
        'mapid' => '',
        'locationname' => '',
        'desc_kr' => '',
        'underwater' => 0,
        'beginZone' => 0,
        'redKnightZone' => 0,
        'ruunCastleZone' => 0,
        'interWarZone' => 0,
        'startX' => 0,
        'endX' => 0,
        'startY' => 0,
        'endY' => 0,
        'monster_amount' => 1.0,
        'drop_rate' => 1.0,
        'markable' => 1,
        'teleportable' => 1,
        'escapable' => 1,
        'resurrection' => 1,
        'painwand' => 0,
        'penalty' => 0,
        'take_pets' => 1,
        'recall_pets' => 1,
        'usable_item' => 1,
        'usable_skill' => 1,
        'dungeon' => 0,
        'decreaseHp' => 0,
        'dominationTeleport' => 0,
        'geradBuffZone' => 0,
        'growBuffZone' => 0,
        'interKind' => 0,
        'script' => '',
        'pngId' => 0,
        'image_url' => '/assets/img/icons/placeholders/map-placeholder.png'
    ];
} else {
    $pageTitle = 'Edit Map';
    
    // Get map details
    $map = getMapById($conn, $mapId);
    
    // If map not found, redirect to the map list
    if (!$map) {
        header("Location: " . $adminBaseUrl . "pages/maps/admin-map-list.php");
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $formData = [
        'mapid' => isset($_POST['mapid']) ? (int)$_POST['mapid'] : 0,
        'locationname' => isset($_POST['locationname']) ? $_POST['locationname'] : '',
        'desc_kr' => isset($_POST['desc_kr']) ? $_POST['desc_kr'] : '',
        'underwater' => isset($_POST['underwater']) ? 1 : 0,
        'beginZone' => isset($_POST['beginZone']) ? 1 : 0,
        'redKnightZone' => isset($_POST['redKnightZone']) ? 1 : 0,
        'ruunCastleZone' => isset($_POST['ruunCastleZone']) ? 1 : 0,
        'interWarZone' => isset($_POST['interWarZone']) ? 1 : 0,
        'startX' => isset($_POST['startX']) ? (int)$_POST['startX'] : 0,
        'endX' => isset($_POST['endX']) ? (int)$_POST['endX'] : 0,
        'startY' => isset($_POST['startY']) ? (int)$_POST['startY'] : 0,
        'endY' => isset($_POST['endY']) ? (int)$_POST['endY'] : 0,
        'monster_amount' => isset($_POST['monster_amount']) ? (float)$_POST['monster_amount'] : 1.0,
        'drop_rate' => isset($_POST['drop_rate']) ? (float)$_POST['drop_rate'] : 1.0,
        'markable' => isset($_POST['markable']) ? 1 : 0,
        'teleportable' => isset($_POST['teleportable']) ? 1 : 0,
        'escapable' => isset($_POST['escapable']) ? 1 : 0,
        'resurrection' => isset($_POST['resurrection']) ? 1 : 0,
        'painwand' => isset($_POST['painwand']) ? 1 : 0,
        'penalty' => isset($_POST['penalty']) ? 1 : 0,
        'take_pets' => isset($_POST['take_pets']) ? 1 : 0,
        'recall_pets' => isset($_POST['recall_pets']) ? 1 : 0,
        'usable_item' => isset($_POST['usable_item']) ? 1 : 0,
        'usable_skill' => isset($_POST['usable_skill']) ? 1 : 0,
        'dungeon' => isset($_POST['dungeon']) ? 1 : 0,
        'decreaseHp' => isset($_POST['decreaseHp']) ? 1 : 0,
        'dominationTeleport' => isset($_POST['dominationTeleport']) ? 1 : 0,
        'geradBuffZone' => isset($_POST['geradBuffZone']) ? 1 : 0, 
        'growBuffZone' => isset($_POST['growBuffZone']) ? 1 : 0,
        'interKind' => isset($_POST['interKind']) ? (int)$_POST['interKind'] : 0,
        'script' => isset($_POST['script']) ? $_POST['script'] : '',
        'pngId' => isset($_POST['pngId']) ? (int)$_POST['pngId'] : 0
    ];
    
    // Basic validation
    if (empty($formData['locationname'])) {
        $message = "Map name is required.";
        $messageType = 'danger';
    } elseif ($action === 'create' && empty($formData['mapid'])) {
        $message = "Map ID is required.";
        $messageType = 'danger';
    } else {
        // Process form submission
        if ($action === 'create') {
            // Create new map
            $result = createMap($conn, $formData);
            if ($result) {
                $message = "Map created successfully.";
                $messageType = 'success';
                // Redirect to edit page after creation
                header("Location: " . $adminBaseUrl . "pages/maps/admin-map-detail.php?id=$result&action=edit&message=created");
                exit;
            } else {
                $message = "Error creating map. " . $conn->error;
                $messageType = 'danger';
            }
        } else {
            // Update existing map
            $result = updateMap($conn, $mapId, $formData);
            if ($result) {
                $message = "Map updated successfully.";
                $messageType = 'success';
                // Refresh map data after update
                $map = getMapById($conn, $mapId);
            } else {
                $message = "Error updating map. " . $conn->error;
                $messageType = 'danger';
            }
        }
    }
}

// Check for message parameter
if (isset($_GET['message']) && $_GET['message'] === 'created') {
    $message = "Map created successfully.";
    $messageType = 'success';
}

// Include admin header
include '../../includes/admin-header.php';
?>

<div class="container">
    <!-- Enhanced Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title"><?php echo $action === 'create' ? 'Create New Map' : 'Edit Map: ' . htmlspecialchars($map['locationname']); ?></h1>
                <p class="admin-hero-subtitle"><?php echo $action === 'create' ? 'Add a new location to the game world' : 'Modify existing map properties and settings'; ?></p>
            </div>
            <div class="hero-actions">
                <a href="<?php echo $adminBaseUrl; ?>pages/maps/admin-map-list.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left btn-icon"></i> Back to Map List
                </a>
                <?php if ($action === 'edit'): ?>
                <button type="button" onclick="document.getElementById('mapForm').submit();" class="btn btn-primary">
                    <i class="fas fa-save btn-icon"></i> Save Changes
                </button>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($action === 'edit'): ?>
        <div class="hero-controls">
            <div class="hero-filter-controls">
                <span class="filter-label">Map ID:</span>
                <span class="filter-value"><?php echo $map['mapid']; ?></span>
                
                <span class="filter-label ml-4">Type:</span>
                <span class="filter-value"><?php echo getMapTypeName($map['underwater'], $map['beginZone'], $map['redKnightZone']); ?></span>
                
                <?php if ($map['dungeon']): ?>
                <span class="badge bg-danger ml-2">Dungeon</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <i class="fas fa-info-circle alert-icon"></i>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <!-- Map Form -->
    <div class="admin-form-container">
        <form action="" method="POST" id="mapForm">
            <div class="admin-form-grid">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <h3 class="form-section-title">Basic Information</h3>
                    
                    <div class="form-group">
                        <label for="mapid" class="form-label">Map ID</label>
                        <input type="number" id="mapid" name="mapid" class="form-control" value="<?php echo htmlspecialchars($map['mapid']); ?>" <?php echo $action === 'edit' ? 'readonly' : ''; ?> required>
                    </div>
                    
                    <div class="form-group">
                        <label for="locationname" class="form-label">Map Name</label>
                        <input type="text" id="locationname" name="locationname" class="form-control" value="<?php echo htmlspecialchars($map['locationname']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="desc_kr" class="form-label">Description</label>
                        <input type="text" id="desc_kr" name="desc_kr" class="form-control" value="<?php echo htmlspecialchars($map['desc_kr']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="pngId" class="form-label">Map Image ID</label>
                        <input type="number" id="pngId" name="pngId" class="form-control" value="<?php echo htmlspecialchars($map['pngId']); ?>">
                        <small class="form-text">Used to find map image in /assets/img/icons/maps/ folder</small>
                    </div>
                    
                    <?php if ($action === 'edit'): ?>
                    <div class="form-group">
                        <label class="form-label">Map Image Preview</label>
                        <div class="map-image-preview">
                            <img src="<?php echo $map['image_url']; ?>" alt="Map Preview">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Map Coordinates Section -->
                <div class="form-section">
                    <h3 class="form-section-title">Map Coordinates</h3>
                    
                    <div class="form-group">
                        <label for="startX" class="form-label">Start X</label>
                        <input type="number" id="startX" name="startX" class="form-control" value="<?php echo htmlspecialchars($map['startX']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="endX" class="form-label">End X</label>
                        <input type="number" id="endX" name="endX" class="form-control" value="<?php echo htmlspecialchars($map['endX']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="startY" class="form-label">Start Y</label>
                        <input type="number" id="startY" name="startY" class="form-control" value="<?php echo htmlspecialchars($map['startY']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="endY" class="form-label">End Y</label>
                        <input type="number" id="endY" name="endY" class="form-control" value="<?php echo htmlspecialchars($map['endY']); ?>">
                    </div>
                </div>
                
                <!-- Map Type Section -->
                <div class="form-section">
                    <h3 class="form-section-title">Map Type</h3>
                    
                    <div class="form-check">
                        <input type="checkbox" id="underwater" name="underwater" class="form-check-input" <?php echo $map['underwater'] ? 'checked' : ''; ?>>
                        <label for="underwater" class="form-check-label">Underwater Map</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="beginZone" name="beginZone" class="form-check-input" <?php echo $map['beginZone'] ? 'checked' : ''; ?>>
                        <label for="beginZone" class="form-check-label">Beginner Zone</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="redKnightZone" name="redKnightZone" class="form-check-input" <?php echo $map['redKnightZone'] ? 'checked' : ''; ?>>
                        <label for="redKnightZone" class="form-check-label">Red Knight Zone</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="ruunCastleZone" name="ruunCastleZone" class="form-check-input" <?php echo $map['ruunCastleZone'] ? 'checked' : ''; ?>>
                        <label for="ruunCastleZone" class="form-check-label">Ruun Castle Zone</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="interWarZone" name="interWarZone" class="form-check-input" <?php echo $map['interWarZone'] ? 'checked' : ''; ?>>
                        <label for="interWarZone" class="form-check-label">Inter War Zone</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="dungeon" name="dungeon" class="form-check-input" <?php echo $map['dungeon'] ? 'checked' : ''; ?>>
                        <label for="dungeon" class="form-check-label">Dungeon</label>
                    </div>
                    
                    <div class="form-group">
                        <label for="interKind" class="form-label">Inter Kind</label>
                        <input type="number" id="interKind" name="interKind" class="form-control" value="<?php echo htmlspecialchars($map['interKind']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="script" class="form-label">Script</label>
                        <input type="text" id="script" name="script" class="form-control" value="<?php echo htmlspecialchars($map['script']); ?>">
                    </div>
                </div>
                
                <!-- Map Rates Section -->
                <div class="form-section">
                    <h3 class="form-section-title">Map Rates</h3>
                    
                    <div class="form-group">
                        <label for="monster_amount" class="form-label">Monster Amount Multiplier</label>
                        <input type="number" id="monster_amount" name="monster_amount" class="form-control" min="0" step="0.1" value="<?php echo htmlspecialchars($map['monster_amount']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="drop_rate" class="form-label">Drop Rate Multiplier</label>
                        <input type="number" id="drop_rate" name="drop_rate" class="form-control" min="0" step="0.1" value="<?php echo htmlspecialchars($map['drop_rate']); ?>">
                    </div>
                </div>
                
                <!-- Additional Zone Types -->
                <div class="form-section">
                    <h3 class="form-section-title">Special Zones</h3>
                    
                    <div class="form-check">
                        <input type="checkbox" id="geradBuffZone" name="geradBuffZone" class="form-check-input" <?php echo $map['geradBuffZone'] ? 'checked' : ''; ?>>
                        <label for="geradBuffZone" class="form-check-label">Gerad Buff Zone</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="growBuffZone" name="growBuffZone" class="form-check-input" <?php echo $map['growBuffZone'] ? 'checked' : ''; ?>>
                        <label for="growBuffZone" class="form-check-label">Grow Buff Zone</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="decreaseHp" name="decreaseHp" class="form-check-input" <?php echo $map['decreaseHp'] ? 'checked' : ''; ?>>
                        <label for="decreaseHp" class="form-check-label">HP Decrease Zone</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="dominationTeleport" name="dominationTeleport" class="form-check-input" <?php echo $map['dominationTeleport'] ? 'checked' : ''; ?>>
                        <label for="dominationTeleport" class="form-check-label">Domination Teleport</label>
                    </div>
                </div>
                
                <!-- Map Flags Section -->
                <div class="form-section">
                    <h3 class="form-section-title">Map Properties</h3>
                    
                    <div class="form-rows">
                        <div class="form-check">
                            <input type="checkbox" id="markable" name="markable" class="form-check-input" <?php echo $map['markable'] ? 'checked' : ''; ?>>
                            <label for="markable" class="form-check-label">Markable</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="teleportable" name="teleportable" class="form-check-input" <?php echo $map['teleportable'] ? 'checked' : ''; ?>>
                            <label for="teleportable" class="form-check-label">Teleportable</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="escapable" name="escapable" class="form-check-input" <?php echo $map['escapable'] ? 'checked' : ''; ?>>
                            <label for="escapable" class="form-check-label">Escapable</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="resurrection" name="resurrection" class="form-check-input" <?php echo $map['resurrection'] ? 'checked' : ''; ?>>
                            <label for="resurrection" class="form-check-label">Resurrection Allowed</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="painwand" name="painwand" class="form-check-input" <?php echo $map['painwand'] ? 'checked' : ''; ?>>
                            <label for="painwand" class="form-check-label">Pain Wand Allowed</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="penalty" name="penalty" class="form-check-input" <?php echo $map['penalty'] ? 'checked' : ''; ?>>
                            <label for="penalty" class="form-check-label">Death Penalty</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="take_pets" name="take_pets" class="form-check-input" <?php echo $map['take_pets'] ? 'checked' : ''; ?>>
                            <label for="take_pets" class="form-check-label">Take Pets Allowed</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="recall_pets" name="recall_pets" class="form-check-input" <?php echo $map['recall_pets'] ? 'checked' : ''; ?>>
                            <label for="recall_pets" class="form-check-label">Recall Pets Allowed</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="usable_item" name="usable_item" class="form-check-input" <?php echo $map['usable_item'] ? 'checked' : ''; ?>>
                            <label for="usable_item" class="form-check-label">Usable Items</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="usable_skill" name="usable_skill" class="form-check-input" <?php echo $map['usable_skill'] ? 'checked' : ''; ?>>
                            <label for="usable_skill" class="form-check-label">Usable Skills</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-buttons">
                <a href="<?php echo $adminBaseUrl; ?>pages/maps/admin-map-list.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?php echo $action === 'create' ? 'Create Map' : 'Update Map'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview map image when pngId changes
    const pngIdInput = document.getElementById('pngId');
    const imagePreview = document.querySelector('.map-image-preview img');
    
    if (pngIdInput && imagePreview) {
        pngIdInput.addEventListener('change', function() {
            const pngId = this.value;
            
            // Create a temporary URL to check if the image exists
            const testImage = new Image();
            testImage.onload = function() {
                // Image exists, update the preview
                imagePreview.src = this.src;
            };
            
            testImage.onerror = function() {
                // Image doesn't exist, show placeholder
                imagePreview.src = '/assets/img/icons/placeholders/map-placeholder.png';
            };
            
            // Try to load the image
            if (pngId) {
                testImage.src = `/assets/img/icons/maps/${pngId}.jpg`;
            } else {
                imagePreview.src = '/assets/img/icons/placeholders/map-placeholder.png';
            }
        });
    }
});
</script>

<?php
// Include admin footer
include '../../includes/admin-footer.php';
?>