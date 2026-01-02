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
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate
    if (empty($email)) {
        $errors[] = 'Vui lòng nhập email!';
    }
    if (empty($password)) {
        $errors[] = 'Vui lòng nhập mật khẩu!';
    }
    
    if (empty($errors)) {
        $userModel = new User();
        $user = $userModel->login($email, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_role'] = $user['vai_tro'];
            
            $_SESSION['success'] = 'Đăng nhập thành công!';
            
            // Nếu là admin thì redirect về admin dashboard, ngược lại về trang chủ
            if ($user['vai_tro'] === 'admin') {
                redirect('views/admin/dashboard.php');
            } else {
                $redirect = $_GET['redirect'] ?? 'index.php';
                redirect($redirect);
            }
        } else {
            $errors[] = 'Email hoặc mật khẩu không đúng!';
        }
    }
}

$page_title = "Đăng nhập";
include __DIR__ . '/../layout/header.php';
?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-card card border-0">
                    <div class="auth-header">
                        <h3 class="mb-0">
                            <i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập
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
                        
                        <form method="POST" id="loginForm" onsubmit="return validateForm('loginForm')">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                            </button>
                            
                            <div class="text-center">
                                <p class="mb-0">
                                    Chưa có tài khoản? 
                                    <a href="register.php" class="text-primary">Đăng ký ngay</a>
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
