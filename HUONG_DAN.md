# HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG WEBSITE BÁN NƯỚC HOA

## BƯỚC 1: CÀI ĐẶT

### 1.1. Yêu cầu hệ thống
- XAMPP (hoặc bất kỳ web server nào hỗ trợ PHP + MySQL)
- PHP 8.0 trở lên
- MySQL 5.7 trở lên
- Trình duyệt web hiện đại (Chrome, Firefox, Edge, Safari)

### 1.2. Cài đặt dự án

#### Bước 1: Copy project
- Copy thư mục `perfume_shop_php` vào `C:\xampp\htdocs\`
- Đường dẫn đầy đủ: `C:\xampp\htdocs\perfume_shop_php\`

#### Bước 2: Tạo database
1. Khởi động XAMPP Control Panel
2. Start Apache và MySQL
3. Mở phpMyAdmin: `http://localhost/phpmyadmin`
4. Tạo database mới tên: `shop_nuoc_hoa`
5. Import file SQL:
   - Click vào database `shop_nuoc_hoa`
   - Chọn tab "Import"
   - Click "Choose File" và chọn file `db/shop_nuoc_hoa.sql`
   - Click "Go" để import
6. Import dữ liệu mẫu (tùy chọn):
   - Chọn tab "SQL"
   - Copy nội dung file `db/sample_users.sql` và paste vào
   - Click "Go"

#### Bước 3: Cấu hình (nếu cần)
- Mở file `config/database.php`
- Điều chỉnh thông tin kết nối nếu cần:
  ```php
  private $host = "localhost";
  private $db_name = "shop_nuoc_hoa";
  private $username = "root";
  private $password = "";
  ```

#### Bước 4: Kiểm tra cấu trúc thư mục
- Đảm bảo thư mục `assets/products/` tồn tại và có quyền ghi (777 trên Linux)
- Nếu chưa có, tạo thư mục: `mkdir assets/products`

#### Bước 5: Truy cập website
- Mở trình duyệt và truy cập: `http://localhost/perfume_shop_php/`

## BƯỚC 2: TÀI KHOẢN MẪU

### Tài khoản Admin
```
Email: admin@perfumeshop.com
Password: admin123
```

### Tài khoản User (Khách hàng)
```
User 1:
Email: user@example.com
Password: user123

User 2:
Email: user2@example.com
Password: user123
```

## BƯỚC 3: HƯỚNG DẪN SỬ DỤNG

### 3.1. Dành cho Khách hàng

#### Đăng ký tài khoản mới
1. Click "Đăng nhập/Đăng ký" ở góc phải trên
2. Click "Đăng ký ngay"
3. Điền đầy đủ thông tin:
   - Họ tên
   - Email (phải là email hợp lệ)
   - Mật khẩu (tối thiểu 6 ký tự)
   - Xác nhận mật khẩu
   - Giới tính
   - Ngày sinh
4. Click "Đăng ký"

#### Đăng nhập
1. Click "Đăng nhập/Đăng ký"
2. Nhập email và mật khẩu
3. Click "Đăng nhập"

#### Xem và tìm kiếm sản phẩm
1. **Trang chủ**: Hiển thị sản phẩm nổi bật và mới nhất
2. **Menu Sản Phẩm**: 
   - Click "Sản phẩm" để xem tất cả
   - Hoặc chọn danh mục cụ thể từ dropdown
3. **Tìm kiếm**: 
   - Nhập từ khóa vào ô tìm kiếm ở header
   - Click nút tìm kiếm
4. **Lọc sản phẩm**:
   - Theo danh mục
   - Theo thương hiệu
   - Theo giới tính
   - Theo khoảng giá
   - Sắp xếp: Mới nhất, Cũ nhất, Giá tăng, Giá giảm

#### Xem chi tiết sản phẩm
1. Click vào sản phẩm bất kỳ
2. Xem:
   - Hình ảnh sản phẩm
   - Tên, giá, thương hiệu
   - Mô tả chi tiết
   - Dung tích
   - Giới tính phù hợp
   - Số lượng còn lại
   - Sản phẩm liên quan

#### Thêm vào giỏ hàng
1. Ở trang chi tiết sản phẩm
2. Chọn số lượng
3. Click "Thêm vào giỏ hàng"
4. Hoặc click icon giỏ hàng ở danh sách sản phẩm

#### Quản lý giỏ hàng
1. Click icon giỏ hàng ở header (hiển thị số lượng)
2. Xem danh sách sản phẩm trong giỏ
3. Cập nhật số lượng
4. Xóa sản phẩm không cần
5. Click "Thanh toán" khi sẵn sàng

#### Đặt hàng
1. Từ trang giỏ hàng, click "Thanh toán"
2. Kiểm tra thông tin giao hàng:
   - Họ tên người nhận
   - Số điện thoại
   - Địa chỉ giao hàng
3. Chọn phương thức thanh toán
4. Kiểm tra lại đơn hàng
5. Click "Đặt hàng"
6. Nhận mã đơn hàng

#### Danh sách yêu thích
1. Click icon trái tim ở sản phẩm để thêm vào yêu thích
2. Click icon trái tim ở header để xem danh sách
3. Click lại icon trái tim để xóa khỏi danh sách

#### Quản lý tài khoản
1. Click vào tên người dùng ở góc phải
2. Chọn "Tài khoản của tôi"
3. **Thông tin cá nhân**:
   - Xem và cập nhật thông tin
   - Thay đổi avatar
   - Cập nhật địa chỉ, số điện thoại
4. **Đơn hàng của tôi**:
   - Xem lịch sử đơn hàng
   - Theo dõi trạng thái
   - Xem chi tiết đơn hàng
   - Hủy đơn hàng (nếu đang chờ xử lý)
5. **Đổi mật khẩu**:
   - Nhập mật khẩu cũ
   - Nhập mật khẩu mới
   - Xác nhận mật khẩu mới
   - Lưu thay đổi

### 3.2. Dành cho Quản trị viên

#### Truy cập Admin Panel
1. Đăng nhập bằng tài khoản admin
2. Click "Quản trị" ở menu header
3. Hoặc truy cập trực tiếp: `http://localhost/perfume_shop_php/views/admin/dashboard.php`

#### Dashboard
- Xem tổng quan:
  - Tổng số sản phẩm
  - Tổng số đơn hàng
  - Tổng số người dùng
  - Tổng doanh thu
- Biểu đồ phân bố đơn hàng theo trạng thái
- Danh sách đơn hàng gần đây
- Danh sách sản phẩm sắp hết hàng

#### Quản lý sản phẩm
1. **Xem danh sách**: Click "Quản lý sản phẩm" trong sidebar
2. **Thêm mới**:
   - Click "Thêm sản phẩm mới"
   - Điền thông tin:
     * Tên sản phẩm (bắt buộc)
     * Danh mục (chọn từ dropdown)
     * Thương hiệu (chọn từ dropdown)
     * Giá bán (số dương)
     * Số lượng
     * Dung tích (ml)
     * Giới tính
     * Mô tả chi tiết
     * Upload hình ảnh (JPG, PNG, GIF, WEBP, max 5MB)
   - Click "Lưu sản phẩm"
3. **Chỉnh sửa**:
   - Click icon "Edit" ở sản phẩm cần sửa
   - Cập nhật thông tin
   - Upload ảnh mới nếu muốn thay đổi
   - Click "Cập nhật sản phẩm"
4. **Xóa**:
   - Click icon "Delete"
   - Xác nhận xóa
   - Sản phẩm sẽ bị xóa mềm (soft delete)

#### Quản lý đơn hàng
1. **Xem danh sách**: Click "Quản lý đơn hàng"
2. **Lọc đơn hàng**:
   - Theo trạng thái
   - Tìm kiếm theo mã đơn, tên khách hàng
3. **Xem chi tiết**:
   - Click "Xem chi tiết"
   - Modal hiển thị:
     * Thông tin khách hàng
     * Địa chỉ giao hàng
     * Danh sách sản phẩm
     * Tổng tiền
4. **Cập nhật trạng thái**:
   - Click "Cập nhật trạng thái"
   - Chọn trạng thái mới:
     * Chờ xử lý
     * Đã xác nhận
     * Đang giao hàng
     * Đã giao hàng
     * Đã hủy
   - Click "Cập nhật"

#### Quản lý danh mục
1. **Xem danh sách**: Click "Quản lý danh mục"
2. **Thêm mới**:
   - Click "Thêm danh mục mới"
   - Nhập:
     * Tên danh mục
     * URL hình ảnh
     * Mô tả
   - Click "Lưu danh mục"
3. **Chỉnh sửa**: Click "Edit" và cập nhật thông tin
4. **Xóa**: Click "Delete" và xác nhận

#### Quản lý thương hiệu
1. **Xem danh sách**: Click "Quản lý thương hiệu"
2. **Thêm mới**:
   - Click "Thêm thương hiệu mới"
   - Nhập:
     * Tên thương hiệu
     * Quốc gia
     * URL logo
     * Mô tả
   - Click "Lưu thương hiệu"
3. **Chỉnh sửa**: Click "Edit"
4. **Xóa**: Click "Delete" (soft delete)

#### Quản lý người dùng
1. **Xem danh sách**: Click "Quản lý người dùng"
2. **Xem thông tin**:
   - ID, Tên, Email
   - Giới tính, Ngày sinh
   - Vai trò (Admin/Khách hàng)
   - Trạng thái (Hoạt động/Khóa)
3. **Khóa/Mở khóa tài khoản**:
   - Click nút "Khóa"/"Mở khóa"
   - Tài khoản bị khóa không thể đăng nhập
   - Không thể khóa chính mình

## BƯỚC 4: CẤU TRÚC DATABASE

### Bảng nguoi_dung
- ma_nguoi_dung (Primary Key)
- ten_nguoi_dung
- email (Unique)
- mat_khau (MD5)
- vai_tro (quan_tri_vien/khach_hang)
- gioi_tinh (nam/nu/khac)
- ngay_sinh
- dia_chi
- so_dien_thoai
- hinh_dai_dien
- trang_thai (1: active, 0: locked)
- ngay_tao

### Bảng san_pham
- ma_san_pham (Primary Key)
- ten_san_pham
- ma_danh_muc (Foreign Key)
- ma_thuong_hieu (Foreign Key)
- gia_ban
- so_luong
- dung_tich
- gioi_tinh
- mo_ta
- hinh_anh
- trang_thai (1: active, 0: deleted)
- ngay_tao

### Bảng danh_muc
- ma_danh_muc (Primary Key)
- ten_danh_muc
- hinh_anh
- mo_ta
- ngay_tao

### Bảng thuong_hieu
- ma_thuong_hieu (Primary Key)
- ten_thuong_hieu
- quoc_gia
- logo
- mo_ta
- trang_thai
- ngay_tao

### Bảng don_hang
- ma_don_hang (Primary Key)
- ma_nguoi_dung (Foreign Key)
- ten_nguoi_nhan
- so_dien_thoai
- dia_chi
- tong_tien
- phuong_thuc_thanh_toan
- trang_thai (cho_xu_ly/da_xac_nhan/dang_giao/da_giao/da_huy)
- ghi_chu
- ngay_tao

### Bảng chi_tiet_don_hang
- ma_chi_tiet (Primary Key)
- ma_don_hang (Foreign Key)
- ma_san_pham (Foreign Key)
- so_luong
- don_gia
- thanh_tien

### Bảng gio_hang
- ma_gio_hang (Primary Key)
- ma_nguoi_dung (Foreign Key)
- ngay_tao

### Bảng chi_tiet_gio_hang
- ma_chi_tiet (Primary Key)
- ma_gio_hang (Foreign Key)
- ma_san_pham (Foreign Key)
- so_luong
- ngay_them

### Bảng danh_sach_yeu_thich
- ma_yeu_thich (Primary Key)
- ma_nguoi_dung (Foreign Key)
- ma_san_pham (Foreign Key)
- ngay_them

## BƯỚC 5: TROUBLESHOOTING

### Lỗi kết nối database
- Kiểm tra MySQL đã khởi động trong XAMPP chưa
- Kiểm tra thông tin kết nối trong `config/database.php`
- Kiểm tra database `shop_nuoc_hoa` đã được tạo chưa

### Lỗi upload ảnh
- Kiểm tra thư mục `assets/products/` đã tồn tại chưa
- Kiểm tra quyền ghi của thư mục
- Kiểm tra kích thước file (max 5MB)
- Kiểm tra định dạng file (chỉ chấp nhận: JPG, PNG, GIF, WEBP)

### Lỗi 404 Not Found
- Kiểm tra đường dẫn project: `C:\xampp\htdocs\perfume_shop_php\`
- Kiểm tra Apache đã khởi động chưa
- Kiểm tra BASE_URL trong `config/config.php`

### Không đăng nhập được
- Kiểm tra email và password có đúng không
- Kiểm tra tài khoản có bị khóa không (trong database)
- Xóa cache và cookie của trình duyệt

### Session timeout
- Tăng thời gian session trong `php.ini`:
  ```
  session.gc_maxlifetime = 1440
  ```

## BƯỚC 6: LIÊN HỆ VÀ HỖ TRỢ

Nếu gặp vấn đề khi cài đặt hoặc sử dụng, vui lòng liên hệ:
- Email: [your-email@example.com]
- Phone: [your-phone]

---

© 2024 Perfume Shop. All rights reserved.
