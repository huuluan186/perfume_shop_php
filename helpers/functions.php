<?php
// Các hàm hỗ trợ chung

// Làm sạch dữ liệu đầu vào
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Redirect
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

// Kiểm tra đăng nhập
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Kiểm tra trạng thái tài khoản và tự động đăng xuất nếu bị khóa
function check_account_status() {
    if (is_logged_in()) {
        require_once __DIR__ . '/../models/User.php';
        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        // Nếu không tìm thấy user hoặc tài khoản bị khóa
        if (!$user || $user['trang_thai'] != 1) {
            // Xóa session và đăng xuất
            session_destroy();
            set_message('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên!');
            redirect('views/auth/login.php');
        }
    }
}

// Kiểm tra admin
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === ROLE_ADMIN;
}

// Yêu cầu đăng nhập
function require_login() {
    if (!is_logged_in()) {
        $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục!';
        redirect('auth/login.php');
    }
}

// Yêu cầu quyền admin
function require_admin() {
    require_login();
    if (!is_admin()) {
        $_SESSION['error'] = 'Bạn không có quyền truy cập!';
        redirect('index.php');
    }
}

// Format tiền VNĐ
function format_currency($amount) {
    return number_format($amount, 0, ',', '.') . ' ₫';
}

// Format ngày giờ
function format_datetime($datetime) {
    return date('d/m/Y H:i', strtotime($datetime));
}

// Format ngày
function format_date($date) {
    return date('d/m/Y', strtotime($date));
}

// Tính giá sau giảm
function calculate_sale_price($original_price, $discount_percent) {
    return $original_price - ($original_price * $discount_percent / 100);
}

// Upload file
function upload_image($file, $folder = '') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // Kiểm tra loại file
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        return false;
    }
    
    // Tạo tên file unique
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    
    // Đường dẫn lưu file
    $upload_dir = UPLOAD_PATH . $folder;
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $upload_file = $upload_dir . '/' . $filename;
    
    // Di chuyển file
    if (move_uploaded_file($file['tmp_name'], $upload_file)) {
        return $folder . '/' . $filename;
    }
    
    return false;
}

// Xóa file
function delete_image($filepath) {
    $full_path = UPLOAD_PATH . $filepath;
    if (file_exists($full_path)) {
        return unlink($full_path);
    }
    return false;
}

// Tạo slug từ tên
function create_slug($str) {
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);
    $str = preg_replace("/[^a-z0-9-\s]/", '', strtolower($str));
    $str = preg_replace('/([\s]+)/', '-', $str);
    return $str;
}

// Lấy giỏ hàng từ session
function get_cart() {
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

// Đếm số lượng sản phẩm trong giỏ hàng
function count_cart_items() {
    $cart = get_cart();
    $count = 0;
    foreach ($cart as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

// Tính tổng giá trị giỏ hàng
function calculate_cart_total() {
    $cart = get_cart();
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Hiển thị thông báo
function show_message($type = 'info') {
    $messages = [
        'success' => 'success',
        'error' => 'error',
        'warning' => 'warning',
        'info' => 'info'
    ];
    
    if (isset($_SESSION[$type])) {
        $message = $_SESSION[$type];
        unset($_SESSION[$type]);
        return [
            'type' => $messages[$type],
            'message' => $message
        ];
    }
    return null;
}

// Set message vào session
function set_message($type, $message) {
    $_SESSION[$type] = $message;
}

// Tạo token CSRF
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Xác thực token CSRF
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Phân trang
function paginate($total_items, $current_page, $items_per_page) {
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'total_items' => $total_items,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'items_per_page' => $items_per_page,
        'offset' => $offset
    ];
}

// Render pagination với ellipsis
function render_pagination($total_pages, $current_page, $base_url, $params = []) {
    if ($total_pages <= 1) return '';
    
    $query_string = '';
    foreach ($params as $key => $value) {
        if ($key != 'page' && !empty($value)) {
            $query_string .= '&' . urlencode($key) . '=' . urlencode($value);
        }
    }
    
    $html = '<nav><ul class="pagination justify-content-center">';
    
    // Previous button
    if ($current_page > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '?page=' . ($current_page - 1) . $query_string . '"><i class="fas fa-chevron-left"></i></a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>';
    }
    
    // Page numbers with ellipsis
    $range = 2; // Số trang hiển thị mỗi bên
    
    if ($total_pages <= 7) {
        // Hiển thị tất cả nếu ít hơn 7 trang
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $current_page) ? 'active' : '';
            $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $base_url . '?page=' . $i . $query_string . '">' . $i . '</a></li>';
        }
    } else {
        // Trang đầu
        $html .= '<li class="page-item ' . (($current_page == 1) ? 'active' : '') . '"><a class="page-link" href="' . $base_url . '?page=1' . $query_string . '">1</a></li>';
        
        // Ellipsis bên trái
        if ($current_page > $range + 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        
        // Các trang ở giữa
        $start = max(2, $current_page - $range);
        $end = min($total_pages - 1, $current_page + $range);
        
        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $current_page) ? 'active' : '';
            $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $base_url . '?page=' . $i . $query_string . '">' . $i . '</a></li>';
        }
        
        // Ellipsis bên phải
        if ($current_page < $total_pages - $range - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        
        // Trang cuối
        $html .= '<li class="page-item ' . (($current_page == $total_pages) ? 'active' : '') . '"><a class="page-link" href="' . $base_url . '?page=' . $total_pages . $query_string . '">' . $total_pages . '</a></li>';
    }
    
    // Next button
    if ($current_page < $total_pages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '?page=' . ($current_page + 1) . $query_string . '"><i class="fas fa-chevron-right"></i></a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

// Upload hình ảnh sản phẩm
function upload_product_image($file, $product_name = '') {
    // Kiểm tra có file được upload
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    
    // Kiểm tra lỗi upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Lỗi khi upload file: ' . $file['error']);
    }
    
    // Kiểm tra loại file
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = mime_content_type($file['tmp_name']);
    
    if (!in_array($file_type, $allowed_types)) {
        throw new Exception('Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)');
    }
    
    // Kiểm tra kích thước file (tối đa 5MB)
    $max_size = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $max_size) {
        throw new Exception('Kích thước file không được vượt quá 5MB');
    }
    
    // Tạo tên file duy nhất
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('product_' . date('Ymd') . '_') . '.' . $extension;
    
    // Đường dẫn lưu file
    $upload_dir = UPLOAD_PATH;
    
    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $upload_path = $upload_dir . $filename;
    
    // Di chuyển file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new Exception('Không thể lưu file');
    }
    
    // Trả về tên file
    return $filename;
}

// Xóa hình ảnh sản phẩm
function delete_product_image($filename) {
    if (empty($filename)) {
        return true;
    }
    
    $file_path = UPLOAD_PATH . $filename;
    
    if (file_exists($file_path)) {
        return unlink($file_path);
    }
    
    return true;
}
?>
