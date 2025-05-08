</main>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-column">
                    <h3>Navigation</h3>
                    <ul class="footer-links">
                        <li><a href="weapons/">Weapons</a></li>
                        <li><a href="armor/">Armor</a></li>
                        <li><a href="items/">Items</a></li>
                        <li><a href="monsters/">Monsters</a></li>
                        <li><a href="maps/">Maps</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Resources</h3>
                    <ul class="footer-links">
                        <li><a href="dolls/">Magic Dolls</a></li>
                        <li><a href="npcs/">NPCs</a></li>
                        <li><a href="skills/">Skills</a></li>
                        <li><a href="polymorph/">Polymorph</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Admin</h3>
                    <ul class="footer-links">
                        <li><a href="admin/">Admin Dashboard</a></li>
                        <li><a href="admin/items/">Manage Items</a></li>
                        <li><a href="admin/monsters/">Manage Monsters</a></li>
                        <li><a href="admin/maps/">Manage Maps</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>About</h3>
                    <ul class="footer-links">
                        <li><a href="about.php">About L1J Database</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms of Use</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> L1J Database. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="assets/js/functions.js"></script>
    <?php if (isset($extraJS)): ?>
        <?php foreach ($extraJS as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
