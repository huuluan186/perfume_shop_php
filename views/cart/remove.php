<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$cart_key = $_POST['cart_key'] ?? '';

if (empty($cart_key) || !isset($_SESSION['cart'][$cart_key])) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ!']);
    exit;
}

unset($_SESSION['cart'][$cart_key]);

echo json_encode([
    'success' => true,
    'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
    'cart_count' => count_cart_items()
]);
?>
