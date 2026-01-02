<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Brand.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$brandModel = new Brand();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_thuong_hieu = clean_input($_POST['ten_thuong_hieu'] ?? '');
    $quoc_gia = clean_input($_POST['quoc_gia'] ?? '');
    $mo_ta = clean_input($_POST['mo_ta'] ?? '');
    $logo = clean_input($_POST['logo'] ?? '');
    
    if (empty($ten_thuong_hieu)) $errors[] = 'Vui lòng nhập tên thương hiệu!';
    
    if (empty($errors)) {
        $data = [
            'ten_thuong_hieu' => $ten_thuong_hieu,
            'quoc_gia' => $quoc_gia,
            'mo_ta' => $mo_ta,
            'duong_dan_logo' => $logo
        ];
        
        $brand_id = $brandModel->create($data);
        if ($brand_id) {
            set_message('success', 'Thêm thương hiệu thành công!');
            redirect('views/admin/brands/index.php');
        } else {
            $errors[] = 'Thêm thương hiệu thất bại!';
        }
    }
}

$page_title = "Thêm thương hiệu";
include __DIR__ . '/../../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-plus me-2"></i>Thêm thương hiệu</h2>
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
                                   value="<?php echo htmlspecialchars($_POST['ten_thuong_hieu'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Quốc gia</label>
                            <input type="text" class="form-control" name="quoc_gia" 
                                   placeholder="VD: Pháp, Ý, Mỹ..."
                                   value="<?php echo htmlspecialchars($_POST['quoc_gia'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">URL Logo</label>
                            <input type="url" class="form-control" name="logo" 
                                   placeholder="https://example.com/logo.png"
                                   value="<?php echo htmlspecialchars($_POST['logo'] ?? ''); ?>">
                            <small class="text-muted">Nhập link URL của logo thương hiệu</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="mo_ta" rows="5"><?php echo htmlspecialchars($_POST['mo_ta'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-end">
                    <a href="index.php" class="btn btn-secondary px-4">Hủy</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layout/footer.php'; ?>
