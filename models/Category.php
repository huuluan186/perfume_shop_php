<?php
require_once __DIR__ . '/../config/config.php';

class Category {
    private $conn;
    private $table = 'danh_muc';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }
    
    // Lấy tất cả danh mục
    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY ten_danh_muc ASC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy danh mục theo ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
    
    // Thêm danh mục mới
    public function create($ten_danh_muc, $mo_ta = null) {
        $query = "INSERT INTO {$this->table} (ten_danh_muc, mo_ta) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $ten_danh_muc, $mo_ta);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    // Cập nhật danh mục
    public function update($id, $ten_danh_muc, $mo_ta = null) {
        $query = "UPDATE {$this->table} SET ten_danh_muc = ?, mo_ta = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $ten_danh_muc, $mo_ta, $id);
        return $stmt->execute();
    }
    
    // Xóa danh mục
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
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
