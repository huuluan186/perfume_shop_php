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
include __DIR__ . '/../layout/header.php';
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
            <form method="POST" id="brandForm">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_thuong_hieu" 
                                   value="<?php echo htmlspecialchars($_POST['ten_thuong_hieu'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quốc gia</label>
                                <input type="text" class="form-control" name="quoc_gia" 
                                       placeholder="VD: Pháp, Ý, Mỹ..."
                                       value="<?php echo htmlspecialchars($_POST['quoc_gia'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">URL Logo</label>
                                <input type="url" class="form-control" name="logo" 
                                       placeholder="https://example.com/logo.png"
                                       value="<?php echo htmlspecialchars($_POST['logo'] ?? ''); ?>">
                                <small class="text-muted">Nhập link URL của logo</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="mo_ta" rows="5"><?php echo htmlspecialchars($_POST['mo_ta'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-end">
                    <a href="index.php" class="btn btn-secondary px-4">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Lưu thương hiệu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validation form
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('brandForm');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        let errorMsg = [];
        
        // 1. Tên thương hiệu - NOT NULL trong DB
        const tenThuongHieu = form.querySelector('[name="ten_thuong_hieu"]');
        if (!tenThuongHieu.value.trim()) {
            errorMsg.push('Tên thương hiệu không được để trống!');
            tenThuongHieu.classList.add('is-invalid');
            isValid = false;
        } else {
            tenThuongHieu.classList.remove('is-invalid');
        }
        
        // 2. URL Logo - Nếu nhập thì phải đúng format
        const logo = form.querySelector('[name="logo"]');
        if (logo.value && !isValidUrl(logo.value)) {
            errorMsg.push('URL logo không hợp lệ!');
            logo.classList.add('is-invalid');
            isValid = false;
        } else {
            logo.classList.remove('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng kiểm tra lại:\n\n' + errorMsg.join('\n'));
            return false;
        }
        
        return true;
    });
    
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
