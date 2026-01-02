<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';

// Xóa session
session_destroy();

// Chuyển về trang chủ
$_SESSION['success'] = 'Đăng xuất thành công!';
redirect('index.php');
?>
