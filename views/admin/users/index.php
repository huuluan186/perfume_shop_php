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
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm người dùng
        </a>
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
                            <th>Giới tính</th>
                            <th>Ngày sinh</th>
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
                            <td><?php echo $user['gioi_tinh'] ? ucfirst($user['gioi_tinh']) : '-'; ?></td>
                            <td><?php echo $user['ngay_sinh'] ? format_date($user['ngay_sinh']) : '-'; ?></td>
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
                            <td class="text-center sticky-action" class="text-center sticky-action">
                                <a href="view.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-info me-1" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if (!$user['ngay_xoa'] && $user['id'] !== $_SESSION['user_id']): ?>
                                    <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-<?php echo $user['trang_thai'] == 1 ? 'warning' : 'success'; ?> me-1 toggle-status" 
                                            data-id="<?php echo $user['id']; ?>"
                                            data-status="<?php echo $user['trang_thai']; ?>"
                                            title="<?php echo $user['trang_thai'] == 1 ? 'Khóa' : 'Mở khóa'; ?>">
                                        <i class="fas fa-<?php echo $user['trang_thai'] == 1 ? 'lock' : 'unlock'; ?>"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-user" 
                                            data-id="<?php echo $user['id']; ?>" title="Xóa">
                                        <i class="fas fa-trash"></i>
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
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <li class="page-item <?php echo ($i === $pagination['current_page']) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
$(document).ready(function() {
    console.log('User management page loaded');
    
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
</script>
