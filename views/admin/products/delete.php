<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Product.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$product_id = intval($_POST['id'] ?? 0);

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không hợp lệ!']);
    exit;
}

$productModel = new Product();
$result = $productModel->softDelete($product_id);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Đã xóa sản phẩm thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Xóa sản phẩm thất bại!']);
}
?>
