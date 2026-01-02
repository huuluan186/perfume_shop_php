# WEBSITE BÁN NƯỚC HOA - TÀI LIỆU DỰ ÁN

## 1. THÔNG TIN DỰ ÁN

### 1.1. Tổng quan
- **Tên dự án**: Website Bán Nước Hoa (Perfume Shop)
- **Mục đích**: Xây dựng website thương mại điện tử chuyên về nước hoa
- **Công nghệ**: PHP thuần, MySQL, Bootstrap 5, jQuery

### 1.2. Tính năng chính
#### Khách hàng:
- Đăng ký, đăng nhập tài khoản
- Xem, tìm kiếm, lọc sản phẩm
- Quản lý giỏ hàng
- Quản lý danh sách yêu thích
- Đặt hàng và theo dõi đơn hàng
- Quản lý tài khoản cá nhân

#### Quản trị viên:
- Dashboard thống kê tổng quan
- Quản lý sản phẩm (CRUD)
- Quản lý đơn hàng
- Quản lý danh mục
- Quản lý thương hiệu
- Quản lý người dùng

## 2. KIẾN TRÚC HỆ THỐNG

### 2.1. Mô hình MVC (Model-View-Controller)

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │
       ↓
┌─────────────────────┐
│  index.php / Views  │ ← User Interface
└──────┬──────────────┘
       │
       ↓
┌─────────────────────┐
│    Controllers      │ ← Business Logic (trong Views)
└──────┬──────────────┘
       │
       ↓
┌─────────────────────┐
│      Models         │ ← Data Access Layer
└──────┬──────────────┘
       │
       ↓
┌─────────────────────┐
│     Database        │ ← MySQL (shop_nuoc_hoa)
└─────────────────────┘
```

### 2.2. Cấu trúc thư mục

```
perfume_shop_php/
│
├── assets/                 # Tài nguyên tĩnh
│   ├── css/
│   │   ├── style.css      # CSS chính cho user
│   │   └── admin.css      # CSS cho admin panel
│   ├── js/
│   │   ├── main.js        # JavaScript chính
│   │   ├── wishlist.js    # Xử lý wishlist
│   │   └── admin.js       # JavaScript cho admin
│   ├── images/            # Hình ảnh hệ thống
│   └── products/          # Hình ảnh sản phẩm upload
│
├── config/                # Cấu hình
│   ├── config.php        # Cấu hình chung
│   └── database.php      # Kết nối database
│
├── models/               # Data Models
│   ├── User.php         # Model người dùng
│   ├── Product.php      # Model sản phẩm
│   ├── Category.php     # Model danh mục
│   ├── Brand.php        # Model thương hiệu
│   ├── Order.php        # Model đơn hàng
│   └── Wishlist.php     # Model danh sách yêu thích
│
├── views/               # Giao diện
│   ├── layout/
│   │   ├── header.php   # Header chung
│   │   └── footer.php   # Footer chung
│   ├── auth/
│   │   ├── login.php    # Đăng nhập
│   │   ├── register.php # Đăng ký
│   │   └── logout.php   # Đăng xuất
│   ├── products/
│   │   ├── index.php    # Danh sách sản phẩm
│   │   └── detail.php   # Chi tiết sản phẩm
│   ├── cart/
│   │   ├── index.php    # Giỏ hàng
│   │   ├── add.php      # Thêm vào giỏ
│   │   ├── update.php   # Cập nhật giỏ
│   │   ├── remove.php   # Xóa khỏi giỏ
│   │   ├── count.php    # Đếm số lượng
│   │   └── checkout.php # Thanh toán
│   ├── wishlist/
│   │   ├── index.php    # Danh sách yêu thích
│   │   ├── add.php      # Thêm yêu thích
│   │   ├── remove.php   # Xóa yêu thích
│   │   └── count.php    # Đếm số lượng
│   ├── account/
│   │   ├── profile.php        # Thông tin cá nhân
│   │   ├── orders.php         # Đơn hàng của tôi
│   │   ├── order-details.php  # Chi tiết đơn hàng
│   │   ├── cancel-order.php   # Hủy đơn hàng
│   │   └── change-password.php# Đổi mật khẩu
│   ├── admin/
│   │   ├── layout/
│   │   │   ├── header.php    # Header admin
│   │   │   └── footer.php    # Footer admin
│   │   ├── dashboard.php     # Trang tổng quan
│   │   ├── products/
│   │   │   ├── index.php     # Danh sách sản phẩm
│   │   │   ├── create.php    # Thêm sản phẩm
│   │   │   ├── edit.php      # Sửa sản phẩm
│   │   │   └── delete.php    # Xóa sản phẩm
│   │   ├── orders/
│   │   │   ├── index.php     # Danh sách đơn hàng
│   │   │   ├── view-order.php    # Xem chi tiết
│   │   │   └── update-status.php # Cập nhật trạng thái
│   │   ├── categories/
│   │   │   ├── index.php     # Danh sách danh mục
│   │   │   ├── create.php    # Thêm danh mục
│   │   │   ├── edit.php      # Sửa danh mục
│   │   │   └── delete.php    # Xóa danh mục
│   │   ├── brands/
│   │   │   ├── index.php     # Danh sách thương hiệu
│   │   │   ├── create.php    # Thêm thương hiệu
│   │   │   ├── edit.php      # Sửa thương hiệu
│   │   │   └── delete.php    # Xóa thương hiệu
│   │   └── users/
│   │       ├── index.php         # Danh sách người dùng
│   │       └── toggle-status.php # Khóa/mở khóa
│   ├── brands/
│   │   ├── index.php    # Danh sách thương hiệu
│   │   └── detail.php   # Chi tiết thương hiệu
│   ├── about.php        # Giới thiệu
│   └── contact.php      # Liên hệ
│
├── helpers/
│   └── functions.php    # Hàm tiện ích
│
├── db/
│   ├── shop_nuoc_hoa.sql    # Database schema
│   ├── sample_users.sql     # Dữ liệu user mẫu
│   └── products.json        # Dữ liệu sản phẩm JSON
│
├── index.php            # Trang chủ
├── .htaccess           # Cấu hình Apache
├── 404.php             # Trang lỗi 404
├── README.md           # Tài liệu tóm tắt
└── HUONG_DAN.md        # Hướng dẫn sử dụng
```

## 3. DATABASE SCHEMA

### 3.1. Sơ đồ quan hệ

```
┌──────────────┐       ┌──────────────┐
│  nguoi_dung  │◄──────┤   don_hang   │
└──────────────┘       └───────┬──────┘
       ▲                       │
       │                       ▼
       │              ┌─────────────────────┐
       │              │ chi_tiet_don_hang   │
       │              └──────────┬──────────┘
       │                         │
       │                         ▼
       │              ┌──────────────┐
       │              │  san_pham    │
       │              └───┬────┬─────┘
       │                  │    │
       │         ┌────────┘    └────────┐
       │         ▼                      ▼
       │  ┌──────────────┐      ┌──────────────┐
       │  │  danh_muc    │      │ thuong_hieu  │
       │  └──────────────┘      └──────────────┘
       │
       │         ┌──────────────┐
       └─────────┤  gio_hang    │
       │         └───────┬──────┘
       │                 │
       │                 ▼
       │         ┌──────────────────────┐
       │         │ chi_tiet_gio_hang    │
       │         └──────────┬───────────┘
       │                    │
       │                    ▼
       │         ┌──────────────┐
       │         │  san_pham    │
       │         └──────────────┘
       │
       │         ┌──────────────────────┐
       └─────────┤ danh_sach_yeu_thich  │
                 └──────────┬───────────┘
                            │
                            ▼
                 ┌──────────────┐
                 │  san_pham    │
                 └──────────────┘
```

### 3.2. Chi tiết các bảng

#### nguoi_dung
| Trường | Kiểu | Mô tả |
|--------|------|-------|
| ma_nguoi_dung | INT PK AUTO | ID người dùng |
| ten_nguoi_dung | VARCHAR(100) | Họ tên |
| email | VARCHAR(100) UNIQUE | Email đăng nhập |
| mat_khau | VARCHAR(255) | Password (MD5) |
| vai_tro | ENUM | quan_tri_vien / khach_hang |
| gioi_tinh | ENUM | nam / nu / khac |
| ngay_sinh | DATE | Ngày sinh |
| dia_chi | TEXT | Địa chỉ |
| so_dien_thoai | VARCHAR(20) | SĐT |
| hinh_dai_dien | VARCHAR(255) | Avatar path |
| trang_thai | TINYINT(1) | 1: active, 0: locked |
| ngay_tao | DATETIME | Thời gian tạo |

#### san_pham
| Trường | Kiểu | Mô tả |
|--------|------|-------|
| ma_san_pham | INT PK AUTO | ID sản phẩm |
| ten_san_pham | VARCHAR(200) | Tên |
| ma_danh_muc | INT FK | ID danh mục |
| ma_thuong_hieu | INT FK | ID thương hiệu |
| gia_ban | DECIMAL(10,2) | Giá |
| so_luong | INT | Tồn kho |
| dung_tich | VARCHAR(50) | Dung tích (ml) |
| gioi_tinh | ENUM | nam / nu / unisex |
| mo_ta | TEXT | Mô tả |
| hinh_anh | VARCHAR(255) | Đường dẫn ảnh |
| trang_thai | TINYINT(1) | 1: active, 0: deleted |
| ngay_tao | DATETIME | Thời gian tạo |

#### danh_muc
| Trường | Kiểu | Mô tả |
|--------|------|-------|
| ma_danh_muc | INT PK AUTO | ID danh mục |
| ten_danh_muc | VARCHAR(100) | Tên |
| hinh_anh | VARCHAR(255) | URL ảnh |
| mo_ta | TEXT | Mô tả |
| ngay_tao | DATETIME | Thời gian tạo |

#### thuong_hieu
| Trường | Kiểu | Mô tả |
|--------|------|-------|
| ma_thuong_hieu | INT PK AUTO | ID thương hiệu |
| ten_thuong_hieu | VARCHAR(100) | Tên |
| quoc_gia | VARCHAR(100) | Quốc gia |
| logo | VARCHAR(255) | URL logo |
| mo_ta | TEXT | Mô tả |
| trang_thai | TINYINT(1) | 1: active, 0: deleted |
| ngay_tao | DATETIME | Thời gian tạo |

#### don_hang
| Trường | Kiểu | Mô tả |
|--------|------|-------|
| ma_don_hang | INT PK AUTO | ID đơn hàng |
| ma_nguoi_dung | INT FK | ID người đặt |
| ten_nguoi_nhan | VARCHAR(100) | Tên người nhận |
| so_dien_thoai | VARCHAR(20) | SĐT nhận |
| dia_chi | TEXT | Địa chỉ giao |
| tong_tien | DECIMAL(10,2) | Tổng tiền |
| phuong_thuc_thanh_toan | VARCHAR(50) | COD/Banking/... |
| trang_thai | ENUM | Trạng thái đơn |
| ghi_chu | TEXT | Ghi chú |
| ngay_tao | DATETIME | Thời gian đặt |

## 4. TÍNH NĂNG CHI TIẾT

### 4.1. Authentication & Authorization

#### Đăng ký
- Validate email format
- Password tối thiểu 6 ký tự
- Check email trùng lặp
- Mã hóa password bằng MD5
- Tự động đăng nhập sau khi đăng ký

#### Đăng nhập
- Xác thực email và password
- Tạo session lưu thông tin user
- Redirect về trang trước đó hoặc trang chủ
- Check trạng thái tài khoản (bị khóa?)

#### Phân quyền
- Role: Admin (quan_tri_vien) và Customer (khach_hang)
- Admin: Full quyền quản trị
- Customer: Chỉ xem và mua hàng
- Middleware: require_login(), require_admin()

### 4.2. Product Management

#### Tìm kiếm và lọc
- **Tìm kiếm theo tên**: Full-text search
- **Lọc theo danh mục**: Dropdown categories
- **Lọc theo thương hiệu**: Brand filter
- **Lọc theo giới tính**: Nam/Nữ/Unisex
- **Lọc theo giá**: Min-Max price range
- **Sắp xếp**: Mới nhất, Cũ nhất, Giá tăng, Giá giảm
- **Phân trang**: 12 sản phẩm/trang

#### CRUD Admin
- **Create**: Upload ảnh, validate inputs
- **Read**: Pagination, search, filter
- **Update**: Cập nhật thông tin, thay ảnh
- **Delete**: Soft delete (trang_thai = 0)

### 4.3. Shopping Cart

#### Chức năng
- Thêm sản phẩm vào giỏ (AJAX)
- Cập nhật số lượng
- Xóa sản phẩm
- Hiển thị tổng tiền realtime
- Badge số lượng sản phẩm trong giỏ
- Persistent cart (lưu database)

#### Validation
- Check số lượng tồn kho
- Không cho thêm quá số lượng available
- Tự động cập nhật khi sản phẩm hết hàng

### 4.4. Wishlist

#### Chức năng
- Toggle thêm/xóa bằng icon tim
- AJAX không reload trang
- Badge số lượng wishlist
- Từ wishlist có thể add to cart

### 4.5. Order Management

#### Checkout Flow
1. Xem giỏ hàng
2. Click "Thanh toán"
3. Nhập thông tin giao hàng
4. Chọn phương thức thanh toán
5. Xác nhận đơn hàng
6. Tạo đơn hàng trong database
7. Clear giỏ hàng
8. Redirect đến trang đơn hàng

#### Order Status Flow
```
Chờ xử lý → Đã xác nhận → Đang giao → Đã giao
     ↓
  Đã hủy
```

#### Khách hàng
- Xem lịch sử đơn hàng
- Chi tiết từng đơn
- Hủy đơn (nếu đang chờ xử lý)
- Theo dõi trạng thái

#### Admin
- Xem tất cả đơn hàng
- Lọc theo trạng thái
- Cập nhật trạng thái
- Xem chi tiết đơn hàng

### 4.6. Dashboard Statistics

#### Metrics
- **Tổng sản phẩm**: Count active products
- **Tổng đơn hàng**: Count all orders
- **Tổng khách hàng**: Count customers
- **Doanh thu**: Sum completed orders

#### Biểu đồ
- Phân bố đơn hàng theo trạng thái (Pie/Doughnut chart)
- Doanh thu theo tháng (Line chart)

#### Danh sách
- 10 đơn hàng gần nhất
- Sản phẩm sắp hết hàng (< 10)

## 5. BẢO MẬT

### 5.1. SQL Injection Prevention
- Sử dụng MySQLi Prepared Statements
- Bind parameters cho tất cả queries
- Không concatenate user input trực tiếp vào SQL

### 5.2. XSS Prevention
- htmlspecialchars() cho tất cả output
- clean_input() function
- Validate input types

### 5.3. CSRF Protection
- generate_csrf_token()
- verify_csrf_token()
- Hidden token field trong forms

### 5.4. Password Security
- MD5 hashing (có thể nâng cấp lên bcrypt)
- Không hiển thị password trong form
- Password minimum length validation

### 5.5. Session Security
- session_start() với secure settings
- Session timeout
- Regenerate session ID sau login
- Clear session khi logout

### 5.6. File Upload Security
- Validate file type (MIME)
- Check file extension
- Limit file size (5MB)
- Rename file với unique name
- Store outside public directory (recommended)

## 6. PERFORMANCE OPTIMIZATION

### 6.1. Database
- Index trên các cột thường query (email, ma_san_pham, ma_nguoi_dung)
- Foreign keys cho referential integrity
- Soft delete thay vì hard delete

### 6.2. Frontend
- Minify CSS/JS (nếu production)
- Image optimization
- Lazy loading cho images
- CDN cho Bootstrap, jQuery, Font Awesome

### 6.3. Caching
- Browser caching cho static assets (.htaccess)
- Session caching cho user data

## 7. RESPONSIVE DESIGN

### Breakpoints
- Mobile: < 576px
- Tablet: 576px - 992px
- Desktop: > 992px

### Features
- Mobile-first approach
- Collapsible navigation
- Touch-friendly buttons
- Responsive tables
- Adaptive images

## 8. TESTING CHECKLIST

### 8.1. Functional Testing
- [ ] Đăng ký tài khoản mới
- [ ] Đăng nhập/Đăng xuất
- [ ] Tìm kiếm sản phẩm
- [ ] Lọc sản phẩm (danh mục, thương hiệu, giá)
- [ ] Sắp xếp sản phẩm
- [ ] Xem chi tiết sản phẩm
- [ ] Thêm vào giỏ hàng
- [ ] Cập nhật giỏ hàng
- [ ] Xóa khỏi giỏ hàng
- [ ] Thêm vào wishlist
- [ ] Xóa khỏi wishlist
- [ ] Checkout và đặt hàng
- [ ] Xem lịch sử đơn hàng
- [ ] Hủy đơn hàng
- [ ] Cập nhật profile
- [ ] Đổi mật khẩu
- [ ] Admin: Thêm sản phẩm
- [ ] Admin: Sửa sản phẩm
- [ ] Admin: Xóa sản phẩm
- [ ] Admin: Quản lý đơn hàng
- [ ] Admin: Cập nhật trạng thái đơn
- [ ] Admin: Quản lý danh mục
- [ ] Admin: Quản lý thương hiệu
- [ ] Admin: Quản lý người dùng
- [ ] Admin: Khóa/mở khóa tài khoản

### 8.2. Security Testing
- [ ] SQL Injection attempts
- [ ] XSS attempts
- [ ] CSRF protection
- [ ] File upload validation
- [ ] Password strength
- [ ] Session hijacking prevention

### 8.3. Performance Testing
- [ ] Page load time < 3s
- [ ] Database query optimization
- [ ] Image lazy loading
- [ ] No memory leaks

### 8.4. UI/UX Testing
- [ ] Responsive trên mobile
- [ ] Responsive trên tablet
- [ ] Consistent styling
- [ ] Form validation messages
- [ ] Error handling
- [ ] Loading indicators
- [ ] Success notifications

## 9. FUTURE ENHANCEMENTS

### Ngắn hạn
- [ ] Email verification khi đăng ký
- [ ] Forgot password functionality
- [ ] Product reviews và ratings
- [ ] Advanced search với filters nhiều hơn
- [ ] Export orders to Excel/PDF

### Trung hạn
- [ ] Payment gateway integration (VNPay, Momo)
- [ ] Email notifications cho order status
- [ ] SMS notifications
- [ ] Coupon và discount codes
- [ ] Product recommendations
- [ ] Recently viewed products

### Dài hạn
- [ ] Multi-language support (EN/VI)
- [ ] Mobile app (React Native/Flutter)
- [ ] Live chat support
- [ ] Social media login (Facebook, Google)
- [ ] Advanced analytics dashboard
- [ ] Inventory management system
- [ ] Shipping integration với đối tác vận chuyển

## 10. DEPLOYMENT

### Production Checklist
- [ ] Đổi password database mạnh hơn
- [ ] Enable HTTPS (SSL certificate)
- [ ] Đổi MD5 sang bcrypt/Argon2
- [ ] Enable error logging (không hiển thị errors ra ngoài)
- [ ] Backup database định kỳ
- [ ] Set proper file permissions
- [ ] Configure .htaccess security headers
- [ ] Minify CSS/JS
- [ ] Optimize images
- [ ] Enable Gzip compression
- [ ] Set up CDN
- [ ] Configure caching
- [ ] Monitor server resources

### Server Requirements
- Apache 2.4+
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- mod_rewrite enabled
- GD Library cho image processing
- SSL certificate

---

## PHỤ LỤC

### A. Constants Defined
```php
// config/config.php
BASE_URL                 // Root URL của project
ASSETS_URL              // URL thư mục assets
UPLOAD_PATH             // Đường dẫn upload files
UPLOAD_URL              // URL truy cập uploaded files
PRODUCTS_PER_PAGE       // Số sản phẩm/trang (12)
ORDERS_PER_PAGE         // Số đơn hàng/trang (10)
ROLE_ADMIN              // 'quan_tri_vien'
ROLE_CUSTOMER           // 'khach_hang'
ORDER_STATUS_PENDING    // 'cho_xu_ly'
ORDER_STATUS_CONFIRMED  // 'da_xac_nhan'
ORDER_STATUS_SHIPPING   // 'dang_giao'
ORDER_STATUS_DELIVERED  // 'da_giao'
ORDER_STATUS_CANCELLED  // 'da_huy'
```

### B. Helper Functions
```php
clean_input($data)              // Sanitize input
redirect($url)                  // Redirect to URL
is_logged_in()                 // Check if user logged in
is_admin()                     // Check if user is admin
require_login()                // Require login or redirect
require_admin()                // Require admin or redirect
format_currency($amount)       // Format to VNĐ
format_date($date)            // Format datetime
set_message($type, $message)  // Set session message
get_message($type)            // Get and clear message
generate_csrf_token()         // Generate CSRF token
verify_csrf_token($token)     // Verify CSRF token
paginate($total, $page, $perPage) // Calculate pagination
upload_product_image($file)   // Upload product image
delete_product_image($filename) // Delete product image
```

### C. Session Variables
```php
$_SESSION['user_id']          // ID người dùng
$_SESSION['username']         // Tên người dùng
$_SESSION['user_role']        // Vai trò (admin/customer)
$_SESSION['success']          // Success message
$_SESSION['error']            // Error message
$_SESSION['warning']          // Warning message
$_SESSION['info']             // Info message
$_SESSION['csrf_token']       // CSRF protection token
```

---

**Tài liệu này được tạo để hỗ trợ việc phát triển, bảo trì và mở rộng dự án Website Bán Nước Hoa.**

© 2024 Perfume Shop Development Team
