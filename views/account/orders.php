<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/User.php';

if (!is_logged_in()) {
    redirect('views/auth/login.php');
}

$orderModel = new Order();
$userModel = new User();
$user = $userModel->getUserById($_SESSION['user_id']);

// Phân trang
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = ORDERS_PER_PAGE;
$offset = ($page - 1) * $limit;

$orders = $orderModel->getByUserId($_SESSION['user_id'], $limit, $offset);
$total_orders = $orderModel->countByUserId($_SESSION['user_id']);
$pagination = paginate($total_orders, $page, $limit);

$page_title = "Đơn hàng của tôi";
include __DIR__ . '/../layout/header.php';
?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h5 class="mb-1"><?php echo htmlspecialchars($_SESSION['username']); ?></h5>
                        <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                    </div>
                    <hr>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="profile.php">
                            <i class="fas fa-user me-2"></i>Thông tin tài khoản
                        </a>
                        <a class="nav-link active" href="orders.php">
                            <i class="fas fa-shopping-bag me-2"></i>Đơn hàng của tôi
                        </a>
                        <a class="nav-link" href="change-password.php">
                            <i class="fas fa-key me-2"></i>Đổi mật khẩu
                        </a>
                        <a class="nav-link text-danger" href="<?php echo BASE_URL; ?>views/auth/logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-shopping-bag me-2"></i>Đơn hàng của tôi</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($orders)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Bạn chưa có đơn hàng nào</h5>
                        <a href="<?php echo BASE_URL; ?>views/products/index.php" class="btn btn-primary mt-3">
                            <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
                        </a>
                    </div>
                    <?php else: ?>
                    <!-- Header cho danh sách đơn hàng -->
                    <div class="row mb-3 fw-bold text-muted border-bottom pb-2 ps-3">
                        <div class="col-1">Mã đơn</div>
                        <div class="col-2">Ngày đặt</div>
                        <div class="col-2">Người nhận</div>
                        <div class="col-2">Số điện thoại</div>
                        <div class="col-2">Tổng tiền</div>
                        <div class="col-2">Trạng thái</div>
                        <div class="col-1">Thao tác</div>
                    </div>
                    
                    <div class="accordion" id="ordersAccordion"><?php foreach ($orders as $index => $order): 
                            // Lấy chi tiết đơn hàng
                            $order_details_result = $orderModel->getOrderDetails($order['id']);
                            $order_details = $order_details_result ? $order_details_result['items'] : [];
                            
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
                        <div class="accordion-item border-0 mb-2 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed p-3" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#order<?php echo $order['id']; ?>">
                                    <div class="row w-100 align-items-center">
                                        <div class="col-1">
                                            <strong>#<?php echo $order['id']; ?></strong>
                                        </div>
                                        <div class="col-2">
                                            <small><?php echo format_date($order['ngay_dat']); ?></small>
                                        </div>
                                        <div class="col-2">
                                            <?php echo htmlspecialchars($order['ho_ten_nguoi_nhan']); ?>
                                        </div>
                                        <div class="col-2">
                                            <small><?php echo htmlspecialchars($order['so_dien_thoai_nhan']); ?></small>
                                        </div>
                                        <div class="col-2">
                                            <strong class="text-primary"><?php echo format_currency($order['tong_tien']); ?></strong>
                                        </div>
                                        <div class="col-2">
                                            <span class="badge bg-<?php echo $badge_class; ?>"><?php echo $status_text; ?></span>
                                        </div>
                                        <div class="col-1"></div>
                                    </div>
                                </button>
                            </h2>
                            <div id="order<?php echo $order['id']; ?>" class="accordion-collapse collapse">
                                <div class="accordion-body bg-light">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-danger"></i><strong>Địa chỉ giao hàng:</strong> <?php echo htmlspecialchars($order['dia_chi_giao_hang']); ?></p>
                                        </div>
                                    </div>
                                    
                                    <h6 class="fw-bold mb-3"><i class="fas fa-shopping-bag me-2 text-primary"></i>Chi tiết sản phẩm</h6>
                                    
                                    <?php if (empty($order_details)): ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Không tìm thấy chi tiết sản phẩm cho đơn hàng này.
                                            <!-- Debug: Order ID = <?php echo $order['id']; ?> -->
                                        </div>
                                    <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover bg-white">
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
                                                            <?php 
                                                            $image_path = $detail['duong_dan_hinh_anh'] ?? '';
                                                            if (!empty($image_path)): 
                                                                // Check old vs new path
                                                                if (strpos($image_path, '/') !== false) {
                                                                    $full_image_url = ASSETS_URL . urldecode($image_path);
                                                                } else {
                                                                    $full_image_url = UPLOAD_URL . $image_path;
                                                                }
                                                            ?>
                                                            <img src="<?php echo htmlspecialchars($full_image_url); ?>" 
                                                                 alt="<?php echo htmlspecialchars($detail['ten_san_pham'] ?? ''); ?>" 
                                                                 class="me-3 rounded" 
                                                                 style="width: 60px; height: 60px; object-fit: cover;"
                                                                 onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png';">
                                                            
                                                            <?php else: ?>
                                                            <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center" 
                                                                 style="width: 60px; height: 60px;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                            <?php endif; ?>
                                                            <div>
                                                                <strong><?php echo htmlspecialchars($detail['ten_san_pham'] ?? 'Sản phẩm không xác định'); ?></strong>
                                                                <?php if (!empty($detail['ngay_xoa'])): ?>
                                                                    <span class="badge bg-danger ms-2"><i class="fas fa-trash me-1"></i>Đã xóa</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo format_currency($detail['gia_ban']); ?></td>
                                                    <td class="text-center"><span class="badge bg-secondary"><?php echo $detail['so_luong']; ?></span></td>
                                                    <td class="text-end"><strong class="text-primary"><?php echo format_currency($detail['gia_ban'] * $detail['so_luong']); ?></strong></td>
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
                                    <?php endif; ?>
                                    
                                    <div class="mt-3">
                                        <?php 
                                        error_log("Order {$order['id']} status: $status (PENDING=" . ORDER_STATUS_PENDING . ")");
                                        if ($status === ORDER_STATUS_PENDING): 
                                        ?>
                                        <button class="btn btn-danger btn-sm cancel-order" 
                                                data-order-id="<?php echo $order['id']; ?>">
                                            <i class="fas fa-times me-1"></i> Hủy đơn hàng
                                        </button>
                                        <?php else: ?>
                                        <!-- Trạng thái: <?php echo $status; ?> -->
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="mt-4">
                        <?php echo render_pagination($pagination['total_pages'], $pagination['current_page'], '', []); ?>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
// Script cho cancel order - đặt sau khi jQuery được load
$(document).ready(function() {
    console.log('Cancel order handler initialized');
    console.log('jQuery version:', $.fn.jquery);
    
    // Xử lý hủy đơn hàng - PHẢI dùng event delegation vì nút trong accordion collapse
    $(document).on('click', '.cancel-order', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        console.log('Cancel button clicked!');
        
        var orderId = $(this).data('order-id');
        console.log('Order ID:', orderId);
        
        if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng #' + orderId + '?')) {
            console.log('User cancelled the confirmation');
            return false;
        }
        
        var button = $(this);
        var originalHtml = button.html();
        
        console.log('Sending cancel request for order:', orderId);
        
        // Disable button
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...');
        
        $.ajax({
            url: '<?php echo BASE_URL; ?>views/account/cancel-order.php',
            type: 'POST',
            data: { order_id: orderId },
            dataType: 'json',
            beforeSend: function() {
                console.log('Sending AJAX request...');
            },
            success: function(response) {
                console.log('AJAX Success! Response:', response);
                if (response.success) {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert('Lỗi: ' + response.message);
                    button.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error!');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                console.error('Status Code:', xhr.status);
                alert('Có lỗi xảy ra khi hủy đơn hàng!');
                button.prop('disabled', false).html(originalHtml);
            }
        });
        
        return false;
    });
});
</script>
