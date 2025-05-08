<footer class="site-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-md-start text-center mb-3 mb-md-0">
                        <h4 class="text-accent mb-3">L1J Remastered Database</h4>
                        <p>A comprehensive database browser for the L1J Remastered game data.</p>
                    </div>
                    <div class="col-md-6 text-md-end text-center">
                        <div class="mb-3">
                            <a href="#" class="btn btn-sm btn-outline-primary me-2"><i class="fab fa-github"></i> GitHub</a>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="fab fa-discord"></i> Discord</a>
                        </div>
                        <p>&copy; <?= date('Y') ?> L1J Remastered Database. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div><!-- end .site-wrapper -->

    <!-- Bootstrap and jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: SweetAlert2 for better modals -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom JS -->
    <script src="<?= isset($is_admin) && $is_admin ? '../' : '' ?>assets/js/main.js"></script>
    <?php if (isset($is_admin) && $is_admin): ?>
    <script src="<?= isset($is_admin) && $is_admin ? '../' : '' ?>assets/js/admin.js"></script>
    <?php endif; ?>
    
    <!-- Page load animation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('loaded');
        });
    </script>
</body>
</html>
