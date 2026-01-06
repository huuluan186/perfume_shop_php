# Website Bán Nước Hoa - Perfume Shop

Website thương mại điện tử chuyên bán nước hoa cao cấp, được xây dựng bằng PHP thuần theo mô hình MVC.

## Mục lục
- [Công nghệ sử dụng](#công-nghệ-sử-dụng)
- [Tính năng](#tính-năng)
- [Cài đặt](#cài-đặt)
- [Cấu trúc dự án](#cấu-trúc-dự-án)
- [Tài khoản mẫu](#tài-khoản-mẫu)
- [Hướng dẫn sử dụng](#hướng-dẫn-sử-dụng)

## Công nghệ sử dụng

### Backend
- **PHP 8.x** - Ngôn ngữ lập trình chính
- **MySQL/MariaDB** - Hệ quản trị cơ sở dữ liệu
- **MySQLi** - Extension kết nối database với Prepared Statements

### Frontend
- **HTML5 & CSS3** - Cấu trúc và giao diện
- **Bootstrap 5** - Framework CSS responsive
- **JavaScript (ES6+)** - Xử lý tương tác
- **jQuery** - Thư viện JavaScript
- **Font Awesome** - Icon library

### Kiến trúc
- **MVC Pattern** - Mô hình phân tầng
- **RESTful API** - Các endpoint AJAX
- **Session Management** - Quản lý phiên đăng nhập

## Tính năng

### Dành cho Người dùng
- Đăng ký tài khoản mới
- Đăng nhập/Đăng xuất (mã hóa mật khẩu MD5)
- Xem danh sách sản phẩm với phân trang
- Tìm kiếm và lọc sản phẩm (theo danh mục, thương hiệu, giá)
- Xem chi tiết sản phẩm (hình ảnh, mô tả, giá)
- Quản lý giỏ hàng (thêm, xóa, cập nhật số lượng)
- Danh sách yêu thích (Wishlist)
- Đặt hàng và thanh toán
- Xem lịch sử đơn hàng
- Quản lý tài khoản cá nhân
- Đổi mật khẩu
- Hủy đơn hàng (nếu đơn còn chờ xử lý)
- Liên hệ với shop

### Dành cho Quản trị viên
- Dashboard với thống kê tổng quan:
  - Tổng doanh thu
  - Số lượng đơn hàng
  - Số lượng sản phẩm
  - Số lượng người dùng
- Quản lý người dùng:
  - Xem danh sách
  - Phân quyền (Admin/User)
  - Kích hoạt/Vô hiệu hóa tài khoản
  - Xóa tài khoản
- Quản lý danh mục sản phẩm (CRUD)
- Quản lý thương hiệu (CRUD)
- Quản lý sản phẩm:
  - Thêm/Sửa/Xóa sản phẩm
  - Upload hình ảnh
  - Quản lý tồn kho
  - Thiết lập giá
- Quản lý đơn hàng:
  - Xem chi tiết đơn hàng
  - Cập nhật trạng thái
  - Xóa đơn hàng
- Xem tin nhắn liên hệ từ khách hàng
- Báo cáo và thống kê

## Cài đặt

### Yêu cầu hệ thống
- **XAMPP** (Apache 2.4+, MySQL 5.7+, PHP 8.0+)
- Trình duyệt web hiện đại (Chrome, Firefox, Edge, Safari)
- 100MB dung lượng trống

### Hướng dẫn cài đặt

#### Bước 1: Chuẩn bị project
```bash
# Clone hoặc copy project vào thư mục htdocs
cd C:\xampp\htdocs\
# Copy toàn bộ thư mục perfume_shop_php vào đây
```

#### Bước 2: Tạo và import database
1. Khởi động **XAMPP Control Panel**
2. Start **Apache** và **MySQL**
3. Mở trình duyệt và truy cập: `http://localhost/phpmyadmin`
4. Tạo database mới:
   - Click **New** trên sidebar
   - Database name: `shop_nuoc_hoa`
   - Collation: `utf8mb4_unicode_ci`
   - Click **Create**
5. Import dữ liệu:
   - Click vào database `shop_nuoc_hoa` vừa tạo
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
