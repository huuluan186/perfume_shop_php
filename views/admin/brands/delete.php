<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Brand.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$brand_id = intval($_POST['id'] ?? 0);

if ($brand_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Thương hiệu không hợp lệ!']);
    exit;
}

$brandModel = new Brand();
$result = $brandModel->softDelete($brand_id);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Đã xóa thương hiệu thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Xóa thương hiệu thất bại!']);
}
?>
