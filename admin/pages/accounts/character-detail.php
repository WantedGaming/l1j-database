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
                    <img src="<?php echo getClassImagePath($charData['Class'], $charData['gender']); ?>" 
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
        
        <!-- Tab Content: Inventory, Skills, History -->
        <div class="tab-content" id="inventory-tab">
            <div class="detail-columns">
                <div class="stats-combined-card" style="grid-column: span 3; text-align: center; padding: 30px;">
                    <i class="fas fa-box-open" style="font-size: 48px; color: var(--accent); margin-bottom: 20px;"></i>
                    <h3>Inventory Feature Coming Soon</h3>
                    <p>This functionality will be available in the next update.</p>
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
