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

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_danh_muc = clean_input($_POST['ten_danh_muc'] ?? '');
    $mo_ta = clean_input($_POST['mo_ta'] ?? '');
    $hinh_anh = clean_input($_POST['hinh_anh'] ?? '');
    
    if (empty($ten_danh_muc)) $errors[] = 'Vui lòng nhập tên danh mục!';
    
    if (empty($errors)) {
        $data = [
            'ten_danh_muc' => $ten_danh_muc,
            'mo_ta' => $mo_ta,
            'hinh_anh' => $hinh_anh
        ];
        
        $result = $categoryModel->update($category_id, $data);
        if ($result) {
            set_message('success', 'Cập nhật danh mục thành công!');
            redirect('views/admin/categories/index.php');
        } else {
            $errors[] = 'Cập nhật danh mục thất bại!';
        }
    }
}

$page_title = "Sửa danh mục";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-edit me-2"></i>Sửa danh mục</h2>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
    
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_danh_muc" 
                                   value="<?php echo htmlspecialchars($category['ten_danh_muc']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">URL Hình ảnh</label>
                            <input type="url" class="form-control" name="hinh_anh" 
                                   value="<?php echo htmlspecialchars($category['hinh_anh'] ?? ''); ?>">
                            <small class="text-muted">Nhập link URL của hình ảnh danh mục</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="mo_ta" rows="5"><?php echo htmlspecialchars($category['mo_ta'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <?php if (!empty($category['hinh_anh'])): ?>
                    <div class="col-md-6">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        <div class="border rounded p-3 text-center">
                            <img src="<?php echo htmlspecialchars($category['hinh_anh']); ?>" 
                                 class="img-fluid rounded" style="max-height: 200px;"
                                 onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.jpg'">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <hr>
                
                <div class="text-end">
                    <a href="index.php" class="btn btn-secondary px-4">Hủy</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
