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
$limit = 8;
$offset = ($page - 1) * $limit;

$brands = $brandModel->getAllForAdmin($limit, $offset);
$total_brands = $brandModel->countForAdmin();
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
                <table class="table table-hover align-middle table-sticky-action">
                    <thead class="table-light">
                        <tr>
                            <th width="80">ID</th>
                            <th width="120">Logo</th>
                            <th>Tên thương hiệu</th>
                            <th>Quốc gia</th>
                            <th>Số sản phẩm</th>
                            <th>Trạng thái</th>
                            <th class="text-center sticky-action">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($brands as $brand): ?>
                        <tr <?php echo $brand['ngay_xoa'] ? 'class="table-secondary" style="opacity: 0.6;"' : ''; ?>>
                            <td><strong>#<?php echo $brand['id']; ?></strong></td>
                            <td>
                                <?php if (!empty($brand['duong_dan_logo'])): ?>
                                <img src="<?php echo htmlspecialchars($brand['duong_dan_logo']); ?>" 
                                     class="rounded" style="max-width: 80px; max-height: 60px; object-fit: contain;"
                                     onerror="this.src='<?php echo ASSETS_URL; ?>images/placeholder.png'">
                                <?php else: ?>
                                <span class="text-muted small">Chưa có</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($brand['ten_thuong_hieu']); ?></strong></td>
                            <td><?php echo htmlspecialchars(ucfirst($brand['quoc_gia'] ?? '-')); ?></td>
                            <td><span class="badge bg-info"><?php echo $brand['product_count']; ?> sản phẩm</span></td>
                            <td>
                                <?php if ($brand['ngay_xoa']): ?>
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
                                <button class="btn btn-sm btn-outline-info me-1 view-brand" 
                                        data-id="<?php echo $brand['id']; ?>" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if (!$brand['ngay_xoa']): ?>
                                <a href="edit.php?id=<?php echo $brand['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-brand" 
                                        data-id="<?php echo $brand['id']; ?>" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
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
    $('.delete-brand').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm('Bạn có chắc muốn xóa thương hiệu này?')) return;
        
        const brandId = $(this).data('id');
        const $button = $(this);
        
        // Disable button to prevent double click
        $button.prop('disabled', true);
        
        $.ajax({
            url: 'delete.php',
            method: 'POST',
            data: { id: brandId },
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
                alert('Có lỗi xảy ra khi xóa thương hiệu!');
                $button.prop('disabled', false);
            }
        });
    });
    
    // Xem chi tiết thương hiệu
    $('.view-brand').click(function() {
        const brandId = $(this).data('id');
        
        $('#brandDetailModal').modal('show');
        $('#brandDetailContent').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary"></i><p class="mt-3">Đang tải...</p></div>');
        
        $.ajax({
            url: 'get-detail.php?id=' + brandId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const b = response.brand;
                    const html = `
                        <div class="row">
                            ${b.duong_dan_logo ? '<div class="col-md-4"><div class="text-center mb-3"><img src="' + b.duong_dan_logo + '" class="img-fluid rounded shadow" style="max-height: 200px;" onerror="this.src=\\'<?php echo ASSETS_URL; ?>images/placeholder.png\\'"></div></div>' : ''}
                            <div class="${b.duong_dan_logo ? 'col-md-8' : 'col-12'}">
                                <h4 class="text-primary mb-4">${b.ten_thuong_hieu}</h4>
                                
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Thông tin thương hiệu</h6>
                                        <div class="row mb-2">
                                            <div class="col-md-4"><small class="text-muted">Mã:</small></div>
                                            <div class="col-md-8"><strong class="text-primary">#${b.id}</strong></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-4"><small class="text-muted">Tên thương hiệu:</small></div>
                                            <div class="col-md-8"><strong>${b.ten_thuong_hieu}</strong></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-4"><small class="text-muted">Quốc gia:</small></div>
                                            <div class="col-md-8">${b.quoc_gia ? capitalizeFirst(b.quoc_gia) : '<span class="text-muted">Chưa rõ</span>'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-4"><small class="text-muted">Số sản phẩm:</small></div>
                                            <div class="col-md-8"><span class="badge bg-info">${b.product_count || 0} sản phẩm</span></div>
                                        </div>
                                        ${b.ngay_xoa ? '<div class="row mb-2"><div class="col-md-4"><small class="text-muted">Ngày xóa:</small></div><div class="col-md-8"><span class="text-danger">' + formatDateTime(b.ngay_xoa) + '</span></div></div>' : ''}
                                    </div>
                                </div>
                                
                                ${b.mo_ta ? '<div class="card border-0 bg-light"><div class="card-body"><h6 class="text-primary mb-3"><i class="fas fa-align-left me-2"></i>Mô tả</h6><div style="line-height: 1.8;">' + b.mo_ta.replace(/\n/g, '<br>') + '</div></div></div>' : ''}
                            </div>
                        </div>
                    `;
                    $('#brandDetailContent').html(html);
                } else {
                    $('#brandDetailContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>' + response.message + '</div>');
                }
            },
            error: function() {
                $('#brandDetailContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra khi tải thông tin!</div>');
            }
        });
    });
    
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

<!-- Modal Chi tiết thương hiệu -->
<div class="modal fade" id="brandDetailModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Chi tiết thương hiệu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="brandDetailContent">
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

