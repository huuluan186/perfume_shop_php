<?php
// File test để kiểm tra database connection và query
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/Order.php';

session_start();

// Giả lập login (thay đổi user_id phù hợp)
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'test';

$orderModel = new Order();

echo "<h2>Test Order Details</h2>";

// Lấy tất cả đơn hàng của user
$orders = $orderModel->getByUserId($_SESSION['user_id'], 100, 0);

echo "<h3>Tổng số đơn hàng: " . count($orders) . "</h3>";

foreach ($orders as $order) {
    echo "<hr>";
    echo "<h4>Đơn hàng #" . $order['id'] . "</h4>";
    echo "<p>Trạng thái: " . $order['trang_thai'] . "</p>";
    echo "<p>Tổng tiền: " . number_format($order['tong_tien']) . " đ</p>";
    
    // Lấy chi tiết
    $details = $orderModel->getOrderDetails($order['id']);
    echo "<p>Số sản phẩm: " . count($details) . "</p>";
    
    if (!empty($details)) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID SP</th><th>Tên SP</th><th>Ảnh</th><th>SL</th><th>Giá</th></tr>";
        foreach ($details as $detail) {
            echo "<tr>";
            echo "<td>" . ($detail['id_san_pham'] ?? 'N/A') . "</td>";
            echo "<td>" . ($detail['ten_san_pham'] ?? 'N/A') . "</td>";
            echo "<td>" . ($detail['duong_dan_hinh_anh'] ?? 'N/A') . "</td>";
            echo "<td>" . ($detail['so_luong'] ?? 'N/A') . "</td>";
            echo "<td>" . ($detail['don_gia'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>KHÔNG CÓ CHI TIẾT SẢN PHẨM!</p>";
    }
}

// Test query trực tiếp
echo "<hr><h3>Test Query Trực Tiếp</h3>";
$database = new Database();
$conn = $database->connect();

$query = "SELECT ct.id, ct.id_san_pham, ct.so_luong, ct.don_gia, sp.ten_san_pham, sp.duong_dan_hinh_anh
          FROM chi_tiet_don_hang ct
          INNER JOIN san_pham sp ON ct.id_san_pham = sp.id
          WHERE sp.ngay_xoa IS NULL
          LIMIT 5";

$result = $conn->query($query);
if ($result) {
    echo "<p>Tìm thấy " . $result->num_rows . " dòng</p>";
    while ($row = $result->fetch_assoc()) {
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }
} else {
    echo "<p style='color: red;'>Lỗi query: " . $conn->error . "</p>";
}
?>
