<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../helpers/functions.php';
require_once __DIR__ . '/../../../models/Category.php';
require_once __DIR__ . '/../../../models/Product.php';

header('Content-Type: application/json');

try {
    if (!is_admin()) {
        echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập!']);
        exit;
    }

    $category_id = intval($_GET['id'] ?? 0);
    if ($category_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Danh mục không hợp lệ!']);
        exit;
    }

    $categoryModel = new Category();
    $category = $categoryModel->getById($category_id);

    if (!$category) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy danh mục!']);
        exit;
    }

    // Lấy danh sách sản phẩm thuộc danh mục này
    $productModel = new Product();
    $products = $productModel->getByCategory($category_id);
    $productCount = count($products);

    // Format dữ liệu sản phẩm
    $productsFormatted = [];
    foreach ($products as $product) {
        // Xử lý đường dẫn hình ảnh
        $imagePath = $product['duong_dan_hinh_anh'] ?? '';
        // Nếu đường dẫn không bắt đầu bằng products/, thêm vào
        if (!empty($imagePath) && strpos($imagePath, 'products/') !== 0) {
            $imagePath = 'products/' . $imagePath;
        }
        
        $productsFormatted[] = [
            'id' => $product['id'],
            'ten_san_pham' => $product['ten_san_pham'],
            'ten_thuong_hieu' => $product['ten_thuong_hieu'] ?? 'N/A',
            'image_path' => $imagePath,
            'gia_ban' => $product['gia_ban'],
            'gia_ban_formatted' => format_currency($product['gia_ban']),
            'so_luong_ton' => $product['so_luong_ton'] ?? 0,
            'trang_thai' => $product['trang_thai'] ?? 1
        ];
    }

    echo json_encode([
        'success' => true,
        'category' => $category,
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
