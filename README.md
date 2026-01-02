# Website Bán Nước Hoa - Perfume Shop

## Thông tin dự án
- **Môn học**: Lập trình Web
- **Đề tài**: Website bán nước hoa
- **Nhóm thực hiện**: [Tên nhóm]
- **Giảng viên hướng dẫn**: [Tên giảng viên]

## Công nghệ sử dụng
- PHP thuần (không sử dụng framework)
- MySQL/MariaDB
- Bootstrap 5
- jQuery
- Font Awesome

## Cấu trúc dự án (MVC Pattern)
```
perfume_shop_php/
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── products/
├── config/
│   ├── config.php
│   └── database.php
├── models/
│   ├── User.php
│   ├── Product.php
│   ├── Category.php
│   ├── Brand.php
│   ├── Order.php
│   └── Wishlist.php
├── views/
│   ├── layout/
│   ├── auth/
│   ├── products/
│   ├── cart/
│   ├── wishlist/
│   ├── account/
│   ├── admin/
│   └── brands/
├── helpers/
│   └── functions.php
├── db/
│   ├── shop_nuoc_hoa.sql
│   └── products.json
└── index.php
```

## Cài đặt

### Yêu cầu
- XAMPP (Apache, MySQL, PHP 8.x)
- Web browser hiện đại

### Hướng dẫn cài đặt
1. Clone hoặc copy project vào thư mục `htdocs` của XAMPP
2. Tạo database `shop_nuoc_hoa` trong phpMyAdmin
3. Import file `db/shop_nuoc_hoa.sql`
4. Cấu hình database trong `config/database.php` nếu cần
5. Khởi động Apache và MySQL từ XAMPP Control Panel
6. Truy cập: `http://localhost/perfume_shop_php/`

## Tính năng chính

### Người dùng
- Đăng ký, đăng nhập (mã hóa MD5)
- Xem danh sách sản phẩm (phân trang, tìm kiếm, lọc)
- Xem chi tiết sản phẩm
- Thêm vào giỏ hàng
- Danh sách yêu thích
- Quản lý tài khoản (cập nhật thông tin, đổi mật khẩu)
- Lịch sử đơn hàng
- Đặt hàng

### Quản trị viên
- Dashboard thống kê
- Quản lý người dùng
- Quản lý danh mục
- Quản lý thương hiệu
- Quản lý sản phẩm (CRUD)
- Quản lý đơn hàng
- Thống kê doanh thu

## Tài khoản mẫu
```
Admin:
Email: admin@perfumeshop.com
Password: admin123

User:
Email: user@example.com
Password: user123
```

## Ghi chú
- Sử dụng MySQLi với Prepared Statements để bảo mật
- Validation cả phía client (JavaScript) và server (PHP)
- Responsive design cho mọi thiết bị
- Session để quản lý giỏ hàng và phân quyền

## License
© 2026 - Dự án môn học Lập trình Web
