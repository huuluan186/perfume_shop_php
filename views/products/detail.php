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
$related_products = $productModel->getRelated($product_id, $product['id_danh_muc'] ?? 0, 4);

// Kiểm tra wishlist
$is_in_wishlist = false;
if (is_logged_in()) {
    $wishlistModel = new Wishlist();
    $is_in_wishlist = $wishlistModel->exists($_SESSION['user_id'], $product_id);
}

$page_title = $product['ten_san_pham'];
include __DIR__ . '/../layout/header.php';
?>

<div class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>views/products/index.php">Sản phẩm</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['ten_san_pham']); ?></li>
        </ol>
    </nav>
    
    <div class="row mt-4">
        <div class="col-lg-5">
            <div class="product-image-gallery">
                <div class="main-image mb-3">
                    <?php 
                    // Check old vs new image path
                    $image_url = '';
                    if (!empty($product['duong_dan_hinh_anh'])) {
                        if (strpos($product['duong_dan_hinh_anh'], '/') !== false) {
                            // Old path: products/Brand/Product/image.jpg
                            $image_url = ASSETS_URL . urldecode($product['duong_dan_hinh_anh']);
                        } else {
                            // New path: product_20260104_xxx.png (in uploads/)
                            $image_url = UPLOAD_URL . $product['duong_dan_hinh_anh'];
                        }
                    } else {
                        $image_url = ASSETS_URL . 'images/placeholder.png';
                    }
                    ?>
                    <img src="<?php echo $image_url; ?>" 
                         class="img-fluid rounded shadow" id="mainProductImage"
                         alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>"
                         onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'"
                         style="width: 100%; height: auto; max-height: 500px; object-fit: cover;">
                </div>
            </div>
        </div>
        
        <div class="col-lg-7">
            <div class="product-details">
                <?php if (isset($product['thuong_hieu_id']) && isset($product['ten_thuong_hieu'])): ?>
                <p class="text-muted mb-2">
                    <a href="<?php echo BASE_URL; ?>views/brands/detail.php?id=<?php echo $product['thuong_hieu_id']; ?>" 
                       class="text-decoration-none">
                        <?php echo htmlspecialchars($product['ten_thuong_hieu']); ?>
                    </a>
                </p>
                <?php endif; ?>
                <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($product['ten_san_pham']); ?></h2>
                
                <div class="price-section mb-2">
                    <h3 class="text-primary fw-bold mb-0 d-inline-block me-3"><?php echo format_currency($product['gia_ban']); ?></h3>
                    <?php if ($product['so_luong_ton'] > 0): ?>
                        <span class="badge bg-success fs-6">Còn hàng (<?php echo $product['so_luong_ton']; ?>)</span>
                    <?php else: ?>
                        <span class="badge bg-danger fs-6">Hết hàng</span>
                    <?php endif; ?>
                </div>
                
                <div class="product-info mb-4 mt-4">
                    <table class="table table-borderless" style="font-size: 1.05rem;">
                        <tr>
                            <td width="150" style="white-space: nowrap;"><strong>Danh mục:</strong></td>
                            <td><?php echo htmlspecialchars($product['ten_danh_muc']); ?></td>
                        </tr>
                        <?php if (isset($product['dung_tich_ml']) && !empty($product['dung_tich_ml'])): ?>
                        <tr>
                            <td style="white-space: nowrap;"><strong>Dung tích:</strong></td>
                            <td><?php echo htmlspecialchars($product['dung_tich_ml']); ?> ml</td>
                        </tr>
                        <?php endif; ?>
                        <?php if (isset($product['gioi_tinh_phu_hop']) && !empty($product['gioi_tinh_phu_hop'])): ?>
                        <tr>
                            <td style="white-space: nowrap;"><strong>Giới tính:</strong></td>
                            <td><?php echo ucfirst($product['gioi_tinh_phu_hop']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (isset($product['nhom_huong']) && !empty($product['nhom_huong'])): ?>
                        <tr>
                            <td style="white-space: nowrap; vertical-align: top;"><strong>Nhóm hương:</strong></td>
                            <td><?php echo htmlspecialchars($product['nhom_huong']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (isset($product['phong_cach']) && !empty($product['phong_cach'])): ?>
                        <tr>
                            <td style="white-space: nowrap;"><strong>Phong cách:</strong></td>
                            <td><?php echo ucwords(mb_strtolower(htmlspecialchars($product['phong_cach']), 'UTF-8'), " \t\r\n\f\v,"); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (isset($product['xuat_xu']) && !empty($product['xuat_xu'])): ?>
                        <tr>
                            <td style="white-space: nowrap;"><strong>Xuất xứ:</strong></td>
                            <td><?php echo ucfirst(mb_strtolower(htmlspecialchars($product['xuat_xu']), 'UTF-8')); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (isset($product['nam_phat_hanh']) && !empty($product['nam_phat_hanh'])): ?>
                        <tr>
                            <td style="white-space: nowrap;"><strong>Năm phát hành:</strong></td>
                            <td><?php echo htmlspecialchars($product['nam_phat_hanh']); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
                
                <?php if ($product['so_luong_ton'] > 0): ?>
                <div class="quantity-section mb-4">
                    <label class="form-label fw-bold mb-2">Số lượng:</label>
                    <div class="input-group" style="width: 160px;">
                        <button class="btn btn-outline-primary" type="button" id="decreaseBtn">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="text" class="form-control text-center" id="productQuantity" 
                               value="1" readonly
                               style="font-size: 1.1rem; font-weight: bold; background-color: #fff !important; color: #000 !important; -webkit-appearance: none;">
                        <button class="btn btn-outline-primary" type="button" id="increaseBtn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                
                <div class="action-buttons mb-4">
                    <button class="btn btn-primary btn-lg px-4 me-2 add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                        <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ
                    </button>
                    <button class="btn btn-success btn-lg px-4 me-2 buy-now" data-product-id="<?php echo $product['id']; ?>">
                        <i class="fas fa-bolt me-2"></i>Mua ngay
                    </button>
                    <button class="btn <?php echo $is_in_wishlist ? 'btn-danger' : 'btn-outline-danger'; ?> btn-lg toggle-wishlist" 
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
            </div>
        </div>
    </div>
    
    <!-- Mô tả sản phẩm -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="product-description">
                <h5 class="fw-bold mb-3">Mô tả sản phẩm</h5>
                <div class="text-muted text-justify"><?php echo nl2br($product['mo_ta'] ?? 'Đang cập nhật...'); ?></div>
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
                        <img src="<?php echo ASSETS_URL . urldecode($rp['duong_dan_hinh_anh']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($rp['ten_san_pham']); ?>"
                             onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
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
const maxQuantity = <?php echo $product['so_luong_ton'] ?? 999; ?>;

// Quantity controls
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('productQuantity');
    const increaseBtn = document.getElementById('increaseBtn');
    const decreaseBtn = document.getElementById('decreaseBtn');
    
    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            let current = parseInt(quantityInput.value) || 1;
            if (current < maxQuantity) {
                quantityInput.value = current + 1;
            }
        });
    }
    
    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            let current = parseInt(quantityInput.value) || 1;
            if (current > 1) {
                quantityInput.value = current - 1;
            }
        });
    }
    
    // Buy now button - using direct selector instead of delegation
    const buyNowBtn = document.querySelector('.buy-now');
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function() {
            console.log('Buy now clicked!');
            const productId = this.getAttribute('data-product-id');
            const quantity = document.getElementById('productQuantity').value || 1;
            
            console.log('Product ID:', productId, 'Quantity:', quantity);
            
            $.ajax({
                url: '<?php echo BASE_URL; ?>views/cart/add.php',
                method: 'POST',
                data: { product_id: productId, quantity: quantity },
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    if (response.success) {
                        console.log('Redirecting to checkout...');
                        window.location.href = '<?php echo BASE_URL; ?>views/cart/checkout.php';
                    } else {
                        showNotification('error', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    showNotification('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
                }
            });
        });
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
