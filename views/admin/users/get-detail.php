<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../models/Order.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập!']);
    exit;
}

$user_id = intval($_GET['id'] ?? 0);
if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Người dùng không hợp lệ!']);
    exit;
}

$userModel = new User();
$user = $userModel->getUserById($user_id);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy người dùng!']);
    exit;
}

// Lấy thống kê đơn hàng
$orderModel = new Order();
$orders = $orderModel->getByUserId($user_id, 1000, 0); // Lấy tất cả đơn hàng
$total_orders = count($orders);
$total_spent = 0;

foreach ($orders as $order) {
    if ($order['trang_thai'] != ORDER_STATUS_CANCELLED) {
        $total_spent += $order['tong_tien'];
    }
}

// Format dữ liệu
$user['total_orders'] = $total_orders;
$user['total_spent'] = $total_spent;
$user['total_spent_formatted'] = format_currency($total_spent);
$user['ngay_tao_formatted'] = format_date($user['ngay_tao']);
$user['ngay_sinh_formatted'] = $user['ngay_sinh'] ? format_date($user['ngay_sinh']) : null;

// Format danh sách đơn hàng gần đây (5 đơn)
$recent_orders = [];
$order_count = 0;
foreach ($orders as $order) {
    if ($order_count >= 5) break;
    $recent_orders[] = [
        'id' => $order['id'],
        'ngay_dat' => format_date($order['ngay_dat']),
        'tong_tien' => format_currency($order['tong_tien']),
        'trang_thai' => $order['trang_thai']
    ];
    $order_count++;
}

echo json_encode([
    'success' => true,
    'user' => $user,
    'orders' => $recent_orders
], JSON_UNESCAPED_UNICODE);
?>
