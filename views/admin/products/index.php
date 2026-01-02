<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Product.php';
require_once __DIR__ . '/../../../models/Category.php';
require_once __DIR__ . '/../../../models/Brand.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$productModel = new Product();
$categoryModel = new Category();
$brandModel = new Brand();

// Filters
$filters = [
    'category_id' => $_GET['category'] ?? null,
    'brand_id' => $_GET['brand'] ?? null,
    'search' => $_GET['search'] ?? null
];

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = PRODUCTS_PER_PAGE;
$offset = ($page - 1) * $limit;

$products = $productModel->getAll($filters, $limit, $offset);
$total_products = $productModel->count($filters);
$pagination = paginate($total_products, $page, $limit);

$categories = $categoryModel->getAll();
$brands = $brandModel->getAll();

$page_title = "Quản lý sản phẩm";
include __DIR__ . '/../../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-box me-2"></i>Quản lý sản phẩm</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
        </a>
    </div>
    
    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($filters['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['ten_danh_muc']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="brand" class="form-select">
                        <option value="">Tất cả thương hiệu</option>
                        <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo $brand['id']; ?>" <?php echo ($filters['brand_id'] == $brand['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($products)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-box-open fa-3x mb-3"></i>
                <p>Chưa có sản phẩm nào</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="80">ID</th>
                            <th width="100">Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Giá bán</th>
                            <th>Tồn kho</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><strong>#<?php echo $product['id']; ?></strong></td>
                            <td>
                                <img src="<?php echo UPLOAD_URL . $product['duong_dan_hinh_anh']; ?>" 
                                     class="rounded" style="width: 60px; height: 60px; object-fit: cover;"
                                     onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.jpg'">
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($product['ten_san_pham']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($product['dung_tich']); ?> | <?php echo ucfirst($product['gioi_tinh']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($product['ten_danh_muc']); ?></td>
                            <td><?php echo htmlspecialchars($product['ten_thuong_hieu']); ?></td>
                            <td><strong class="text-primary"><?php echo format_currency($product['gia_ban']); ?></strong></td>
                            <td>
                                <?php if ($product['so_luong_ton'] <= 10): ?>
                                    <span class="badge bg-danger"><?php echo $product['so_luong_ton']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?php echo $product['so_luong_ton']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-product" 
                                        data-id="<?php echo $product['id']; ?>" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <nav class="mt-4">
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

<script>
$(document).on('click', '.delete-product', function() {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
    
    const productId = $(this).data('id');
    
    $.ajax({
        url: 'delete.php',
        method: 'POST',
        data: { id: productId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification('success', response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('error', response.message);
            }
        }
    });
});
</script>

<?php include __DIR__ . '/../../layout/footer.php'; ?>
