<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/Contact.php';

$page_title = "Liên hệ";
include __DIR__ . '/layout/header.php';

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_input($_POST['name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $message = clean_input($_POST['message'] ?? '');
    
    if (empty($name)) {
        $errors[] = 'Vui lòng nhập họ tên!';
    }
    if (empty($email)) {
        $errors[] = 'Vui lòng nhập email!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ!';
    }
    if (empty($message)) {
        $errors[] = 'Vui lòng nhập nội dung!';
    }
    
    if (empty($errors)) {
        $contactModel = new Contact();
        $data = [
            'ho_ten' => $name,
            'email' => $email,
            'noi_dung' => $message
        ];
        
        if ($contactModel->create($data)) {
            $success = true;
            set_message('success', 'Cảm ơn bạn đã gửi liên hệ! Chúng tôi sẽ phản hồi sớm nhất.');
        } else {
            $errors[] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }
    }
}
        
        // Uncomment dòng dưới khi đã cấu hình mail server
        // mail($admin_email, $subject, $email_body, $headers);
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4">Liên Hệ Với Chúng Tôi</h2>
            
            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php foreach ($errors as $error): ?>
                    <div><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" id="contactForm" onsubmit="return validateForm('contactForm')">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="message" class="form-label">Nội dung <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fas fa-paper-plane me-2"></i>Gửi liên hệ
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <h4 class="fw-bold mb-4">Thông Tin Liên Hệ</h4>
            
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i>Địa chỉ</h6>
                    <p class="mb-0">126 Nguyễn Thiện Thành, Phường Hòa Thuận, tỉnh Vĩnh Long</p>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-phone text-primary me-2"></i>Điện thoại</h6>
                    <p class="mb-0">1900 1836</p>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-envelope text-primary me-2"></i>Email</h6>
                    <p class="mb-0">contact@perfumeshop.com</p>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-clock text-primary me-2"></i>Giờ làm việc</h6>
                    <p class="mb-0">Thứ 2 - Chủ Nhật: 8:00 - 22:00</p>
                </div>
            </div>
            
            <div class="mt-4">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3930.126073441039!2d106.34394437353973!3d9.923456874350201!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0175ea296facb%3A0x55ded92e29068221!2zVHLGsOG7nW5nIMSQ4bqhaSBI4buNYyBUcsOgIFZpbmg!5e0!3m2!1svi!2s!4v1767326142234!5m2!1svi!2s" 
                        width="100%" height="250" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
