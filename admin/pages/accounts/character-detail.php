<?php
/**
 * Enhanced Character Detail Page - Complete Redesign
 * Modern dashboard layout with sidebar navigation and card components
 */

require_once '../../../includes/db_connect.php';
require_once '../../includes/admin-config.php';
require_once '../../includes/account-functions.php';

// Helper functions for formatting and data processing
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

function getDifficultyByQuestId($questId) {
    // Simple algorithm to assign a difficulty based on quest ID
    if ($questId < 10) {
        return 'easy';
    } else if ($questId < 20) {
        return 'normal';
    } else if ($questId < 30) {
        return 'hard';
    } else {
        return 'epic';
    }
}

function getCategoryName($categoryId) {
    $categories = [
        0 => 'General',
        1 => 'Weapons',
        2 => 'Armor',
        3 => 'Accessories',
        4 => 'Consumables',
        5 => 'Materials',
        6 => 'Scrolls',
        7 => 'Quest Items'
    ];
    
    return isset($categories[$categoryId]) ? $categories[$categoryId] : 'Unknown';
}

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

// Get character's active quests
$activeQuests = [];
$questsSql = "SELECT * FROM character_quests 
              WHERE char_id = $charId 
              ORDER BY quest_id ASC
              LIMIT 5";
$questsResult = $conn->query($questsSql);
if ($questsResult && $questsResult->num_rows > 0) {
    while ($quest = $questsResult->fetch_assoc()) {
        // Add placeholder difficulty (since we don't have access to the quests table)
        $quest['difficulty'] = getDifficultyByQuestId($quest['quest_id']);
        $quest['quest_name'] = 'Quest #' . $quest['quest_id'];
        $activeQuests[] = $quest;
    }
}

// Get character's hunting quests
$huntingQuests = [];
$huntingSql = "SELECT * FROM character_hunting_quest 
               WHERE objID = $charId
               LIMIT 5";
$huntingResult = $conn->query($huntingSql);
if ($huntingResult && $huntingResult->num_rows > 0) {
    while ($hunt = $huntingResult->fetch_assoc()) {
        // Add placeholder monster name (since we don't have the monsters table)
        $hunt['monster_name'] = 'Monster Target #' . $hunt['quest_id'];
        $hunt['target_count'] = 10; // Default target count
        $huntingQuests[] = $hunt;
    }
}

// Get character's equipment sets
$equipSets = [];
$equipSql = "SELECT * FROM character_equipset WHERE charId = $charId";
$equipResult = $conn->query($equipSql);
if ($equipResult && $equipResult->num_rows > 0) {
    while ($set = $equipResult->fetch_assoc()) {
        $equipSets[] = $set;
    }
}

// Get character's saved teleport locations
$teleportLocations = [];
$teleportSql = "SELECT * FROM character_teleport WHERE char_id = $charId ORDER BY num_id ASC LIMIT 5";
$teleportResult = $conn->query($teleportSql);
if ($teleportResult && $teleportResult->num_rows > 0) {
    while ($tele = $teleportResult->fetch_assoc()) {
        $teleportLocations[] = $tele;
    }
}

// Get character's buddy list
$buddies = [];
$buddySql = "SELECT * FROM character_buddys WHERE char_id = $charId ORDER BY id ASC LIMIT 10";
$buddyResult = $conn->query($buddySql);
if ($buddyResult && $buddyResult->num_rows > 0) {
    while ($buddy = $buddyResult->fetch_assoc()) {
        $buddies[] = $buddy;
    }
}

// Get character's exclude list
$excludes = [];
$excludeSql = "SELECT * FROM character_exclude WHERE char_id = $charId ORDER BY id ASC LIMIT 10";
$excludeResult = $conn->query($excludeSql);
if ($excludeResult && $excludeResult->num_rows > 0) {
    while ($exclude = $excludeResult->fetch_assoc()) {
        $excludes[] = $exclude;
    }
}

// Get character's revenge data
$revengeData = [];
$revengeSql = "SELECT * FROM character_revenge WHERE char_id = $charId ORDER BY starttime DESC LIMIT 5";
$revengeResult = $conn->query($revengeSql);
if ($revengeResult && $revengeResult->num_rows > 0) {
    while ($revenge = $revengeResult->fetch_assoc()) {
        $revengeData[] = $revenge;
    }
}

// Get character's companions
$companions = [];
$companionSql = "SELECT * FROM character_companion WHERE objid = $charId ORDER BY level DESC LIMIT 3";
$companionResult = $conn->query($companionSql);
if ($companionResult && $companionResult->num_rows > 0) {
    while ($companion = $companionResult->fetch_assoc()) {
        $companions[] = $companion;
    }
}

// Get character's einhasad stats
$einhasadStats = [];
$einhasadSql = "SELECT * FROM character_einhasadstat WHERE objid = $charId";
$einhasadResult = $conn->query($einhasadSql);
if ($einhasadResult && $einhasadResult->num_rows > 0) {
    $einhasadStats = $einhasadResult->fetch_assoc();
}

// Get character's death items
$deathItems = [];
$deathItemSql = "SELECT * FROM character_death_item WHERE char_id = $charId ORDER BY delete_time DESC LIMIT 5";
$deathItemResult = $conn->query($deathItemSql);
if ($deathItemResult && $deathItemResult->num_rows > 0) {
    while ($item = $deathItemResult->fetch_assoc()) {
        $deathItems[] = $item;
    }
}

// Get character's favorite items
$favoriteItems = [];
$favorSql = "SELECT * FROM character_favorbook WHERE charObjId = $charId ORDER BY category, slotId ASC LIMIT 5";
$favorResult = $conn->query($favorSql);
if ($favorResult && $favorResult->num_rows > 0) {
    while ($favor = $favorResult->fetch_assoc()) {
        $favoriteItems[] = $favor;
    }
}

// Include admin header
include '../../includes/admin-header.php';
?>

<link rel="stylesheet" href="<?php echo $adminBaseUrl; ?>assets/css/character-detail.css">

<div class="page-container">
    <!-- Breadcrumb navigation -->
    <div class="breadcrumb">
        <a href="<?php echo $adminBaseUrl; ?>">Dashboard</a>
        <span class="breadcrumb-separator">/</span>
        <a href="account-list.php">Accounts</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current"><?php echo htmlspecialchars($charData['char_name']); ?></span>
    </div>

    <!-- Two-column layout with sidebar and main content -->
    <div class="char-layout">
        <!-- Character Sidebar -->
        <div class="char-sidebar">
            <!-- Character Profile Card -->
            <div class="card profile-card">
                <div class="card-body">
                    <!-- Character Avatar -->
                    <div class="profile-avatar">
                        <img src="<?php echo $websiteBaseUrl; ?>assets/img/placeholders/class/header/<?php echo $charData['Class']; ?>_<?php echo $charData['gender']; ?>.png" 
                            alt="<?php echo getClassName($charData['Class'], $charData['gender']); ?>">
                    </div>
                    
                    <!-- Character Name and Class -->
                    <div class="profile-name"><?php echo htmlspecialchars($charData['char_name']); ?></div>
                    <div class="profile-class"><?php echo getClassName($charData['Class'], $charData['gender']); ?></div>
                    
                    <!-- Online Status -->
                    <div class="profile-status <?php echo $charData['OnlineStatus'] ? 'online' : 'offline'; ?>">
                        <?php echo $charData['OnlineStatus'] ? 'Online' : 'Offline'; ?>
                    </div>
                    
                    <!-- Key Stats -->
                    <div class="profile-meta">
                        <div class="profile-stat">
                            <div class="profile-stat-value"><?php echo $charData['level']; ?>/<?php echo $charData['HighLevel']; ?></div>
                            <div class="profile-stat-label">Level</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value"><?php echo $pvpRatio; ?></div>
                            <div class="profile-stat-label">PvP Ratio</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value"><?php echo number_format($charData['ClanContribution']); ?></div>
                            <div class="profile-stat-label">Contribution</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value <?php echo $charData['Karma'] < 0 ? 'negative' : ''; ?>"><?php echo $charData['Karma']; ?></div>
                            <div class="profile-stat-label">Karma</div>
                        </div>
                    </div>
                    
                    <!-- Account Info -->
                    <div class="card-footer">
                        <div class="tooltip-container">
                            Account: <?php echo htmlspecialchars($accountName); ?>
                            <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                            <span class="tooltip-text">Characters linked to this account: <?php echo count($altCharacters); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Menu -->
            <div class="char-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="#overview" class="active">
                            <i class="fas fa-home"></i>
                            <span>Overview</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#stats">
                            <i class="fas fa-chart-bar"></i>
                            <span>Stats & Attributes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#equipment">
                            <i class="fas fa-tshirt"></i>
                            <span>Equipment & Items</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#quests">
                            <i class="fas fa-scroll"></i>
                            <span>Quests & Hunting</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#social">
                            <i class="fas fa-users"></i>
                            <span>Social & Clan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Location</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#admin">
                            <i class="fas fa-cog"></i>
                            <span>Admin Actions</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="char-main">
            <!-- Overview Section -->
            <section id="overview">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">
                        <i class="fas fa-home"></i>
                        Character Overview
                    </h2>
                    <div class="dashboard-actions">
                        <button class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            Edit Character
                        </button>
                        <button class="btn btn-secondary">
                            <i class="fas fa-ellipsis-v"></i>
                            More
                        </button>
                    </div>
                </div>
                
                <div class="dashboard-grid">
                    <!-- Progress Information -->
                    <div class="card col-span-8">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-line"></i>
                                    Progress & Status
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: characters</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Health & Mana -->
                            <div class="progress-container">
                                <div class="progress-header">
                                    <span>Health</span>
                                    <span><?php echo $charData['CurHp']; ?> / <?php echo $charData['MaxHp']; ?></span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill hp" style="width: <?php echo ($charData['CurHp'] / $charData['MaxHp']) * 100; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-container">
                                <div class="progress-header">
                                    <span>Mana</span>
                                    <span><?php echo $charData['CurMp']; ?> / <?php echo $charData['MaxMp']; ?></span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill mp" style="width: <?php echo ($charData['CurMp'] / $charData['MaxMp']) * 100; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-container">
                                <div class="progress-header">
                                    <span>Level Progress</span>
                                    <span><?php echo $charData['level']; ?> / <?php echo $charData['HighLevel']; ?></span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill xp" style="width: <?php echo ($charData['level'] / $charData['HighLevel']) * 100; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-container">
                                <div class="progress-header">
                                    <span>PvP Performance</span>
                                    <span>K/D Ratio: <?php echo $pvpRatio; ?></span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill pvp" style="width: <?php echo min($pvpRatio * 25, 100); ?>%"></div>
                                </div>
                            </div>
                            
                            <!-- Character Attributes -->
                            <div class="attributes-grid">
                                <div class="attribute">
                                    <div class="attribute-icon str">STR</div>
                                    <div class="attribute-values">
                                        <div class="attribute-main"><?php echo $charData['Str']; ?></div>
                                        <div class="attribute-base">Base: <?php echo $charData['BaseStr']; ?></div>
                                    </div>
                                </div>
                                
                                <div class="attribute">
                                    <div class="attribute-icon dex">DEX</div>
                                    <div class="attribute-values">
                                        <div class="attribute-main"><?php echo $charData['Dex']; ?></div>
                                        <div class="attribute-base">Base: <?php echo $charData['BaseDex']; ?></div>
                                    </div>
                                </div>
                                
                                <div class="attribute">
                                    <div class="attribute-icon con">CON</div>
                                    <div class="attribute-values">
                                        <div class="attribute-main"><?php echo $charData['Con']; ?></div>
                                        <div class="attribute-base">Base: <?php echo $charData['BaseCon']; ?></div>
                                    </div>
                                </div>
                                
                                <div class="attribute">
                                    <div class="attribute-icon int">INT</div>
                                    <div class="attribute-values">
                                        <div class="attribute-main"><?php echo $charData['Intel']; ?></div>
                                        <div class="attribute-base">Base: <?php echo $charData['BaseIntel']; ?></div>
                                    </div>
                                </div>
                                
                                <div class="attribute">
                                    <div class="attribute-icon wis">WIS</div>
                                    <div class="attribute-values">
                                        <div class="attribute-main"><?php echo $charData['Wis']; ?></div>
                                        <div class="attribute-base">Base: <?php echo $charData['BaseWis']; ?></div>
                                    </div>
                                </div>
                                
                                <div class="attribute">
                                    <div class="attribute-icon cha">CHA</div>
                                    <div class="attribute-values">
                                        <div class="attribute-main"><?php echo $charData['Cha']; ?></div>
                                        <div class="attribute-base">Base: <?php echo $charData['BaseCha']; ?></div>
                                    </div>
                                </div>
								
								<div class="attribute">
                                    <div class="attribute-icon-ac">AC</div>
                                    <div class="attribute-values">
                                        <div class="attribute-main"><?php echo $charData['Ac']; ?></div>
                                        <div class="attribute-base">Base: <?php echo $charData['Ac']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Characters -->
                    <div class="card col-span-4">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-users"></i>
                                    Account Characters
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: characters</span>
                            </div>
                            <div class="card-badge"><?php echo count($altCharacters); ?></div>
                        </div>
                        <div class="card-body">
                            <div class="character-list">
                                <?php foreach($altCharacters as $altChar): ?>
                                <div class="character-item <?php echo ($altChar['objid'] == $charId) ? 'current' : ''; ?>">
                                    <div class="character-avatar">
                                        <img src="<?php echo $websiteBaseUrl; ?>assets/img/placeholders/class/header/<?php echo $altChar['Class']; ?>_<?php echo $altChar['gender']; ?>.png" 
                                            alt="<?php echo getClassName($altChar['Class'], $altChar['gender']); ?>">
                                    </div>
                                    <div class="character-info">
                                        <div class="character-name">
                                            <?php echo htmlspecialchars($altChar['char_name']); ?>
                                            <?php if ($altChar['objid'] == $charId): ?>
                                            <span class="current-badge">Current</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="character-details">
                                            <span>Lvl <?php echo $altChar['level']; ?></span>
                                            <span><?php echo getClassName($altChar['Class'], $altChar['gender']); ?></span>
                                        </div>
                                    </div>
                                    <?php if ($altChar['objid'] != $charId): ?>
                                    <div class="character-action">
                                        <a href="character-detail.php?id=<?php echo $altChar['objid']; ?>" class="btn btn-sm btn-outline">View</a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Premium Benefits -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-gem"></i>
                                    Premium Benefits
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: characters (premium fields)</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="buffs-grid">
                                <div class="buff <?php echo $charData['BonusStatus'] ? 'buff-active' : 'buff-inactive'; ?>">
                                    <div class="buff-icon">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="buff-name">Bonus Status</div>
                                    <div class="buff-value"><?php echo $charData['BonusStatus'] ? 'Active' : 'Inactive'; ?></div>
                                </div>
                                
                                <div class="buff <?php echo $charData['ElixirStatus'] ? 'buff-active' : 'buff-inactive'; ?>">
                                    <div class="buff-icon">
                                        <i class="fas fa-wine-glass-alt"></i>
                                    </div>
                                    <div class="buff-name">Elixir Status</div>
                                    <div class="buff-value"><?php echo $charData['ElixirStatus'] ? 'Active' : 'Inactive'; ?></div>
                                </div>
                                
                                <div class="buff">
                                    <div class="buff-icon">
                                        <i class="fas fa-pray"></i>
                                    </div>
                                    <div class="buff-name">Ein Point</div>
                                    <div class="buff-value"><?php echo $charData['EinPoint']; ?> points</div>
                                </div>
                                
                                <div class="buff <?php echo ($charData['TamEndTime'] && strtotime($charData['TamEndTime']) > time()) ? 'buff-active' : 'buff-inactive'; ?>">
                                    <div class="buff-icon">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <div class="buff-name">Tam End Time</div>
                                    <div class="buff-value">
                                        <?php 
                                        if ($charData['TamEndTime']) {
                                            $timestamp = strtotime($charData['TamEndTime']);
                                            if ($timestamp > time()) {
                                                echo formatTimeRemaining($timestamp - time()) . ' remaining';
                                            } else {
                                                echo 'Expired';
                                            }
                                        } else {
                                            echo 'Not active';
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="buff <?php echo ($charData['TOPAZTime'] && strtotime($charData['TOPAZTime']) > time()) ? 'buff-active' : 'buff-inactive'; ?>">
                                    <div class="buff-icon">
                                        <i class="fas fa-gem"></i>
                                    </div>
                                    <div class="buff-name">TOPAZ Time</div>
                                    <div class="buff-value">
                                        <?php 
                                        if ($charData['TOPAZTime']) {
                                            $timestamp = strtotime($charData['TOPAZTime']);
                                            if ($timestamp > time()) {
                                                echo formatTimeRemaining($timestamp - time()) . ' remaining';
                                            } else {
                                                echo 'Expired';
                                            }
                                        } else {
                                            echo 'Not active';
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="buff <?php echo ($charData['EinhasadGraceTime'] && strtotime($charData['EinhasadGraceTime']) > time()) ? 'buff-active' : 'buff-inactive'; ?>">
                                    <div class="buff-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="buff-name">Ein Grace</div>
                                    <div class="buff-value">
                                        <?php 
                                        if ($charData['EinhasadGraceTime']) {
                                            $timestamp = strtotime($charData['EinhasadGraceTime']);
                                            if ($timestamp > time()) {
                                                echo formatTimeRemaining($timestamp - time()) . ' remaining';
                                            } else {
                                                echo 'Expired';
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
                    
                    <!-- PVP Stats -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-fist-raised"></i>
                                    PvP Statistics
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: characters (PvP fields)</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="pvp-stats">
                                <div class="pvp-stat">
                                    <div class="pvp-stat-value"><?php echo $charData['PC_Kill']; ?></div>
                                    <div class="pvp-stat-label">Kills</div>
                                </div>
                                
                                <div class="pvp-stat">
                                    <div class="pvp-stat-value"><?php echo $charData['PC_Death']; ?></div>
                                    <div class="pvp-stat-label">Deaths</div>
                                </div>
                                
                                <div class="pvp-stat">
                                    <div class="pvp-stat-value"><?php echo $pvpRatio; ?></div>
                                    <div class="pvp-stat-label">K/D Ratio</div>
                                </div>
                                
                                <div class="pvp-stat">
                                    <div class="pvp-stat-value"><?php echo $charData['PKcount']; ?></div>
                                    <div class="pvp-stat-label">PK Count</div>
                                </div>
                                
                                <div class="pvp-stat">
                                    <div class="pvp-stat-value <?php echo $charData['Karma'] < 0 ? 'negative' : ''; ?>"><?php echo $charData['Karma']; ?></div>
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
            </section>
            
            <!-- Equipment & Items Section -->
            <section id="equipment">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">
                        <i class="fas fa-tshirt"></i>
                        Equipment & Items
                    </h2>
                </div>
                
                <div class="dashboard-grid">
                    <!-- Equipment Sets -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-tshirt"></i>
                                    Equipment Sets
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: character_equipset</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($equipSets)): ?>
                                <?php foreach($equipSets as $index => $set): ?>
                                <div class="equipment-set">
                                    <div class="set-header">
                                        <div class="set-name">
                                            Set <?php echo $index + 1; ?>: <?php echo !empty($set['slot'.$set['current_set'].'_name']) ? htmlspecialchars($set['slot'.$set['current_set'].'_name']) : 'Unnamed Set'; ?>
                                        </div>
                                        <?php if ($set['current_set'] == 1): ?>
                                        <div class="set-badge">Active</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="set-slots">
                                        <?php 
                                        $itemSlots = ['Weapon', 'Armor', 'Shield', 'Accessory'];
                                        foreach($itemSlots as $slotIndex => $slotName): 
                                        ?>
                                        <div class="set-slot">
                                            <div class="slot-title"><?php echo $slotName; ?></div>
                                            <div class="slot-status">
                                                <?php if (!empty($set['slot'.($slotIndex+1).'_item'])): ?>
                                                <i class="fas fa-check-circle"></i>
                                                <span class="slot-equipped">Equipped</span>
                                                <?php else: ?>
                                                <span class="slot-empty">Empty</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-tshirt"></i>
                                <div class="empty-state-title">No Equipment Sets</div>
                                <p>This character hasn't saved any equipment sets.</p>
                                <div class="empty-state-note">Equipment set data will display here when available.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Favorite Items -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-bookmark"></i>
                                    Favorite Items
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: character_favorbook</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($favoriteItems)): ?>
                            <div class="item-grid">
                                <?php foreach($favoriteItems as $favor): ?>
                                <div class="item-card">
                                    <div class="item-header">
                                        <div class="item-icon">
                                            <i class="fas fa-gem"></i>
                                        </div>
                                        <div class="item-name">
                                            <?php 
                                                $enchantPrefix = $favor['enchantLevel'] > 0 ? '+'.$favor['enchantLevel'].' ' : '';
                                                echo $enchantPrefix . (!empty($favor['itemName']) ? htmlspecialchars($favor['itemName']) : 'Item #'.$favor['itemId']); 
                                            ?>
                                        </div>
                                    </div>
                                    <div class="item-body">
                                        <div class="item-stats">
                                            <div class="item-stat">
                                                <span>Category:</span>
                                                <span><?php echo getCategoryName($favor['category']); ?></span>
                                            </div>
                                            <div class="item-stat">
                                                <span>Slot:</span>
                                                <span><?php echo $favor['slotId']; ?></span>
                                            </div>
                                            <div class="item-stat">
                                                <span>Quantity:</span>
                                                <span><?php echo $favor['count']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-bookmark"></i>
                                <div class="empty-state-title">No Favorite Items</div>
                                <p>This character hasn't marked any items as favorites.</p>
                                <div class="empty-state-note">Favorite item data will display here when available.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Death Recovery Items -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-ghost"></i>
                                    Death Recovery Items
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: character_death_item</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($deathItems)): ?>
                            <div class="item-grid">
                                <?php foreach($deathItems as $item): ?>
                                <div class="item-card">
                                    <div class="item-header">
                                        <div class="item-icon">
                                            <i class="fas fa-skull"></i>
                                        </div>
                                        <div class="item-name">
                                            <?php 
                                                $enchantPrefix = $item['enchant'] > 0 ? '+'.$item['enchant'].' ' : '';
                                                echo $enchantPrefix . 'Item #' . $item['itemId']; 
                                            ?>
                                        </div>
                                    </div>
                                    <div class="item-body">
                                        <div class="item-stats">
                                            <div class="item-stat">
                                                <span>Quantity:</span>
                                                <span><?php echo $item['count']; ?></span>
                                            </div>
                                            <div class="item-stat">
                                                <span>Recovery Cost:</span>
                                                <span><?php echo number_format($item['recovery_cost']); ?> Adena</span>
                                            </div>
                                            <div class="item-stat">
                                                <span>Expires:</span>
                                                <span><?php echo date('M d, Y', strtotime($item['delete_time'])); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-ghost"></i>
                                <div class="empty-state-title">No Death Recovery Items</div>
                                <p>This character has no items waiting to be recovered after death.</p>
                                <div class="empty-state-note">Death item data will display here when available.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Saved Teleport Locations -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Saved Teleport Locations
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: character_teleport</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($teleportLocations)): ?>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Coordinates</th>
                                        <th>Map ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($teleportLocations as $tele): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($tele['name']); ?></td>
                                        <td>X: <?php echo $tele['locx']; ?>, Y: <?php echo $tele['locy']; ?></td>
                                        <td><?php echo $tele['mapid']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="empty-state-title">No Saved Teleport Locations</div>
                                <p>This character hasn't saved any teleport locations.</p>
                                <div class="empty-state-note">Teleport data will display here when available.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Quests & Hunting Section -->
            <section id="quests">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">
                        <i class="fas fa-scroll"></i>
                        Quests & Hunting
                    </h2>
                </div>
                
                <div class="dashboard-grid">
                    <!-- Active Quests -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-scroll"></i>
                                    Active Quests
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: character_quests</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($activeQuests)): ?>
                            <div class="quests-list">
                                <?php foreach($activeQuests as $quest): ?>
                                <div class="quest-item">
                                    <div class="quest-difficulty difficulty-<?php echo strtolower($quest['difficulty']); ?>">
                                        <?php echo ucfirst($quest['difficulty']); ?>
                                    </div>
                                    <div class="quest-info">
                                        <div class="quest-name"><?php echo htmlspecialchars($quest['quest_name']); ?></div>
                                        <div class="quest-progress">
                                            <div class="quest-step">
                                                <span>Step <?php echo $quest['quest_step']; ?></span>
                                                <span><?php echo $quest['quest_step']; ?>/5</span>
                                            </div>
                                            <div class="quest-bar">
                                                <div class="quest-fill" style="width: <?php echo ($quest['quest_step'] / 5) * 100; ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-scroll"></i>
                                <div class="empty-state-title">No Active Quests</div>
                                <p>This character isn't currently on any quests.</p>
                                <div class="empty-state-note">Quest data will display here when available.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Hunting Quests -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-dragon"></i>
                                    Hunting Quests
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: character_hunting_quest</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($huntingQuests)): ?>
                            <div class="quests-list">
                                <?php foreach($huntingQuests as $hunt): ?>
                                <div class="quest-item">
                                    <div class="quest-difficulty difficulty-<?php echo $hunt['complete'] == 'true' ? 'easy' : 'normal'; ?>">
                                        <?php echo $hunt['complete'] == 'true' ? 'Complete' : 'Ongoing'; ?>
                                    </div>
                                    <div class="quest-info">
                                        <div class="quest-name"><?php echo htmlspecialchars($hunt['monster_name']); ?></div>
                                        <div class="quest-progress">
                                            <div class="quest-step">
                                                <span>Location: <?php echo !empty($hunt['location_desc']) ? 'Area #'.$hunt['location_desc'] : 'Unknown'; ?></span>
                                                <span><?php echo $hunt['kill_count']; ?>/<?php echo $hunt['target_count']; ?></span>
                                            </div>
                                            <div class="quest-bar">
                                                <div class="quest-fill" style="width: <?php echo ($hunt['kill_count'] / $hunt['target_count']) * 100; ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-dragon"></i>
                                <div class="empty-state-title">No Hunting Quests</div>
                                <p>This character isn't currently on any hunting quests.</p>
                                <div class="empty-state-note">Hunting quest data will display here when available.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Revenge Data -->
                    <div class="card col-span-12">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-skull-crossbones"></i>
                                    Revenge Data
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: character_revenge</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($revengeData)): ?>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Target</th>
                                        <th>Class</th>
                                        <th>Clan</th>
                                        <th>Result</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($revengeData as $revenge): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($revenge['targetname']); ?></td>
                                        <td><?php echo getClassName($revenge['targetclass'], 1); // Using male gender as placeholder ?></td>
                                        <td><?php echo !empty($revenge['targetclanname']) ? htmlspecialchars($revenge['targetclanname']) : 'None'; ?></td>
                                        <td>
                                            <span class="<?php echo $revenge['result'] == 1 ? 'text-success' : 'text-danger'; ?>">
                                                <?php echo $revenge['result'] == 1 ? 'Successful' : 'Failed'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $revenge['endtime'] ? date('M d, Y', strtotime($revenge['endtime'])) : 'Ongoing'; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-skull-crossbones"></i>
                                <div class="empty-state-title">No Revenge Data</div>
                                <p>This character hasn't attempted revenge on any players.</p>
                                <div class="empty-state-note">Revenge data will display here when available.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Social & Clan Section -->
            <section id="social">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">
                        <i class="fas fa-users"></i>
                        Social & Clan
                    </h2>
                </div>
                
                <div class="dashboard-grid">
                    <!-- Clan Information -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-shield-alt"></i>
                                    Clan Information
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: characters (clan fields)</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($charData['ClanID']): ?>
                            <div class="clan-banner">
                                <div class="clan-emblem">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="clan-details">
                                    <h3 class="clan-name"><?php echo htmlspecialchars($charData['Clanname']); ?></h3>
                                    <div class="clan-id">ID: <?php echo $charData['ClanID']; ?></div>
                                </div>
                            </div>
                            
                            <div class="clan-stats">
                                <div class="clan-stat">
                                    <div class="clan-stat-label">Character Rank</div>
                                    <div class="clan-stat-value"><?php echo getClanRankName($charData['ClanRank']); ?></div>
                                </div>
                                
                                <div class="clan-stat">
                                    <div class="clan-stat-label">Joined On</div>
                                    <div class="clan-stat-value">
                                        <?php echo $charData['pledgeJoinDate'] ? date('M d, Y', intval($charData['pledgeJoinDate'])) : 'Unknown'; ?>
                                    </div>
                                </div>
                                
                                <div class="clan-stat">
                                    <div class="clan-stat-label">Rank Since</div>
                                    <div class="clan-stat-value">
                                        <?php echo $charData['pledgeRankDate'] ? date('M d, Y', intval($charData['pledgeRankDate'])) : 'Unknown'; ?>
                                    </div>
                                </div>
                                
                                <div class="clan-stat">
                                    <div class="clan-stat-label">Contribution</div>
                                    <div class="clan-stat-value"><?php echo number_format($charData['ClanContribution']); ?></div>
                                </div>
                                
                                <div class="clan-stat">
                                    <div class="clan-stat-label">Weekly Contribution</div>
                                    <div class="clan-stat-value"><?php echo number_format($charData['ClanWeekContribution']); ?></div>
                                </div>
                                
                                <div class="clan-stat">
                                    <div class="clan-stat-label">Clan Title</div>
                                    <div class="clan-stat-value"><?php echo htmlspecialchars($charData['Title'] ?: 'None'); ?></div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="no-clan">
                                <i class="fas fa-users"></i>
                                <h3>No Clan Affiliation</h3>
                                <p>This character is not a member of any clan.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Companions -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-paw"></i>
                                    Companions
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: character_companion</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($companions)): ?>
                            <div class="quests-list">
                                <?php foreach($companions as $companion): ?>
                                <div class="quest-item">
                                    <div class="quest-difficulty <?php echo $companion['dead'] == 0 ? 'difficulty-easy' : 'difficulty-hard'; ?>">
                                        <?php echo $companion['dead'] == 0 ? 'Active' : 'Dead'; ?>
                                    </div>
                                    <div class="quest-info">
                                        <div class="quest-name"><?php echo htmlspecialchars($companion['name']); ?> (Level <?php echo $companion['level']; ?>)</div>
                                        <div class="quest-progress">
                                            <div class="quest-step">
                                                <span>NPC Type: #<?php echo $companion['npcId']; ?></span>
                                                <span>HP: <?php echo $companion['currentHp']; ?>/<?php echo $companion['maxHp']; ?></span>
                                            </div>
                                            <div class="quest-bar">
                                                <div class="quest-fill hp" style="width: <?php echo ($companion['currentHp'] / $companion['maxHp']) * 100; ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-paw"></i>
                                <div class="empty-state-title">No Companions</div>
                                <p>This character doesn't have any companions or pets.</p>
                                <div class="empty-state-note">Companion data will display here when available.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Friend List -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-user-friends"></i>
                                    Friend List
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: character_buddys</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($buddies)): ?>
                            <div class="social-list">
                                <?php foreach($buddies as $buddy): ?>
                                <div class="social-item">
                                    <div class="social-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="social-info">
                                        <div class="social-name"><?php echo htmlspecialchars($buddy['buddy_name']); ?></div>
                                        <?php if (!empty($buddy['buddy_memo'])): ?>
                                        <div class="social-meta"><?php echo htmlspecialchars($buddy['buddy_memo']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-user-friends"></i>
                                <div class="empty-state-title">No Friends</div>
                                <p>This character hasn't added any friends to their buddy list.</p>
                                <div class="empty-state-note">Friend data will display here when available.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Blocked Characters -->
                    <div class="card col-span-6">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-user-slash"></i>
                                    Blocked Characters
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: character_exclude</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($excludes)): ?>
                            <div class="social-list">
                                <?php foreach($excludes as $exclude): ?>
                                <div class="social-item">
                                    <div class="social-avatar">
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <div class="social-info">
                                        <div class="social-name"><?php echo htmlspecialchars($exclude['exclude_name']); ?></div>
                                        <div class="social-meta">Block Type: <?php echo $exclude['type'] == 1 ? 'Chat Block' : 'Full Block'; ?></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-user-slash"></i>
                                <div class="empty-state-title">No Blocked Characters</div>
                                <p>This character hasn't blocked any other characters.</p>
                                <div class="empty-state-note">Block data will display here when available.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Location Section -->
            <section id="location">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Location Information
                    </h2>
                    <div class="dashboard-actions">
                        <button class="btn btn-primary" onclick="openModal('teleportModal')">
                            <i class="fas fa-map-marker-alt"></i>
                            Teleport Character
                        </button>
                    </div>
                </div>
                
                <div class="dashboard-grid">
                    <!-- Current Location -->
                    <div class="card col-span-12">
                        <div class="card-header">
                            <div class="tooltip-container">
                                <h3 class="card-title">
                                    <i class="fas fa-map-marked-alt"></i>
                                    Current Location
                                </h3>
                                <span class="tooltip-icon"><i class="fas fa-info-circle"></i></span>
                                <span class="tooltip-text">Data from: characters (location fields)</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="map-container">
                                <div class="map-grid"></div>
                                <div class="map-placeholder">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div class="map-marker" style="left: <?php echo min(95, max(5, ($charData['LocX'] / 32768) * 100)); ?>%; top: <?php echo min(95, max(5, ($charData['LocY'] / 32768) * 100)); ?>%;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                            
                            <div class="map-coordinates">
                                <div class="coordinate">
                                    <div class="coordinate-label">X Coordinate</div>
                                    <div class="coordinate-value"><?php echo $charData['LocX']; ?></div>
                                </div>
                                <div class="coordinate">
                                    <div class="coordinate-label">Y Coordinate</div>
                                    <div class="coordinate-value"><?php echo $charData['LocY']; ?></div>
                                </div>
                                <div class="coordinate">
                                    <div class="coordinate-label">Map ID</div>
                                    <div class="coordinate-value"><?php echo $charData['MapID']; ?></div>
                                </div>
                            </div>
                            
                            <div class="location-name"><?php echo $mapName; ?></div>
                            <div class="location-id">Map ID: <?php echo $charData['MapID']; ?></div>
                            
                            <table class="data-table">
                                <tbody>
                                    <tr>
                                        <td>Last Login</td>
                                        <td><?php echo $charData['lastLoginTime'] ? date('M d, Y H:i', strtotime($charData['lastLoginTime'])) : 'Never'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Last Logout</td>
                                        <td><?php echo $charData['lastLogoutTime'] ? date('M d, Y H:i', strtotime($charData['lastLogoutTime'])) : 'Never'; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Online Status</td>
                                        <td>
                                            <span class="status-badge <?php echo $charData['OnlineStatus'] ? 'online' : 'offline'; ?>">
                                                <?php echo $charData['OnlineStatus'] ? 'Online' : 'Offline'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Survival Time</td>
                                        <td><?php echo formatTimeDisplay($charData['SurvivalTime']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Character Created</td>
                                        <td><?php echo $charData['BirthDay'] ? date('M d, Y', intval($charData['BirthDay'])) : 'Unknown'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Admin Actions Section -->
            <section id="admin">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">
                        <i class="fas fa-cog"></i>
                        Admin Actions
                    </h2>
                </div>
                
                <div class="dashboard-grid">
                    <!-- Admin Tools -->
                    <div class="card col-span-12">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tools"></i>
                                Admin Tools
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="dashboard-grid">
                                <div class="col-span-3">
                                    <button class="btn btn-primary" style="width: 100%" onclick="openModal('editCharacterModal')">
                                        <i class="fas fa-edit"></i>
                                        Edit Character
                                    </button>
                                </div>
                                <div class="col-span-3">
                                    <button class="btn btn-primary" style="width: 100%" onclick="openModal('sendMessageModal')">
                                        <i class="fas fa-envelope"></i>
                                        Send Message
                                    </button>
                                </div>
                                <div class="col-span-3">
                                    <button class="btn btn-primary" style="width: 100%" onclick="openModal('teleportModal')">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Teleport Character
                                    </button>
                                </div>
                                <div class="col-span-3">
                                    <button class="btn btn-primary" style="width: 100%" onclick="openModal('itemsModal')">
                                        <i class="fas fa-gift"></i>
                                        Send Items
                                    </button>
                                </div>
                                <div class="col-span-3">
                                    <button class="btn btn-danger" style="width: 100%" onclick="openModal('restrictionModal')">
                                        <i class="fas fa-ban"></i>
                                        Apply Restriction
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
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

<script>
// Function to handle modal opening
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

// Function to handle modal closing
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Initialize section navigation
document.addEventListener('DOMContentLoaded', function() {
    // Section navigation
    const navLinks = document.querySelectorAll('.nav-item a');
    const sections = document.querySelectorAll('section');
    
    // Set the first section as active by default
    navLinks[0].classList.add('active');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            navLinks.forEach(link => {
                link.classList.remove('active');
            });
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Get the target section
            const targetSection = document.querySelector(this.getAttribute('href'));
            
            // Scroll to the target section
            if (targetSection) {
                window.scrollTo({
                    top: targetSection.offsetTop - 20,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-toggle')) {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
    });
    
    // Close modals when clicking outside the modal content or pressing escape
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.active').forEach(modal => {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    });
});
</script>

<?php
include '../../includes/admin-footer.php';
?>