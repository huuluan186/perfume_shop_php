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
    $username = clean_input($_POST['username'] ?? '');
    $gioi_tinh = clean_input($_POST['gioi_tinh'] ?? '');
    $ngay_sinh = clean_input($_POST['ngay_sinh'] ?? '');
    
    if (empty($username)) {
        $errors[] = 'Vui lòng nhập tên người dùng!';
    }
    
    if (empty($errors)) {
        // Cập nhật thông tin
        $result = $userModel->updateProfile($_SESSION['user_id'], $username, $gioi_tinh, $ngay_sinh);
        
        if ($result) {
            $_SESSION['username'] = $username;
            $success = true;
            $user = $userModel->getUserById($_SESSION['user_id']); // Refresh data
        } else {
            $errors[] = 'Cập nhật thất bại. Vui lòng thử lại!';
        }
    }
}

$page_title = "Thông tin tài khoản";
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
                        <a class="nav-link active" href="profile.php">
                            <i class="fas fa-user me-2"></i>Thông tin tài khoản
                        </a>
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-shopping-bag me-2"></i>Đơn hàng của tôi
                        </a>
                        <a class="nav-link" href="change-password.php">
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
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2"></i>Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>Cập nhật thông tin thành công!
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                <small class="text-muted">Email không thể thay đổi</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên người dùng <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="username" 
                                       value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <select class="form-select" name="gioi_tinh">
                                    <option value="">Chọn giới tính</option>
                                    <option value="nam" <?php echo ($user['gioi_tinh'] === 'nam') ? 'selected' : ''; ?>>Nam</option>
                                    <option value="nu" <?php echo ($user['gioi_tinh'] === 'nu') ? 'selected' : ''; ?>>Nữ</option>
                                    <option value="khac" <?php echo ($user['gioi_tinh'] === 'khac') ? 'selected' : ''; ?>>Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" name="ngay_sinh" 
                                       value="<?php echo $user['ngay_sinh'] ?? ''; ?>" max="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
