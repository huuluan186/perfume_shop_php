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
$order = $orderModel->getByIdWithDeleted($order_id);

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng!']);
    exit;
}

// Lấy email từ bảng nguoi_dung nếu có id_nguoi_dung
if (!empty($order['id_nguoi_dung'])) {
    $database = new Database();
    $conn = $database->connect();
    $stmt = $conn->prepare("SELECT email FROM nguoi_dung WHERE id = ?");
    $stmt->bind_param("i", $order['id_nguoi_dung']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($userData = $result->fetch_assoc()) {
        $order['email'] = $userData['email'];
    }
}

// Lấy chi tiết sản phẩm
$items = $orderModel->getOrderDetails($order_id);

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
        'don_gia' => $item['don_gia'],
        'don_gia_formatted' => format_currency($item['don_gia']),
        'thanh_tien_formatted' => format_currency($item['don_gia'] * $item['so_luong'])
    ];
}

echo json_encode([
    'success' => true,
    'order' => $order,
    'items' => $formatted_items
]);
?>
