<?php
// Khởi động session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Định nghĩa các đường dẫn
define('BASE_URL', 'http://localhost/perfume_shop_php/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('UPLOAD_PATH', __DIR__ . '/../assets/products/');
define('UPLOAD_URL', ASSETS_URL . 'products/');

// Số sản phẩm trên mỗi trang
define('PRODUCTS_PER_PAGE', 9);
define('ORDERS_PER_PAGE', 10);

// Múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Các vai trò người dùng
define('ROLE_ADMIN', 'quan_tri_vien');
define('ROLE_CUSTOMER', 'khach_hang');

// Trạng thái đơn hàng
define('ORDER_STATUS_PENDING', 'cho_xu_ly');
define('ORDER_STATUS_CONFIRMED', 'da_xac_nhan');
define('ORDER_STATUS_SHIPPING', 'dang_giao');
define('ORDER_STATUS_DELIVERED', 'da_giao');
define('ORDER_STATUS_COMPLETED', 'hoan_thanh');
define('ORDER_STATUS_CANCELLED', 'da_huy');

// Require file database
require_once __DIR__ . '/database.php';
?>
