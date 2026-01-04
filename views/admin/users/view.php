<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/User.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$user_id = intval($_GET['id'] ?? 0);
if ($user_id <= 0) {
    set_message('error', 'Người dùng không hợp lệ!');
    redirect('views/admin/users/index.php');
}

$userModel = new User();
$user = $userModel->getUserByIdWithDeleted($user_id);

if (!$user) {
    set_message('error', 'Không tìm thấy người dùng!');
    redirect('views/admin/users/index.php');
}

// Xử lý đổi mật khẩu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $new_password = clean_input($_POST['new_password'] ?? '');
    $confirm_password = clean_input($_POST['confirm_password'] ?? '');
    
    $errors = [];
    if (empty($new_password)) $errors[] = 'Vui lòng nhập mật khẩu mới!';
    elseif (strlen($new_password) < 6) $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự!';
    if ($new_password !== $confirm_password) $errors[] = 'Xác nhận mật khẩu không khớp!';
    
    if (empty($errors)) {
        if ($userModel->changePasswordSimple($user_id, $new_password)) {
            set_message('success', 'Đổi mật khẩu thành công!');
            redirect('views/admin/users/view.php?id=' . $user_id);
        } else {
            set_message('error', 'Đổi mật khẩu thất bại!');
        }
    } else {
        foreach ($errors as $error) {
            set_message('error', $error);
        }
    }
}

$page_title = "Chi tiết người dùng";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <?php if ($user['ngay_xoa']): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Cảnh báo:</strong> Người dùng này đã bị xóa vào <?php echo date('d/m/Y H:i', strtotime($user['ngay_xoa'])); ?>
    </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-eye me-2"></i>Chi tiết người dùng #<?php echo $user['id']; ?>
            <?php if ($user['ngay_xoa']): ?>
                <span class="badge bg-danger ms-2">Đã xóa</span>
            <?php elseif ($user['trang_thai'] == 0): ?>
                <span class="badge bg-secondary ms-2">Đã khóa</span>
            <?php endif; ?>
        </h2>
        <div>
            <?php if (!$user['ngay_xoa'] && $user['id'] !== $_SESSION['user_id']): ?>
            <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Sửa
            </a>
            <?php endif; ?>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin người dùng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>ID:</strong>
                        </div>
                        <div class="col-md-9">
                            #<?php echo $user['id']; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Tên người dùng:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php echo htmlspecialchars($user['username'] ?? 'Chưa cập nhật'); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php echo htmlspecialchars($user['email']); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Giới tính:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php echo htmlspecialchars(ucfirst($user['gioi_tinh'] ?? 'Chưa rõ')); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Ngày sinh:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php echo $user['ngay_sinh'] ? format_date($user['ngay_sinh']) : 'Chưa cập nhật'; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Vai trò:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php if ($user['vai_tro'] === ROLE_ADMIN): ?>
                                <span class="badge bg-danger">Admin</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Khách hàng</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Trạng thái:</strong>
                        </div>
                        <div class="col-md-9">
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
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Ngày tạo:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php echo format_date($user['ngay_tao']); ?>
                        </div>
                    </div>
                    
                    <?php if ($user['ngay_xoa']): ?>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Ngày xóa:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="text-danger"><?php echo date('d/m/Y H:i:s', strtotime($user['ngay_xoa'])); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <?php if (!$user['ngay_xoa'] && $user['id'] !== $_SESSION['user_id']): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" name="new_password" 
                                   minlength="6" required>
                            <small class="text-muted">Ít nhất 6 ký tự</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" name="confirm_password" 
                                   minlength="6" required>
                        </div>
                        
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-key me-2"></i>Đổi mật khẩu
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
