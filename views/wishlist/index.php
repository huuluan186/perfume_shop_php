<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Wishlist.php';

if (!is_logged_in()) {
    redirect('views/auth/login.php?redirect=wishlist/index.php');
}

$wishlistModel = new Wishlist();
$wishlist_items = $wishlistModel->getByUserId($_SESSION['user_id']);

$page_title = "Danh sách yêu thích";
include __DIR__ . '/../layout/header.php';
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4"><i class="fas fa-heart me-2 text-danger"></i>Danh sách yêu thích</h2>
    
    <?php if (empty($wishlist_items)): ?>
    <div class="text-center py-5">
        <i class="far fa-heart fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">Danh sách yêu thích trống</h4>
        <p>Hãy thêm sản phẩm yêu thích để không bỏ lỡ những sản phẩm tuyệt vời</p>
        <a href="<?php echo BASE_URL; ?>views/products/index.php" class="btn btn-primary">
            <i class="fas fa-shopping-bag me-2"></i>Khám phá sản phẩm
        </a>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($wishlist_items as $item): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="product-card card h-100 border-0 shadow-sm">
                <div class="position-relative">
                    <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-from-wishlist" 
                            data-product-id="<?php echo $item['id']; ?>" 
                            style="z-index: 10; border-radius: 50%; width: 36px; height: 36px; padding: 0;"
                            title="Xóa khỏi yêu thích">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
                <div class="product-image position-relative overflow-hidden">
                    <?php 
                    // Check old vs new image path
                    $wish_img_url = '';
                    if (!empty($item['duong_dan_hinh_anh'])) {
                        if (strpos($item['duong_dan_hinh_anh'], '/') !== false) {
                            $wish_img_url = ASSETS_URL . urldecode($item['duong_dan_hinh_anh']);
                        } else {
                            $wish_img_url = UPLOAD_URL . $item['duong_dan_hinh_anh'];
                        }
                    } else {
                        $wish_img_url = ASSETS_URL . 'images/placeholder.png';
                    }
                    ?>
                    <img src="<?php echo $wish_img_url; ?>" 
                         class="card-img-top" alt="<?php echo htmlspecialchars($item['ten_san_pham']); ?>"
                         onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                    <div class="product-overlay">
                        <a href="<?php echo BASE_URL; ?>views/products/detail.php?id=<?php echo $item['id']; ?>" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="product-brand text-muted small"><?php echo htmlspecialchars($item['ten_thuong_hieu']); ?></h6>
                    <h5 class="product-name mb-2">
                        <a href="<?php echo BASE_URL; ?>views/products/detail.php?id=<?php echo $item['id']; ?>" 
                           class="text-decoration-none text-dark">
                            <?php echo htmlspecialchars($item['ten_san_pham']); ?>
                        </a>
                    </h5>
                    <div class="product-price mb-3">
                        <span class="fw-bold text-primary"><?php echo format_currency($item['gia_ban']); ?></span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <?php if ($item['so_luong_ton'] > 0): ?>
                    <button class="btn btn-outline-primary w-100 add-to-cart" data-product-id="<?php echo $item['id']; ?>">
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                    </button>
                    <?php else: ?>
                    <button class="btn btn-outline-secondary w-100" disabled>
                        <i class="fas fa-ban"></i> Hết hàng
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).on('click', '.remove-from-wishlist', function() {
    const productId = $(this).data('product-id');
    
    $.ajax({
        url: 'remove.php',
        method: 'POST',
        data: { product_id: productId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification('success', response.message);
                updateWishlistCount();
                setTimeout(() => location.reload(), 500);
            } else {
                showNotification('error', response.message);
            }
        }
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
