<?php
/**
 * Character Detail Page
 * Displays comprehensive character information with improved UI
 */

require_once '../../../includes/db_connect.php';
require_once '../../includes/admin-config.php';
require_once '../../includes/account-functions.php';

$currentAdminPage = 'accounts';
$pageTitle = 'Character Details';

// Define website base URL (root URL) from admin base URL
$websiteBaseUrl = preg_replace('/\/admin(\/.*)?$/', '/', $adminBaseUrl);

// Get character ID from URL
$charId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$charId) {
    echo "Invalid character ID";
    exit;
}

// Get character data
$sql = "SELECT * FROM characters WHERE objid = $charId";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo "Character not found";
    exit;
}

$charData = $result->fetch_assoc();

// Get other characters from this account
$accountName = $charData['account_name'];
$altCharacters = getAccountCharacters($conn, $accountName);

// Get map name using MapID if available
$mapName = "Unknown";
if (!empty($charData['MapID'])) {
    $mapSql = "SELECT locationname FROM mapids WHERE mapid = " . $charData['MapID'];
    $mapResult = $conn->query($mapSql);
    if ($mapResult && $mapResult->num_rows > 0) {
        $mapData = $mapResult->fetch_assoc();
        $mapName = $mapData['locationname'];
    }
}

// Function to calculate progress percentages
function calculateProgress($current, $max) {
    return ($max > 0) ? ($current / $max * 100) : 0;
}

// Include admin header
include '../../includes/admin-header.php';
?>

<link rel="stylesheet" href="<?php echo $adminBaseUrl; ?>assets/css/character-detail.css">

<div class="container">
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Character Profile</h1>
                <div class="hero-actions">
                    <a href="account-list.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Accounts
                    </a>
                    <a href="account-detail.php?name=<?php echo urlencode($accountName); ?>" class="btn btn-primary">
                        <i class="fas fa-user"></i> View Account
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="character-main-card">
        <div class="character-header">
            <div class="character-title-container">
                <div class="character-title-section">
                    <img src="<?php echo $websiteBaseUrl; ?>assets/img/placeholders/class/header/<?php echo $charData['Class']; ?>_<?php echo $charData['gender']; ?>.png" 
                         class="character-avatar"
                         alt="<?php echo getClassName($charData['Class'], $charData['gender']); ?>">
                    <div class="character-meta-info">
                        <h1 class="admin-hero-title"><?php echo htmlspecialchars($charData['char_name']); ?></h1>
                        <div class="account-info-badge">
                            <span class="account-access">Account: <?php echo htmlspecialchars($charData['account_name']); ?></span>
                            <span class="account-status active">Lv. <?php echo $charData['level']; ?> (Max: <?php echo $charData['HighLevel']; ?>)</span>
                            <span class="account-status active"><?php echo getClassName($charData['Class'], $charData['gender']); ?></span>
                            <?php if($charData['Title']): ?>
                            <span class="text-accent">"<?php echo htmlspecialchars($charData['Title']); ?>"</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Admin action buttons -->
                        <div class="action-buttons">
                            <button class="btn-action" onclick="openEditModal('<?php echo $charId; ?>')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn-action">
                                <i class="fas fa-envelope"></i> Message
                            </button>
                            <button class="btn-action">
                                <i class="fas fa-ban"></i> Ban
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Activity information in header -->
                <div class="character-activity-info">
                    <div class="activity-row">
                        <div class="activity-item">
                            <div class="activity-label">Last Logout:</div>
                            <div class="activity-value"><?php echo $charData['lastLogoutTime'] ? formatTimeUSA($charData['lastLogoutTime']) : 'N/A'; ?></div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-label">Last Login:</div>
                            <div class="activity-value"><?php echo $charData['lastLoginTime'] ? formatTimeUSA($charData['lastLoginTime']) : 'N/A'; ?></div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-label">Online Status:</div>
                            <div class="activity-value"><?php echo $charData['OnlineStatus'] ? '<span class="status-online">Online</span>' : '<span class="status-offline">Offline</span>'; ?></div>
                        </div>
                    </div>
                    <div class="activity-row">
                        <div class="activity-item tooltip">
                            <div class="activity-label">Tam End Time:</div>
                            <div class="activity-value"><?php echo $charData['TamEndTime'] ? formatTimeUSA($charData['TamEndTime']) : 'N/A'; ?></div>
                            <span class="tooltip-text">Special buff duration remaining for this character</span>
                        </div>
                        <div class="activity-item tooltip">
                            <div class="activity-label">TOPAZ Time:</div>
                            <div class="activity-value"><?php echo $charData['TOPAZTime'] ? formatTimeUSA($charData['TOPAZTime']) : 'N/A'; ?></div>
                            <span class="tooltip-text">Premium currency boost remaining</span>
                        </div>
                        <div class="activity-item tooltip">
                            <div class="activity-label">Ein Grace Time:</div>
                            <div class="activity-value"><?php echo $charData['EinhasadGraceTime'] ? formatTimeUSA($charData['EinhasadGraceTime']) : 'N/A'; ?></div>
                            <span class="tooltip-text">Divine protection remaining</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab Navigation -->
        <div class="character-tabs">
            <div class="character-tab active" data-tab="stats">Statistics</div>
            <div class="character-tab" data-tab="details">Character Details</div>
            <div class="character-tab" data-tab="account">Account Characters</div>
            <div class="character-tab" data-tab="clan">Clan Information</div>
            <div class="character-tab" data-tab="location">Location</div>
            <div class="character-tab" data-tab="inventory">Inventory</div>
            <div class="character-tab" data-tab="skills">Skills</div>
            <div class="character-tab" data-tab="history">History</div>
        </div>
        
        <!-- Tab Content: Stats -->
        <div class="tab-content active" id="stats-tab">
            <!-- Row 1: Character Stats -->
            <div class="attributes-grid">
                <!-- Row 1: Level, HP, MP, AC, PVP, Karma -->
                <div class="attribute-card">
                    <div class="account-info-label">Level</div>
                    <div class="attribute-value">
                        <?php echo $charData['level']; ?> <small>(Max: <?php echo $charData['HighLevel']; ?>)</small>
                    </div>
                </div>
                
                <div class="attribute-card">
                    <div class="account-info-label">HP</div>
                    <div class="attribute-value">
                        <?php echo $charData['CurHp']; ?> / <?php echo $charData['MaxHp']; ?>
                    </div>
                </div>
                
                <div class="attribute-card">
                    <div class="account-info-label">MP</div>
                    <div class="attribute-value">
                        <?php echo $charData['CurMp']; ?> / <?php echo $charData['MaxMp']; ?>
                    </div>
                </div>
                
                <div class="attribute-card">
                    <div class="account-info-label">AC</div>
                    <div class="attribute-value">
                        <?php echo $charData['Ac']; ?>
                    </div>
                </div>
                
                <div class="attribute-card">
                    <div class="account-info-label">PvP</div>
                    <div class="attribute-value">
                        K: <?php echo $charData['PC_Kill']; ?> / D: <?php echo $charData['PC_Death']; ?>
                    </div>
                    <div class="additional-info">
                        KDR: <?php echo ($charData['PC_Death'] > 0) ? round($charData['PC_Kill'] / $charData['PC_Death'], 2) : $charData['PC_Kill']; ?>
                    </div>
                </div>
                
                <div class="attribute-card">
                    <div class="account-info-label">Karma</div>
                    <div class="attribute-value">
                        <?php echo $charData['Karma']; ?>
                        <?php if($charData['Karma'] < 0): ?>
                        <span class="badge">PK</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Row 2: Strength, Dexterity, Constitution, Intelligence, Wisdom, Charisma -->
                <div class="attribute-card">
                    <div class="account-info-label">Strength</div>
                    <div class="attribute-value">
                        <?php echo $charData['Str']; ?> <small>(<?php echo $charData['BaseStr']; ?>)</small>
                    </div>
                </div>
                
                <div class="attribute-card">
                    <div class="account-info-label">Dexterity</div>
                    <div class="attribute-value">
                        <?php echo $charData['Dex']; ?> <small>(<?php echo $charData['BaseDex']; ?>)</small>
                    </div>
                </div>
                
                <div class="attribute-card">
                    <div class="account-info-label">Constitution</div>
                    <div class="attribute-value">
                        <?php echo $charData['Con']; ?> <small>(<?php echo $charData['BaseCon']; ?>)</small>
                    </div>
                </div>
                
                <div class="attribute-card">
                    <div class="account-info-label">Intelligence</div>
                    <div class="attribute-value">
                        <?php echo $charData['Intel']; ?> <small>(<?php echo $charData['BaseIntel']; ?>)</small>
                    </div>
                </div>
                
                <div class="attribute-card">
                    <div class="account-info-label">Wisdom</div>
                    <div class="attribute-value">
                        <?php echo $charData['Wis']; ?> <small>(<?php echo $charData['BaseWis']; ?>)</small>
                    </div>
                </div>
                
                <div class="attribute-card">
                    <div class="account-info-label">Charisma</div>
                    <div class="attribute-value">
                        <?php echo $charData['Cha']; ?> <small>(<?php echo $charData['BaseCha']; ?>)</small>
                    </div>
                </div>
            </div>
            
            <!-- Combat Statistics -->
            <div class="detail-columns">
                <div class="stats-combined-card">
                    <div class="card-header">
                        <h3 class="admin-card-title">Combat Statistics</h3>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">PvP Kills</div>
                        <div class="account-info-value"><?php echo $charData['PC_Kill']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">PvP Deaths</div>
                        <div class="account-info-value"><?php echo $charData['PC_Death']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Kill/Death Ratio</div>
                        <div class="account-info-value">
                            <?php echo ($charData['PC_Death'] > 0) ? round($charData['PC_Kill'] / $charData['PC_Death'], 2) : $charData['PC_Kill']; ?>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">PK Count</div>
                        <div class="account-info-value"><?php echo $charData['PKcount']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Karma</div>
                        <div class="account-info-value"><?php echo $charData['Karma']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Hell Time</div>
                        <div class="account-info-value"><?php echo $charData['HellTime']; ?></div>
                    </div>
                </div>
                
                <div class="stats-combined-card">
                    <div class="card-header">
                        <h3 class="admin-card-title">Game Bonuses</h3>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Bonus Status</div>
                        <div class="account-info-value"><?php echo $charData['BonusStatus']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Elixir Status</div>
                        <div class="account-info-value"><?php echo $charData['ElixirStatus']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Ein Point</div>
                        <div class="account-info-value"><?php echo $charData['EinPoint']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Tam End Time</div>
                        <div class="account-info-value">
                            <?php echo $charData['TamEndTime'] ? formatTimeUSA($charData['TamEndTime']) : 'N/A'; ?>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">TOPAZ Time</div>
                        <div class="account-info-value">
                            <?php echo $charData['TOPAZTime'] ? formatTimeUSA($charData['TOPAZTime']) : 'N/A'; ?>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Ein Grace Time</div>
                        <div class="account-info-value">
                            <?php echo $charData['EinhasadGraceTime'] ? formatTimeUSA($charData['EinhasadGraceTime']) : 'N/A'; ?>
                        </div>
                    </div>
                </div>
                
                <div class="stats-combined-card">
                    <div class="card-header">
                        <h3 class="admin-card-title">Account Info</h3>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Account</div>
                        <div class="account-info-value">
                            <a href="account-detail.php?name=<?php echo urlencode($accountName); ?>">
                                <?php echo htmlspecialchars($charData['account_name']); ?>
                            </a>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Character Count</div>
                        <div class="account-info-value"><?php echo count($altCharacters); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Access Level</div>
                        <div class="account-info-value"><?php echo getAccessLevelName($charData['AccessLevel']); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Character Created</div>
                        <div class="account-info-value">
                            <?php echo $charData['BirthDay'] ? date('Y-m-d', $charData['BirthDay']) : 'Unknown'; ?>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Last Login</div>
                        <div class="account-info-value">
                            <?php echo $charData['lastLoginTime'] ? formatTimeUSA($charData['lastLoginTime']) : 'N/A'; ?>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Last Logout</div>
                        <div class="account-info-value">
                            <?php echo $charData['lastLogoutTime'] ? formatTimeUSA($charData['lastLogoutTime']) : 'N/A'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab Content: Character Details -->
        <div class="tab-content" id="details-tab">
            <div class="detail-columns">
                <div class="stats-combined-card">
                    <div class="card-header">
                        <h3 class="admin-card-title">Character Profile</h3>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Class</div>
                        <div class="account-info-value"><?php echo getClassName($charData['Class'], $charData['gender']); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Gender</div>
                        <div class="account-info-value"><?php echo $charData['gender'] == 0 ? 'Male' : 'Female'; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Birthday</div>
                        <div class="account-info-value">
                            <?php echo $charData['BirthDay'] ? date('Y-m-d', $charData['BirthDay']) : 'Unknown'; ?>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Alignment</div>
                        <div class="account-info-value"><?php echo $charData['Alignment']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Title</div>
                        <div class="account-info-value"><?php echo htmlspecialchars($charData['Title']); ?></div>
                    </div>
                </div>
                
                <div class="stats-combined-card">
                    <div class="card-header">
                        <h3 class="admin-card-title">Stats Breakdown</h3>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Base Strength</div>
                        <div class="account-info-value"><?php echo $charData['BaseStr']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Base Constitution</div>
                        <div class="account-info-value"><?php echo $charData['BaseCon']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Base Dexterity</div>
                        <div class="account-info-value"><?php echo $charData['BaseDex']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Base Charisma</div>
                        <div class="account-info-value"><?php echo $charData['BaseCha']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Base Intelligence</div>
                        <div class="account-info-value"><?php echo $charData['BaseIntel']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Base Wisdom</div>
                        <div class="account-info-value"><?php echo $charData['BaseWis']; ?></div>
                    </div>
                </div>
                
                <div class="stats-combined-card">
                    <div class="card-header">
                        <h3 class="admin-card-title">Game Statistics</h3>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Current Level</div>
                        <div class="account-info-value"><?php echo $charData['level']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Highest Level</div>
                        <div class="account-info-value"><?php echo $charData['HighLevel']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Experience</div>
                        <div class="account-info-value"><?php echo $charData['Exp']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Alignment</div>
                        <div class="account-info-value"><?php echo $charData['Alignment']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Food</div>
                        <div class="account-info-value"><?php echo $charData['Food']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="account-info-label">Survival Time</div>
                        <div class="account-info-value">
                            <?php echo $charData['SurvivalTime'] ? formatTimeUSA($charData['SurvivalTime']) : 'N/A'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab Content: Account Characters -->
        <div class="tab-content" id="account-tab">
            <div class="detail-columns">
                <div class="character-list-card" style="grid-column: span 3;">
                    <div class="card-header">
                        <h3 class="admin-card-title">Account Characters (<?php echo count($altCharacters); ?>)</h3>
                        <a href="account-detail.php?name=<?php echo urlencode($accountName); ?>" class="btn-action">
                            <i class="fas fa-user"></i> View Account
                        </a>
                    </div>
                    <div class="character-list-scroll">
                        <?php foreach($altCharacters as $altChar): ?>
                        <div class="compact-character-item <?php echo ($altChar['objid'] == $charId) ? 'current' : ''; ?>">
                            <div class="compact-character-info">
                                <div class="character-name">
                                    <a href="character-detail.php?id=<?php echo $altChar['objid']; ?>">
                                        <?php echo htmlspecialchars($altChar['char_name']); ?>
                                        <?php if($altChar['objid'] == $charId): ?>
                                        <span class="badge">Current</span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <div class="character-class"><?php echo getClassName($altChar['Class'], $altChar['gender']); ?></div>
                                <div class="text-right">Lv. <?php echo $altChar['level']; ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab Content: Clan Information -->
        <div class="tab-content" id="clan-tab">
            <div class="detail-columns">
                <div class="stats-combined-card" style="grid-column: span 3;">
                    <div class="card-header">
                        <h3 class="admin-card-title">Clan Information</h3>
                    </div>
                    
                    <?php if($charData['ClanID']): ?>
                    <div class="detail-columns" style="padding: 0;">
                        <div class="stats-combined-card">
                            <div class="stat-item">
                                <div class="account-info-label">Clan ID</div>
                                <div class="account-info-value"><?php echo $charData['ClanID']; ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="account-info-label">Clan Name</div>
                                <div class="account-info-value"><?php echo htmlspecialchars($charData['Clanname']); ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="account-info-label">Title</div>
                                <div class="account-info-value"><?php echo htmlspecialchars($charData['Title']); ?></div>
                            </div>
                        </div>
                        
                        <div class="stats-combined-card">
                            <div class="stat-item">
                                <div class="account-info-label">Clan Rank</div>
                                <div class="account-info-value"><?php echo $charData['ClanRank']; ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="account-info-label">Pledge Join Date</div>
                                <div class="account-info-value">
                                    <?php echo $charData['pledgeJoinDate'] ? date('Y-m-d', $charData['pledgeJoinDate']) : 'N/A'; ?>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="account-info-label">Pledge Rank Date</div>
                                <div class="account-info-value">
                                    <?php echo $charData['pledgeRankDate'] ? date('Y-m-d', $charData['pledgeRankDate']) : 'N/A'; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stats-combined-card">
                            <div class="stat-item">
                                <div class="account-info-label">Clan Contribution</div>
                                <div class="account-info-value"><?php echo $charData['ClanContribution']; ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="account-info-label">Weekly Contribution</div>
                                <div class="account-info-value"><?php echo $charData['ClanWeekContribution']; ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="account-info-label">Contribution Ratio</div>
                                <div class="account-info-value">
                                    <?php 
                                    // Just a placeholder calculation
                                    echo $charData['ClanWeekContribution'] ? 
                                         round($charData['ClanContribution'] / $charData['ClanWeekContribution'], 2) : 
                                         $charData['ClanContribution']; 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="action-buttons" style="justify-content: center; margin-top: 25px;">
                        <button class="btn-action">
                            <i class="fas fa-users"></i> View Clan Members
                        </button>
                        <button class="btn-action">
                            <i class="fas fa-castle"></i> View Clan Castle
                        </button>
                        <button class="btn-action">
                            <i class="fas fa-scroll"></i> Clan History
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="stat-item">
                        <div class="account-info-value" style="text-align: center; width: 100%; padding: 20px 0;">
                            This character is not in a clan.
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Tab Content: Location -->
        <div class="tab-content" id="location-tab">
            <div class="detail-columns">
                <div class="map-preview" style="grid-column: span 3;">
                    <div class="card-header">
                        <h3 class="admin-card-title">Last Logout Location</h3>
                    </div>
                    
                    <div class="map-visual" style="height: 400px;">
                        <div class="map-coordinates">
                            Map <?php echo $charData['MapID']; ?>: <?php echo $mapName; ?><br>
                            X: <?php echo $charData['LocX']; ?>, Y: <?php echo $charData['LocY']; ?>
                        </div>
                    </div>
                    
                    <div class="detail-columns" style="padding: 15px 0 0 0;">
                        <div class="stats-combined-card">
                            <div class="stat-item">
                                <div class="account-info-label">Map ID</div>
                                <div class="account-info-value"><?php echo $charData['MapID']; ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="account-info-label">Location Name</div>
                                <div class="account-info-value"><?php echo $mapName; ?></div>
                            </div>
                        </div>
                        
                        <div class="stats-combined-card">
                            <div class="stat-item">
                                <div class="account-info-label">X Coordinate</div>
                                <div class="account-info-value"><?php echo $charData['LocX']; ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="account-info-label">Y Coordinate</div>
                                <div class="account-info-value"><?php echo $charData['LocY']; ?></div>
                            </div>
                        </div>
                        
                        <div class="stats-combined-card">
                            <div class="stat-item">
                                <div class="account-info-label">Survival Time</div>
                                <div class="account-info-value">
                                    <?php echo $charData['SurvivalTime'] ? formatTimeUSA($charData['SurvivalTime']) : 'N/A'; ?>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="account-info-label">Last Logout</div>
                                <div class="account-info-value">
                                    <?php echo $charData['lastLogoutTime'] ? formatTimeUSA($charData['lastLogoutTime']) : 'N/A'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab Content: Inventory - UPDATED -->
        <div class="tab-content" id="inventory-tab">
            <!-- New inventory layout with side-by-side cards -->
            <div class="inventory-tab-container">
                <!-- Equipment Card -->
                <div class="equipment-card">
                    <div class="card-header">
                        <h3 class="admin-card-title">Equipment</h3>
                        <div class="badge">
                            <i class="fas fa-info-circle"></i> Slot Information
                        </div>
                    </div>
                    
                    <?php
                    // Get character equipment from the character_items table
                    // Join with armor, weapon, and etcitem tables to get the icon IDs
                    $equipmentSql = "
                        SELECT ci.*, 
                               COALESCE(a.iconId, w.iconId, e.iconId) AS iconId,
                               COALESCE(a.desc_en, w.desc_en, e.desc_en, ci.item_name) AS item_display_name,
                               COALESCE(a.type, w.type, e.item_type) AS item_type
                        FROM character_items ci
                        LEFT JOIN armor a ON ci.item_id = a.item_id
                        LEFT JOIN weapon w ON ci.item_id = w.item_id
                        LEFT JOIN etcitem e ON ci.item_id = e.item_id
                        WHERE ci.char_id = $charId AND ci.is_equipped = 1";
                    
                    $equipmentResult = $conn->query($equipmentSql);
                    $equippedItems = [];
                    
                    if ($equipmentResult && $equipmentResult->num_rows > 0) {
                        while ($item = $equipmentResult->fetch_assoc()) {
                            $equippedItems[] = $item;
                        }
                    }
                    
                    // Get character's additional slot values
                    $ringAddSlots = (int)$charData['RingAddSlot'];
                    $earringAddSlots = (int)$charData['EarringAddSlot'];
                    $badgeAddSlots = (int)$charData['BadgeAddSlot'];
                    $shoulderAddSlots = (int)$charData['ShoulderAddSlot'];
                    $characterLevel = (int)$charData['level'];
                    ?>
                    
                    <div class="equipment-slots">
                        <div class="character-paper-doll">
                            <div class="paper-doll-silhouette">
                                <img src="<?php echo $websiteBaseUrl; ?>assets/img/placeholders/class/<?php echo $charData['Class']; ?>_<?php echo $charData['gender']; ?>.png" alt="Character Silhouette">
                            </div>
                        </div>
                        
                        <div class="equipment-grid">
                            <?php
                            // Helper function to determine slot status based on character level and additional slots
                            function getSlotStatus($type, $level, $levelReq, $addSlots, $slotIndex) {
                                // Default slots that are always open
                                if ($levelReq === 0) {
                                    return 'open';
                                }
                                
                                // Check for level-based unlocking
                                if ($level >= $levelReq) {
                                    return 'open';
                                }
                                
                                // Check for additional slots based on type
                                if ($type === 'RING' && $slotIndex <= $addSlots) {
                                    return 'open';
                                } else if ($type === 'EARRING' && $slotIndex <= $addSlots) {
                                    return 'open';
                                } else if ($type === 'SHOULDER' && $slotIndex <= $addSlots) {
                                    return 'open';
                                } else if ($type === 'SENTENCE' && $slotIndex <= $addSlots) { // Badge
                                    return 'open';
                                }
                                
                                // Slot is locked
                                return 'locked';
                            }
                            
                            // Helper function to determine if a slot should match an item
                            function itemMatchesSlot($item, $slotTypes) {
                                if (empty($item['item_type'])) {
                                    return false;
                                }
                                
                                $itemType = strtoupper($item['item_type']);
                                $slotTypeArray = explode(',', $slotTypes);
                                
                                foreach ($slotTypeArray as $slotType) {
                                    $slotType = trim($slotType);
                                    if ($itemType == $slotType || stripos($itemType, $slotType) !== false) {
                                        return true;
                                    }
                                }
                                
                                // Special case for weapons
                                if ($slotTypes == 'WEAPON' && stripos($item['item_display_name'], 'weapon') !== false) {
                                    return true;
                                }
                                
                                return false;
                            }
                            
                            // Equipment slot definitions organized according to character-detail-equipment-card.txt
                            // All unlock levels and requirements now based on character data
                            $equipmentSections = [
                                'left-top' => [
                                    'title' => 'Left Top',
                                    'slots' => [
                                        'earring1' => ['name' => 'Earring', 'icon' => 'earring', 'type' => 'EARRING', 'levelReq' => 0, 'index' => 1],
                                        'earring2' => ['name' => 'Earring', 'icon' => 'earring', 'type' => 'EARRING', 'levelReq' => 101, 'index' => 2],
                                        'insignia' => ['name' => 'Insignia', 'icon' => 'certificate', 'type' => 'INSIGNIA', 'levelReq' => 60, 'index' => 1],
                                        'gloves' => ['name' => 'Gloves', 'icon' => 'mitten', 'type' => 'GLOVE', 'levelReq' => 0, 'index' => 1]
                                    ]
                                ],
                                'middle-top' => [
                                    'title' => 'Middle Top',
                                    'slots' => [
                                        'helmet' => ['name' => 'Helmet', 'icon' => 'hard-hat', 'type' => 'HELMET', 'levelReq' => 0, 'index' => 1],
                                        'pendant' => ['name' => 'Pendant', 'icon' => 'gem', 'type' => 'PENDANT', 'levelReq' => 0, 'index' => 1],
                                        'amulet' => ['name' => 'Amulet', 'icon' => 'medal', 'type' => 'AMULET', 'levelReq' => 0, 'index' => 1],
                                        'empty' => ['name' => 'Empty', 'icon' => 'square', 'type' => '', 'levelReq' => 999, 'index' => 0]
                                    ]
                                ],
                                'right-top' => [
                                    'title' => 'Right Top',
                                    'slots' => [
                                        'earring3' => ['name' => 'Earring', 'icon' => 'earring', 'type' => 'EARRING', 'levelReq' => 60, 'index' => 3],
                                        'earring4' => ['name' => 'Earring', 'icon' => 'earring', 'type' => 'EARRING', 'levelReq' => 103, 'index' => 4],
                                        'shoulder' => ['name' => 'Pauldrons', 'icon' => 'shield-alt', 'type' => 'SHOULDER', 'levelReq' => 60, 'index' => 1],
                                        'cloak' => ['name' => 'Cloak', 'icon' => 'tshirt', 'type' => 'CLOAK', 'levelReq' => 0, 'index' => 1]
                                    ]
                                ],
                                'left-middle' => [
                                    'title' => 'Middle Left',
                                    'slots' => [
                                        'weapon' => ['name' => 'Weapon', 'icon' => 'sword', 'type' => 'WEAPON', 'levelReq' => 0, 'index' => 1],
                                        'empty1' => ['name' => 'Empty', 'icon' => 'square', 'type' => '', 'levelReq' => 999, 'index' => 0],
                                        'empty2' => ['name' => 'Empty', 'icon' => 'square', 'type' => '', 'levelReq' => 999, 'index' => 0],
                                        'empty3' => ['name' => 'Empty', 'icon' => 'square', 'type' => '', 'levelReq' => 999, 'index' => 0]
                                    ]
                                ],
                                'middle-middle' => [
                                    'title' => 'Middle Middle',
                                    'slots' => [
                                        'tshirt' => ['name' => 'T-shirt', 'icon' => 'tshirt', 'type' => 'T_SHIRT', 'levelReq' => 0, 'index' => 1],
                                        'armor' => ['name' => 'Armor', 'icon' => 'shield-alt', 'type' => 'ARMOR', 'levelReq' => 0, 'index' => 1],
                                        'empty1' => ['name' => 'Empty', 'icon' => 'square', 'type' => '', 'levelReq' => 999, 'index' => 0],
                                        'empty2' => ['name' => 'Empty', 'icon' => 'square', 'type' => '', 'levelReq' => 999, 'index' => 0]
                                    ]
                                ],
                                'right-middle' => [
                                    'title' => 'Middle Right',
                                    'slots' => [
                                        'shield' => ['name' => 'Shield/Guarder', 'icon' => 'shield-alt', 'type' => 'SHIELD,GARDER', 'levelReq' => 0, 'index' => 1],
                                        'empty1' => ['name' => 'Empty', 'icon' => 'square', 'type' => '', 'levelReq' => 999, 'index' => 0],
                                        'empty2' => ['name' => 'Empty', 'icon' => 'square', 'type' => '', 'levelReq' => 999, 'index' => 0],
                                        'empty3' => ['name' => 'Empty', 'icon' => 'square', 'type' => '', 'levelReq' => 999, 'index' => 0]
                                    ]
                                ],
                                'left-bottom' => [
                                    'title' => 'Bottom Left',
                                    'slots' => [
                                        'ring1' => ['name' => 'Ring', 'icon' => 'ring', 'type' => 'RING', 'levelReq' => 0, 'index' => 1],
                                        'ring2' => ['name' => 'Ring', 'icon' => 'ring', 'type' => 'RING', 'levelReq' => 60, 'index' => 2],
                                        'ring3' => ['name' => 'Ring', 'icon' => 'ring', 'type' => 'RING', 'levelReq' => 95, 'index' => 3],
                                        'rune' => ['name' => 'Rune', 'icon' => 'gem', 'type' => 'RON', 'levelReq' => 0, 'index' => 1]
                                    ]
                                ],
                                'middle-bottom' => [
                                    'title' => 'Bottom Middle',
                                    'slots' => [
                                        'belt' => ['name' => 'Belt', 'icon' => 'stream', 'type' => 'BELT', 'levelReq' => 0, 'index' => 1],
                                        'gaiters' => ['name' => 'Gaiters', 'icon' => 'socks', 'type' => 'PAIR', 'levelReq' => 0, 'index' => 1],
                                        'boots' => ['name' => 'Boots', 'icon' => 'boot', 'type' => 'BOOTS', 'levelReq' => 0, 'index' => 1],
                                        'empty' => ['name' => 'Empty', 'icon' => 'square', 'type' => '', 'levelReq' => 999, 'index' => 0]
                                    ]
                                ],
                                'right-bottom' => [
                                    'title' => 'Bottom Right',
                                    'slots' => [
                                        'ring4' => ['name' => 'Ring', 'icon' => 'ring', 'type' => 'RING', 'levelReq' => 0, 'index' => 4],
                                        'ring5' => ['name' => 'Ring', 'icon' => 'ring', 'type' => 'RING', 'levelReq' => 60, 'index' => 5],
                                        'ring6' => ['name' => 'Ring', 'icon' => 'ring', 'type' => 'RING', 'levelReq' => 100, 'index' => 6],
                                        'badge' => ['name' => 'Badge', 'icon' => 'certificate', 'type' => 'SENTENCE', 'levelReq' => 0, 'index' => 1]
                                    ]
                                ]
                            ];
                            
                            // Count unlocked slots by type for informational counters
                            $unlockedSlots = [
                                'RING' => 0,
                                'EARRING' => 0,
                                'SHOULDER' => 0,
                                'SENTENCE' => 0  // Badge
                            ];
                            
                            // Display equipment sections
                            foreach ($equipmentSections as $sectionKey => $section) {
                                echo '<div class="equipment-section" id="' . $sectionKey . '">';
                                // Removed the section title for cleaner UI
                                
                                // Count unlocked slots for this section
                                $sectionUnlockedSlots = 0;
                                $sectionTypeSlots = [
                                    'RING' => 0,
                                    'EARRING' => 0,
                                    'SHOULDER' => 0,
                                    'SENTENCE' => 0  // Badge
                                ];
                                
                                foreach ($section['slots'] as $slotKey => $slotInfo) {
                                    // Determine slot type for additional slot calculation
                                    $slotBaseType = explode(',', $slotInfo['type'])[0];
                                    $addSlots = 0;
                                    
                                    switch ($slotBaseType) {
                                        case 'RING':
                                            $addSlots = $ringAddSlots;
                                            break;
                                        case 'EARRING':
                                            $addSlots = $earringAddSlots;
                                            break;
                                        case 'SHOULDER':
                                            $addSlots = $shoulderAddSlots;
                                            break;
                                        case 'SENTENCE': // Badge
                                            $addSlots = $badgeAddSlots;
                                            break;
                                    }
                                    
                                    // Get slot status based on character level and additional slots
                                    $slotStatus = getSlotStatus($slotBaseType, $characterLevel, $slotInfo['levelReq'], $addSlots, $slotInfo['index']);
                                    
                                    // Count unlocked slots for this section
                                    if ($slotStatus === 'open' && !empty($slotInfo['type'])) {
                                        $sectionUnlockedSlots++;
                                        
                                        // Count by type
                                        if (isset($sectionTypeSlots[$slotBaseType])) {
                                            $sectionTypeSlots[$slotBaseType]++;
                                            $unlockedSlots[$slotBaseType]++;
                                        }
                                    }
                                    
                                    $slotClass = 'equipment-slot';
                                    if ($slotStatus === 'locked') {
                                        $slotClass .= ' locked';
                                    }
                                    
                                    echo '<div class="' . $slotClass . '" data-slot="' . $slotKey . '" data-type="' . $slotInfo['type'] . '">';
                                    
                                    // Find item for this slot if the slot is open
                                    if ($slotStatus === 'open' && !empty($slotInfo['type'])) {
                                        $itemInSlot = null;
                                        foreach ($equippedItems as $item) {
                                            if (itemMatchesSlot($item, $slotInfo['type'])) {
                                                $itemInSlot = $item;
                                                break;
                                            }
                                        }
                                        
                                        if ($itemInSlot) {
                                            // Show equipped item
                                            echo '<div class="item-icon tooltip" data-item-id="' . $itemInSlot['item_id'] . '">';
                                            echo '<img src="' . $websiteBaseUrl . 'assets/img/icons/icons/' . $itemInSlot['iconId'] . '.png" onerror="this.src=\'' . $websiteBaseUrl . 'assets/img/placeholders/noiconid.png\'" alt="' . htmlspecialchars($itemInSlot['item_display_name']) . '">';
                                            if ($itemInSlot['enchantlvl'] > 0) {
                                                echo '<div class="item-enchant">+' . $itemInSlot['enchantlvl'] . '</div>';
                                            }
                                            if ($itemInSlot['count'] > 1) {
                                                echo '<div class="item-count">' . $itemInSlot['count'] . '</div>';
                                            }
                                            echo '<span class="tooltip-text">';
                                            echo '<strong>' . htmlspecialchars($itemInSlot['item_display_name']) . '</strong><br>';
                                            if ($itemInSlot['enchantlvl'] > 0) {
                                                echo 'Enchant: +' . $itemInSlot['enchantlvl'] . '<br>';
                                            }
                                            if ($itemInSlot['attr_enchantlvl'] > 0) {
                                                echo 'Attribute: +' . $itemInSlot['attr_enchantlvl'] . '<br>';
                                            }
                                            if ($itemInSlot['special_enchant'] > 0) {
                                                echo 'Special: +' . $itemInSlot['special_enchant'] . '<br>';
                                            }
                                            if ($itemInSlot['durability'] > 0) {
                                                echo 'Durability: ' . $itemInSlot['durability'] . '%<br>';
                                            }
                                            echo 'Item Type: ' . $itemInSlot['item_type'] . '<br>';
                                            echo 'Item ID: ' . $itemInSlot['item_id'];
                                            echo '</span>';
                                            echo '</div>';
                                        } else {
                                            // Show empty slot
                                            echo '<div class="empty-slot tooltip">';
                                            echo '<i class="fas fa-' . $slotInfo['icon'] . '" style="font-size: 32px;"></i>';
                                            echo '<span class="tooltip-text">Empty ' . $slotInfo['name'] . ' Slot</span>';
                                            echo '</div>';
                                        }
                                        
                                        // Add unlocked via add-slot indicator if applicable
                                        if (in_array($slotBaseType, ['RING', 'EARRING', 'SHOULDER', 'SENTENCE']) && 
                                            $slotInfo['levelReq'] > 0 && $characterLevel < $slotInfo['levelReq']) {
                                            echo '<div class="slot-unlocked-info">+' . $slotInfo['index'] . '</div>';
                                        }
                                        
                                    } else if ($slotStatus === 'locked') {
                                        // Show locked slot
                                        echo '<div class="empty-slot tooltip">';
                                        if (!empty($slotInfo['icon']) && $slotInfo['icon'] != 'square') {
                                            echo '<i class="fas fa-' . $slotInfo['icon'] . '" style="font-size: 32px; opacity: 0.3;"></i>';
                                        }
                                        
                                        $tooltipText = 'Locked ' . $slotInfo['name'] . ' Slot';
                                        if (!empty($slotInfo['levelReq']) && $slotInfo['levelReq'] < 999) {
                                            echo '<div class="slot-level-req">Lv. ' . $slotInfo['levelReq'] . '+</div>';
                                            $tooltipText .= ' (Unlocks at Level ' . $slotInfo['levelReq'] . ')';
                                        }
                                        
                                        echo '<span class="tooltip-text">' . $tooltipText . '</span>';
                                        echo '</div>';
                                    } else {
                                        // Empty placeholder slot
                                        echo '<div class="empty-slot"></div>';
                                    }
                                    
                                    echo '</div>'; // Close equipment-slot
                                }
                                
                                // Add section counter for unlocked slots if there are any special slots in this section
                                $hasSpecialSlots = false;
                                $specialSlotInfo = '';
                                
                                foreach ($sectionTypeSlots as $type => $count) {
                                    if ($count > 0) {
                                        $hasSpecialSlots = true;
                                        $slotTypeName = $type === 'SENTENCE' ? 'Badge' : ucfirst(strtolower($type));
                                        $specialSlotInfo .= $slotTypeName . ': ' . $count . ' ';
                                    }
                                }
                                
                                if ($hasSpecialSlots) {
                                    echo '<span class="equipment-section-counter" title="' . trim($specialSlotInfo) . '">';
                                    echo $sectionUnlockedSlots . ' slots';
                                    echo '</span>';
                                }
                                
                                echo '</div>'; // Close equipment-section
                            }
                            ?>
                        </div>
                    </div>
                    
                    <!-- Equipment Slot Summary -->
                    <div class="equipment-summary">
                        <div class="tooltip">
                            <span><i class="fas fa-ring"></i> Rings: <?php echo $unlockedSlots['RING']; ?> unlocked</span>
                            <span class="tooltip-text">
                                Default: 2 slots<br>
                                Level 60+: +2 slots<br>
                                Level 95/100+: +2 slots<br>
                                Add Slots: +<?php echo $ringAddSlots; ?> slot(s)
                            </span>
                        </div>
                        <div class="tooltip">
                            <span><i class="fas fa-earring"></i> Earrings: <?php echo $unlockedSlots['EARRING']; ?> unlocked</span>
                            <span class="tooltip-text">
                                Default: 1 slot<br>
                                Level 60+: +1 slot<br>
                                Level 101/103+: +2 slots<br>
                                Add Slots: +<?php echo $earringAddSlots; ?> slot(s)
                            </span>
                        </div>
                        <div class="tooltip">
                            <span><i class="fas fa-shield-alt"></i> Shoulders: <?php echo $unlockedSlots['SHOULDER']; ?> unlocked</span>
                            <span class="tooltip-text">
                                Level 60+: 1 slot<br>
                                Add Slots: +<?php echo $shoulderAddSlots; ?> slot(s)
                            </span>
                        </div>
                        <div class="tooltip">
                            <span><i class="fas fa-certificate"></i> Badges: <?php echo $unlockedSlots['SENTENCE']; ?> unlocked</span>
                            <span class="tooltip-text">
                                Default: 1 slot<br>
                                Add Slots: +<?php echo $badgeAddSlots; ?> slot(s)
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Inventory Card -->
                <div class="inventory-card">
                    <div class="card-header">
                        <h3 class="admin-card-title">Inventory</h3>
                        <span class="inventory-count">
                            <?php
                            // Get inventory count
                            $inventorySql = "SELECT COUNT(*) as total FROM character_items WHERE char_id = $charId AND is_equipped = 0";
                            $inventoryResult = $conn->query($inventorySql);
                            $inventoryCount = 0;
                            if ($inventoryResult && $inventoryResult->num_rows > 0) {
                                $inventoryData = $inventoryResult->fetch_assoc();
                                $inventoryCount = $inventoryData['total'];
                            }
                            echo $inventoryCount . ' items';
                            ?>
                        </span>
                    </div>
                    
                    <div class="inventory-grid" id="inventory-grid">
                        <?php
                        // Get inventory items with icon information
                        $inventorySql = "
                            SELECT ci.*, 
                                   COALESCE(a.iconId, w.iconId, e.iconId) AS iconId,
                                   COALESCE(a.desc_en, w.desc_en, e.desc_en, ci.item_name) AS item_display_name,
                                   COALESCE(a.type, w.type, e.item_type) AS item_type
                            FROM character_items ci
                            LEFT JOIN armor a ON ci.item_id = a.item_id
                            LEFT JOIN weapon w ON ci.item_id = w.item_id
                            LEFT JOIN etcitem e ON ci.item_id = e.item_id
                            WHERE ci.char_id = $charId AND ci.is_equipped = 0 
                            ORDER BY ci.item_id
                            LIMIT 0, 24"; // Show 24 items per page (4 rows x 6 columns)
                        $inventoryResult = $conn->query($inventorySql);
                        
                        $allItems = array();
                        if ($inventoryResult && $inventoryResult->num_rows > 0) {
                            while ($item = $inventoryResult->fetch_assoc()) {
                                $allItems[] = $item;
                            }
                        }
                        
                        // Display the first page of items
                        $itemsOnPage = count($allItems);
                        foreach ($allItems as $item) {
                            echo '<div class="inventory-slot tooltip">';
                            echo '<div class="item-icon" data-item-id="' . $item['item_id'] . '">';
                            echo '<img src="' . $websiteBaseUrl . 'assets/img/icons/icons/' . $item['iconId'] . '.png" onerror="this.src=\'' . $websiteBaseUrl . 'assets/img/placeholders/noiconid.png\'" alt="' . htmlspecialchars($item['item_display_name']) . '">';
                            if ($item['count'] > 1) {
                                echo '<div class="item-count">' . $item['count'] . '</div>';
                            }
                            if ($item['enchantlvl'] > 0) {
                                echo '<div class="item-enchant">+' . $item['enchantlvl'] . '</div>';
                            }
                            echo '<span class="tooltip-text">';
                            echo '<strong>' . htmlspecialchars($item['item_display_name']) . '</strong><br>';
                            echo 'Quantity: ' . $item['count'] . '<br>';
                            if ($item['enchantlvl'] > 0) {
                                echo 'Enchant: +' . $item['enchantlvl'] . '<br>';
                            }
                            if ($item['attr_enchantlvl'] > 0) {
                                echo 'Attribute: +' . $item['attr_enchantlvl'] . '<br>';
                            }
                            if ($item['special_enchant'] > 0) {
                                echo 'Special: +' . $item['special_enchant'] . '<br>';
                            }
                            if ($item['durability'] > 0) {
                                echo 'Durability: ' . $item['durability'] . '%<br>';
                            }
                            if ($item['remaining_time'] > 0) {
                                echo 'Time Remaining: ' . formatTimeRemaining($item['remaining_time']) . '<br>';
                            }
                            echo 'Item Type: ' . $item['item_type'] . '<br>';
                            echo 'Item ID: ' . $item['item_id'];
                            echo '</span>';
                            echo '</div>';
                            echo '</div>';
                        }
                        
                        // Add empty slots to fill the grid - always 24 slots total
                        $emptySlots = 24 - $itemsOnPage;
                        for ($i = 0; $i < $emptySlots; $i++) {
                            echo '<div class="inventory-slot empty"></div>';
                        }
                        
                        // Helper function for time formatting
                        function formatTimeRemaining($seconds) {
                            if ($seconds < 60) {
                                return $seconds . ' seconds';
                            } elseif ($seconds < 3600) {
                                return floor($seconds / 60) . ' minutes';
                            } elseif ($seconds < 86400) {
                                return floor($seconds / 3600) . ' hours';
                            } else {
                                return floor($seconds / 86400) . ' days';
                            }
                        }
                        ?>
                    </div>
                    
                    <!-- Pagination controls -->
                    <div class="inventory-pagination">
                        <button class="pagination-button" id="prev-page" <?php echo $inventoryCount <= 24 ? 'disabled' : ''; ?>>
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                        <span class="pagination-info">Page <span id="current-page">1</span> of <?php echo ceil($inventoryCount / 24); ?></span>
                        <button class="pagination-button" id="next-page" <?php echo $inventoryCount <= 24 ? 'disabled' : ''; ?>>
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                        <input type="hidden" id="total-items" value="<?php echo $inventoryCount; ?>">
                        <input type="hidden" id="items-per-page" value="24">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="tab-content" id="skills-tab">
            <div class="detail-columns">
                <div class="stats-combined-card" style="grid-column: span 3; text-align: center; padding: 30px;">
                    <i class="fas fa-magic" style="font-size: 48px; color: var(--accent); margin-bottom: 20px;"></i>
                    <h3>Skills Feature Coming Soon</h3>
                    <p>This functionality will be available in the next update.</p>
                </div>
            </div>
        </div>
        
        <div class="tab-content" id="history-tab">
            <div class="detail-columns">
                <div class="stats-combined-card" style="grid-column: span 3; text-align: center; padding: 30px;">
                    <i class="fas fa-history" style="font-size: 48px; color: var(--accent); margin-bottom: 20px;"></i>
                    <h3>History Feature Coming Soon</h3>
                    <p>This functionality will be available in the next update.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Character Edit Modal (Placeholder) -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <h2>Edit Character</h2>
        <p>Edit functionality will be implemented in the next update.</p>
        <button onclick="closeModal()">Close</button>
    </div>
</div>

<script>
// Tab navigation functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.character-tab');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.character-tab').forEach(t => {
                t.classList.remove('active');
            });
            
            // Remove active class from all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Show corresponding tab content
            const tabContent = document.getElementById(this.dataset.tab + '-tab');
            if (tabContent) {
                tabContent.classList.add('active');
            }
        });
    });
    
    // Modal functions
    window.openEditModal = function(charId) {
        document.getElementById('editModal').style.display = 'block';
    };
    
    window.closeModal = function() {
        document.getElementById('editModal').style.display = 'none';
    };
});
</script>

<?php include '../../includes/admin-footer.php'; ?>
