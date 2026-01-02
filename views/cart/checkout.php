<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/User.php';

if (!is_logged_in()) {
    redirect('views/auth/login.php?redirect=cart/checkout.php');
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    redirect('views/cart/index.php');
}

$cart_total = calculate_cart_total();
$shipping_fee = $cart_total >= 500000 ? 0 : 30000;
$total = $cart_total + $shipping_fee;

$userModel = new User();
$user = $userModel->getUserById($_SESSION['user_id']);

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_nguoi_nhan = clean_input($_POST['ten_nguoi_nhan'] ?? '');
    $sdt_nguoi_nhan = clean_input($_POST['sdt_nguoi_nhan'] ?? '');
    $dia_chi_giao_hang = clean_input($_POST['dia_chi_giao_hang'] ?? '');
    $ghi_chu = clean_input($_POST['ghi_chu'] ?? '');
    $phuong_thuc_thanh_toan = clean_input($_POST['phuong_thuc_thanh_toan'] ?? '');
    
    if (empty($ten_nguoi_nhan)) $errors[] = 'Vui lòng nhập tên người nhận!';
    if (empty($sdt_nguoi_nhan)) $errors[] = 'Vui lòng nhập số điện thoại!';
    if (empty($dia_chi_giao_hang)) $errors[] = 'Vui lòng nhập địa chỉ giao hàng!';
    if (empty($phuong_thuc_thanh_toan)) $errors[] = 'Vui lòng chọn phương thức thanh toán!';
    
    if (empty($errors)) {
        $orderModel = new Order();
        $order_id = $orderModel->create(
            $_SESSION['user_id'],
            $cart,
            $total,
            $ten_nguoi_nhan,
            $sdt_nguoi_nhan,
            $dia_chi_giao_hang,
            $ghi_chu,
            $phuong_thuc_thanh_toan
        );
        
        if ($order_id) {
            unset($_SESSION['cart']);
            set_message('success', 'Đặt hàng thành công! Mã đơn hàng: #' . $order_id);
            redirect('views/account/orders.php');
        } else {
            $errors[] = 'Đặt hàng thất bại. Vui lòng thử lại!';
        }
    }
}

$page_title = "Thanh toán";
include __DIR__ . '/../layout/header.php';
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4"><i class="fas fa-credit-card me-2"></i>Thanh toán</h2>
    
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <div><?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" id="checkoutForm" onsubmit="return validateForm('checkoutForm')">
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Thông tin giao hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên người nhận <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ten_nguoi_nhan" 
                                       value="<?php echo htmlspecialchars($user['ten_nguoi_dung'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="sdt_nguoi_nhan" 
                                       pattern="[0-9]{10}" title="Số điện thoại phải có 10 chữ số" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="dia_chi_giao_hang" rows="3" required></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea class="form-control" name="ghi_chu" rows="2" 
                                          placeholder="Ghi chú về đơn hàng (tùy chọn)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Phương thức thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="phuong_thuc_thanh_toan" 
                                   id="cod" value="COD" checked>
                            <label class="form-check-label" for="cod">
                                <i class="fas fa-money-bill-wave text-success me-2"></i>
                                <strong>Thanh toán khi nhận hàng (COD)</strong>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="phuong_thuc_thanh_toan" 
                                   id="bank" value="Chuyển khoản">
                            <label class="form-check-label" for="bank">
                                <i class="fas fa-university text-primary me-2"></i>
                                <strong>Chuyển khoản ngân hàng</strong>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Đơn hàng (<?php echo count($cart); ?> sản phẩm)</h5>
                    </div>
                    <div class="card-body">
                        <div class="order-items mb-3" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach ($cart as $item): ?>
                            <div class="d-flex mb-2 pb-2 border-bottom">
                                <img src="<?php echo UPLOAD_URL . $item['image']; ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover;" class="rounded me-2"
                                     onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.jpg'">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 small"><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <small class="text-muted">SL: <?php echo $item['quantity']; ?></small>
                                </div>
                                <div class="text-end">
                                    <strong><?php echo format_currency($item['price'] * $item['quantity']); ?></strong>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <strong><?php echo format_currency($cart_total); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <strong><?php echo $shipping_fee > 0 ? format_currency($shipping_fee) : 'Miễn phí'; ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0">Tổng cộng:</h5>
                            <h5 class="mb-0 text-primary"><?php echo format_currency($total); ?></h5>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-check-circle me-2"></i>Đặt hàng
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
