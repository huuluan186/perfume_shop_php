<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Product.php';

$productModel = new Product();
$products = $productModel->getAll();

echo "<h2>Kiểm tra ảnh sản phẩm</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Tên SP</th><th>Đường dẫn DB</th><th>File tồn tại?</th><th>URL test</th></tr>";

foreach ($products as $product) {
    $db_path = $product['duong_dan_hinh_anh'];
    $full_path = __DIR__ . '/assets/' . rawurldecode($db_path);
    $exists = file_exists($full_path);
    
    $color = $exists ? 'green' : 'red';
    $status = $exists ? '✓ Có' : '✗ Không';
    
    echo "<tr>";
    echo "<td>{$product['id']}</td>";
    echo "<td>{$product['ten_san_pham']}</td>";
    echo "<td>{$db_path}</td>";
    echo "<td style='color: {$color}; font-weight: bold'>{$status}</td>";
    echo "<td><a href='" . ASSETS_URL . $db_path . "' target='_blank'>Test</a></td>";
    echo "</tr>";
}

echo "</table>";
?>
