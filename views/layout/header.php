<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Cửa hàng nước hoa'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
</head>
<body>
<?php 
// Kiểm tra trạng thái tài khoản - tự động đăng xuất nếu bị khóa
if (is_logged_in() && !is_admin()) {
    check_account_status();
}
?>
    <!-- Header Wrapper - Sticky cả khối -->
    <div class="header-wrapper sticky-top" style="z-index: 1030;">
        <!-- Top Bar -->
        <div class="top-bar bg-dark text-white py-2">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <small><i class="fas fa-phone me-2"></i>Hotline: 1900 1836</small>
                        <small class="ms-3"><i class="fas fa-envelope me-2"></i>contact@perfumeshop.com</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <!-- Search -->
                        <form action="<?php echo BASE_URL; ?>views/products/index.php" method="GET" class="d-inline-flex">
                            <input type="search" name="search" class="form-control form-control-sm" placeholder="Tìm kiếm sản phẩm..." style="width: 250px;">
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
                        <?php if (is_logged_in()): ?>
                            <small>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></small>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>views/auth/login.php" class="text-white text-decoration-none me-3">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                            </a>
                            <a href="<?php echo BASE_URL; ?>views/auth/register.php" class="text-white text-decoration-none">
                                <i class="fas fa-user-plus me-1"></i>Đăng ký
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <header class="header bg-white shadow-sm">
            <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light py-3">
             <a class="navbar-brand d-flex align-items-center"
             href="<?php echo BASE_URL; ?>">
            <img src="<?php echo BASE_URL; ?>/assets/images/logo.png"
             alt="Perfume Shop"
             height="40"
             class="me-2">
    </a>

                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <?php
                    $current_page = basename($_SERVER['PHP_SELF']);
                    $current_dir = basename(dirname($_SERVER['PHP_SELF']));
                    ?>
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'index.php' && $current_dir != 'products' && $current_dir != 'brands') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>">
                                <i class="fas fa-home me-1"></i>Trang chủ
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link <?php echo ($current_dir == 'products') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>views/products/index.php" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Sản phẩm <i class="fas fa-chevron-down ms-1" style="font-size: 0.7em;"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                                <?php
                                require_once __DIR__ . '/../../models/Category.php';
                                $categoryModel = new Category();
                                $categories = $categoryModel->getAll();
                                foreach ($categories as $cat):
                                ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>views/products/index.php?category=<?php echo $cat['id']; ?>"><i class="fas fa-tag me-2"></i><?php echo htmlspecialchars($cat['ten_danh_muc']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_dir == 'brands') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>views/brands/index.php">
                                Thương hiệu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>views/about.php">
                                Giới thiệu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>views/contact.php">
                                Liên hệ
                            </a>
                        </li>
                    </ul>
                    
                    <div class="d-flex align-items-center">
                        <!-- Wishlist -->
                        <?php if (is_logged_in()): ?>
                        <a href="<?php echo BASE_URL; ?>views/wishlist/index.php" class="text-dark me-3 position-relative">
                            <i class="fas fa-heart fa-lg"></i>
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle wishlist-badge" id="wishlistCount">0</span>
                        </a>
                        <?php endif; ?>
                        
                        <!-- Cart -->
                        <a href="<?php echo BASE_URL; ?>views/cart/index.php" class="text-dark me-3 position-relative">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            <span class="badge bg-primary position-absolute top-0 start-100 translate-middle cart-badge" id="cartCount"><?php echo count_cart_items(); ?></span>
                        </a>
                        
                        <!-- User Menu -->
                        <?php if (is_logged_in()): ?>
                        <div class="dropdown">
                            <a class="text-dark dropdown-toggle" href="#" role="button" id="userDropdown" 
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>views/account/profile.php"><i class="fas fa-user me-2"></i>Tài khoản</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>views/account/orders.php"><i class="fas fa-box me-2"></i>Đơn hàng</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>views/wishlist/index.php"><i class="fas fa-heart me-2"></i>Yêu thích</a></li>
                                <?php if (is_admin()): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>views/admin/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Quản trị</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>views/auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    </div>
    <!-- End Header Wrapper -->

    <!-- Toast Notification Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <?php
        $message = show_message('success') ?? show_message('error') ?? show_message('warning') ?? show_message('info');
        if ($message):
            $icon_map = [
                'success' => 'fa-check-circle',
                'error' => 'fa-times-circle',
                'warning' => 'fa-exclamation-triangle',
                'info' => 'fa-info-circle'
            ];
            $bg_map = [
                'success' => 'success',
                'error' => 'danger',
                'warning' => 'warning',
                'info' => 'info'
            ];
            $msg_type = $message['type'];
            $bg_class = $bg_map[$msg_type] ?? 'info';
            $icon_class = $icon_map[$msg_type] ?? 'fa-info-circle';
        ?>
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000" style="box-shadow: 0 8px 24px rgba(0,0,0,0.25); border: none; min-width: 300px;">
            <div class="toast-header bg-<?php echo $bg_class; ?> text-white" style="border: none;">
                <i class="fas <?php echo $icon_class; ?> me-2"></i>
                <strong class="me-auto">
                    <?php 
                    echo $msg_type === 'success' ? 'Thành công' : 
                         ($msg_type === 'error' ? 'Lỗi' : 
                         ($msg_type === 'warning' ? 'Cảnh báo' : 'Thông báo'));
                    ?>
                </strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" style="background-color: #fff; color: #333; font-weight: 500;">
                <?php echo htmlspecialchars($message['message']); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
    // Auto hide toast after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toastElList = document.querySelectorAll('.toast');
        toastElList.forEach(function(toastEl) {
            const toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 3000
            });
            toast.show();
        });
    });
    </script>

    <!-- Dropdown Hover Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = document.querySelectorAll('.dropdown');
        
        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle, .nav-link[data-bs-toggle="dropdown"]');
            const menu = dropdown.querySelector('.dropdown-menu');
            let timeout;
            
            // Ngăn chặn Bootstrap dropdown khi click - cho phép redirect
            if (toggle) {
                toggle.addEventListener('click', function(e) {
                    // Nếu có href và không phải # thì cho redirect
                    if (this.getAttribute('href') && this.getAttribute('href') !== '#') {
                        window.location.href = this.getAttribute('href');
                        e.preventDefault();
                        return false;
                    }
                });
            }
            
            // Hover vào dropdown
            dropdown.addEventListener('mouseenter', function() {
                clearTimeout(timeout);
                if (toggle) {
                    const bsDropdown = new bootstrap.Dropdown(toggle);
                    bsDropdown.show();
                }
            });
            
            // Hover ra khỏi dropdown
            dropdown.addEventListener('mouseleave', function() {
                timeout = setTimeout(() => {
                    if (toggle) {
                        const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                        if (bsDropdown) {
                            bsDropdown.hide();
                        }
                    }
                }, 200);
            });
            
            // Giữ menu mở khi hover vào menu
            if (menu) {
                menu.addEventListener('mouseenter', function() {
                    clearTimeout(timeout);
                });
                
                menu.addEventListener('mouseleave', function() {
                    timeout = setTimeout(() => {
                        if (toggle) {
                            const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                            if (bsDropdown) {
                                bsDropdown.hide();
                            }
                        }
                    }, 200);
                });
            }
        });
    });
    </script>

    <main class="main-content">
