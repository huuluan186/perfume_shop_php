<?php
require_once __DIR__ . '/../config/config.php';

class Product {
    private $conn;
    private $table = 'san_pham';
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }
    
    // Lấy tất cả sản phẩm với filter và phân trang
    public function getAll($filters = [], $limit = PRODUCTS_PER_PAGE, $offset = 0) {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                  FROM {$this->table} sp
                  LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE sp.ngay_xoa IS NULL";
        
        $params = [];
        $types = "";
        
        // Lọc theo danh mục
        if (!empty($filters['category_id'])) {
            $query .= " AND sp.id_danh_muc = ?";
            $params[] = $filters['category_id'];
            $types .= "i";
        }
        
        // Lọc theo thương hiệu
        if (!empty($filters['brand_id'])) {
            $query .= " AND sp.id_thuong_hieu = ?";
            $params[] = $filters['brand_id'];
            $types .= "i";
        }
        
        // Lọc theo giới tính
        if (!empty($filters['gender'])) {
            $query .= " AND sp.gioi_tinh_phu_hop = ?";
            $params[] = $filters['gender'];
            $types .= "s";
        }
        
        // Tìm kiếm
        if (!empty($filters['search'])) {
            $query .= " AND (sp.ten_san_pham LIKE ? OR sp.mo_ta LIKE ? OR th.ten_thuong_hieu LIKE ?)";
            $search_param = "%{$filters['search']}%";
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
            $types .= "sss";
        }
        
        // Lọc theo khoảng giá
        if (!empty($filters['min_price'])) {
            $query .= " AND sp.gia_ban >= ?";
            $params[] = $filters['min_price'];
            $types .= "d";
        }
        
        if (!empty($filters['max_price'])) {
            $query .= " AND sp.gia_ban <= ?";
            $params[] = $filters['max_price'];
            $types .= "d";
        }
        
        // Sắp xếp
        $order_by = "sp.ngay_xoa IS NULL DESC, sp.id DESC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $order_by = "sp.ngay_xoa IS NULL DESC, sp.gia_ban ASC";
                    break;
                case 'price_desc':
                    $order_by = "sp.ngay_xoa IS NULL DESC, sp.gia_ban DESC";
                    break;
                case 'name_asc':
                    $order_by = "sp.ngay_xoa IS NULL DESC, sp.ten_san_pham ASC";
                    break;
                case 'name_desc':
                    $order_by = "sp.ngay_xoa IS NULL DESC, sp.ten_san_pham DESC";
                    break;
                case 'newest':
                    $order_by = "sp.ngay_xoa IS NULL DESC, sp.id DESC";
                    break;
            }
        }
        
        $query .= " ORDER BY {$order_by} LIMIT ? OFFSET ?";
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
    
    // Đếm tổng sản phẩm theo filter
    public function count($filters = []) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} sp
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE sp.ngay_xoa IS NULL";
        
        $params = [];
        $types = "";
        
        if (!empty($filters['category_id'])) {
            $query .= " AND sp.id_danh_muc = ?";
            $params[] = $filters['category_id'];
            $types .= "i";
        }
        
        if (!empty($filters['brand_id'])) {
            $query .= " AND sp.id_thuong_hieu = ?";
            $params[] = $filters['brand_id'];
            $types .= "i";
        }
        
        if (!empty($filters['gender'])) {
            $query .= " AND sp.gioi_tinh_phu_hop = ?";
            $params[] = $filters['gender'];
            $types .= "s";
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (sp.ten_san_pham LIKE ? OR sp.mo_ta LIKE ? OR th.ten_thuong_hieu LIKE ?)";
            $search_param = "%{$filters['search']}%";
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
            $types .= "sss";
        }
        
        if (!empty($filters['min_price'])) {
            $query .= " AND sp.gia_ban >= ?";
            $params[] = $filters['min_price'];
            $types .= "d";
        }
        
        if (!empty($filters['max_price'])) {
            $query .= " AND sp.gia_ban <= ?";
            $params[] = $filters['max_price'];
            $types .= "d";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    
    // Lấy chi tiết sản phẩm
    public function getById($id) {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu, th.quoc_gia 
                  FROM {$this->table} sp
                  LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE sp.id = ? AND sp.ngay_xoa IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
    
    // Lấy sản phẩm theo ID kể cả đã xóa (dùng cho admin)
    public function getByIdWithDeleted($id) {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu, th.quoc_gia 
                  FROM {$this->table} sp
                  LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE sp.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
    
    // Lấy sản phẩm mới nhất
    public function getNewest($limit = 8) {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                  FROM {$this->table} sp
                  LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE sp.ngay_xoa IS NULL AND sp.so_luong_ton > 0
                  ORDER BY sp.id DESC
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy sản phẩm bán chạy (giả lập bằng random hoặc theo số lượng đơn hàng)
    public function getBestSelling($limit = 8) {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                  FROM {$this->table} sp
                  LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE sp.ngay_xoa IS NULL AND sp.so_luong_ton > 0
                  ORDER BY RAND()
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy sản phẩm liên quan
    public function getRelated($category_id, $exclude_id, $limit = 4) {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                  FROM {$this->table} sp
                  LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE sp.id_danh_muc = ? AND sp.id != ? AND sp.ngay_xoa IS NULL
                  ORDER BY RAND()
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $category_id, $exclude_id, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Thêm sản phẩm mới (admin)
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (ten_san_pham, gia_ban, dung_tich_ml, nhom_huong, gioi_tinh_phu_hop, 
                   phong_cach, xuat_xu, nam_phat_hanh, mo_ta, duong_dan_hinh_anh, 
                   so_luong_ton, id_danh_muc, id_thuong_hieu) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Lấy giá trị từ data array - giữ nguyên NULL nếu đã là NULL
        $dung_tich_ml = $data['dung_tich_ml'] ?? ($data['dung_tich'] ?? null);
        $gioi_tinh_phu_hop = $data['gioi_tinh_phu_hop'] ?? ($data['gioi_tinh'] ?? null);
        $nhom_huong = $data['nhom_huong'] ?? null;
        $phong_cach = $data['phong_cach'] ?? null;
        $xuat_xu = $data['xuat_xu'] ?? null;
        $nam_phat_hanh = $data['nam_phat_hanh'] ?? null;
        $mo_ta = $data['mo_ta'] ?? null;
        $duong_dan_hinh_anh = $data['duong_dan_hinh_anh'] ?? null;
        $id_danh_muc = $data['id_danh_muc'] ?? null;
        $id_thuong_hieu = $data['id_thuong_hieu'] ?? null;
        
        $stmt = $this->conn->prepare($query);
        // Type string: s=string, d=double, i=integer
        // ten_san_pham(s), gia_ban(d), dung_tich_ml(i), nhom_huong(s), gioi_tinh_phu_hop(s),
        // phong_cach(s), xuat_xu(s), nam_phat_hanh(i), mo_ta(s), duong_dan_hinh_anh(s),
        // so_luong_ton(i), id_danh_muc(i), id_thuong_hieu(i)
        $stmt->bind_param("sdissssissiii", 
            $data['ten_san_pham'],
            $data['gia_ban'],
            $dung_tich_ml,
            $nhom_huong,
            $gioi_tinh_phu_hop,
            $phong_cach,
            $xuat_xu,
            $nam_phat_hanh,
            $mo_ta,
            $duong_dan_hinh_anh,
            $data['so_luong_ton'],
            $id_danh_muc,
            $id_thuong_hieu
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    // Cập nhật sản phẩm (admin)
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
                  ten_san_pham = ?, gia_ban = ?, dung_tich_ml = ?, nhom_huong = ?, 
                  gioi_tinh_phu_hop = ?, phong_cach = ?, xuat_xu = ?, nam_phat_hanh = ?, 
                  mo_ta = ?, duong_dan_hinh_anh = ?, so_luong_ton = ?, 
                  id_danh_muc = ?, id_thuong_hieu = ? 
                  WHERE id = ?";
        
        // Lấy giá trị từ data array - giữ nguyên NULL nếu đã là NULL
        $dung_tich_ml = $data['dung_tich_ml'] ?? ($data['dung_tich'] ?? null);
        $gioi_tinh_phu_hop = $data['gioi_tinh_phu_hop'] ?? ($data['gioi_tinh'] ?? null);
        $nhom_huong = $data['nhom_huong'] ?? null;
        $phong_cach = $data['phong_cach'] ?? null;
        $xuat_xu = $data['xuat_xu'] ?? null;
        $nam_phat_hanh = $data['nam_phat_hanh'] ?? null;
        $mo_ta = $data['mo_ta'] ?? null;
        $duong_dan_hinh_anh = $data['duong_dan_hinh_anh'] ?? null;
        $id_danh_muc = $data['id_danh_muc'] ?? null;
        $id_thuong_hieu = $data['id_thuong_hieu'] ?? null;
        
        $stmt = $this->conn->prepare($query);
        // Type string: s=string, d=double, i=integer
        // ten_san_pham(s), gia_ban(d), dung_tich_ml(i), nhom_huong(s), gioi_tinh_phu_hop(s),
        // phong_cach(s), xuat_xu(s), nam_phat_hanh(i), mo_ta(s), duong_dan_hinh_anh(s),
        // so_luong_ton(i), id_danh_muc(i), id_thuong_hieu(i), id(i)
        $stmt->bind_param("sdissssissiiii", 
            $data['ten_san_pham'],
            $data['gia_ban'],
            $dung_tich_ml,
            $nhom_huong,
            $gioi_tinh_phu_hop,
            $phong_cach,
            $xuat_xu,
            $nam_phat_hanh,
            $mo_ta,
            $duong_dan_hinh_anh,
            $data['so_luong_ton'],
            $id_danh_muc,
            $id_thuong_hieu,
            $id
        );
        
        return $stmt->execute();
    }
    
    // Xóa mềm sản phẩm (admin)
    public function softDelete($id) {
        $query = "UPDATE {$this->table} SET ngay_xoa = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Cập nhật số lượng tồn kho
    public function updateStock($id, $quantity) {
        $query = "UPDATE {$this->table} SET so_luong_ton = so_luong_ton - ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $id);
        return $stmt->execute();
    }
    
    // Lấy sản phẩm sắp hết hàng
    public function getLowStock($limit = 10, $threshold = 10) {
        $query = "SELECT sp.*, th.ten_thuong_hieu 
                  FROM {$this->table} sp
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE sp.ngay_xoa IS NULL AND sp.so_luong_ton <= ?
                  ORDER BY sp.so_luong_ton ASC
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $threshold, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy tất cả sản phẩm cho admin (bao gồm cả sản phẩm đã xóa)
    public function getAllForAdmin($filters = [], $limit = PRODUCTS_PER_PAGE, $offset = 0) {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                  FROM {$this->table} sp
                  LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE 1=1";
        
        $params = [];
        $types = "";
        
        // Lọc theo danh mục
        if (!empty($filters['category_id'])) {
            $query .= " AND sp.id_danh_muc = ?";
            $params[] = $filters['category_id'];
            $types .= "i";
        }
        
        // Lọc theo thương hiệu
        if (!empty($filters['brand_id'])) {
            $query .= " AND sp.id_thuong_hieu = ?";
            $params[] = $filters['brand_id'];
            $types .= "i";
        }
        
        // Tìm kiếm
        if (!empty($filters['search'])) {
            $query .= " AND (sp.ten_san_pham LIKE ? OR sp.mo_ta LIKE ? OR th.ten_thuong_hieu LIKE ?)";
            $search_param = "%{$filters['search']}%";
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
            $types .= "sss";
        }
        
        // Sắp xếp: sản phẩm chưa xóa lên trước, đã xóa xuống cuối
        $order_by = "CASE WHEN sp.ngay_xoa IS NULL THEN 0 ELSE 1 END, sp.id DESC";
        
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $order_by = "CASE WHEN sp.ngay_xoa IS NULL THEN 0 ELSE 1 END, sp.gia_ban ASC";
                    break;
                case 'price_desc':
                    $order_by = "CASE WHEN sp.ngay_xoa IS NULL THEN 0 ELSE 1 END, sp.gia_ban DESC";
                    break;
                case 'name_asc':
                    $order_by = "CASE WHEN sp.ngay_xoa IS NULL THEN 0 ELSE 1 END, sp.ten_san_pham ASC";
                    break;
                case 'name_desc':
                    $order_by = "CASE WHEN sp.ngay_xoa IS NULL THEN 0 ELSE 1 END, sp.ten_san_pham DESC";
                    break;
                case 'newest':
                    $order_by = "CASE WHEN sp.ngay_xoa IS NULL THEN 0 ELSE 1 END, sp.id DESC";
                    break;
            }
        }
        
        $query .= " ORDER BY {$order_by} LIMIT ? OFFSET ?";
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
    
    // Đếm tổng sản phẩm cho admin (bao gồm cả đã xóa)
    public function countForAdmin($filters = []) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} sp
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id
                  WHERE 1=1";
        
        $params = [];
        $types = "";
        
        if (!empty($filters['category_id'])) {
            $query .= " AND sp.id_danh_muc = ?";
            $params[] = $filters['category_id'];
            $types .= "i";
        }
        
        if (!empty($filters['brand_id'])) {
            $query .= " AND sp.id_thuong_hieu = ?";
            $params[] = $filters['brand_id'];
            $types .= "i";
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (sp.ten_san_pham LIKE ? OR sp.mo_ta LIKE ? OR th.ten_thuong_hieu LIKE ?)";
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
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
