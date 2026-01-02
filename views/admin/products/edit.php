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

$product = $productModel->getById($product_id);
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
    $danh_muc_id = intval($_POST['danh_muc_id'] ?? 0);
    $thuong_hieu_id = intval($_POST['thuong_hieu_id'] ?? 0);
    $dung_tich = clean_input($_POST['dung_tich'] ?? '');
    $gia_ban = floatval($_POST['gia_ban'] ?? 0);
    $so_luong_ton = intval($_POST['so_luong_ton'] ?? 0);
    $gioi_tinh = clean_input($_POST['gioi_tinh'] ?? '');
    $mo_ta = clean_input($_POST['mo_ta'] ?? '');
    
    // Validation
    if (empty($ten_san_pham)) $errors[] = 'Vui lòng nhập tên sản phẩm!';
    if ($danh_muc_id <= 0) $errors[] = 'Vui lòng chọn danh mục!';
    if ($thuong_hieu_id <= 0) $errors[] = 'Vui lòng chọn thương hiệu!';
    if (empty($dung_tich)) $errors[] = 'Vui lòng nhập dung tích!';
    if ($gia_ban <= 0) $errors[] = 'Giá bán phải lớn hơn 0!';
    if ($so_luong_ton < 0) $errors[] = 'Số lượng tồn không hợp lệ!';
    if (empty($gioi_tinh)) $errors[] = 'Vui lòng chọn giới tính!';
    
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
            'danh_muc_id' => $danh_muc_id,
            'thuong_hieu_id' => $thuong_hieu_id,
            'dung_tich' => $dung_tich,
            'gia_ban' => $gia_ban,
            'so_luong_ton' => $so_luong_ton,
            'gioi_tinh' => $gioi_tinh,
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
include __DIR__ . '/../../layout/header.php';
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
                                <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select class="form-select" name="danh_muc_id" required>
                                    <option value="">Chọn danh mục</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" 
                                            <?php echo ($product['danh_muc_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['ten_danh_muc']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thương hiệu <span class="text-danger">*</span></label>
                                <select class="form-select" name="thuong_hieu_id" required>
                                    <option value="">Chọn thương hiệu</option>
                                    <?php foreach ($brands as $brand): ?>
                                    <option value="<?php echo $brand['id']; ?>"
                                            <?php echo ($product['thuong_hieu_id'] == $brand['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Dung tích <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="dung_tich" 
                                       value="<?php echo htmlspecialchars($product['dung_tich']); ?>" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="gia_ban" min="0" step="1000"
                                       value="<?php echo $product['gia_ban']; ?>" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Số lượng tồn <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="so_luong_ton" min="0"
                                       value="<?php echo $product['so_luong_ton']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Giới tính <span class="text-danger">*</span></label>
                            <select class="form-select" name="gioi_tinh" required>
                                <option value="">Chọn giới tính</option>
                                <option value="nam" <?php echo ($product['gioi_tinh'] === 'nam') ? 'selected' : ''; ?>>Nam</option>
                                <option value="nữ" <?php echo ($product['gioi_tinh'] === 'nữ') ? 'selected' : ''; ?>>Nữ</option>
                                <option value="unisex" <?php echo ($product['gioi_tinh'] === 'unisex') ? 'selected' : ''; ?>>Unisex</option>
                            </select>
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
                            <img id="imagePreview" src="<?php echo UPLOAD_URL . $product['duong_dan_hinh_anh']; ?>" 
                                 class="img-fluid rounded shadow-sm" style="max-height: 300px;"
                                 onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.jpg'">
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

<?php include __DIR__ . '/../../layout/footer.php'; ?>
