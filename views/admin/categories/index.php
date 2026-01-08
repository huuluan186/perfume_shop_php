<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Category.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$categoryModel = new Category();
$categories = $categoryModel->getAll();

$page_title = "Quản lý danh mục";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-list me-2"></i>Quản lý danh mục</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm danh mục
        </a>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($categories)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-list fa-3x mb-3"></i>
                <p>Chưa có danh mục nào</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-sticky-action">
                    <thead class="table-light">
                        <tr>
                            <th width="100">ID</th>
                            <th>Tên danh mục</th>
                            <th width="200" class="text-center">Số sản phẩm</th>
                            <th width="250" class="text-center sticky-action">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><strong>#<?php echo $category['id']; ?></strong></td>
                            <td><strong><?php echo htmlspecialchars($category['ten_danh_muc']); ?></strong></td>
                            <td class="text-center">
                                <span class="badge bg-primary"><?php echo $category['product_count'] ?? 0; ?> sản phẩm</span>
                            </td>
                            <td class="text-nowrap text-center">
                                <button class="btn btn-sm btn-outline-info me-1 view-category" 
                                        data-id="<?php echo $category['id']; ?>" title="Xem chi tiết">
                                    <i class="fas fa-eye" style="pointer-events: none;"></i>
                                </button>
                                <a href="edit.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-category" 
                                        data-id="<?php echo $category['id']; ?>" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Chi tiết danh mục -->
<div class="modal fade" id="categoryDetailModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Chi tiết danh mục</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="categoryDetailContent" style="max-height: 70vh; overflow-y: auto;">
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

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
// Lấy ASSETS_URL từ PHP
const ASSETS_URL = '<?php echo ASSETS_URL; ?>';

$(document).ready(function() {
    // Xóa danh mục - sử dụng event delegation
    $(document).on('click', '.delete-category', function(e) {
        e.preventDefault();
        
        if (!confirm('Bạn có chắc muốn xóa danh mục này?')) return;
        
        const categoryId = $(this).data('id');
        const $button = $(this);
        
        // Disable button to prevent double click
        $button.prop('disabled', true);
        
        $.ajax({
            url: 'delete.php',
            method: 'POST',
            data: { id: categoryId },
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
                alert('Có lỗi xảy ra khi xóa danh mục!');
                $button.prop('disabled', false);
            }
        });
    });

    // Xem chi tiết danh mục - sử dụng event delegation
    $(document).on('click', '.view-category', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('View category clicked');
        const categoryId = $(this).data('id');
        console.log('Category ID:', categoryId);
        
        const modalEl = document.getElementById('categoryDetailModal');
        console.log('Modal element:', modalEl);
        
        if (!modalEl) {
            console.error('Modal element not found!');
            alert('Không tìm thấy modal!');
            return;
        }
        
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        console.log('Modal shown');
        
        $('#categoryDetailContent').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary"></i><p class="mt-3">Đang tải...</p></div>');
        
        $.ajax({
            url: 'get-detail.php?id=' + categoryId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('AJAX response:', response);
                if (response.success) {
                    const html = renderCategoryDetail(response.category, response.products, response.product_count);
                    $('#categoryDetailContent').html(html);
                } else {
                    $('#categoryDetailContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>' + response.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                console.log('Response:', xhr.responseText);
                $('#categoryDetailContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra khi tải thông tin!</div>');
            }
        });
    });
});

function renderCategoryDetail(category, products, productCount) {
    // Render danh sách sản phẩm
    let productsHtml = '';
    if (products && products.length > 0) {
        products.forEach(function(p) {
            // Đường dẫn ảnh đã được xử lý từ backend, trả về là full URL
            const imageUrl = p.image_url || ASSETS_URL + 'images/product-placeholder.png';
            const statusBadge = p.trang_thai == 1 
                ? '<span class="badge bg-success">Đang bán</span>' 
                : '<span class="badge bg-danger">Ngừng bán</span>';
            
            productsHtml += `
                <tr>
                    <td class="text-center">
                        <img src="${imageUrl}" 
                             alt="${p.ten_san_pham}" 
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                             onerror="this.onerror=null; this.src='${ASSETS_URL}images/product-placeholder.png';">
                    </td>
                    <td>
                        <strong>${p.ten_san_pham}</strong>
                        <br><small class="text-muted">${p.ten_thuong_hieu}</small>
                    </td>
                    <td class="text-end">${p.gia_ban_formatted}</td>
                    <td class="text-center">${p.so_luong_ton}</td>
                    <td class="text-center">${statusBadge}</td>
                </tr>
            `;
        });
    } else {
        productsHtml = '<tr><td colspan="5" class="text-center text-muted fst-italic py-4">Chưa có sản phẩm nào trong danh mục này</td></tr>';
    }
    
    return `
        <div class="row">
            <div class="col-12">
                <h4 class="text-primary mb-4">
                    ${category.ten_danh_muc}
                    <span class="badge bg-primary ms-2">${productCount} sản phẩm</span>
                </h4>
                
                <div class="card border-0 bg-light mb-4">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Thông tin danh mục</h6>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Mã danh mục:</small></div>
                            <div class="col-md-9"><strong class="text-primary">#${category.id}</strong></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><small class="text-muted">Tên danh mục:</small></div>
                            <div class="col-md-9"><strong>${category.ten_danh_muc}</strong></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><small class="text-muted">Mô tả:</small></div>
                            <div class="col-md-9">
                                <div style="white-space: pre-wrap; word-wrap: break-word;">
                                    ${category.mo_ta ? category.mo_ta : '<span class="text-muted fst-italic">Chưa có mô tả</span>'}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0">
                    <div class="card-body">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-boxes me-2"></i>Danh sách sản phẩm 
                            <span class="badge bg-primary">${productCount}</span>
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80" class="text-center">Hình ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th width="150" class="text-end">Giá bán</th>
                                        <th width="100" class="text-center">Tồn kho</th>
                                        <th width="120" class="text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${productsHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}
</script>
