<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Order.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$order_id = intval($_GET['id'] ?? 0);
if ($order_id <= 0) {
    set_message('error', 'Đơn hàng không hợp lệ!');
    redirect('views/admin/orders/index.php');
}

$orderModel = new Order();
$order = $orderModel->getByIdWithDeleted($order_id);

if (!$order) {
    set_message('error', 'Không tìm thấy đơn hàng!');
    redirect('views/admin/orders/index.php');
}

$items = $orderModel->getOrderDetails($order_id);

$page_title = "Chi tiết đơn hàng";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <?php if ($order['ngay_xoa']): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Cảnh báo:</strong> Đơn hàng này đã bị xóa vào <?php echo date('d/m/Y H:i', strtotime($order['ngay_xoa'])); ?>
    </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-receipt me-2"></i>Chi tiết đơn hàng #<?php echo $order['id']; ?>
            <?php if ($order['ngay_xoa']): ?>
                <span class="badge bg-danger ms-2">Đã xóa</span>
            <?php else:
                $status = (int)$order['trang_thai'];
                $badge = 'secondary';
                $text = 'Không xác định';
                switch($status) {
                    case ORDER_STATUS_PENDING: $badge = 'warning'; $text = 'Chưa duyệt'; break;
                    case ORDER_STATUS_APPROVED: $badge = 'info'; $text = 'Đã duyệt'; break;
                    case ORDER_STATUS_SHIPPING: $badge = 'primary'; $text = 'Đang giao hàng'; break;
                    case ORDER_STATUS_COMPLETED: $badge = 'success'; $text = 'Hoàn thành'; break;
                    case ORDER_STATUS_CANCELLED: $badge = 'danger'; $text = 'Đã hủy'; break;
                }
            ?>
                <span class="badge bg-<?php echo $badge; ?> ms-2"><?php echo $text; ?></span>
            <?php endif; ?>
        </h2>
        <div>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Mã đơn:</strong></div>
                        <div class="col-md-8">#<?php echo $order['id']; ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Khách hàng:</strong></div>
                        <div class="col-md-8"><?php echo htmlspecialchars($order['username'] ?? 'Khách'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Email:</strong></div>
                        <div class="col-md-8"><?php echo htmlspecialchars($order['email'] ?? '-'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Ngày đặt:</strong></div>
                        <div class="col-md-8"><?php echo format_date($order['ngay_dat']); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Thanh toán:</strong></div>
                        <div class="col-md-8"><?php echo htmlspecialchars($order['phuong_thuc_thanh_toan'] ?? 'COD'); ?></div>
                    </div>
                    <?php if ($order['ngay_xoa']): ?>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Ngày xóa:</strong></div>
                        <div class="col-md-8">
                            <span class="text-danger"><?php echo date('d/m/Y H:i:s', strtotime($order['ngay_xoa'])); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Người nhận:</strong></div>
                        <div class="col-md-8"><?php echo htmlspecialchars($order['ho_ten_nguoi_nhan'] ?? '-'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Số điện thoại:</strong></div>
                        <div class="col-md-8"><?php echo htmlspecialchars($order['so_dien_thoai_nhan'] ?? '-'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Địa chỉ:</strong></div>
                        <div class="col-md-8"><?php echo htmlspecialchars($order['dia_chi_giao_hang'] ?? '-'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="fas fa-box me-2"></i>Sản phẩm</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th width="150">Đơn giá</th>
                            <th width="100">Số lượng</th>
                            <th width="150">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Không có sản phẩm nào</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($item['duong_dan_hinh_anh'])): 
                                        $image_path = $item['duong_dan_hinh_anh'];
                                        if (strpos($image_path, '/') === false) {
                                            $image_path = ASSETS_URL . 'products/' . $image_path;
                                        }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;"
                                         onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                                    <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($item['ten_san_pham']); ?></td>
                                <td><?php echo format_currency($item['don_gia']); ?></td>
                                <td class="text-center"><?php echo $item['so_luong']; ?></td>
                                <td><strong><?php echo format_currency($item['don_gia'] * $item['so_luong']); ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                            <td><strong class="text-primary fs-5"><?php echo format_currency($order['tong_tien']); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
