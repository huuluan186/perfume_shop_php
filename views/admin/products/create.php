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

$categories = $categoryModel->getAll();
$brands = $brandModel->getAll();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_san_pham = clean_input($_POST['ten_san_pham'] ?? '');
    $danh_muc_id = !empty($_POST['danh_muc_id']) ? intval($_POST['danh_muc_id']) : null;
    $thuong_hieu_id = !empty($_POST['thuong_hieu_id']) ? intval($_POST['thuong_hieu_id']) : null;
    $dung_tich = !empty($_POST['dung_tich']) ? intval($_POST['dung_tich']) : null;
    $gia_ban = floatval($_POST['gia_ban'] ?? 0);
    $so_luong_ton = isset($_POST['so_luong_ton']) && $_POST['so_luong_ton'] !== '' ? intval($_POST['so_luong_ton']) : 0;
    
    // Xử lý các trường text - kiểm tra trước khi clean
    $gioi_tinh = (isset($_POST['gioi_tinh']) && $_POST['gioi_tinh'] !== '') ? clean_input($_POST['gioi_tinh']) : null;
    $nhom_huong = (isset($_POST['nhom_huong']) && trim($_POST['nhom_huong']) !== '') ? clean_input($_POST['nhom_huong']) : null;
    $phong_cach = (isset($_POST['phong_cach']) && trim($_POST['phong_cach']) !== '') ? clean_input($_POST['phong_cach']) : null;
    $xuat_xu = (isset($_POST['xuat_xu']) && trim($_POST['xuat_xu']) !== '') ? clean_input($_POST['xuat_xu']) : null;
    $nam_phat_hanh = (isset($_POST['nam_phat_hanh']) && $_POST['nam_phat_hanh'] !== '' && $_POST['nam_phat_hanh'] > 0) ? intval($_POST['nam_phat_hanh']) : null;
    $mo_ta = (isset($_POST['mo_ta']) && trim($_POST['mo_ta']) !== '') ? clean_input($_POST['mo_ta']) : null;
    
    // Validation - chỉ validate tên, giá và dung tích (required trường)
    if (empty($ten_san_pham)) $errors[] = 'Vui lòng nhập tên sản phẩm!';
    if ($gia_ban <= 0) $errors[] = 'Giá bán phải lớn hơn 0!';
    if (!$dung_tich || $dung_tich <= 0) $errors[] = 'Vui lòng nhập dung tích!';
    if ($so_luong_ton < 0) $errors[] = 'Số lượng tồn không hợp lệ!';
    
    // Upload image
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_result = upload_product_image($_FILES['image']);
        if ($upload_result['success']) {
            $image_path = $upload_result['path'];
        } else {
            $errors[] = $upload_result['message'];
        }
    }
    // Không bắt buộc phải có ảnh vì trong DB là DEFAULT NULL
    
    if (empty($errors)) {
        $data = [
            'ten_san_pham' => $ten_san_pham,
            'id_danh_muc' => $danh_muc_id,
            'id_thuong_hieu' => $thuong_hieu_id,
            'dung_tich_ml' => $dung_tich,
            'gia_ban' => $gia_ban,
            'so_luong_ton' => $so_luong_ton,
            'gioi_tinh_phu_hop' => $gioi_tinh,
            'nhom_huong' => $nhom_huong,
            'phong_cach' => $phong_cach,
            'xuat_xu' => $xuat_xu,
            'nam_phat_hanh' => $nam_phat_hanh,
            'mo_ta' => $mo_ta,
            'duong_dan_hinh_anh' => $image_path ?: null
        ];
        
        $product_id = $productModel->create($data);
        if ($product_id) {
            set_message('success', 'Thêm sản phẩm thành công!');
            redirect('views/admin/products/index.php');
        } else {
            $errors[] = 'Thêm sản phẩm thất bại!';
        }
    }
}

$page_title = "Thêm sản phẩm mới";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold"><i class="fas fa-plus me-2"></i>Thêm sản phẩm mới</h2>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
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
            <form method="POST" enctype="multipart/form-data" id="productForm" onsubmit="return validateForm('productForm')">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_san_pham" 
                                   value="<?php echo htmlspecialchars($_POST['ten_san_pham'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Danh mục</label>
                                <select class="form-select" name="danh_muc_id">
                                    <option value="">Chọn danh mục</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" 
                                            <?php echo (isset($_POST['danh_muc_id']) && $_POST['danh_muc_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['ten_danh_muc']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thương hiệu</label>
                                <select class="form-select" name="thuong_hieu_id">
                                    <option value="">Chọn thương hiệu</option>
                                    <?php foreach ($brands as $brand): ?>
                                    <option value="<?php echo $brand['id']; ?>"
                                            <?php echo (isset($_POST['thuong_hieu_id']) && $_POST['thuong_hieu_id'] == $brand['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Dung tích (ml) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="dung_tich" min="1" required
                                       placeholder="VD: 100" value="<?php echo htmlspecialchars($_POST['dung_tich'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="gia_ban" min="0" step="1000" max="999999999"
                                       value="<?php echo htmlspecialchars($_POST['gia_ban'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Số lượng tồn</label>
                                <input type="number" class="form-control" name="so_luong_ton" min="0"
                                       value="<?php echo htmlspecialchars($_POST['so_luong_ton'] ?? '0'); ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <select class="form-select" name="gioi_tinh">
                                <option value="">Chọn giới tính</option>
                                <option value="nam" <?php echo (isset($_POST['gioi_tinh']) && $_POST['gioi_tinh'] === 'nam') ? 'selected' : ''; ?>>Nam</option>
                                <option value="nữ" <?php echo (isset($_POST['gioi_tinh']) && $_POST['gioi_tinh'] === 'nữ') ? 'selected' : ''; ?>>Nữ</option>
                                <option value="unisex" <?php echo (isset($_POST['gioi_tinh']) && $_POST['gioi_tinh'] === 'unisex') ? 'selected' : ''; ?>>Unisex</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nhóm hương</label>
                            <input type="text" class="form-control" name="nhom_huong" 
                                   placeholder="VD: Hương gỗ, Hương hoa cỏ..."
                                   value="<?php echo htmlspecialchars($_POST['nhom_huong'] ?? ''); ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phong cách</label>
                                <input type="text" class="form-control" name="phong_cach" 
                                       placeholder="VD: Sang trọng, Năng động..."
                                       value="<?php echo htmlspecialchars($_POST['phong_cach'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Xuất xứ</label>
                                <input type="text" class="form-control" name="xuat_xu" 
                                       placeholder="VD: Pháp, Ý..."
                                       value="<?php echo htmlspecialchars($_POST['xuat_xu'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Năm phát hành</label>
                            <input type="number" class="form-control" name="nam_phat_hanh" 
                                   min="1900" max="2100" placeholder="VD: 2024"
                                   value="<?php echo htmlspecialchars($_POST['nam_phat_hanh'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="mo_ta" rows="5"><?php echo htmlspecialchars($_POST['mo_ta'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh sản phẩm</label>
                            <input type="file" class="form-control" name="image" accept="image/*" 
                                   onchange="previewImage(this)">
                            <small class="text-muted">Chấp nhận: JPG, PNG, GIF (Max: 5MB)</small>
                        </div>
                        <div class="text-center">
                            <img id="imagePreview" src="" class="img-fluid rounded shadow-sm" 
                                 style="max-height: 300px; display: none;">
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-end">
                    <a href="index.php" class="btn btn-secondary px-4">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Lưu sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Validation form
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        let errorMsg = [];
        
        // VALIDATE CÁC TRƯỜNG BẮT BUỘC
        
        // 1. Tên sản phẩm - NOT NULL trong DB
        const tenSanPham = form.querySelector('[name="ten_san_pham"]');
        if (!tenSanPham.value.trim()) {
            errorMsg.push('Tên sản phẩm không được để trống!');
            tenSanPham.classList.add('is-invalid');
            isValid = false;
        } else {
            tenSanPham.classList.remove('is-invalid');
        }
        
        // 2. Giá bán - NOT NULL trong DB
        const giaBan = form.querySelector('[name="gia_ban"]');
        if (!giaBan.value || parseFloat(giaBan.value) <= 0) {
            errorMsg.push('Giá bán phải lớn hơn 0!');
            giaBan.classList.add('is-invalid');
            isValid = false;
        } else {
            giaBan.classList.remove('is-invalid');
        }
        
        // 3. Dung tích - Bắt buộc
        const dungTich = form.querySelector('[name="dung_tich"]');
        if (!dungTich.value || parseInt(dungTich.value) <= 0) {
            errorMsg.push('Dung tích phải lớn hơn 0!');
            dungTich.classList.add('is-invalid');
            isValid = false;
        } else {
            dungTich.classList.remove('is-invalid');
        }
        
        // VALIDATE CÁC TRƯỜNG TÙY CHỌN - CHỈ KIỂM TRA KIỂU DỮ LIỆU NẾU CÓ NHẬP
        
        // 4. Số lượng tồn - Nếu nhập thì phải là số >= 0
        const soLuong = form.querySelector('[name="so_luong_ton"]');
        if (soLuong.value !== '' && (isNaN(parseInt(soLuong.value)) || parseInt(soLuong.value) < 0)) {
            errorMsg.push('Số lượng tồn phải là số không âm!');
            soLuong.classList.add('is-invalid');
            isValid = false;
        } else {
            soLuong.classList.remove('is-invalid');
        }
        
        // 5. Hình ảnh - Nếu chọn thì kiểm tra file size và type
        const image = form.querySelector('[name="image"]');
        if (image.files && image.files.length > 0) {
            const file = image.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            
            if (file.size > maxSize) {
                errorMsg.push('Kích thước ảnh không được vượt quá 5MB!');
                image.classList.add('is-invalid');
                isValid = false;
            } else if (!allowedTypes.includes(file.type)) {
                errorMsg.push('Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)!');
                image.classList.add('is-invalid');
                isValid = false;
            } else {
                image.classList.remove('is-invalid');
            }
        } else {
            image.classList.remove('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng kiểm tra lại:\n\n' + errorMsg.join('\n'));
            return false;
        }
        
        return true;
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
