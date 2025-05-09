<?php
/**
 * Character Detail Page
 * Displays comprehensive character information
 */

require_once '../../../includes/db_connect.php';
require_once '../../includes/admin-config.php';
require_once '../../includes/account-functions.php';

$currentAdminPage = 'accounts';
$pageTitle = 'Character Details';

$charId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$charId) die('Invalid character ID');

$charData = getCharacterById($conn, $charId);
if (!$charData) die('Character not found');

$altCharacters = getAccountCharacters($conn, $charData['account_name']);

include '../../includes/admin-header.php';
?>

<link rel="stylesheet" href="<?= $adminBaseUrl ?>assets/css/admin-style.css">
<link rel="stylesheet" href="<?= $adminBaseUrl ?>assets/css/character-detail.css">

<div class="container">
    <div class="admin-hero">
        <div class="hero-header">
            <div class="admin-hero-content">
                <h1 class="admin-hero-title">Character Profile</h1>
                <div class="hero-actions">
                    <a href="account-list.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Accounts
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="character-main-card">
        <div class="character-header">
            <div class="character-title-section">
                <img src="<?= getClassImagePath($charData['Class'], $charData['gender']) ?>" 
                     class="character-avatar"
                     alt="<?= getClassName($charData['Class'], $charData['gender']) ?>">
                <div class="character-meta-info">
                    <h1 class="admin-hero-title"><?= htmlspecialchars($charData['char_name']) ?></h1>
                    <div class="account-info-badge">
                        <span class="account-access">Lv. <?= $charData['level'] ?> (Max: <?= $charData['HighLevel'] ?>)</span>
                        <span class="account-status active"><?= getClassName($charData['Class'], $charData['gender']) ?></span>
                        <?php if($charData['Title']): ?>
                        <span class="text-accent">"<?= htmlspecialchars($charData['Title']) ?>"</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="attributes-grid">
            <div class="attribute-card">
                <div class="account-info-label">Strength</div>
                <div class="attribute-value">
                    <?= $charData['Str'] ?> <small>(<?= $charData['BaseStr'] ?>)</small>
                </div>
            </div>
            <div class="attribute-card">
                <div class="account-info-label">Constitution</div>
                <div class="attribute-value">
                    <?= $charData['Con'] ?> <small>(<?= $charData['BaseCon'] ?>)</small>
                </div>
            </div>
            <div class="attribute-card">
                <div class="account-info-label">Dexterity</div>
                <div class="attribute-value">
                    <?= $charData['Dex'] ?> <small>(<?= $charData['BaseDex'] ?>)</small>
                </div>
            </div>
            <div class="attribute-card">
                <div class="account-info-label">Charisma</div>
                <div class="attribute-value">
                    <?= $charData['Cha'] ?> <small>(<?= $charData['BaseCha'] ?>)</small>
                </div>
            </div>
            <div class="attribute-card">
                <div class="account-info-label">Intelligence</div>
                <div class="attribute-value">
                    <?= $charData['Intel'] ?> <small>(<?= $charData['BaseIntel'] ?>)</small>
                </div>
            </div>
            <div class="attribute-card">
                <div class="account-info-label">Wisdom</div>
                <div class="attribute-value">
                    <?= $charData['Wis'] ?> <small>(<?= $charData['BaseWis'] ?>)</small>
                </div>
            </div>
        </div>

        <div class="detail-columns">
            <!-- Account Characters Card -->
            <div class="character-list-card">
                <h3 class="admin-card-title mb-3">Characters (<?= count($altCharacters) ?>)</h3>
                <div class="character-list-scroll">
                    <?php foreach($altCharacters as $altChar): ?>
                    <div class="compact-character-item">
                        <div class="compact-character-info">
                            <div class="character-name">
                                <a href="character-detail.php?id=<?= $altChar['objid'] ?>">
                                    <?= htmlspecialchars($altChar['char_name']) ?>
                                </a>
                            </div>
                            <div class="character-class"><?= getClassName($altChar['Class'], $altChar['gender']) ?></div>
                            <div class="text-right">Lv. <?= $altChar['level'] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="stats-combined-card">
                <h3 class="admin-card-title mb-3">Stats</h3>
                <div class="stat-item">
                    <div class="account-info-label">HP/MP</div>
                    <div class="account-info-value">
                        <?= $charData['CurHp'] ?>/<?= $charData['MaxHp'] ?> 
                        <span class="text-muted">|</span> 
                        <?= $charData['CurMp'] ?>/<?= $charData['MaxMp'] ?>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="account-info-label">AC/PK Count</div>
                    <div class="account-info-value">
                        <?= $charData['Ac'] ?> <span class="text-muted">|</span> <?= $charData['PKcount'] ?>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="account-info-label">PvP Stats</div>
                    <div class="account-info-value">
                        Kills: <?= $charData['PC_Kill'] ?> <span class="text-muted">|</span> Deaths: <?= $charData['PC_Death'] ?>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="account-info-label">Equipment Slots</div>
                    <div class="account-info-value">
                        Ring +<?= $charData['RingAddSlot'] ?> 
                        <span class="text-muted">|</span> 
                        Earring +<?= $charData['EarringAddSlot'] ?>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="account-info-label">Karma</div>
                    <div class="account-info-value"><?= $charData['Karma'] ?></div>
                </div>
                <div class="stat-item">
                    <div class="account-info-label">Experience</div>
                    <div class="stat-progress mt-2">
                        <div class="stat-progress-bar" 
                             style="width: <?= ($charData['Exp'] % 1000000)/10000 ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Others Card -->
            <div class="map-preview">
                <h3 class="admin-card-title mb-3">World & Clan</h3>
                <div class="account-info-label">Current Location</div>
                <div class="map-visual">
                    <div class="map-coordinates">
                        Map <?= $charData['MapID'] ?>: <?= $charData['LocX'] ?>, <?= $charData['LocY'] ?>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="account-info-label">Clan Status</div>
                    <div class="account-info-value mt-2">
                        <?php if($charData['Clanname']): ?>
                        <div><?= htmlspecialchars($charData['Clanname']) ?></div>
                        <div class="text-muted">Rank: <?= $charData['ClanRank'] ?> | Contribution: <?= $charData['ClanContribution'] ?></div>
                        <?php else: ?>
                        <div>No Clan</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="account-info-label">Activity</div>
                    <div class="account-info-value mt-2">
                        <?php if($charData['lastLoginTime']): ?>
                        <div>Last Login: <?= formatTimeUSA($charData['lastLoginTime']) ?></div>
                        <?php endif; ?>
                        <?php if($charData['BirthDay']): ?>
                        <div>Created: <?= formatTimeUSA($charData['BirthDay']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/admin-footer.php'; ?>