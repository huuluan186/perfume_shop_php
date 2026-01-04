<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Category.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập!']);
    exit;
}

$category_id = intval($_GET['id'] ?? 0);
if ($category_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Danh mục không hợp lệ!']);
    exit;
}

$categoryModel = new Category();
$category = $categoryModel->getById($category_id);

if (!$category) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy danh mục!']);
    exit;
}

echo json_encode([
    'success' => true,
    'category' => $category
]);
?>
