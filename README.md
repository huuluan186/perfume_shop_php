# Website Bán Nước Hoa - Perfume Shop

Đồ án môn học **Phát triển ứng dụng Web với mã nguồn mở**

Website thương mại điện tử chuyên bán nước hoa cao cấp, được xây dựng bằng PHP thuần theo mô hình MVC.

---

## 📋 Mục lục
- [Giới thiệu đề tài](#giới-thiệu-đề-tài)
- [Cấu trúc thư mục](#cấu-trúc-thư-mục)
- [Chức năng chính](#chức-năng-chính)
- [Cài đặt và sử dụng](#cài-đặt-và-sử-dụng)
- [Tài khoản quản trị](#tài-khoản-quản-trị)
- [Thông tin liên hệ](#thông-tin-liên-hệ)

---

## 🎯 Giới thiệu đề tài

**Tên đề tài:** Website Bán Nước Hoa - Perfume Shop

**Mô tả:** Hệ thống thương mại điện tử chuyên cung cấp các sản phẩm nước hoa cao cấp từ các thương hiệu nổi tiếng thế giới. Website được xây dựng theo mô hình MVC, cung cấp đầy đủ các tính năng quản lý sản phẩm, đơn hàng, và người dùng.

**Công nghệ sử dụng:**
- Backend: PHP 8.x, MySQL/MariaDB
- Frontend: HTML5, CSS3, Bootstrap 5, JavaScript, jQuery
- Kiến trúc: MVC Pattern
- Bảo mật: Password hashing, Prepared Statements, XSS protection

---

## 📁 Cấu trúc thư mục

```
perfume_shop_php/
├── assets/                 # Tài nguyên tĩnh
│   ├── css/               # File CSS
│   ├── js/                # File JavaScript
│   ├── images/            # Hình ảnh
│   └── products/          # Ảnh sản phẩm theo thương hiệu
├── config/                # Cấu hình hệ thống
│   ├── config.php         # Constants, BASE_URL
│   └── database.php       # Kết nối PDO Database
├── db/                    # Database scripts
│   └── shop_nuoc_hoa.sql  # File SQL import
├── helpers/               # Helper functions
│   └── functions.php      # Các hàm tiện ích
├── models/                # Model - Xử lý logic nghiệp vụ
│   ├── Brand.php
│   ├── Category.php
│   ├── Contact.php
│   ├── Order.php
│   ├── Product.php
│   ├── User.php
│   └── Wishlist.php
├── uploads/               # Upload files
├── views/                 # View - Giao diện
│   ├── layout/           # Header, Footer
│   ├── auth/             # Đăng nhập, Đăng ký
│   ├── products/         # Danh sách, Chi tiết SP
│   ├── cart/             # Giỏ hàng, Thanh toán
│   ├── account/          # Quản lý tài khoản
│   ├── brands/           # Thương hiệu
│   ├── wishlist/         # Danh sách yêu thích
│   ├── admin/            # Trang quản trị
│   ├── about.php
│   └── contact.php
├── index.php             # Trang chủ
└── README.md
```

---

## ⚙️ Chức năng chính

### Người dùng (User):
✅ Đăng ký/Đăng nhập tài khoản  
✅ Xem danh sách sản phẩm với phân trang (9 sản phẩm/trang)  
✅ Tìm kiếm và lọc sản phẩm (theo thương hiệu, danh mục, giá, giới tính)  
✅ Xem chi tiết sản phẩm (hình ảnh, mô tả, giá, tồn kho)  
✅ Quản lý giỏ hàng (thêm, xóa, cập nhật số lượng)  
✅ Đặt hàng và thanh toán  
✅ Xem lịch sử đơn hàng, chi tiết đơn  
✅ Hủy đơn hàng (khi trạng thái "Chờ xử lý")  
✅ Quản lý danh sách yêu thích (Wishlist)  
✅ Quản lý thông tin cá nhân  
✅ Đổi mật khẩu  
✅ Liên hệ với shop  

### Quản trị viên (Admin):
🔧 Dashboard thống kê (doanh thu, đơn hàng, sản phẩm, người dùng)  
🔧 Quản lý sản phẩm (CRUD, upload ảnh, quản lý tồn kho)  
🔧 Quản lý danh mục sản phẩm (CRUD)  
🔧 Quản lý thương hiệu (CRUD)  
🔧 Quản lý đơn hàng (xem chi tiết, cập nhật trạng thái)  
🔧 Quản lý người dùng (xem, kích hoạt/khóa tài khoản, xóa)  
🔧 Xem tin nhắn liên hệ từ khách hàng  

---

## 🚀 Cài đặt và sử dụng

### Bước 1: Cài đặt XAMPP

1. **Download XAMPP:**
   - Truy cập: https://www.apachefriends.org/
   - Tải phiên bản phù hợp với hệ điều hành (Windows/Mac/Linux)
   - Khuyến nghị: PHP 8.0 trở lên

2. **Cài đặt XAMPP:**
   - Chạy file cài đặt vừa tải
   - Chọn thư mục cài đặt (mặc định: `C:\xampp`)
   - Chọn components: Apache, MySQL, PHP, phpMyAdmin
   - Hoàn tất cài đặt

3. **Khởi động XAMPP:**
   - Mở **XAMPP Control Panel**
   - Click **Start** cho **Apache**
   - Click **Start** cho **MySQL**
   - Đợi đến khi cả 2 service hiển thị màu xanh

### Bước 2: Clone/Copy Project

```bash
# Cách 1: Clone từ Git (nếu có)
cd C:\xampp\htdocs\
git clone https://github.com/huuluan186/perfume_shop_php.git

# Cách 2: Copy thủ công
# - Copy toàn bộ thư mục project vào: C:\xampp\htdocs\perfume_shop_php
```

### Bước 3: Tạo Database

1. Mở trình duyệt, truy cập: **http://localhost/phpmyadmin**

2. **Tạo Database mới:**
   - Click **"New"** ở sidebar bên trái
   - Database name: `shop_nuoc_hoa`
   - Collation: `utf8mb4_unicode_ci`
   - Click **"Create"**

### Bước 4: Import File SQL

1. Click vào database `shop_nuoc_hoa` vừa tạo

2. Click tab **"Import"** ở menu trên

3. Click **"Choose File"** (Chọn tệp)

4. Tìm và chọn file: `perfume_shop_php/db/shop_nuoc_hoa.sql`

5. Scroll xuống dưới, click **"Import"** (Nhập)

6. Đợi đến khi xuất hiện thông báo **"Import has been successfully finished"**

### Bước 5: Cấu hình Project

Mở file `config/config.php`, kiểm tra cấu hình:

```php
// Đảm bảo BASE_URL khớp với đường dẫn project
define('BASE_URL', 'http://localhost/perfume_shop_php/');
```

### Bước 6: Truy cập Website

1. **Trang người dùng:**  
   Mở trình duyệt, truy cập: **http://localhost/perfume_shop_php**

2. **Trang quản trị:**  
   Truy cập: **http://localhost/perfume_shop_php/views/admin/dashboard.php**

---

## 🔐 Tài khoản quản trị

File SQL đã bao gồm sẵn tài khoản Admin:

**Tài khoản Admin:**
- **Email:** `admin@gmail.com`
- **Mật khẩu:** `admin123`

**Tài khoản User mẫu:** (Có thể tìm trong file `db/sample_users.sql`)

**Lưu ý bảo mật:**
- Đổi mật khẩu admin ngay sau khi cài đặt
- Không sử dụng tài khoản mặc định trong môi trường production

---

## 📞 Thông tin liên hệ

**Đồ án môn học:** Phát triển ứng dụng Web với mã nguồn mở  
**Lớp:** DA22TTA  
**Năm học:** 2025-2026

### Thành viên nhóm:

**1. PHẠM HỮU LUÂN**
- MSSV: 110122016
- GitHub: https://github.com/huuluan186
- Microsoft mail: 110122016@st.tvu.edu.vn

**2. NGUYỄN HỮU ANH**
- MSSV: 110122033
- GitHub: https://github.com/huuanh2512
- Microsoft mail: 110122033@st.tvu.edu.vn

**3. LÂM THANH ĐỈNH**
- MSSV: 110122051
- GitHub: https://github.com/LamThanhDinh
- Microsoft mail: 110122051@st.tvu.edu.vn

---

## 📝 License

© 2026 Perfume Shop. Đồ án môn học - Nhóm sinh viên DA22TTA.

**Lưu ý:** Đây là project học tập, không sử dụng cho mục đích thương mại.
   - Chọn tab **Import**
   - Click **Choose File** và chọn file `db/shop_nuoc_hoa.sql`
   - Click **Go** để import
   - Đợi đến khi hoàn tất

#### Bước 3: Cấu hình database (nếu cần)
Mở file `config/database.php` và điều chỉnh thông tin kết nối:
```php
private $host = "localhost";
private $db_name = "shop_nuoc_hoa";
private $username = "root";
private $password = ""; // Để trống với XAMPP mặc định
```

#### Bước 4: Truy cập website
- Mở trình duyệt
- Truy cập: `http://localhost/perfume_shop_php/`
- Trang chủ sẽ hiển thị danh sách sản phẩm

#### Bước 5: Đăng nhập (tùy chọn)
Sử dụng tài khoản mẫu bên dưới để đăng nhập

## Cấu trúc dự án

```
perfume_shop_php/
├── index.php                    # Trang chủ, điều hướng chính
├── 404.php                      # Trang lỗi 404
├── test.php                     # File test kết nối
├── check_images.php             # Kiểm tra hình ảnh sản phẩm
│
├── assets/                      # Tài nguyên tĩnh
│   ├── css/                     # File CSS
│   │   ├── style.css           # CSS cho người dùng
│   │   └── admin.css           # CSS cho admin
│   ├── js/                      # File JavaScript
│   │   ├── main.js             # JS chính
│   │   ├── admin.js            # JS cho admin
│   │   └── wishlist.js         # JS cho wishlist
│   ├── images/                  # Hình ảnh website
│   │   └── banners/            # Banner trang chủ
│   └── products/                # Hình ảnh sản phẩm
│       ├── Burberry/
│       ├── Chanel/
│       ├── Dior/
│       └── ... (các thương hiệu khác)
│
├── config/                      # Cấu hình hệ thống
│   ├── config.php              # Cấu hình chung
│   └── database.php            # Kết nối database
│
├── models/                      # Models (MVC)
│   ├── User.php                # Model người dùng
│   ├── Product.php             # Model sản phẩm
│   ├── Category.php            # Model danh mục
│   ├── Brand.php               # Model thương hiệu
│   ├── Order.php               # Model đơn hàng
│   ├── Contact.php             # Model liên hệ
│   └── Wishlist.php            # Model danh sách yêu thích
│
├── views/                       # Views (MVC)
│   ├── layout/                 # Layout chung
│   │   ├── header.php          # Header
│   │   └── footer.php          # Footer
│   ├── auth/                   # Xác thực
│   │   ├── login.php           # Đăng nhập
│   │   ├── logout.php          # Đăng xuất
│   │   └── register.php        # Đăng ký
│   ├── products/               # Sản phẩm
│   │   ├── index.php           # Danh sách sản phẩm
│   │   └── detail.php          # Chi tiết sản phẩm
│   ├── brands/                 # Thương hiệu
│   │   ├── index.php           # Danh sách thương hiệu
│   │   └── detail.php          # Sản phẩm theo thương hiệu
│   ├── cart/                   # Giỏ hàng
│   │   ├── index.php           # Xem giỏ hàng
│   │   ├── add.php             # Thêm vào giỏ
│   │   ├── update.php          # Cập nhật giỏ hàng
│   │   ├── remove.php          # Xóa khỏi giỏ
│   │   ├── checkout.php        # Thanh toán
│   │   └── count.php           # Đếm số lượng
│   ├── wishlist/               # Danh sách yêu thích
│   │   ├── index.php           # Xem wishlist
│   │   ├── add.php             # Thêm vào wishlist
│   │   ├── remove.php          # Xóa khỏi wishlist
│   │   └── count.php           # Đếm số lượng
│   ├── account/                # Tài khoản người dùng
│   │   ├── profile.php         # Thông tin cá nhân
│   │   ├── change-password.php # Đổi mật khẩu
│   │   ├── orders.php          # Lịch sử đơn hàng
│   │   ├── order-details.php   # Chi tiết đơn hàng
│   │   ├── get-order-details.php # API lấy chi tiết
│   │   └── cancel-order.php    # Hủy đơn hàng
│   ├── admin/                  # Quản trị
│   │   ├── dashboard.php       # Dashboard
│   │   ├── users/              # Quản lý người dùng
│   │   ├── categories/         # Quản lý danh mục
│   │   ├── brands/             # Quản lý thương hiệu
│   │   ├── products/           # Quản lý sản phẩm
│   │   ├── orders/             # Quản lý đơn hàng
│   │   ├── contacts/           # Quản lý liên hệ
│   │   └── layout/             # Layout admin
│   ├── about.php               # Giới thiệu
│   └── contact.php             # Liên hệ
│
├── helpers/                     # Helper functions
│   └── functions.php           # Các hàm tiện ích
│
├── db/                         # Database
│   ├── shop_nuoc_hoa.sql      # File SQL chính
│   └── sample_users.sql       # Dữ liệu mẫu
│
├── logs/                       # Log files
│   └── contact_2026-01.txt    # Log liên hệ
│
└── uploads/                    # Thư mục upload (dự trữ)
```

## Tài khoản mẫu

### Tài khoản Admin
```
Email: admin@perfumeshop.com
Password: admin123
```
**Quyền hạn:** Truy cập đầy đủ tất cả chức năng quản trị

### Tài khoản User
```
Email: user@example.com
Password: user123
```
**Quyền hạn:** Mua sắm và quản lý đơn hàng cá nhân

## Hướng dẫn sử dụng

### Dành cho Người dùng

#### Đăng ký tài khoản
1. Click **Đăng ký** trên header
2. Điền thông tin: Họ tên, Email, Mật khẩu
3. Click **Đăng ký**

#### Mua sắm
1. Tìm kiếm sản phẩm bằng thanh tìm kiếm hoặc lọc theo danh mục/thương hiệu
2. Click vào sản phẩm để xem chi tiết
3. Chọn số lượng và click **Thêm vào giỏ hàng**
4. Click icon giỏ hàng để xem
5. Click **Thanh toán** để đặt hàng
6. Điền thông tin giao hàng và xác nhận

#### Quản lý đơn hàng
1. Click **Tài khoản** > **Đơn hàng của tôi**
2. Xem danh sách đơn hàng và trạng thái
3. Click **Chi tiết** để xem thông tin đầy đủ
4. Có thể hủy đơn nếu đơn còn ở trạng thái "Chờ xử lý"

### Dành cho Quản trị viên

#### Truy cập Admin Panel
1. Đăng nhập bằng tài khoản admin
2. Click **Admin** trên header
3. Dashboard sẽ hiển thị thống kê tổng quan

#### Quản lý sản phẩm
1. **Admin** > **Sản phẩm**
2. Click **Thêm sản phẩm mới** để tạo sản phẩm
3. Điền thông tin: Tên, Mô tả, Giá, Danh mục, Thương hiệu
4. Upload hình ảnh
5. Click **Lưu**

#### Quản lý đơn hàng
1. **Admin** > **Đơn hàng**
2. Click **Chi tiết** để xem thông tin đơn hàng
3. Cập nhật trạng thái: Chờ xử lý → Đang xử lý → Đã giao
4. Click **Cập nhật** để lưu

## Bảo mật

- Mật khẩu được mã hóa bằng MD5
- Sử dụng Prepared Statements để phòng chống SQL Injection
- Validation dữ liệu đầu vào ở cả client và server
- Session-based authentication
- CSRF protection cho các form quan trọng

## Ghi chú

- Responsive design hoạt động tốt trên mọi thiết bị (Desktop, Tablet, Mobile)
- Hỗ trợ tìm kiếm và phân trang
- Hình ảnh sản phẩm được tổ chức theo thư mục thương hiệu
- Log liên hệ được lưu theo tháng trong thư mục `logs/`

## Khắc phục sự cố

### Không kết nối được database
- Kiểm tra MySQL đã khởi động trong XAMPP chưa
- Kiểm tra thông tin kết nối trong `config/database.php`
- Đảm bảo database `shop_nuoc_hoa` đã được tạo và import dữ liệu

### Không hiển thị hình ảnh sản phẩm
- Kiểm tra thư mục `assets/products/` có tồn tại không
- Đảm bảo đường dẫn hình ảnh trong database đúng
- Chạy file `check_images.php` để kiểm tra

### Lỗi 404
- Kiểm tra đường dẫn project: phải là `htdocs/perfume_shop_php/`
- Xóa cache trình duyệt và thử lại

## License

Project này được tạo ra cho mục đích học tập.

## Liên hệ

Nếu có bất kỳ câu hỏi nào, vui lòng liên hệ qua form liên hệ trên website.

---

© 2026 Perfume Shop - Website bán nước hoa cao cấp
- Session để quản lý giỏ hàng và phân quyền

## License
© 2026 - Dự án môn học Lập trình Web
