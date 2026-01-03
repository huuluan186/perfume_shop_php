-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 02, 2026 lúc 11:53 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shop_nuoc_hoa`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `id` int(11) NOT NULL,
  `id_don_hang` int(11) DEFAULT NULL,
  `id_san_pham` int(11) DEFAULT NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`id`, `id_don_hang`, `id_san_pham`, `so_luong`, `don_gia`) VALUES
(1, 1, 2, 1, 385000.00),
(2, 2, 15, 2, 1500000.00),
(3, 2, 51, 1, 3050000.00),
(4, 2, 49, 1, 4250000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_gio_hang`
--

CREATE TABLE `chi_tiet_gio_hang` (
  `id` int(11) NOT NULL,
  `id_gio_hang` int(11) DEFAULT NULL,
  `id_san_pham` int(11) DEFAULT NULL,
  `so_luong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `id` int(11) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL,
  `mo_ta` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`id`, `ten_danh_muc`, `mo_ta`) VALUES
(1, 'Nước hoa nam', NULL),
(2, 'Nước hoa nữ', NULL),
(3, 'Nước hoa unisex', NULL),
(4, 'Gift set', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_sach_yeu_thich`
--

CREATE TABLE `danh_sach_yeu_thich` (
  `id` int(11) NOT NULL,
  `id_nguoi_dung` int(11) DEFAULT NULL,
  `id_san_pham` int(11) DEFAULT NULL,
  `ngay_them` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_sach_yeu_thich`
--

INSERT INTO `danh_sach_yeu_thich` (`id`, `id_nguoi_dung`, `id_san_pham`, `ngay_them`) VALUES
(2, 1, 44, '2026-01-02 17:24:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `id` int(11) NOT NULL,
  `id_nguoi_dung` int(11) DEFAULT NULL,
  `ho_ten_nguoi_nhan` varchar(150) DEFAULT NULL,
  `so_dien_thoai_nhan` varchar(20) DEFAULT NULL,
  `dia_chi_giao_hang` text DEFAULT NULL,
  `tong_tien` decimal(12,2) DEFAULT NULL,
  `trang_thai` int(20) NOT NULL DEFAULT 0,
  `ngay_dat` datetime DEFAULT current_timestamp(),
  `ngay_xoa` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`id`, `id_nguoi_dung`, `ho_ten_nguoi_nhan`, `so_dien_thoai_nhan`, `dia_chi_giao_hang`, `tong_tien`, `trang_thai`, `ngay_dat`, `ngay_xoa`) VALUES
(1, 1, 'Hữu Luân', '0386291762', 'aps do tRà vinh', 415000.00, 0, '2026-01-02 16:41:28', NULL),
(2, 1, 'Hữu Luân', '0386291762', 'asaadsf gfgf', 10300000.00, 4, '2026-01-02 17:17:14', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gio_hang`
--

CREATE TABLE `gio_hang` (
  `id` int(11) NOT NULL,
  `id_nguoi_dung` int(11) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `gioi_tinh` varchar(10) DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `vai_tro` varchar(20) DEFAULT 'khach_hang',
  `trang_thai` tinyint(4) DEFAULT 1,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `ngay_xoa` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `username`, `gioi_tinh`, `ngay_sinh`, `email`, `password`, `vai_tro`, `trang_thai`, `ngay_tao`, `ngay_xoa`) VALUES
(1, 'luan2', '', '2025-12-08', 'luanphamhuu2004@gmail.com', 'bbb8aae57c104cda40c93843ad5e6db8', 'khach_hang', 1, '2026-01-01 20:10:23', NULL),
(2, 'admin', NULL, NULL, 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'quan_tri_vien', 1, '2026-01-02 17:31:02', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `id` int(11) NOT NULL,
  `ten_san_pham` varchar(200) NOT NULL,
  `gia_ban` decimal(12,2) NOT NULL,
  `dung_tich_ml` int(11) DEFAULT NULL,
  `nhom_huong` varchar(150) DEFAULT NULL,
  `gioi_tinh_phu_hop` varchar(20) DEFAULT NULL,
  `phong_cach` varchar(100) DEFAULT NULL,
  `xuat_xu` varchar(20) DEFAULT NULL,
  `nam_phat_hanh` int(11) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `duong_dan_hinh_anh` varchar(255) DEFAULT NULL,
  `so_luong_ton` int(11) DEFAULT 0,
  `id_danh_muc` int(11) DEFAULT NULL,
  `id_thuong_hieu` int(11) DEFAULT NULL,
  `ngay_xoa` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`id`, `ten_san_pham`, `gia_ban`, `dung_tich_ml`, `nhom_huong`, `gioi_tinh_phu_hop`, `phong_cach`, `xuat_xu`, `nam_phat_hanh`, `mo_ta`, `duong_dan_hinh_anh`, `so_luong_ton`, `id_danh_muc`, `id_thuong_hieu`, `ngay_xoa`) VALUES
(1, 'Burberry Hero', 2300000.00, 50, 'Gỗ tuyết tùng, Cây bách xù, Cam bergamot', 'nam', 'Nam tính, Mạnh mẽ, Tự do', 'Anh', 2021, 'Thương hiệu Burberry cho ra mắt dòng nước hoa mới mang tên Hero vào năm 2021 với nhiều hứa hẹn đây sẽ là một trong những chai nước hoa vô cùng được yêu thích bởi cánh mày râu.<br>Burberry Hero mở ra với tông mùi tươi mát của cam Bergamot, mang lại cảm giác tràn đầy sức sống của những ngày đầu xuân và có đôi chút lành lạnh trong những cơn gió của thành phố London. Hương xanh ấy sẽ được cộng hưởng thêm với những quả Bách Xù mọng nước. Xanh và tươi nhưng vẫn không thể thiếu đi được những nét nam tính mạnh mẽ nhờ có Tiêu đen. Hương cay này là một trong những nét hương nổi bật và đặc trưng có trong mỗi dòng nước hoa của thương hiệu Burberry.<br>Ngôi sao chính của Burberry Hero không phải ở tầng hương giữa mà la ở tầng hương cuối. Tổ hợp gỗ Tuyết Tùng đến từ nhiều địa danh nổi tiếng như Virginia, đỉnh Atlas và đỉnh Himalaya, nâng cấp độ nam tính, mạnh mẽ của mùi hương lên gấp bội. Burberry Hero chắc chắn sẽ thể hiện bản lĩnh của một người đàn ông dám nghĩ dám làm, sống đúng với bản chất và màu sắc riêng của chính mình.', 'products/Burberry/Burberry%20Hero/burberry-hero-edt.jpg', 30, 1, 5, NULL),
(2, 'Bvlgari Omnia Crystalline EDT', 385000.00, 10, 'Hoa Cỏ Và Biển Cả', 'nữ', 'Bí ẩn, Gợi cảm, Tươi sáng', 'Ý', 2005, 'Bvlgari Omnia Crystalline EDT mang lại cho người dùng một hương thơm tươi mát và nhẹ nhàng, đồng thời cũng thể hiện được sự cá tính với mùi hương đầu tiên đem lại cho bạn sự trẻ trung và nữ tính, với hương của trúc và lê mang đến cảm giác xanh ngắt, tươi mát. Tiếp đó, hương hoa sen dịu dàng và êm ái khiến nàng thư thái, dễ chịu. Hương giữa còn kết hợp với vị cay nồng ấm của cây bã đậu và sự tươi tắn của Cassia. Qua đó tạo nên nét nữ tính và mềm mại của Omnia Crystalline.', 'products/Bvlgari/Bvlgari%20Omnia%20Crystalline%20EDT/bvlgari-omnia-crystalline-eau-de-toilette.jpg', 19, 2, 15, NULL),
(3, 'Bvlgari Splendida Rose Rose For Women EDP', 2250000.00, 100, 'Hoa hồng Đan Mạch, Quả mâm xôi, Xạ hương', 'nữ', 'Nhẹ nhàng, Nữ tính, Thanh lịch', 'Ý', 2017, 'Được mệnh danh là bàn tay đầy ma thuật, Sophie Labbe - chủ nhân của nhiều bộ sưu tập nước hoa nổi tiếng cho không dưới 60 hãng thời trang nổi tiếng - đã cho ra đời Splendia Rose Rose Bvlgari vào năm 2017 như một lần nữa khẳng định tên tuổi của bà.<br> Như một bài ca Nữ quyền, Splendia Rose Rose toát lên vẻ kiêu kỳ, tự tin và đầy phóng khoáng tượng trưng cho phái nữ ở xã hội hiện đại ngày nay khi bắt đầu với tông hương thanh mát, tinh nghịch từ Cam quýt và Dâu đen mọng nước.<br>Ở tầng hương thứ hai, khi Sophie Labbe thể hiện cái tôi của bà bằng cách sử dụng độc một mùi hương - Hoa hồng - cũng chính là nhân vật chính tạo nên cái tên nước hoa này. Độc lập, quyến rũ với hương thơm kích thích đến lạ, Splendia Rose Rose là một vũ khí cho bất kỳ người phụ nữ nào sở hữu được nó.<br>Đến cuối ngày, cô nàng kiêu kỳ của chúng ta cũng để lộ ra một trái tim ấm áp, được hun nóng bằng Gỗ vetiver, Gỗ bạch đàn hương và không kém tinh tế với Xạ hương cộng hưởng cùng Hoắc hương.', 'products/Bvlgari/Bvlgari%20Splendida%20Rose%20Rose%20For%20Women%20EDP/bvlgari-splendida-rose-rose-eau-de-parfum.jpg', 20, 2, 15, NULL),
(4, 'Carolina Herrera Good Girl EDP', 430000.00, 7, 'Đậu tonka, Ca cao, Hoa huệ, Hoa nhài', 'nữ', 'Quyến rũ, Nữ tính, Ngọt ngào', 'Tây Ban Nha', 2016, 'Một sản phẩm không thể thiếu trên kệ tủ của bất kì cô gái sành điệu nào, Caroline Herrera Good Girl Eau de Parfum là một chiếc guốc sang trọng, biểu tượng của một quý cô ngọt ngào, thanh lịch và cũng rất mạnh mẽ.<br>Mùi hương của chai nước hoa được tạo nên bởi những điều tương phản tưởng chừng khó thể nào sánh đôi, nhưng chính những điều đó lại đem đến một sự hòa quyện hoàn hảo đến bất ngờ. Hãy tưởng tượng mà xem, Good Girl là tất thảy những sự ngọt ngào và êm dịu trên thế giới này được tổng hợp và chiết xuất lại. Tầng hương đầu tiên là mùi của Hạnh nhân ngọt bùi, cái mát mẻ của Cam Bergamot và Quả chanh vàng. Hương thơm tự nhiên, tao nhã đan xen mãnh liệt ngọt đắng tiến thẳng vào khứu giác, thúc tiến tinh thần ta và khơi gợi niềm cảm hứng cho một hành trình mới.<br>Tầng hương giữa lại đem đến một cảm giác ngọt ngào nhưng kiêu kỳ, toả sáng bởi sự kết hợp của những loài hoa khác nhau. Hương kem beo béo của Huệ hoà quyện với Hoa nhài Sambac tươi tắn, tất thảy làm nên hương thơm nữ tính nhưng không hề ủy mị, dịu dàng mà vẫn toát lên vẻ thanh lịch của Good Girl. Và rồi tầng hương cuối nào là Đậu Tonka, Vanilla hay kẹo Praline làm cho hương thơm béo ngậy, trầm ấm, có chút thoảng Hương Gỗ đầy lắng đọng, vấn vương.', 'products/Carolina%20Herrera/Carolina%20Herrera%20Good%20Girl%20EDP/carolina-herrera-good-girl-eau-de-parfum-80ml.jpg', 20, 2, 29, NULL),
(5, 'Chanel Allure Homme EDT', 2880000.00, 50, 'Hương vani, Quả chanh vàng', 'nam', 'Tinh tế, Quyến rũ, Lãng mạn', 'Pháp', 1999, 'Sự quyến rũ hay tốc độ của anh ấy rất quyết đoán, nhưng lại pha chút nhẹ nhàng. Thành phần được tạo ra từ bốn dòng hương chính: sự tươi mát của màu xanh lá cây, sự sắc nét ấm áp của hạt tiêu, sức mạnh và sự sang trọng của gỗ, và sự gợi cảm ấm áp của Benzoin và tonka.<br>Không có đường nét chính nào có thể được cảm nhận một cách riêng biệt, chúng cùng nhau tạo nên một tổng thể hài hòa. Bằng cách kết hợp những thành phần phức tạp, tạo nên một Chanel Allure Homme Eau de Toilette đã ghi dấu ấn vượt thời gian trong hơn 2 thập kỷ qua.', 'products/Chanel/Chanel%20Allure%20Homme%20EDT/chanel-allure-homme-eau-de-toilette-50ml.jpg', 20, 1, 2, NULL),
(6, 'Chanel Bleu de Chanel EDT', 3100000.00, 50, 'Bưởi, Hương nhang, Gừng, Chang vàng', 'nam', 'Sang trọng, Gợi cảm, Lịch lãm', 'Pháp', 2010, 'Chanel Bleu de Chanel đã đem tới thế giới hương thơm một mùi hương dễ chịu. Cam chanh mở màn bằng nét đặc trưng của nốt hương Bưởi, tươi sáng mọng nước cùng Gừng thanh lịch sạch sẽ, rồi nhẹ nhàng thoang thoảng chút hương hoa. Gừng của Bleu de Chanel rõ ràng, dễ gần, đẹp và nịnh mũi. Lắng dần xuống, mùi hương của Bleu de Chanel trở nên dày và có chiều sâu hơn với Nhang mềm ngọt, cùng sự rắn chắc vững chãi của hương Gỗ, qua hương thơm từ Cỏ hương bài, Gỗ tuyết tùng và Đàn hương.<br>Bạn thấy chứ? Không cần bàn quá nhiều về mùi hương của Bleu de Chanel, một hương thơm dễ mến và đa dụng. Sự thành công của Bleu de Chanel thậm chí còn lớn đến mức tạo thành một trào lưu những mùi hương đi theo phong cách của chai nước hoa này. Tươi sáng, dễ chịu ở giai đoạn mở đầu, và tối dần về hậu hương với các nốt hương có chiều sâu như Gỗ, Hoắc hương hay Nhang, có thể tùy biến nhưng vẫn phải tuân theo một tôn chỉ tuyệt đối. Ấy là dễ mến, dễ dùng và phù hợp với mọi dịp bạn cần, còn được gọi là các “blue fragrances”.<br>Liệu Bleu de Chanel còn được các quý ông yêu thích và sử dụng đến khi nào, chẳng ai có thể biết trước được. Thế nhưng với tuổi đời đã hơn 10 năm trên thị trường, Bleu de Chanel vẫn chưa một lần rời khỏi top những mùi hương được yêu thích và bán chạy nhất, thành tích đó là đủ để chứng minh chất lượng tuyệt đối của cái tên này.', 'products/Chanel/Chanel%20Bleu%20de%20Chanel%20EDT/chanel-bleu-de-chanel-eau-de-toilette-50ml.jpg', 20, 1, 2, NULL),
(7, 'Chanel Chance Eau Vive', 2750000.00, 100, 'Cam đỏ, Bưởi , Hương cam chanh', 'nữ', 'Tươi mới, Thanh lịch, Tinh tế', 'Pháp', 2015, 'Chanel ra mắt Chance Eau Vive vào năm 2015, dưới bàn tay của Olivier Polge, một chiếc mũi tài năng của nhà Chanel và là con trai của Jacques Polge, một nhà pha chế nước hoa quen thuộc với các fan hâm mộ của nhà Chanel. Chance Eau Vive là một flanker nằm trong bộ sưu tập Chanel Chance của hãng, với những cái tên quen thuộc nổi tiếng như Chanel Chance (2003), Chanel Chance Eau Fraiche (2007) và Chanel Chance Eau Tendre (2010), với mục tiêu là những mùi hương tươi tắn dịu dàng và dành cho các quý cô trẻ trung.<br>Olivier Polge đã kết hợp note hương bưởi lấp lánh với hương cam đỏ mọng nước, tạo ra một sự bùng nổ với các note hương citrus và kết hợp chúng với sự thanh lịch của hoa nhài và xạ hương trắng quyến rũ. Ở phần cuối của mùi hương, cỏ hương bài và gỗ tuyết tùng đem lại một cảm giác ấm cúng, nhẹ nhàng, chững chạc hơn cùng một chút phấn tươi từ đóa diên vĩ tím khiến mùi hương trở nên nữ tính, nhẹ nhàng, thanh lịch và rất tinh tế.<br>Chance Eau Vive là một mùi hương được xếp vào nhóm hương Hoa – Gỗ - Xạ hương (Floral – Woody – Musk) chuẩn mực dành cho một quý cô và được thổi vào một làn hơi tươi trẻ với những note hương cam chanh ở đầu, khiến nó rất phù hợp với các chị em trẻ trung, năng động, có thể mặc đi học, đi làm, đi chơi cùng bạn bè, thâm chí có thể là những buổi hẹn hò thông thường.', 'products/Chanel/Chanel%20Chance%20Eau%20Vive/chance-eau-vive.jpg', 5, 2, 2, NULL),
(8, 'Dior J\'adore EDP', 475000.00, 10, 'Hương hoa cỏ trái cây', 'nữ', 'Gợi cảm, Sang trọng, Nữ tính', 'Pháp', 1999, 'Khi sử dụng nước hoa nữ Dior J\'adore EDP, sự nữ tính vô hạn của bạn như luôn chực chờ thời điểm bùng phát, hương thơm nước hoa lan tỏa mạnh mẽ, như mùi hương của bông hoa hồng tươi mát chớm nở trong ánh bình minh sớm mai.', 'products/Dior/Dior%20J\'adore%20EDP/dior-j_adore-eau-de-parfum-100ml.jpg', 15, 2, 1, NULL),
(9, 'Dior Sauvage Elixir EDP', 675000.00, 10, 'Aromatic Spicy (tone cay ấm)', 'nam', 'Nam tính, Hấp dẫn, Quyền lực', 'Pháp', 2021, 'Dior Sauvage Elixir EDP như một bản hòa âm đầy nam tính và lịch lãm. Những nốt hương đậm gia vị sẽ mang đến cho bạn sự ấn tượng rất khác của dòng nước hoa Dior Sauvage. Một mùi hương hoang dã hơn, ấm nồng hơn nhưng vẫn rất quyền lực và lôi cuốn.', 'products/Dior/Dior%20Sauvage%20Elixir/sauvage-elixir-60ml.jpg', 15, 1, 1, NULL),
(10, 'Valentino Uomo Born In Roma Green Stravaganza', 3050000.00, 100, 'Hương Cam Bergamot, Cà Phê, Cỏ Hương Bài', 'nam', 'Tinh Tế, Nam Tính, Nhẹ Nhàng', 'Pháp', 2024, 'Khi bản lĩnh được tỏa sáng giữa vườn xanh rực rỡ<br>Ra mắt vào năm 2024, Valentino Uomo Born in Roma Green Stravaganza là một chương mới đầy năng lượng trong bộ sưu tập Born in Roma. Lấy cảm hứng từ những khu vườn đậm chất Ý giữa lòng thành Rome, mùi hương là sự tôn vinh cá tính tự do, sôi nổi và bản lĩnh của người đàn ông hiện đại, luôn chọn sống khác biệt.<br>Ngay từ những nốt đầu tiên, Green Stravaganza mở ra với cam Bergamot vùng Calabria, tươi sáng, rạng rỡ như ánh nắng Địa Trung Hải. Khi lớp hương lắng lại, nốt cà phê dần hiện lên, với chút mạnh mẽ, gợi cảm và tràn đầy năng lượng, như nhịp sống sôi động của thành phố. Kết thúc của hương thơm là sự kết hợp tinh tế giữa Cỏ hương bài ấm khô nồng nàn đầy quyến rũ, để lại một dấu ấn vừa chín chắn vừa cá tính.<br>Đây là mùi hương dành cho những người đàn ông yêu thích sự bứt phá nhưng vẫn giữ vững bản sắc riêng. Phù hợp trong những buổi hẹn hò, tiệc tối hay bất kỳ khoảnh khắc nào bạn muốn tỏa sáng một cách lịch lãm, cuốn hút và đầy khác biệt.', 'products/Valentino/Valentino%20Uomo%20Born%20In%20Roma%20Green%20Stravaganza/valentino-uomo-born-in-roma-green-stravaganza.jpg', 15, 1, 18, NULL),
(11, 'Versace Eros For Men', 285000.00, 10, 'Hương thơm dương xỉ', 'nam', 'Nam tính, Gợi cảm, Thu hút', 'Ý', 2012, 'Nước hoa nam quyến rũ Versace Eros For Men EDT là mùi hương phù hợp với bất kỳ người đàn ông nào, nhưng với Parfumerie nó phù hợp hơn với những người đàn ông trưởng thành đầy bản lĩnh, cầm lên được thì bỏ xuống được, thôi thúc họ thể hiện được cái tôi một cách thông minh.', 'products/Versace/Versace%20Eros%20For%20Men/versace-eros-for-men.jpg', 15, 1, 4, NULL),
(12, 'Yves Saint Laurent Libre EDT', 2800000.00, 50, 'Trà lài, Hoa cam, Hoa nhài', 'nữ', 'Tưới mới, Tự do, Quyến rũ', 'Pháp', 2021, 'Libre (2019 - EDP) là một mùi hương thành công của YSL khi được đón nhận nồng nhiệt từ cả người dùng và những chuyên gia, với việc ẵm rất nhiều giải thưởng được bình chọn bởi người tiêu dùng, các tổ chức uy tín (Trong đó có cả FiFi). Tiếp nối sự thành công đó Yves Saint Laurent quyết định cho ra mắt thêm flankers cho dòng nước hoa này vào 2020 và 2021, đó là phiên bản Libre Intense (2020) và Libre Eau de Toilette (2021).<br>Đúng với tinh thần EDT, Libre EDT vẫn khai thác mạnh mẽ vào một mùi Oải hương nhiều phấn rất nữ tính và được chị em yêu thích từ Libre, cùng hương hoa Cam và Nhài ngọt dịu tôn thêm lên cái sự nữ tính ấy,  tươi và dịu hơn với hương thơm thanh khiết của Trà. Trà Nhài, pha thêm một chút hoa Cam, và nhiều phấn, vẫn giữ cái tinh thần “nữ quyền” mạnh mẽ ở phiên bản gốc nhưng đã phần nào đó thanh khiết và lả lướt hơn.<br>Về cuối, khác với Libre Intense khi cường điệu note hương ngọt ngào của Vanilla bằng Coumarin (Đậu tonka), Libre EDT lại bớt ngọt hơn, phấn hơn 1 chút và thanh lịch hơn. Điều này chắc chắn sẽ làm cho Libre EDT bớt “sexy” hơn trong mắt các chị em hảo ngọt, nhưng lại khiến mùi hương này trở nên nhẹ nhàng và đa dụng hơn. ', 'products/YSL/Yves%20Saint%20Laurent%20Libre%20EDT/libre-edt.jpg', 20, 2, 6, NULL),
(13, 'Yves Saint Laurent Y EDP', 2300000.00, 60, 'Táo xanh, Cây xô thơm, Amberwood, Gừng, Cam bergamot', 'nam', 'Nam tính, Thu hút, Hấp dẫn', 'Pháp', 2018, 'Yves Saint Laurent (YSL) Y Eau de Parfum có lẽ không còn xa lạ với các quý ông thân quen dùng nước hoa, đến mức cái tên này đã trở thành một đại diện cho hương thơm lịch lãm dành cho nam giới hiện đại. Bạn có biết tại sao lại như vậy không?<br>Lý do dễ hiểu nhất để Y Eau de Parfum thành công chinh phục các quý ông, ấy chính là ở mùi hương của chai nước hoa này. Đúng vậy, Y EDP sở hữu tất cả những nốt hương tuyệt vời dành cho nam giới, một chút trái cây giòn giã của Táo, nét đầm ấm thanh tao của Gừng, để dẫn vào cái tâm lõi Thảo mộc của mùi hương. Thảo mộc đã tạo nên sự nhã nhặn lịch lãm cho Y EDP, bằng mùi hương xanh xanh, hăng hăng, vừa lãnh đạm mà cũng đủ mạnh mẽ, bởi Xô thơm và một chút Bách xù, trên cái nền ngọt dịu và cứng chắc của Gỗ và Nhựa thơm. Y EDP có mọi thứ mà một người đàn ông cần ở hương thơm của mình, để phô diễn và tự tin với sự nam tính trời ban của mình.<br>Có thể nói, Y EDP là một hương thơm toàn diện để có thể trở thành “mùi hương đặc trưng” của bất kỳ quý ông nào, bởi sự dễ gần, dễ kiểm soát và có thể khoác lên mình bất cứ lúc nào ta muốn.', 'products/YSL/Yves%20Saint%20Laurent%20Y%20EDP/yves-saint-laurent-y-eau-de-parfum.jpg', 2, 1, 6, NULL),
(14, 'Jean Paul Gaultier Le Male Le Parfum', 390000.00, 7, 'Nguyên liệu phương Đông, Hương vani, Hoa oải hương', 'nam', 'Gợi cảm, Quyến rũ, Thu hút', 'Pháp, Tây Ban Nha', 2020, 'Le Male Le Parfum của Jean Paul Gaultier là một hương thơm phương Đông dành cho nam giới. Được ra mắt vào năm 2020, Le Male Le Parfum được tạo ra bởi Quentin Bisch và Nathalie Gracia-Cetto.<br>Mở đầu bằng sự độc tôn của Bạch Đậu Khấu, Le Male Le Parfum thể hiện được sự độc đáo và táo bạo trong cách suy nghĩ và hành động của mình khi chỉ dùng 1 loại hương cho tầng hương đầu tiên nhằm thách thức cũng như lôi kéo sự tò mò của khán giả về phía mình.<br>Không ngần ngại tỏ sự thông minh, lôi cuốn của mình, Le Male Le Parfum thể hiện mình dưới một vỏ bọc hoàn hảo, sự ngọt ngào được trau chuốt cùng với sự phóng khoáng của Hoa Oải Hương và Iris, khiến Le Male trở thành tâm điểm của đám đông.<br>Không dừng lại ở đó, Le Male Le Parfum khiến người ta liên tưởng đến những cái ôm đầy ấm áp, an toàn của gã đàn ông lịch thiệp, một cái ốm mang ý nghĩa của sự lãng mạn đặc trưng mùi hương của những đêm lạnh phương Đông Huyền bí hòa cùng hương gỗ đang dai dẳng mùi hương nơi khứu giác.', 'products/Jean%20Paul%20Gaultier/Jean%20Paul%20Gaultier%20Le%20Male%20Le%20Parfum/jean-paul-gaultier-le-male-le-parfum-125ml.jpg', 0, 1, 24, NULL),
(15, 'Jean Paul Gaultier Le Male', 1500000.00, 40, 'Vani, Hoa oải hương, Bạc hà, Quế, Đậu tonka', 'nam', 'Sexy, Gợi cảm, Cuốn hút', 'Pháp, Tây Ban Nha', 1995, 'Hiện đại, an yên và nhẹ nhàng là những gì ta có thể nhanh chóng thấy được trong Jean Paul Gaultier Le Male, kể cả là từ tông hương đầu tiên.<br>Dưới sự cộng hưởng đầy ăn nhập, Bạch đậu khấu và Bạc hà nhuốm lấy khứu giác hương thơm thanh cay, mềm mại, hòa quyện với Oải hương miên man cùng Cam bergamot. Không sôi nổi năng động, Le Male thể hiện bản chất lịch lãm, an toàn của mình bằng cách kết hợp mộc mạc giữa Hoa cam và Quế, nồng nàn và ấm áp khôn xiết.<br>Như một cái ôm siết ngày lạnh, Đàn hương, Vanilla cùng Tuyết tùng càng in đậm trong ta hình ảnh của một người tĩnh tại, thư thái cùng những khí chất không phải quý ông nào cũng may mắn có được.', 'products/Jean%20Paul%20Gaultier/Jean%20Paul%20Gaultier%20Le%20Male/jean-paul-gaultier-le-male.jpg', 8, 1, 24, NULL),
(16, 'Jean Paul Gaultier Le Male Elixir', 2860000.00, 75, 'Hương Hoa Oải Hương, Hương Vanilla, Đậu Tonka, Mật Ong', 'nam', 'Ngọt ngào, Nam tính, Cuốn hút', 'Pháp, Tây Ban Nha', 2023, 'Nếu bạn là nam và yêu thích những nét hương ngọt ngào, đậm nét, thì ấn phẩm mới đến từ nhà Jean Paul Gaultier - Le Male Elixir chắc chắn sẽ khiến bạn phải xiêu lòng đấy!<br>Vị ngọt làm nên tính cách của Jean Paul Gaultier Le Male Elixir đến từ Vanilla và Mật ong. Vanilla bông phấn mịn màng, quyện cùng Mật ong sánh đặc, tạo nên tông vị mềm mại nhưng vẫn da diết vô cùng. Thuốc lá đến ngay sau đó như khẳng định sự nam tính vẫn ở đó xuyên suốt hành trình mùi hương.<br>Dù \'ngọt\' là tính từ chính để miêu tả Jean Paul Gaultier Le Male Elixir, thế nhưng ấn nấp trong các tầng hương còn có cả Bạc hà the lạnh, Oải hương đậm đà hương thảo mộc, và cả Đậu tonka khô hăng, ấm áp nữa.', 'products/Jean%20Paul%20Gaultier/Jean%20Paul%20Gaultier%20Le%20Male%20Elixir/jean-paul-gaultier-le-male-elixir.jpg', 0, 1, 24, NULL),
(17, 'Jean Paul Gaultier Scandal', 390000.00, 6, 'Hương Hoa Oải Hương, Hương Vanilla, Đậu Tonka, Mật Ong', 'nam', 'Ngọt ngào, Nam tính, Cuốn hút', 'Pháp, Tây Ban Nha', 2023, 'Nếu bạn là nam và yêu thích những nét hương ngọt ngào, đậm nét, thì ấn phẩm mới đến từ nhà Jean Paul Gaultier - Le Male Elixir chắc chắn sẽ khiến bạn phải xiêu lòng đấy!<br>Vị ngọt làm nên tính cách của Jean Paul Gaultier Le Male Elixir đến từ Vanilla và Mật ong. Vanilla bông phấn mịn màng, quyện cùng Mật ong sánh đặc, tạo nên tông vị mềm mại nhưng vẫn da diết vô cùng. Thuốc lá đến ngay sau đó như khẳng định sự nam tính vẫn ở đó xuyên suốt hành trình mùi hương.<br>Dù \'ngọt\' là tính từ chính để miêu tả Jean Paul Gaultier Le Male Elixir, thế nhưng ấn nấp trong các tầng hương còn có cả Bạc hà the lạnh, Oải hương đậm đà hương thảo mộc, và cả Đậu tonka khô hăng, ấm áp nữa.', 'products/Jean%20Paul%20Gaultier/Jean%20Paul%20Gaultier%20Scandal/jean-paul-gaultier-scandal.jpg', 0, 1, 24, NULL),
(18, 'Dior Sauvage Parfum', 3950000.00, 100, 'Gỗ đàn hương, Oud Alezan Eau de Parfum', 'nam', 'Lịch lãm, Gợi cảm, Thu hút', 'Pháp', 2019, 'Dior sauvage Parfum là phiên bản mới nhất trong bộ sưu tập nước hoa của nhà Dior trong dòng Sauvage, tiếp nối sự thành công của các phiên bản Sauvage EDT và Sauvage EDP. Một phiên bản mới được thiết kế đậm đà hơn nhưng vẫn giữ nguyên các ADN cốt lõi làm nên thương hiệu “Lady Killer” đình đám của Dior Sauvage. Chuyên gia Francois Demachy đã phát hành phiên bản Sauvage Parfume vào năm 2019, được lấy cảm hứng từ vùng thảo nguyên, thời điểm ánh trăng lên cao cùng bầu trời tối đen le lói ánh sáng của lửa trại.', 'products/Dior/Dior%20Sauvage%20Parfum/dior-sauvage-parfum-100ml.jpg', 10, 1, 1, NULL),
(19, 'Diptyque Eau Rose', 3550000.00, 100, 'Hoa hồng, Quả vải, Quả lý chua đen', 'nữ', 'Sang trọng, Gợi cảm, Ấm áp', 'Pháp', 2012, 'Đem tới những hương thơm dành tặng riêng cho những nữ hoàng xinh đẹp, quyến rũ. Diptyque Eau Rose như một bó hoa hồng dịu dàng luôn tồn tại đặc biệt bên trong các quý cô điệu đà, sang chảnh.<br>Được ra mắt vào năm 2012, Diptyque Eau Rose chẳng cần thể hiện ra bên ngoài cũng dễ dàng nhận được cái gật đầu đồng ý cùng danh hiệu nữ hoàng của những loài hoa. Vốn dĩ chai Eau Rose có được danh hiệu quyền quý này bởi vì hương Hoa hồng quyến rũ được lấy làm chủ đạo và phản phất mạnh mẽ thu hút lấy những ánh nhìn dù cho là nam giới hay nữ giới.<br>Không vội vàng trao mình trong những giây phút ban đầu, những tinh chất Cam bergamot, Quả lý chua đen và Quả vải thay nhau lần lượt gợi mời, quấn quít nơi đầu mũi tạo ra những cảm giác dễ chịu giúp rũ bỏ đi những mệt mỏi, căng thẳng mà sẵn sàng đón nhận những cảm xúc tuyệt vời hơn cùng Eau Rose.<br>Tạo thành từng đợt dồn dập và mạnh liệt, Eau Rose thực sự khiến cho người khác phải trầm trồ và trở nên điên loạn trước vẻ quyến rũ khó cưỡng của những nốt hương Hoa hồng, Hoa nhài và Hoa phong lữ. Không chỉ mang lại những hương thơm ngọt dịu đặc trưng, từng nốt hương như gợi lên sự khoái cảm bên trong lấp đầy trong tâm trí người xung quanh cùng những câu hỏi “Là ai?” – “Hương thơm này đến từ đâu?” – “Có phải từ cô gái vừa lướt qua ư?”.<br>Để lại sự quyến rũ của mình qua những nơi mình lướt qua, Eau Rose còn vô tình lưu lại những mùi hương của mình bằng những note hương Xạ quen thuộc hợp cùng nền Gỗ tuyết tùng để tôn lên nét lắng đọng đầy ngọt ngào của Mật ong trắng. Diptyque không chỉ gợi nhắc đến hình dáng của một quý cô giàu sang, thơm tho mà còn đem tới sự quyến rũ, gợi cảm khiến cho các chàng trai phải thèm khát đến tột cùng.', 'products/Diptyque/Diptyque%20Eau%20Rose/diptyque_eau_rose.jpg', 10, 2, 20, NULL),
(20, 'Dolce & Gabbana Dolce Lily', 2650000.00, 75, 'Quả chanh dây, Hoa lily, Xạ hương', 'nữ', 'Gợi cảm, Nữ tính, Thanh lịch', 'Anh, Pháp, Đức', 2022, 'Được ra đời vào năm 2022, Dolce Lily là phiên bản flanker mới nhất của Bộ Sưu Tập Dolce và là một bản nhạc được tấu lên nhằm tôn vinh sự tồn tại đầy quyến rũ của bông hoa Lily, loài hoa biểu tượng cho sự nữ tính và lòng nhân ái của phái đẹp.<br>Là một người phụ nữ hiện đại, mang trong mình nguồn năng lượng tươi mới, một khởi đầu mát mẻ của các tông hương Cam Chanh là một cách tinh tế để cùng nàng tạo ra sự tự tin, thoải mái toát ra trong bầu không khí xung quanh.<br>Cuối cùng, cô chấm dứt một sự trầm ấm, nhịp nhàng. Thảo bước trên con phố với những sự tự tin, Xạ Hương xuất hiện nâng niu từng bước chân cô, Vanilla tạo ra những nét ấm áp, khơi gợi về một cô gái trẻ trung, luôn muốn tạo ra một sự cao đẹp nơi tâm hồn như những đóa hoa Lily nở rộ dưới ánh dương.', 'products/Dolce%20&%20Gabbana/Dolce%20&%20Gabbana%20Dolce%20Lily/dolce-lily.jpg', 20, 2, 27, NULL),
(21, 'Dolce & Gabbana Dolce Rosa Excelsa EDP', 2500000.00, 75, 'Hoa hồng, Hoa sủng, Xạ hương', 'nữ', 'Nữ tính, Quyến rũ, Tinh tế', 'Anh, Pháp, Đức', 2016, 'Như một cuộc dạo chơi trong khu vườn với những hoa là hoa, đó là gói gọn những gì mà Dolce & Gabbana Dolce Rosa Excelsa Eau de Parfum sắp thể hiện với bạn đó.<br>Câu hỏi đầu tiên đặt ra khi thử Dolce Rosa Excelsa EDP, chắc hẳn sẽ là \'Khu vườn này có những loại hoa gì thế?\', vì thoạt nhiên, bạn sẽ chẳng thể đoán được hương hoa nào là hương hoa nào. Ở đó hòa quyện sự thanh tươi, sắc cạnh tựa Cam chanh, với một chút ngọt đậm, mướt như Sữa, và cũng miên man hoài niệm với chút thoảng \'vintage\' một chút.<br>Hương hoa ấy cứ mãi miết đeo đuổi đến tận cuối cùng, thiếu một mà cũng không thêm thắt gì, cứ vần vũ ta trong khu vườn đầy mê hoặc, thần kỳ thuyết phục ta rằng chẳng cần phải chỉ mặt điểm tên cho rõ ràng mà vẫn nhận ra được nét đẹp của tổng hợp các loài hoa.', 'products/Dolce%20&%20Gabbana/Dolce%20&%20Gabbana%20Dolce%20Rosa%20Excelsa%20EDP/dolce-_-gabbana-rosa-excelsa.jpg', 5, 2, 27, NULL),
(22, 'Giorgio Armani Acqua di Gio EDP', 3949000.00, 100, 'Hương Biển, Khoáng Sản, Quả Cam', 'nam', 'Nam tính, Hiện đại, Phóng khoáng', 'Pháp', 2022, 'Mang trong mình hơi thở Thụy sinh, Giorgio Armani Acqua di Gio Eau de Parfum là vẻ đẹp của một người đàn ông cá tính, hiện đại và có đôi phần \'ướt át\'. Ra mắt từ năm 2022, ấn phẩm được sự đón nhận nồng nhiệt của giới mộ điệu, bởi đây không chỉ là một sự kế thừa và phát huy DNA của Acqua di Gio nguyên bản, mà còn là một phiên bản hoàn chỉnh của sự giao thoa giữa giữa bản gốc và bản Essenza.<br>Ấn phẩm là sự mô tả về một không gian rộng lớn và vô tận như đang đắm mình vào biển cả, không giới hạn, không chút áp lực và không có sự hạn chế. Hương thơm của Khoáng chất kết hợp cùng Quất xanh, dịu ngọt nhưng cực tươi mát, lại càng nhấn mạnh vẻ đẹp của biển cả. Càng về sau, hương thơm càng nồng ấm, hoang dã của Xô thơm lẩn trong cái kết cấu phức tạp của Chi Mở hạt, tạo nên cái trầm lắng bên trong mỗi quý ông.<br>Để rồi người đàn ông ấy quyến luyến mọi cô gái bằng sự phóng khoáng, lơ đãng của mình. Thoảng hoặc theo đó chút Cỏ Hương bài, chút Hoắc hương nồng đượm hương Gỗ đặc trưng. Những nốt hương trong Giorgio Armani Acqua di Gio EDP đan xen tựa hết cảm xúc biến chuyển của một gã trai hiện đại, và bên trong họ là những phần tâm hồn tươi tắn, phóng khoáng.', 'products/Giorgio%20Armani/Giorgio%20Armani%20Acqua%20di%20Gio%20EDP/acqua-di-gio-edp.jpg', 1, 1, 12, NULL),
(23, 'Giorgio Armani Code', 3500000.00, 100, 'Da thuộc, Hồi hương, Hoa olive, Đậu tonka', 'nam', 'Tinh tế, Ấm áp, Quyến rũ', 'Pháp', 2004, 'Armani Code của Giorgio Armani là một Eau de Toilette quyến rũ dành cho người đàn ông đương đại. Hương thơm hiện đại và tinh tế dành cho nam giới này cắt lên bằng mùi hương của Bergamot và chanh. Hương hoa Olive, quả Hồi và Gỗ Guaiac. Như thể diễn tả một cách chuẩn xác cách mà người đàn ông trưởng thành điểm nhiên thể hiện sự hấp dẫn của mình, hơi thuốc lá tan vào không gian, kéo theo đó là hương da và đậu Tonka làm ấm dần những cảm xúc và miêu tả triệt để sức hút từ anh chàng.', 'products/Giorgio%20Armani/Giorgio%20Armani%20Code/giorgio-armani-armani-code-edt-ban-moi.jpg', 9, 1, 12, NULL),
(24, 'Giorgio Armani Emporio Armani Stronger With You For Men', 2950000.00, 100, 'Hương vanila, Bạch đậu khấu, Cây hạt dẻ', 'nam', 'Tinh tế, Tinh tế, Thu hút', 'Pháp', 2017, 'Hãng Giorgio Armani đã công bố một cặp đôi hoàn toàn mới trong bộ sưu tập nước hoa danh tiếng của hãng, và được ví von là “ Tình yêu hiện đại đích thực trong khứu giác”. Phiên bản nam giới với tên gọi Emporio Armani Stronger With you và phiên bản nữ là Because It’s You được ra mắt cùng lúc nào tháng 6 năm 2017. Emporio Armani Stronger With You được ra mắt trong sự kỳ vọng lớn của hãng Armani, dưới áp lực của những dòng nước hoa danh tiếng kinh điển của hãng như Gio Giorgio Armani hay Armani Code. Với tone mùi hương hoàn toàn khác biệt so với những dòng nước hoa đã từng phát hành của hãng, Chàng trai Emporio Armani sở hữu các tầng hương phức tạp, tạo ra những cảm xúc khó phán đoán. Điều bạn dễ dàng nhận thấy nhất khi lần đầu tiên tiếp xúc với chàng Emporio chính là sự ngọt ngào độc đáo, nhưng cực kỳ nam tính trong cách gợi ý sự chú ý từ đối phương.', 'products/Giorgio%20Armani/Giorgio%20Armani%20Emporio%20Armani%20Stronger%20With%20You%20For%20Men/emporio-armani-stronger-with-you-giorgio-armani.jpg', 12, 1, 12, NULL),
(25, 'Giorgio Armani My Way', 4103000.00, 90, 'Gỗ tuyết tùng, Hoa nhài, Hoa cam', 'nữ', 'Nữ tính, Tinh tế, Sang trọng', 'Pháp', 2020, 'Nếu bạn đã trót lòng yêu thích Gucci Bloom trứ danh vì tông hương thanh tao, quyến quý từ Huệ và Nhài, thì có lẽ My Way Giorgio Armani sẽ đem đến cho bạn cảm giác còn hơn cả thoả mãn nữa đấy.<br>Mở ra con đường của mình không chút e ngại, My Way đúng như tinh thần phóng khoáng của mình đã bắt đầu với tông hương của Hoa cam quyện cùng chút thanh mát của Cam bergamot. Tất cả chỉ để dự báo cho sự xuất hiện đầy huy hoàng của Hoa huệ cùng Nhài khi tiến sâu hơn vào bóc tách từng lớp hương.<br>Thường thường, các nốt hoa trắng sẽ là ‘thủ lĩnh\' và chiếm trọn tất cả các spotlight trong một chai nước hoa, My Way cũng không phải ngoại lệ. Cho đến khi tất cả các nốt hương di chuyển đến con đường cuối cùng, khi có thêm sự xuất hiện của Vanilla. Vẻ ngọt ngào say đắm, bồng bềnh nhưng rất thực, lãng đãng xung quanh bầu không khí, khiến cho nét đẹp ban đầu hơi kiêu kỳ giờ đây trở nên gần gũi đến lạ.', 'products/Giorgio%20Armani/Giorgio%20Armani%20My%20Way/08a5dcffaec74ed1827a261f229b71a0.jpg', 18, 2, 12, NULL),
(26, 'Hermes Terre D’Hermes EDT', 2600000.00, 100, 'Hương gỗ cay nồng', 'nam', 'Nam tính, Chững chạc, Phong trần', 'Pháp', 2006, 'Nếu những mùi hương “đương đại” xô bồ đã khiến bạn nhàm chán, hãy một lần tìm đến sự cổ điển, lịch lãm trong không gian mùi hương của Terre d’Hermes - cả một vùng trời đất rộng lớn kỳ diệu mà rồi chàng trai nào cũng sẽ đạt tới độ trưởng thành và bản lĩnh để thưởng thức một lần trong đời.', 'products/Hermes/Hermes%20Terre%20D’Hermes%20EDT/terre-d-hermes-eau-de-toilette-parfumerie.jpg', 10, 1, 14, NULL),
(27, 'Gucci Bloom Eau de Parfum For Her', 4390000.00, 100, 'Hoa nhài, Hoa huệ, Hoa kim ngân', 'nữ', 'Quyến rũ, Sang trọng, Quý phái', 'Anh, Đức, Pháp', 2017, 'Gucci Bloom EDP For Her, một mùi hương tiêu biểu cho một nét đẹp thanh thoát và tao nhã chuẩn Ý. Nếu bạn lỡ say đắm mùi hương thanh lịch và quyến quý của những đoá hoa trắng, thì nhất định bạn sẽ phải \'nghiêng mình ngả mũi\' khi bắt gặp mùi hương của Gucci Bloom EDP trên phố.<br>Chưng cất hương thơm của một vườn hoa nào Nhài và Huệ vào buổi sáng, Gucci Bloom EDP tỏa hương thơm nức lòng người, kiêu diễm và tràn đầy sức sống. Ấy thế, đâu đó len lỏi trong từng tầng hương vẫn là chút vị đắng nhưng tươi, bạn có thể lấy mùi hương của Trà để tưởng tượng.<br>Từng phút từng giây trôi qua, ta chỉ ngày càng thêm yêu nét hương ý tứ, sang trọng mà Gucci Bloom EDP chưng cất. Nhiều người hỏi rằng Gucci Bloom EDP phù hợp với độ tuổi nào, mà quả thật thì tôi không biết chắc, vì với tôi, đây là một mùi hương không có tuổi và đẹp đẽ vô vàn.', 'products/Gucci/Gucci%20Bloom%20EDP%20For%20Her/gucci-bloom-eau-de-parfum.jpg', 30, 2, 3, NULL),
(28, 'Gucci Flora Gorgeous Gardenia EDP', 4690000.00, 100, 'Hoa lê, Đường nâu, Hoa nhài', 'nữ', 'Ngọt ngào, Gợi cảm, Mãnh liệt', 'Anh, Đức, Pháp', 2021, 'Gucci là một thương hiệu vốn rất thành công trong mảng nước hoa nữ, bằng những mùi hương khai thác nét đẹp mềm mại, nữ tính của các loại hoa và trái cây. Flora by Gucci cũng là một làn hương nữ tính dễ chịu và đáng yêu dành cho các quý cô, thường xuất hiện khá “le lói” trên thị trường dưới dạng những bản phát hành giới hạn theo từng năm hoặc sẽ dừng sản xuất sau vài năm phát hành trên thị trường. Phải đến năm 2021, Gucci mới cho ra một cái tên với ngoại hình cố định và mới mẻ cho dòng hương này, thay vì sử dụng những chiếc chai đựng được lấy cảm hứng từ kiểu dáng của Flora by Gucci năm 1966, ấy là Gucci Flora Gorgeous Gardenia Eau de Parfum.<br>Đựng trong một chiếc chai màu hồng với kiểu dáng như những chiếc lọ trên giá làm việc của một bác sĩ thảo mộc, Gucci Flora Gorgeous Gardenia thật sự sở hữu mùi hương cũng khá “gorgeous” như cái tên và ngoại hình. Với chủ điểm là hoa trắng, Dành dành và Nhài bung tỏa ngay từ những giây phút đầu tiên của mùi hương, được tăng thêm độ ngọt và mọng nước từ Quất và Quả mọng. Một chút thanh ngọt nịnh mũi của Hoa lê, càng làm tăng thêm nét mơn mởn trong từng làn hương trắng trải ra khắp không gian.<br>Được là ngọt từ Hoa và các thứ Trái cây nịnh mũi, nên Gucci Flora Gorgeous Gardenia không phải là một thứ mùi hương ngọt lịm quá trớn, mà thanh thoát, uyển chuyển và đồng thời cũng sở hữu những rung động nữ tính ở mức tinh tế hơn. Khi khô xuống, Gucci Flora Gorgeous Gardenia vẫn tiếp tục duy trì được độ ngọt của mình bằng Đường nâu, nhưng chừng mực và vẫn giữ được khuôn mẫu sang trọng vốn có của mình.', 'products/Gucci/Gucci%20Flora%20Gorgeous%20Gardenia%20EDP/flora-gorgeous-gardenia-eau-de-parfum-gucci-for-women.jpg', 10, 2, 3, NULL),
(29, 'Jimmy Choo Urban Hero for Men', 2380000.00, 100, 'Da thuộc, Gỗ hồng, Cỏ hương bài', 'nam', 'Tươi mới, Nam tính, Lịch lãm', 'Anh, Pháp', 2019, 'Urban Hero là gương mặt mới được Jimmy Choo giới thiệu vào năm 2019, lấy cảm hứng từ sự phá cách, phong trần, có chút ngông nghênh nhưng lại cực kỳ nam tính, lôi cuốn sự chú ý của mọi người, và là tâm điểm của sức sống đang cuộn trào với cuộc sống đô thị bộn bề đang diễn ra. Jules Delet là gương mặt được Jimmy Choo chọn làm gương mặt đại diện cho hình ảnh của Urban Hero, một nghệ sĩ đường phố nổi tiếng, mang trong mình sự bí ẩn, tự tin cùng với sự nổi loạn một cách thông minh và tinh tế. Urban Hero mang trong mình hương thơm nam tính đậm mùi da thuộc, mạnh mẽ, khoáng đạt và có sự phong lưu trong cách tỏa mùi. Urban Hero như một làn khói trắng tỏa bay quanh một chiếc ghế da cao cấp được đóng bằng gỗ, nơi người đang ông đang phiêu theo từng nốt nhạc, sạch sẽ và kiệm lời trong ánh mắt, nhưng cuốn hút tuyệt đối bằng phong cách cá nhân đậm chữ \'tôi\' của chính bản thân.', 'products/Jimmy%20Choo/Jimmy%20Choo%20Urban%20Hero%20for%20Men/jimmy_choo_urban_hero_100ml.jpg', 22, 1, 36, NULL),
(30, 'Jo Malone London English Pear & Freesia Cologne', 3500000.00, 100, 'Quả lê, Hoa diên vĩ, Hoa hồng, Xạ hương', 'nữ', 'Nhẹ nhàng, Sang trọng, Gợi cảm', 'Anh', 2010, 'Hầu hết mọi người đều biết đến Jo Malone như một nhà hương Anh Quốc chuyên làm những mùi hương nhẹ nhàng, mơ man kiểu Eau de Cologne cổ điển, phải chứ? Jo Malone London English Pear & Freesia Cologne lại là sự khác biệt thú vị với phần còn lại, đôi khi mạnh mẽ lan tỏa, khi lại nhẹ nhàng mơn trớn đúng kiểu Jo Malone, một cách lạ kỳ.<br>Đã có lần, tôi mặc mùi hương này và đi chung thang với hai mẹ con cô bé nọ. Cô bé nhỏ ấy nói với mẹ rằng đang cảm thấy mùi hương hoa quả đang \'nổi dậy\'. Thú vị đấy bé con ạ, nhưng chẳng có thứ gì nổi dậy ở đây cả. Chúng chỉ đang thở, những nhịp thở lúc trầm lúc bổng của Quả lê mọng nước và chùm Lan Nam Phi ngọt ngào trong English Pear & Freesia. Hai thứ ấy như quấn lấy nhau, dịu dàng tỏa hương trong một không gian nhẹ nhàng và vui tươi.<br>Đúng như cái tên, English Pear & Freesia Cologne là một màn trình diễn hoàn chỉnh của Lan Nam Phi và Quả lê, rồi đến khi khô xuống, Xạ hương dịu dàng xuất hiện, như cùng với hai nốt hương chính vỗ về làn da cùng khứu giác chúng ta cho tới lúc mùi hương biến mất.', 'products/Jo%20Malone/Jo%20Malone%20London%20English%20Pear%20&%20Freesia%20Cologne/jo-malone-london-english-pear.jpg', 16, 2, 10, NULL),
(31, 'Lancôme Trésor L\'Eau De Parfum', 2950000.00, 100, 'Hương hoa cỏ phương đông', 'nữ', 'Quyến rũ, Nữ tính, Gợi cảm', 'Pháp', 1990, 'Hương thơm của nước hoa Lancôme Trésor đưa người ta vào một không gian đầy ma mị, mê mẩn tâm hồn bằng thứ hoa hồng dại, là lựa chọn chỉnh chu nhất cho người phụ nữ hiểu được giá trị thời gian.', 'products/Lancome/Lancôme%20Trésor%20L\'Eau%20De%20Parfum/2b40a777-009d-4d2f-8382-706689ad3d5b.jpg', 20, 2, 13, NULL),
(32, 'Le Labo Rose 31', 6900000.00, 100, 'Hoa hồng, Cỏ vetiver, Gỗ tuyết tùng', 'unisex', 'Cuốn hút, Tinh tế, Sang trọng', 'Mỹ', 2006, 'Khác với những mùi hương truyền thống vốn có, Le Labo Rose 31 đem lại sự quyến rũ vốn có từ những đặc trưng từ Hoa hồng đến cho chủ nhân của mình thông qua những tầng hương được trau chuốt tỉ mỉ.<br>Gom trọn mọi cảm xúc chỉ trong lần xịt đầu tiên, từng nốt hương ngọt nhẹ, ấm áp từ Cỏ hương bài và Gỗ tuyết tùng dìu dắt sự quyến rũ, thu hút của Hoa hồng lên trên bề mặt da thịt. Nằm trọn trên cơ thể, Hoa hồng bám lấy không dứt qua đến tầng hương giữa đến khi những nốt hương Thì là (Ai Cập) tạo ra một chuỗi liên tiếp những mùi hương phản phất sự hấp dẫn, mềm mại đến lạ kỳ.<br>Sự trung lập của Le Labo Rose 31 ngày càng được khắc họa rõ hơn sau mỗi đợt hương Xạ phản ứng cùng Gỗ guaiac, Nhũ hương, Labdanum và Trầm hương. Đó là lý do tại sao Le Labo Rose 31 khi tiếp xúc trên cơ thể nam giới, hương thơm sẽ trở nên cá tính, quyến rũ còn lên da nữ thì lại hóa thành gợi cảm, dịu dàng.', 'products/Le%20Labo/Le%20Labo%20Rose%2031/le_labo_rose_31.jpg', 6, 3, 22, NULL),
(33, 'Louis Vuitton Apogée EDP', 10500000.00, 100, 'Hương hoa cỏ trái cây', 'nữ', 'Ngọt ngào, Tinh tế, Nữ tính', 'Pháp', 2016, 'Nước hoa nữ LV Apogée được đánh giá là một trong 3 mùi bán chạy nhất trong bộ sưu tập mới nhất Les Parfums của thương hiệu Louis Vuitton, hương thơm mở ra những khía cạnh nữ tính, thuần khiết và tinh tế hơn trong nét đẹp của phụ nữ.', 'products/LV/Louis%20Vuitton%20Apogée%20EDP/cadf6a86.jpg', 20, 2, 37, NULL),
(34, 'Louis Vuitton Imagination EDP', 10800000.00, 100, 'Nhóm hương cam chanh thơm ngát', 'unisex', 'Sang trọng, Hiện đại', 'Pháp', 2021, 'Louis Vuitton Imagination là sự kết hợp hoàn hảo giữa tinh thần tự do, sáng tạo và phong cách sống hiện đại. Đây là một mùi hương dễ dùng, dễ yêu, và chắc chắn sẽ làm hài lòng những ai đang tìm kiếm một chai nước hoa mùa hè tinh tế nhưng không nhàm chán.', 'products/LV/Louis%20Vuitton%20Imagination%20EDP/z6244204291199.jpg', 20, 3, 37, NULL),
(35, 'Louis Vuitton L’immensité', 12500000.00, 100, 'Hương cay nồng phương đông', 'unisex', 'Nam tính, Nhẹ nhàng, Phóng khoáng', 'Pháp', 2018, 'Bậc thầy nước hoa Jacques Cavallier Belletrud đã kết tinh những trải nghiệm tuyệt vời của ông trong chuyến hành trình đến vùng biển mát lành, nơi khởi nguồn của chai nước hoa Louis Vuitton L’immensité. Là loại nước hoa đầu tiên trong 5 loại nước hoa Les Parfum Louis Vuitton dành cho nam đơn giản được ra mắt vào năm 2018. Tuy là thiết kế truyền thống nhưng thân chai thủy tinh chắc chắn, được gia công tỉ mỉ từng góc cạnh. Lớp trên cùng là nước thơm, trong vắt, chứa nhiều sương sương, đã được kết tinh lại để tạo nên L’immensité hoàn hảo để hoàn thiện tinh chất.', 'products/LV/Louis%20Vuitton%20L’immensité/z6988799969966-226fcf4c70d444a34689c4ddd5e754ec.jpg', 20, 3, 37, NULL),
(36, 'Maison Francis Kurkdjian A La Rose', 13535000.00, 200, 'Hoa hồng Bulgari, Cam bergamot Calabrian, Hoa violet, Gỗ tuyết tùng', 'nữ', 'Tinh tế, Nữ tính, Sang trọng', 'Pháp', 2014, 'Hoa hồng là chủ đề muôn thuở trong thế giới nước hoa, từ những ngày đầu khởi nguồn của hương thơm cho tới ngày nay. Hồng có thể lộng lẫy, đỏng đảnh, choáng ngợp, rồi đôi khi lại e ấp, lấp ló trong màn sương sớm, bởi nó đa màu, đa sắc, giàu thái độ và đầy cảm xúc. Vì vậy, nếu bạn có hứng thú với loài hoa này, và theo đuổi phong cách thư thái nhẹ nhàng như những bông hoa trắng, thì Hoa hồng vẫn sẽ luôn có lựa chọn dành cho bạn, ấy là À La Rose của Maison Francis Kurkdjian.<br>Khoác Maison Francis Kurkdjian À La Rose lên mình, bạn hãy đơn giản tưởng tượng ra rằng mình đang mặc lên một tấm khăn, được dệt từ những cánh hoa hồng mềm mịn như nhung lụa. À La Rose mở ra bằng một bức tranh của vườn hồng sớm mai, tươi tắn, đẫm nước bằng cái thanh mọng của Bergamot, cùng chút ngọt ngào của Cam, tổng hòa ra một buổi sáng dễ chịu nơi vườn hồng mộng mơ. Hồng xuất hiện sớm, xuyên suốt từ đầu tới cuối của mùi hương, ẩm ướt, mềm mại, không đậm thẫm và tình tứ như những thứ hoa hồng quen thuộc, cũng không mạnh mẽ bí ẩn như khi kết hợp với Trầm hương.<br>Maison Francis Kurkdjian À La Rose không phải là một thứ hoa hồng nổi bật, nồng nàn hay đánh cướp mọi sự thu hút khi xuất hiện, nhưng chắc chắn là một loài hoa hồng êm dịu, nịnh nọt khứu giác, để bất kỳ ai bắt qua làn hương cũng phải thích thú, quyến luyến.', 'products/Maison%20Francis%20Kurkdjian/Maison%20Francis%20Kurkdjian%20A%20La%20Rose/maison-francis-kurkdjian-a-la-rose.jpg', 3, 2, 11, NULL),
(37, 'Maison Francis Kurkdjian Oud', 5350000.00, 70, 'Hương Hoa Nghệ Tây, Trầm Hương, Quảng Hoắc Hương', 'unisex', 'Thanh Lịch, Tinh Tế, Ấm Áp', 'Pháp', 2012, 'Hoa hồng chưa bao giờ có thể lãng mạn hơn khi xuất hiện trong ấn phẩm Maison Francis Kurkjian Oud Satin Mood. Tất nhiên, nói thế không có nghĩa mùi hương này được tạo nên xề xòa hay không công kỹ, chỉ là vẻ đẹp này quá lạ lẫm so với hàng hà sa số những “bình hoa di động” trên thị trường hiện nay.<br>Hồng trong Oud Satin Mood chứng chạc và điếm đạm, không xô bồ và cũng chẳng đầy gai. Tất nhiên, nó nói thế không có nghĩa mùi hương này được tạo nên xề xòa hay không công kỹ, chỉ là vẻ đẹp này quá lạ lẫm so với hàng hà sa số những “bình hoa di động” trên thị trường hiện nay.<br>Tôi hay ví von cặp đôi Hồng & Oud có một hiệu ứng mềm mịn như nhung. Tấm vải nhung đỏ thẫm, mặn mà sắc sảo mà ta phải nâng niu rất cẩn thận. Kể cả khi bạn không thích Hồng và cũng chẳng chịu Oud, thì Maison Francis Kurkjian Oud Satin Mood vẫn sẽ có cách của riêng nó để khiến bạn phải để tâm tới mà thôi.', 'products/Maison%20Francis%20Kurkdjian/Maison%20Francis%20Kurkdjian%20Oud/oud-extrait.jpg', 10, 3, 11, NULL),
(38, 'Maison Francis Kurkdjian Oud Satin Mood', 15500000.00, 200, 'Hương gỗ phương đông', 'unisex', 'Thanh Lịch, Tinh Tế, Độc Đáo', 'Pháp', 2015, 'Hoa hồng chưa bao giờ có thể lãng mạn hơn khi xuất hiện trong ấn phẩm Maison Francis Kurkjian Oud Satin Mood. Tất nhiên, nói thế không có nghĩa mùi hương này được tạo nên xề xòa hay không công kỹ, chỉ là vẻ đẹp này quá lạ lẫm so với hàng hà sa số những “bình hoa di động” trên thị trường hiện nay.<br>Hồng trong Oud Satin Mood chứng chạc và điếm đạm, không xô bồ và cũng chẳng đầy gai. Tất nhiên, nó nói thế không có nghĩa mùi hương này được tạo nên xề xòa hay không công kỹ, chỉ là vẻ đẹp này quá lạ lẫm so với hàng hà sa số những “bình hoa di động” trên thị trường hiện nay.<br>Tôi hay ví von cặp đôi Hồng & Oud có một hiệu ứng mềm mịn như nhung. Tấm vải nhung đỏ thẫm, mặn mà sắc sảo mà ta phải nâng niu rất cẩn thận. Kể cả khi bạn không thích Hồng và cũng chẳng chịu Oud, thì Maison Francis Kurkjian Oud Satin Mood vẫn sẽ có cách của riêng nó để khiến bạn phải để tâm tới mà thôi.', 'products/Maison%20Francis%20Kurkdjian/Maison%20Francis%20Kurkdjian%20Oud%20Satin%20Mood/satin-mood-edp.jpg', 10, 3, 11, NULL),
(39, 'Montblanc Legend Spirit Mini Size', 420000.00, 8, 'Hương nước, Hoa oải hương, Quả bưởi, Cam bergamot', 'nam', 'Tự tin, Nam tính, Hiện đại', 'Tây Ban Nha', 2016, 'Montblanc công bố Legend Spirit vào năm 2016 và là một phiên bản hoàn toàn mới so với bản gốc từ năm 2011, với khẩu hiệu \'Follow Your Spirit\'. Hương thơm thể hiện sự lịch lãm vượt thời gian, từ cổ điển tới hiện đại, nhưng vẫn giữ cốt cách nam tính đầy mạnh mẽ của cánh mày râu. Thành phần mùi hương mở đầu bằng sự pha trộn đầy tinh tế giữa Cam bergamot, Bưởi và Hồng tiêu, là mùi hương của sự tự tin và thành đạt. Midnote với sự có mặt của hoa Lavender cùng Bạch đậu khấu, hấp dẫn ánh mắt của mọi cô gái, cùng sự tươi mát của hương nước. Montblanc Legend Spirit là một người đang ông của tương lai dù đang sống ở hiện tại, với cách tiếp cận cùng phong cách rất cởi mở, thân thiện, hướng ngoại và chu đáo của mình, cũng chính vì vậy, Montblanc Legend Spirit cũng dễ dàng trở thành người chồng tương lai được chọn của các quý cô đến tuổi trưởng thành.', 'products/Montblanc/Montblanc%20Legend%20Spirit%20Mini%20Size/montblanc-legend-spirit-mini-size.jpg', 0, 1, 17, NULL),
(40, 'Penhaligon’s Cairo EDP', 5500000.00, 100, 'Vanilla, Hoa hồng', 'unisex', 'Quyến rũ, Tinh tế, Sang trọng', 'Anh', 2019, 'Penhaligon’s Lothair được lấy cảm hứng từ một thành phố cổ kính, hay còn biết đến là thủ đô của Ai cập được lớp ánh sáng mặt trời phủ lên trông rất ấm áp. Cairo của Penhaligon’s có chứa Hoa hồng Damascan tinh tế, được ngâm trong vô số loại gỗ và gia vị. Một hương thơm trang trí đầy thủ công, tinh xảo và xa hoa vang vọng với nghệ tây và trầm hương, được cho là nước hoa của các vị thần, mùi hương kết thúc với hoắc hương và vani ở hương cuối để tạo cảm giác yên tâm quen thuộc như một buổi hoàng hôn từ đất nước của Thần Linh.', 'products/Penhaligon’s/Penhaligon’s%20Cairo%20EDP/new.jpeg', 4, 3, 19, NULL),
(41, 'Prada L\'Homme Intense', 3520000.00, 100, 'Hoa diên vĩ, Đậu tonka, Da thuộc', 'nam', 'Tinh tế, Tự tin, Hiện đại, Cuốn hút', 'Pháp, Tây Ban Nha', 2017, 'Bộ đôi nước hoa mới của Prada, Prada La Femme và Prada L\'Homme, ra mắt thị trường vào giữa năm 2016. Một năm sau, các chế phẩm được cải tiến ở nồng độ Eau de Parfum Intense và đã mang lại những tiếng tăm vang dội trong giới mộ điệu mùi hương.<br>Prada L\'Homme Intense hứa hẹn \'một trải nghiệm nhiều tầng\' và cải tiến từ mùi hương của hoa diên vĩ kết hợp cùng hổ phách với bộ đôi hương da thuộc và hoắc hương, trong khi đậu tonka mang đến cho nó một dấu ấn phương Đông. Mục tiêu của mùi hương và dáng chai mờ đục là gợi lên \'bản chất khó nắm bắt của người đàn ông Prada đa diện\'.', 'products/Prada/Prada%20L\'Homme%20Intense/prada_l_homme_intense.jpg', 9, 1, 16, NULL),
(42, 'Prada Luna Rossa Carbon', 3040000.00, 100, 'Hoa lavender, Hương kim loại', 'nam', 'Lịch lãm, Nam tính, Cuốn hút', 'Ý', 2017, 'Prada Luna Rossa Carbon được ra mắt vào năm 2017 dưới sự sáng tạo của chuyên gia nước hoa Daniela Andrier. Một đứa con xuất sắc trong đại gia đình Luna Rossa của Prada với triết lý thiết kế mùi hương đầy nam tính dựa theo nhóm hương kinh điển Ambroxan và Hoa lavender. Hẳn đã rất nhiều người mê mẩn và đắm chìm trong các note hương Ambroxan hay Lavender trong Dior Sauvage hay Versace Eros, hai chai nước hoa làm mưa làm gió và đại diện cho sự quyến rũ một cách đầy nam tính của hai thương hiệu hàng đầu.<br> Nhiều sự so sánh về các tầng hương của Prada Luna Rossa Carbon dành cho hai nhân vật kia, về cảm giác, về các note hương hay về cảm xúc mang lại. Bản thân Prada Luna Rossa Carbon mang trong mình sự quyến rũ đầy khiêu khích của Dior Sauvage EDT ngay cú xịt đầu tiên, đó là khi Lavender và Ambroxan hoạt động hết công suất, tạo ra sự mạnh mẽ một cách tuyệt đối, thì sự tham gia của cam bergamot lại làm tươi mát cùng sự tròn vị của tiêu đen khiến cái gật ở tầng hương đầu của Prada Luna Rossa Carbon giảm đi đáng kể. Việc có thêm than đá, đúng như cái tên hãng đặt, Carbon chính là sự khác biệt ở chàng trai hấp dẫn này, nó khiến anh chàng này định hình được phóng cách của mình, sự mạnh mẽ cuốn hút nhưng rất lạnh lùng.<br>Vị ngọt nhẹ và đầy sức hút của lavender ở base note kết hợp cùng hương nước lại khiến bản thân nhiều người so sánh với gã thần tình yêu Versace Eros, điều này chỉ duy trì trong khoảng thời gian ngắn khi Prada Luna Rossa Carbon xuống da sau 20 phút mà thôi. Nếu bạn hỏi tôi Prada Luna Rossa Carbon phù hợp vào thời gian nào và phù hợp với đối tượng nào, thì với khía cạnh cá nhân, gã này phù hợp cho mọi không gian và thời gian, sự cân bằng giữa vị ngọt và thanh tươi ở Prada Luna Rossa Carbon là điều khiến gã này trở nên hoàn hảo. Và với những đàn ông trẻ trung nhưng lại trưởng thành, hiện đại, thì Prada Luna Rossa Carbon là thứ không nên thiếu trọn bộ sưu tập của chính mình.', 'products/Prada/Prada%20Luna%20Rossa%20Carbon/prada_luna_rossa_carbon.jpg', 1, 1, 16, NULL),
(43, 'Prada Luna Rossa Ocean', 2800000.00, 100, 'Hoa oải hương, Cam bergamot, Hoa diên vĩ', 'nam', 'Nam tính, Tươi mát, Hấp dẫn', 'Pháp', 2021, 'Prada vẫn luôn được biết đến với phong cách làm hương tinh tế chuẩn mực, thể hiện rõ nét nam tính nhưng không hề đi theo \'lối mòn\' tác chế. Chính thế, những ấn phẩm hương thơm khoác tên Prada vẫn luôn giữ được sự yêu thích của người nghiện hương mặc cho sự bào mòn của thời gian và thời đại.<br>Đừng để cái tên Luna Rossa Ocean khiến bạn lầm tưởng rằng đây là mùi hương của nước, của biển. Dầu cho thực sự có một nét tính cách rất dễ chịu, nhưng Prada Luna Rossa Ocean lại được làm nên trước tiên bởi những nét hương của Cam chanh. Từ tông vị thanh chua ấy, Hoa diên vĩ và Xạ hương mới dần phủ lên một lớp, bồng bồng mền màng, là dịu dàng đi tổng thể nhưng vẫn nam tính hiện đại bởi Xô thơm và Cỏ hương bài.<br>Oải hương là một nét hương sáng, nói thế không có nghĩa sự xuất hiện của loài hoa này sẽ giảm đi chiều sâu sắc của mùi hương, mà \'sáng\' ở đây tượng trưng cho hiệu ứng miên man, nồng đượm mà nó đã giúp tôn lên cho tổng thể. Tinh tế, phong cách, hiện đại, đó là những gì ta có thể tóm gọn về Prada Luna Rossa Ocean.', 'products/Prada/Prada%20Luna%20Rossa%20Ocean/prada-ocean-100ml.jpg', 20, 1, 16, NULL),
(44, 'Prada Luna Rossa Sport EDT', 3800000.00, 150, 'Hoa oải hương, Hương vanila, Đậu tonka', 'nam', 'Nổi bật, Hấp dẫn, Hiện đại', 'Anh, Pháp', 2015, 'Có vẻ như trong những mùi hương đến từ dòng Luna Rossa của Prada, thì Sport là một cái tên khác biệt nhất so với những người anh em đồng trang lứa, và cũng là một nhân vật “thiếu” DNA Prada nhất trong kho nước hoa của nhà mốt đến từ Ý.<br>Prada Luna Rossa Sport Eau de Toilette không khai thác mùi hương từ những nốt quen thuộc với fan của Prada, là Diên vĩ, Phấn hay Hổ phách, cũng không phải là đất diễn của các nốt hương tổng hợp đại diện cho một xu hướng mùi hương hiện đại như những người anh em Luna Rossa. Khởi đầu với Bách xù và Gừng, những nốt hương quen thuộc với người yêu nước hoa cổ điển, mở ra một chút tươi thanh lịch, gọn gàng và sạch sẽ. Oải hương vẫn nắm giữ vị trí trung tâm trong Luna Rossa Sport, gia tăng màu sắc thanh lịch cổ điển trong mùi hương, với một thái độ nhã nhặn nền nã. Cùng với Oải hương, mùi hương này đem tới một vị ngọt dịu, hơi ngậy và mềm mại, đem lại cảm giác mãnh liệt hơn để đúng với cái tên Sport, được tạo thành bởi Vanilla và Đậu Tonka.<br>Thật khó hiểu khi với mùi hương này, Prada lại đặt cho nó một cái tên là Sport. Nhưng Luna Rossa Sport là một chai nước hoa dễ chịu, nịnh mũi và có thể đáp ứng bất kỳ yêu cầu nào đến từ các quý ông. Đó là sự ngọt ngào, dễ mến và nét thanh lịch chỉn chu.', 'products/Prada/Prada%20Luna%20Rossa%20Sport%20EDT/prada-luna-rossa-sport-edt.jpg', 10, 1, 16, NULL);
INSERT INTO `san_pham` (`id`, `ten_san_pham`, `gia_ban`, `dung_tich_ml`, `nhom_huong`, `gioi_tinh_phu_hop`, `phong_cach`, `xuat_xu`, `nam_phat_hanh`, `mo_ta`, `duong_dan_hinh_anh`, `so_luong_ton`, `id_danh_muc`, `id_thuong_hieu`, `ngay_xoa`) VALUES
(45, 'Prada Paradoxe Virtual Flower', 4280000.00, 90, 'Hương Hoa Nhài, Hoa Cam Đắng, Cam Bergamot', 'nữ', 'Nhẹ Nhàng, Tươi Mát, Cuốn Hút', 'Pháp', 2024, 'Khi hương hoa trắng chạm đến thế giới mộng ảo<br>Ra mắt năm 2024, Paradoxe Virtual Flower là chương tiếp theo đầy mới mẻ của nhà mốt Prada trong bộ sưu tập Paradoxe, nơi vẻ đẹp nữ tính được tái hiện qua lăng kính hiện đại cùng sự tươi trẻ giàu năng lượng. Mùi hương là một đóa hoa trắng bước ra từ giấc mơ, nhẹ nhàng nhưng vẫn đầy ấn tượng, như một lực chọn đẹp của những cô nàng yêu sự tinh tế mà chẳng ngại khác biệt.<br>Mở đầu là Cam Bergamot từ Calabria, một khởi đầu mát lành, sáng bừng và mang lại cảm giác như làn gió đầu ngày lướt qua làn da. Sự tươi mát ấy là tấm nền tuyệt đẹp dẫn tới sự bung nở của Nhài và Hoa cam: nữ tính, mềm mại và thoảng chút ngọt, như một nụ cười nhẹ luôn khắc dấu trong tim nếu lỡ lọt tầm mắt. Sự nữ tính đẹp đẽ ấy lan tỏa trên lớp nền gợi cảm trong trẻo được thiết kế bởi Xạ hương trắng và Hạt vông vang (Một loại \'xạ hương\' thực vật), để viết lại một điểm kết cho Paradoxe Virtual Flower bằng sự ấm áp, như mùi da sạch thoảng hương hoa, lưu luyến và đầy cảm xúc.<br>Virtual Flower là lựa chọn lý tưởng cho những ngày bạn muốn bản thân trở nên nhẹ tênh, tự do nhưng vẫn có điểm nhấn rất riêng. Thích hợp với mùa xuân - hè, cả ban ngày lẫn buổi tối, như là một hương thơm đặc trưng ấn tượng mà không phô trương, và luôn khiến người khác muốn lại gần thêm chút nữa.', 'products/Prada/Prada%20Paradoxe%20Virtual%20Flower/prada-paradoxe-virtual-flower.jpg', 10, 2, 16, NULL),
(46, 'Salvatore Ferragamo Savane di Seta', 2816000.00, 100, 'Bơ diên vĩ, Gỗ đàn hương, Hạt cà rốt', 'unisex', 'Cuốn hút, Nổi bật, Tinh tế', 'Ý', 2021, 'Có bao giờ bạn bắt gặp một vẻ đẹp bất bình thường vì quá đơn giản chưa? Tối giản có thể là một xu hướng, nhưng Savane di Seta còn bình dị hơn cả tối giản nữa. Tất nhiên, nói thế không có nghĩa mùi hương này được tạo nên xuề xòa hay không công kỹ, chỉ là vẻ đẹp này quá lạ lẫm so với hàng hà sa số những “bình hoa di động” trên thị trường hiện nay.<br>Không có lấy một nốt hương Hoa hay Trái cây, nhưng Savane di Seta có thể mau chóng khiến ta thấy dễ chịu, được ve vuốt với hương vị bồng bềnh, hơi phấn của Rễ cây diên vĩ. Tầng hương phấn này của Rễ cây diên vĩ không quá nữ tính, hơi ướm một chút đất và có những nét màu tối nếu ngửi kỹ.<br>Để làm dày thêm và tạo chiều sâu cho tổng thể mùi hương, Savane di Seta đã chỉ sử dụng đúng duy nhất mùi hương của Gỗ đàn hương với tông vị ấm áp, ngọt đậm đặc trưng. Thế đấy, chỉ với vẹn vẹn những nốt hương này thôi, đơn giản quá sức tưởng tượng, nhưng ấn tượng mà bạn sẽ có với Savane di Seta không hề đơn giản như vậy đâu.', 'products/Salvatore%20Ferragamo/Salvatore%20Ferragamo%20Savane%20di%20Seta/salvatore-ferragamo-savane-di-seta.jpg', 18, 3, 28, NULL),
(47, 'Thierry Mugler Alien', 3200000.00, 100, 'Hoa nhài, Hổ phách, Gỗ', 'nữ', 'Bí ẩn, Quyến rũ, Nổi bật', 'Pháp', 2005, 'Thierry Mugler là một người có cá tính mạnh mẽ, thể hiện rõ qua tất cả những thứ ông tạo ra, từ những bộ trang phục ông đã từng thiết kế, hay những mùi hương khi chuyển sang tập trung vào Mugler Perfumes. Nước hoa của Mugler phần lớn đều được đón nhận nồng nhiệt từ người dùng, hoặc là những mùi hương được giới chuyên sâu đánh giá cao như dòng A*Men, Mirror Mirror hay đặc biệt là Les Exceptions, hoặc là thỏa mãn được nhu cầu của đại chúng và trở thành một “best-seller”. Alien là một cái tên thỏa mãn đại chúng như vậy, nổi tiếng và trở thành một di sản đặc trưng của Mugler.<br>Alien nối tiếp sự thành công của người chị Angel đi trước và đã duy trì được danh tiếng của Thierry Mugler trong ngành nước hoa, với một thủ pháp làm hương ngược lại hoàn toàn. Alien đánh vào sự đơn giản, không phải một mùi hương ngọt ngào đầy phức tạp như Angel, mà khai thác vẻ đẹp nữ tính gợi cảm của hương thơm chỉ trong hai nốt: Hoa nhài và Nhựa hổ phách, cùng một chút xêu gia giảm bằng hương Gỗ. Alien đại diện cho sự hỗn mang đúng với màu tím huyền ảo của chai nước hoa này, với mùi hương chuyển qua lại giữa các sắc thái cảm xúc. Khi thì tươi tắn mềm mại với Nhài, khi lại gợi cảm, quyến rũ và tối màu hơn với Nhựa hương, lúc lại lơ lửng ở giữa, tạo ra cảm xúc thú vị cho người thưởng thức. Một mùi hương không phức tạp, nhưng có thái độ, ở giữa và biến chuyển qua lại, vừa ngây thơ hiền dịu mà lại rất “đàn bà”.<br>Alien thực tế không dành cho số đông, và ai yêu thích nó thường đều đã xây dựng được những cá tính riêng về việc chọn mùi hương cho bản thân. Alien đủ đậm thẫm, cũng chẳng thiếu nồng nhiệt, mạnh mẽ bung tỏa trên làn da.', 'products/Thierry%20Mugler/Thierry%20Mugler%20Alien/thierry-mugler-alien-edp.jpg', 15, 2, 26, NULL),
(48, 'Tom Ford Ombré Leather', 4500000.00, 100, 'Hương da thuộc', 'unisex', 'Sang trọng, Ấm áp, Cổ điển', 'Mỹ', 2018, 'Nước hoa Tom Ford Ombré Leather không phải là một mùi hương dễ ngửi, nhưng càng ngửi thì bạn sẽ càng yêu thích. Mùi hương đâu đó là dư âm của sự cổ điển, tươi mát và cũng chính sự cá tính này đã tạo nên sự không giới hạn để cả chàng và nàng đều có thể thưởng thức.', 'products/Tom%20Ford/Tom%20Ford%20Ombré%20Leather/tom-ford-ombre-leather-100ml.jpg', 15, 3, 8, NULL),
(49, 'Tom Ford Ombre Leather Parfum', 4250000.00, 100, 'Da thuộc, Lá hoa violet, Hoa nhài Sambac', 'unisex', 'Sang trọng, Thanh lịch, Quyến rũ, Gây nghiện', 'Mỹ, Thụy Sĩ', 2021, 'Sự xuất hiện của siêu phẩm Tom Ford Ombre Leather vào năm 2018 đã tạo ra một làn sóng dữ dội bởi mùi hương Da Thuộc chủ đạo vô cùng nam tính.<br>Sau 3 năm, tức là năm 2021, một phiên bản mới nhất được ra mắt - Tom Ford Ombre Leather Parfum cùng những hình thái mới của một gã đàn ông được mô phỏng từ một khía cạnh khác biệt và vô cùng đặc biệt.<br>Tầng hương đầu xuất hiện cùng với mùi hương Gỗ Tuyết Tùng và Lá Violet đã mang đến một hỗn hợp cân bằng đầy tinh tế, mùi hương thanh ngọt hòa cùng hương trầm ấm của gỗ là một điều vô cùng táo bạo để mang lại một sự khác biệt đầy cá tính.<br>Phía tầng hương sau là sự xuất hiện của Hoa Nhài Sambac, có lẽ ở phiên bản Parfum dù được nâng cấp ở phần nồng độ tinh dầu để mùi hương trở nên mạnh mẽ hơn thì phần xuất hiện của Hoa Nhài Sambac lại đi ngược lại hoàn toàn với định hướng mùi hương của Ombre Leather, phần hoa Nhài có phần dày mùi hương thanh tươi hơn như 1 cách để nhìn vào gã đàn ông bụi bặm phong trần kia bằng một khía cạnh sâu sắc hơn với tâm hồn cũng yếu đuối cũng muốn được đồng cảm.<br>Ombre Leather Parfum là nguồn cảm hứng bất tận đến từ khu vực miền Tây Nước Mỹ, nơi mà sự bụi bặm và phong trần của những gã cao bồi với chiếc áo da đang trầm ngâm với những điếu thuốc dang dở.<br>Mùi hương của Da Thuộc được khai thác rõ rệt và mạnh mẽ hơn để tạo ra những dấu ấn đúng tinh thần như cái tên đã được sinh ra - Ombre Leather Parfum. Ngoài ra, sự kết hợp với thuốc lá đã thực sự khiến Ombre Leather một lần nữa lại ghi dấu ấn trong lòng giới mộ điệu mùi hương.', 'products/Tom%20Ford/Tom%20Ford%20Ombre%20Leather%20Parfum/ombre-leather-parfum-tom-ford.jpg', 8, 3, 8, NULL),
(50, 'Valentino Donna Born In Roma Green Stravaganza', 3980000.00, 100, 'Hương Trà Đen Lapsang Souchong, Hoa Nhài, Vanila', 'nữ', 'Ngọt Ngào, Tươi Mới, Quyến Rũ', 'Pháp', 2024, 'Ra mắt vào năm 2024, Valentino Donna Born in Roma Green Stravaganza là lời thì thầm đầy sức sống đến từ kinh đô hoa lệ, nơi những khu vườn thanh mát của Rome được tái hiện qua từng tầng hương. Với thiết kế chai màu xanh lục bắt mắt, cùng họa tiết Rockstud đặc trưng, đây không chỉ là một chai nước hoa, mà là một tuyên ngôn phong cách cho người phụ nữ hiện đại, biết tận hưởng cuộc sống theo cách riêng.<br>Mùi hương mở đầu bằng trà Lapsang Souchong, nhẹ nhàng, có chút khói quyện đầy thư giãn, như một buổi chiều thảnh thơi giữa khu vườn xanh. Khi hương trà dần dịu xuống, hoa nhài bắt đầu nở rộ mềm mại, trong trẻo và nữ tính đến khó quên. Cuối cùng, tất cả được ôm ấp trong lớp nền vanilla ấm ngọt, dịu dàng lưu lại trên da như dư âm của một ánh nhìn tinh tế.<br>Donna Green Stravaganza là lựa chọn lý tưởng cho những nàng yêu nét đẹp tự nhiên, không gồng gánh chỉ cần một lớp hương nhẹ đủ khiến người khác ngoái nhìn. Phù hợp cho cả ban ngày lẫn buổi tối, đặc biệt là những dịp bạn muốn mang theo mình một chút tự do, thanh mát và nữ tính có chiều sâu.', 'products/Valentino/Valentino%20Donna%20Born%20In%20Roma%20Green%20Stravaganza/valentino-donna-born-in-roma-green-stravaganza.jpg', 20, 2, 18, NULL),
(51, 'Valentino Uomo Born In Roma Green Stravaganza', 3050000.00, 100, 'Hương Cam Bergamot, Cà Phê, Cỏ Hương Bài', 'nam', 'Tinh Tế, Nam Tính, Nhẹ Nhàng', 'Pháp', 2024, 'Khi bản lĩnh được tỏa sáng giữa vườn xanh rực rỡ<br>Ra mắt vào năm 2024, Valentino Uomo Born in Roma Green Stravaganza là một chương mới đầy năng lượng trong bộ sưu tập Born in Roma. Lấy cảm hứng từ những khu vườn đậm chất Ý giữa lòng thành Rome, mùi hương là sự tôn vinh cá tính tự do, sôi nổi và bản lĩnh của người đàn ông hiện đại, luôn chọn sống khác biệt.<br>Ngay từ những nốt đầu tiên, Green Stravaganza mở ra với cam Bergamot vùng Calabria, tươi sáng, rạng rỡ như ánh nắng Địa Trung Hải. Khi lớp hương lắng lại, nốt cà phê dần hiện lên, với chút mạnh mẽ, gợi cảm và tràn đầy năng lượng, như nhịp sống sôi động của thành phố. Kết thúc của hương thơm là sự kết hợp tinh tế giữa Cỏ hương bài ấm khô nồng nàn đầy quyến rũ, để lại một dấu ấn vừa chín chắn vừa cá tính.<br>Đây là mùi hương dành cho những người đàn ông yêu thích sự bứt phá nhưng vẫn giữ vững bản sắc riêng. Phù hợp trong những buổi hẹn hò, tiệc tối hay bất kỳ khoảnh khắc nào bạn muốn tỏa sáng một cách lịch lãm, cuốn hút và đầy khác biệt.', 'products/Valentino/Valentino%20Uomo%20Born%20In%20Roma%20Green%20Stravaganza/valentino-uomo-born-in-roma-green-stravaganza.jpg', 0, 1, 18, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuong_hieu`
--

CREATE TABLE `thuong_hieu` (
  `id` int(11) NOT NULL,
  `ten_thuong_hieu` varchar(100) NOT NULL,
  `quoc_gia` varchar(100) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `duong_dan_logo` varchar(255) DEFAULT NULL,
  `ngay_xoa` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thuong_hieu`
--

INSERT INTO `thuong_hieu` (`id`, `ten_thuong_hieu`, `quoc_gia`, `mo_ta`, `duong_dan_logo`, `ngay_xoa`) VALUES
(1, 'Christian Dior', 'France', 'Christian Dior là thương hiệu thời trang cao cấp đến từ Pháp, nổi tiếng với các dòng nước hoa sang trọng, tinh tế và đầy quyến rũ.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-christian-dior.png', NULL),
(2, 'Chanel', 'France', 'Chanel là biểu tượng của sự thanh lịch và đẳng cấp, mang đến những mùi hương kinh điển vượt thời gian.', 'https://upload.wikimedia.org/wikipedia/en/9/92/Chanel_logo_interlocking_cs.svg', NULL),
(3, 'Gucci', 'Italy', 'Gucci là thương hiệu thời trang xa xỉ của Ý với phong cách táo bạo, hiện đại và đầy cá tính trong từng mùi hương.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-gucci.png', NULL),
(4, 'Versace', 'Italy', 'Versace mang đến những dòng nước hoa gợi cảm, mạnh mẽ và đậm chất Ý dành cho những cá tính nổi bật.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-versace.png', NULL),
(5, 'Burberry', 'United Kingdom', 'Burberry kết hợp tinh thần cổ điển Anh Quốc với phong cách hiện đại, tạo nên những mùi hương thanh lịch.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-burberry.png', NULL),
(6, 'Yves Saint Laurent', 'France', 'Yves Saint Laurent nổi bật với các dòng nước hoa táo bạo, quyến rũ và đậm chất thời trang Pháp.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-yves-saint-laurent.png', NULL),
(7, 'Creed', 'France', 'Creed là thương hiệu nước hoa niche cao cấp, nổi tiếng với nguyên liệu tự nhiên và lịch sử lâu đời.', 'https://upload.wikimedia.org/wikipedia/commons/c/cc/Creed_%28Logo%29.png', NULL),
(8, 'Tom Ford', 'United States', 'Tom Ford mang đến những mùi hương sang trọng, gợi cảm và đầy cá tính dành cho giới thượng lưu.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-tom-ford.png', NULL),
(9, 'Calvin Klein', 'United States', 'Calvin Klein nổi tiếng với các dòng nước hoa tối giản, trẻ trung và dễ sử dụng hàng ngày.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-calvin-klein.png', NULL),
(10, 'Jo Malone', 'United Kingdom', 'Jo Malone mang phong cách Anh Quốc tinh tế với các mùi hương nhẹ nhàng, thanh khiết và dễ layer.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-jo-malone.png', NULL),
(11, 'Maison Francis Kurkdjian', 'France', 'Maison Francis Kurkdjian nổi tiếng với những mùi hương niche tinh xảo, sang trọng và đầy nghệ thuật.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-maison-francis-kurkdjian.png', NULL),
(12, 'Giorgio Armani', 'Italy', 'Giorgio Armani mang phong cách Ý tinh tế, tối giản và sang trọng trong từng mùi hương.', 'https://upload.wikimedia.org/wikipedia/commons/7/7a/Giorgio_Armani.svg', NULL),
(13, 'Lancôme', 'France', 'Lancôme là thương hiệu Pháp nổi tiếng với những dòng nước hoa nữ tính, quyến rũ và thanh lịch.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-lancome.png', NULL),
(14, 'Hermès', 'France', 'Hermès mang đến những mùi hương tinh tế, nghệ thuật và mang đậm dấu ấn thủ công Pháp.', 'https://upload.wikimedia.org/wikipedia/en/e/e4/Herm%C3%A8s.svg', NULL),
(15, 'Bvlgari', 'Italy', 'Bvlgari kết hợp sự sang trọng Ý với phong cách táo bạo và hiện đại trong từng chai nước hoa.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-bvlgari.png', NULL),
(16, 'Prada', 'Italy', 'Prada mang đến những mùi hương độc đáo, hiện đại và mang tính nghệ thuật cao.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-prada.png', NULL),
(17, 'Montblanc', 'Germany', 'Montblanc nổi tiếng với phong cách nam tính, lịch lãm và sang trọng.', 'https://upload.wikimedia.org/wikipedia/en/2/2c/Montblanc_Logo.svg', NULL),
(18, 'Valentino', 'Italy', 'Valentino mang đến những mùi hương lãng mạn, sang trọng và đậm chất Ý.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-valentino.png', NULL),
(19, 'Penhaligon’s', 'United Kingdom', 'Penhaligon’s là thương hiệu nước hoa cổ điển Anh Quốc với phong cách hoàng gia và độc đáo.', 'https://upload.wikimedia.org/wikipedia/commons/0/0f/Penhaligon%27s_logo.svg', NULL),
(20, 'Diptyque', 'France', 'Diptyque nổi tiếng với các mùi hương nghệ thuật, tinh tế và giàu cảm xúc.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-diptyque.png', NULL),
(21, 'Byredo', 'Sweden', 'Byredo mang phong cách Bắc Âu tối giản, hiện đại và đầy chiều sâu cảm xúc.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-byredo.png', NULL),
(22, 'Le Labo', 'United States', 'Le Labo nổi tiếng với nước hoa thủ công, cá nhân hóa và đậm chất nghệ thuật.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-le-labo.png', NULL),
(23, 'Narciso Rodriguez', 'United States', 'Narciso Rodriguez mang phong cách gợi cảm, tinh tế và rất đặc trưng.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-narciso-rodriguez.png', NULL),
(24, 'Jean Paul Gaultier', 'France', 'Jean Paul Gaultier nổi bật với phong cách táo bạo, cá tính và không giới hạn.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-jean-paul-gaultier.png', NULL),
(25, 'Paco Rabanne', 'Spain', 'Paco Rabanne mang đến những mùi hương mạnh mẽ, trẻ trung và đầy năng lượng.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-paco-rabanne.png', NULL),
(26, 'Thierry Mugler', 'France', 'Thierry Mugler nổi tiếng với các mùi hương độc lạ, đậm cá tính và khác biệt.', 'https://logohistory.net/wp-content/uploads/2024/02/Thierry-Mugler-Logo.png', NULL),
(27, 'Dolce & Gabbana', 'Italy', 'Dolce & Gabbana mang phong cách Địa Trung Hải gợi cảm và sang trọng.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-dolce-gabbana.png', NULL),
(28, 'Salvatore Ferragamo', 'Italy', 'Salvatore Ferragamo mang phong cách cổ điển Ý kết hợp sự tinh tế hiện đại.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-salvatore-ferragamo.png', NULL),
(29, 'Carolina Herrera', 'Venezuela', 'Carolina Herrera nổi tiếng với những mùi hương sang trọng, gợi cảm và nữ tính.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-carolina-herrera.png', NULL),
(30, 'Victoria’s Secret', 'United States', 'Victoria’s Secret mang đến những mùi hương ngọt ngào, trẻ trung và quyến rũ.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-victorias-secret.png', NULL),
(31, 'Elizabeth Arden', 'United States', 'Elizabeth Arden nổi tiếng với các mùi hương thanh lịch, cổ điển và dễ dùng.', 'https://logoeps.com/wp-content/uploads/2012/10/elizabeth-arden-logo-vector.png', NULL),
(32, 'Ralph Lauren', 'United States', 'Ralph Lauren mang phong cách Mỹ cổ điển, nam tính và đầy tinh thần tự do.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-ralph-lauren.png', NULL),
(33, 'Kenzo', 'France', 'Kenzo mang đến những mùi hương sáng tạo, trẻ trung và đầy màu sắc.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-kenzo.png', NULL),
(34, 'Montale', 'France', 'Montale nổi tiếng với các mùi hương đậm, lưu hương lâu và mang phong cách Trung Đông.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-montale.png', NULL),
(35, 'Amouage', 'Oman', 'Amouage là thương hiệu niche cao cấp với những mùi hương xa xỉ và phức tạp.', 'https://thefragranceclinic.com.au/wp-content/uploads/2023/01/amouage-australia.png', NULL),
(36, 'Jimmy Choo', 'United Kingdom', 'Jimmy Choo mang đến những mùi hương gợi cảm, hiện đại và đầy quyến rũ.', 'https://1000logos.net/wp-content/uploads/2022/07/Jimmy-Choo-Logo-1996.png', NULL),
(37, 'Louis Vuitton', 'France', 'Louis Vuitton mang đến những mùi hương sang trọng, đậm chất du hành và di sản Pháp.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-louis-vuitton.png', NULL),
(38, 'Marc Jacobs', 'United States', 'Marc Jacobs nổi tiếng với những mùi hương trẻ trung, sáng tạo và hiện đại.', 'https://theme.hstatic.net/1000340570/1000964732/14/logo-brand-marc-jacobs.png', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_don_hang` (`id_don_hang`),
  ADD KEY `id_san_pham` (`id_san_pham`);

--
-- Chỉ mục cho bảng `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_gio_hang` (`id_gio_hang`),
  ADD KEY `id_san_pham` (`id_san_pham`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `danh_sach_yeu_thich`
--
ALTER TABLE `danh_sach_yeu_thich`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nguoi_dung` (`id_nguoi_dung`),
  ADD KEY `id_san_pham` (`id_san_pham`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nguoi_dung` (`id_nguoi_dung`);

--
-- Chỉ mục cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nguoi_dung` (`id_nguoi_dung`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_danh_muc` (`id_danh_muc`),
  ADD KEY `id_thuong_hieu` (`id_thuong_hieu`);

--
-- Chỉ mục cho bảng `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `danh_sach_yeu_thich`
--
ALTER TABLE `danh_sach_yeu_thich`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT cho bảng `thuong_hieu`
--
ALTER TABLE `thuong_hieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id`),
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`);

--
-- Các ràng buộc cho bảng `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD CONSTRAINT `chi_tiet_gio_hang_ibfk_1` FOREIGN KEY (`id_gio_hang`) REFERENCES `gio_hang` (`id`),
  ADD CONSTRAINT `chi_tiet_gio_hang_ibfk_2` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`);

--
-- Các ràng buộc cho bảng `danh_sach_yeu_thich`
--
ALTER TABLE `danh_sach_yeu_thich`
  ADD CONSTRAINT `danh_sach_yeu_thich_ibfk_1` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`),
  ADD CONSTRAINT `danh_sach_yeu_thich_ibfk_2` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`);

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`);

--
-- Các ràng buộc cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `gio_hang_ibfk_1` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`);

--
-- Các ràng buộc cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`id_danh_muc`) REFERENCES `danh_muc` (`id`),
  ADD CONSTRAINT `san_pham_ibfk_2` FOREIGN KEY (`id_thuong_hieu`) REFERENCES `thuong_hieu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

