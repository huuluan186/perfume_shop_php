<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/functions.php';

$page_title = "Liên hệ";
include __DIR__ . '/layout/header.php';

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_input($_POST['name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $phone = clean_input($_POST['phone'] ?? '');
    $message = clean_input($_POST['message'] ?? '');
    
    if (empty($name)) {
        $errors[] = 'Vui lòng nhập họ tên!';
    }
    if (empty($email)) {
        $errors[] = 'Vui lòng nhập email!';
    }
    if (empty($message)) {
        $errors[] = 'Vui lòng nhập nội dung!';
    }
    
    if (empty($errors)) {
        // Lưu vào file log
        $log_dir = __DIR__ . '/../logs';
        if (!file_exists($log_dir)) {
            mkdir($log_dir, 0777, true);
        }
        
        $log_file = $log_dir . '/contact_' . date('Y-m') . '.txt';
        $log_content = sprintf(
            "\n========================================\nNgày giờ: %s\nHọ tên: %s\nEmail: %s\nSố điện thoại: %s\nNội dung:\n%s\n========================================\n",
            date('d/m/Y H:i:s'),
            $name,
            $email,
            $phone ?: 'Không có',
            $message
        );
        file_put_contents($log_file, $log_content, FILE_APPEND);
        
        // Gửi email thông báo cho admin (nếu cấu hình mail)
        $admin_email = 'admin@perfumeshop.com'; // Thay bằng email của bạn
        $subject = '[Liên hệ mới] ' . $name;
        $email_body = "Có liên hệ mới từ website:\n\n" . $log_content;
        $headers = "From: noreply@perfumeshop.com\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        // Uncomment dòng dưới khi đã cấu hình mail server
        // mail($admin_email, $subject, $email_body, $headers);
        
        $success = true;
    }
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4">Liên Hệ Với Chúng Tôi</h2>
            
            <?php if ($success): ?>
            <div class="alert alert-success">
                Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.
            </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
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
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
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
