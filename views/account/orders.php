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
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thanh toán</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong>#<?php echo $order['id']; ?></strong></td>
                                    <td><?php echo format_date($order['ngay_dat_hang']); ?></td>
                                    <td><strong class="text-primary"><?php echo format_currency($order['tong_tien']); ?></strong></td>
                                    <td>
                                        <?php
                                        $badge_class = 'secondary';
                                        switch($order['trang_thai']) {
                                            case ORDER_STATUS_PENDING:
                                                $badge_class = 'warning';
                                                $status_text = 'Chờ xác nhận';
                                                break;
                                            case ORDER_STATUS_CONFIRMED:
                                                $badge_class = 'info';
                                                $status_text = 'Đã xác nhận';
                                                break;
                                            case ORDER_STATUS_SHIPPING:
                                                $badge_class = 'primary';
                                                $status_text = 'Đang giao';
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
                                                $status_text = $order['trang_thai'];
                                        }
                                        ?>
                                        <span class="badge bg-<?php echo $badge_class; ?>"><?php echo $status_text; ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo htmlspecialchars($order['phuong_thuc_thanh_toan']); ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary view-order-details" 
                                                data-order-id="<?php echo $order['id']; ?>"
                                                data-bs-toggle="modal" data-bs-target="#orderDetailModal">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </button>
                                        <?php if ($order['trang_thai'] === ORDER_STATUS_PENDING): ?>
                                        <button class="btn btn-sm btn-outline-danger cancel-order" 
                                                data-order-id="<?php echo $order['id']; ?>">
                                            <i class="fas fa-times"></i> Hủy
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($pagination['total_pages'] > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination['current_page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <li class="page-item <?php echo ($i === $pagination['current_page']) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.view-order-details', function() {
    const orderId = $(this).data('order-id');
    
    $.ajax({
        url: 'order-details.php',
        method: 'GET',
        data: { id: orderId },
        success: function(response) {
            $('#orderDetailContent').html(response);
        },
        error: function() {
            $('#orderDetailContent').html('<div class="alert alert-danger">Không thể tải thông tin đơn hàng!</div>');
        }
    });
});

$(document).on('click', '.cancel-order', function() {
    if (!confirm('Bạn có chắc muốn hủy đơn hàng này?')) return;
    
    const orderId = $(this).data('order-id');
    
    $.ajax({
        url: 'cancel-order.php',
        method: 'POST',
        data: { order_id: orderId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification('success', response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('error', response.message);
            }
        }
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
