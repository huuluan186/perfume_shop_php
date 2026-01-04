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

$page_title = "Chi tiết danh mục";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-eye me-2"></i>Chi tiết danh mục #<?php echo $category['id']; ?></h2>
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
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin danh mục</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>ID:</strong>
                        </div>
                        <div class="col-md-9">
                            #<?php echo $category['id']; ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Tên danh mục:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php echo htmlspecialchars($category['ten_danh_muc']); ?>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Mô tả:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php if (!empty($category['mo_ta'])): ?>
                                <div class="text-muted"><?php echo nl2br(htmlspecialchars($category['mo_ta'])); ?></div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa có mô tả</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
