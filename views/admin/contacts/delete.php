<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Contact.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$contact_id = intval($_POST['id'] ?? 0);
if ($contact_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Liên hệ không hợp lệ!']);
    exit;
}

$contactModel = new Contact();

if ($contactModel->delete($contact_id)) {
    echo json_encode([
        'success' => true,
        'message' => 'Đã xóa liên hệ thành công!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Có lỗi xảy ra, vui lòng thử lại!'
    ]);
}
?>
