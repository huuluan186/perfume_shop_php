<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Brand.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập!']);
    exit;
}

$brand_id = intval($_GET['id'] ?? 0);
if ($brand_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Thương hiệu không hợp lệ!']);
    exit;
}

$brandModel = new Brand();
$brand = $brandModel->getByIdWithDeleted($brand_id);

if (!$brand) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy thương hiệu!']);
    exit;
}

// Get product count
$products = $brandModel->getAll();
$product_count = 0;
foreach ($products as $b) {
    if ($b['id'] == $brand_id) {
        $product_count = $b['product_count'];
        break;
    }
}
$brand['product_count'] = $product_count;

echo json_encode([
    'success' => true,
    'brand' => $brand
]);
?>
