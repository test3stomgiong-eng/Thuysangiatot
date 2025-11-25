-- ====================================================
-- 1. THIẾT LẬP CƠ SỞ DỮ LIỆU
-- ====================================================
-- Xóa database cũ nếu tồn tại để tránh lỗi
DROP DATABASE IF EXISTS `ts_aqua`;

-- Tạo database mới với bảng mã UTF-8 (Hỗ trợ tiếng Việt)
CREATE DATABASE `ts_aqua` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ts_aqua`;

-- ====================================================
-- 2. NHÓM NGƯỜI DÙNG & PHÂN QUYỀN
-- ====================================================

-- Bảng Nhân viên / Quản trị viên (Truy cập trang Admin)
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL, -- Mật khẩu sẽ được mã hóa (MD5/Bcrypt)
  `role` enum('admin','sale','warehouse','editor') DEFAULT 'sale', -- Phân quyền
  `avatar` varchar(255) NULL,
  `status` tinyint(1) DEFAULT 1, -- 1: Đang làm, 0: Nghỉ việc
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng Khách hàng (Người mua hàng trên web)
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL UNIQUE,
  `email` varchar(100) NULL,
  `password` varchar(255) NOT NULL,
  `address` text NULL,
  `avatar` varchar(255) NULL,
  `status` tinyint(1) DEFAULT 1, -- 1: Hoạt động, 0: Bị khóa
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================================
-- 3. NHÓM SẢN PHẨM (PRODUCTS)
-- ====================================================

-- Bảng Danh mục Sản phẩm (Hỗ trợ đa cấp Cha - Con)
CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT 0, -- 0: Là danh mục gốc, >0: Là con của ID đó
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,  -- Link thân thiện (vd: men-vi-sinh)
  `description` text NULL,
  `status` tinyint(1) DEFAULT 1, -- 1: Hiện, 0: Ẩn
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng Sản phẩm
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL, -- Thuộc danh mục nào
  `name` varchar(255) NOT NULL,
  `sku` varchar(50) NULL,         -- Mã sản phẩm (SP001)
  `price` decimal(15,2) NOT NULL, -- Giá bán niêm yết
  `sale_price` decimal(15,2) DEFAULT 0, -- Giá khuyến mãi
  `main_image` varchar(255) NULL, -- Ảnh đại diện chính (1 hình)
  `stock` int(11) DEFAULT 0,      -- Số lượng tồn kho
  `summary` text NULL,            -- Mô tả ngắn
  `description` longtext NULL,    -- Nội dung chi tiết (HTML từ CKEditor)
  `status` tinyint(1) DEFAULT 1,  -- 1: Đang bán, 0: Ẩn, 2: Hết hàng
  `views` int(11) DEFAULT 0,      -- Lượt xem
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `product_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng Ảnh phụ (Gallery - Lưu nhiều hình cho 1 sản phẩm)
CREATE TABLE `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0, -- Thứ tự hiển thị
  PRIMARY KEY (`id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 1. Xóa bỏ 2 cột cũ (Mô tả ngắn & Mô tả chi tiết)
ALTER TABLE `products`
  DROP COLUMN `summary`,
  DROP COLUMN `description`;

-- 2. Chèn thêm 4 cột mới vào sau cột 'stock' (Số lượng tồn kho)
ALTER TABLE `products`
  ADD COLUMN `ingredients` TEXT NULL COMMENT 'Thành phần' AFTER `stock`,
  ADD COLUMN `uses` TEXT NULL COMMENT 'Công dụng' AFTER `ingredients`,
  ADD COLUMN `usage_instruction` TEXT NULL COMMENT 'Hướng dẫn sử dụng' AFTER `uses`,
  ADD COLUMN `note` TEXT NULL COMMENT 'Lưu ý riêng' AFTER `usage_instruction`;
-- ====================================================
-- 4. NHÓM ĐƠN HÀNG (ORDERS)
-- ====================================================

-- Bảng Đơn hàng (Thông tin chung)
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL UNIQUE, -- Mã đơn hàng (VD: DH20251122)
  `customer_id` int(11) NULL,         -- Có thể NULL (nếu khách mua không cần đăng ký)
  `staff_id` int(11) NULL,            -- Nhân viên xử lý đơn này
  `customer_name` varchar(100) NOT NULL, -- Lưu cứng tên lúc đặt (phòng khi khách đổi tên)
  `customer_phone` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `total_money` decimal(15,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'COD', -- COD, Banking, Momo
  `status` enum('pending','shipping','completed','cancelled') DEFAULT 'pending',
  `note` text NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`staff_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng Chi tiết đơn hàng (Lưu từng món hàng trong đơn)
CREATE TABLE `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL, -- Lưu cứng tên SP
  `price` decimal(15,2) NOT NULL,       -- Giá tại thời điểm mua
  `quantity` int(11) NOT NULL,
  `total_price` decimal(15,2) NOT NULL, -- Thành tiền dòng này (price * quantity)
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================================
-- 5. NHÓM TIN TỨC (NEWS)
-- ====================================================

-- Bảng Danh mục Tin tức (Riêng biệt với danh mục SP)
CREATE TABLE `news_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng Bài viết
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NULL,
  `summary` text NULL,          -- Sapo/Mô tả ngắn
  `content` longtext NULL,      -- Nội dung chính (CKEditor)
  `author_id` int(11) NOT NULL, -- Người viết (Link sang bảng users)
  `views` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1, -- 1: Đăng, 0: Nháp
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `news_categories`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================================
-- 6. CẤU HÌNH HỆ THỐNG (SETTINGS)
-- ====================================================

-- Lưu các cài đặt chung (Logo, Hotline, Email, SEO...)
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(50) NOT NULL UNIQUE,  -- Tên cấu hình (vd: site_hotline)
  `config_value` text NULL,                  -- Giá trị
  `description` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ====================================================
-- 7. DỮ LIỆU MẪU (SEED DATA)
-- ====================================================

-- Thêm Admin mặc định (Mật khẩu: 123456)
INSERT INTO `users` (`fullname`, `email`, `password`, `role`, `status`) VALUES 
('Administrator', 'admin@gmail.com', '123456', 'admin', 1);

-- Thêm Cấu hình mặc định
INSERT INTO `settings` (`config_key`, `config_value`, `description`) VALUES
('site_title', 'TS-AQUA Pharma - Thuốc Thủy Sản', 'Tiêu đề Website'),
('site_logo', 'assets/img/logo.png', 'Logo'),
('site_hotline', '1800 55 88 99', 'Số Hotline'),
('site_email', 'hotro@tsaqua.com', 'Email liên hệ'),
('site_address', '123 Đường A, Phường B, TP. Sóc Trăng', 'Địa chỉ công ty'),
('social_facebook', '#', 'Link Fanpage'),
('social_zalo', '#', 'Link Zalo');

-- Thêm Danh mục Sản phẩm mẫu
INSERT INTO `product_categories` (`name`, `slug`) VALUES 
('Thuốc Thủy Sản', 'thuoc-thuy-san'),
('Dinh Dưỡng & Khoáng', 'dinh-duong-khoang'),
('Men Vi Sinh', 'men-vi-sinh'),
('Xử Lý Nước', 'xu-ly-nuoc');

-- Thêm Danh mục Tin tức mẫu
INSERT INTO `news_categories` (`name`, `slug`) VALUES 
('Kỹ thuật nuôi tôm', 'ky-thuat-nuoi-tom'),
('Tin tức thị trường', 'tin-tuc-thi-truong'),
('Phác đồ điều trị', 'phac-do-dieu-tri');