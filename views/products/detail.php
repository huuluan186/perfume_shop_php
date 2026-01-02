<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Wishlist.php';

$product_id = intval($_GET['id'] ?? 0);
if ($product_id <= 0) {
    redirect('views/products/index.php');
}

$productModel = new Product();
$product = $productModel->getById($product_id);

if (!$product) {
    set_message('error', 'Sản phẩm không tồn tại!');
    redirect('views/products/index.php');
}

// Lấy sản phẩm liên quan
$related_products = $productModel->getRelated($product_id, $product['danh_muc_id'], 4);

// Kiểm tra wishlist
$is_in_wishlist = false;
if (is_logged_in()) {
    $wishlistModel = new Wishlist();
    $is_in_wishlist = $wishlistModel->exists($_SESSION['user_id'], $product_id);
}

$page_title = $product['ten_san_pham'];
include __DIR__ . '/../layout/header.php';
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>views/products/index.php">Sản phẩm</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['ten_san_pham']); ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-lg-5">
            <div class="product-image-gallery">
                <div class="main-image mb-3">
                    <img src="<?php echo UPLOAD_URL . $product['duong_dan_hinh_anh']; ?>" 
                         class="img-fluid rounded shadow" id="mainProductImage"
                         alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>"
                         onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.jpg'">
                </div>
            </div>
        </div>
        
        <div class="col-lg-7">
            <div class="product-details">
                <p class="text-muted mb-2">
                    <a href="<?php echo BASE_URL; ?>views/brands/detail.php?id=<?php echo $product['thuong_hieu_id']; ?>" 
                       class="text-decoration-none">
                        <?php echo htmlspecialchars($product['ten_thuong_hieu']); ?>
                    </a>
                </p>
                <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($product['ten_san_pham']); ?></h2>
                
                <div class="price-section mb-4">
                    <h3 class="text-primary fw-bold mb-0"><?php echo format_currency($product['gia_ban']); ?></h3>
                </div>
                
                <div class="product-info mb-4">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Danh mục:</strong></td>
                            <td><?php echo htmlspecialchars($product['ten_danh_muc']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Dung tích:</strong></td>
                            <td><?php echo htmlspecialchars($product['dung_tich']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Giới tính:</strong></td>
                            <td><?php echo ucfirst($product['gioi_tinh']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tình trạng:</strong></td>
                            <td>
                                <?php if ($product['so_luong_ton'] > 0): ?>
                                    <span class="badge bg-success">Còn hàng (<?php echo $product['so_luong_ton']; ?>)</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Hết hàng</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <?php if ($product['so_luong_ton'] > 0): ?>
                <div class="quantity-section mb-4">
                    <label class="form-label fw-bold">Số lượng:</label>
                    <div class="input-group" style="width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="form-control text-center" id="productQuantity" 
                               value="1" min="1" max="<?php echo $product['so_luong_ton']; ?>">
                        <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity(<?php echo $product['so_luong_ton']; ?>)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                
                <div class="action-buttons mb-4">
                    <button class="btn btn-primary btn-lg px-5 me-2 add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                        <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ
                    </button>
                    <button class="btn btn-outline-danger btn-lg toggle-wishlist" 
                            data-product-id="<?php echo $product['id']; ?>"
                            data-in-wishlist="<?php echo $is_in_wishlist ? '1' : '0'; ?>">
                        <i class="<?php echo $is_in_wishlist ? 'fas' : 'far'; ?> fa-heart"></i>
                    </button>
                </div>
                <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>Sản phẩm tạm thời hết hàng
                </div>
                <?php endif; ?>
                
                <div class="product-description">
                    <h5 class="fw-bold mb-3">Mô tả sản phẩm</h5>
                    <p><?php echo nl2br(htmlspecialchars($product['mo_ta'] ?? 'Đang cập nhật...')); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($related_products)): ?>
    <div class="mt-5">
        <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
        <div class="row g-4">
            <?php foreach ($related_products as $rp): ?>
            <div class="col-lg-3 col-md-6">
                <div class="product-card card h-100 border-0 shadow-sm">
                    <div class="product-image position-relative overflow-hidden">
                        <img src="<?php echo UPLOAD_URL . $rp['duong_dan_hinh_anh']; ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($rp['ten_san_pham']); ?>"
                             onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.jpg'">
                        <div class="product-overlay">
                            <a href="detail.php?id=<?php echo $rp['id']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="product-brand text-muted small"><?php echo htmlspecialchars($rp['ten_thuong_hieu']); ?></h6>
                        <h5 class="product-name mb-2">
                            <a href="detail.php?id=<?php echo $rp['id']; ?>" class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($rp['ten_san_pham']); ?>
                            </a>
                        </h5>
                        <div class="product-price">
                            <span class="fw-bold text-primary"><?php echo format_currency($rp['gia_ban']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function increaseQuantity(max) {
    const input = document.getElementById('productQuantity');
    const currentValue = parseInt(input.value);
    if (currentValue < max) {
        input.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('productQuantity');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

// Override add to cart to include quantity
$(document).on('click', '.add-to-cart', function() {
    const productId = $(this).data('product-id');
    const quantity = $('#productQuantity').val();
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>views/cart/add.php',
        method: 'POST',
        data: { product_id: productId, quantity: quantity },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification('success', response.message);
                updateCartCount();
            } else {
                showNotification('error', response.message);
            }
        }
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
