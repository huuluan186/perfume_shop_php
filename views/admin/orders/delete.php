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

$order_id = intval($_POST['id'] ?? 0);

if ($order_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Đơn hàng không hợp lệ!']);
    exit;
}

$orderModel = new Order();
$result = $orderModel->softDelete($order_id);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Đã xóa đơn hàng thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Xóa đơn hàng thất bại!']);
}
?>
