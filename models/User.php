<?php
require_once __DIR__ . '/../config/config.php';

class User {
    private $conn;
    private $table = 'nguoi_dung';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }
    
    // Đăng ký người dùng mới
    public function register($username, $email, $password, $gioi_tinh = null, $ngay_sinh = null) {
        // Kiểm tra email đã tồn tại
        if ($this->emailExists($email)) {
            return false;
        }
        
        $hashed_password = md5($password);
        
        $query = "INSERT INTO {$this->table} (username, email, password, gioi_tinh, ngay_sinh, vai_tro, trang_thai) 
                  VALUES (?, ?, ?, ?, ?, ?, 1)";
        
        $stmt = $this->conn->prepare($query);
        $role = ROLE_CUSTOMER;
        $stmt->bind_param("ssssss", $username, $email, $hashed_password, $gioi_tinh, $ngay_sinh, $role);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    // Đăng nhập
    public function login($email, $password) {
        $hashed_password = md5($password);
        
        $query = "SELECT id, username, email, vai_tro, gioi_tinh, ngay_sinh 
                  FROM {$this->table} 
                  WHERE email = ? AND password = ? AND trang_thai = 1 AND ngay_xoa IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $email, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
    
    // Kiểm tra email đã tồn tại
    public function emailExists($email, $exclude_id = null) {
        $query = "SELECT id FROM {$this->table} WHERE email = ? AND ngay_xoa IS NULL";
        if ($exclude_id) {
            $query .= " AND id != ?";
        }
        
        $stmt = $this->conn->prepare($query);
        if ($exclude_id) {
            $stmt->bind_param("si", $email, $exclude_id);
        } else {
            $stmt->bind_param("s", $email);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    // Lấy thông tin user theo ID
    public function getUserById($id) {
        $query = "SELECT id, username, email, password as mat_khau, gioi_tinh, ngay_sinh, vai_tro, trang_thai, ngay_tao 
                  FROM {$this->table} 
                  WHERE id = ? AND ngay_xoa IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
    
    // Đổi mật khẩu
    public function changePassword($id, $old_password, $new_password) {
        // Kiểm tra mật khẩu cũ
        $query = "SELECT id FROM {$this->table} WHERE id = ? AND password = ?";
        $stmt = $this->conn->prepare($query);
        $old_hashed = md5($old_password);
        $stmt->bind_param("is", $id, $old_hashed);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        // Cập nhật mật khẩu mới
        $query = "UPDATE {$this->table} SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $new_hashed = md5($new_password);
        $stmt->bind_param("si", $new_hashed, $id);
        
        return $stmt->execute();
    }
    
    // Đổi mật khẩu đơn giản (không kiểm tra mật khẩu cũ)
    public function changePasswordSimple($id, $new_password) {
        $query = "UPDATE {$this->table} SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $new_hashed = md5($new_password);
        $stmt->bind_param("si", $new_hashed, $id);
        return $stmt->execute();
    }
    
    // Cập nhật thông tin profile
    public function updateProfile($id, $username, $gioi_tinh, $ngay_sinh) {
        $query = "UPDATE {$this->table} SET username = ?, gioi_tinh = ?, ngay_sinh = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('sssi', $username, $gioi_tinh, $ngay_sinh, $id);
        return $stmt->execute();
    }
    
    // Lấy tất cả users (admin)
    public function getAllUsers($search = '', $limit = 20, $offset = 0) {
        $query = "SELECT id, username, email, gioi_tinh, ngay_sinh, vai_tro, trang_thai, ngay_tao 
                  FROM {$this->table} 
                  WHERE ngay_xoa IS NULL";
        
        if ($search) {
            $query .= " AND (username LIKE ? OR email LIKE ?)";
        }
        
        $query .= " ORDER BY ngay_tao DESC LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        
        if ($search) {
            $search_param = "%{$search}%";
            $stmt->bind_param("ssii", $search_param, $search_param, $limit, $offset);
        } else {
            $stmt->bind_param("ii", $limit, $offset);
        }
        
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Đếm tổng số users
    public function countUsers($search = '') {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE ngay_xoa IS NULL";
        
        if ($search) {
            $query .= " AND (username LIKE ? OR email LIKE ?)";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($search) {
            $search_param = "%{$search}%";
            $stmt->bind_param("ss", $search_param, $search_param);
        }
        
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    
    // Alias cho countUsers (để tương thích với dashboard)
    public function count() {
        return $this->countUsers();
    }
    
    // Cập nhật trạng thái user (admin)
    public function updateStatus($id, $status) {
        $query = "UPDATE {$this->table} SET trang_thai = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $status, $id);
        return $stmt->execute();
    }
    
    // Xóa mềm user (admin)
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
