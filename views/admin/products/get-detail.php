<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Product.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập!']);
    exit();
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ!']);
    exit();
}

$productModel = new Product();
$product = $productModel->getById($product_id);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm!']);
    exit();
}

// Format image URL
if (!empty($product['duong_dan_hinh_anh'])) {
    $image_url = (strpos($product['duong_dan_hinh_anh'], '/') !== false) 
        ? ASSETS_URL . urldecode($product['duong_dan_hinh_anh']) 
        : UPLOAD_URL . $product['duong_dan_hinh_anh'];
} else {
    $image_url = ASSETS_URL . 'images/placeholder.png';
}

$product['image_url'] = $image_url;
$product['gia_ban_formatted'] = format_currency($product['gia_ban']);

echo json_encode(['success' => true, 'product' => $product]);
?>
