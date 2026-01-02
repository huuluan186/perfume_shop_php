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
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="80">ID</th>
                            <th width="120">Hình ảnh</th>
                            <th>Tên danh mục</th>
                            <th width="400">Mô tả</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><strong>#<?php echo $category['id']; ?></strong></td>
                            <td>
                                <?php if (!empty($category['hinh_anh'])): ?>
                                <img src="<?php echo htmlspecialchars($category['hinh_anh']); ?>" 
                                     class="rounded" style="width: 80px; height: 60px; object-fit: cover;"
                                     onerror="this.style.display='none'">
                                <?php else: ?>
                                <span class="text-muted small">Chưa có</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($category['ten_danh_muc']); ?></strong></td>
                            <td><small><?php echo htmlspecialchars(substr($category['mo_ta'] ?? '', 0, 100)); ?><?php echo (strlen($category['mo_ta'] ?? '') > 100) ? '...' : ''; ?></small></td>
                            <td>
                                <a href="edit.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-category" 
                                        data-id="<?php echo $category['id']; ?>">
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
$(document).on('click', '.delete-category', function() {
    if (!confirm('Bạn có chắc muốn xóa danh mục này?')) return;
    
    const categoryId = $(this).data('id');
    
    $.ajax({
        url: 'delete.php',
        method: 'POST',
        data: { id: categoryId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showNotification('success', response.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('error', response.message);
            }
        }
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
