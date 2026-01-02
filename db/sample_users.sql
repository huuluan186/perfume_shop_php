-- Insert admin và user mẫu
-- Password: admin123 (MD5 hash)
INSERT INTO `nguoi_dung` (`ten_nguoi_dung`, `email`, `mat_khau`, `vai_tro`, `gioi_tinh`, `ngay_sinh`, `dia_chi`, `so_dien_thoai`, `trang_thai`) VALUES
('Admin', 'admin@perfumeshop.com', '0192023a7bbd73250516f069df18b500', 'quan_tri_vien', 'khac', '1990-01-01', 'Hà Nội, Việt Nam', '0123456789', 1);

-- Password: user123 (MD5 hash)
INSERT INTO `nguoi_dung` (`ten_nguoi_dung`, `email`, `mat_khau`, `vai_tro`, `gioi_tinh`, `ngay_sinh`, `dia_chi`, `so_dien_thoai`, `trang_thai`) VALUES
('Nguyễn Văn A', 'user@example.com', '6ad14ba9986e3615423dfca256d04e3f', 'khach_hang', 'nam', '1995-05-15', '123 Đường ABC, Quận 1, TP.HCM', '0987654321', 1),
('Trần Thị B', 'user2@example.com', '6ad14ba9986e3615423dfca256d04e3f', 'khach_hang', 'nu', '1998-08-20', '456 Đường XYZ, Quận 3, TP.HCM', '0123456788', 1);
