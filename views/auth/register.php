<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/User.php';

// Nếu đã đăng nhập thì chuyển về trang chủ
if (is_logged_in()) {
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $gioi_tinh = clean_input($_POST['gioi_tinh'] ?? '');
    $ngay_sinh = clean_input($_POST['ngay_sinh'] ?? '');
    
    // Validate
    if (empty($username)) {
        $errors[] = 'Vui lòng nhập họ tên!';
    }
    if (empty($email)) {
        $errors[] = 'Vui lòng nhập email!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ!';
    }
    if (empty($password)) {
        $errors[] = 'Vui lòng nhập mật khẩu!';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự!';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Mật khẩu xác nhận không khớp!';
    }
    
    if (empty($errors)) {
        $userModel = new User();
        
        // Kiểm tra email đã tồn tại
        if ($userModel->emailExists($email)) {
            $errors[] = 'Email đã được sử dụng!';
        } else {
            $user_id = $userModel->register($username, $email, $password, $gioi_tinh, $ngay_sinh);
            
            if ($user_id) {
                $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                redirect('views/auth/login.php');
            } else {
                $errors[] = 'Có lỗi xảy ra. Vui lòng thử lại!';
            }
        }
    }
}

$page_title = "Đăng ký";
include __DIR__ . '/../layout/header.php';
?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="auth-card card border-0">
                    <div class="auth-header">
                        <h3 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>Đăng Ký
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <div><?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="registerForm" onsubmit="return validateForm('registerForm')">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="username" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="gioi_tinh" class="form-label">Giới tính</label>
                                    <select class="form-select" id="gioi_tinh" name="gioi_tinh">
                                        <option value="">Chọn giới tính</option>
                                        <option value="Nam" <?php echo (isset($_POST['gioi_tinh']) && $_POST['gioi_tinh'] === 'Nam') ? 'selected' : ''; ?>>Nam</option>
                                        <option value="Nữ" <?php echo (isset($_POST['gioi_tinh']) && $_POST['gioi_tinh'] === 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                                        <option value="Khác" <?php echo (isset($_POST['gioi_tinh']) && $_POST['gioi_tinh'] === 'Khác') ? 'selected' : ''; ?>>Khác</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh" 
                                           value="<?php echo htmlspecialchars($_POST['ngay_sinh'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <small class="text-muted">Tối thiểu 6 ký tự</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="agree" required>
                                <label class="form-check-label" for="agree">
                                    Tôi đồng ý với các <a href="#" class="text-primary">điều khoản dịch vụ</a>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-user-plus me-2"></i>Đăng ký
                            </button>
                            
                            <div class="text-center">
                                <p class="mb-0">
                                    Đã có tài khoản? 
                                    <a href="login.php" class="text-primary">Đăng nhập ngay</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
