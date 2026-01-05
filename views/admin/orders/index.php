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
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th class="text-center sticky-action">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr <?php echo $order['ngay_xoa'] ? 'class="table-secondary" style="opacity: 0.6;"' : ''; ?>>
                            <td><strong>#<?php echo $order['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($order['ho_ten_nguoi_nhan'] ?? ''); ?></td>
                            <td><strong class="text-primary"><?php echo format_currency($order['tong_tien']); ?></strong></td>
                            <td>
                                <?php
                                if ($order['ngay_xoa']) {
                                    echo '<span class="badge bg-danger"><i class="fas fa-trash me-1"></i>Đã xóa</span>';
                                } else {
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
                                    echo '<span class="badge bg-' . $badge . '">' . $text . '</span>';
                                }
                                ?>
                            </td>
                            <td><?php echo format_date($order['ngay_dat']); ?></td>
                            <td class="text-center sticky-action">
                                <button class="btn btn-sm btn-outline-info me-1 view-order" 
                                        data-id="<?php echo $order['id']; ?>" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if (!$order['ngay_xoa']): ?>
                                    <?php 
                                    $status = (int)$order['trang_thai'];
                                    if ($status !== ORDER_STATUS_CANCELLED && $status !== ORDER_STATUS_COMPLETED): 
                                    ?>
                                    <button class="btn btn-sm btn-outline-primary me-1 update-status" 
                                            data-id="<?php echo $order['id']; ?>"
                                            title="Cập nhật trạng thái">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-outline-danger delete-order" 
                                            data-id="<?php echo $order['id']; ?>" title="Xóa">
                                        <i class="fas fa-trash"></i>
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
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-receipt me-2"></i>Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailContent" style="max-height: 70vh; overflow-y: auto;">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Đóng
                </button>
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
                            <option value="0">Chưa duyệt</option>
                            <option value="1">Đã duyệt</option>
                            <option value="2">Đang giao hàng</option>
                            <option value="3">Hoàn thành</option>
                            <option value="4">Đã hủy</option>
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

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
// Lấy ASSETS_URL từ PHP
const ASSETS_URL = '<?php echo ASSETS_URL; ?>';

$(document).ready(function() {
    // View order detail
    $(document).on('click', '.view-order', function(e) {
        e.preventDefault();
        const orderId = $(this).data('id');
        
        console.log('Opening order detail modal for ID:', orderId);
        
        // Khởi tạo và hiển thị modal
        const modalEl = document.getElementById('orderDetailModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        
        $('#orderDetailContent').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary"></i><p class="mt-3">Đang tải...</p></div>');
        
        // Load thông tin đơn hàng
        $.ajax({
            url: 'get-detail.php?id=' + orderId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    renderOrderDetail(response);
                } else {
                    $('#orderDetailContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>' + response.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.log('Response Text:', xhr.responseText);
                $('#orderDetailContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra khi tải thông tin!</div>');
            }
        });
    });
    
    function renderOrderDetail(data) {
        const o = data.order;
        const items = data.items || [];
        
        // Xác định trạng thái
        let statusBadge = '';
        if (o.ngay_xoa) {
            statusBadge = '<span class="badge bg-danger"><i class="fas fa-trash me-1"></i>Đã xóa</span>';
        } else {
            const statusMap = {
                '0': {class: 'warning', text: 'Chưa duyệt'},
                '1': {class: 'info', text: 'Đã duyệt'},
                '2': {class: 'primary', text: 'Đang giao hàng'},
                '3': {class: 'success', text: 'Hoàn thành'},
                '4': {class: 'danger', text: 'Đã hủy'}
            };
            const status = statusMap[o.trang_thai] || {class: 'secondary', text: 'Không xác định'};
            statusBadge = '<span class="badge bg-' + status.class + '">' + status.text + '</span>';
        }
        
        // Tạo bảng sản phẩm
        let productsHtml = '';
        if (items.length > 0) {
            productsHtml = `
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="80">Hình ảnh</th>
                                <th>Sản phẩm</th>
                                <th width="150">Đơn giá</th>
                                <th width="100">Số lượng</th>
                                <th width="150">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            items.forEach(function(item) {
                let imagePath = item.duong_dan_hinh_anh || '';
                if (imagePath && !imagePath.startsWith('http')) {
                    if (imagePath.startsWith('products/')) {
                        imagePath = ASSETS_URL + imagePath;
                    } else {
                        imagePath = ASSETS_URL + 'products/' + imagePath;
                    }
                } else if (!imagePath) {
                    imagePath = ASSETS_URL + 'images/placeholder.png';
                }
                
                productsHtml += `
                    <tr>
                        <td>
                            <img src="${imagePath}" class="rounded" 
                                 style="width: 60px; height: 60px; object-fit: cover;"
                                 onerror="this.src='` + ASSETS_URL + `images/placeholder.png'">
                        </td>
                        <td>
                            <div class="fw-bold">${item.ten_san_pham}</div>
                        </td>
                        <td>${item.don_gia_formatted}</td>
                        <td class="text-center">${item.so_luong}</td>
                        <td><strong>${item.thanh_tien_formatted}</strong></td>
                    </tr>`;
            });
            
            productsHtml += '</tbody></table></div>';
        }
        
        const html = `
            <div class="row">
                <div class="col-md-12 mb-4">
                    <h4 class="text-primary mb-3">Đơn hàng #${o.id} ${statusBadge}</h4>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h6>
                            <div class="mb-2">
                                <small class="text-muted">Mã đơn hàng:</small>
                                <div><strong class="text-primary">#${o.id}</strong></div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Ngày đặt:</small>
                                <div><strong>${formatDateTime(o.ngay_dat)}</strong></div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Trạng thái:</small>
                                <div>${statusBadge}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Phương thức thanh toán:</small>
                                <div><span class="badge bg-secondary">COD</span></div>
                            </div>
                            ${o.ngay_xoa ? '<div class="mb-2"><small class="text-muted">Ngày xóa:</small><div><strong class="text-danger">' + formatDateTime(o.ngay_xoa) + '</strong></div></div>' : ''}
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="fas fa-shipping-fast me-2"></i>Thông tin giao hàng</h6>
                            <div class="mb-2">
                                <small class="text-muted">Người nhận:</small>
                                <div><strong>${o.ho_ten_nguoi_nhan || 'N/A'}</strong></div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Số điện thoại:</small>
                                <div><strong>${o.so_dien_thoai_nhan || 'N/A'}</strong></div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Email:</small>
                                <div>${o.email || 'N/A'}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Địa chỉ giao hàng:</small>
                                <div>${o.dia_chi_giao_hang || 'N/A'}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 bg-light mb-3">
                <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="fas fa-box me-2"></i>Sản phẩm (${items.length})</h6>
                    ${productsHtml}
                    
                    <div class="row mt-3">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Tổng cộng:</strong>
                                    <strong class="text-primary fs-5">${o.tong_tien_formatted}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#orderDetailContent').html(html);
    }
    
    function formatDateTime(datetime) {
        if (!datetime) return '';
        const date = new Date(datetime);
        return date.toLocaleString('vi-VN');
    }
    
    // Update status
    $(document).on('click', '.update-status', function(e) {
        e.preventDefault();
        
        const orderId = $(this).data('id');
        $('#order_id').val(orderId);
        $('#statusModal').modal('show');
    });

    // Submit status form
    $('#statusForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const originalText = $submitBtn.html();
        
        $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...');
        
        $.ajax({
            url: 'update-status.php',
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#statusModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.log('Response:', xhr.responseText);
                alert('Có lỗi xảy ra khi cập nhật trạng thái!');
                $submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Delete order
    $(document).on('click', '.delete-order', function(e) {
        e.preventDefault();
        
        if (!confirm('Bạn có chắc muốn xóa đơn hàng này?')) return;
        
        const orderId = $(this).data('id');
        const $button = $(this);
        $button.prop('disabled', true);
        
        $.ajax({
            url: 'delete.php',
            method: 'POST',
            data: { id: orderId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                    $button.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.log('Response:', xhr.responseText);
                alert('Có lỗi xảy ra khi xóa đơn hàng!');
                $button.prop('disabled', false);
            }
        });
    });
});
</script>
