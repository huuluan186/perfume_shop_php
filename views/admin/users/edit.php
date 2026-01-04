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

// Không cho edit chính mình
if ($user_id === $_SESSION['user_id']) {
    set_message('error', 'Không thể sửa tài khoản của chính mình!');
    redirect('views/admin/users/index.php');
}

$userModel = new User();
$user = $userModel->getUserByIdWithDeleted($user_id);

if (!$user) {
    set_message('error', 'Không tìm thấy người dùng!');
    redirect('views/admin/users/index.php');
}

// Không cho edit user đã xóa
if ($user['ngay_xoa']) {
    set_message('error', 'Không thể sửa người dùng đã bị xóa!');
    redirect('views/admin/users/index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $gioi_tinh = clean_input($_POST['gioi_tinh'] ?? '');
    $ngay_sinh = clean_input($_POST['ngay_sinh'] ?? '');
    $vai_tro = clean_input($_POST['vai_tro'] ?? ROLE_CUSTOMER);
    
    // Validate
    if (empty($username)) $errors[] = 'Vui lòng nhập tên người dùng!';
    if (empty($email)) $errors[] = 'Vui lòng nhập email!';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ!';
    
    if (empty($errors)) {
        $result = $userModel->updateUser($user_id, $username, $email, $gioi_tinh, $ngay_sinh, $vai_tro);
        if ($result) {
            set_message('success', 'Cập nhật người dùng thành công!');
            redirect('views/admin/users/index.php');
        } else {
            $errors[] = 'Email đã tồn tại hoặc cập nhật người dùng thất bại!';
        }
    }
}

$page_title = "Sửa người dùng";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-edit me-2"></i>Sửa người dùng #<?php echo $user['id']; ?></h2>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
    
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tên người dùng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" 
                                   value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <select class="form-select" name="gioi_tinh">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="nam" <?php echo ($user['gioi_tinh'] === 'nam') ? 'selected' : ''; ?>>Nam</option>
                                <option value="nữ" <?php echo ($user['gioi_tinh'] === 'nữ') ? 'selected' : ''; ?>>Nữ</option>
                                <option value="khác" <?php echo ($user['gioi_tinh'] === 'khác') ? 'selected' : ''; ?>>Khác</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" name="ngay_sinh" 
                                   value="<?php echo htmlspecialchars($user['ngay_sinh'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                            <select class="form-select" name="vai_tro" required>
                                <option value="<?php echo ROLE_CUSTOMER; ?>" <?php echo ($user['vai_tro'] === ROLE_CUSTOMER) ? 'selected' : ''; ?>>Khách hàng</option>
                                <option value="<?php echo ROLE_ADMIN; ?>" <?php echo ($user['vai_tro'] === ROLE_ADMIN) ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Lưu ý:</strong> Để đổi mật khẩu cho người dùng này, vui lòng xem chi tiết người dùng.
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-end">
                    <a href="index.php" class="btn btn-secondary px-4">Hủy</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
