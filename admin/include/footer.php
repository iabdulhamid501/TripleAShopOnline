<!-- Admin Footer - VELORIA Style -->
<footer class="veloria-admin-footer mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> Triple A ShopOnline | Admin Panel. All rights reserved.
            </div>
            <div class="footer-links">
                <a href="../index.php"><i class="fas fa-store"></i> Back to Shop</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .veloria-admin-footer {
        background: transparent;
        border-top: 1px solid #EDE5DE;
        padding: 1rem 0;
        margin-top: 2rem;
        font-family: 'Inter', sans-serif;
    }
    .veloria-admin-footer .copyright {
        color: #7A726C;
        font-size: 0.75rem;
        font-weight: 400;
    }
    .veloria-admin-footer .footer-links a {
        color: #C47A5E;
        text-decoration: none;
        font-size: 0.75rem;
        transition: 0.2s;
    }
    .veloria-admin-footer .footer-links a:hover {
        text-decoration: underline;
        color: #A85E44;
    }
</style>