<?php
class Contact {
    private $conn;
    private $table = 'lien_he';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }
    
    // Tạo liên hệ mới
    public function create($data) {
        $query = "INSERT INTO {$this->table} (ho_ten, email, noi_dung) 
                  VALUES (?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "sss",
            $data['ho_ten'],
            $data['email'],
            $data['noi_dung']
        );
        
        return $stmt->execute();
    }
    
    // Lấy tất cả liên hệ
    public function getAll($limit = 100, $offset = 0) {
        $query = "SELECT * FROM {$this->table} 
                  ORDER BY thoi_gian_gui DESC 
                  LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Đếm tổng số liên hệ
    public function count() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // Lấy liên hệ theo ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Xóa liên hệ
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Lấy liên hệ theo tháng
    public function getByMonth($year, $month) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE YEAR(thoi_gian_gui) = ? AND MONTH(thoi_gian_gui) = ?
                  ORDER BY thoi_gian_gui DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Đếm liên hệ mới (trong 7 ngày)
    public function countNew() {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE thoi_gian_gui >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
