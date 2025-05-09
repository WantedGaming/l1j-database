<?php
/**
 * Admin Character Detail Page
 * Displays detailed information about a specific character
 */

// Include required files
require_once '../../../includes/db_connect.php';
require_once '../../includes/admin-config.php';
require_once '../../includes/account-functions.php';

// Set current page for navigation highlighting
$currentAdminPage = 'accounts';

// Get character ID from URL
$characterId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get character details
$character = getCharacterById($conn, $characterId);

// If character not found, redirect to the account list
if (!$character) {
    header("Location: " . $adminBaseUrl . "pages/accounts/account-list.php");
    exit;
}

// Set page title
$pageTitle = 'Character: ' . $character['char_name'];

// Include admin header
include '../../includes/admin-header.php';
?>
<!-- Include the character detail specific CSS -->
<link rel="stylesheet" href="<?php echo $adminBaseUrl; ?>assets/css/account-styles.css">
<link rel="stylesheet" href="<?php echo $adminBaseUrl; ?>assets/css/character-detail.css">

<div class="container">
    <!-- Enhanced Hero Section -->
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Character: <?php echo htmlspecialchars($character['char_name']); ?></h1>
                <p class="admin-hero-subtitle">
                    <?php echo getClassName($character['Class'], $character['gender']); ?> 
                    Level <?php echo $character['level']; ?> - 
                    Account: <?php echo htmlspecialchars($character['account_name']); ?>
                </p>
            </div>
            <div class="hero-actions">
                <?php if (isset($character['account_id'])): ?>
                <a href="<?php echo $adminBaseUrl; ?>pages/accounts/account-detail.php?id=<?php echo $character['account_id']; ?>" class="btn btn-primary">
                    <i class="fas fa-user btn-icon"></i> View Account
                </a>
                <?php endif; ?>
                <a href="<?php echo $adminBaseUrl; ?>pages/accounts/account-list.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left btn-icon"></i> Back to Accounts
                </a>
            </div>
        </div>
    </div>
	
    <!-- Attributes Row - Each stat in its own card -->
    <div class="attributes-row">
        <!-- STR Attribute -->
        <div class="attribute-card">
            <div class="attribute-value">
                <?php echo $character['Str']; ?>
                <?php if ($character['Str'] != $character['BaseStr']): ?>
                    <span class="base-value">(<?php echo $character['BaseStr']; ?>)</span>
                <?php endif; ?>
            </div>
            <div class="attribute-bar">
                <div class="attribute-fill" style="width: <?php echo min(round(($character['Str'] / 30) * 100), 100); ?>%"></div>
            </div>
            <div class="attribute-label">STR</div>
        </div>
        
        <!-- DEX Attribute -->
        <div class="attribute-card">
            <div class="attribute-value">
                <?php echo $character['Dex']; ?>
                <?php if ($character['Dex'] != $character['BaseDex']): ?>
                    <span class="base-value">(<?php echo $character['BaseDex']; ?>)</span>
                <?php endif; ?>
            </div>
            <div class="attribute-bar">
                <div class="attribute-fill" style="width: <?php echo min(round(($character['Dex'] / 30) * 100), 100); ?>%"></div>
            </div>
            <div class="attribute-label">DEX</div>
        </div>
        
        <!-- CON Attribute -->
        <div class="attribute-card">
            <div class="attribute-value">
                <?php echo $character['Con']; ?>
                <?php if ($character['Con'] != $character['BaseCon']): ?>
                    <span class="base-value">(<?php echo $character['BaseCon']; ?>)</span>
                <?php endif; ?>
            </div>
            <div class="attribute-bar">
                <div class="attribute-fill" style="width: <?php echo min(round(($character['Con'] / 30) * 100), 100); ?>%"></div>
            </div>
            <div class="attribute-label">CON</div>
        </div>
        
        <!-- WIS Attribute -->
        <div class="attribute-card">
            <div class="attribute-value">
                <?php echo $character['Wis']; ?>
                <?php if ($character['Wis'] != $character['BaseWis']): ?>
                    <span class="base-value">(<?php echo $character['BaseWis']; ?>)</span>
                <?php endif; ?>
            </div>
            <div class="attribute-bar">
                <div class="attribute-fill" style="width: <?php echo min(round(($character['Wis'] / 30) * 100), 100); ?>%"></div>
            </div>
            <div class="attribute-label">WIS</div>
        </div>
        
        <!-- INT Attribute -->
        <div class="attribute-card">
            <div class="attribute-value">
                <?php echo $character['Intel']; ?>
                <?php if ($character['Intel'] != $character['BaseIntel']): ?>
                    <span class="base-value">(<?php echo $character['BaseIntel']; ?>)</span>
                <?php endif; ?>
            </div>
            <div class="attribute-bar">
                <div class="attribute-fill" style="width: <?php echo min(round(($character['Intel'] / 30) * 100), 100); ?>%"></div>
            </div>
            <div class="attribute-label">INT</div>
        </div>
        
        <!-- CHA Attribute -->
        <div class="attribute-card">
            <div class="attribute-value">
                <?php echo $character['Cha']; ?>
                <?php if ($character['Cha'] != $character['BaseCha']): ?>
                    <span class="base-value">(<?php echo $character['BaseCha']; ?>)</span>
                <?php endif; ?>
            </div>
            <div class="attribute-bar">
                <div class="attribute-fill" style="width: <?php echo min(round(($character['Cha'] / 30) * 100), 100); ?>%"></div>
            </div>
            <div class="attribute-label">CHA</div>
        </div>
    </div>
    
    <!-- Main Content Grid - 3 Cards Layout -->
    <div class="main-content-grid">
        <!-- Other Characters Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Other Characters</h3>
            </div>
            <div class="admin-card-body">
                <?php
                // Assuming this function gets other characters from the same account
                $accountName = $character['account_name'] ?? '';
                $otherCharacters = [];
                
                if (!empty($accountName) && function_exists('getAccountCharacters')) {
                    $otherCharacters = getAccountCharacters($conn, $accountName, $character['objid']);
                }
                
                if (empty($otherCharacters)):
                ?>
                <div class="empty-state">No other characters found for this account.</div>
                <?php else: ?>
                <div class="character-list-container">
                    <ul class="character-list">
                        <?php foreach ($otherCharacters as $otherChar): ?>
                        <li class="character-item">
                            <span class="character-level-badge"><?php echo $otherChar['level']; ?></span>
                            <img src="<?php echo getClassImagePath($otherChar['Class'], $otherChar['gender']); ?>" 
                                 alt="<?php echo getClassName($otherChar['Class'], $otherChar['gender']); ?>" 
                                 class="character-icon">
                            <div class="character-info">
                                <div class="character-name">
                                    <a href="<?php echo $adminBaseUrl; ?>pages/accounts/character-detail.php?id=<?php echo $otherChar['objid']; ?>">
                                        <?php echo htmlspecialchars($otherChar['char_name']); ?>
                                    </a>
                                    <?php if (!empty($otherChar['Clanname'])): ?>
                                    <span class="clan-info"><?php echo htmlspecialchars($otherChar['Clanname']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="character-class"><?php echo getClassName($otherChar['Class'], $otherChar['gender']); ?></div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Details Card (Combining Character Overview, Basic Info, PVP Statistics) -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Details</h3>
            </div>
            <div class="admin-card-body">
                <!-- Character Overview Section -->
                <div class="section-divider">
                    <h4 class="section-title">Character Overview</h4>
                </div>
                
                <div class="character-profile">
                    <img src="<?php echo getClassImagePath($character['Class'], $character['gender']); ?>" 
                         alt="<?php echo getClassName($character['Class'], $character['gender']); ?>" 
                         class="character-avatar">
                    
                    <div class="character-header">
                        <h2 class="detail-title"><?php echo htmlspecialchars($character['char_name']); ?></h2>
                        <div class="character-profile-info">
                            <div class="character-profile-item">
                                <i class="fas fa-user"></i> 
                                <?php echo getClassName($character['Class'], $character['gender']); ?>
                            </div>
                            
                            <div class="character-profile-item">
                                <i class="fas fa-star"></i> 
                                Level <?php echo $character['level']; ?> 
                                <?php if ($character['HighLevel'] > 0): ?>
                                    (High Level: <?php echo $character['HighLevel']; ?>)
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($character['Clanname'])): ?>
                                <div class="character-profile-item">
                                    <i class="fas fa-shield-alt"></i> 
                                    <?php echo htmlspecialchars($character['Clanname']); ?>
                                    (<?php echo getClanRankName($character['ClanRank']); ?>)
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php 
                        // Determine class for styling
                        $classType = '';
                        if (strpos($character['Class'], 'royal') !== false || 
                            strpos($character['Class'], 'knight') !== false || 
                            strpos($character['Class'], 'warrior') !== false) {
                            $classType = 'Human';
                        } elseif (strpos($character['Class'], 'elf') !== false) {
                            $classType = 'Elf';
                        } elseif (strpos($character['Class'], 'darkelf') !== false) {
                            $classType = 'DarkElf';
                        } elseif (strpos($character['Class'], 'orc') !== false) {
                            $classType = 'Orc';
                        } elseif (strpos($character['Class'], 'dwarf') !== false) {
                            $classType = 'Dwarf';
                        } elseif (strpos($character['Class'], 'kamael') !== false) {
                            $classType = 'Kamael';
                        }
                        ?>
                        <div class="class-tag class-<?php echo strtolower($classType); ?>">
                            <?php echo $classType; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Combat Stats Section -->
                <div class="section-divider">
                    <h4 class="section-title">Combat Stats</h4>
                </div>
                
                <div class="resource-stats">
                    <div class="resource-stat">
                        <div class="resource-label">
                            <span class="resource-name">HP</span>
                            <span class="resource-value"><?php echo $character['CurHp']; ?> / <?php echo $character['MaxHp']; ?></span>
                        </div>
                        <div class="resource-bar">
                            <div class="hp-bar" style="width: <?php echo max(1, min(100, round(($character['CurHp'] / max(1, $character['MaxHp'])) * 100))); ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="resource-stat">
                        <div class="resource-label">
                            <span class="resource-name">MP</span>
                            <span class="resource-value"><?php echo $character['CurMp']; ?> / <?php echo $character['MaxMp']; ?></span>
                        </div>
                        <div class="resource-bar">
                            <div class="mp-bar" style="width: <?php echo max(1, min(100, round(($character['CurMp'] / max(1, $character['MaxMp'])) * 100))); ?>%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="compact-stats">
                    <div class="compact-stat">
                        <span class="stat-label">AC</span>
                        <span class="stat-value"><?php echo $character['Ac']; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Exp Recovery</span>
                        <span class="stat-value"><?php echo $character['ExpRes']; ?>%</span>
                    </div>
                </div>
                
                <!-- Basic Information Section -->
                <div class="section-divider">
                    <h4 class="section-title">Basic Information</h4>
                </div>
                
                <div class="compact-stats">
                    <div class="compact-stat">
                        <span class="stat-label">Character ID</span>
                        <span class="stat-value"><?php echo $character['objid']; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Type</span>
                        <span class="stat-value"><?php echo $character['Type']; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Status</span>
                        <span class="stat-value">
                            <?php 
                            $statusClass = '';
                            switch($character['Status']) {
                                case 'Active':
                                    $statusClass = 'status-active';
                                    break;
                                case 'Deleted':
                                    $statusClass = 'status-expired';
                                    break;
                            }
                            ?>
                            <span class="<?php echo $statusClass; ?>"><?php echo $character['Status']; ?></span>
                        </span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Title</span>
                        <span class="stat-value"><?php echo !empty($character['Title']) ? htmlspecialchars($character['Title']) : 'None'; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Access Level</span>
                        <span class="stat-value"><?php echo getAccessLevelName($character['AccessLevel']); ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Online Status</span>
                        <span class="stat-value">
                            <?php if($character['OnlineStatus']): ?>
                                <span class="status-active">Online</span>
                            <?php else: ?>
                                <span>Offline</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                
                <!-- PVP Statistics Section -->
                <div class="section-divider">
                    <h4 class="section-title">PvP Statistics</h4>
                </div>
                
                <?php 
                $pcKill = $character['PC_Kill'] ?? 0;
                $pcDeath = $character['PC_Death'] ?? 1; // Avoid division by zero
                $kdRatio = number_format($pcKill / max(1, $pcDeath), 2);
                $kdClass = $kdRatio >= 1 ? 'kd-ratio-good' : 'kd-ratio-bad';
                ?>
                
                <div class="pvp-overview">
                    <div class="pvp-stat">
                        <div class="pk-count"><?php echo $character['PKcount']; ?></div>
                        <div class="pk-label">Player Kills</div>
                    </div>
                    <div class="pvp-stat">
                        <div class="kd-ratio <?php echo $kdClass; ?>"><?php echo $kdRatio; ?></div>
                        <div class="pk-label">K/D Ratio</div>
                    </div>
                </div>
                
                <div class="compact-stats">
                    <div class="compact-stat">
                        <span class="stat-label">Karma</span>
                        <span class="stat-value"><?php echo $character['Karma']; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Last PK</span>
                        <span class="stat-value"><?php echo $character['LastPk'] ? date('M j, Y H:i:s', strtotime($character['LastPk'])) : 'Never'; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Player Kills</span>
                        <span class="stat-value"><?php echo $character['PC_Kill'] ?? 0; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Player Deaths</span>
                        <span class="stat-value"><?php echo $character['PC_Death'] ?? 0; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Other Card (Location + Time + Map preview) -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Others</h3>
            </div>
            <div class="admin-card-body">
                <!-- Location Section -->
                <div class="section-divider">
                    <h4 class="section-title">Location</h4>
                </div>
                
                <div class="map-preview-container">
                    <div class="map-preview">
                        <!-- Map Preview Placeholder -->
                        <div class="map-placeholder">
                            <i class="fas fa-map-marked-alt"></i>
                            <div>Map Preview</div>
                        </div>
                    </div>
                    
                    <div class="map-coordinates">
                        <div class="coordinate-label">
                            <i class="fas fa-map-marker-alt"></i> Map: 
                            <span class="coordinate-value">
                                <?php echo $character['MapID']; ?>
                                <?php if (function_exists('getMapName')): ?>
                                    (<?php echo getMapName($character['MapID']); ?>)
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="coordinate-label">
                            <i class="fas fa-location-arrow"></i> Position: 
                            <span class="coordinate-value">
                                X: <?php echo $character['LocX']; ?>, 
                                Y: <?php echo $character['LocY']; ?>
                            </span>
                        </div>
                        <div class="coordinate-label">
                            <i class="fas fa-compass"></i> Heading: 
                            <span class="coordinate-value"><?php echo $character['Heading']; ?></span>
                        </div>
                        <div class="coordinate-label">
                            <i class="fas fa-home"></i> Hometown: 
                            <span class="coordinate-value">
                                <?php echo $character['HomeTownID']; ?>
                                <?php if (function_exists('getTownName')): ?>
                                    (<?php echo getTownName($character['HomeTownID']); ?>)
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Time Information Section -->
                <div class="section-divider">
                    <h4 class="section-title">Time Information</h4>
                </div>
                
                <div class="compact-stats">
                    <div class="compact-stat">
                        <span class="stat-label">Last Login</span>
                        <span class="stat-value"><?php echo $character['lastLoginTime'] ? date('M j, Y H:i:s', strtotime($character['lastLoginTime'])) : 'Never'; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Last Logout</span>
                        <span class="stat-value"><?php echo $character['lastLogoutTime'] ? date('M j, Y H:i:s', strtotime($character['lastLogoutTime'])) : 'Never'; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Delete Time</span>
                        <span class="stat-value">
                            <?php if ($character['DeleteTime']): ?>
                                <span class="status-expired"><?php echo date('M j, Y H:i:s', strtotime($character['DeleteTime'])); ?></span>
                            <?php else: ?>
                                Not Scheduled
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Hell Time</span>
                        <span class="stat-value"><?php echo $character['HellTime'] ? $character['HellTime'] . ' seconds' : 'None'; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Birthday</span>
                        <span class="stat-value"><?php echo $character['BirthDay'] ? $character['BirthDay'] : 'Not Set'; ?></span>
                    </div>
                    <div class="compact-stat">
                        <span class="stat-label">Ban Status</span>
                        <span class="stat-value">
                            <?php if ($character['Banned']): ?>
                                <span class="status-expired">Banned</span>
                            <?php else: ?>
                                <span class="status-active">Not Banned</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Special Status Section (Card Layout) -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Special Statuses & Buffs</h3>
        </div>
        <div class="admin-card-body">
            <?php
            // Define buff fields to check
            $buffFields = [
                'Buff_DMG_Time' => 'Damage Buff',
                'Buff_Reduc_Time' => 'Reduction Buff',
                'Buff_Magic_Time' => 'Magic Buff',
                'Buff_Stun_Time' => 'Stun Resistance Buff',
                'Buff_Hold_Time' => 'Hold Resistance Buff',
                'BUFF_PCROOM_Time' => 'PC Room Buff',
                'Buff_FireDefence_Time' => 'Fire Defense Buff',
                'Buff_EarthDefence_Time' => 'Earth Defense Buff',
                'Buff_WaterDefence_Time' => 'Water Defense Buff',
                'Buff_WindDefence_Time' => 'Wind Defense Buff',
                'Buff_SoulDefence_Time' => 'Soul Defense Buff',
                'Buff_Str_Time' => 'Strength Buff',
                'Buff_Dex_Time' => 'Dexterity Buff',
                'Buff_Wis_Time' => 'Wisdom Buff',
                'Buff_Int_Time' => 'Intelligence Buff',
                'Buff_FireAttack_Time' => 'Fire Attack Buff',
                'Buff_EarthAttack_Time' => 'Earth Attack Buff',
                'Buff_WaterAttack_Time' => 'Water Attack Buff',
                'Buff_WindAttack_Time' => 'Wind Attack Buff',
                'Buff_Hero_Time' => 'Hero Buff',
                'Buff_Life_Time' => 'Life Buff',
                'DragonRaid_Buff' => 'Dragon Raid Buff',
                'EinhasadGraceTime' => 'Einhasad\'s Grace',
                'EMETime' => 'Emerald Buff',
                'EMETime2' => 'Emerald Buff 2',
                'PUPLETime' => 'Purple Buff',
                'TOPAZTime' => 'Topaz Buff',
                'ThirdSkillTime' => 'Third Skill Buff',
                'FiveSkillTime' => 'Five Skill Buff',
                'SurvivalTime' => 'Survival Buff',
                'TamEndTime' => 'Tam End Buff'
            ];
            
            // Check if at least one buff is active
            $hasActiveBuffs = false;
            foreach ($buffFields as $field => $name) {
                if (!empty($character[$field]) && $character[$field] != '0000-00-00 00:00:00') {
                    $hasActiveBuffs = true;
                    break;
                }
            }
            
            if (!$hasActiveBuffs): 
            ?>
                <div class="empty-state">No active buffs for this character.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Buff Name</th>
                                <th>Expires At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($buffFields as $field => $name):
                                if (!empty($character[$field]) && $character[$field] != '0000-00-00 00:00:00'):
                                    // Check if buff is still active
                                    $expires = strtotime($character[$field]);
                                    $now = time();
                                    $isActive = $expires > $now;
                            ?>
                                <tr>
                                    <td><?php echo $name; ?></td>
                                    <td><?php echo date('M j, Y H:i:s', $expires); ?></td>
                                    <td>
                                        <?php if ($isActive): ?>
                                            <span class="status-active">Active</span>
                                        <?php else: ?>
                                            <span class="status-expired">Expired</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php 
                                endif;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Character Actions Section -->
    <div class="admin-table-header">
        <h3 class="admin-table-title">Character Actions</h3>
        <div class="admin-table-actions">
            <?php if ($character['OnlineStatus']): ?>
                <button type="button" class="btn btn-warning" disabled>
                    <i class="fas fa-power-off btn-icon"></i> Kick Offline
                </button>
            <?php else: ?>
                <a href="<?php echo $adminBaseUrl; ?>pages/accounts/character-edit.php?id=<?php echo $character['objid']; ?>" class="btn btn-primary">
                    <i class="fas fa-edit btn-icon"></i> Edit Character
                </a>
            <?php endif; ?>
            
            <?php if ($character['Banned']): ?>
                <button type="button" class="btn btn-success">
                    <i class="fas fa-undo btn-icon"></i> Unban Character
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-danger">
                    <i class="fas fa-ban btn-icon"></i> Ban Character
                </button>
            <?php endif; ?>
            
            <a href="<?php echo $adminBaseUrl; ?>pages/accounts/character-inventory.php?id=<?php echo $character['objid']; ?>" class="btn btn-secondary">
                <i class="fas fa-box-open btn-icon"></i> View Inventory
            </a>
        </div>
    </div>
    
    <!-- Back Button Section -->
    <div class="form-buttons">
        <a href="<?php echo $adminBaseUrl; ?>pages/accounts/account-list.php" class="btn btn-secondary">Back to Account List</a>
    </div>

</div>

<?php
/**
 * Get clan rank name based on rank ID
 * 
 * @param int $rankId Clan rank ID
 * @return string Clan rank name
 */
function getClanRankName($rankId) {
    switch ($rankId) {
        case 10:
            return 'Ruler';
        case 9:
        case 8:
            return 'Submaster';
        case 3:
        case 2:
            return 'Knight';
        case 1:
        case 0:
        default:
            return 'Member';
    }
}
?>

<!-- Include the character detail JavaScript -->
<script src="<?php echo $adminBaseUrl; ?>assets/js/character-detail.js"></script>

<?php
// Include admin footer
include '../../includes/admin-footer.php';
?>