<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Category.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$category_id = intval($_POST['id'] ?? 0);

if ($category_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Danh mục không hợp lệ!']);
    exit;
}

$categoryModel = new Category();
$result = $categoryModel->delete($category_id);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Đã xóa danh mục thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Xóa danh mục thất bại!']);
}
?>
