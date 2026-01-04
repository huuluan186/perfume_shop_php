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
$order = $orderModel->getById($order_id);

if (!$order || $order['id_nguoi_dung'] != $_SESSION['user_id']) {
    echo '<div class="alert alert-danger">Không tìm thấy đơn hàng!</div>';
    exit;
}

$order_details = $orderModel->getOrderDetails($order_id);

// Xác định trạng thái
$status = (int)$order['trang_thai'];
switch($status) {
    case ORDER_STATUS_PENDING:
        $badge_class = 'warning';
        $status_text = 'Chưa duyệt';
        break;
    case ORDER_STATUS_APPROVED:
        $badge_class = 'info';
        $status_text = 'Đã duyệt';
        break;
    case ORDER_STATUS_SHIPPING:
        $badge_class = 'primary';
        $status_text = 'Đang giao hàng';
        break;
    case ORDER_STATUS_COMPLETED:
        $badge_class = 'success';
        $status_text = 'Hoàn thành';
        break;
    case ORDER_STATUS_CANCELLED:
        $badge_class = 'danger';
        $status_text = 'Đã hủy';
        break;
    default:
        $badge_class = 'secondary';
        $status_text = 'Không xác định';
}
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Thông tin đơn hàng</h6>
        <table class="table table-sm table-borderless">
            <tr>
                <td width="120"><strong>Mã đơn:</strong></td>
                <td>#<?php echo $order['id']; ?></td>
            </tr>
            <tr>
                <td><strong>Ngày đặt:</strong></td>
                <td><?php echo format_date($order['ngay_dat']); ?></td>
            </tr>
            <tr>
                <td><strong>Trạng thái:</strong></td>
                <td><span class="badge bg-<?php echo $badge_class; ?>"><?php echo $status_text; ?></span></td>
            </tr>
            <tr>
                <td><strong>Tổng tiền:</strong></td>
                <td><strong class="text-primary"><?php echo format_currency($order['tong_tien']); ?></strong></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold mb-3"><i class="fas fa-user me-2 text-success"></i>Thông tin người nhận</h6>
        <table class="table table-sm table-borderless">
            <tr>
                <td width="120"><strong>Họ tên:</strong></td>
                <td><?php echo htmlspecialchars($order['ho_ten_nguoi_nhan']); ?></td>
            </tr>
            <tr>
                <td><strong>Số điện thoại:</strong></td>
                <td><?php echo htmlspecialchars($order['so_dien_thoai_nhan']); ?></td>
            </tr>
            <tr>
                <td><strong>Địa chỉ:</strong></td>
                <td><?php echo htmlspecialchars($order['dia_chi_giao_hang']); ?></td>
            </tr>
        </table>
    </div>
</div>

<h6 class="fw-bold mb-3"><i class="fas fa-shopping-bag me-2 text-danger"></i>Chi tiết sản phẩm</h6>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th width="60">STT</th>
                <th>Sản phẩm</th>
                <th width="120">Đơn giá</th>
                <th width="80" class="text-center">SL</th>
                <th width="140" class="text-end">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $stt = 1;
            foreach ($order_details as $detail): 
            ?>
            <tr>
                <td class="text-center"><?php echo $stt++; ?></td>
                <td>
                    <div class="d-flex align-items-center">
                        <?php if (!empty($detail['duong_dan_hinh_anh'])): 
                            // Check old vs new path
                            if (strpos($detail['duong_dan_hinh_anh'], '/') !== false) {
                                $detail_img_url = ASSETS_URL . urldecode($detail['duong_dan_hinh_anh']);
                            } else {
                                $detail_img_url = UPLOAD_URL . $detail['duong_dan_hinh_anh'];
                            }
                        ?>
                        <img src="<?php echo htmlspecialchars($detail_img_url); ?>" 
                             alt="<?php echo htmlspecialchars($detail['ten_san_pham']); ?>" 
                             class="me-3 rounded" 
                             style="width: 60px; height: 60px; object-fit: cover;"
                             onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                        <?php else: ?>
                        <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                        <?php endif; ?>
                        <div>
                            <strong><?php echo htmlspecialchars($detail['ten_san_pham']); ?></strong>
                        </div>
                    </div>
                </td>
                <td><?php echo format_currency($detail['don_gia']); ?></td>
                <td class="text-center"><span class="badge bg-secondary"><?php echo $detail['so_luong']; ?></span></td>
                <td class="text-end"><strong class="text-primary"><?php echo format_currency($detail['don_gia'] * $detail['so_luong']); ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="table-light">
            <tr>
                <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                <td class="text-end"><h5 class="mb-0 text-primary"><?php echo format_currency($order['tong_tien']); ?></h5></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php if ($status === ORDER_STATUS_PENDING): ?>
<div class="alert alert-info mt-3">
    <i class="fas fa-info-circle me-2"></i>
    Đơn hàng đang chờ duyệt. Bạn có thể hủy đơn hàng này nếu muốn.
</div>
<?php endif; ?>
