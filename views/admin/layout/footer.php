            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container-fluid">
            <!-- Phần thành viên nhóm -->
            <div class="row mb-3">
                <div class="col-12">
                    <h5 class="text-center text-white mb-4">
                        <i class="fas fa-users me-2"></i>Thành viên nhóm
                    </h5>
                    <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap" style="max-width: 920px; margin: 0 auto;">
                        <!-- Thành viên 1 -->
                        <div style="width: 280px;">
                            <div class="team-member p-3 border border-info rounded h-100 text-center" style="background-color: rgba(13, 110, 253, 0.1);">
                                <h6 class="text-white mb-2 fw-bold">PHẠM HỮU LUÂN</h6>
                                <p class="text-light small mb-2">
                                    <i class="fas fa-id-card me-1 text-info"></i>110122016
                                </p>
                                <p class="text-light small mb-3">
                                    <i class="fas fa-graduation-cap me-1 text-info"></i>DA22TTA
                                </p>
                                <div class="social-links">
                                    <a href="https://github.com/username1" target="_blank" class="btn btn-sm btn-outline-light me-2" title="GitHub">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <a href="https://facebook.com/username1" target="_blank" class="btn btn-sm btn-outline-light" title="Facebook">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thành viên 2 -->
                        <div style="width: 280px;">
                            <div class="team-member p-3 border border-info rounded h-100 text-center" style="background-color: rgba(13, 110, 253, 0.1);">
                                <h6 class="text-white mb-2 fw-bold">NGUYỄN HỮU ANH</h6>
                                <p class="text-light small mb-2">
                                    <i class="fas fa-id-card me-1 text-info"></i>110122033
                                </p>
                                <p class="text-light small mb-3">
                                    <i class="fas fa-graduation-cap me-1 text-info"></i>DA22TTA
                                </p>
                                <div class="social-links">
                                    <a href="https://github.com/username2" target="_blank" class="btn btn-sm btn-outline-light me-2" title="GitHub">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <a href="https://facebook.com/username2" target="_blank" class="btn btn-sm btn-outline-light" title="Facebook">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thành viên 3 -->
                        <div style="width: 280px;">
                            <div class="team-member p-3 border border-info rounded h-100 text-center" style="background-color: rgba(13, 110, 253, 0.1);">
                                <h6 class="text-white mb-2 fw-bold">LÂM THANH ĐỈNH</h6>
                                <p class="text-light small mb-2">
                                    <i class="fas fa-id-card me-1 text-info"></i>110122051
                                </p>
                                <p class="text-light small mb-3">
                                    <i class="fas fa-graduation-cap me-1 text-info"></i>DA22TTA
                                </p>
                                <div class="social-links">
                                    <a href="https://github.com/username3" target="_blank" class="btn btn-sm btn-outline-light me-2" title="GitHub">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <a href="https://facebook.com/username3" target="_blank" class="btn btn-sm btn-outline-light" title="Facebook">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="border-secondary my-3">
            
            <!-- Copyright -->
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0 text-light small">
                        &copy; 2026 Perfume Shop. Đồ án môn học Phát triển ứng dụng Web với mã nguồn mở - Nhóm sinh viên DA22TTA.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery (load first) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js for Dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo ASSETS_URL; ?>js/admin.js"></script>
    
    <!-- Initialize Bootstrap dropdowns -->
    <script>
        // Ensure Bootstrap dropdowns are initialized
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
            
            console.log('Bootstrap dropdowns initialized:', dropdownList.length);
        });
    </script>
</body>
</html>
