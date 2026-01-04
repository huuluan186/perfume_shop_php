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
                            <th width="80">ID</th>
                            <th>Tên danh mục</th>
                            <th width="500">Mô tả</th>
                            <th class="text-center sticky-action">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><strong>#<?php echo $category['id']; ?></strong></td>
                            <td><strong><?php echo htmlspecialchars($category['ten_danh_muc']); ?></strong></td>
                            <td><small><?php echo htmlspecialchars(substr($category['mo_ta'] ?? '', 0, 150)); ?><?php echo (strlen($category['mo_ta'] ?? '') > 150) ? '...' : ''; ?></small></td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-outline-info me-1 view-category" 
                                        data-id="<?php echo $category['id']; ?>" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
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

<script>
$('.delete-category').on('click', function(e) {
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

// Xem chi tiết danh mục
$('.view-category').click(function() {
    const categoryId = $(this).data('id');
    
    $('#categoryDetailModal').modal('show');
    $('#categoryDetailContent').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary"></i><p class="mt-3">Đang tải...</p></div>');
    
    $.ajax({
        url: 'get-detail.php?id=' + categoryId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const c = response.category;
                const html = `
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-primary mb-4">${c.ten_danh_muc}</h4>
                            
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Thông tin danh mục</h6>
                                    <div class="row mb-2">
                                        <div class="col-md-3"><small class="text-muted">Mã danh mục:</small></div>
                                        <div class="col-md-9"><strong class="text-primary">#${c.id}</strong></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3"><small class="text-muted">Tên danh mục:</small></div>
                                        <div class="col-md-9"><strong>${c.ten_danh_muc}</strong></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3"><small class="text-muted">Mô tả:</small></div>
                                        <div class="col-md-9">${c.mo_ta ? c.mo_ta.replace(/\n/g, '<br>') : '<span class="text-muted fst-italic">Chưa có mô tả</span>'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#categoryDetailContent').html(html);
            } else {
                $('#categoryDetailContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>' + response.message + '</div>');
            }
        },
        error: function() {
            $('#categoryDetailContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra khi tải thông tin!</div>');
        }
    });
});
</script>

<!-- Modal Chi tiết danh mục -->
<div class="modal fade" id="categoryDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Chi tiết danh mục</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="categoryDetailContent">
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
