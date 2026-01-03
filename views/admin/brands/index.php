<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Brand.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$brandModel = new Brand();

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$brands = $brandModel->getAll($limit, $offset);
$total_brands = $brandModel->count();
$pagination = paginate($total_brands, $page, $limit);

$page_title = "Quản lý thương hiệu";
include __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-crown me-2"></i>Quản lý thương hiệu</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm thương hiệu
        </a>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($brands)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-crown fa-3x mb-3"></i>
                <p>Chưa có thương hiệu nào</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="80">ID</th>
                            <th width="120">Logo</th>
                            <th>Tên thương hiệu</th>
                            <th>Quốc gia</th>
                            <th>Số sản phẩm</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($brands as $brand): ?>
                        <tr>
                            <td><strong>#<?php echo $brand['id']; ?></strong></td>
                            <td>
                                <?php if (!empty($brand['logo'])): ?>
                                <img src="<?php echo htmlspecialchars($brand['logo']); ?>" 
                                     class="rounded" style="max-width: 80px; max-height: 60px; object-fit: contain;"
                                     onerror="this.style.display='none'">
                                <?php else: ?>
                                <span class="text-muted small">Chưa có</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></strong></td>
                            <td><?php echo htmlspecialchars($brand['quoc_gia'] ?? '-'); ?></td>
                            <td><span class="badge bg-info"><?php echo $brand['product_count']; ?> sản phẩm</span></td>
                            <td>
                                <a href="edit.php?id=<?php echo $brand['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-brand" 
                                        data-id="<?php echo $brand['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($pagination['total_pages'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <li class="page-item <?php echo ($i === $pagination['current_page']) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
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
    $('.delete-brand').on('click', function() {
        if (!confirm('Bạn có chắc muốn xóa thương hiệu này?')) return;
        
        const brandId = $(this).data('id');
        
        $.ajax({
            url: 'delete.php',
            method: 'POST',
            data: { id: brandId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function() {
                showNotification('error', 'Có lỗi xảy ra!');
            }
        });
    });
});
</script>
