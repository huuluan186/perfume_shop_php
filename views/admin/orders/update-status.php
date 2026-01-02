<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Order.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$order_id = intval($_POST['order_id'] ?? 0);
$status = intval($_POST['status'] ?? 0);

if ($order_id <= 0 || $status < 0) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ!']);
    exit;
}

$orderModel = new Order();
$result = $orderModel->updateStatus($order_id, $status);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại!']);
}
?>
