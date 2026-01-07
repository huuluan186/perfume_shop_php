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
