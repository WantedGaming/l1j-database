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

<link rel="stylesheet" href="<?php echo $adminBaseUrl; ?>assets/css/account-styles.css">

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
                <a href="<?php echo $adminBaseUrl; ?>pages/accounts/account-list.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left btn-icon"></i> Back to Accounts
                </a>
            </div>
        </div>
    </div>
    
    <!-- Character Profile Section -->
    <div class="admin-form-container">
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
            </div>
        </div>
        
        <!-- Stats Grid -->
        <div class="stat-grid">
            <!-- Basic Stats -->
            <div class="stat-box">
                <h3 class="stat-box-title">Basic Information</h3>
                <div class="stat-list">
                    <div class="stat-item">
                        <span class="stat-label">Character ID</span>
                        <span class="stat-value"><?php echo $character['objid']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Type</span>
                        <span class="stat-value"><?php echo $character['Type']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Status</span>
                        <span class="stat-value"><?php echo $character['Status']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Title</span>
                        <span class="stat-value"><?php echo !empty($character['Title']) ? htmlspecialchars($character['Title']) : 'None'; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Access Level</span>
                        <span class="stat-value"><?php echo getAccessLevelName($character['AccessLevel']); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Online Status</span>
                        <span class="stat-value"><?php echo $character['OnlineStatus'] ? 'Online' : 'Offline'; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Combat Stats -->
            <div class="stat-box">
                <h3 class="stat-box-title">Combat Stats</h3>
                <div class="stat-list">
                    <div class="stat-item">
                        <span class="stat-label">HP</span>
                        <span class="stat-value"><?php echo $character['CurHp']; ?> / <?php echo $character['MaxHp']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">MP</span>
                        <span class="stat-value"><?php echo $character['CurMp']; ?> / <?php echo $character['MaxMp']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">AC</span>
                        <span class="stat-value"><?php echo $character['Ac']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Experience</span>
                        <span class="stat-value"><?php echo number_format($character['Exp']); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">EXP Recovery</span>
                        <span class="stat-value"><?php echo $character['ExpRes']; ?>%</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Alignment</span>
                        <span class="stat-value"><?php echo number_format($character['Alignment']); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Attributes -->
            <div class="stat-box">
                <h3 class="stat-box-title">Attributes</h3>
                <div class="stat-list">
                    <div class="stat-item">
                        <span class="stat-label">STR</span>
                        <span class="stat-value">
                            <?php echo $character['Str']; ?> 
                            <?php if ($character['Str'] != $character['BaseStr']): ?>
                                (Base: <?php echo $character['BaseStr']; ?>)
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">DEX</span>
                        <span class="stat-value">
                            <?php echo $character['Dex']; ?> 
                            <?php if ($character['Dex'] != $character['BaseDex']): ?>
                                (Base: <?php echo $character['BaseDex']; ?>)
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">CON</span>
                        <span class="stat-value">
                            <?php echo $character['Con']; ?> 
                            <?php if ($character['Con'] != $character['BaseCon']): ?>
                                (Base: <?php echo $character['BaseCon']; ?>)
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">WIS</span>
                        <span class="stat-value">
                            <?php echo $character['Wis']; ?> 
                            <?php if ($character['Wis'] != $character['BaseWis']): ?>
                                (Base: <?php echo $character['BaseWis']; ?>)
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">INT</span>
                        <span class="stat-value">
                            <?php echo $character['Intel']; ?> 
                            <?php if ($character['Intel'] != $character['BaseIntel']): ?>
                                (Base: <?php echo $character['BaseIntel']; ?>)
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">CHA</span>
                        <span class="stat-value">
                            <?php echo $character['Cha']; ?> 
                            <?php if ($character['Cha'] != $character['BaseCha']): ?>
                                (Base: <?php echo $character['BaseCha']; ?>)
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Location Information -->
            <div class="stat-box">
                <h3 class="stat-box-title">Location</h3>
                <div class="stat-list">
                    <div class="stat-item">
                        <span class="stat-label">Map ID</span>
                        <span class="stat-value"><?php echo $character['MapID']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">X Coordinate</span>
                        <span class="stat-value"><?php echo $character['LocX']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Y Coordinate</span>
                        <span class="stat-value"><?php echo $character['LocY']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Heading</span>
                        <span class="stat-value"><?php echo $character['Heading']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Hometown</span>
                        <span class="stat-value"><?php echo $character['HomeTownID']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Food</span>
                        <span class="stat-value"><?php echo $character['Food']; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Additional Stats -->
            <div class="stat-box">
                <h3 class="stat-box-title">PvP Statistics</h3>
                <div class="stat-list">
                    <div class="stat-item">
                        <span class="stat-label">PK Count</span>
                        <span class="stat-value"><?php echo $character['PKcount']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Karma</span>
                        <span class="stat-value"><?php echo $character['Karma']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Last PK</span>
                        <span class="stat-value"><?php echo $character['LastPk'] ? date('M j, Y H:i:s', strtotime($character['LastPk'])) : 'Never'; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Player Kills</span>
                        <span class="stat-value"><?php echo $character['PC_Kill'] ?? 0; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Player Deaths</span>
                        <span class="stat-value"><?php echo $character['PC_Death'] ?? 0; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">K/D Ratio</span>
                        <span class="stat-value">
                            <?php 
                                $pcKill = $character['PC_Kill'] ?? 0;
                                $pcDeath = $character['PC_Death'] ?? 1; // Avoid division by zero
                                echo number_format($pcKill / max(1, $pcDeath), 2); 
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Timestamps -->
            <div class="stat-box">
                <h3 class="stat-box-title">Time Information</h3>
                <div class="stat-list">
                    <div class="stat-item">
                        <span class="stat-label">Last Login</span>
                        <span class="stat-value"><?php echo $character['lastLoginTime'] ? date('M j, Y H:i:s', strtotime($character['lastLoginTime'])) : 'Never'; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Last Logout</span>
                        <span class="stat-value"><?php echo $character['lastLogoutTime'] ? date('M j, Y H:i:s', strtotime($character['lastLogoutTime'])) : 'Never'; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Delete Time</span>
                        <span class="stat-value"><?php echo $character['DeleteTime'] ? date('M j, Y H:i:s', strtotime($character['DeleteTime'])) : 'Not Scheduled'; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Hell Time</span>
                        <span class="stat-value"><?php echo $character['HellTime'] ? $character['HellTime'] . ' seconds' : 'None'; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Birthday</span>
                        <span class="stat-value"><?php echo $character['BirthDay'] ? $character['BirthDay'] : 'Not Set'; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Ban Status</span>
                        <span class="stat-value"><?php echo $character['Banned'] ? 'Banned' : 'Not Banned'; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Special Status Section -->
        <div class="detail-section">
            <h2 class="detail-section-title">Special Statuses & Buffs</h2>
            
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
                <p style="color: #999; text-align: center; padding: 20px;">No active buffs for this character.</p>
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
                                            <span style="color: #28a745;">Active</span>
                                        <?php else: ?>
                                            <span style="color: #dc3545;">Expired</span>
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
        
        <div class="form-buttons">
            <a href="<?php echo $adminBaseUrl; ?>pages/accounts/account-list.php" class="btn btn-secondary">Back to Account List</a>
        </div>
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

<?php
// Include admin footer
include '../../includes/admin-footer.php';
?>