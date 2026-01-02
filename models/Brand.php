<?php
require_once __DIR__ . '/../config/config.php';

class Brand {
    private $conn;
    private $table = 'thuong_hieu';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }
    
    // Lấy tất cả thương hiệu
    public function getAll($limit = null, $offset = 0) {
        $query = "SELECT * FROM {$this->table} WHERE ngay_xoa IS NULL ORDER BY ten_thuong_hieu ASC";
        
        if ($limit) {
            $query .= " LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Đếm tổng thương hiệu
    public function count() {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE ngay_xoa IS NULL";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // Lấy thương hiệu theo ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ? AND ngay_xoa IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
    
    // Lấy sản phẩm của thương hiệu
    public function getProducts($brand_id, $limit = 12, $offset = 0) {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                  FROM san_pham sp
                  LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id
                  LEFT JOIN {$this->table} th ON sp.id_thuong_hieu = th.id
                  WHERE sp.id_thuong_hieu = ? AND sp.ngay_xoa IS NULL
                  ORDER BY sp.id DESC
                  LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $brand_id, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Đếm sản phẩm của thương hiệu
    public function countProducts($brand_id) {
        $query = "SELECT COUNT(*) as total FROM san_pham WHERE id_thuong_hieu = ? AND ngay_xoa IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    
    // Thêm thương hiệu mới
    public function create($data) {
        $query = "INSERT INTO {$this->table} (ten_thuong_hieu, quoc_gia, mo_ta, duong_dan_logo) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", 
            $data['ten_thuong_hieu'],
            $data['quoc_gia'],
            $data['mo_ta'],
            $data['duong_dan_logo']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    // Cập nhật thương hiệu
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
                  ten_thuong_hieu = ?, quoc_gia = ?, mo_ta = ?, duong_dan_logo = ? 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", 
            $data['ten_thuong_hieu'],
            $data['quoc_gia'],
            $data['mo_ta'],
            $data['duong_dan_logo'],
            $id
        );
        return $stmt->execute();
    }
    
    // Xóa mềm thương hiệu
    public function softDelete($id) {
        $query = "UPDATE {$this->table} SET ngay_xoa = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
