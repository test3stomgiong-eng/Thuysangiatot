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
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '0' COMMENT '0: Chưa xem, 1: Đã xem',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'Nguyễn Văn Duy','st.tomgiongsinhhoc3s@gmail.com','125436987','Tôm bị phân trắng trị như thế nào','2025-12-06 02:34:45',1);
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

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
  `role` enum('customer','admin','sale','warehouse','editor') DEFAULT 'customer',
  `address` text,
  `avatar` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (11,'Nguyễn Văn Duy','0125436987','st.tomgiongsinhhoc3s@gmail.com','$2y$10$ceS7b9LV0mBwt7c4fAnveeZajEuu.UwfLj67/FcKPmSNcLL8x7Fp6','admin',NULL,NULL,1,NULL,NULL,'2025-12-05 09:04:32');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_details`
--

DROP TABLE IF EXISTS `inventory_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `receipt_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int NOT NULL,
  `import_price` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_details`
--

LOCK TABLES `inventory_details` WRITE;
/*!40000 ALTER TABLE `inventory_details` DISABLE KEYS */;
INSERT INTO `inventory_details` VALUES (1,1,6,'3S - NO2 Cấp Cứu NO2, Rớt Cục Thịt',1,199000.00),(2,2,6,'3S - NO2 Cấp Cứu NO2, Rớt Cục Thịt',10,199000.00),(3,3,6,'3S - NO2 Cấp Cứu NO2, Rớt Cục Thịt',1,100000.00);
/*!40000 ALTER TABLE `inventory_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_receipts`
--

DROP TABLE IF EXISTS `inventory_receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_receipts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `total_money` decimal(15,2) DEFAULT '0.00',
  `note` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_receipts`
--

LOCK TABLES `inventory_receipts` WRITE;
/*!40000 ALTER TABLE `inventory_receipts` DISABLE KEYS */;
INSERT INTO `inventory_receipts` VALUES (1,'PN1764928427',11,199000.00,'','2025-12-05 09:53:47'),(2,'PN1764987514',11,1990000.00,'Nhap hàng','2025-12-06 02:18:34'),(3,'PN1764987596',11,100000.00,'','2025-12-06 02:19:56');
/*!40000 ALTER TABLE `inventory_receipts` ENABLE KEYS */;
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
  `author_id` int DEFAULT NULL,
  `views` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `fk_news_customers` (`author_id`),
  CONSTRAINT `fk_news_customers` FOREIGN KEY (`author_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (2,6,'Xuất khẩu thủy sản năm 2025 có thể cán mốc 11 tỷ USD','xuat-khau-thuy-san-nam-2025-co-the-can-moc-11-ty-usd','news_1764561459_599.png','Theo Hiệp hội Chế biến và Xuất khẩu Thủy sản Việt Nam (VASEP), xuất khẩu thủy sản Việt Nam 10 tháng đầu năm 2025 ghi nhận bước tiến quan trọng, đạt hơn 9,5 tỷ USD, tăng 15% so với cùng kỳ.','<h2>D&ugrave; đối mặt nhiều kh&oacute; khăn, c&aacute;c sản phẩm thủy sản chủ lực nước ta vẫn duy tr&igrave; sức bật tốt, tạo nền tảng để xuất khẩu cả năm c&oacute; thể đạt 11 tỷ USD.</h2>\r\n\r\n<h2>Nỗ lực bền bỉ trong bối cảnh thị trường nhiều biến động</h2>\r\n\r\n<p>Theo Hiệp hội Chế biến v&agrave; Xuất khẩu Thủy sản Việt Nam (VASEP), xuất khẩu&nbsp;<a href=\"https://nongnghiepmoitruong.vn/thuy-san-tag82120/\" rel=\"follow noopener\" target=\"_blank\">thủy sản</a>&nbsp;Việt Nam 10 th&aacute;ng đầu năm 2025 ghi nhận bước tiến quan trọng, đạt hơn 9,5 tỷ USD, tăng 15% so với c&ugrave;ng kỳ.</p>\r\n\r\n<p>Kết quả n&agrave;y cho thấy sự nỗ lực bền bỉ của ng&agrave;nh trong bối cảnh thị trường nhiều biến động, đặc biệt l&agrave; c&aacute;c c&uacute; sốc ch&iacute;nh s&aacute;ch từ thị trường Hoa Kỳ. D&ugrave; qu&yacute; III bắt đầu xuất hiện dấu hiệu chững lại do ảnh hưởng của thuế đối ứng, c&aacute;c nh&oacute;m sản phẩm chủ lực vẫn duy tr&igrave; được sức bật tốt v&agrave; tạo nền tảng để xuất khẩu cả năm c&aacute;n mốc 11 tỷ USD.</p>\r\n\r\n<p><img alt=\"Xuất khẩu thủy sản Việt Nam 10 tháng đầu năm 2025 đạt hơn 9,5 tỷ USD, tăng 15% so với cùng kỳ. Ảnh: Hồng Thắm.\" src=\"https://i.ex-cdn.com/nongnghiepmoitruong.vn/files/content/2025/11/30/img_0019-a2-135128_216.jpg\" style=\"width:1920px\" title=\"Xuất khẩu thủy sản Việt Nam 10 tháng đầu năm 2025 đạt hơn 9,5 tỷ USD, tăng 15% so với cùng kỳ. Ảnh: Hồng Thắm.\" /></p>\r\n\r\n<p>Xuất khẩu thủy sản Việt Nam 10 th&aacute;ng đầu năm 2025 đạt hơn 9,5 tỷ USD, tăng 15% so với c&ugrave;ng kỳ. Ảnh:&nbsp;<em>Hồng Thắm</em>.</p>\r\n\r\n<p>Trong nh&oacute;m sản phẩm xuất khẩu,&nbsp;<a href=\"https://nongnghiepmoitruong.vn/tom-tag74824/\" rel=\"follow noopener\" target=\"_blank\">t&ocirc;m</a>&nbsp;tiếp tục giữ vai tr&ograve; &quot;đầu t&agrave;u&quot; khi 10 th&aacute;ng đạt tr&ecirc;n 3,9 tỷ USD. Ngo&agrave;i t&ocirc;m thẻ v&agrave; t&ocirc;m s&uacute; duy tr&igrave; tăng ổn định, t&acirc;m điểm của năm nay l&agrave; t&ocirc;m h&ugrave;m - mặt h&agrave;ng c&oacute; mức tăng trưởng bứt ph&aacute; hiếm thấy đạt tr&ecirc;n 712 triệu USD, tăng tới 134%. Đ&acirc;y l&agrave; sự b&ugrave;ng nổ xuất ph&aacute;t từ nhu cầu cực lớn của Trung Quốc v&agrave; Hồng K&ocirc;ng cho ph&acirc;n kh&uacute;c t&ocirc;m sống, t&ocirc;m cao cấp, nhất l&agrave; nh&oacute;m ẩm thực HORECA.</p>\r\n\r\n<p>C&aacute; tra l&agrave; mặt h&agrave;ng chủ lực thứ hai, đạt kim ngạch xuất khẩu khoảng 1,8 tỷ USD sau 10 th&aacute;ng. Đ&aacute;ng ch&uacute; &yacute;, c&aacute; r&ocirc; phi trở th&agrave;nh điểm s&aacute;ng mới, tăng trưởng ấn tượng 220% đạt 62 triệu USD v&agrave; đang được định h&igrave;nh l&agrave; ng&agrave;nh h&agrave;ng chiến lược tiềm năng của Việt Nam, với nhu cầu tăng tại Hoa Kỳ v&agrave; nhiều nước ch&acirc;u &Acirc;u.</p>\r\n\r\n<p>Trong khi đ&oacute;,&nbsp;<a href=\"https://nongnghiepmoitruong.vn/ca-ngu-tag31694/\" rel=\"follow noopener\" target=\"_blank\">c&aacute; ngừ</a>&nbsp;vẫn chịu nhiều sức &eacute;p. 10 th&aacute;ng xuất khẩu duy tr&igrave; ở mức khoảng 791 triệu USD, thấp hơn gần 4% so với c&ugrave;ng kỳ do thiếu nguy&ecirc;n liệu c&aacute; ngừ vằn chế biến c&aacute; hộp v&agrave; xung đột tại Trung Đ&ocirc;ng tiếp tục l&agrave;m gi&aacute;n đoạn chuỗi cung ứng. Một số doanh nghiệp phải thu hẹp sản xuất hoặc chuyển hướng sang sản phẩm loin (thăn) để tiết giảm chi ph&iacute;.</p>\r\n\r\n<p>Tr&aacute;i lại, nh&oacute;m mực v&agrave; bạch tuộc đ&atilde; c&oacute; sự phục hồi r&otilde; rệt, đưa tổng kim ngạch 10 th&aacute;ng vượt 627 triệu USD; nhu cầu ở Nhật Bản, H&agrave;n Quốc v&agrave; Hoa Kỳ cải thiện mạnh, đặc biệt với sản phẩm đ&ocirc;ng lạnh phục vụ chế biến. Chả c&aacute; v&agrave; surimi cũng g&acirc;y ch&uacute; &yacute; khi 10 th&aacute;ng đạt tới 291 triệu USD - mức tăng 24% so với c&ugrave;ng kỳ, trở th&agrave;nh một trong những ng&agrave;nh h&agrave;ng tăng trưởng mạnh trong to&agrave;n ng&agrave;nh.</p>\r\n\r\n<h2>Trung Quốc l&agrave; &ldquo;điểm tựa quan trọng&rdquo;</h2>\r\n\r\n<p>Cũng theo VASEP, 10 th&aacute;ng đầu năm 2025, Trung Quốc v&agrave; l&atilde;nh thổ Hồng K&ocirc;ng tiếp tục l&agrave; &ldquo;điểm tựa quan trọng&rdquo; của ng&agrave;nh thủy sản Việt Nam. Kim ngạch xuất khẩu sang thị trường n&agrave;y đ&atilde; vượt 2 tỷ USD, tăng hơn 32%, đặc biệt mạnh ở c&aacute;c mặt h&agrave;ng t&ocirc;m h&ugrave;m, c&aacute; biển v&agrave; cua sống. Nhu cầu ti&ecirc;u thụ thủy sản tươi sống trong dịp cuối năm đang mở ra dư địa tăng trưởng đ&aacute;ng kể cho xuất khẩu thủy sản Việt Nam.</p>\r\n\r\n<p>Tr&aacute;i lại, thị trường Hoa Kỳ bước v&agrave;o giai đoạn nhiều biến động. D&ugrave; t&iacute;nh chung 10 th&aacute;ng xuất khẩu sang Mỹ vẫn tăng so với c&ugrave;ng kỳ, đạt khoảng 1,66 tỷ USD, xu hướng giảm đ&atilde; bắt đầu r&otilde; rệt từ qu&yacute; III do thuế đối ứng 20% &aacute;p dụng từ th&aacute;ng 8. C&aacute;c nh&oacute;m h&agrave;ng t&ocirc;m v&agrave; c&aacute; tra, vốn chiếm tỷ trọng lớn đều giảm trong th&aacute;ng 9 v&agrave; th&aacute;ng 10 v&igrave; nhiều doanh nghiệp chủ động điều chỉnh lượng h&agrave;ng để tr&aacute;nh rủi ro thua lỗ.</p>\r\n\r\n<p><img alt=\"Nhu cầu tiêu thụ thủy sản tươi sống trong dịp cuối năm đang mở ra dư địa tăng trưởng đáng kể cho xuất khẩu thủy sản Việt Nam. Ảnh: Kim Sơ.\" src=\"https://i.ex-cdn.com/nongnghiepmoitruong.vn/files/content/2025/11/30/3962ce6c8bba59e400ab-135245_608.jpg\" style=\"width:1920px\" title=\"Nhu cầu tiêu thụ thủy sản tươi sống trong dịp cuối năm đang mở ra dư địa tăng trưởng đáng kể cho xuất khẩu thủy sản Việt Nam. Ảnh: Kim Sơ.\" /></p>\r\n\r\n<p>Nhu cầu ti&ecirc;u thụ thủy sản tươi sống trong dịp cuối năm đang mở ra dư địa tăng trưởng đ&aacute;ng kể cho xuất khẩu thủy sản Việt Nam. Ảnh:&nbsp;<em>Kim Sơ</em>.</p>\r\n\r\n<p>B&ecirc;n cạnh đ&oacute;, c&aacute;c th&aacute;ch thức kh&aacute;c như thuế chống b&aacute;n ph&aacute; gi&aacute; t&ocirc;m v&agrave; quy định Đạo luật Bảo vệ Động vật biển c&oacute; v&uacute; (<a href=\"https://nongnghiepmoitruong.vn/mmpa-tag213767/\" rel=\"follow noopener\" target=\"_blank\">MMPA</a>) dự kiến ảnh hưởng trực tiếp đến hải sản khai th&aacute;c từ đầu năm 2026 - khiến thị trường Hoa Kỳ trở th&agrave;nh &ldquo;điểm n&oacute;ng rủi ro&rdquo; của ng&agrave;nh.</p>\r\n\r\n<p>Xuất khẩu sang Nhật Bản tiếp tục phục hồi ổn định, với kim ngạch 10 th&aacute;ng đạt gần 1,45 tỷ USD, tăng nhờ ti&ecirc;u thụ mạnh c&aacute;c mặt h&agrave;ng t&ocirc;m, mực, c&aacute; biển v&agrave; cua thanh tr&ugrave;ng. EU cũng l&agrave; thị trường tăng trưởng tốt, đạt 985 triệu USD trong 10 th&aacute;ng, hưởng lợi từ việc khối n&agrave;y nới lỏng một số r&agrave;o cản kỹ thuật đối với thủy sản nu&ocirc;i trồng của Việt Nam...</p>\r\n\r\n<h2>Nhận diện cơ hội v&agrave; th&aacute;ch thức trong năm 2026</h2>\r\n\r\n<p>Đối với ng&agrave;nh t&ocirc;m, thị trường t&ocirc;m đ&ocirc;ng lạnh (frozen shrimp) to&agrave;n cầu được dự b&aacute;o sẽ tăng từ khoảng 18.742,6 triệu USD trong năm 2025 l&ecirc;n đến 32.847,3 triệu USD v&agrave;o năm 2035 - tương đương mức tăng khoảng 75,3% trong 10 năm tới. Tốc độ tăng trưởng trung b&igrave;nh h&agrave;ng năm (CAGR) được ước t&iacute;nh đạt 5,8% từ năm 2025 - 2035.</p>\r\n\r\n<p>Thị trường t&ocirc;m đ&ocirc;ng lạnh to&agrave;n cầu đang bước v&agrave;o giai đoạn tăng trưởng mạnh mẽ, được th&uacute;c đẩy bởi xu hướng ti&ecirc;u d&ugrave;ng y&ecirc;u th&iacute;ch thực phẩm tiện lợi, nhu cầu protein cao, mở rộng nu&ocirc;i trồng v&agrave; c&ocirc;ng nghệ chế biến hiện đại. Với dự b&aacute;o tăng trưởng l&ecirc;n hơn một lần rưỡi trong thập kỷ tới, ng&agrave;nh t&ocirc;m đ&ocirc;ng lạnh l&agrave; một mảng hứa hẹn đối với c&aacute;c nh&agrave; sản xuất, chế biến v&agrave; nh&agrave; ph&acirc;n phối.&nbsp;</p>\r\n\r\n<p>Với đ&agrave; tăng trưởng mạnh của thị trường t&ocirc;m đ&ocirc;ng lạnh to&agrave;n cầu, ng&agrave;nh t&ocirc;m Việt Nam đứng trước cơ hội lớn để mở rộng quy m&ocirc; v&agrave; n&acirc;ng cao gi&aacute; trị xuất khẩu. Nhu cầu ti&ecirc;u d&ugrave;ng thực phẩm tiện lợi, gi&agrave;u protein c&ugrave;ng xu hướng chế biến s&acirc;u, truy xuất nguồn gốc r&otilde; r&agrave;ng tạo &ldquo;dư địa&rdquo; cho t&ocirc;m Việt Nam tham gia s&acirc;u hơn v&agrave;o ph&acirc;n kh&uacute;c gi&aacute; trị cao. Nếu tận dụng tốt lợi thế nu&ocirc;i trồng, chế biến, c&ocirc;ng nghệ v&agrave; ti&ecirc;u chuẩn xanh, ng&agrave;nh t&ocirc;m kh&ocirc;ng chỉ tăng sản lượng m&agrave; c&ograve;n bứt ph&aacute; về gi&aacute; trị trong thập kỷ tới.</p>\r\n\r\n<p>&Ocirc;ng L&ecirc; Văn Quang, Chủ tịch HĐQT ki&ecirc;m Tổng Gi&aacute;m đốc C&ocirc;ng ty Cổ phần Tập đo&agrave;n Thủy sản Minh Ph&uacute; nhận định, kh&oacute; khăn lớn nhất hiện nay của ng&agrave;nh t&ocirc;m Việt Nam l&agrave; quy hoạch v&ugrave;ng nu&ocirc;i chưa hợp l&yacute;, dẫn đến dịch bệnh diễn biến phức tạp, k&eacute;o theo gi&aacute; th&agrave;nh sản xuất cao - thậm ch&iacute; cao hơn 30% so với Ấn Độ v&agrave; gấp đ&ocirc;i Ecuador, l&agrave;m giảm năng lực cạnh tranh.</p>\r\n\r\n<p>&lsquo;Nếu giải quyết được vấn đề dịch bệnh v&agrave; quy hoạch v&ugrave;ng nu&ocirc;i hợp l&yacute;, con t&ocirc;m Việt Nam ho&agrave;n to&agrave;n c&oacute; thể vươn l&ecirc;n dẫn đầu thế giới&rsquo;, &ocirc;ng Quang nhấn mạnh.&nbsp;</p>\r\n\r\n<p>Đối với&nbsp;<a href=\"https://nongnghiepmoitruong.vn/ca-tra-tag81743/\" rel=\"follow noopener\" target=\"_blank\">c&aacute; tra</a>, Trung Quốc vẫn l&agrave; thị trường tiềm năng của Việt Nam, d&ugrave; c&ograve;n kh&ocirc;ng &iacute;t th&aacute;ch thức. Nhu cầu đối với sản phẩm fillet c&aacute; tra đ&ocirc;ng lạnh tại thị trường n&agrave;y vẫn duy tr&igrave; ổn định nhờ gi&aacute; cả phải chăng, ph&ugrave; hợp ph&acirc;n kh&uacute;c ti&ecirc;u d&ugrave;ng phổ th&ocirc;ng. B&ecirc;n cạnh đ&oacute;, lợi thế địa l&yacute; gần gi&uacute;p chi ph&iacute; logistics thấp hơn đ&aacute;ng kể so với c&aacute;c thị trường xa, qua đ&oacute; tạo ưu thế cạnh tranh r&otilde; rệt cho doanh nghiệp c&aacute; tra Việt Nam.</p>\r\n\r\n<p>Tại thị trường EU, c&aacute; tra vẫn c&ograve;n nhiều dư địa. Thiếu hụt nguồn cung c&aacute; thịt trắng đang t&aacute;c động trực tiếp đến gi&aacute; cả v&agrave; ph&acirc;n bổ thị phần c&aacute;c sản phẩm tại khu vực n&agrave;y. Trong bối cảnh đ&oacute;, nhu cầu t&igrave;m kiếm c&aacute;c lựa chọn c&oacute; gi&aacute; hợp l&yacute;, nguồn cung ổn định được dự b&aacute;o gia tăng, mở ra khoảng trống cho c&aacute;c sản phẩm thay thế. Nhờ lợi thế về chi ph&iacute;, sự ổn định nguy&ecirc;n liệu v&agrave; khả năng đ&aacute;p ứng đa dạng y&ecirc;u cầu chế biến, c&aacute; tra Việt Nam được xem l&agrave; ứng vi&ecirc;n ph&ugrave; hợp để gia tăng thị phần tại EU.</p>\r\n\r\n<p>Trong khi đ&oacute;,<a href=\"https://nongnghiepmoitruong.vn/ca-ro-phi-tag7771/\" rel=\"follow noopener\" target=\"_blank\">c&aacute; r&ocirc; phi</a>&nbsp;Việt Nam đang nổi l&ecirc;n như một ng&agrave;nh h&agrave;ng chiến lược, c&oacute; khả năng &ldquo;bứt ph&aacute;&rdquo; mạnh mẽ nếu được đầu tư b&agrave;i bản, từ kh&acirc;u nu&ocirc;i, chế biến đến x&acirc;y dựng thương hiệu quốc tế. Với lợi thế tự nhi&ecirc;n, nhu cầu to&agrave;n cầu v&agrave; ch&iacute;nh s&aacute;ch ph&aacute;t triển thủy sản, c&aacute; r&ocirc; phi Việt Nam c&oacute; tiềm năng lớn để trở th&agrave;nh mặt h&agrave;ng xuất khẩu chủ lực, bổ sung cho t&ocirc;m v&agrave; c&aacute; tra.</p>\r\n\r\n<p><img alt=\"Cá rô phi Việt Nam đang nổi lên như một ngành hàng chiến lược, có khả năng \'bứt phá\' mạnh mẽ nếu được đầu tư bài bản. Ảnh: Hồng Thắm.\" src=\"https://i.ex-cdn.com/nongnghiepmoitruong.vn/files/content/2025/11/11/2-112743_383.jpg\" style=\"width:1920px\" title=\"Cá rô phi Việt Nam đang nổi lên như một ngành hàng chiến lược, có khả năng \'bứt phá\' mạnh mẽ nếu được đầu tư bài bản. Ảnh: Hồng Thắm.\" /></p>\r\n\r\n<p>C&aacute; r&ocirc; phi Việt Nam đang nổi l&ecirc;n như một ng&agrave;nh h&agrave;ng chiến lược, c&oacute; khả năng &ldquo;bứt ph&aacute;&rdquo; mạnh mẽ nếu được đầu tư b&agrave;i bản. Ảnh:&nbsp;<em>Hồng Thắm</em>.</p>\r\n\r\n<p>Tương tự với ng&agrave;nh c&aacute; ngừ, mới đ&acirc;y Ch&iacute;nh phủ đ&atilde; ban h&agrave;nh Nghị định số 309/2025/NĐ-CP về việc sửa đổi, bổ sung một số điều của Nghị định 26/2019/NĐ-CP v&agrave; Nghị định 37/2024/NĐ-CP li&ecirc;n quan đến quy định chi tiết biện ph&aacute;p thi h&agrave;nh Luật Thủy sản.</p>\r\n\r\n<p>Điểm đ&aacute;ng ch&uacute; &yacute;, Nghị định điều chỉnh một số nội dung về quản l&yacute; khai th&aacute;c, bao gồm việc ngưng hiệu lực thi h&agrave;nh quy định về k&iacute;ch thước tối thiểu được ph&eacute;p khai th&aacute;c của một số lo&agrave;i thủy sản sống trong v&ugrave;ng nước tự nhi&ecirc;n tại Phụ lục V ban h&agrave;nh k&egrave;m theo Nghị định số 37/2024/NĐ-CP cho đến khi sửa đổi c&aacute;c quy định đảm bảo ph&ugrave; hợp với quy định của ph&aacute;p luật, chủ trương của Đảng, chỉ đạo của Ch&iacute;nh phủ, Thủ tướng Ch&iacute;nh phủ.</p>\r\n\r\n<p>Đ&acirc;y được xem l&agrave; bước gỡ vướng quan trọng cho doanh nghiệp, đặc biệt với mặt h&agrave;ng c&aacute; ngừ, qua đ&oacute; mở ra kỳ vọng mới cho sự phục hồi v&agrave; tăng trưởng của ng&agrave;nh thủy sản trong thời gian tới.</p>\r\n\r\n<p>B&ecirc;n cạnh những cơ hội mở ra, năm 2026 ng&agrave;nh thủy sản Việt Nam vẫn phải đối mặt với nhiều th&aacute;ch thức lớn, từ sự k&eacute;o d&agrave;i của thuế đối ứng tại thị trường Hoa Kỳ, nguy cơ chịu t&aacute;c động từ MMPA, khả năng EU tiếp tục duy tr&igrave;&nbsp;<a href=\"https://nongnghiepmoitruong.vn/the-vang-iuu-tag33672/\" rel=\"follow noopener\" target=\"_blank\">thẻ v&agrave;ng IUU</a>, đến &aacute;p lực cạnh tranh ng&agrave;y c&agrave;ng gay gắt từ Ấn Độ, Ecuador, Indonesia...</p>\r\n\r\n<p>Bối cảnh đ&oacute; đ&ograve;i hỏi doanh nghiệp Việt Nam phải chủ động t&aacute;i cơ cấu thị trường, đẩy mạnh sản phẩm gi&aacute; trị gia tăng, đầu tư c&ocirc;ng nghệ chế biến v&agrave; n&acirc;ng cao ti&ecirc;u chuẩn bền vững, qua đ&oacute; tạo nền tảng cho tăng trưởng d&agrave;i hạn.</p>\r\n',NULL,2,1,'2025-12-01 03:57:39');
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
  `status` tinyint(1) DEFAULT '1' COMMENT '1: Hiện, 0: Ẩn',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_categories`
--

LOCK TABLES `news_categories` WRITE;
/*!40000 ALTER TABLE `news_categories` DISABLE KEYS */;
INSERT INTO `news_categories` VALUES (4,0,'Giấy kiểm dịch TPD','giay-kiem-dich-tpd',1,'2025-12-01 03:44:52'),(5,4,'Giấy kiểm dịch TPD Thai Lan C.P','giay-kiem-dich-tpd-thai-lan-cp',1,'2025-12-01 03:45:20'),(6,7,'Tin tức thuỷ sản','tin-tuc-thuy-san',1,'2025-12-01 03:52:27'),(7,0,'Tin tức','tin-tuc',1,'2025-12-01 03:52:54');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,1,1,'3S - SIÊU KHOÁNG: Mau Lột Xác Cứng Vỏ',469000.00,2,938000.00),(2,2,5,'3S - BOTTOM USA: Sạch Đáy, Sạch Nước',199000.00,1,199000.00),(3,2,1,'3S - SIÊU KHOÁNG: Mau Lột Xác Cứng Vỏ',469000.00,1,469000.00),(4,3,5,'3S - BOTTOM USA: Sạch Đáy, Sạch Nước',199000.00,1,199000.00);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'DH1764056433',NULL,NULL,'Lê Khánh Duy','0873942510','Sóc trăng',938000.00,'COD','completed','Giao buổi chiều','2025-11-25 07:40:33'),(2,'DH1764316234',NULL,NULL,'Oanh','0547865222','Duyen hai tra vinh',668000.00,'COD','pending','D9iện thoai','2025-11-28 07:50:34'),(3,'DH1764555806',NULL,NULL,'Nguyen Van A','0123456789','soc trăng',199000.00,'COD','pending','','2025-12-01 02:23:26');
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
  `paper_size` varchar(10) DEFAULT 'A4' COMMENT 'A4, A5, K80',
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
INSERT INTO `print_templates` VALUES (4,'HD CTY 3S','A4','<table style=\"border-collapse: collapse; width: 103.002%; margin-left: auto; margin-right: auto; height: 630px;\" border=\"1\"><colgroup><col style=\"width: 100%;\"></colgroup>\r\n<tbody>\r\n<tr>\r\n<td style=\"text-align: center;\">\r\n<p><span style=\"font-size: 18pt;\"><strong>C&Ocirc;NG TY TNHH KHOA HỌC C&Ocirc;NG NGHỆ 3S</strong></span></p>\r\n<p style=\"text-align: center;\">Địa chỉ: Đường Số 6 - KĐT 5A - Kh&oacute;m 4 - P.04 - TP.S&oacute;c Trăng</p>\r\n<p style=\"text-align: center;\">Website: Thuysangiatot.vn</p>\r\n<p style=\"text-align: center;\">--------------</p>\r\n<p style=\"text-align: center;\"><strong>HO&Aacute; ĐƠN B&Aacute;N H&Agrave;NG</strong></p>\r\n<p style=\"text-align: center;\">Ng&agrave;y: {NGAY_TAO}&nbsp; &nbsp; Số HĐ: {MA_DON}</p>\r\n<p style=\"text-align: left;\">T&ecirc;n kh&aacute;ch h&agrave;ng: {TEN_KHACH}</p>\r\n<p style=\"text-align: left;\">Địa chỉ: {DIA_CHI}</p>\r\n<p style=\"text-align: left;\">Nh&acirc;n vi&ecirc;n b&aacute;n h&agrave;ng:</p>\r\n<p style=\"text-align: left;\">{BANG_HANG_CHI_TIET}</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>',1,'2025-11-28 09:33:59');
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
  `image` varchar(255) DEFAULT NULL COMMENT 'Icon đại diện',
  `icon_class` varchar(100) DEFAULT 'fa-solid fa-folder-open' COMMENT 'Class FontAwesome',
  `description` text,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (1,0,'Thuốc Thuỷ Sản','thuoc-thuy-san',NULL,'fa-solid fa-folder-open','Danh mục thuốc',1,'2025-11-28 02:47:30'),(2,1,'Khoáng chất','khoang-chat','','fa-solid fa-vial','',1,'2025-11-24 01:38:15'),(4,1,'Hỗ Trợ Gan - Ruột','ho-tro-gan-ruot',NULL,'fa-solid fa-folder-open','Nhóm sản phẩm hỗ trợ Gan và Ruột tôm thẻ chân trắng là các chế phẩm đặc biệt được phát triển nhằm tối ưu hóa sức khỏe và năng suất nuôi tôm. Các sản phẩm này bao gồm hỗn hợp các vi sinh vật có lợi, Enzyme và các chất dinh dưỡng thiết yếu, giúp cải thiện chức năng gan và hệ tiêu hóa của tôm. Vi sinh vật có lợi cạnh tranh với mầm bệnh, phân hủy chất hữu cơ và tăng cường hệ miễn dịch, trong khi Enzyme hỗ trợ tiêu hóa, phân giải Protein, Lipit và Carbohydrate, giúp tôm hấp thu dưỡng chất một cách hiệu quả. Nhờ vào sự kết hợp độc đáo và khoa học, nhóm sản phẩm này không chỉ cải thiện môi trường ao nuôi mà còn thúc đẩy sự tăng trưởng và phát triển toàn diện của tôm thẻ chân trắng, mang lại năng suất cao và hiệu quả kinh tế bền vững cho người nuôi.',1,'2025-11-24 01:38:15'),(5,1,'Enzyme & Vi Sinh Xử Lý Môi Trường','enzyme-vi-sinh-xu-ly-moi-truong','','fa-solid fa-droplet','Nhóm sản phẩm Vi Sinh Và Enzyme cho tôm thẻ chân trắng là giải pháp tiên tiến và hiệu quả trong việc quản lý và tối ưu hóa môi trường nuôi trồng. Các chế phẩm vi sinh bao gồm các chủng vi khuẩn có lợi như Bacillus, Lactobacillus, và Nitrobacter, giúp phân hủy chất hữu cơ, giảm thiểu amoniac và nitrit, và cạnh tranh với mầm bệnh, từ đó cải thiện chất lượng nước và duy trì môi trường sống an toàn cho tôm. Bên cạnh đó, các Enzyme như Protease, Lipase, và Amylase hỗ trợ quá trình tiêu hóa, giúp tôm hấp thụ dưỡng chất tốt hơn và tăng trưởng nhanh hơn. Sự kết hợp giữa vi sinh và enzyme không chỉ giúp kiểm soát các yếu tố gây ô nhiễm trong ao nuôi mà còn tăng cường sức khỏe và khả năng đề kháng của tôm. Với công nghệ sản xuất hiện đại và thành phần được chọn lọc kỹ lưỡng, nhóm sản phẩm Vi Sinh và Enzyme cho tôm thẻ chân trắng là lựa chọn lý tưởng cho người nuôi, mang lại hiệu quả vượt trội và bền vững trong hoạt động nuôi trồng thủy sản. Giới thiệu về nhóm sản phẩm hỗ trợ Gan và Ruột tôm thẻ chân trắng',1,'2025-11-27 08:38:00'),(7,6,'Tin tức thuỷ sản','tin-tuc-thuy-san',NULL,'fa-solid fa-folder-open','',1,'2025-11-27 08:51:18'),(8,1,'Dinh Dưỡng Cho Tôm','dinh-duong-cho-tom','','fa-solid fa-fish-fins','Nhóm sản phẩm dinh dưỡng cho tôm thẻ chân trắng được phát triển với công thức đặc biệt nhằm cung cấp đầy đủ các dưỡng chất thiết yếu, giúp tôm phát triển nhanh chóng và khỏe mạnh. Các sản phẩm này bao gồm các loại thức ăn bổ sung chứa protein chất lượng cao, axit béo không bão hòa, vitamin, khoáng chất, và các axit amin thiết yếu. Chúng được chế biến theo công nghệ tiên tiến để đảm bảo khả năng tiêu hóa và hấp thu tối ưu, giúp tôm tăng trưởng mạnh mẽ, cải thiện sức đề kháng và tăng cường khả năng chống chịu bệnh tật. Bên cạnh đó, nhóm sản phẩm còn bao gồm các chất kích thích tăng trưởng tự nhiên và enzyme hỗ trợ tiêu hóa, giúp tôm tận dụng tối đa nguồn dinh dưỡng từ thức ăn. Với sự kết hợp hài hòa giữa các thành phần dinh dưỡng và phụ gia chất lượng cao, nhóm sản phẩm dinh dưỡng cho tôm thẻ chân trắng là giải pháp toàn diện, giúp người nuôi tối ưu hóa hiệu suất nuôi trồng và đảm bảo chất lượng tôm thương phẩm vượt trội.\r\n\r\nSẢN PHẨM ĐÃ XEM',1,'2025-11-27 09:02:48'),(10,1,'Men vi sinh','men-vi-sinh','','fa-solid fa-bacteria','',1,'2025-12-01 09:54:57'),(11,1,'Kháng sinh','khang-sinh','','fa-solid fa-pills','',1,'2025-12-01 09:55:33');
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
  `promo_type` enum('none','same','gift') DEFAULT 'none' COMMENT 'none: Không, same: Tặng chính nó, gift: Tặng sp khác',
  `promo_buy` int DEFAULT '0' COMMENT 'Số lượng cần mua (VD: Mua 2)',
  `promo_get` int DEFAULT '0' COMMENT 'Số lượng được tặng (VD: Tặng 1)',
  `promo_gift_id` int DEFAULT '0' COMMENT 'ID sản phẩm quà tặng (Nếu là tặng sp khác)',
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
INSERT INTO `products` VALUES (1,2,'3S - SIÊU KHOÁNG: Mau Lột Xác Cứng Vỏ','sp001',469000.00,0.00,'sieukhoang.png',2,'<ul><li>Canxi (min): 20%</li><li>Magie (min): 10%</li><li>Kali: 5%</li><li>Chất đệm vừa đủ: 1kg</li></ul>','<p>Giúp tôm lột xác đồng loạt, cứng vỏ nhanh sau 2 giờ.</p><p>Khắc phục hiện tượng <b>cong thân, đục cơ</b> do thiếu khoáng.</p><p>Ổn định màu nước, gây màu trà đẹp.</p>','<b>Định kỳ:</b> Dùng 1-2kg/1.000m3 nước (3 ngày/lần).<br><b>Khi tôm lột hoặc trời mưa:</b> Dùng 3kg/1.000m3 giúp cứng vỏ cấp tốc.','<span style=\"color:red; font-weight:bold;\">Lưu ý: Nên tạt vào ban đêm (20h-22h) để tôm hấp thu tốt nhất.</span>',1,0,'2025-11-24 08:52:44','none',0,0,0),(5,5,'3S - BOTTOM USA: Sạch Đáy, Sạch Nước','',199000.00,0.00,'sp_1764300996_630.png',99,'<p>Bacillus spp (min) ..................................... 1X109&nbsp;cfu/kg</p>\r\n\r\n<p>Chất mang vừa đủ .................................... 1Kg</p>\r\n','<p>Sạch đ&aacute;y, b&oacute;ng nước, c&acirc;n bằng hệ tảo v&agrave; vi sinh.</p>\r\n','<p>Trước khi thả t&ocirc;m 3 - 4 ng&agrave;y: 454g/6.000m3&nbsp;nước.</p>\r\n\r\n<p>Xử l&yacute; định kỳ: 454g/5.0003&nbsp;nước.</p>\r\n\r\n<p>Ph&acirc;n hủy lap-lap, tẩy trắng đ&aacute;y ao dơ: 454g/4.000m3&nbsp;nước.</p>\r\n\r\n<p>Giảm diệt tảo độc: 454g/3.000m3&nbsp;nước.</p>\r\n','<p><strong>C&Aacute;CH SỬ DỤNG:</strong></p>\r\n\r\n<ul>\r\n	<li>- Pha sản phẩm với 30 l&iacute;t nước tạt đều khắp ao.</li>\r\n	<li>- D&ugrave;ng l&uacute;c 6 - 8h s&aacute;ng: sạch v&agrave; ổn định m&agrave;u nước.</li>\r\n	<li>- D&ugrave;ng l&uacute;c 19 - 20h: sạch đ&aacute;y, giảm tảo, nước chuyển sang m&agrave;u bạc.</li>\r\n	<li>- Hiệu quả sau 12 giờ sử dụng.</li>\r\n</ul>\r\n\r\n<p><img alt=\"\" src=\"content/\" /><img alt=\"\" src=\"/assets/uploads/content/post_1764301801_270.png\" style=\"float:left; height:500px; width:500px\" /></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>BẢO QUẢN:</strong></p>\r\n\r\n<ul>\r\n	<li>Nơi kh&ocirc; r&aacute;o, tho&aacute;ng m&aacute;t, tr&aacute;nh &aacute;nh nắng trực tiếp.</li>\r\n</ul>\r\n',1,1,'2025-11-28 03:15:22','none',0,0,0),(6,5,'3S - NO2 Cấp Cứu NO2, Rớt Cục Thịt','TS284575',199000.00,0.00,'sp_1764312482_104.png',112,'<p>Bacillus spp (min).........................................1x10^9&nbsp;cfu/kg</p>\r\n\r\n<p>Chất mang vừa đủ .............................................. 1 kg&nbsp;</p>\r\n','<p>Xử l&yacute; NO, cấp t&oacute;c, sạch nước, ổn định m&ocirc;i trường, xử l&yacute; t&ocirc;m rớt cục thịt do NO</p>\r\n','<p>&nbsp;- Trước khi thả t&ocirc;m 3-4 ng&agrave;y: 454g/6.000m3nước</p>\r\n\r\n<p>&nbsp;- Xử l&yacute; định kỳ: 454g/5.000m3&nbsp;nước.&nbsp;</p>\r\n\r\n<p>&nbsp;- Giải ph&oacute;ng kh&iacute; độc NO,: 454g cho 3,000m3&nbsp;nước&nbsp;</p>\r\n','<p><strong>C&Aacute;CH SỬ DỤNG:</strong>&nbsp;</p>\r\n\r\n<p>- Pha sản phẩm với 30 l&iacute;t nước tạt đều khắp ao&nbsp;</p>\r\n\r\n<p>- D&ugrave;ng l&uacute;c 6-8h s&aacute;ng: sạch v&agrave; ổn định m&agrave;u nước.</p>\r\n\r\n<p>- D&ugrave;ng l&uacute;c 19-20h: sạch đ&aacute;y, giảm tảo, nước chuyển sang m&agrave;u bạc.</p>\r\n\r\n<p>- Hiệu quả sau 12 giờ sử dụng&nbsp;</p>\r\n\r\n<p><strong>BẢO QUẢN:&nbsp;</strong></p>\r\n\r\n<p>Nơi kh&ocirc; r&aacute;o, tho&aacute;ng m&aacute;t, tr&aacute;nh &aacute;nh nắng trực tiếp</p>\r\n',1,1,'2025-11-28 06:48:02','same',2,1,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_title','Thuốc Thuỷ Sản Giá Tốt','Tiêu đề Website'),(2,'site_logo','assets/img/logo.png','Logo'),(3,'site_hotline','0798088588','Số Hotline & Zalo'),(4,'site_email','thuysangiatotvn@gmail.com','Email liên hệ'),(5,'site_address','Số 375 Lê Hồng Phong, Khóm 5, Phường 3, Sóc Trăng','Địa chỉ công ty'),(6,'social_facebook','https://www.facebook.com/profile.php?id=61556963632002','Link Fanpage'),(7,'social_zalo','0798088588','Link Zalo'),(8,'invoice_template','','Mẫu in hóa đơn tùy chỉnh'),(9,'banner_main_img','','Ảnh Banner Chính (Slider)'),(10,'banner_main_link','#','Link Banner Chính'),(11,'banner_sub1_img','','Ảnh Banner Phụ 1 (Trên)'),(12,'banner_sub1_link','#','Link Banner Phụ 1'),(13,'banner_sub1_title','Deal Sốc Trong Ngày','Tiêu đề Banner Phụ 1'),(14,'banner_sub2_img','','Ảnh Banner Phụ 2 (Dưới)'),(15,'banner_sub2_link','#','Link Banner Phụ 2'),(16,'banner_sub2_title','Combo Tiết Kiệm','Tiêu đề Banner Phụ 2'),(17,'seo_description','Được thành lập với sứ mệnh mang lại những giải pháp nuôi trồng thủy sản hiệu quả và bền vững, Thuốc Thuỷ Sản Giá Tốt tự hào là đơn vị cung cấp thuốc, men vi sinh và chế phẩm sinh học hàng đầu tại Việt Nam.\r\n\r\nChúng tôi hiểu rằng, vụ mùa thắng lợi của bà con chính là niềm vui của chúng tôi. Tất cả sản phẩm đều được kiểm định nghiêm ngặt, đảm bảo chính hãng và giá thành cạnh tranh nhất thị trường.',NULL),(18,'social_youtube','https://www.youtube.com/','Link kênh Youtube'),(19,'social_tiktok','http://tiktok.com/',NULL),(20,'status_facebook','1','Trạng thái hiển thị Facebook'),(21,'status_zalo','1','Trạng thái hiển thị Zalo'),(22,'status_youtube','1','Trạng thái hiển thị Youtube'),(23,'status_tiktok','1','Trạng thái hiển thị Tiktok'),(24,'smtp_host','smtp.gmail.com','Host gửi mail'),(25,'smtp_username','test.3stomgiong@gmail.com','Email gửi'),(26,'smtp_password','wsag wsam mljy wvpc','Mật khẩu ứng dụng'),(27,'smtp_port','587','Cổng'),(28,'smtp_secure','tls','Bảo mật');
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

-- Dump completed on 2025-12-06 10:18:15
