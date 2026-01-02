<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Order.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$order_id = intval($_POST['order_id'] ?? 0);

if ($order_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Đơn hàng không hợp lệ!']);
    exit;
}

$orderModel = new Order();
$result = $orderModel->cancel($order_id, $_SESSION['user_id']);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Đã hủy đơn hàng thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng. Vui lòng liên hệ hỗ trợ!']);
}
?>
