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
                                        <img src="<?php echo UPLOAD_URL . $item['image']; ?>" 
                                             class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;"
                                             onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.jpg'">
                                    </td>
                                    <td>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($item['brand']); ?></small>
                                    </td>
                                    <td><?php echo format_currency($item['price']); ?></td>
                                    <td>
                                        <div class="input-group" style="width: 120px;">
                                            <button class="btn btn-sm btn-outline-secondary update-cart-quantity" 
                                                    data-action="decrease" data-cart-key="<?php echo $key; ?>">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="form-control form-control-sm text-center cart-quantity" 
                                                   value="<?php echo $item['quantity']; ?>" min="1" readonly>
                                            <button class="btn btn-sm btn-outline-secondary update-cart-quantity" 
                                                    data-action="increase" data-cart-key="<?php echo $key; ?>">
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
                        <i class="fas fa-credit-card me-2"></i>Thanh toán
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
$(document).on('click', '.update-cart-quantity', function() {
    const cartKey = $(this).data('cart-key');
    const action = $(this).data('action');
    
    $.ajax({
        url: 'update.php',
        method: 'POST',
        data: { cart_key: cartKey, action: action },
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

$(document).on('click', '.remove-from-cart', function() {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
    
    const cartKey = $(this).data('cart-key');
    
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
            }
        }
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
