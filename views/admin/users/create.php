<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/User.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$userModel = new User();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $password = clean_input($_POST['password'] ?? '');
    $confirm_password = clean_input($_POST['confirm_password'] ?? '');
    $gioi_tinh = clean_input($_POST['gioi_tinh'] ?? '');
    $ngay_sinh = clean_input($_POST['ngay_sinh'] ?? '');
    $vai_tro = clean_input($_POST['vai_tro'] ?? ROLE_CUSTOMER);
    
    // Validate
    if (empty($username)) $errors[] = 'Vui lòng nhập tên người dùng!';
    if (empty($email)) $errors[] = 'Vui lòng nhập email!';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ!';
    if (empty($password)) $errors[] = 'Vui lòng nhập mật khẩu!';
    elseif (strlen($password) < 6) $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự!';
    if ($password !== $confirm_password) $errors[] = 'Xác nhận mật khẩu không khớp!';
    
    if (empty($errors)) {
        $user_id = $userModel->createUser($username, $email, $password, $gioi_tinh, $ngay_sinh, $vai_tro);
        if ($user_id) {
            set_message('success', 'Thêm người dùng thành công!');
            redirect('views/admin/users/index.php');
        } else {
            $errors[] = 'Email đã tồn tại hoặc thêm người dùng thất bại!';
        }
    }
}

$page_title = "Thêm người dùng";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-plus me-2"></i>Thêm người dùng</h2>
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
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" 
                                   minlength="6" required>
                            <small class="text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="confirm_password" 
                                   minlength="6" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <select class="form-select" name="gioi_tinh">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="nam" <?php echo (($_POST['gioi_tinh'] ?? '') === 'nam') ? 'selected' : ''; ?>>Nam</option>
                                <option value="nữ" <?php echo (($_POST['gioi_tinh'] ?? '') === 'nữ') ? 'selected' : ''; ?>>Nữ</option>
                                <option value="khác" <?php echo (($_POST['gioi_tinh'] ?? '') === 'khác') ? 'selected' : ''; ?>>Khác</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" name="ngay_sinh" 
                                   value="<?php echo htmlspecialchars($_POST['ngay_sinh'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                            <select class="form-select" name="vai_tro" required>
                                <option value="<?php echo ROLE_CUSTOMER; ?>" <?php echo (($_POST['vai_tro'] ?? ROLE_CUSTOMER) === ROLE_CUSTOMER) ? 'selected' : ''; ?>>Khách hàng</option>
                                <option value="<?php echo ROLE_ADMIN; ?>" <?php echo (($_POST['vai_tro'] ?? '') === ROLE_ADMIN) ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-end">
                    <a href="index.php" class="btn btn-secondary px-4">Hủy</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
