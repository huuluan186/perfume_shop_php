<?php
// Test file để kiểm tra ảnh và database

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Brand.php';

$productModel = new Product();
$brandModel = new Brand();

echo "<h2>Kiểm tra Database và Hình ảnh</h2>";

// Test products
echo "<h3>Sản phẩm (10 sản phẩm đầu):</h3>";
$products = $productModel->getAll([], 10, 0);
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Tên</th><th>Đường dẫn ảnh</th><th>Preview</th><th>File tồn tại?</th></tr>";
foreach ($products as $product) {
    $image_path = ASSETS_URL . $product['duong_dan_hinh_anh'];
    $file_path = __DIR__ . '/assets/' . $product['duong_dan_hinh_anh'];
    $exists = file_exists($file_path) ? '✓ CÓ' : '✗ KHÔNG';
    echo "<tr>";
    echo "<td>{$product['id']}</td>";
    echo "<td>{$product['ten_san_pham']}</td>";
    echo "<td>{$product['duong_dan_hinh_anh']}</td>";
    echo "<td><img src='{$image_path}' width='50' onerror='this.src=\"" . ASSETS_URL . "images/placeholder.jpg\"'></td>";
    echo "<td>{$exists}</td>";
    echo "</tr>";
}
echo "</table>";

// Test brands
echo "<h3>Thương hiệu (10 thương hiệu đầu):</h3>";
$brands = $brandModel->getAll(10);
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Tên</th><th>Logo URL</th><th>Preview</th></tr>";
foreach ($brands as $brand) {
    echo "<tr>";
    echo "<td>{$brand['id']}</td>";
    echo "<td>{$brand['ten_thuong_hieu']}</td>";
    echo "<td>" . ($brand['logo'] ?? 'NULL') . "</td>";
    if (!empty($brand['logo'])) {
        echo "<td><img src='{$brand['logo']}' width='50' onerror='this.alt=\"❌ Không tải được\"'></td>";
    } else {
        echo "<td>Không có logo</td>";
    }
    echo "</tr>";
}
echo "</table>";

echo "<hr>";
echo "<h3>Thông tin cấu hình:</h3>";
echo "BASE_URL: " . BASE_URL . "<br>";
echo "ASSETS_URL: " . ASSETS_URL . "<br>";
echo "UPLOAD_URL: " . UPLOAD_URL . "<br>";
echo "UPLOAD_PATH: " . UPLOAD_PATH . "<br>";
echo "PRODUCTS_PER_PAGE: " . PRODUCTS_PER_PAGE . "<br>";
?>
