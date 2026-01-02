<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../models/Wishlist.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập!']);
    exit;
}

$wishlistModel = new Wishlist();
$count = $wishlistModel->countByUserId($_SESSION['user_id']);

echo json_encode([
    'success' => true,
    'count' => $count
]);
?>
