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
$limit = 20;
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
                <table class="table table-hover align-middle">
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
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
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
                                <?php if ($user['trang_thai'] == 1): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Đã khóa</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo format_date($user['ngay_tao']); ?></td>
                            <td>
                                <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                    <button class="btn btn-sm btn-outline-<?php echo $user['trang_thai'] == 1 ? 'warning' : 'success'; ?> toggle-status" 
                                            data-id="<?php echo $user['id']; ?>"
                                            data-status="<?php echo $user['trang_thai']; ?>">
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
    $('.toggle-status').on('click', function() {
        const userId = $(this).data('id');
        const currentStatus = $(this).data('status');
        const action = currentStatus == 1 ? 'khóa' : 'mở khóa';
        
        if (!confirm(`Bạn có chắc muốn ${action} tài khoản này?`)) return;
        
        $.ajax({
            url: 'toggle-status.php',
            method: 'POST',
            data: { id: userId, status: currentStatus },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function() {
                showNotification('error', 'Có lỗi xảy ra!');
            }
        });
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
