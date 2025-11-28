-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: ts_aqua
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin','staff') DEFAULT 'customer',
  `address` text,
  `avatar` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (4,'Lê Khánh Duy','0873942510','test.3stomgiong@gmail.com','$2y$10$.4EAnBPCZKFGP03fJ5o.Ee00nUzO4/pNq6BrYcUkmSJe51qlTF6bu','admin',NULL,NULL,1,'2025-11-27 03:11:59'),(5,'Tăng Thị Kiều Oanh','0547865222','lkdff@gmail.com','$2y$10$RAnQ6/YtwgDud7qx1O1rI.OSjS0Rl3arQuo2kI5uM.qsiHXlqn3K.','customer',NULL,NULL,1,'2025-11-27 07:59:32');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `summary` text,
  `content` longtext,
  `author_id` int NOT NULL,
  `views` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `news_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_categories`
--

DROP TABLE IF EXISTS `news_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_categories`
--

LOCK TABLES `news_categories` WRITE;
/*!40000 ALTER TABLE `news_categories` DISABLE KEYS */;
INSERT INTO `news_categories` VALUES (1,0,'Kỹ thuật nuôi tôm','ky-thuat-nuoi-tom','2025-11-24 01:38:15'),(2,0,'Tin tức thị trường','tin-tuc-thi-truong','2025-11-24 01:38:15'),(3,0,'Phác đồ điều trị','phac-do-dieu-tri','2025-11-24 01:38:15');
/*!40000 ALTER TABLE `news_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,1,1,'3S - SIÊU KHOÁNG: Mau Lột Xác Cứng Vỏ',469000.00,2,938000.00),(2,2,5,'3S - BOTTOM USA: Sạch Đáy, Sạch Nước',199000.00,1,199000.00),(3,2,1,'3S - SIÊU KHOÁNG: Mau Lột Xác Cứng Vỏ',469000.00,1,469000.00);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `customer_id` int DEFAULT NULL,
  `staff_id` int DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `total_money` decimal(15,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'COD',
  `status` enum('pending','shipping','completed','cancelled') DEFAULT 'pending',
  `note` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `customer_id` (`customer_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'DH1764056433',NULL,NULL,'Lê Khánh Duy','0873942510','Sóc trăng',938000.00,'COD','shipping','Giao buổi chiều','2025-11-25 07:40:33'),(2,'DH1764316234',NULL,NULL,'Oanh','0547865222','Duyen hai tra vinh',668000.00,'COD','pending','D9iện thoai','2025-11-28 07:50:34');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `print_templates`
--

DROP TABLE IF EXISTS `print_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `print_templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `print_templates`
--

LOCK TABLES `print_templates` WRITE;
/*!40000 ALTER TABLE `print_templates` DISABLE KEYS */;
INSERT INTO `print_templates` VALUES (1,'Mẫu A4 Chuẩn','...Nội dung HTML mẫu A4 cũ của bạn...',0,'2025-11-28 08:20:37'),(2,'Mẫu K80 (Máy in nhiệt)','<div style=\"width: 80mm; font-size: 12px;\">...Nội dung K80...</div>\r\n',1,'2025-11-28 08:20:37'),(4,'Hoa don 1','<p>{MA_DON}{NGAY_TAO}</p>\r\n<p>{TEN_KHACH}{SDT_KHACH}</p>\r\n<p>{DIA_CHI}</p>\r\n<p>{BANG_HANG_CHI_TIET}</p>\r\n<p>{TONG_TIEN_HANG}</p>\r\n<p>{PHI_SHIP}</p>\r\n<p>{TONG_CONG}</p>',0,'2025-11-28 09:33:59');
/*!40000 ALTER TABLE `print_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (1,0,'Thuốc Thuỷ Sản','thuoc-thuy-san','Danh mục thuốc',1,'2025-11-28 02:47:30'),(2,1,'Dinh Dưỡng & Khoáng','dinh-duong-khoang','',1,'2025-11-24 01:38:15'),(4,1,'Hỗ Trợ Gan - Ruột','ho-tro-gan-ruot','Nhóm sản phẩm hỗ trợ Gan và Ruột tôm thẻ chân trắng là các chế phẩm đặc biệt được phát triển nhằm tối ưu hóa sức khỏe và năng suất nuôi tôm. Các sản phẩm này bao gồm hỗn hợp các vi sinh vật có lợi, Enzyme và các chất dinh dưỡng thiết yếu, giúp cải thiện chức năng gan và hệ tiêu hóa của tôm. Vi sinh vật có lợi cạnh tranh với mầm bệnh, phân hủy chất hữu cơ và tăng cường hệ miễn dịch, trong khi Enzyme hỗ trợ tiêu hóa, phân giải Protein, Lipit và Carbohydrate, giúp tôm hấp thu dưỡng chất một cách hiệu quả. Nhờ vào sự kết hợp độc đáo và khoa học, nhóm sản phẩm này không chỉ cải thiện môi trường ao nuôi mà còn thúc đẩy sự tăng trưởng và phát triển toàn diện của tôm thẻ chân trắng, mang lại năng suất cao và hiệu quả kinh tế bền vững cho người nuôi.',1,'2025-11-24 01:38:15'),(5,1,'Enzyme & Vi Sinh Xử Lý Môi Trường','enzyme-vi-sinh-xu-ly-moi-truong','Nhóm sản phẩm Vi Sinh Và Enzyme cho tôm thẻ chân trắng là giải pháp tiên tiến và hiệu quả trong việc quản lý và tối ưu hóa môi trường nuôi trồng. Các chế phẩm vi sinh bao gồm các chủng vi khuẩn có lợi như Bacillus, Lactobacillus, và Nitrobacter, giúp phân hủy chất hữu cơ, giảm thiểu amoniac và nitrit, và cạnh tranh với mầm bệnh, từ đó cải thiện chất lượng nước và duy trì môi trường sống an toàn cho tôm. Bên cạnh đó, các Enzyme như Protease, Lipase, và Amylase hỗ trợ quá trình tiêu hóa, giúp tôm hấp thụ dưỡng chất tốt hơn và tăng trưởng nhanh hơn. Sự kết hợp giữa vi sinh và enzyme không chỉ giúp kiểm soát các yếu tố gây ô nhiễm trong ao nuôi mà còn tăng cường sức khỏe và khả năng đề kháng của tôm. Với công nghệ sản xuất hiện đại và thành phần được chọn lọc kỹ lưỡng, nhóm sản phẩm Vi Sinh và Enzyme cho tôm thẻ chân trắng là lựa chọn lý tưởng cho người nuôi, mang lại hiệu quả vượt trội và bền vững trong hoạt động nuôi trồng thủy sản. Giới thiệu về nhóm sản phẩm hỗ trợ Gan và Ruột tôm thẻ chân trắng',1,'2025-11-27 08:38:00'),(6,0,'Bài Viết Tổng Hợp','bai-viet-tong-hop','',1,'2025-11-27 08:51:00'),(7,6,'Tin tức thuỷ sản','tin-tuc-thuy-san','',1,'2025-11-27 08:51:18'),(8,1,'Dinh Dưỡng Cho Tôm','dinh-duong-cho-tom','Nhóm sản phẩm dinh dưỡng cho tôm thẻ chân trắng được phát triển với công thức đặc biệt nhằm cung cấp đầy đủ các dưỡng chất thiết yếu, giúp tôm phát triển nhanh chóng và khỏe mạnh. Các sản phẩm này bao gồm các loại thức ăn bổ sung chứa protein chất lượng cao, axit béo không bão hòa, vitamin, khoáng chất, và các axit amin thiết yếu. Chúng được chế biến theo công nghệ tiên tiến để đảm bảo khả năng tiêu hóa và hấp thu tối ưu, giúp tôm tăng trưởng mạnh mẽ, cải thiện sức đề kháng và tăng cường khả năng chống chịu bệnh tật. Bên cạnh đó, nhóm sản phẩm còn bao gồm các chất kích thích tăng trưởng tự nhiên và enzyme hỗ trợ tiêu hóa, giúp tôm tận dụng tối đa nguồn dinh dưỡng từ thức ăn. Với sự kết hợp hài hòa giữa các thành phần dinh dưỡng và phụ gia chất lượng cao, nhóm sản phẩm dinh dưỡng cho tôm thẻ chân trắng là giải pháp toàn diện, giúp người nuôi tối ưu hóa hiệu suất nuôi trồng và đảm bảo chất lượng tôm thương phẩm vượt trội.\r\n\r\nSẢN PHẨM ĐÃ XEM',1,'2025-11-27 09:02:48');
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'sieukhoang2.png',1),(2,1,'sieukhoang3.png',2),(3,5,'sp_1764299722_736.png',0),(4,5,'sp_1764299722_487.png',0),(5,5,'sp_1764300996_847.png',0),(6,6,'sp_1764312482_823.png',0);
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT '0.00',
  `main_image` varchar(255) DEFAULT NULL,
  `stock` int DEFAULT '0',
  `ingredients` text COMMENT 'Thành phần',
  `uses` text COMMENT 'Công dụng',
  `usage_instruction` text COMMENT 'Hướng dẫn sử dụng',
  `note` text COMMENT 'Lưu ý riêng',
  `status` tinyint(1) DEFAULT '1',
  `views` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,2,'3S - SIÊU KHOÁNG: Mau Lột Xác Cứng Vỏ','sp001',469000.00,0.00,'sieukhoang.png',2,'<ul><li>Canxi (min): 20%</li><li>Magie (min): 10%</li><li>Kali: 5%</li><li>Chất đệm vừa đủ: 1kg</li></ul>','<p>Giúp tôm lột xác đồng loạt, cứng vỏ nhanh sau 2 giờ.</p><p>Khắc phục hiện tượng <b>cong thân, đục cơ</b> do thiếu khoáng.</p><p>Ổn định màu nước, gây màu trà đẹp.</p>','<b>Định kỳ:</b> Dùng 1-2kg/1.000m3 nước (3 ngày/lần).<br><b>Khi tôm lột hoặc trời mưa:</b> Dùng 3kg/1.000m3 giúp cứng vỏ cấp tốc.','<span style=\"color:red; font-weight:bold;\">Lưu ý: Nên tạt vào ban đêm (20h-22h) để tôm hấp thu tốt nhất.</span>',1,0,'2025-11-24 08:52:44'),(5,5,'3S - BOTTOM USA: Sạch Đáy, Sạch Nước','',199000.00,0.00,'sp_1764300996_630.png',100,'<p>Bacillus spp (min) ..................................... 1X109&nbsp;cfu/kg</p>\r\n\r\n<p>Chất mang vừa đủ .................................... 1Kg</p>\r\n','<p>Sạch đ&aacute;y, b&oacute;ng nước, c&acirc;n bằng hệ tảo v&agrave; vi sinh.</p>\r\n','<p>Trước khi thả t&ocirc;m 3 - 4 ng&agrave;y: 454g/6.000m3&nbsp;nước.</p>\r\n\r\n<p>Xử l&yacute; định kỳ: 454g/5.0003&nbsp;nước.</p>\r\n\r\n<p>Ph&acirc;n hủy lap-lap, tẩy trắng đ&aacute;y ao dơ: 454g/4.000m3&nbsp;nước.</p>\r\n\r\n<p>Giảm diệt tảo độc: 454g/3.000m3&nbsp;nước.</p>\r\n','<p><strong>C&Aacute;CH SỬ DỤNG:</strong></p>\r\n\r\n<ul>\r\n	<li>- Pha sản phẩm với 30 l&iacute;t nước tạt đều khắp ao.</li>\r\n	<li>- D&ugrave;ng l&uacute;c 6 - 8h s&aacute;ng: sạch v&agrave; ổn định m&agrave;u nước.</li>\r\n	<li>- D&ugrave;ng l&uacute;c 19 - 20h: sạch đ&aacute;y, giảm tảo, nước chuyển sang m&agrave;u bạc.</li>\r\n	<li>- Hiệu quả sau 12 giờ sử dụng.</li>\r\n</ul>\r\n\r\n<p><img alt=\"\" src=\"content/\" /><img alt=\"\" src=\"/assets/uploads/content/post_1764301801_270.png\" style=\"float:left; height:500px; width:500px\" /></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>BẢO QUẢN:</strong></p>\r\n\r\n<ul>\r\n	<li>Nơi kh&ocirc; r&aacute;o, tho&aacute;ng m&aacute;t, tr&aacute;nh &aacute;nh nắng trực tiếp.</li>\r\n</ul>\r\n',1,0,'2025-11-28 03:15:22'),(6,5,'3S - NO2 Cấp Cứu NO2, Rớt Cục Thịt','TS284575',199000.00,0.00,'sp_1764312482_104.png',100,'<p>Bacillus spp (min).........................................1x10^9&nbsp;cfu/kg</p>\r\n\r\n<p>Chất mang vừa đủ .............................................. 1 kg&nbsp;</p>\r\n','<p>Xử l&yacute; NO, cấp t&oacute;c, sạch nước, ổn định m&ocirc;i trường, xử l&yacute; t&ocirc;m rớt cục thịt do NO</p>\r\n','<p>&nbsp;- Trước khi thả t&ocirc;m 3-4 ng&agrave;y: 454g/6.000m3nước</p>\r\n\r\n<p>&nbsp;- Xử l&yacute; định kỳ: 454g/5.000m3&nbsp;nước.&nbsp;</p>\r\n\r\n<p>&nbsp;- Giải ph&oacute;ng kh&iacute; độc NO,: 454g cho 3,000m3&nbsp;nước&nbsp;</p>\r\n','<p><strong>C&Aacute;CH SỬ DỤNG:</strong>&nbsp;</p>\r\n\r\n<p>- Pha sản phẩm với 30 l&iacute;t nước tạt đều khắp ao&nbsp;</p>\r\n\r\n<p>- D&ugrave;ng l&uacute;c 6-8h s&aacute;ng: sạch v&agrave; ổn định m&agrave;u nước.</p>\r\n\r\n<p>- D&ugrave;ng l&uacute;c 19-20h: sạch đ&aacute;y, giảm tảo, nước chuyển sang m&agrave;u bạc.</p>\r\n\r\n<p>- Hiệu quả sau 12 giờ sử dụng&nbsp;</p>\r\n\r\n<p><strong>BẢO QUẢN:&nbsp;</strong></p>\r\n\r\n<p>Nơi kh&ocirc; r&aacute;o, tho&aacute;ng m&aacute;t, tr&aacute;nh &aacute;nh nắng trực tiếp</p>\r\n',2,0,'2025-11-28 06:48:02');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `config_key` varchar(50) NOT NULL,
  `config_value` text,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_key` (`config_key`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_title','Thuốc Thuỷ Sản Giá Tốt','Tiêu đề Website'),(2,'site_logo','assets/img/logo.png','Logo'),(3,'site_hotline','0798088588','Số Hotline & Zalo'),(4,'site_email','thuysangiatotvn@gmail.com','Email liên hệ'),(5,'site_address','Số 375 Lê Hồng Phong, Khóm 5, Phường 3, Sóc Trăng','Địa chỉ công ty'),(6,'social_facebook','#','Link Fanpage'),(7,'social_zalo','0798088588','Link Zalo'),(8,'invoice_template','','Mẫu in hóa đơn tùy chỉnh');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','sale','warehouse','editor') DEFAULT 'sale',
  `avatar` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@gmail.com','123456','admin',NULL,1,'2025-11-24 01:38:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-28 16:56:36
