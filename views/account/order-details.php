<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Order.php';

if (!is_logged_in()) {
    echo '<div class="alert alert-danger">Vui lòng đăng nhập!</div>';
    exit;
}

$order_id = intval($_GET['id'] ?? 0);
if ($order_id <= 0) {
    echo '<div class="alert alert-danger">Đơn hàng không hợp lệ!</div>';
    exit;
}

$orderModel = new Order();
$order_details = $orderModel->getOrderDetails($order_id);

if (!$order_details || $order_details['order']['nguoi_dung_id'] !== $_SESSION['user_id']) {
    echo '<div class="alert alert-danger">Không tìm thấy đơn hàng!</div>';
    exit;
}

$order = $order_details['order'];
$items = $order_details['items'];
?>

<div class="order-detail">
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="fw-bold">Thông tin đơn hàng</h6>
            <p class="mb-1"><strong>Mã đơn:</strong> #<?php echo $order['id']; ?></p>
            <p class="mb-1"><strong>Ngày đặt:</strong> <?php echo format_date($order['ngay_dat_hang']); ?></p>
            <p class="mb-1"><strong>Trạng thái:</strong> 
                <?php
                $badge_class = 'secondary';
                switch($order['trang_thai']) {
                    case ORDER_STATUS_PENDING: $badge_class = 'warning'; $status_text = 'Chờ xác nhận'; break;
                    case ORDER_STATUS_CONFIRMED: $badge_class = 'info'; $status_text = 'Đã xác nhận'; break;
                    case ORDER_STATUS_SHIPPING: $badge_class = 'primary'; $status_text = 'Đang giao'; break;
                    case ORDER_STATUS_COMPLETED: $badge_class = 'success'; $status_text = 'Hoàn thành'; break;
                    case ORDER_STATUS_CANCELLED: $badge_class = 'danger'; $status_text = 'Đã hủy'; break;
                    default: $status_text = $order['trang_thai'];
                }
                ?>
                <span class="badge bg-<?php echo $badge_class; ?>"><?php echo $status_text; ?></span>
            </p>
        </div>
        <div class="col-md-6">
            <h6 class="fw-bold">Thông tin giao hàng</h6>
            <p class="mb-1"><strong>Người nhận:</strong> <?php echo htmlspecialchars($order['ten_nguoi_nhan']); ?></p>
            <p class="mb-1"><strong>SĐT:</strong> <?php echo htmlspecialchars($order['sdt_nguoi_nhan']); ?></p>
            <p class="mb-1"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['dia_chi_giao_hang']); ?></p>
            <p class="mb-1"><strong>Thanh toán:</strong> <?php echo htmlspecialchars($order['phuong_thuc_thanh_toan']); ?></p>
        </div>
    </div>
    
    <h6 class="fw-bold mb-3">Sản phẩm</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
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
            <tfoot>
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
        <p><?php echo nl2br(htmlspecialchars($order['ghi_chu'])); ?></p>
    </div>
    <?php endif; ?>
</div>
