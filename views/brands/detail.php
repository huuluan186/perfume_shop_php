<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Brand.php';

$brand_id = intval($_GET['id'] ?? 0);
if ($brand_id <= 0) {
    redirect('views/brands/index.php');
}

$brandModel = new Brand();
$brand = $brandModel->getById($brand_id);

if (!$brand) {
    set_message('error', 'Thương hiệu không tồn tại!');
    redirect('views/brands/index.php');
}

// Lấy sản phẩm của thương hiệu
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$products = $brandModel->getProducts($brand_id, $limit, $offset);
$total_products = $brandModel->countProducts($brand_id);
$pagination = paginate($total_products, $page, $limit);

$page_title = $brand['ten_thuong_hieu'];
include __DIR__ . '/../layout/header.php';
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="index.php">Thương hiệu</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></li>
        </ol>
    </nav>
    
    <div class="brand-header card border-0 shadow-sm mb-5">
        <div class="card-body p-5">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <?php if (!empty($brand['duong_dan_logo'])): ?>
                    <img src="<?php echo htmlspecialchars($brand['duong_dan_logo']); ?>" 
                         alt="<?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>"
                         class="img-fluid" style="max-height: 150px; object-fit: contain;"
                         onerror="this.style.display='none'">
                    <?php else: ?>
                    <h2 class="text-muted"><?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></h2>
                    <?php endif; ?>
                </div>
                <div class="col-md-9">
                    <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></h1>
                    <?php if (!empty($brand['quoc_gia'])): ?>
                    <p class="text-muted mb-2">
                        <i class="fas fa-globe me-2"></i><?php echo htmlspecialchars($brand['quoc_gia']); ?>
                    </p>
                    <?php endif; ?>
                    <p class="lead text-muted mb-3"><?php echo nl2br(htmlspecialchars($brand['mo_ta'] ?? 'Thương hiệu nước hoa cao cấp')); ?></p>
                    <p class="mb-0">
                        <i class="fas fa-box text-primary me-2"></i>
                        <strong><?php echo $total_products; ?></strong> sản phẩm
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <h4 class="fw-bold mb-4">Sản phẩm của <?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></h4>
    
    <?php if (empty($products)): ?>
    <div class="text-center py-5">
        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Chưa có sản phẩm nào</h5>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($products as $product): ?>
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
                    <?php if ($product['so_luong_ton'] > 0): ?>
                    <button class="btn btn-outline-primary w-100 add-to-cart" data-product-id="<?php echo $product['id']; ?>">
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
    
    <!-- Pagination -->
    <?php if ($pagination['total_pages'] > 1): ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <?php if ($pagination['current_page'] > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?id=<?php echo $brand_id; ?>&page=<?php echo $pagination['current_page'] - 1; ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
            <li class="page-item <?php echo ($i === $pagination['current_page']) ? 'active' : ''; ?>">
                <a class="page-link" href="?id=<?php echo $brand_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            
            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
            <li class="page-item">
                <a class="page-link" href="?id=<?php echo $brand_id; ?>&page=<?php echo $pagination['current_page'] + 1; ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
