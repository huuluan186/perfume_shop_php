<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../models/Brand.php';

$productModel = new Product();
$categoryModel = new Category();
$brandModel = new Brand();

// Lấy tham số filter
$filters = [
    'category_id' => $_GET['category'] ?? null,
    'brand_id' => $_GET['brand'] ?? null,
    'gender' => $_GET['gender'] ?? null,
    'search' => $_GET['search'] ?? null,
    'min_price' => $_GET['min_price'] ?? null,
    'max_price' => $_GET['max_price'] ?? null,
    'sort' => $_GET['sort'] ?? 'newest'
];

// Phân trang
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = PRODUCTS_PER_PAGE;
$offset = ($page - 1) * $limit;

// Lấy sản phẩm
$products = $productModel->getAll($filters, $limit, $offset);
$total_products = $productModel->count($filters);
$pagination = paginate($total_products, $page, $limit);

// Lấy danh mục và thương hiệu
$categories = $categoryModel->getAll();
$brands = $brandModel->getAll();

$page_title = "Danh sách sản phẩm";
include __DIR__ . '/../layout/header.php';
?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <div class="filter-group">
                    <h6><i class="fas fa-filter me-2"></i>Bộ Lọc</h6>
                    <form method="GET" id="filterForm">
                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="form-label">Danh mục</label>
                            <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Tất cả danh mục</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                        <?php echo ($filters['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['ten_danh_muc']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Brand Filter -->
                        <div class="mb-3">
                            <label class="form-label">Thương hiệu</label>
                            <select name="brand" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Tất cả thương hiệu</option>
                                <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo $brand['id']; ?>" 
                                        <?php echo ($filters['brand_id'] == $brand['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Gender Filter -->
                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <select name="gender" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                <option value="nam" <?php echo ($filters['gender'] === 'nam') ? 'selected' : ''; ?>>Nam</option>
                                <option value="nữ" <?php echo ($filters['gender'] === 'nữ') ? 'selected' : ''; ?>>Nữ</option>
                                <option value="unisex" <?php echo ($filters['gender'] === 'unisex') ? 'selected' : ''; ?>>Unisex</option>
                            </select>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Khoảng giá (vnđ)</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control form-control-sm" 
                                           placeholder="Từ" value="<?php echo $filters['min_price'] ?? ''; ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control form-control-sm" 
                                           placeholder="Đến" value="<?php echo $filters['max_price'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-search me-2"></i>Tìm kiếm
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                            <i class="fas fa-redo me-2"></i>Đặt lại
                        </a>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Toolbar -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    Sản phẩm 
                    <span class="badge bg-primary"><?php echo $total_products; ?></span>
                </h4>
                <div class="d-flex align-items-center">
                    <label class="me-2 mb-0">Sắp xếp:</label>
                    <select name="sort" class="form-select form-select-sm" style="width: 200px;" onchange="this.form.submit()" form="filterForm">
                        <option value="newest" <?php echo ($filters['sort'] === 'newest') ? 'selected' : ''; ?>>Mới nhất</option>
                        <option value="price_asc" <?php echo ($filters['sort'] === 'price_asc') ? 'selected' : ''; ?>>Giá tăng dần</option>
                        <option value="price_desc" <?php echo ($filters['sort'] === 'price_desc') ? 'selected' : ''; ?>>Giá giảm dần</option>
                        <option value="name_asc" <?php echo ($filters['sort'] === 'name_asc') ? 'selected' : ''; ?>>Tên A-Z</option>
                        <option value="name_desc" <?php echo ($filters['sort'] === 'name_desc') ? 'selected' : ''; ?>>Tên Z-A</option>
                    </select>
                </div>
            </div>
            
            <!-- Products List -->
            <?php if (empty($products)): ?>
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Không tìm thấy sản phẩm nào</h5>
            </div>
            <?php else: ?>
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="product-card card h-100 border-0 shadow-sm">
                        <div class="product-image position-relative overflow-hidden">
                            <?php 
                            // Check old vs new image path
                            $img_url = '';
                            if (!empty($product['duong_dan_hinh_anh'])) {
                                if (strpos($product['duong_dan_hinh_anh'], '/') !== false) {
                                    $img_url = ASSETS_URL . urldecode($product['duong_dan_hinh_anh']);
                                } else {
                                    $img_url = UPLOAD_URL . $product['duong_dan_hinh_anh'];
                                }
                            } else {
                                $img_url = ASSETS_URL . 'images/placeholder.png';
                            }
                            ?>
                            <img src="<?php echo $img_url; ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>"
                                 onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                            <?php if ($product['so_luong_ton'] <= 0): ?>
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-danger">Hết hàng</span>
                            </div>
                            <?php endif; ?>
                            <div class="product-overlay">
                                <a href="detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="product-brand text-muted small"><?php echo htmlspecialchars($product['ten_thuong_hieu']); ?></h6>
                            <h5 class="product-name mb-2">
                                <a href="detail.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
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
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="fas fa-times-circle"></i> Hết hàng
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
                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>&<?php echo http_build_query($filters); ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <li class="page-item <?php echo ($i === $pagination['current_page']) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&<?php echo http_build_query($filters); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>&<?php echo http_build_query($filters); ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
