<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$cart_key = $_POST['cart_key'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($cart_key) || !isset($_SESSION['cart'][$cart_key])) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ!']);
    exit;
}

if ($action === 'increase') {
    $_SESSION['cart'][$cart_key]['quantity']++;
} elseif ($action === 'decrease') {
    if ($_SESSION['cart'][$cart_key]['quantity'] > 1) {
        $_SESSION['cart'][$cart_key]['quantity']--;
    } else {
        echo json_encode(['success' => false, 'message' => 'Số lượng tối thiểu là 1!']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ!']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Đã cập nhật giỏ hàng!',
    'cart_count' => count_cart_items()
]);
?>
