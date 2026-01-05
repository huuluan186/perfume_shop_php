<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Order.php';
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập!']);
    exit;
}

$order_id = intval($_GET['id'] ?? 0);
if ($order_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Đơn hàng không hợp lệ!']);
    exit;
}

$orderModel = new Order();
$order_details_result = $orderModel->getOrderDetails($order_id, true); // include deleted for admin

if (!$order_details_result) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng!']);
    exit;
}

$order = $order_details_result['order'];
$items = $order_details_result['items'];

// Format giá tiền
$order['tong_tien_formatted'] = format_currency($order['tong_tien']);

// Format items
$formatted_items = [];
foreach ($items as $item) {
    $formatted_items[] = [
        'id' => $item['id'],
        'ten_san_pham' => $item['ten_san_pham'],
        'duong_dan_hinh_anh' => $item['duong_dan_hinh_anh'] ?? '',
        'so_luong' => $item['so_luong'],
        'don_gia' => $item['gia_ban'], // Alias đã đổi từ don_gia thành gia_ban
        'don_gia_formatted' => format_currency($item['gia_ban']),
        'thanh_tien_formatted' => format_currency($item['gia_ban'] * $item['so_luong']),
        'ngay_xoa' => $item['ngay_xoa'] ?? null
    ];
}

echo json_encode([
    'success' => true,
    'order' => $order,
    'items' => $formatted_items
]);
?>
