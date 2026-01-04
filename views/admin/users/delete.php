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

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Người dùng không hợp lệ!']);
    exit;
}

// Không cho xóa chính mình
if ($user_id === $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'Không thể xóa tài khoản của chính mình!']);
    exit;
}

$userModel = new User();
$result = $userModel->softDelete($user_id);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Đã xóa người dùng thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Xóa người dùng thất bại!']);
}
?>
