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

$product_id = intval($_GET['id'] ?? 0);
if ($product_id <= 0) {
    set_message('error', 'Sản phẩm không hợp lệ!');
    redirect('views/admin/products/index.php');
}

$productModel = new Product();
$categoryModel = new Category();
$brandModel = new Brand();

$product = $productModel->getByIdWithDeleted($product_id);
if (!$product) {
    set_message('error', 'Không tìm thấy sản phẩm!');
    redirect('views/admin/products/index.php');
}

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
    
    // Validation - chỉ validate tên và giá (NOT NULL trong DB)
    if (empty($ten_san_pham)) $errors[] = 'Vui lòng nhập tên sản phẩm!';
    if ($gia_ban <= 0) $errors[] = 'Giá bán phải lớn hơn 0!';
    if ($so_luong_ton < 0) $errors[] = 'Số lượng tồn không hợp lệ!';
    
    // Upload image if new file selected
    $image_path = $product['duong_dan_hinh_anh'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_result = upload_product_image($_FILES['image']);
        if ($upload_result['success']) {
            $image_path = $upload_result['path'];
        } else {
            $errors[] = $upload_result['message'];
        }
    }
    
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
            'duong_dan_hinh_anh' => $image_path
        ];
        
        $result = $productModel->update($product_id, $data);
        if ($result) {
            set_message('success', 'Cập nhật sản phẩm thành công!');
            redirect('views/admin/products/index.php');
        } else {
            $errors[] = 'Cập nhật sản phẩm thất bại!';
        }
    }
}

$page_title = "Sửa sản phẩm";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold"><i class="fas fa-edit me-2"></i>Sửa sản phẩm</h2>
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
                                   value="<?php echo htmlspecialchars($product['ten_san_pham']); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Danh mục</label>
                                <select class="form-select" name="danh_muc_id">
                                    <option value="">Chọn danh mục</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" 
                                            <?php echo ($product['id_danh_muc'] == $cat['id']) ? 'selected' : ''; ?>>
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
                                            <?php echo ($product['id_thuong_hieu'] == $brand['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Dung tích (ml)</label>
                                <input type="number" class="form-control" name="dung_tich" min="1"
                                       value="<?php echo htmlspecialchars($product['dung_tich_ml'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="gia_ban" min="0" step="1000" max="999999999"
                                       value="<?php echo $product['gia_ban']; ?>" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Số lượng tồn</label>
                                <input type="number" class="form-control" name="so_luong_ton" min="0"
                                       value="<?php echo $product['so_luong_ton']; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Giới tính</label>
                            <select class="form-select" name="gioi_tinh">
                                <option value="">Chọn giới tính</option>
                                <option value="nam" <?php echo (($product['gioi_tinh_phu_hop'] ?? '') === 'nam') ? 'selected' : ''; ?>>Nam</option>
                                <option value="nữ" <?php echo (($product['gioi_tinh_phu_hop'] ?? '') === 'nữ') ? 'selected' : ''; ?>>Nữ</option>
                                <option value="unisex" <?php echo (($product['gioi_tinh_phu_hop'] ?? '') === 'unisex') ? 'selected' : ''; ?>>Unisex</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nhóm hương</label>
                            <input type="text" class="form-control" name="nhom_huong" 
                                   placeholder="VD: Hương gỗ, Hương hoa cỏ..."
                                   value="<?php echo htmlspecialchars($product['nhom_huong'] ?? ''); ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phong cách</label>
                                <input type="text" class="form-control" name="phong_cach" 
                                       placeholder="VD: Sang trọng, Năng động..."
                                       value="<?php echo htmlspecialchars($product['phong_cach'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Xuất xứ</label>
                                <input type="text" class="form-control" name="xuat_xu" 
                                       placeholder="VD: Pháp, Ý..."
                                       value="<?php echo htmlspecialchars($product['xuat_xu'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Năm phát hành</label>
                            <input type="number" class="form-control" name="nam_phat_hanh" 
                                   min="1900" max="2100" placeholder="VD: 2024"
                                   value="<?php echo htmlspecialchars($product['nam_phat_hanh'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="mo_ta" rows="5"><?php echo htmlspecialchars($product['mo_ta'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh sản phẩm</label>
                            <input type="file" class="form-control" name="image" accept="image/*" 
                                   onchange="previewImage(this)">
                            <small class="text-muted">Để trống nếu không thay đổi</small>
                        </div>
                        <div class="text-center">
                            <?php 
                            // Check old vs new image path
                            $current_image_url = '';
                            if (!empty($product['duong_dan_hinh_anh'])) {
                                if (strpos($product['duong_dan_hinh_anh'], '/') !== false) {
                                    // Old path format: products/Brand/Product/image.jpg
                                    $current_image_url = ASSETS_URL . urldecode($product['duong_dan_hinh_anh']);
                                } else {
                                    // New path format: product_20240101_abc123.jpg (in uploads/)
                                    $current_image_url = UPLOAD_URL . $product['duong_dan_hinh_anh'];
                                }
                            }
                            ?>
                            <img id="imagePreview" 
                                 src="<?php echo $current_image_url ?: (ASSETS_URL . 'images/placeholder.png'); ?>" 
                                 class="img-fluid rounded shadow-sm" style="max-height: 300px;"
                                 onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-end">
                    <a href="index.php" class="btn btn-secondary px-4">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Cập nhật
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
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
