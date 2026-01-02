<?php
require_once __DIR__ . '/../config/config.php';

class Order {
    private $conn;
    private $table = 'don_hang';
    private $detail_table = 'chi_tiet_don_hang';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }
    
    // Tạo đơn hàng mới
    public function create($user_id, $ho_ten, $sdt, $dia_chi, $cart_items, $total) {
        // Bắt đầu transaction
        $this->conn->begin_transaction();
        
        try {
            // Thêm đơn hàng
            $query = "INSERT INTO {$this->table} 
                      (id_nguoi_dung, ho_ten_nguoi_nhan, so_dien_thoai_nhan, dia_chi_giao_hang, tong_tien, trang_thai) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($query);
            $status = ORDER_STATUS_PENDING;
            $stmt->bind_param("isssds", $user_id, $ho_ten, $sdt, $dia_chi, $total, $status);
            $stmt->execute();
            
            $order_id = $this->conn->insert_id;
            
            // Thêm chi tiết đơn hàng
            $query_detail = "INSERT INTO {$this->detail_table} (id_don_hang, id_san_pham, so_luong, don_gia) 
                             VALUES (?, ?, ?, ?)";
            $stmt_detail = $this->conn->prepare($query_detail);
            
            foreach ($cart_items as $item) {
                $stmt_detail->bind_param("iiid", 
                    $order_id, 
                    $item['product_id'], 
                    $item['quantity'], 
                    $item['price']
                );
                $stmt_detail->execute();
                
                // Cập nhật số lượng tồn kho
                $query_stock = "UPDATE san_pham SET so_luong_ton = so_luong_ton - ? WHERE id = ?";
                $stmt_stock = $this->conn->prepare($query_stock);
                $stmt_stock->bind_param("ii", $item['quantity'], $item['product_id']);
                $stmt_stock->execute();
            }
            
            $this->conn->commit();
            return $order_id;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    
    // Lấy đơn hàng theo user
    public function getByUserId($user_id, $limit = ORDERS_PER_PAGE, $offset = 0) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE id_nguoi_dung = ? AND ngay_xoa IS NULL 
                  ORDER BY ngay_dat DESC 
                  LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $user_id, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Đếm đơn hàng của user
    public function countByUserId($user_id) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE id_nguoi_dung = ? AND ngay_xoa IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    
    // Lấy chi tiết đơn hàng
    public function getById($id) {
        $query = "SELECT dh.*, nd.username, nd.email 
                  FROM {$this->table} dh
                  LEFT JOIN nguoi_dung nd ON dh.id_nguoi_dung = nd.id
                  WHERE dh.id = ? AND dh.ngay_xoa IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
    
    // Lấy chi tiết sản phẩm trong đơn hàng
    public function getOrderDetails($order_id) {
        $query = "SELECT ct.*, sp.ten_san_pham, sp.duong_dan_hinh_anh 
                  FROM {$this->detail_table} ct
                  LEFT JOIN san_pham sp ON ct.id_san_pham = sp.id
                  WHERE ct.id_don_hang = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy tất cả đơn hàng (admin)
    public function getAll($filters = [], $limit = ORDERS_PER_PAGE, $offset = 0) {
        $query = "SELECT dh.*, nd.username, nd.email 
                  FROM {$this->table} dh
                  LEFT JOIN nguoi_dung nd ON dh.id_nguoi_dung = nd.id
                  WHERE dh.ngay_xoa IS NULL";
        
        $params = [];
        $types = "";
        
        if (!empty($filters['status'])) {
            $query .= " AND dh.trang_thai = ?";
            $params[] = $filters['status'];
            $types .= "s";
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (dh.ho_ten_nguoi_nhan LIKE ? OR dh.so_dien_thoai_nhan LIKE ? OR nd.email LIKE ?)";
            $search_param = "%{$filters['search']}%";
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
            $types .= "sss";
        }
        
        $query .= " ORDER BY dh.ngay_dat DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Đếm tổng đơn hàng (admin)
    public function count($filters = []) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} dh
                  LEFT JOIN nguoi_dung nd ON dh.id_nguoi_dung = nd.id
                  WHERE dh.ngay_xoa IS NULL";
        
        $params = [];
        $types = "";
        
        if (!empty($filters['status'])) {
            $query .= " AND dh.trang_thai = ?";
            $params[] = $filters['status'];
            $types .= "s";
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (dh.ho_ten_nguoi_nhan LIKE ? OR dh.so_dien_thoai_nhan LIKE ? OR nd.email LIKE ?)";
            $search_param = "%{$filters['search']}%";
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
            $types .= "sss";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    
    // Cập nhật trạng thái đơn hàng
    public function updateStatus($id, $status) {
        $query = "UPDATE {$this->table} SET trang_thai = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
    
    // Hủy đơn hàng
    public function cancel($id) {
        return $this->updateStatus($id, ORDER_STATUS_CANCELLED);
    }
    
    // Xóa mềm đơn hàng
    public function softDelete($id) {
        $query = "UPDATE {$this->table} SET ngay_xoa = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Thống kê doanh thu
    public function getRevenue($start_date = null, $end_date = null) {
        $query = "SELECT SUM(tong_tien) as total_revenue, COUNT(*) as total_orders 
                  FROM {$this->table} 
                  WHERE trang_thai != ? AND ngay_xoa IS NULL";
        
        $params = [ORDER_STATUS_CANCELLED];
        $types = "s";
        
        if ($start_date) {
            $query .= " AND DATE(ngay_dat) >= ?";
            $params[] = $start_date;
            $types .= "s";
        }
        
        if ($end_date) {
            $query .= " AND DATE(ngay_dat) <= ?";
            $params[] = $end_date;
            $types .= "s";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
