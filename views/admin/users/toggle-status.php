<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/User.php';

header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thực hiện!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$user_id = intval($_POST['id'] ?? 0);
$current_status = intval($_POST['status'] ?? 1);

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Người dùng không hợp lệ!']);
    exit;
}

// Không cho khóa chính mình
if ($user_id === $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'Không thể khóa tài khoản của chính bạn!']);
    exit;
}

$userModel = new User();
$new_status = $current_status == 1 ? 0 : 1;
$result = $userModel->updateStatus($user_id, $new_status);

if ($result) {
    $message = $new_status == 1 ? 'Đã mở khóa tài khoản!' : 'Đã khóa tài khoản!';
    echo json_encode(['success' => true, 'message' => $message]);
} else {
    echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại!']);
}
?>
