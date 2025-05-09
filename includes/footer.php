<?php
/**
 * Footer component for public section
 */
?>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Equipment</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/weapons/weapon-list.php">Weapons</a></li>
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/armor/armor-list.php">Armor</a></li>
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/items/item-list.php">Items</a></li>
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/dolls/doll-list.php">Magic Dolls</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>World</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/monsters/monster-list.php">Monsters</a></li>
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/npcs/npc-list.php">NPCs</a></li>
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/maps/map-list.php">Maps</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Character</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/skills/skill-list.php">Skills</a></li>
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/polymorph/polymorph-list.php">Polymorph</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Resources</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/about.php">About</a></li>
                        <li class="footer-link"><a href="<?php echo $baseUrl; ?>pages/contact.php">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> Lineage II Database. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?php echo $baseUrl; ?>assets/js/main.js"></script>
</body>
</html>
