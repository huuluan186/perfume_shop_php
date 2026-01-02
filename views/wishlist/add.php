<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Wishlist.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$product_id = intval($_POST['product_id'] ?? 0);

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ!']);
    exit;
}

$wishlistModel = new Wishlist();

// Kiểm tra đã tồn tại chưa
if ($wishlistModel->exists($_SESSION['user_id'], $product_id)) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm đã có trong danh sách yêu thích!']);
    exit;
}

$result = $wishlistModel->add($_SESSION['user_id'], $product_id);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Đã thêm vào danh sách yêu thích!',
        'wishlist_count' => $wishlistModel->countByUserId($_SESSION['user_id'])
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Thêm thất bại. Vui lòng thử lại!']);
}
?>
