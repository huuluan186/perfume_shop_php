<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Order.php';

// Log để debug
error_log("cancel-order.php called");
error_log("POST data: " . print_r($_POST, true));
error_log("Session: " . print_r($_SESSION, true));

header('Content-Type: application/json');

if (!is_logged_in()) {
    error_log("User not logged in");
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$order_id = intval($_POST['order_id'] ?? 0);
error_log("Order ID to cancel: $order_id");

if ($order_id <= 0) {
    error_log("Invalid order ID");
    echo json_encode(['success' => false, 'message' => 'Đơn hàng không hợp lệ!']);
    exit;
}

$orderModel = new Order();

// Kiểm tra xem đơn hàng có thuộc về user không
$order = $orderModel->getById($order_id);
error_log("Order data: " . print_r($order, true));

if (!$order) {
    error_log("Order not found");
    echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại!']);
    exit;
}

if ($order['id_nguoi_dung'] != $_SESSION['user_id']) {
    error_log("Order does not belong to user. Order user: " . $order['id_nguoi_dung'] . ", Session user: " . $_SESSION['user_id']);
    echo json_encode(['success' => false, 'message' => 'Đơn hàng không thuộc về bạn!']);
    exit;
}

// Chỉ cho phép hủy đơn hàng chưa duyệt
$status = (int)$order['trang_thai'];
error_log("Order status: $status, ORDER_STATUS_PENDING: " . ORDER_STATUS_PENDING);

if ($status !== ORDER_STATUS_PENDING) {
    $msg = "Chỉ có thể hủy đơn hàng chưa duyệt! (Trạng thái hiện tại: $status)";
    error_log($msg);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

$result = $orderModel->cancel($order_id);
error_log("Cancel result: " . ($result ? 'true' : 'false'));

if ($result) {
    error_log("Order cancelled successfully");
    echo json_encode(['success' => true, 'message' => 'Đã hủy đơn hàng thành công!']);
} else {
    error_log("Failed to cancel order");
    echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng. Có lỗi xảy ra trong quá trình xử lý!']);
}

?>
