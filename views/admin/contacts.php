<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Contact.php';

if (!is_admin()) {
    set_message('error', 'Bạn không có quyền truy cập!');
    redirect('index.php');
}

$contactModel = new Contact();

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Lấy tổng số liên hệ
$total_contacts = $contactModel->count();
$pagination = paginate($total_contacts, $page, $limit);

// Lấy danh sách liên hệ
$contacts = $contactModel->getAll($limit, $offset);

$page_title = "Quản lý liên hệ";
include __DIR__ . '/layout/header.php';
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-envelope me-2"></i>Quản lý liên hệ</h2>
        <div class="text-muted">
            Tổng: <strong><?php echo $total_contacts; ?></strong> liên hệ
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($contacts)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <p>Chưa có liên hệ nào</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="80">ID</th>
                            <th width="250">Họ tên</th>
                            <th width="250">Email</th>
                            <th width="150" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><strong>#<?php echo $contact['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($contact['ho_ten']); ?></td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>" class="text-decoration-none">
                                    <i class="fas fa-envelope me-1"></i>
                                    <?php echo htmlspecialchars($contact['email']); ?>
                                </a>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info view-contact" 
                                        data-id="<?php echo $contact['id']; ?>" 
                                        title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-contact" 
                                        data-id="<?php echo $contact['id']; ?>" 
                                        title="Xóa">
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
                    <?php if ($pagination['current_page'] > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <li class="page-item <?php echo ($i === $pagination['current_page']) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>">
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

<!-- Modal Chi tiết liên hệ -->
<div class="modal fade" id="contactDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-envelope me-2"></i>Chi tiết liên hệ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contactDetailContent">
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

<?php include __DIR__ . '/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    // View contact detail
    $(document).on('click', '.view-contact', function() {
        const contactId = $(this).data('id');
        
        $('#contactDetailContent').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
        
        const modal = new bootstrap.Modal(document.getElementById('contactDetailModal'));
        modal.show();
        
        $.ajax({
            url: 'contacts/get-detail.php',
            method: 'GET',
            data: { id: contactId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const c = response.contact;
                    const html = `
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Mã liên hệ:</strong></div>
                            <div class="col-md-9"><span class="badge bg-primary">#${c.id}</span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Họ tên:</strong></div>
                            <div class="col-md-9">${c.ho_ten}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Email:</strong></div>
                            <div class="col-md-9">
                                <a href="mailto:${c.email}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-1"></i>${c.email}
                                </a>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Thời gian:</strong></div>
                            <div class="col-md-9"><i class="far fa-clock me-1"></i>${c.thoi_gian_gui_formatted}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><strong>Nội dung:</strong></div>
                            <div class="col-md-9">
                                <div class="border rounded p-3 bg-light">
                                    ${c.noi_dung.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        </div>
                    `;
                    $('#contactDetailContent').html(html);
                } else {
                    $('#contactDetailContent').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#contactDetailContent').html('<div class="alert alert-danger">Có lỗi xảy ra!</div>');
            }
        });
    });
    
    // Delete contact
    $(document).on('click', '.delete-contact', function() {
        if (!confirm('Bạn có chắc muốn xóa liên hệ này?')) return;
        
        const contactId = $(this).data('id');
        const $button = $(this);
        $button.prop('disabled', true);
        
        $.ajax({
            url: 'contacts/delete.php',
            method: 'POST',
            data: { id: contactId },
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
            error: function() {
                alert('Có lỗi xảy ra!');
                $button.prop('disabled', false);
            }
        });
    });
});
</script>
