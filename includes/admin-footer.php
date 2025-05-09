</main>
    
    <footer class="admin-footer">
        <div class="container">
            <div class="admin-footer-content">
                <div class="admin-footer-logo">
                    L1J <span>Database</span> Admin
                </div>
                <div class="admin-footer-links">
                    <a href="<?php echo $rootPath; ?>admin/backup.php">Database Backup</a>
                    <a href="<?php echo $rootPath; ?>admin/logs.php">View Logs</a>
                    <a href="<?php echo $rootPath; ?>admin/settings.php">Settings</a>
                    <a href="<?php echo $rootPath; ?>admin/help.php">Help</a>
                </div>
            </div>
            <div class="admin-footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> L1J Database Administration. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="<?php echo $rootPath; ?>assets/js/functions.js"></script>
    <?php if (isset($extraJS)): ?>
        <?php foreach ($extraJS as $js): ?>
            <script src="<?php echo (strpos($js, 'http') === 0) ? $js : $rootPath . ltrim(str_replace('../', '', $js), '/'); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>