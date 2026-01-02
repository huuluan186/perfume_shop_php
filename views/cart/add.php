<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$product_id = intval($_POST['product_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);

if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ!']);
    exit;
}

// Lấy thông tin sản phẩm
require_once __DIR__ . '/../../models/Product.php';
$productModel = new Product();
$product = $productModel->getById($product_id);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại!']);
    exit;
}

// Kiểm tra tồn kho
if ($product['so_luong_ton'] < $quantity) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không đủ số lượng!']);
    exit;
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Thêm hoặc cập nhật sản phẩm trong giỏ
$cart_key = 'product_' . $product_id;

if (isset($_SESSION['cart'][$cart_key])) {
    $_SESSION['cart'][$cart_key]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$cart_key] = [
        'product_id' => $product_id,
        'name' => $product['ten_san_pham'],
        'price' => $product['gia_ban'],
        'image' => $product['duong_dan_hinh_anh'],
        'quantity' => $quantity,
        'brand' => $product['ten_thuong_hieu']
    ];
}

echo json_encode([
    'success' => true,
    'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
    'cart_count' => count_cart_items()
]);
?>
