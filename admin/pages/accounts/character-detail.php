<?php
/**
 * Enhanced Character Detail Page - No Tabs Version
 * New implementation with improved UX/UI displaying all content in a single page
 */

require_once '../../../includes/db_connect.php';
require_once '../../includes/admin-config.php';
require_once '../../includes/account-functions.php';

$currentAdminPage = 'accounts';
$pageTitle = 'Character Details';

// Define website base URL from admin base URL
$websiteBaseUrl = preg_replace('/\/admin(\/.*)?$/', '/', $adminBaseUrl);

// Get character ID from URL
$charId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$charId) {
    header("Location: account-list.php");
    exit;
}

// Get character data
$sql = "SELECT * FROM characters WHERE objid = $charId";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    header("Location: account-list.php?error=character-not-found");
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

// Get character's PVP ratio
$pvpRatio = 0;
if ($charData['PC_Death'] > 0) {
    $pvpRatio = round($charData['PC_Kill'] / $charData['PC_Death'], 2);
} else {
    $pvpRatio = $charData['PC_Kill'] > 0 ? $charData['PC_Kill'] : 0;
}

// Include admin header
include '../../includes/admin-header.php';
?>

<link rel="stylesheet" href="<?php echo $adminBaseUrl; ?>assets/css/character-detail.css">

<div class="page-container">
    <!-- Breadcrumb navigation -->
    <div class="breadcrumb">
        <a href="<?php echo $adminBaseUrl; ?>">Dashboard</a>
        <span class="separator">/</span>
        <a href="account-list.php">Accounts</a>
        <span class="separator">/</span>
        <span class="current"><?php echo htmlspecialchars($charData['char_name']); ?></span>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Character Header -->
        <div class="character-header">
            <div class="character-header-left">
                <div class="character-portrait-container">
                    <div class="character-portrait">
                        <img src="<?php echo $websiteBaseUrl; ?>assets/img/placeholders/class/<?php echo $charData['Class']; ?>_<?php echo $charData['gender']; ?>.png" 
                            alt="<?php echo getClassName($charData['Class'], $charData['gender']); ?>">
                        
                        <div class="character-level">
                            <span><?php echo $charData['level']; ?></span>
                        </div>
                    </div>
                    
                    <div class="character-status-indicator <?php echo $charData['OnlineStatus'] ? 'online' : 'offline'; ?>">
                        <?php echo $charData['OnlineStatus'] ? 'Online' : 'Offline'; ?>
                    </div>
                </div>
            </div>
            
            <div class="character-header-center">
                <h1 class="character-name"><?php echo htmlspecialchars($charData['char_name']); ?></h1>
                
                <div class="character-meta">
                    <div class="character-meta-item">
                        <i class="fas fa-user"></i>
                        <span><?php echo htmlspecialchars($accountName); ?></span>
                    </div>
                    
                    <div class="character-meta-item">
                        <i class="fas fa-hat-wizard"></i>
                        <span><?php echo getClassName($charData['Class'], $charData['gender']); ?></span>
                    </div>
                    
                    <?php if(!empty($charData['Clanname'])): ?>
                    <div class="character-meta-item">
                        <i class="fas fa-users"></i>
                        <span><?php echo htmlspecialchars($charData['Clanname']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($charData['Title'])): ?>
                    <div class="character-meta-item title">
                        <i class="fas fa-crown"></i>
                        <span><?php echo htmlspecialchars($charData['Title']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="character-stats-summary">
                    <div class="stat-pill">
                        <span class="stat-label">Level</span>
                        <span class="stat-value"><?php echo $charData['level']; ?>/<?php echo $charData['HighLevel']; ?></span>
                    </div>
                    
                    <div class="stat-pill">
                        <span class="stat-label">HP</span>
                        <span class="stat-value"><?php echo $charData['CurHp']; ?>/<?php echo $charData['MaxHp']; ?></span>
                    </div>
                    
                    <div class="stat-pill">
                        <span class="stat-label">MP</span>
                        <span class="stat-value"><?php echo $charData['CurMp']; ?>/<?php echo $charData['MaxMp']; ?></span>
                    </div>
                    
                    <div class="stat-pill">
                        <span class="stat-label">PvP</span>
                        <span class="stat-value"><?php echo $charData['PC_Kill']; ?>:<?php echo $charData['PC_Death']; ?></span>
                    </div>
                    
                    <div class="stat-pill <?php echo $charData['Karma'] < 0 ? 'negative' : ''; ?>">
                        <span class="stat-label">Karma</span>
                        <span class="stat-value"><?php echo $charData['Karma']; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="character-header-right">
                <div class="last-activity">
                    <div class="activity-item">
                        <span class="activity-label">Last Login</span>
                        <span class="activity-time"><?php echo $charData['lastLoginTime'] ? date('M d, Y H:i', strtotime($charData['lastLoginTime'])) : 'Never'; ?></span>
                    </div>
                    
                    <div class="activity-item">
                        <span class="activity-label">Last Logout</span>
                        <span class="activity-time"><?php echo $charData['lastLogoutTime'] ? date('M d, Y H:i', strtotime($charData['lastLogoutTime'])) : 'Never'; ?></span>
                    </div>
                    
                    <div class="activity-item">
                        <span class="activity-label">Character Created</span>
                        <span class="activity-time"><?php echo $charData['BirthDay'] ? date('M d, Y', intval($charData['BirthDay'])) : 'Unknown'; ?></span>
                    </div>
                </div>
                
                <div class="admin-actions">
                    <button class="admin-action-btn primary" onclick="openModal('editCharacterModal')">
                        <i class="fas fa-edit"></i> Edit Character
                    </button>
                    
                    <div class="admin-action-dropdown">
                        <button class="admin-action-btn secondary dropdown-toggle">
                            <i class="fas fa-ellipsis-v"></i> More Actions
                        </button>
                        <div class="dropdown-menu">
                            <a href="#" onclick="openModal('sendMessageModal')">
                                <i class="fas fa-envelope"></i> Send Message
                            </a>
                            <a href="#" onclick="openModal('teleportModal')">
                                <i class="fas fa-map-marker-alt"></i> Teleport Character
                            </a>
                            <a href="#" onclick="openModal('itemsModal')">
                                <i class="fas fa-gift"></i> Send Items
                            </a>
                            <a href="#" class="danger" onclick="openModal('restrictionModal')">
                                <i class="fas fa-ban"></i> Apply Restriction
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Benefits Section -->
        <div class="section">
            <div class="section-header">
                <h2><i class="fas fa-gem"></i> Premium Benefits</h2>
            </div>
            
            <div class="premium-benefits-grid">
                <!-- Bonus Status Card -->
                <div class="card benefit-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-star"></i> Bonus Status</h3>
                    </div>
                    <div class="card-body">
                        <div class="benefit-value <?php echo $charData['BonusStatus'] ? 'active' : 'inactive'; ?>">
                            <?php echo $charData['BonusStatus'] ? 'Active' : 'Inactive'; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Elixir Status Card -->
                <div class="card benefit-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-wine-glass-alt"></i> Elixir Status</h3>
                    </div>
                    <div class="card-body">
                        <div class="benefit-value <?php echo $charData['ElixirStatus'] ? 'active' : 'inactive'; ?>">
                            <?php echo $charData['ElixirStatus'] ? 'Active' : 'Inactive'; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Ein Point Card -->
                <div class="card benefit-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-pray"></i> Ein Point</h3>
                    </div>
                    <div class="card-body">
                        <div class="benefit-value">
                            <?php echo $charData['EinPoint']; ?> points
                        </div>
                    </div>
                </div>
                
                <!-- Tam End Time Card -->
                <div class="card benefit-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-hourglass-half"></i> Tam End Time</h3>
                    </div>
                    <div class="card-body">
                        <div class="benefit-value">
                            <?php 
                            if ($charData['TamEndTime']) {
                                $timestamp = strtotime($charData['TamEndTime']);
                                if ($timestamp > time()) {
                                    echo formatTimeRemaining($timestamp - time()) . ' remaining';
                                } else {
                                    echo 'Expired (' . date('M d, Y H:i', $timestamp) . ')';
                                }
                            } else {
                                echo 'Not active';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- TOPAZ Time Card -->
                <div class="card benefit-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-gem"></i> TOPAZ Time</h3>
                    </div>
                    <div class="card-body">
                        <div class="benefit-value">
                            <?php 
                            if ($charData['TOPAZTime']) {
                                $timestamp = strtotime($charData['TOPAZTime']);
                                if ($timestamp > time()) {
                                    echo formatTimeRemaining($timestamp - time()) . ' remaining';
                                } else {
                                    echo 'Expired (' . date('M d, Y H:i', $timestamp) . ')';
                                }
                            } else {
                                echo 'Not active';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Ein Grace Time Card -->
                <div class="card benefit-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-shield-alt"></i> Ein Grace Time</h3>
                    </div>
                    <div class="card-body">
                        <div class="benefit-value">
                            <?php 
                            if ($charData['EinhasadGraceTime']) {
                                $timestamp = strtotime($charData['EinhasadGraceTime']);
                                if ($timestamp > time()) {
                                    echo formatTimeRemaining($timestamp - time()) . ' remaining';
                                } else {
                                    echo 'Expired (' . date('M d, Y H:i', $timestamp) . ')';
                                }
                            } else {
                                echo 'Not active';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Character Overview Section -->
        <div class="section">
            <div class="section-header">
                <h2><i class="fas fa-user-circle"></i> Character Overview</h2>
            </div>
            
            <div class="content-grid">
                <!-- Main Character Info -->
                <div class="card character-overview">
                    <div class="card-header">
                        <h3 class="card-title">Character Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="character-progress-bars">
                            <div class="progress-item">
                                <div class="progress-label">
                                    <span>Level Progress</span>
                                    <span class="progress-value"><?php echo $charData['level']; ?>/<?php echo $charData['HighLevel']; ?></span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo ($charData['level'] / $charData['HighLevel']) * 100; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-item">
                                <div class="progress-label">
                                    <span>HP</span>
                                    <span class="progress-value"><?php echo $charData['CurHp']; ?>/<?php echo $charData['MaxHp']; ?></span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill hp" style="width: <?php echo ($charData['CurHp'] / $charData['MaxHp']) * 100; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-item">
                                <div class="progress-label">
                                    <span>MP</span>
                                    <span class="progress-value"><?php echo $charData['CurMp']; ?>/<?php echo $charData['MaxMp']; ?></span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill mp" style="width: <?php echo ($charData['CurMp'] / $charData['MaxMp']) * 100; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-item">
                                <div class="progress-label">
                                    <span>PvP Ratio</span>
                                    <span class="progress-value"><?php echo $pvpRatio; ?> K/D</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill pvp" style="width: <?php echo min($pvpRatio * 25, 100); ?>%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="character-attributes">
                            <h3 class="section-title">Attributes</h3>
                            <div class="attributes-grid">
                                <div class="attribute-item">
                                    <div class="attribute-icon str">STR</div>
                                    <div class="attribute-details">
                                        <div class="attribute-value"><?php echo $charData['Str']; ?></div>
                                        <div class="attribute-base">(<?php echo $charData['BaseStr']; ?>)</div>
                                    </div>
                                </div>
                                
                                <div class="attribute-item">
                                    <div class="attribute-icon dex">DEX</div>
                                    <div class="attribute-details">
                                        <div class="attribute-value"><?php echo $charData['Dex']; ?></div>
                                        <div class="attribute-base">(<?php echo $charData['BaseDex']; ?>)</div>
                                    </div>
                                </div>
                                
                                <div class="attribute-item">
                                    <div class="attribute-icon con">CON</div>
                                    <div class="attribute-details">
                                        <div class="attribute-value"><?php echo $charData['Con']; ?></div>
                                        <div class="attribute-base">(<?php echo $charData['BaseCon']; ?>)</div>
                                    </div>
                                </div>
                                
                                <div class="attribute-item">
                                    <div class="attribute-icon int">INT</div>
                                    <div class="attribute-details">
                                        <div class="attribute-value"><?php echo $charData['Intel']; ?></div>
                                        <div class="attribute-base">(<?php echo $charData['BaseIntel']; ?>)</div>
                                    </div>
                                </div>
                                
                                <div class="attribute-item">
                                    <div class="attribute-icon wis">WIS</div>
                                    <div class="attribute-details">
                                        <div class="attribute-value"><?php echo $charData['Wis']; ?></div>
                                        <div class="attribute-base">(<?php echo $charData['BaseWis']; ?>)</div>
                                    </div>
                                </div>
                                
                                <div class="attribute-item">
                                    <div class="attribute-icon cha">CHA</div>
                                    <div class="attribute-details">
                                        <div class="attribute-value"><?php echo $charData['Cha']; ?></div>
                                        <div class="attribute-base">(<?php echo $charData['BaseCha']; ?>)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Character Buffs -->
                <div class="card buffs-card">
                    <div class="card-header">
                        <h3 class="card-title">Active Buffs & Bonuses</h3>
                    </div>
                    <div class="card-body">
                        <div class="buffs-grid">
                            <div class="buff-item">
                                <div class="buff-icon">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div class="buff-details">
                                    <div class="buff-name">Tam Buff</div>
                                    <div class="buff-time">
                                        <?php if ($charData['TamEndTime'] && strtotime($charData['TamEndTime']) > time()): ?>
                                            <?php echo formatTimeRemaining(strtotime($charData['TamEndTime']) - time()); ?> remaining
                                        <?php else: ?>
                                            Not active
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="buff-item">
                                <div class="buff-icon">
                                    <i class="fas fa-gem"></i>
                                </div>
                                <div class="buff-details">
                                    <div class="buff-name">TOPAZ Time</div>
                                    <div class="buff-time">
                                        <?php if ($charData['TOPAZTime'] && strtotime($charData['TOPAZTime']) > time()): ?>
                                            <?php echo formatTimeRemaining(strtotime($charData['TOPAZTime']) - time()); ?> remaining
                                        <?php else: ?>
                                            Not active
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="buff-item">
                                <div class="buff-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="buff-details">
                                    <div class="buff-name">Einhasad's Grace</div>
                                    <div class="buff-time">
                                        <?php if ($charData['EinhasadGraceTime'] && strtotime($charData['EinhasadGraceTime']) > time()): ?>
                                            <?php echo formatTimeRemaining(strtotime($charData['EinhasadGraceTime']) - time()); ?> remaining
                                        <?php else: ?>
                                            Not active
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="buff-item">
                                <div class="buff-icon">
                                    <i class="fas fa-wine-glass-alt"></i>
                                </div>
                                <div class="buff-details">
                                    <div class="buff-name">Elixir Status</div>
                                    <div class="buff-status">
                                        <?php echo $charData['ElixirStatus'] ? 'Active' : 'Not active'; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="buff-item">
                                <div class="buff-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="buff-details">
                                    <div class="buff-name">Bonus Status</div>
                                    <div class="buff-status">
                                        <?php echo $charData['BonusStatus'] ? 'Active' : 'Not active'; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="buff-item">
                                <div class="buff-icon">
                                    <i class="fas fa-pray"></i>
                                </div>
                                <div class="buff-details">
                                    <div class="buff-name">Ein Points</div>
                                    <div class="buff-value">
                                        <?php echo $charData['EinPoint']; ?> points
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- PVP Stats -->
                <div class="card pvp-stats-card">
                    <div class="card-header">
                        <h3 class="card-title">PvP Statistics</h3>
                    </div>
                    <div class="card-body">
                        <div class="pvp-stats-container">
                            <div class="pvp-stat-item">
                                <div class="pvp-stat-value"><?php echo $charData['PC_Kill']; ?></div>
                                <div class="pvp-stat-label">Kills</div>
                            </div>
                            
                            <div class="pvp-stat-item">
                                <div class="pvp-stat-value"><?php echo $charData['PC_Death']; ?></div>
                                <div class="pvp-stat-label">Deaths</div>
                            </div>
                            
                            <div class="pvp-stat-item">
                                <div class="pvp-stat-value"><?php echo $pvpRatio; ?></div>
                                <div class="pvp-stat-label">K/D Ratio</div>
                            </div>
                            
                            <div class="pvp-stat-item">
                                <div class="pvp-stat-value"><?php echo $charData['PKcount']; ?></div>
                                <div class="pvp-stat-label">PK Count</div>
                            </div>
                            
                            <div class="pvp-stat-item">
                                <div class="pvp-stat-value"><?php echo $charData['Karma']; ?></div>
                                <div class="pvp-stat-label">Karma</div>
                            </div>
                        </div>
                        
                        <?php if($charData['HellTime'] > 0): ?>
                        <div class="hell-time">
                            <i class="fas fa-fire"></i>
                            <span>Hell Time: <?php echo $charData['HellTime']; ?> minutes</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Character Details and Location Section (New Layout) -->
        <div class="section">
            <div class="content-grid two-columns">
                <!-- COLUMN 1: Account Characters -->
                <div class="card account-chars-card">
                    <div class="card-header">
                        <h3 class="card-title">Account Characters</h3>
                    </div>
                    <div class="card-body">
                        <div class="account-characters-list">
                            <?php 
                            // Display existing characters first
                            $charactersShown = 0;
                            foreach($altCharacters as $altChar): 
                                $charactersShown++;
                            ?>
                            <div class="character-list-item <?php echo ($altChar['objid'] == $charId) ? 'current' : ''; ?>">
                                <div class="character-list-avatar">
                                    <img src="<?php echo $websiteBaseUrl; ?>assets/img/placeholders/class/header/<?php echo $altChar['Class']; ?>_<?php echo $altChar['gender']; ?>.png" 
                                         alt="<?php echo getClassName($altChar['Class'], $altChar['gender']); ?>">
                                </div>
                                
                                <div class="character-list-info">
                                    <div class="character-list-name">
                                        <?php echo htmlspecialchars($altChar['char_name']); ?>
                                        <?php if ($altChar['objid'] == $charId): ?>
                                        <span class="current-char-badge">Current</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="character-list-class">
                                        <?php echo getClassName($altChar['Class'], $altChar['gender']); ?>
                                    </div>
                                    
                                    <div class="character-list-clan">
                                        <?php echo !empty($altChar['Clanname']) ? htmlspecialchars($altChar['Clanname']) : 'No Clan'; ?>
                                    </div>
                                </div>
                                
                                <div class="character-list-stats">
                                    <div class="list-stat">
                                        <span class="list-stat-label">Level</span>
                                        <span class="list-stat-value"><?php echo $altChar['level']; ?></span>
                                    </div>
                                    
                                    <div class="list-stat">
                                        <span class="list-stat-label">PVP</span>
                                        <span class="list-stat-value"><?php echo $altChar['PC_Kill']; ?>/<?php echo $altChar['PC_Death']; ?></span>
                                    </div>
                                    
                                    <div class="list-stat">
                                        <span class="list-stat-label">Status</span>
                                        <span class="list-stat-value status-badge <?php echo $altChar['OnlineStatus'] ? 'online' : 'offline'; ?>">
                                            <?php echo $altChar['OnlineStatus'] ? 'Online' : 'Offline'; ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="character-list-actions">
                                    <?php if ($altChar['objid'] != $charId): ?>
                                    <a href="character-detail.php?id=<?php echo $altChar['objid']; ?>" class="btn-outline-small">
                                        View
                                    </a>
                                    <?php else: ?>
                                    <span class="current-indicator">Current</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <?php 
                            // Add empty slots to fill up to 10 total
                            $emptySlots = 10 - $charactersShown;
                            for ($i = 0; $i < $emptySlots; $i++): 
                            ?>
                            <div class="character-list-item empty-slot">
                                <div>Character Slot <?php echo $charactersShown + $i + 1; ?> - Empty</div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <!-- COLUMN 2: Map and Clan Information -->
                <div class="column-cards">
                    <!-- Map Location Card -->
                    <div class="card location-card">
                        <div class="card-header">
                            <h3 class="card-title">Location Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="location-map">
                                <div class="map-container">
                                    <div class="map-overlay">
                                        <div class="map-placeholder">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </div>
                                        
                                        <div class="map-marker" style="left: <?php echo min(95, max(5, ($charData['LocX'] / 32768) * 100)); ?>%; top: <?php echo min(95, max(5, ($charData['LocY'] / 32768) * 100)); ?>%;">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="location-coordinates">
                                    <div class="coordinate-display">
                                        <div class="coordinate-label">X:</div>
                                        <div class="coordinate-value"><?php echo $charData['LocX']; ?></div>
                                    </div>
                                    
                                    <div class="coordinate-display">
                                        <div class="coordinate-label">Y:</div>
                                        <div class="coordinate-value"><?php echo $charData['LocY']; ?></div>
                                    </div>
                                    
                                    <div class="coordinate-display">
                                        <div class="coordinate-label">Map:</div>
                                        <div class="coordinate-value"><?php echo $charData['MapID']; ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="location-name">
                                <h3><?php echo $mapName; ?></h3>
                                <div class="map-id">Map ID: <?php echo $charData['MapID']; ?></div>
                            </div>
                            
                            <div class="location-stats">
                                <div class="stat-row">
                                    <div class="stat-label">Last Login</div>
                                    <div class="stat-value"><?php echo $charData['lastLoginTime'] ? date('M d, Y H:i', strtotime($charData['lastLoginTime'])) : 'Never'; ?></div>
                                </div>
                                
                                <div class="stat-row">
                                    <div class="stat-label">Last Logout</div>
                                    <div class="stat-value"><?php echo $charData['lastLogoutTime'] ? date('M d, Y H:i', strtotime($charData['lastLogoutTime'])) : 'Never'; ?></div>
                                </div>
                                
                                <div class="stat-row">
                                    <div class="stat-label">Online Status</div>
                                    <div class="stat-value">
                                        <span class="status-badge <?php echo $charData['OnlineStatus'] ? 'online' : 'offline'; ?>">
                                            <?php echo $charData['OnlineStatus'] ? 'Online' : 'Offline'; ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="stat-row">
                                    <div class="stat-label">Survival Time</div>
                                    <div class="stat-value"><?php echo formatTimeDisplay($charData['SurvivalTime']); ?></div>
                                </div>
                            </div>
                            
                            <div class="teleport-actions">
                                <button class="btn-primary" onclick="openModal('teleportModal')">
                                    <i class="fas fa-map-marker-alt"></i> Teleport Character
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Clan Information Card -->
                    <div class="card clan-details-card">
                        <div class="card-header">
                            <h3 class="card-title">Clan Information</h3>
                        </div>
                        
                        <div class="card-body">
                            <?php if ($charData['ClanID']): ?>
                            <div class="clan-info">
                                <div class="clan-banner">
                                    <div class="clan-emblem">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="clan-name-container">
                                        <h3 class="clan-name"><?php echo htmlspecialchars($charData['Clanname']); ?></h3>
                                        <div class="clan-id">ID: <?php echo $charData['ClanID']; ?></div>
                                    </div>
                                </div>
                                
                                <div class="clan-stats-grid">
                                    <div class="clan-stat-item">
                                        <div class="clan-stat-label">Character Rank</div>
                                        <div class="clan-stat-value"><?php echo getClanRankName($charData['ClanRank']); ?></div>
                                    </div>
                                    
                                    <div class="clan-stat-item">
                                        <div class="clan-stat-label">Joined On</div>
                                        <div class="clan-stat-value">
                                            <?php echo $charData['pledgeJoinDate'] ? date('M d, Y', intval($charData['pledgeJoinDate'])) : 'Unknown'; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="clan-stat-item">
                                        <div class="clan-stat-label">Rank Since</div>
                                        <div class="clan-stat-value">
                                            <?php echo $charData['pledgeRankDate'] ? date('M d, Y', intval($charData['pledgeRankDate'])) : 'Unknown'; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="clan-stat-item">
                                        <div class="clan-stat-label">Contribution</div>
                                        <div class="clan-stat-value"><?php echo number_format($charData['ClanContribution']); ?></div>
                                    </div>
                                    
                                    <div class="clan-stat-item">
                                        <div class="clan-stat-label">Weekly Contribution</div>
                                        <div class="clan-stat-value"><?php echo number_format($charData['ClanWeekContribution']); ?></div>
                                    </div>
                                    
                                    <div class="clan-stat-item">
                                        <div class="clan-stat-label">Clan Title</div>
                                        <div class="clan-stat-value"><?php echo htmlspecialchars($charData['Title'] ?: 'None'); ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="clan-empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3>No Clan Affiliation</h3>
                                <p>This character is not a member of any clan.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Templates -->
<!-- Edit Character Modal -->
<div id="editCharacterModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Character</h2>
            <button class="modal-close" onclick="closeModal('editCharacterModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Edit functionality will be implemented in the next update.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal('editCharacterModal')">Cancel</button>
            <button class="btn-primary" disabled>Save Changes</button>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div id="sendMessageModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Send Message to Character</h2>
            <button class="modal-close" onclick="closeModal('sendMessageModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Message functionality will be implemented in the next update.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal('sendMessageModal')">Cancel</button>
            <button class="btn-primary" disabled>Send Message</button>
        </div>
    </div>
</div>

<!-- Teleport Modal -->
<div id="teleportModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Teleport Character</h2>
            <button class="modal-close" onclick="closeModal('teleportModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Teleport functionality will be implemented in the next update.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal('teleportModal')">Cancel</button>
            <button class="btn-primary" disabled>Teleport</button>
        </div>
    </div>
</div>

<!-- Items Modal -->
<div id="itemsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Send Items to Character</h2>
            <button class="modal-close" onclick="closeModal('itemsModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Item sending functionality will be implemented in the next update.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal('itemsModal')">Cancel</button>
            <button class="btn-primary" disabled>Send Items</button>
        </div>
    </div>
</div>

<!-- Restriction Modal -->
<div id="restrictionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Apply Restriction</h2>
            <button class="modal-close" onclick="closeModal('restrictionModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Restriction functionality will be implemented in the next update.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal('restrictionModal')">Cancel</button>
            <button class="btn-primary danger" disabled>Apply Restriction</button>
        </div>
    </div>
</div>

<script src="<?php echo $adminBaseUrl; ?>assets/js/character-detail.js"></script>

<?php
// Helper functions for formatting time
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

function formatTimeDisplay($seconds) {
    if (!$seconds) return 'N/A';
    
    // If it's a datetime string, convert to timestamp
    if (is_string($seconds) && !is_numeric($seconds)) {
        $seconds = strtotime($seconds);
        if ($seconds === false) return 'N/A';
        
        // Calculate time difference from now
        $seconds = time() - $seconds;
    }
    
    $days = floor($seconds / 86400);
    $hours = floor(($seconds % 86400) / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    
    $output = '';
    if ($days > 0) $output .= $days . ' days ';
    if ($hours > 0) $output .= $hours . ' hours ';
    if ($minutes > 0) $output .= $minutes . ' minutes';
    
    return trim($output) ?: '0 minutes';
}

function getClanRankName($rankId) {
    $ranks = [
        0 => 'None',
        1 => 'Academy Member',
        2 => 'Member',
        3 => 'Elite',
        4 => 'General',
        5 => 'Clan Leader'
    ];
    
    return isset($ranks[$rankId]) ? $ranks[$rankId] : 'Unknown';
}

include '../../includes/admin-footer.php';
?>