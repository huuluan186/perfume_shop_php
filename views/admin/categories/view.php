<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Category.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$category_id = intval($_GET['id'] ?? 0);
if ($category_id <= 0) {
    set_message('error', 'Danh mục không hợp lệ!');
    redirect('views/admin/categories/index.php');
}

$categoryModel = new Category();
$category = $categoryModel->getById($category_id);

if (!$category) {
    set_message('error', 'Không tìm thấy danh mục!');
    redirect('views/admin/categories/index.php');
}

// Lấy danh sách sản phẩm thuộc danh mục
require_once __DIR__ . '/../../../models/Product.php';
$productModel = new Product();
$products = $productModel->getByCategory($category_id);
$productCount = count($products);

$page_title = "Chi tiết danh mục";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-eye me-2"></i>Chi tiết danh mục: <?php echo htmlspecialchars($category['ten_danh_muc']); ?>
            <span class="badge bg-primary ms-2"><?php echo $productCount; ?> sản phẩm</span>
        </h2>
        <div>
            <a href="edit.php?id=<?php echo $category['id']; ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Sửa
            </a>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin danh mục</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <strong>Mã danh mục:</strong>
                        </div>
                        <div class="col-md-10">
                            <span class="badge bg-primary fs-6">#<?php echo $category['id']; ?></span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <strong>Tên danh mục:</strong>
                        </div>
                        <div class="col-md-10">
                            <h5 class="mb-0"><?php echo htmlspecialchars($category['ten_danh_muc']); ?></h5>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <strong>Số sản phẩm:</strong>
                        </div>
                        <div class="col-md-10">
                            <span class="badge bg-success fs-6"><?php echo $productCount; ?> sản phẩm</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <strong>Mô tả:</strong>
                        </div>
                        <div class="col-md-10">
                            <?php if (!empty($category['mo_ta'])): ?>
                                <div class="text-muted"><?php echo nl2br(htmlspecialchars($category['mo_ta'])); ?></div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa có mô tả</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Danh sách sản phẩm -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>Danh sách sản phẩm
                        <span class="badge bg-primary ms-2"><?php echo $productCount; ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($products)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p>Chưa có sản phẩm nào trong danh mục này</p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="80" class="text-center">Hình ảnh</th>
                                    <th>Sản phẩm</th>
                                    <th width="150" class="text-end">Giá bán</th>
                                    <th width="100" class="text-center">Tồn kho</th>
                                    <th width="120" class="text-center">Trạng thái</th>
                                    <th width="150" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php 
                                        $image_path = $product['duong_dan_hinh_anh'] ?? '';
                                        if (!empty($image_path)) {
                                            if (strpos($image_path, '/') !== false) {
                                                $full_image_path = ASSETS_URL . urldecode($image_path);
                                            } else {
                                                $full_image_path = UPLOAD_URL . $image_path;
                                            }
                                        } else {
                                            $full_image_path = ASSETS_URL . 'images/placeholder.png';
                                        }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($full_image_path); ?>" 
                                             alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>"
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                                             onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($product['ten_san_pham']); ?></strong>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($product['ten_thuong_hieu'] ?? 'N/A'); ?></small>
                                    </td>
                                    <td class="text-end"><strong class="text-primary"><?php echo format_currency($product['gia_ban']); ?></strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo $product['so_luong_ton'] > 0 ? 'success' : 'danger'; ?>">
                                            <?php echo $product['so_luong_ton']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($product['ngay_xoa'])): ?>
                                            <span class="badge bg-danger">Đã xóa</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Đang bán</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="../products/view.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-info" title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="../products/edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
