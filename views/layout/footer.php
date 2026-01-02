    </main>

    <!-- Footer -->
    <footer class="footer bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-spray-can me-2"></i>Perfume Shop
                    </h5>
                    <p class="text-light-50">
                        Cửa hàng nước hoa cao cấp chuyên cung cấp các sản phẩm nước hoa chính hãng từ các thương hiệu nổi tiếng thế giới.
                    </p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-youtube fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-tiktok fa-lg"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5 class="mb-3">Liên kết</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>" class="text-light-50 text-decoration-none">Trang chủ</a></li>
                        <li><a href="<?php echo BASE_URL; ?>views/products/index.php" class="text-light-50 text-decoration-none">Sản phẩm</a></li>
                        <li><a href="<?php echo BASE_URL; ?>views/brands/index.php" class="text-light-50 text-decoration-none">Thương hiệu</a></li>
                        <li><a href="<?php echo BASE_URL; ?>views/about.php" class="text-light-50 text-decoration-none">Giới thiệu</a></li>
                        <li><a href="<?php echo BASE_URL; ?>views/contact.php" class="text-light-50 text-decoration-none">Liên hệ</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="mb-3">Chính sách</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light-50 text-decoration-none">Chính sách bảo mật</a></li>
                        <li><a href="#" class="text-light-50 text-decoration-none">Điều khoản dịch vụ</a></li>
                        <li><a href="#" class="text-light-50 text-decoration-none">Chính sách đổi trả</a></li>
                        <li><a href="#" class="text-light-50 text-decoration-none">Hướng dẫn mua hàng</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="mb-3">Liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span class="text-light-50">123 Đường ABC, Quận 1, TP.HCM</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <span class="text-light-50">1900 xxxx</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            <span class="text-light-50">contact@perfumeshop.com</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock me-2"></i>
                            <span class="text-light-50">8:00 - 22:00 (Hàng ngày)</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-secondary my-4">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-light-50">
                        &copy; 2026 Perfume Shop. Bản quyền thuộc về nhóm sinh viên.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-light-50">
                        <strong>Đề tài:</strong> Website bán nước hoa | <strong>Môn học:</strong> Lập trình Web
                    </p>
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-12 text-center">
                    <p class="mb-0 text-light-50 small">
                        <strong>Nhóm thực hiện:</strong> [Tên nhóm] - [Danh sách sinh viên] - [Lớp] - [Khóa]
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="btn btn-primary position-fixed bottom-0 end-0 m-4" style="display: none; z-index: 999;">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo ASSETS_URL; ?>js/main.js"></script>
    
    <script>
        // Update cart and wishlist counts on page load
        $(document).ready(function() {
            updateCartCount();
            updateWishlistCount();
        });
    </script>
</body>
</html>
