<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Brand.php';

$productModel = new Product();
$categoryModel = new Category();
$brandModel = new Brand();

// Lấy dữ liệu cho trang chủ
$newest_products = $productModel->getNewest(8);
$bestselling_products = $productModel->getBestSelling(8);
$categories = $categoryModel->getAll();
$brands = $brandModel->getAll(12);

$page_title = "Trang chủ - Cửa hàng nước hoa";
include __DIR__ . '/views/layout/header.php';
?>

<!-- Banner Slider -->
<section class="banner-slider">
    <div id="bannerCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="3"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="4"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="<?php echo ASSETS_URL; ?>images/banners/slideshow_1.jpg" class="d-block w-100" alt="Banner 1" style="height: 500px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?php echo ASSETS_URL; ?>images/banners/slideshow_2.jpg" class="d-block w-100" alt="Banner 2" style="height: 500px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?php echo ASSETS_URL; ?>images/banners/slideshow_3.jpg" class="d-block w-100" alt="Banner 3" style="height: 500px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?php echo ASSETS_URL; ?>images/banners/slideshow_4.jpg" class="d-block w-100" alt="Banner 4" style="height: 500px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="<?php echo ASSETS_URL; ?>images/banners/slideshow_5.jpg" class="d-block w-100" alt="Banner 5" style="height: 500px; object-fit: cover;">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Features Section -->
<section class="features py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                    <h5>Giao Hàng Nhanh</h5>
                    <p class="text-muted small">Miễn phí vận chuyển</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <i class="fas fa-certificate fa-3x text-primary mb-3"></i>
                    <h5>Hàng Chính Hãng</h5>
                    <p class="text-muted small">100% chính hãng</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <i class="fas fa-undo fa-3x text-primary mb-3"></i>
                    <h5>Đổi Trả Dễ Dàng</h5>
                    <p class="text-muted small">Trong vòng 7 ngày</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center">
                    <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                    <h5>Hỗ Trợ 24/7</h5>
                    <p class="text-muted small">Luôn sẵn sàng hỗ trợ</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- New Products Section -->
<section class="new-products py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="fw-bold">Sản Phẩm Mới Nhất</h2>
            <p class="text-muted">Khám phá những mùi hương mới nhất từ các thương hiệu nổi tiếng</p>
        </div>
        <div class="row g-4">
            <?php foreach ($newest_products as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card card h-100 border-0 shadow-sm">
                    <div class="product-image position-relative overflow-hidden">
                        <img src="<?php echo ASSETS_URL . urldecode($product['duong_dan_hinh_anh']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>"
                             onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.jpg'">
                        <div class="product-overlay">
                            <a href="<?php echo BASE_URL; ?>views/products/detail.php?id=<?php echo $product['id']; ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="product-brand text-muted small"><?php echo htmlspecialchars($product['ten_thuong_hieu']); ?></h6>
                        <h5 class="product-name mb-2">
                            <a href="<?php echo BASE_URL; ?>views/products/detail.php?id=<?php echo $product['id']; ?>" 
                               class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($product['ten_san_pham']); ?>
                            </a>
                        </h5>
                        <div class="product-price">
                            <span class="fw-bold text-primary"><?php echo format_currency($product['gia_ban']); ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <button class="btn btn-outline-primary w-100 add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                            <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>views/products/index.php" class="btn btn-primary btn-lg px-5">
                Xem tất cả sản phẩm <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Best Selling Products Section -->
<section class="bestselling-products py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="fw-bold">Sản Phẩm Bán Chạy</h2>
            <p class="text-muted">Những sản phẩm được yêu thích nhất</p>
        </div>
        <div class="row g-4">
            <?php foreach ($bestselling_products as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card card h-100 border-0 shadow-sm">
                    <div class="product-image position-relative overflow-hidden">
                        <img src="<?php echo ASSETS_URL . urldecode($product['duong_dan_hinh_anh']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>"
                             onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.jpg'">
                        <div class="product-overlay">
                            <a href="<?php echo BASE_URL; ?>views/products/detail.php?id=<?php echo $product['id']; ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="product-brand text-muted small"><?php echo htmlspecialchars($product['ten_thuong_hieu']); ?></h6>
                        <h5 class="product-name mb-2">
                            <a href="<?php echo BASE_URL; ?>views/products/detail.php?id=<?php echo $product['id']; ?>" 
                               class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($product['ten_san_pham']); ?>
                            </a>
                        </h5>
                        <div class="product-price">
                            <span class="fw-bold text-primary"><?php echo format_currency($product['gia_ban']); ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <button class="btn btn-outline-primary w-100 add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                            <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Brands Section -->
<section class="brands py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="fw-bold">Thương Hiệu</h2>
            <p class="text-muted">Các thương hiệu nước hoa hàng đầu thế giới</p>
        </div>
        <div class="row g-4">
            <?php foreach ($brands as $brand): ?>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <a href="<?php echo BASE_URL; ?>views/brands/detail.php?id=<?php echo $brand['id']; ?>" 
                   class="brand-item d-block text-center p-3 border rounded hover-shadow h-100" 
                   style="text-decoration: none; min-height: 120px; display: flex; flex-direction: column; justify-content: center; align-items: center; transition: all 0.3s ease;">
                    <div style="height: 70px; display: flex; align-items: center; justify-content: center; width: 100%; margin-bottom: 10px;">
                        <?php if (!empty($brand['duong_dan_logo'])): ?>
                            <img src="<?php echo htmlspecialchars($brand['duong_dan_logo']); ?>" 
                                 alt="<?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>"
                                 class="img-fluid" 
                                 style="max-height: 60px; max-width: 100%; object-fit: contain;"
                                 onerror="this.style.display='none'; this.parentElement.parentElement.querySelector('.brand-name').style.display='block';">
                        <?php else: ?>
                            <i class="fas fa-copyright fa-2x text-muted"></i>
                        <?php endif; ?>
                    </div>
                    <h6 class="mb-0 text-dark brand-name" style="font-size: 0.8rem; <?php echo !empty($brand['duong_dan_logo']) ? 'display: none;' : ''; ?>">
                        <?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>
                    </h6>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>views/brands/index.php" class="btn btn-outline-primary btn-lg px-5">
                Xem tất cả thương hiệu
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/views/layout/footer.php'; ?>
