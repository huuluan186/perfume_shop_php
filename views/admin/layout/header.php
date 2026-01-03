<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Panel'; ?> - Perfume Shop</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/admin.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, .05);
            overflow-y: auto;
            background: #fff;
        }
        .sidebar .nav-link {
            color: #495057;
            padding: 14px 24px;
            border-radius: 0;
            transition: all 0.3s;
            font-size: 0.95rem;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover {
            background: #f8f9fa;
            color: #0d6efd;
            border-left-color: #0d6efd;
        }
        .sidebar .nav-link.active {
            background: linear-gradient(90deg, #e7f1ff 0%, #fff 100%);
            color: #0d6efd;
            border-left-color: #0d6efd;
            font-weight: 500;
        }
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
        }
        main {
            margin-left: 16.666667%;
            padding: 24px;
            min-height: calc(100vh - 56px);
        }
        @media (max-width: 768px) {
            main {
                margin-left: 0;
            }
            .sidebar {
                display: none;
            }
        }
        .card {
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>views/admin/dashboard.php">
                <i class="fas fa-spray-can me-2"></i>Perfume Shop - Quản Trị
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>" target="_blank">
                            <i class="fas fa-globe me-1"></i>Về trang website
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i><?php echo $_SESSION['username'] ?? 'Admin'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>views/account/profile.php">
                                <i class="fas fa-user me-2"></i>Tài khoản
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>views/auth/logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block bg-white sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'dashboard.php') !== false ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>views/admin/dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/admin/products/') !== false ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>views/admin/products/index.php">
                                <i class="fas fa-box me-2"></i>Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/admin/orders/') !== false ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>views/admin/orders/index.php">
                                <i class="fas fa-shopping-cart me-2"></i>Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/admin/categories/') !== false ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>views/admin/categories/index.php">
                                <i class="fas fa-tags me-2"></i>Danh mục
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/admin/brands/') !== false ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>views/admin/brands/index.php">
                                <i class="fas fa-copyright me-2"></i>Thương hiệu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/admin/users/') !== false ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>views/admin/users/index.php">
                                <i class="fas fa-users me-2"></i>Người dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'contacts.php') !== false ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>views/admin/contacts.php">
                                <i class="fas fa-envelope me-2"></i>Liên hệ
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['warning'])): ?>
                    <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
