            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="admin-footer bg-dark text-white">
        <div class="container-fluid">
            <!-- Phần thành viên nhóm -->
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="text-center text-white mb-3">
                        <i class="fas fa-users me-2"></i>Thành viên nhóm
                    </h6>
                    <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                        <!-- Thành viên 1 -->
                        <div class="team-member-card">
                            <div class="p-3 border border-info rounded text-center h-100" style="background-color: rgba(13, 110, 253, 0.1);">
                                <h6 class="text-white mb-2 fw-bold small">PHẠM HỮU LUÂN</h6>
                                <p class="text-light mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-id-card me-1 text-info"></i>110122016
                                </p>
                                <p class="text-light mb-2" style="font-size: 0.85rem;">
                                    <i class="fas fa-graduation-cap me-1 text-info"></i>DA22TTA
                                </p>
                                <div class="social-links">
                                    <a href="https://github.com/huuluan186" target="_blank" class="btn btn-sm btn-outline-light me-1" title="GitHub">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <a href="https://www.facebook.com/huu.luan.791758" target="_blank" class="btn btn-sm btn-outline-light" title="Facebook">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thành viên 2 -->
                        <div class="team-member-card">
                            <div class="p-3 border border-info rounded text-center h-100" style="background-color: rgba(13, 110, 253, 0.1);">
                                <h6 class="text-white mb-2 fw-bold small">NGUYỄN HỮU ANH</h6>
                                <p class="text-light mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-id-card me-1 text-info"></i>110122033
                                </p>
                                <p class="text-light mb-2" style="font-size: 0.85rem;">
                                    <i class="fas fa-graduation-cap me-1 text-info"></i>DA22TTA
                                </p>
                                <div class="social-links">
                                    <a href="https://github.com/huuanh2512" target="_blank" class="btn btn-sm btn-outline-light me-1" title="GitHub">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <a href="https://www.facebook.com/hnauuh24" target="_blank" class="btn btn-sm btn-outline-light" title="Facebook">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thành viên 3 -->
                        <div class="team-member-card">
                            <div class="p-3 border border-info rounded text-center h-100" style="background-color: rgba(13, 110, 253, 0.1);">
                                <h6 class="text-white mb-2 fw-bold small">LÂM THANH ĐỈNH</h6>
                                <p class="text-light mb-1" style="font-size: 0.85rem;">
                                    <i class="fas fa-id-card me-1 text-info"></i>110122051
                                </p>
                                <p class="text-light mb-2" style="font-size: 0.85rem;">
                                    <i class="fas fa-graduation-cap me-1 text-info"></i>DA22TTA
                                </p>
                                <div class="social-links">
                                    <a href="https://github.com/LamThanhDinh" target="_blank" class="btn btn-sm btn-outline-light me-1" title="GitHub">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <a href="https://www.facebook.com/nottdd/" target="_blank" class="btn btn-sm btn-outline-light" title="Facebook">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="border-secondary my-2">
            
            <!-- Copyright -->
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0 text-light" style="font-size: 0.85rem;">
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
    
    <script>
        // Sidebar toggle - Thu gọn/Mở rộng
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('adminSidebar');
            const main = document.querySelector('.admin-main');
            const footer = document.querySelector('.admin-footer');
            const header = document.querySelector('.admin-header');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('collapsed');
                    
                    // Update header, main and footer margin
                    if (sidebar.classList.contains('collapsed')) {
                        if (header) header.style.left = '60px';
                        if (main) main.style.marginLeft = '60px';
                        if (footer) footer.style.marginLeft = '60px';
                    } else {
                        if (header) header.style.left = '260px';
                        if (main) main.style.marginLeft = '260px';
                        if (footer) footer.style.marginLeft = '260px';
                    }
                });
            }
        });
    </script>
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
