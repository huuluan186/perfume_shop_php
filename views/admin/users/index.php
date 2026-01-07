<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/User.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$userModel = new User();

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 8;
$offset = ($page - 1) * $limit;

$users = $userModel->getAllUsers($limit, $offset);
$total_users = $userModel->count();
$pagination = paginate($total_users, $page, $limit);

$page_title = "Quản lý người dùng";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-users me-2"></i>Quản lý người dùng</h2>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($users)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-users fa-3x mb-3"></i>
                <p>Chưa có người dùng nào</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-sticky-action">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th class="text-center sticky-action">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr <?php echo $user['ngay_xoa'] ? 'class="table-secondary" style="opacity: 0.6;"' : ''; ?>>
                            <td><strong>#<?php echo $user['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($user['username'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php if ($user['vai_tro'] === ROLE_ADMIN): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Khách hàng</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['ngay_xoa']): ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-trash me-1"></i>Đã xóa
                                    </span>
                                <?php elseif ($user['trang_thai'] == 1): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Hoạt động
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-lock me-1"></i>Đã khóa
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo format_date($user['ngay_tao']); ?></td>
                            <td class="text-center sticky-action">
                                <button class="btn btn-sm btn-outline-info me-1 view-user" 
                                        data-id="<?php echo $user['id']; ?>" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if (!$user['ngay_xoa'] && $user['id'] !== $_SESSION['user_id']): ?>
                                    <button class="btn btn-sm btn-outline-<?php echo $user['trang_thai'] == 1 ? 'warning' : 'success'; ?> toggle-status" 
                                            data-id="<?php echo $user['id']; ?>"
                                            data-status="<?php echo $user['trang_thai']; ?>"
                                            title="<?php echo $user['trang_thai'] == 1 ? 'Khóa tài khoản' : 'Mở khóa tài khoản'; ?>">
                                        <i class="fas fa-<?php echo $user['trang_thai'] == 1 ? 'lock' : 'unlock'; ?>"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
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

<!-- Modal Chi tiết người dùng -->
<div class="modal fade" id="userDetailModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user me-2"></i>Chi tiết người dùng</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailContent" style="max-height: 70vh; overflow-y: auto;">
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

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
$(document).ready(function() {
    console.log('User management page loaded');
    
    // View user detail
    $(document).on('click', '.view-user', function(e) {
        e.preventDefault();
        const userId = $(this).data('id');
        console.log('View user clicked:', userId);
        
        // Show loading
        $('#userDetailContent').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
        modal.show();
        
        // Load user detail
        $.ajax({
            url: 'get-detail.php',
            method: 'GET',
            data: { id: userId },
            dataType: 'json',
            success: function(response) {
                console.log('User detail response:', response);
                if (response.success) {
                    const html = renderUserDetail(response.user, response.orders);
                    $('#userDetailContent').html(html);
                } else {
                    $('#userDetailContent').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.log('Response:', xhr.responseText);
                $('#userDetailContent').html('<div class="alert alert-danger">Có lỗi xảy ra khi tải thông tin!</div>');
            }
        });
    });
    
    // Use event delegation for toggle status buttons
    $(document).on('click', '.toggle-status', function(e) {
        e.preventDefault();
        
        const userId = $(this).data('id');
        const currentStatus = $(this).data('status');
        const action = currentStatus == 1 ? 'khóa' : 'mở khóa';
        
        console.log('Toggle status clicked:', userId, currentStatus);
        
        if (!confirm(`Bạn có chắc muốn ${action} tài khoản này?`)) return;
        
        const $button = $(this);
        $button.prop('disabled', true);
        
        $.ajax({
            url: 'toggle-status.php',
            method: 'POST',
            data: { id: userId, status: currentStatus },
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
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
                alert('Có lỗi xảy ra! Lỗi: ' + error);
                $button.prop('disabled', false);
            }
        });
    });
    
    // Delete user
    $(document).on('click', '.delete-user', function(e) {
        e.preventDefault();
        
        if (!confirm('Bạn có chắc muốn xóa người dùng này?')) return;
        
        const userId = $(this).data('id');
        const $button = $(this);
        $button.prop('disabled', true);
        
        $.ajax({
            url: 'delete.php',
            method: 'POST',
            data: { id: userId },
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
                alert('Có lỗi xảy ra khi xóa người dùng!');
                $button.prop('disabled', false);
            }
        });
    });
});

function renderUserDetail(user, orders) {
    const roleBadge = user.vai_tro == 1 
        ? '<span class="badge bg-danger">Admin</span>' 
        : '<span class="badge bg-primary">Khách hàng</span>';
    
    const statusBadge = user.trang_thai == 1 
        ? '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Hoạt động</span>' 
        : '<span class="badge bg-secondary"><i class="fas fa-lock me-1"></i>Đã khóa</span>';
    
    // Render danh sách đơn hàng
    let ordersHtml = '';
    if (orders && orders.length > 0) {
        orders.forEach(function(o) {
            let statusClass = '';
            let statusText = '';
            const trangThai = parseInt(o.trang_thai);
            switch(trangThai) {
                case 0:
                    statusClass = 'warning';
                    statusText = 'Chưa duyệt';
                    break;
                case 1:
                    statusClass = 'info';
                    statusText = 'Đã duyệt';
                    break;
                case 2:
                    statusClass = 'primary';
                    statusText = 'Đang giao hàng';
                    break;
                case 3:
                    statusClass = 'success';
                    statusText = 'Hoàn thành';
                    break;
                case 4:
                    statusClass = 'danger';
                    statusText = 'Đã hủy';
                    break;
                default:
                    statusClass = 'secondary';
                    statusText = 'Không xác định';
            }
            
            ordersHtml += `
                <tr>
                    <td class="text-center"><strong>#${o.id}</strong></td>
                    <td>${o.ngay_dat}</td>
                    <td class="text-end"><strong>${o.tong_tien}</strong></td>
                    <td class="text-center">
                        <span class="badge bg-${statusClass}">${statusText}</span>
                    </td>
                </tr>
            `;
        });
    } else {
        ordersHtml = '<tr><td colspan="4" class="text-center text-muted fst-italic py-4">Chưa có đơn hàng nào</td></tr>';
    }
    
    return `
        <div class="row">
            <div class="col-12">
                <h4 class="text-primary mb-4">
                    ${user.username || 'N/A'}
                    <span class="badge bg-primary ms-2">${user.total_orders} đơn hàng</span>
                </h4>
                
                <div class="card border-0 bg-light mb-4">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Thông tin cá nhân</h6>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Mã người dùng:</small></div>
                            <div class="col-md-9"><strong class="text-primary">#${user.id}</strong></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Tên đăng nhập:</small></div>
                            <div class="col-md-9"><strong>${user.username || 'N/A'}</strong></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Email:</small></div>
                            <div class="col-md-9">${user.email}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Số điện thoại:</small></div>
                            <div class="col-md-9">${user.so_dien_thoai || '<span class="text-muted">Chưa cập nhật</span>'}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Giới tính:</small></div>
                            <div class="col-md-9">${user.gioi_tinh ? (user.gioi_tinh.charAt(0).toUpperCase() + user.gioi_tinh.slice(1)) : '<span class="text-muted">Chưa cập nhật</span>'}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Ngày sinh:</small></div>
                            <div class="col-md-9">${user.ngay_sinh_formatted || '<span class="text-muted">Chưa cập nhật</span>'}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Địa chỉ:</small></div>
                            <div class="col-md-9">${user.dia_chi || '<span class="text-muted">Chưa cập nhật</span>'}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Vai trò:</small></div>
                            <div class="col-md-9">${roleBadge}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Trạng thái:</small></div>
                            <div class="col-md-9">${statusBadge}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Ngày tạo:</small></div>
                            <div class="col-md-9">${user.ngay_tao_formatted}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><small class="text-muted">Tổng chi tiêu:</small></div>
                            <div class="col-md-9"><strong class="text-success">${user.total_spent_formatted}</strong></div>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0">
                    <div class="card-body">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-shopping-cart me-2"></i>Đơn hàng gần đây 
                            <span class="badge bg-primary">${orders.length}</span>
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="100" class="text-center">Mã ĐH</th>
                                        <th width="200">Ngày đặt</th>
                                        <th width="150" class="text-end">Tổng tiền</th>
                                        <th width="150" class="text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${ordersHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}
</script>
