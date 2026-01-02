<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/User.php';

if (!is_logged_in()) {
    redirect('views/auth/login.php');
}

$userModel = new User();
$user = $userModel->getUserById($_SESSION['user_id']);

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($current_password)) {
        $errors[] = 'Vui lòng nhập mật khẩu hiện tại!';
    } elseif (md5($current_password) !== $user['mat_khau']) {
        $errors[] = 'Mật khẩu hiện tại không đúng!';
    }
    
    if (empty($new_password)) {
        $errors[] = 'Vui lòng nhập mật khẩu mới!';
    } elseif (strlen($new_password) < 6) {
        $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
    }
    
    if ($new_password !== $confirm_password) {
        $errors[] = 'Mật khẩu xác nhận không khớp!';
    }
    
    if (empty($errors)) {
        $result = $userModel->changePasswordSimple($_SESSION['user_id'], $new_password);
        if ($result) {
            $success = true;
        } else {
            $errors[] = 'Đổi mật khẩu thất bại. Vui lòng thử lại!';
        }
    }
}

$page_title = "Đổi mật khẩu";
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
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-shopping-bag me-2"></i>Đơn hàng của tôi
                        </a>
                        <a class="nav-link active" href="change-password.php">
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
                    <h5 class="mb-0 fw-bold"><i class="fas fa-key me-2"></i>Đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>Đổi mật khẩu thành công!
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <div><i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <!-- Form bên trái -->
                        <div class="col-md-8">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="new_password" 
                                           minlength="6" required>
                                    <small class="text-muted">Tối thiểu 6 ký tự</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="confirm_password" 
                                           minlength="6" required>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i>Đổi mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Warning Box bên phải -->
                        <div class="col-md-4">
                            <div class="alert alert-warning h-100">
                                <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Lưu ý quan trọng</h6>
                                <ul class="mb-0 small">
                                    <li>Mật khẩu phải có ít nhất 6 ký tự</li>
                                    <li>Nên sử dụng kết hợp chữ hoa, chữ thường và số</li>
                                    <li>Không chia sẻ mật khẩu với người khác</li>
                                    <li>Đổi mật khẩu định kỳ để bảo mật tài khoản</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validate password match
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    const newPassword = document.querySelector('input[name="new_password"]').value;
    const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        showNotification('error', 'Mật khẩu xác nhận không khớp!');
        return false;
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
