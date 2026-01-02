<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';

// Kiểm tra đăng nhập admin
if (!is_logged_in() || !is_admin()) {
    redirect('views/auth/login.php');
}

$page_title = "Quản lý liên hệ";
include __DIR__ . '/layout/header.php';

// Lấy danh sách file log
$log_dir = __DIR__ . '/../../logs';
$log_files = [];
if (is_dir($log_dir)) {
    $files = glob($log_dir . '/contact_*.txt');
    rsort($files); // Sắp xếp mới nhất trước
    foreach ($files as $file) {
        $log_files[] = [
            'name' => basename($file),
            'path' => $file,
            'time' => filemtime($file),
            'size' => filesize($file)
        ];
    }
}

// Đọc nội dung file được chọn
$selected_file = $_GET['file'] ?? '';
$log_content = '';
if ($selected_file && file_exists($log_dir . '/' . $selected_file)) {
    $log_content = file_get_contents($log_dir . '/' . $selected_file);
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý liên hệ</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Liên hệ</li>
    </ol>

    <div class="row">
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-file-alt me-2"></i>File log theo tháng
                </div>
                <div class="list-group list-group-flush">
                    <?php if (empty($log_files)): ?>
                        <div class="list-group-item text-muted">Chưa có liên hệ nào</div>
                    <?php else: ?>
                        <?php foreach ($log_files as $file): ?>
                            <a href="?file=<?php echo urlencode($file['name']); ?>" 
                               class="list-group-item list-group-item-action <?php echo $selected_file === $file['name'] ? 'active' : ''; ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-calendar me-2"></i>
                                        <?php echo str_replace(['contact_', '.txt'], '', $file['name']); ?>
                                    </div>
                                    <small><?php echo format_filesize($file['size']); ?></small>
                                </div>
                                <small class="text-muted">
                                    <?php echo date('d/m/Y H:i', $file['time']); ?>
                                </small>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-envelope me-2"></i>
                    Nội dung liên hệ
                    <?php if ($selected_file): ?>
                        - <?php echo str_replace(['contact_', '.txt'], '', $selected_file); ?>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($selected_file)): ?>
                        <p class="text-muted text-center py-5">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            Chọn file log bên trái để xem nội dung
                        </p>
                    <?php else: ?>
                        <div class="bg-light p-3" style="white-space: pre-wrap; font-family: monospace; max-height: 600px; overflow-y: auto;">
<?php echo htmlspecialchars($log_content); ?>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo $log_dir . '/' . $selected_file; ?>" 
                               download 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-download me-1"></i>Tải xuống
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Helper function
function format_filesize($bytes) {
    if ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return round($bytes / 1024, 2) . ' KB';
    }
    return $bytes . ' B';
}
?>

<?php include __DIR__ . '/layout/footer.php'; ?>
