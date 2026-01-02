<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Order.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$orderModel = new Order();

// Filters
$filters = [
    'status' => $_GET['status'] ?? null,
    'search' => $_GET['search'] ?? null
];

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = ORDERS_PER_PAGE;
$offset = ($page - 1) * $limit;

$orders = $orderModel->getAll($filters, $limit, $offset);
$total_orders = $orderModel->count($filters);
$pagination = paginate($total_orders, $page, $limit);

$page_title = "Quản lý đơn hàng";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-shopping-cart me-2"></i>Quản lý đơn hàng</h2>
    </div>
    
    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Tìm theo mã đơn, tên khách hàng, SĐT..." 
                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="<?php echo ORDER_STATUS_PENDING; ?>" <?php echo ($filters['status'] == ORDER_STATUS_PENDING) ? 'selected' : ''; ?>>Chưa duyệt</option>
                        <option value="<?php echo ORDER_STATUS_APPROVED; ?>" <?php echo ($filters['status'] == ORDER_STATUS_APPROVED) ? 'selected' : ''; ?>>Đã duyệt</option>
                        <option value="<?php echo ORDER_STATUS_SHIPPING; ?>" <?php echo ($filters['status'] == ORDER_STATUS_SHIPPING) ? 'selected' : ''; ?>>Đang giao hàng</option>
                        <option value="<?php echo ORDER_STATUS_COMPLETED; ?>" <?php echo ($filters['status'] == ORDER_STATUS_COMPLETED) ? 'selected' : ''; ?>>Hoàn thành</option>
                        <option value="<?php echo ORDER_STATUS_CANCELLED; ?>" <?php echo ($filters['status'] == ORDER_STATUS_CANCELLED) ? 'selected' : ''; ?>>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($orders)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-receipt fa-3x mb-3"></i>
                <p>Chưa có đơn hàng nào</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>SĐT</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Ngày đặt</th>
                            <th width="200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?php echo $order['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($order['ten_nguoi_nhan']); ?></td>
                            <td><?php echo htmlspecialchars($order['sdt_nguoi_nhan']); ?></td>
                            <td><strong class="text-primary"><?php echo format_currency($order['tong_tien']); ?></strong></td>
                            <td>
                                <?php
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
                                <span class="badge bg-<?php echo $badge; ?>"><?php echo $text; ?></span>
                            </td>
                            <td><small><?php echo htmlspecialchars($order['phuong_thuc_thanh_toan'] ?? 'COD'); ?></small></td>
                            <td><?php echo format_date($order['ngay_dat']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-info view-order" 
                                        data-id="<?php echo $order['id']; ?>"
                                        data-bs-toggle="modal" data-bs-target="#orderModal">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if ($status !== ORDER_STATUS_CANCELLED && $status !== ORDER_STATUS_COMPLETED): ?>
                                <button class="btn btn-sm btn-outline-primary update-status" 
                                        data-id="<?php echo $order['id']; ?>"
                                        data-bs-toggle="modal" data-bs-target="#statusModal">
                                    <i class="fas fa-edit"></i>
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
                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>&<?php echo http_build_query($filters); ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <li class="page-item <?php echo ($i === $pagination['current_page']) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&<?php echo http_build_query($filters); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>&<?php echo http_build_query($filters); ?>">
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

<!-- Order Detail Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <input type="hidden" id="order_id" name="order_id">
                    <div class="mb-3">
                        <label class="form-label">Trạng thái mới</label>
                        <select class="form-select" name="status" required>
                            <option value="<?php echo ORDER_STATUS_PENDING; ?>">Chờ xác nhận</option>
                            <option value="<?php echo ORDER_STATUS_CONFIRMED; ?>">Đã xác nhận</option>
                            <option value="<?php echo ORDER_STATUS_PENDING; ?>">Chưa duyệt</option>
                            <option value="<?php echo ORDER_STATUS_APPROVED; ?>">Đã duyệt</option>
                            <option value="<?php echo ORDER_STATUS_SHIPPING; ?>">Đang giao hàng</option>
                            <option value="<?php echo ORDER_STATUS_COMPLETED; ?>">Hoàn thành</option>
                            <option value="<?php echo ORDER_STATUS_CANCELLED; ?>">Đã hủy</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.view-order', function() {
    const orderId = $(this).data('id');
    $.get('view-order.php?id=' + orderId, function(data) {
        $('#orderContent').html(data);
    });
});

$(document).on('click', '.update-status', function() {
    const orderId = $(this).data('id');
    $('#order_id').val(orderId);
});

$('#statusForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'update-status.php',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification('success', response.message);
                $('#statusModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('error', response.message);
            }
        }
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
