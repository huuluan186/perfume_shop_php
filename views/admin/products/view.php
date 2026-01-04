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

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    set_message('error', 'Sản phẩm không hợp lệ!');
    redirect('views/admin/products/index.php');
}

$productModel = new Product();
// Admin có thể xem cả sản phẩm đã xóa
$product = $productModel->getByIdWithDeleted($product_id);

if (!$product) {
    set_message('error', 'Không tìm thấy sản phẩm!');
    redirect('views/admin/products/index.php');
}

$page_title = "Chi tiết sản phẩm: " . $product['ten_san_pham'];
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold"><i class="fas fa-eye me-2"></i>Chi tiết sản phẩm</h2>
                <div>
                    <?php if (!$product['ngay_xoa']): ?>
                    <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-2"></i>Sửa sản phẩm
                    </a>
                    <?php endif; ?>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if ($product['ngay_xoa']): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Sản phẩm này đã bị xóa vào ngày <?php echo format_datetime($product['ngay_xoa']); ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-image me-2"></i>Hình ảnh</h5>
                </div>
                <div class="card-body text-center">
                    <?php 
                    $image_url = (strpos($product['duong_dan_hinh_anh'], '/') !== false) 
                        ? ASSETS_URL . urldecode($product['duong_dan_hinh_anh']) 
                        : UPLOAD_URL . $product['duong_dan_hinh_anh'];
                    ?>
                    <img src="<?php echo $image_url; ?>" 
                         class="img-fluid rounded shadow" 
                         style="max-height: 400px;"
                         onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                </div>
            </div>
            
            <!-- Thông tin trạng thái -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Trạng thái</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Trạng thái:</label>
                        <div>
                            <?php if ($product['ngay_xoa']): ?>
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-trash me-1"></i>Đã xóa
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Hoạt động
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Số lượng tồn kho:</label>
                        <div>
                            <?php if ($product['so_luong_ton'] <= 10): ?>
                                <span class="badge bg-danger fs-5"><?php echo $product['so_luong_ton']; ?> sản phẩm</span>
                            <?php else: ?>
                                <span class="badge bg-success fs-5"><?php echo $product['so_luong_ton']; ?> sản phẩm</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($product['ngay_xoa']): ?>
                    <div>
                        <label class="text-muted small">Ngày xóa:</label>
                        <div class="text-danger"><strong><?php echo date('d/m/Y H:i', strtotime($product['ngay_xoa'])); ?></strong></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <div class="col-md-8">
            <!-- Thông tin cơ bản -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Mã sản phẩm:</label>
                            <div><strong class="fs-5 text-primary">#<?php echo $product['id']; ?></strong></div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Tên sản phẩm:</label>
                            <div><strong class="fs-5"><?php echo htmlspecialchars($product['ten_san_pham']); ?></strong></div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Danh mục:</label>
                            <div>
                                <span class="badge bg-info text-dark fs-6">
                                    <i class="fas fa-tags me-1"></i>
                                    <?php echo htmlspecialchars($product['ten_danh_muc']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Thương hiệu:</label>
                            <div>
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="fas fa-copyright me-1"></i>
                                    <?php echo htmlspecialchars($product['ten_thuong_hieu']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Giá bán:</label>
                            <div><strong class="text-danger fs-4"><?php echo format_currency($product['gia_ban']); ?></strong></div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Dung tích:</label>
                            <div>
                                <?php if ($product['dung_tich_ml'] && $product['dung_tich_ml'] > 0): ?>
                                    <strong class="fs-5"><?php echo $product['dung_tich_ml']; ?> ml</strong>
                                <?php else: ?>
                                    <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Giới tính:</label>
                            <div>
                                <?php if ($product['gioi_tinh_phu_hop'] && $product['gioi_tinh_phu_hop'] !== '0'): ?>
                                    <span class="badge bg-secondary fs-6">
                                        <?php echo ucfirst($product['gioi_tinh_phu_hop']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin bổ sung -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-info me-2"></i>Thông tin bổ sung</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Nhóm hương:</label>
                            <div>
                                <?php if ($product['nhom_huong'] && $product['nhom_huong'] !== '0'): ?>
                                    <strong><?php echo htmlspecialchars($product['nhom_huong']); ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Phong cách:</label>
                            <div>
                                <?php if ($product['phong_cach'] && $product['phong_cach'] !== '0'): ?>
                                    <strong><?php echo htmlspecialchars($product['phong_cach']); ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Xuất xứ:</label>
                            <div>
                                <?php if ($product['xuat_xu'] && $product['xuat_xu'] !== '0'): ?>
                                    <strong><?php echo htmlspecialchars($product['xuat_xu']); ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Năm phát hành:</label>
                            <div>
                                <?php if ($product['nam_phat_hanh'] && $product['nam_phat_hanh'] > 0): ?>
                                    <strong><?php echo $product['nam_phat_hanh']; ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mô tả sản phẩm -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-align-left me-2"></i>Mô tả sản phẩm</h5>
                </div>
                <div class="card-body">
                    <?php if ($product['mo_ta']): ?>
                        <div class="product-description">
                            <?php echo nl2br(htmlspecialchars($product['mo_ta'])); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Chưa có mô tả cho sản phẩm này.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-description {
    line-height: 1.8;
    font-size: 0.95rem;
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>
