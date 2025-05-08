</div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> L1J Remastered Database Admin</p>
        </div>
    </footer>
    
    <script src="/public/js/main.js"></script>
    <script src="/public/js/admin.js"></script>
    <?php if (isset($extraScripts) && !empty($extraScripts)): ?>
        <?php foreach ($extraScripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
