<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';

$cart = $_SESSION['cart'] ?? [];
$cart_total = calculate_cart_total();

$page_title = "Giỏ hàng";
include __DIR__ . '/../layout/header.php';
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn</h2>
    
    <?php if (empty($cart)): ?>
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">Giỏ hàng trống</h4>
        <p>Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
        <a href="<?php echo BASE_URL; ?>views/products/index.php" class="btn btn-primary">
            <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
    <?php else: ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">Hình ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th width="150">Đơn giá</th>
                                    <th width="150">Số lượng</th>
                                    <th width="150">Thành tiền</th>
                                    <th width="80"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $key => $item): ?>
                                <tr data-cart-key="<?php echo $key; ?>">
                                    <td>
                                        <?php 
                                        // Check old vs new image path
                                        $cart_img_url = '';
                                        if (!empty($item['image'])) {
                                            if (strpos($item['image'], '/') !== false) {
                                                $cart_img_url = ASSETS_URL . urldecode($item['image']);
                                            } else {
                                                $cart_img_url = UPLOAD_URL . $item['image'];
                                            }
                                        } else {
                                            $cart_img_url = ASSETS_URL . 'images/placeholder.png';
                                        }
                                        ?>
                                        <img src="<?php echo $cart_img_url; ?>" 
                                             class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;"
                                             onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                                    </td>
                                    <td>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($item['brand']); ?></small>
                                    </td>
                                    <td><?php echo format_currency($item['price']); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center" style="gap: 0.3rem;">
                                            <button class="btn btn-sm btn-outline-primary update-cart-quantity" 
                                                    data-action="decrease" data-cart-key="<?php echo $key; ?>"
                                                    style="width: 32px; height: 32px; padding: 0;">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="text" class="form-control form-control-sm text-center fw-bold cart-quantity" 
                                                   value="<?php echo $item['quantity']; ?>" readonly
                                                   style="width: 60px; background-color: #fff !important; color: #000 !important; border: 1px solid #dee2e6;">
                                            <button class="btn btn-sm btn-outline-primary update-cart-quantity" 
                                                    data-action="increase" data-cart-key="<?php echo $key; ?>"
                                                    style="width: 32px; height: 32px; padding: 0;">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="fw-bold"><?php echo format_currency($item['price'] * $item['quantity']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger remove-from-cart" 
                                                data-cart-key="<?php echo $key; ?>" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-bold mb-0">Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tạm tính:</span>
                        <strong><?php echo format_currency($cart_total); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Phí vận chuyển:</span>
                        <strong><?php echo $cart_total >= 500000 ? 'Miễn phí' : format_currency(30000); ?></strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="mb-0">Tổng cộng:</h5>
                        <h5 class="mb-0 text-primary">
                            <?php echo format_currency($cart_total + ($cart_total >= 500000 ? 0 : 30000)); ?>
                        </h5>
                    </div>
                    
                    <?php if (is_logged_in()): ?>
                    <a href="checkout.php" class="btn btn-primary w-100 btn-lg mb-2">
                        <i class="fas fa-credit-card me-2"></i>Đặt hàng
                    </a>
                    <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>views/auth/login.php?redirect=cart/checkout.php" 
                       class="btn btn-primary w-100 btn-lg mb-2">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập để thanh toán
                    </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo BASE_URL; ?>views/products/index.php" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Update cart quantity - tương tự trang chi tiết sản phẩm
document.addEventListener('DOMContentLoaded', function() {
    // Handle all increase buttons
    document.querySelectorAll('.update-cart-quantity[data-action="increase"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const cartKey = this.getAttribute('data-cart-key');
            const row = this.closest('tr');
            const input = row.querySelector('.cart-quantity');
            
            $.ajax({
                url: 'update.php',
                method: 'POST',
                data: { cart_key: cartKey, action: 'increase' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        showNotification('error', response.message);
                    }
                }
            });
        });
    });
    
    // Handle all decrease buttons
    document.querySelectorAll('.update-cart-quantity[data-action="decrease"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const cartKey = this.getAttribute('data-cart-key');
            const row = this.closest('tr');
            const input = row.querySelector('.cart-quantity');
            const currentQty = parseInt(input.value) || 1;
            
            if (currentQty <= 1) {
                showNotification('error', 'Số lượng tối thiểu là 1');
                return;
            }
            
            $.ajax({
                url: 'update.php',
                method: 'POST',
                data: { cart_key: cartKey, action: 'decrease' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        showNotification('error', response.message);
                    }
                }
            });
        });
    });
});

// Remove from cart
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-from-cart').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
            
            const cartKey = this.getAttribute('data-cart-key');
            
            $.ajax({
                url: 'remove.php',
                method: 'POST',
                data: { cart_key: cartKey },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotification('success', response.message);
                        location.reload();
                    } else {
                        showNotification('error', response.message);
                        if (response.debug) {
                            console.log('Debug info:', response.debug);
                        }
                    }
                }
            });
        });
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
