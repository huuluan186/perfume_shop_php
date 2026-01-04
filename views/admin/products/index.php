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

// Filters
$filters = [
    'category_id' => $_GET['category'] ?? null,
    'brand_id' => $_GET['brand'] ?? null,
    'search' => $_GET['search'] ?? null
];

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = PRODUCTS_PER_PAGE;
$offset = ($page - 1) * $limit;

$products = $productModel->getAllForAdmin($filters, $limit, $offset);
$total_products = $productModel->countForAdmin($filters);
$pagination = paginate($total_products, $page, $limit);

$categories = $categoryModel->getAll();
$brands = $brandModel->getAll();

$page_title = "Quản lý sản phẩm";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-box me-2"></i>Quản lý sản phẩm</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
        </a>
    </div>
    
    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($filters['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['ten_danh_muc']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="brand" class="form-select">
                        <option value="">Tất cả thương hiệu</option>
                        <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo $brand['id']; ?>" <?php echo ($filters['brand_id'] == $brand['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($products)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-box-open fa-3x mb-3"></i>
                <p>Chưa có sản phẩm nào</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-sticky-action">
                    <thead class="table-light">
                        <tr>
                            <th width="80">ID</th>
                            <th width="100">Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Giá bán</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr <?php echo $product['ngay_xoa'] ? 'class="table-secondary" style="opacity: 0.6;"' : ''; ?>>
                            <td><strong>#<?php echo $product['id']; ?></strong></td>
                            <td>
                                <?php 
                                // Kiểm tra xem đường dẫn có phải là ảnh cũ (có chứa thư mục) hay ảnh mới
                                if (!empty($product['duong_dan_hinh_anh'])) {
                                    $image_url = (strpos($product['duong_dan_hinh_anh'], '/') !== false) 
                                        ? ASSETS_URL . urldecode($product['duong_dan_hinh_anh']) 
                                        : UPLOAD_URL . $product['duong_dan_hinh_anh'];
                                } else {
                                    $image_url = ASSETS_URL . 'images/placeholder.png';
                                }
                                ?>
                                <img src="<?php echo $image_url; ?>" 
                                     class="rounded" style="width: 60px; height: 60px; object-fit: cover;"
                                     onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                            </td>
                            <td style="max-width: 250px;">
                                <strong><?php echo htmlspecialchars($product['ten_san_pham']); ?></strong><br>
                                <small class="text-muted">
                                    <?php 
                                    $info = [];
                                    if (!empty($product['dung_tich'])) $info[] = htmlspecialchars($product['dung_tich']);
                                    if (!empty($product['gioi_tinh'])) $info[] = ucfirst($product['gioi_tinh']);
                                    echo implode(' | ', $info);
                                    ?>
                                </small>
                            </td>
                            <td class="text-nowrap"><?php echo htmlspecialchars($product['ten_danh_muc']); ?></td>
                            <td class="text-nowrap"><?php echo htmlspecialchars($product['ten_thuong_hieu']); ?></td>
                            <td class="text-nowrap"><strong class="text-primary"><?php echo format_currency($product['gia_ban']); ?></strong></td>
                            <td>
                                <?php if ($product['so_luong_ton'] <= 10): ?>
                                    <span class="badge bg-danger"><?php echo $product['so_luong_ton']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?php echo $product['so_luong_ton']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($product['ngay_xoa']): ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-trash me-1"></i>Đã xóa
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Hoạt động
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-outline-info me-1 view-product" 
                                        data-id="<?php echo $product['id']; ?>" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if (!$product['ngay_xoa']): ?>
                                <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-product" 
                                        data-id="<?php echo $product['id']; ?>" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($pagination['current_page'] > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>&<?php echo http_build_query($filters); ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <li class="page-item <?php echo ($i === $pagination['current_page']) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&<?php echo http_build_query($filters); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>&<?php echo http_build_query($filters); ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
$(document).ready(function() {
    $('.delete-product').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
        
        const productId = $(this).data('id');
        const $button = $(this);
        
        // Disable button to prevent double click
        $button.prop('disabled', true);
        
        $.ajax({
            url: 'delete.php',
            method: 'POST',
            data: { id: productId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                    $button.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.log('Response:', xhr.responseText);
                alert('Có lỗi xảy ra khi xóa sản phẩm!');
                $button.prop('disabled', false);
            }
        });
    });
    
    // Xem chi tiết sản phẩm
    $('.view-product').click(function() {
        const productId = $(this).data('id');
        
        // Hiển thị loading
        $('#productDetailModal').modal('show');
        $('#productDetailContent').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary"></i><p class="mt-3">Đang tải...</p></div>');
        
        // Load thông tin sản phẩm
        $.ajax({
            url: 'get-detail.php?id=' + productId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const p = response.product;
                    const html = `
                        <div class="row">
                            <div class="col-md-5">
                                <div class="text-center mb-3">
                                    <img src="${p.image_url}" class="img-fluid rounded shadow" style="max-height: 400px;" 
                                         onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                                </div>
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Trạng thái</h6>
                                        <div class="mb-2">
                                            <small class="text-muted">Trạng thái:</small>
                                            ${p.ngay_xoa ? '<span class="badge bg-danger ms-2"><i class="fas fa-trash me-1"></i>Đã xóa</span>' : '<span class="badge bg-success ms-2"><i class="fas fa-check-circle me-1"></i>Hoạt động</span>'}
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Tồn kho:</small>
                                            ${p.so_luong_ton <= 10 ? '<span class="badge bg-danger ms-2">' + p.so_luong_ton + ' sản phẩm</span>' : '<span class="badge bg-success ms-2">' + p.so_luong_ton + ' sản phẩm</span>'}
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Ngày tạo:</small>
                                            <div><strong>${formatDateTime(p.ngay_tao)}</strong></div>
                                        </div>
                                        ${p.ngay_cap_nhat ? '<div><small class="text-muted">Cập nhật:</small><div><strong>' + formatDateTime(p.ngay_cap_nhat) + '</strong></div></div>' : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h4 class="text-primary mb-3">${p.ten_san_pham}</h4>
                                
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3"><i class="fas fa-box me-2"></i>Thông tin cơ bản</h6>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <small class="text-muted">Mã sản phẩm:</small>
                                                <div><strong class="text-primary">#${p.id}</strong></div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Giá bán:</small>
                                                <div><strong class="text-danger fs-5">${p.gia_ban_formatted}</strong></div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <small class="text-muted">Danh mục:</small>
                                                <div><span class="badge bg-info text-dark">${p.ten_danh_muc}</span></div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Thương hiệu:</small>
                                                <div><span class="badge bg-warning text-dark">${p.ten_thuong_hieu}</span></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Dung tích:</small>
                                                <div><strong>${p.dung_tich_ml && p.dung_tich_ml > 0 ? p.dung_tich_ml + ' ml' : '<span class="text-muted">Chưa cập nhật</span>'}</strong></div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Giới tính:</small>
                                                <div>${p.gioi_tinh_phu_hop && p.gioi_tinh_phu_hop !== '0' ? '<span class="badge bg-secondary">' + capitalizeFirst(p.gioi_tinh_phu_hop) + '</span>' : '<span class="text-muted">Chưa cập nhật</span>'}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3"><i class="fas fa-info me-2"></i>Thông tin bổ sung</h6>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <small class="text-muted">Nhóm hương:</small>
                                                <div>${p.nhom_huong && p.nhom_huong !== '0' ? p.nhom_huong : '<span class="text-muted">Chưa cập nhật</span>'}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Phong cách:</small>
                                                <div>${p.phong_cach && p.phong_cach !== '0' ? p.phong_cach : '<span class="text-muted">Chưa cập nhật</span>'}</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Xuất xứ:</small>
                                                <div>${p.xuat_xu && p.xuat_xu !== '0' ? p.xuat_xu : '<span class="text-muted">Chưa cập nhật</span>'}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Năm phát hành:</small>
                                                <div>${p.nam_phat_hanh && p.nam_phat_hanh > 0 ? p.nam_phat_hanh : '<span class="text-muted">Chưa cập nhật</span>'}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                ${p.mo_ta ? '<div class="card border-0 bg-light"><div class="card-body"><h6 class="text-primary mb-3"><i class="fas fa-align-left me-2"></i>Mô tả</h6><div style="line-height: 1.8;">' + p.mo_ta.replace(/\n/g, '<br>') + '</div></div></div>' : ''}
                            </div>
                        </div>
                    `;
                    $('#productDetailContent').html(html);
                } else {
                    $('#productDetailContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>' + response.message + '</div>');
                }
            },
            error: function() {
                $('#productDetailContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra khi tải thông tin!</div>');
            }
        });
    });
    
    // Helper functions
    function formatDateTime(datetime) {
        if (!datetime) return '';
        const date = new Date(datetime);
        return date.toLocaleString('vi-VN');
    }
    
    function capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});
</script>

<!-- Modal Chi tiết sản phẩm -->
<div class="modal fade" id="productDetailModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Chi tiết sản phẩm</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="productDetailContent" style="max-height: 70vh; overflow-y: auto;">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Đóng
                </button>
            </div>
        </div>
    </div>
</div>

