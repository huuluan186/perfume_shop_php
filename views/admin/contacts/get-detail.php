<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Contact.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập!']);
    exit;
}

$contact_id = intval($_GET['id'] ?? 0);
if ($contact_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Liên hệ không hợp lệ!']);
    exit;
}

$contactModel = new Contact();
$contact = $contactModel->getById($contact_id);

if (!$contact) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy liên hệ!']);
    exit;
}

// Format dữ liệu
$contact['thoi_gian_gui_formatted'] = format_datetime($contact['thoi_gian_gui']);

echo json_encode([
    'success' => true,
    'contact' => $contact
], JSON_UNESCAPED_UNICODE);
?>
