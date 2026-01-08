<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Brand.php';
require_once __DIR__ . '/../../../models/Product.php';

header('Content-Type: application/json');

try {
    if (!is_admin()) {
        echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập!']);
        exit;
    }

    $brand_id = intval($_GET['id'] ?? 0);
    if ($brand_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Thương hiệu không hợp lệ!']);
        exit;
    }

    $brandModel = new Brand();
    $brand = $brandModel->getByIdWithDeleted($brand_id);

    if (!$brand) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy thương hiệu!']);
        exit;
    }

    // Lấy danh sách sản phẩm theo thương hiệu
    $productModel = new Product();
    $products = $productModel->getAll(['brand_id' => $brand_id], 1000, 0);
    $productCount = count($products);

    // Format dữ liệu sản phẩm
    $productsFormatted = [];
    foreach ($products as $product) {
        // Xử lý đường dẫn hình ảnh giống y hệt products/index.php
        $imagePath = $product['duong_dan_hinh_anh'] ?? '';
        if (!empty($imagePath)) {
            $imageUrl = (strpos($imagePath, '/') !== false) 
                ? ASSETS_URL . urldecode($imagePath) 
                : UPLOAD_URL . $imagePath;
        } else {
            $imageUrl = ASSETS_URL . 'images/placeholder.png';
        }
        
        $productsFormatted[] = [
            'id' => $product['id'],
            'ten_san_pham' => $product['ten_san_pham'],
            'ten_danh_muc' => $product['ten_danh_muc'] ?? 'N/A',
            'image_url' => $imageUrl,
            'gia_ban' => $product['gia_ban'],
            'gia_ban_formatted' => format_currency($product['gia_ban']),
            'so_luong_ton' => $product['so_luong_ton'] ?? 0,
            'trang_thai' => $product['trang_thai'] ?? 1
        ];
    }

    echo json_encode([
        'success' => true,
        'brand' => $brand,
        'products' => $productsFormatted,
        'product_count' => $productCount
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Lỗi: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>
