<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Brand.php';

$brandModel = new Brand();

// Phân trang
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$brands = $brandModel->getAll($limit, $offset);
$total_brands = $brandModel->count();
$pagination = paginate($total_brands, $page, $limit);

$page_title = "Thương hiệu";
include __DIR__ . '/../layout/header.php';
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Thương Hiệu Nước Hoa</h2>
        <p class="text-muted">Khám phá các thương hiệu nước hoa nổi tiếng thế giới</p>
    </div>
    
    <?php if (empty($brands)): ?>
    <div class="text-center py-5">
        <i class="fas fa-crown fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Chưa có thương hiệu nào</h5>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($brands as $brand): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <a href="detail.php?id=<?php echo $brand['id']; ?>" class="text-decoration-none">
                <div class="brand-card card h-100 border-0 shadow-sm">
                    <div class="brand-image position-relative overflow-hidden">
                        <?php if (!empty($brand['logo'])): ?>
                        <img src="<?php echo htmlspecialchars($brand['logo']); ?>" 
                             class="card-img-top p-4" alt="<?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>"
                             style="height: 200px; object-fit: contain; background: #f8f9fa;"
                             onerror="this.src='<?php echo ASSETS_URL; ?>images/brand-placeholder.jpg'">
                        <?php else: ?>
                        <div class="p-5 text-center bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                            <h3 class="mb-0 text-muted"><?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></h3>
                        </div>
                        <?php endif; ?>
                        <div class="brand-overlay">
                            <span class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Xem sản phẩm
                            </span>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="brand-name mb-2"><?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></h5>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-box me-1"></i><?php echo $brand['product_count']; ?> sản phẩm
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($pagination['total_pages'] > 1): ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <?php if ($pagination['current_page'] > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
            <li class="page-item <?php echo ($i === $pagination['current_page']) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            
            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.brand-card {
    transition: all 0.3s ease;
}

.brand-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.brand-image {
    position: relative;
}

.brand-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.brand-card:hover .brand-overlay {
    opacity: 1;
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>
