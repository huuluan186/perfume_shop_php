<?php
// Khởi động session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Định nghĩa các đường dẫn
define('BASE_URL', 'http://localhost/perfume_shop_php/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

// Số sản phẩm trên mỗi trang
define('PRODUCTS_PER_PAGE', 9);
define('ORDERS_PER_PAGE', 8);

// Múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Các vai trò người dùng
define('ROLE_ADMIN', 'quan_tri_vien');
define('ROLE_CUSTOMER', 'khach_hang');

// Trạng thái đơn hàng (int)
define('ORDER_STATUS_PENDING', 0);      // Chưa duyệt
define('ORDER_STATUS_APPROVED', 1);     // Đã duyệt
define('ORDER_STATUS_SHIPPING', 2);     // Đang giao hàng
define('ORDER_STATUS_COMPLETED', 3);    // Hoàn thành
define('ORDER_STATUS_CANCELLED', 4);    // Đã hủy

// Require file database
require_once __DIR__ . '/database.php';
?>
