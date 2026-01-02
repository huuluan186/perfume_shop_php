<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Order.php';

if (!is_admin()) {
    echo '<div class="alert alert-danger">Bạn không có quyền truy cập!</div>';
    exit;
}

$order_id = intval($_GET['id'] ?? 0);
if ($order_id <= 0) {
    echo '<div class="alert alert-danger">Đơn hàng không hợp lệ!</div>';
    exit;
}

$orderModel = new Order();
$order_details = $orderModel->getOrderDetails($order_id);

if (!$order_details) {
    echo '<div class="alert alert-danger">Không tìm thấy đơn hàng!</div>';
    exit;
}

$order = $order_details['order'];
$items = $order_details['items'];
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h6 class="fw-bold">Thông tin đơn hàng</h6>
        <table class="table table-sm table-borderless">
            <tr><td width="120"><strong>Mã đơn:</strong></td><td>#<?php echo $order['id']; ?></td></tr>
            <tr><td><strong>Ngày đặt:</strong></td><td><?php echo format_date($order['ngay_dat_hang']); ?></td></tr>
            <tr><td><strong>Trạng thái:</strong></td>
                <td>
                    <?php
                    $badge = 'secondary';
                    $text = $order['trang_thai'];
                    switch($order['trang_thai']) {
                        case ORDER_STATUS_PENDING: $badge = 'warning'; $text = 'Chờ xác nhận'; break;
                        case ORDER_STATUS_CONFIRMED: $badge = 'info'; $text = 'Đã xác nhận'; break;
                        case ORDER_STATUS_SHIPPING: $badge = 'primary'; $text = 'Đang giao'; break;
                        case ORDER_STATUS_COMPLETED: $badge = 'success'; $text = 'Hoàn thành'; break;
                        case ORDER_STATUS_CANCELLED: $badge = 'danger'; $text = 'Đã hủy'; break;
                    }
                    ?>
                    <span class="badge bg-<?php echo $badge; ?>"><?php echo $text; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold">Thông tin giao hàng</h6>
        <table class="table table-sm table-borderless">
            <tr><td width="120"><strong>Người nhận:</strong></td><td><?php echo htmlspecialchars($order['ten_nguoi_nhan']); ?></td></tr>
            <tr><td><strong>SĐT:</strong></td><td><?php echo htmlspecialchars($order['sdt_nguoi_nhan']); ?></td></tr>
            <tr><td><strong>Địa chỉ:</strong></td><td><?php echo htmlspecialchars($order['dia_chi_giao_hang']); ?></td></tr>
            <tr><td><strong>Thanh toán:</strong></td><td><?php echo htmlspecialchars($order['phuong_thuc_thanh_toan']); ?></td></tr>
        </table>
    </div>
</div>

<h6 class="fw-bold mb-3">Sản phẩm</h6>
<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>Sản phẩm</th>
                <th width="120">Đơn giá</th>
                <th width="80">SL</th>
                <th width="120">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['ten_san_pham']); ?></td>
                <td><?php echo format_currency($item['gia_ban']); ?></td>
                <td class="text-center"><?php echo $item['so_luong']; ?></td>
                <td><strong><?php echo format_currency($item['gia_ban'] * $item['so_luong']); ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="table-light">
            <tr>
                <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                <td><strong class="text-primary"><?php echo format_currency($order['tong_tien']); ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php if (!empty($order['ghi_chu'])): ?>
<div class="mt-3">
    <h6 class="fw-bold">Ghi chú:</h6>
    <p class="mb-0"><?php echo nl2br(htmlspecialchars($order['ghi_chu'])); ?></p>
</div>
<?php endif; ?>
