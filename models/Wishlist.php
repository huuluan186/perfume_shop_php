<?php
require_once __DIR__ . '/../config/config.php';

class Wishlist {
    private $conn;
    private $table = 'danh_sach_yeu_thich';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }
    
    // Thêm sản phẩm vào danh sách yêu thích
    public function add($user_id, $product_id) {
        // Kiểm tra đã tồn tại chưa
        if ($this->exists($user_id, $product_id)) {
            return false;
        }
        
        $query = "INSERT INTO {$this->table} (id_nguoi_dung, id_san_pham) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        return $stmt->execute();
    }
    
    // Xóa sản phẩm khỏi danh sách yêu thích
    public function remove($user_id, $product_id) {
        $query = "DELETE FROM {$this->table} WHERE id_nguoi_dung = ? AND id_san_pham = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        return $stmt->execute();
    }
    
    // Kiểm tra sản phẩm đã trong danh sách yêu thích chưa
    public function exists($user_id, $product_id) {
        $query = "SELECT id FROM {$this->table} WHERE id_nguoi_dung = ? AND id_san_pham = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    // Lấy danh sách yêu thích của user
    public function getByUserId($user_id) {
        $query = "SELECT yt.*, sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                  FROM {$this->table} yt
                  INNER JOIN san_pham sp ON yt.id_san_pham = sp.id
                  LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE yt.id_nguoi_dung = ? AND sp.ngay_xoa IS NULL
                  ORDER BY yt.ngay_them DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Đếm số sản phẩm yêu thích của user
    public function countByUserId($user_id) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} yt
                  INNER JOIN san_pham sp ON yt.id_san_pham = sp.id
                  WHERE yt.id_nguoi_dung = ? AND sp.ngay_xoa IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
