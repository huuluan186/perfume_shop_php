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

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_thuong_hieu = clean_input($_POST['ten_thuong_hieu'] ?? '');
    $quoc_gia = clean_input($_POST['quoc_gia'] ?? '');
    $mo_ta = clean_input($_POST['mo_ta'] ?? '');
    $logo = clean_input($_POST['logo'] ?? '');
    
    if (empty($ten_thuong_hieu)) $errors[] = 'Vui lòng nhập tên thương hiệu!';
    
    if (empty($errors)) {
        // Handle NULL for optional fields
        $quoc_gia = !empty($quoc_gia) ? $quoc_gia : null;
        $mo_ta = !empty($mo_ta) ? $mo_ta : null;
        $logo = !empty($logo) ? $logo : null;
        
        $data = [
            'ten_thuong_hieu' => $ten_thuong_hieu,
            'quoc_gia' => $quoc_gia,
            'mo_ta' => $mo_ta,
            'duong_dan_logo' => $logo
        ];
        
        $result = $brandModel->update($brand_id, $data);
        if ($result) {
            set_message('success', 'Cập nhật thương hiệu thành công!');
            redirect('views/admin/brands/index.php');
        } else {
            $errors[] = 'Cập nhật thương hiệu thất bại!';
        }
    }
}

$page_title = "Sửa thương hiệu";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-edit me-2"></i>Sửa thương hiệu</h2>
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
                            <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_thuong_hieu" 
                                   value="<?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Quốc gia</label>
                            <input type="text" class="form-control" name="quoc_gia" 
                                   value="<?php echo htmlspecialchars($brand['quoc_gia'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">URL Logo</label>
                            <input type="url" class="form-control" name="logo" 
                                   value="<?php echo htmlspecialchars($brand['logo'] ?? ''); ?>">
                            <small class="text-muted">Nhập link URL của logo thương hiệu</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="mo_ta" rows="5"><?php echo htmlspecialchars($brand['mo_ta'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <?php if (!empty($brand['logo'])): ?>
                    <div class="col-md-6">
                        <label class="form-label">Logo hiện tại</label>
                        <div class="border rounded p-3 text-center">
                            <img src="<?php echo htmlspecialchars($brand['logo']); ?>" 
                                 class="img-fluid" style="max-height: 200px;"
                                 onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
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
