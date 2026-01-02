<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../helpers/functions.php';

header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'count' => count_cart_items()
]);
?>
