<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Brand.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$brand_id = intval($_GET['id'] ?? 0);
if ($brand_id <= 0) {
    set_message('error', 'Thương hiệu không hợp lệ!');
    redirect('views/admin/brands/index.php');
}

$brandModel = new Brand();
$brand = $brandModel->getByIdWithDeleted($brand_id);

if (!$brand) {
    set_message('error', 'Không tìm thấy thương hiệu!');
    redirect('views/admin/brands/index.php');
}

$page_title = "Chi tiết thương hiệu";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <?php if ($brand['ngay_xoa']): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Cảnh báo:</strong> Thương hiệu này đã bị xóa vào <?php echo date('d/m/Y H:i', strtotime($brand['ngay_xoa'])); ?>
    </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-eye me-2"></i>Chi tiết thương hiệu #<?php echo $brand['id']; ?>
            <?php if ($brand['ngay_xoa']): ?>
                <span class="badge bg-danger ms-2">Đã xóa</span>
            <?php endif; ?>
        </h2>
        <div>
            <?php if (!$brand['ngay_xoa']): ?>
            <a href="edit.php?id=<?php echo $brand['id']; ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Sửa
            </a>
            <?php endif; ?>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin thương hiệu</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>ID:</strong>
                        </div>
                        <div class="col-md-9">
                            #<?php echo $brand['id']; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Tên thương hiệu:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Quốc gia:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php echo htmlspecialchars(ucfirst($brand['quoc_gia'] ?? 'Chưa rõ')); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Mô tả:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php if (!empty($brand['mo_ta'])): ?>
                                <div class="text-muted"><?php echo nl2br(htmlspecialchars($brand['mo_ta'])); ?></div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa có mô tả</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($brand['ngay_xoa']): ?>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Ngày xóa:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="text-danger"><?php echo date('d/m/Y H:i:s', strtotime($brand['ngay_xoa'])); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <?php if (!empty($brand['duong_dan_logo'])): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-image me-2"></i>Logo thương hiệu</h5>
                </div>
                <div class="card-body text-center">
                    <img src="<?php echo htmlspecialchars($brand['duong_dan_logo']); ?>" 
                         class="img-fluid rounded" style="max-height: 200px;"
                         onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
