-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.45 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.16.0.7229
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for powerrig
CREATE DATABASE IF NOT EXISTS `powerrig` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `powerrig`;

-- Dumping structure for table powerrig.address
CREATE TABLE IF NOT EXISTS `address` (
  `address_id` int NOT NULL AUTO_INCREMENT,
  `address_line` varchar(100) NOT NULL,
  `city_city_id` tinyint NOT NULL,
  PRIMARY KEY (`address_id`),
  KEY `fk_user_has_city_city1_idx` (`city_city_id`),
  CONSTRAINT `fk_user_has_city_city1` FOREIGN KEY (`city_city_id`) REFERENCES `city` (`city_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.address: ~0 rows (approximately)
INSERT INTO `address` (`address_id`, `address_line`, `city_city_id`) VALUES
	(5, '102/1,Ihala Bomiriya,Kaduwela.', 13);

-- Dumping structure for table powerrig.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `email` varchar(100) NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `vcode` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `joined_date` datetime NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.admin: ~0 rows (approximately)
INSERT INTO `admin` (`email`, `fname`, `lname`, `vcode`, `joined_date`) VALUES
	('isururathnayaka999@gmail.com', 'Isuru', 'Rathnayaka', '6a38730398525', '2024-01-05 11:08:05');

-- Dumping structure for table powerrig.brand
CREATE TABLE IF NOT EXISTS `brand` (
  `brand_id` int NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(25) NOT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.brand: ~11 rows (approximately)
INSERT INTO `brand` (`brand_id`, `brand_name`) VALUES
	(1, 'MSI'),
	(2, 'Intel'),
	(3, 'AMD'),
	(4, 'Logitech'),
	(5, 'ASUS'),
	(6, 'Kingston'),
	(7, 'HP'),
	(8, 'Razer'),
	(9, 'Lenovo'),
	(10, 'Acer'),
	(11, 'Jedel');

-- Dumping structure for table powerrig.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `qty` int NOT NULL,
  `product_id` int NOT NULL,
  `user_email` varchar(50) NOT NULL,
  PRIMARY KEY (`cart_id`),
  KEY `fk_cart_product2_idx` (`product_id`),
  KEY `fk_cart_user2_idx` (`user_email`),
  CONSTRAINT `fk_cart_product2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `fk_cart_user2` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.cart: ~0 rows (approximately)

-- Dumping structure for table powerrig.category
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(25) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.category: ~12 rows (approximately)
INSERT INTO `category` (`category_id`, `category_name`) VALUES
	(13, 'Laptop'),
	(15, 'Headphones'),
	(16, 'Monitors'),
	(17, 'Mouse'),
	(18, 'Keyboard'),
	(19, 'Motherboards'),
	(20, 'Processors'),
	(21, 'Graphic Cards'),
	(22, 'Memory'),
	(23, 'Storage'),
	(24, 'Printer'),
	(25, 'Accessories & Parts');

-- Dumping structure for table powerrig.category_has_brand
CREATE TABLE IF NOT EXISTS `category_has_brand` (
  `category_has_brand_id` int NOT NULL AUTO_INCREMENT,
  `category_category_id` tinyint(1) NOT NULL,
  `brand_brand_id` int NOT NULL,
  PRIMARY KEY (`category_has_brand_id`),
  KEY `fk_category_has_brand_brand1_idx` (`brand_brand_id`),
  KEY `fk_category_has_brand_category1_idx` (`category_category_id`),
  CONSTRAINT `fk_category_has_brand_brand1` FOREIGN KEY (`brand_brand_id`) REFERENCES `brand` (`brand_id`),
  CONSTRAINT `fk_category_has_brand_category1` FOREIGN KEY (`category_category_id`) REFERENCES `category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.category_has_brand: ~36 rows (approximately)
INSERT INTO `category_has_brand` (`category_has_brand_id`, `category_category_id`, `brand_brand_id`) VALUES
	(1, 13, 1),
	(3, 15, 1),
	(4, 16, 1),
	(5, 17, 1),
	(6, 18, 1),
	(7, 19, 1),
	(8, 21, 1),
	(9, 20, 2),
	(10, 20, 3),
	(11, 13, 7),
	(12, 24, 7),
	(13, 25, 7),
	(14, 13, 5),
	(15, 21, 5),
	(16, 25, 5),
	(17, 19, 5),
	(18, 15, 5),
	(19, 18, 5),
	(20, 17, 5),
	(21, 16, 5),
	(22, 22, 6),
	(23, 23, 6),
	(24, 13, 9),
	(25, 25, 9),
	(26, 17, 8),
	(27, 15, 8),
	(28, 18, 8),
	(29, 15, 4),
	(30, 17, 4),
	(31, 18, 4),
	(32, 18, 11),
	(33, 17, 11),
	(34, 15, 11),
	(35, 13, 10),
	(36, 25, 10),
	(37, 16, 10),
	(38, 25, 1);

-- Dumping structure for table powerrig.category_has_model
CREATE TABLE IF NOT EXISTS `category_has_model` (
  `category_has_model_id` int NOT NULL AUTO_INCREMENT,
  `category_category_id` tinyint(1) NOT NULL,
  `model_model_id` int NOT NULL,
  PRIMARY KEY (`category_has_model_id`),
  KEY `fk_category_has_model_model1_idx` (`model_model_id`),
  KEY `fk_category_has_model_category1_idx` (`category_category_id`),
  CONSTRAINT `fk_category_has_model_category1` FOREIGN KEY (`category_category_id`) REFERENCES `category` (`category_id`),
  CONSTRAINT `fk_category_has_model_model1` FOREIGN KEY (`model_model_id`) REFERENCES `model` (`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.category_has_model: ~55 rows (approximately)
INSERT INTO `category_has_model` (`category_has_model_id`, `category_category_id`, `model_model_id`) VALUES
	(2, 13, 1),
	(3, 13, 2),
	(4, 13, 3),
	(5, 19, 4),
	(6, 16, 4),
	(7, 16, 5),
	(8, 25, 5),
	(9, 16, 6),
	(10, 21, 7),
	(11, 15, 8),
	(12, 18, 4),
	(13, 18, 44),
	(14, 18, 43),
	(15, 17, 43),
	(16, 18, 45),
	(17, 17, 45),
	(18, 17, 44),
	(19, 20, 9),
	(20, 20, 10),
	(21, 20, 11),
	(22, 13, 12),
	(23, 13, 13),
	(24, 24, 15),
	(25, 24, 16),
	(26, 25, 14),
	(27, 13, 17),
	(28, 25, 19),
	(29, 19, 19),
	(30, 13, 19),
	(31, 15, 19),
	(32, 18, 19),
	(33, 17, 19),
	(34, 21, 18),
	(35, 21, 19),
	(36, 19, 20),
	(37, 22, 21),
	(38, 22, 24),
	(39, 22, 22),
	(40, 23, 25),
	(41, 23, 23),
	(42, 23, 27),
	(43, 22, 26),
	(44, 13, 28),
	(45, 13, 30),
	(46, 25, 30),
	(47, 13, 29),
	(48, 17, 35),
	(49, 17, 36),
	(50, 18, 36),
	(51, 17, 37),
	(52, 18, 38),
	(53, 17, 38),
	(54, 25, 31),
	(55, 13, 31),
	(56, 16, 32),
	(57, 13, 32),
	(58, 16, 33),
	(59, 15, 34),
	(60, 17, 41),
	(61, 17, 40),
	(62, 17, 39),
	(63, 15, 46),
	(64, 19, 5);

-- Dumping structure for table powerrig.city
CREATE TABLE IF NOT EXISTS `city` (
  `city_id` tinyint NOT NULL AUTO_INCREMENT,
  `city_name` varchar(20) NOT NULL,
  `postal_code` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `district_id` tinyint NOT NULL,
  PRIMARY KEY (`city_id`),
  KEY `fk_city_district1_idx` (`district_id`),
  CONSTRAINT `fk_city_district1` FOREIGN KEY (`district_id`) REFERENCES `district` (`district_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.city: ~27 rows (approximately)
INSERT INTO `city` (`city_id`, `city_name`, `postal_code`, `district_id`) VALUES
	(1, 'Akkaraipattu', '32400', 1),
	(2, 'Ambagahawatta', '90326', 1),
	(3, 'Ampara', '32000', 1),
	(4, 'Bakmitiyawa', '32024', 1),
	(5, 'Deegawapiya', '32006', 1),
	(6, 'Devalahinda', '32038', 1),
	(7, 'Padiyatalawa', '32100', 1),
	(8, 'Dorakumbura', '32104', 1),
	(9, 'Gonagolla', '32064', 1),
	(10, 'Hulannuge', '32514', 1),
	(11, 'Kalmunai', '32300', 1),
	(12, 'Kannakipuram', '32405', 1),
	(13, 'Kaduwela', '10640', 5),
	(14, 'Kekirihena', '32074', 1),
	(15, 'Koknahara', '32035', 1),
	(16, 'Kolamanthalawa', '32102', 1),
	(17, 'Colombo 1', '100', 5),
	(18, 'Colombo 3', '300', 5),
	(19, 'Colombo 4', '400', 5),
	(20, 'Colombo 5', '500', 5),
	(21, 'Colombo 7', '700', 5),
	(22, 'Colombo 9', '900', 5),
	(23, 'Colombo 10', '1000', 5),
	(24, 'Colombo 11', '1100', 5),
	(25, 'Colombo 12', '1200', 5),
	(26, 'Colombo 14', '1400', 5),
	(27, 'Vavuniya', '43000', 25);

-- Dumping structure for table powerrig.color
CREATE TABLE IF NOT EXISTS `color` (
  `color_id` int NOT NULL AUTO_INCREMENT,
  `color_name` varchar(20) NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.color: ~7 rows (approximately)
INSERT INTO `color` (`color_id`, `color_name`) VALUES
	(15, 'None'),
	(16, 'Black'),
	(17, 'Titanium Grey'),
	(18, 'Carbon Gray'),
	(19, 'Black & Red'),
	(20, 'White'),
	(21, 'Black & Silver');

-- Dumping structure for table powerrig.comment
CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `comment` text NOT NULL,
  `comment_date` datetime NOT NULL,
  `user_email` varchar(50) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `fk_comment_product1_idx` (`product_id`),
  KEY `fk_comment_user1_idx` (`user_email`),
  CONSTRAINT `fk_comment_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `fk_comment_user1` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.comment: ~1 rows (approximately)
INSERT INTO `comment` (`comment_id`, `product_id`, `comment`, `comment_date`, `user_email`) VALUES
	(4, 46, 'this is a good product', '2026-06-22 05:51:50', 'isururathnayaka999@gmail.com');

-- Dumping structure for table powerrig.condition
CREATE TABLE IF NOT EXISTS `condition` (
  `condition_id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `condition_name` varchar(8) NOT NULL,
  PRIMARY KEY (`condition_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.condition: ~2 rows (approximately)
INSERT INTO `condition` (`condition_id`, `condition_name`) VALUES
	(1, 'New'),
	(2, 'Used');

-- Dumping structure for table powerrig.district
CREATE TABLE IF NOT EXISTS `district` (
  `district_id` tinyint NOT NULL,
  `district_name` varchar(15) NOT NULL,
  `province_id` tinyint(1) NOT NULL,
  PRIMARY KEY (`district_id`),
  KEY `fk_district_province1_idx` (`province_id`),
  CONSTRAINT `fk_district_province1` FOREIGN KEY (`province_id`) REFERENCES `province` (`province_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.district: ~25 rows (approximately)
INSERT INTO `district` (`district_id`, `district_name`, `province_id`) VALUES
	(1, 'Ampara', 6),
	(2, 'Anuradhapura', 8),
	(3, 'Badulla', 7),
	(4, 'Batticaloa', 6),
	(5, 'Colombo', 1),
	(6, 'Galle', 3),
	(7, 'Gampaha', 1),
	(8, 'Hambantota', 3),
	(9, 'Jaffna', 9),
	(10, 'Kalutara', 1),
	(11, 'Kandy', 2),
	(12, 'Kegalle', 5),
	(13, 'Kilinochchi', 9),
	(14, 'Kurunegala', 4),
	(15, 'Mannar', 9),
	(16, 'Matale', 2),
	(17, 'Matara', 3),
	(18, 'Monaragala', 7),
	(19, 'Mullaitivu', 9),
	(20, 'Nuwara Eliya', 2),
	(21, 'Polonnaruwa', 8),
	(22, 'Puttalam', 4),
	(23, 'Ratnapura', 5),
	(24, 'Trincomalee', 6),
	(25, 'Vavuniya', 9);

-- Dumping structure for table powerrig.history
CREATE TABLE IF NOT EXISTS `history` (
  `history_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `order_order_id` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`history_id`),
  KEY `fk_history_product1_idx` (`product_id`),
  KEY `fk_history_order1_idx` (`order_order_id`),
  CONSTRAINT `FK_history_order` FOREIGN KEY (`order_order_id`) REFERENCES `order` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.history: ~0 rows (approximately)

-- Dumping structure for table powerrig.invoice
CREATE TABLE IF NOT EXISTS `invoice` (
  `invoice_id` varchar(12) NOT NULL,
  `order_order_id` varchar(15) NOT NULL,
  `invoice_date` datetime NOT NULL,
  `total_price` double NOT NULL,
  PRIMARY KEY (`invoice_id`),
  KEY `fk_invoice_order1_idx` (`order_order_id`),
  CONSTRAINT `FK_invoice_order` FOREIGN KEY (`order_order_id`) REFERENCES `order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.invoice: ~6 rows (approximately)
INSERT INTO `invoice` (`invoice_id`, `order_order_id`, `invoice_date`, `total_price`) VALUES
	('INV-2D2ED7BA', 'ORD-E1E3B2B9', '2026-06-22 04:54:54', 1309200),
	('INV-662B2BFF', 'ORD-78E2897E', '2026-06-22 04:24:35', 139200),
	('INV-6A60466E', 'ORD-B63232FC', '2026-06-22 04:25:32', 9700),
	('INV-87D70F07', 'ORD-433A376E', '2026-06-22 04:53:57', 1309200),
	('INV-8BC73FF4', 'ORD-917A9862', '2026-06-22 04:31:39', 16880),
	('INV-B4B8279F', 'ORD-1B546105', '2026-06-22 04:27:07', 9700),
	('INV-D81E7541', 'ORD-475A315C', '2026-06-22 05:22:46', 176200);

-- Dumping structure for table powerrig.invoice_item
CREATE TABLE IF NOT EXISTS `invoice_item` (
  `invoice_item_id` int NOT NULL AUTO_INCREMENT,
  `invoice_invoice_id` varchar(12) NOT NULL,
  `product_id` int NOT NULL,
  `product_qty` int NOT NULL,
  PRIMARY KEY (`invoice_item_id`),
  KEY `fk_invoice_item_product1_idx` (`product_id`),
  KEY `fk_invoice_item_invoice1_idx` (`invoice_invoice_id`),
  CONSTRAINT `FK_invoice_item_invoice` FOREIGN KEY (`invoice_invoice_id`) REFERENCES `invoice` (`invoice_id`),
  CONSTRAINT `fk_invoice_item_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.invoice_item: ~7 rows (approximately)
INSERT INTO `invoice_item` (`invoice_item_id`, `invoice_invoice_id`, `product_id`, `product_qty`) VALUES
	(19, 'INV-662B2BFF', 46, 1),
	(20, 'INV-6A60466E', 36, 1),
	(21, 'INV-B4B8279F', 36, 1),
	(22, 'INV-8BC73FF4', 39, 2),
	(24, 'INV-2D2ED7BA', 30, 1),
	(25, 'INV-2D2ED7BA', 38, 2),
	(26, 'INV-2D2ED7BA', 44, 1),
	(27, 'INV-D81E7541', 26, 1);

-- Dumping structure for table powerrig.model
CREATE TABLE IF NOT EXISTS `model` (
  `model_id` int NOT NULL AUTO_INCREMENT,
  `model_name` varchar(25) NOT NULL,
  PRIMARY KEY (`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.model: ~39 rows (approximately)
INSERT INTO `model` (`model_id`, `model_name`) VALUES
	(1, 'MSI CYBORG 15'),
	(2, 'MSI Thin 15'),
	(3, 'MSI Modern 15'),
	(4, 'MSI Pro'),
	(5, 'MSI MAG'),
	(6, 'MSI Optix'),
	(7, 'MSI GeForce RTX'),
	(8, 'MSI Gaming Headphones'),
	(9, 'Intel Core'),
	(10, 'Intel Ultra'),
	(11, 'AMD Ryzen'),
	(12, 'HP Victus'),
	(13, 'HP Elitebook'),
	(14, 'HP Ink Bottle'),
	(15, 'HP Lazer'),
	(16, 'HP DeskJet'),
	(17, 'ASUS Vivobook'),
	(18, 'ASUS GeForce GTX'),
	(19, 'ASUS TUF Gaming'),
	(20, 'ASUS Prime'),
	(21, 'Kingston FURY'),
	(22, 'Kingston DDR5'),
	(23, 'Kingston NV3'),
	(24, 'Kingston DDR4'),
	(25, 'Kingston NV2'),
	(26, 'Kingston HyperX'),
	(27, 'Kingston A400'),
	(28, 'Lenovo LOQ'),
	(29, 'Lenovo V15'),
	(30, 'Lenovo IdeaPad'),
	(31, 'Acer Aspire'),
	(32, 'Acer Nitro'),
	(33, 'Acer FHD'),
	(34, 'Jedel K13'),
	(35, 'Logitech LIGHTSYNC'),
	(36, 'Logitech Nano'),
	(37, 'Logitech Optical'),
	(38, 'Logitech MK'),
	(39, 'Razer DeathAdder'),
	(40, 'Razer Cobra'),
	(41, 'Razer Basilisk'),
	(43, 'MSI Vigor'),
	(44, 'MSI Clutch'),
	(45, 'MSI Forge'),
	(46, 'MSI Maestro');

-- Dumping structure for table powerrig.order
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` varchar(15) NOT NULL DEFAULT '',
  `order_date` datetime NOT NULL,
  `total_amount` varchar(45) NOT NULL,
  `order_status_id` tinyint(1) NOT NULL,
  `shipping_address` int NOT NULL,
  `billing_address` int NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `qty` int DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `fk_order_order_status1_idx` (`order_status_id`),
  KEY `fk_order_user_has_address1_idx` (`billing_address`),
  KEY `fk_order_address1_idx` (`shipping_address`),
  KEY `fk_order_user1_idx` (`user_email`),
  CONSTRAINT `fk_order_address1` FOREIGN KEY (`shipping_address`) REFERENCES `address` (`address_id`),
  CONSTRAINT `fk_order_order_status1` FOREIGN KEY (`order_status_id`) REFERENCES `order_status` (`order_status_id`),
  CONSTRAINT `fk_order_user1` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`),
  CONSTRAINT `fk_order_user_has_address1` FOREIGN KEY (`billing_address`) REFERENCES `address` (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.order: ~7 rows (approximately)
INSERT INTO `order` (`order_id`, `order_date`, `total_amount`, `order_status_id`, `shipping_address`, `billing_address`, `user_email`, `qty`) VALUES
	('ORD-1B546105', '2026-06-22 04:27:07', '9700', 3, 5, 5, 'isururathnayaka999@gmail.com', 1),
	('ORD-433A376E', '2026-06-22 04:53:57', '1309200', 1, 5, 5, 'isururathnayaka999@gmail.com', 0),
	('ORD-475A315C', '2026-06-22 05:22:46', '176200', 5, 5, 5, 'isururathnayaka999@gmail.com', 1),
	('ORD-78E2897E', '2026-06-22 04:24:35', '139200', 5, 5, 5, 'isururathnayaka999@gmail.com', 1),
	('ORD-917A9862', '2026-06-22 04:31:39', '16880', 5, 5, 5, 'isururathnayaka999@gmail.com', 2),
	('ORD-B63232FC', '2026-06-22 04:25:32', '9700', 2, 5, 5, 'isururathnayaka999@gmail.com', 1),
	('ORD-E1E3B2B9', '2026-06-22 04:54:54', '1309200', 4, 5, 5, 'isururathnayaka999@gmail.com', 4);

-- Dumping structure for table powerrig.order_status
CREATE TABLE IF NOT EXISTS `order_status` (
  `order_status_id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `order_status_name` varchar(15) NOT NULL,
  PRIMARY KEY (`order_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.order_status: ~5 rows (approximately)
INSERT INTO `order_status` (`order_status_id`, `order_status_name`) VALUES
	(1, 'Confirm Order'),
	(2, 'Packing'),
	(3, 'Shipped'),
	(4, 'Dispatched'),
	(5, 'Delivered');

-- Dumping structure for table powerrig.payment_method
CREATE TABLE IF NOT EXISTS `payment_method` (
  `method_id` int NOT NULL AUTO_INCREMENT,
  `method_name` varchar(20) NOT NULL,
  PRIMARY KEY (`method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.payment_method: ~2 rows (approximately)
INSERT INTO `payment_method` (`method_id`, `method_name`) VALUES
	(1, 'Credit Card'),
	(2, 'PayPal');

-- Dumping structure for table powerrig.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `price` double NOT NULL,
  `qty` int NOT NULL,
  `description` text NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `datetime_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `default_delivery_fee` double NOT NULL,
  `status_status_id` int NOT NULL,
  `category_category_id` tinyint NOT NULL,
  `brand_brand_id` int NOT NULL,
  `model_model_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_status1_idx` (`status_status_id`) USING BTREE,
  KEY `fk_product_category1_idx` (`category_category_id`),
  KEY `fk_product_brand1_idx` (`brand_brand_id`),
  KEY `fk_product_model1_idx` (`model_model_id`),
  CONSTRAINT `fk_product_brand1` FOREIGN KEY (`brand_brand_id`) REFERENCES `brand` (`brand_id`),
  CONSTRAINT `fk_product_category1` FOREIGN KEY (`category_category_id`) REFERENCES `category` (`category_id`),
  CONSTRAINT `fk_product_model1` FOREIGN KEY (`model_model_id`) REFERENCES `model` (`model_id`),
  CONSTRAINT `fk_product_status1` FOREIGN KEY (`status_status_id`) REFERENCES `status` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.product: ~22 rows (approximately)
INSERT INTO `product` (`id`, `price`, `qty`, `description`, `title`, `datetime_added`, `default_delivery_fee`, `status_status_id`, `category_category_id`, `brand_brand_id`, `model_model_id`) VALUES
	(25, 375000, 10, '– Intel Core i7-13620H Processor\r\n– 512GB NVMe PCIe SSD\r\n– 16GB DDR5 4800MHZ RAM\r\n– 15.6″ FHD (1920×1080), 144Hz, IPS Display\r\n– 6GB NVIDIA GeForce RTX 3050 Graphics\r\n– Single Backlit Keyboard\r\n– Black Edition Color\r\n\r\nMSI Cyborg 15 Gaming A13UDX. Equipped with the powerful Intel Intel Core i7-13620H,\r\nthis laptop ensures smooth and efficient multitasking.\r\n\r\nEnjoy stunning visuals and fluid gameplay on the 15.6″ FHD (1920×1080) IPS display with a 144Hz refresh rate.\r\nThe 6GB NVIDIA GeForce RTX 3050 Graphics card provides top-tier graphics performance.', 'MSI Cyborg 15 Gaming Black Edition A13UDX – i7', '2026-06-15 20:08:23', 0, 1, 13, 1, 1),
	(26, 88000, 7, 'Cores: 10 (6 Performance-cores + 4 Efficient-cores)\r\nThreads: 16\r\nBase Clock: 2.5 GHz\r\nBoost Clock: 4.7 GHz\r\nCache: 20 MB\r\nSocket: FCLGA1700\r\nThe Intel Core i5 Processor 14400 is a 14th generation desktop CPU that offers balanced performance for everyday computing needs. It is suitable for Sri Lankan users involved in office work, online classes, and casual gaming.\r\n\r\nThis processor delivers good multi-threaded and single-threaded performance for daily tasks and moderate workloads. It helps freelancers and home users manage video calls and productivity applications efficiently.\r\n\r\nThe build quality and design support Intel\'s Raptor Lake Refresh architecture, ensuring power-efficient operation with a 65W TDP. The processor fits in motherboards with the FCLGA1700 socket.\r\n\r\nCompatibility with DDR4 and DDR5 memory improves system flexibility. It is suitable for home desktop setups, office PCs, and general productivity within Sri Lankan computer environments.\r\n\r\nWith 10 cores and 16 threads, this processor manages multitasking well, aiding smooth performance across common software applications and light content creation tasks.', 'Intel Core i5-14400 Processor', '2026-06-15 20:25:43', 0, 1, 20, 2, 9),
	(29, 345900, 5, 'Processor: Intel Core i7-13620H\r\nRAM: 16GB DDR5\r\nStorage: 512GB SSD\r\nDisplay: 15.6-inch Full HD IPS 144Hz\r\nGraphics: NVIDIA GeForce RTX 3050 4GB\r\nWeight: 1.98 kg\r\nThe MSI Cyborg 15 A13UC laptop features a powerful Intel Core i7-13620H processor and RTX 3050 graphics, designed for gamers and professionals in Sri Lanka who need strong performance for gaming, design, and multitasking.\r\n\r\nThe Intel 13th Gen i7 processor delivers smooth multitasking and responsive performance for gaming, office work, and streaming. The 15.6-inch Full HD IPS display with 144Hz refresh rate provides clear, sharp visuals.\r\n\r\nThe RTX 3050 graphics card supports light to moderate gaming and creative tasks like video editing and design. It also handles multiple external displays for productivity.\r\n\r\nThe 1.98 kg weight and decent battery life allow users to comfortably carry it for gaming sessions, work, and remote meetings around Sri Lanka.', 'MSI Cyborg 15 A13UC - i7', '2026-06-15 20:37:23', 0, 1, 13, 1, 1),
	(30, 645000, 2, 'High Speed RAM And Enormous Space】32GB high-bandwidth RAM to smoothly run multiple applications and browser tabs all at once; 1TB PCIe NVMe M.2 Solid State Drive allows to fast bootup and data transfer\r\n【Processor】13th Gen Intel Core i7-13620H Processor (10 Cores, 16 Threads, 24MB L3 Cache, Base Frequency at 1.8 GHz, up to 4.9 GHz), with NVIDIA GeForce RTX 4050 (6GB GDDR6)\r\n【Tech Specs】2 x USB 3.2 Gen 1 Type-A, 1 x USB 3.2 Type-C, 1 x HDMI, 1 x RJ-45, 1 x Audio Port; Backlit Keyboard (Fn+F8); Wi-Fi 6 and Bluetooth\r\n【Display】15.6" FHD (1920x1080, 16:9), IPS-Level 144Hz 45%NTSC,\r\n【Operating System】Windows 11 Home-Beautiful, more consistent new design, Great window layout options, Better multi-monitor functionality, Improved performance features, New videogame selection and capabilities, Compatible with Android Apps', 'msi Cyborg 15 Gaming Laptop', '2026-06-15 20:54:30', 0, 1, 13, 1, 1),
	(31, 415000, 4, '【15.6" FHD Touchscreen Display】Enjoy vivid colors and crisp detail with an intuitive touchscreen, delivering an immersive and responsive visual experience for work, entertainment, and everyday use.\r\n【Intel Core 9 270H Processor】Delivers smooth, responsive performance for effortless multitasking—stream, chat, and work simultaneously without slowdowns.\r\n【32GB DDR4 RAM】Seamlessly tackle resource-intensive tasks, smoothly run multiple applications simultaneously, and enjoy lightning-fast responsiveness that keeps you in the flow.【2TB Storage】1TB SSD + 1TB Docking Station Set; Massive storage space for your files, applications, and multimedia content, providing fast and reliable data access.\r\n【Intel Graphics】Deliver vibrant colors and sharp details for clear, smooth visuals in everyday computing and media use.【Backlit Keyboard】Type comfortably and accurately, even in low-light environments.【720p HD Camera】Provides clear, reliable video quality for calls, meetings, and online interactions.\r\n【Windows 11 Home】Dive into limitless productivity with Windows, your gateway to a world of seamless computing.【Dimensions】14.1" x 9.5" x 0.78",4.19 lbs.【Ports】1 x USB-C 3.2, 3 x USB-A 3.2, 1 x HDMI 2.1, 1x Micro SD Card Reader. Wi-Fi 6E, Bluetooth. Black.【Bonus Docking Station Set】1x 7-in-1 Docking Station w/ 1TB Storage, 1x 32GB MicroSD Card w/ Adapter, 1x Type-C Data Cable, 1x 3-in-1 Charging Cable, 1x Suede Cleaning Cloth.', 'MSI Modern 15 H 15.6" FHD Touchcreen Laptop', '2026-06-15 21:03:50', 0, 1, 13, 1, 3),
	(32, 220000, 5, 'Brand	                                        MSI\r\nModel Name	                                Modern 15 A11MU-653\r\nScreen Size	                                15.6 Inches\r\nColor	                                        Carbon Gray\r\nHard Disk Size	                        512 GB\r\nCPU Model	                                Core i7 Family\r\nRam Memory Installed Size	8 GB\r\nOperating System	                Windows 10 Home\r\nSpecial Feature	                        Thin Bezel\r\nGraphics Card Description	Integrated\r\n\r\nSmooth Performance: The 11th Gen. Intel Core i7 processor maximizes your efficiency, multi-tasking, productivity.\r\nIris Xe Graphics: The Intel Iris Xe graphics delivers performance and reliability to supercharge your productivity.\r\nThin Bezel Display: The 15-inch FHD display fully expands your screen estate enhancing your productivity and workflow.\r\nMSI Center: Take optimization to the next level with easily adjustable system modes and resources for a wide range of scenarios and needs.\r\nHi-Res Audio: With the ability to support up to 24bit / 192kHz sampling, hear audio the way it was truly intended.', 'MSI Modern 15A Thin and Light Daily Laptop', '2026-06-15 21:09:01', 0, 1, 13, 1, 3),
	(33, 257000, 5, 'Brand	                                        msi\r\nModel Name	                                Thin 15 B12UCX-2041US\r\nScreen Size	                                15.6 Inches\r\nColor	                                        Black\r\nHard Disk Size	                        512 GB\r\nCPU Model	                                Intel Core i5\r\nRam Memory Installed Size	16 GB\r\nOperating System	                Windows 11 Home\r\nSpecial Feature	                        Backlit Keyboard, HD Audio\r\nGraphics Card	                        RTX 2050', 'MSI Thin 15 15.6” 144Hz FHD Gaming Laptop', '2026-06-15 23:30:03', 0, 1, 13, 1, 2),
	(34, 567000, 6, 'Brand	                                        msi\r\nModel Name	                                MSI Thin 15\r\nScreen Size	                                15.6 Inches\r\nColor	                                        Black\r\nHard Disk Size	                        1 TB\r\nCPU Model	                                AMD Ryzen 5 7535HS\r\nRam Memory Installed Size	16 GB\r\nOperating System	                Windows 11 Home\r\nSpecial Feature	                        RGB Fans\r\nGraphics Card                              RTX 4050\r\n\r\n【FHD Display】The 15.6” 144Hz display delivers fast refresh rate for better immersive experience. Enjoy the latest generation of Windows 11 Home for your everyday needs. MSI recommends Windows 11 Home for business use.\r\n【AMD Ryzen 5 7535HS Processor】the AMD Ryzen 5 7535HS processor is built on the efficient Zen 3+ architecture, featuring 6 cores and 12 threads. It delivers excellent performance for gaming, content creation, and multitasking while maintaining power efficiency for laptops.\r\n【Beyond Fast】The NVIDIA GeForce RTX 4050 powered by the Ada architecture unleashes the full glory of ray tracing, which simulates how light behaves in the real world.\r\n【Ample Memory and Storage】With 16GB DDR4 RAM and a 1TB NVMe SSD, users benefit from quick load times, efficient multitasking, and sufficient storage for games and applications.\r\n【Simplistic Design】Portable and Sleek Design: Weighing around 1.9 kg and boasting a thin chassis, it is easy to carry for school, travel, or daily commutes.\r\n【Modern Connectivity】Offers Wi-Fi 6E, Bluetooth 5.3, and multiple ports (USB Type-C, HDMI 2.1), allowing flexible connections for peripherals and fast network speeds.', 'MSI Thin 15 15.6”144Hz Gaming Laptop', '2026-06-15 23:34:44', 0, 1, 13, 1, 2),
	(35, 14000, 5, 'Brand	                msi\r\nColor	                Black\r\nEar Placement	Over Ear\r\nForm Factor	        Over Ear\r\nImpedance	        32 Ohms\r\n\r\n40mm Neodymium Drivers: High-quality bass-enhancing 40mm drivers reproduce real-life audio across high, medium, and low frequencies for gaming, movies, and music.\r\nUnidirectional (Cardioid) Mic\r\n50 – 15,000 Hz Microphone frequency response\r\nWired USB-C to A Adapter', 'MSI Maestro 300 Gaming Headset', '2026-06-15 23:41:35', 0, 1, 15, 1, 46),
	(36, 9500, 2, 'Brand	                 msi\r\nColor	                 BLACK\r\nEar Placement	 Over Ear\r\nForm Factor	         Over Ear\r\nImpedance	          2.2 Ohms\r\n\r\nTwo large 40mm drivers for quality audio\r\nImproved noise reduction\r\nHigh quality speakers\r\nErgonomic design: extra lightweight and Self-adjusting headband\r\nAdjustable omnidirectional microphone\r\nQuick access to volume, microphone, and vibration control via full function remote controller\r\nConnectivity technology: Wired', 'MSI Gaming Headset with Microphone', '2026-06-15 23:46:02', 0, 1, 15, 1, 8),
	(37, 23500, 6, 'Brand	                msi\r\nColor	                Black\r\nEar Placement	Over Ear\r\nForm Factor	        Over Ear\r\nModel Name	        MAESTRO 500 W BLACK\r\n\r\nHigh-resolution 40mm Drivers - 40 mm drivers deliver deep bass, clear mids, and crisp highs, with response up to 40 kHz for richer detail\r\nActive Noise Cancellation - Processes and counters noise around you, letting you immerse yourself in your games; or stay aware of your surroundings in ambient mode\r\nBeamforming and ENC - Beamforming helps focus on your voice, while ENC reduces surrounding noise for clearer calls, meetings, and in-game communication\r\nSmart Mic Control Instant Mute - Lower the microphone to talk, then raise it to mute instantly for quick, intuitive switching between voice and silence\r\nLong-Lasting Battery Life - A 1000 mAh battery delivers up to 90 hours of playback,supporting long listening through work, travel, and daily use', 'MSI Maestro 500 W Black Gaming Headset', '2026-06-15 23:49:23', 0, 1, 15, 1, 46),
	(38, 20000, 3, 'Brand	                                                        msi\r\nCompatible Devices	                                Laptop\r\nConnectivity Technology	                        2.4 GHz Wireless / Bluetooth / USB 2.0\r\nKeyboard Description	                                Mechanical\r\nRecommended Uses For Product	        Gaming\r\nSpecial Feature	                                        Hot-Swappable\r\nColor	                                                        White\r\nKeyboard backlighting color support	RGB\r\nStyle	                                                        FORGE GK600 TKL W SKY US\r\nProduct Dimensions	                                15.4"L x 7.7"W x 2.3"H\r\n\r\nDURABLE MECHANICAL SWITCHES - The mechanical Linear switches feature a lifespan of 50+ million keystrokes per key that deliver crisp, audible, and linear actuation\r\nDYE-SUBLIMATED PBT KEYCAPS - The GK600 TKL Wireless Sky employs a tenkeyless-sized keyboard layout with dye-sublimated PBT keycaps for improved durability and comfort\r\nTRI-MODE CONNECTIVITY - The FORGE GK600 TKL WIRELESS SKY supports both wired (1.8m USB 2.0) & wireless (2.4GHz & Bluetooth) connectivity with a 4000mAh rechargeable battery providing up to 20 days of continuous gameplay (RGB LED turned off)\r\nINTUITIVE CONTROL & DISPLAY A 1.06-inch screen provides key details such as battery status, RGB lighting profiles, and brightness settings, ensuring quick and easy access to essential information\r\nHOT-SWAPPABLE DESIGN - Easily swap default mechanical switches with 5-pin compatible ones, no soldering required. Customize your typing experience with ease, ensuring flexibility and lasting durability', 'MSI Forge GK600 TKL Wireless Sky', '2026-06-15 23:54:56', 0, 1, 18, 1, 45),
	(39, 8340, 2, 'Brand	                                                msi\r\nCompatible Devices	                        PC\r\nConnectivity Technology	                USB\r\nKeyboard Description	                        Membrane\r\nRecommended Uses For Product	Gaming\r\nSpecial Feature	                                Backlit\r\nMaterial	                                                Plastic\r\nPower Source	                                USB power\r\nKeyboard layout	                                QWERTY\r\nHand Orientation	                                Ambidextrous\r\n\r\nDurable Switches - Years of gaming with switches rated for over 10 Million actuations\r\nHotkeys for Rapid Control - Easy to change lighting effects, media controls, and access widgets\r\nAnti-ghosting - Up to 19-key anti-ghosting allows for simultaneous key presses\r\nAdjustable Keyboard Angles - Two keyboard feet per side support a tilt angle to ensure a more comfortable wrist position\r\nRGB LED - Lighten the mood by playing with predefined effects for the preferred vibe', 'MSI Forge GK100 US', '2026-06-15 23:57:49', 0, 1, 18, 1, 45),
	(40, 18500, 4, 'Brand	                                                      msi\r\nCompatible Devices	                               PC\r\nConnectivity Technology	                       USB\r\nKeyboard Description	                               Mechanical\r\nRecommended Uses For Product	       Gaming\r\nSpecial Feature	                                       Vigor GK30 White Gaming Keyboard\r\nColor	                                                        White\r\nKeyboard backlighting color support	RGB\r\nStyle	                                                        VIGOR GK30 COMBO White\r\nProduct Dimensions	                                 8"L x 21"W x 2"H\r\n\r\nVigor GK30 combo us includes Vigor GK30 gaming keyboard and clutch GM11 gaming mouse, the best combo fit for PC gaming\r\nKeyboard is equipped with special membrane switches for excellent mechanical feel\r\nKeyboard provides 6-region RGB illumination with 8 amazing light effects and supports MSI Mystic Light\r\nMouse is asymmetric ergonomic design', 'MSI Vigor GK30 Combo White', '2026-06-16 00:01:57', 0, 1, 18, 1, 43),
	(41, 140000, 6, 'Graphics Coprocessor	NVIDIA GeForce RTX 5060 Ti\r\nBrand	                                msi\r\nGraphics Ram Size	        8 GB\r\nGPU Clock Speed	        2602 MHz\r\nVideo Output Interface	DisplayPort\r\n\r\nPowered by the NVIDIA Blackwell architecture and DLSS 4\r\nTORX Fan 5.0: Fan blades linked by ring arcs work to stabilize and maintain high-pressure airflow\r\nSolid Baseplate: A solid baseplate transfers heat from the GPU to all heat pipes for better cooling\r\nHeat Pipes designed for efficient heat transfer, the heat pipes effectively draw thermal energy away from the GPU, improving overall cooling performance\r\nMetal Backplate: A sturdy metal backplate strengthens the graphics card while the airflow vent design reduces excess heat', 'msi Gaming RTX 5060 Ti 8G Ventus 3X OC Graphics Card', '2026-06-16 00:06:42', 0, 1, 21, 1, 7),
	(42, 70000, 4, 'Graphics Coprocessor	NVIDIA GeForce RTX 3050\r\nBrand	                                msi\r\nGraphics Ram Size	        6 GB\r\nGPU Clock Speed	        1492 MHz\r\nVideo Output Interface	DisplayPort\r\n\r\nChipset: GeForce RTX 3050\r\nBoost Clock / Memory: 1492 MHz / 14 Gbps\r\nVideo Memory: 6GB GDDR6\r\nMemory Interface: 96-bit\r\nOutput: DisplayPort x 1 (v1.4a) / HDMI 2.1a x 2', 'MSI Gaming RTX 3050 Ventus 2X 6G OC Graphics Card', '2026-06-16 00:10:53', 0, 1, 21, 1, 7),
	(43, 213000, 5, 'Graphics Coprocessor	NVIDIA GeForce RTX 5070\r\nBrand	                                msi\r\nGraphics Ram Size	        12 GB\r\nGPU Clock Speed	        2625 MHz\r\nVideo Output Interface	DisplayPort', 'MSI RTX 5070 12G Gaming Trio OC Graphics Card', '2026-06-16 00:13:08', 0, 1, 21, 1, 7),
	(44, 624000, 5, 'Graphics Coprocessor	NVIDIA GeForce RTX 5080\r\nBrand	                                msi\r\nGraphics Ram Size	        16 GB\r\nGPU Clock Speed	        2.7 GHz\r\nVideo Output Interface	DisplayPort, HDMI', 'MSI GeForce RTX 5080 16G Gaming Trio OC Graphics Card', '2026-06-16 00:16:18', 0, 1, 21, 1, 7),
	(45, 46800, 4, 'Brand	                                        msi\r\nScreen Size	                                27 Inches\r\nResolution	                                FHD 1080p\r\nAspect Ratio	                                16:9\r\nScreen Surface Description	Matte\r\n\r\nRapid VA Panel – Provides 0.5ms (GtG, Min.) response time, optimizes screen colors and brightness\r\n240Hz Refresh Rate – Respond faster with smoother frames\r\n0.5ms (GtG, Min.) Response Time – Eliminate screen tearing and choppy frame rates\r\nAI Vision – The new AI Vision technology can not only reveal details in dark areas but also enhance overall brightness and saturate colors\r\nLess Blue Light – Use a software solution to reduce the light emission in the blue-violet region of the blue light spectrum', 'MSI MAG 274CF X24 27-inch 1920 x 1080 (FHD) Gaming Monitor', '2026-06-17 02:08:46', 0, 1, 16, 1, 5),
	(46, 139000, 5, 'Brand	                                        msi\r\nScreen Size	                                26.5 Inches\r\nResolution	                                2560 x 1440 (QHD)\r\nAspect Ratio	                                16:9\r\nScreen Surface Description	Glossy\r\n\r\nQUANTUM DOT OLED - The MSI MAG 272QP QD-OLED X24 gaming monitor combines 10-bit Quantum Dot color conversion with OLED self-emitting pixels for unrivalled dark levels & an elite response time; An enhanced sub-pixel arrangement improves image sharpness\r\n26.5" WQHD, 10-BIT COLOR - A Quantum Dot OLED panel displays 1.07 billion colors (10-bit, 99% DCI-P3) with extreme Delta E ≤2 color accuracy; (1500000:1 native contrast ratio) & up to 400 nits peak brightness\r\n240 HZ REFRESH RATE, 0.03MS RESPONSE TIME - A high 240 Hz refresh rate is complemented by an incredibly low 0.03ms (GtG) response time for an amazing VESA ClearMR 13000 rating; An elite graphene heatsink (fanless) enhances panel durability\r\nGAMING INTELLIGENCE - MSI GI software features OLED Care 2.0 to help prevent burn-in, AI supported software (Smart Crosshair), Console modes & Game Assistance; Features a 4-way adjustable stand (VESA 100mm)\r\nCUTTING-EDGE CONNECTIVITY - PC, Mac, console & laptop interface options (all WQHD/240Hz) include DisplayPort 1.4a, HDMI 2.1 CEC ports', 'MSI MAG 272QP QD-OLED X24 27-Inch WQHD Gaming Monitor', '2026-06-17 02:11:23', 0, 1, 16, 1, 5),
	(47, 40000, 5, 'Brand	                                        msi\r\nPower Connector Type	        4-Pin\r\nWattage	                                16.8 watts\r\nCooling Method	                        Water\r\nCompatible Devices	                Desktop, Laptop\r\nNoise Level	                                33 Decibels\r\nMaterial	                                        Plastic\r\nMaximum Rotational Speed	5200 RPM\r\nUPC	                                        824142389690 824142381304\r\nNumber of Packs	                        1\r\n\r\nCycloBlade Design: Focuses airflow and reduces noise.\r\nARGB GEN2: Customize the perfect lighting effects for you through MSI CENTER.\r\nEPDM Tubing: Durable and effective in preventing coolant evaporation.\r\nPre-installed Fans: Saves gamers installation time and enhances the assembly experience.\r\nEnhanced Cooling for Stable AI Performance: Precise heat source targeting for stable CPU performance\r\nEasy installation: Pre-installed UNI Bracket (1700/1851/AM4/AM5)', 'MSI MAG Coreliquid A15 360 - AIO ARGB Liquid Cooling', '2026-06-17 02:14:03', 0, 1, 25, 1, 5),
	(48, 21700, 4, 'Product Dimensions	 9.84"L x 4.72"W x 15.75"H\r\nBrand	                         msi\r\nCooling Method	         Water\r\nCompatible Devices	 Desktop, Gaming Console\r\nNoise Level	                 15 Decibels\r\nMaterial	Cold plate:     Copper Radiator: Aluminium Cooling pipe: EPDM with reinforced plastic mesh (multi-layer structure) Fans: High quality plastic with double ball bearingsCold plate: Copper Radiator: Aluminium Cooling pipe: EPDM with reinforced plastic mesh (multi-layer structure) Fans: High quality plastic with double ball bearings\r\nUPC	                         824142394953\r\nNumber of Packs	         1\r\nManufacturer	         MSI\r\nNumber of Items	         1\r\n\r\nMystic Light Compatible: Customize your ARGB fans through MSI\'s Mystic Light software\r\nEvaporation Proof Tubing: Prevents coolant loss and eases bending for convenient assembly\r\nPump Motor Resonance Elimination: Silent operation with optimized performance\r\nDouble Ball Bearring: Delivers powerful performance and exceptional durability', 'MSI MAG Coreliquid A12 240 - AIO ARGB CPU Liquid Cooler', '2026-06-17 02:17:29', 0, 1, 25, 1, 5),
	(49, 65300, 5, 'Brand	                                   msi\r\nCPU Socket	                           Socket AM5\r\nCompatible Devices	           Personal Computer\r\nRAM Memory Technology	   DDR5\r\nCompatible Processors	   Supports AMD Ryzen 9000/7000 Series Desktop Processor\r\nChipset Type	                   B850\r\nMemory Clock Speed	   8400.0\r\nPlatform	                           Windows 11\r\nModel Name	                           MAG B850 Tomahawk MAX\r\nCPU Model	                           Ryzen 7\r\n\r\nULTRA POWER - SUPPORTS THE LATEST RYZEN 9000 PROCESSORS IN HIGH PERFORMANCE - The MAG B850 TOMAHAWK MAX WIFI employs a 14 Duet Rail Power System (80A, SPS) VRM for the AMD B850 chipset (AM5, Ryzen 9000 / 8000 / 7000) with Core Boost architecture\r\nFROZR GUARD - Premium cooling features such as 7W/mK MOSFET thermal pads, extra choke thermal pads and an Extended Heatsink; Includes chipset heatsink, EZ M.2 Shield Frozr II, and a Combo-fan (for pump & system) header (3A)\r\nDDR5 MEMORY, PCIe 5.0 x16 SLOT - 4 x DDR5 DIMM SMT slots enable extreme memory overclocking speeds (1DPC 1R, 8400+ MT/s); 1 x PCIe 5.0 x16 SMT slot (128GB/s) with Steel Armor II supports cutting-edge graphics cards\r\nQUADRUPLE M.2 CONNECTORS - Storage options include 2 x M.2 Gen5 x4 128Gbps slots, 1 x M.2 Gen4 x4 64Gbps slot and 1 x M.2 Gen4 x2 32Gbps slot; Features EZ M.2 Shield Frozr II to prevent thermal throttling and EZ M.2 Clip II for EZ DIY experience\r\nCONNECTIVITY - Network hardware includes a full-speed Wi-Fi 7 module with Bluetooth 5.4 & 5Gbps LAN; Rear ports include USB 20G Type-C and 7.1 USB High Performance Audio with Audio Boost 5 (supports S/PDIF output)', 'MSI MAG B850 Tomahawk MAX WiFi Motherboard', '2026-06-17 02:20:39', 0, 1, 19, 1, 5),
	(50, 32700, 8, 'Model Name	                    MAG A750GL PCIE5\r\nBrand	                             msi\r\nCompatible Devices	     Personal Computer\r\nConnector Type	             ATX\r\nOutput Wattage	              750 Watts\r\nForm Factor	                      ATX\r\nWattage	                      750 watts\r\nCooling Method	              Air\r\nItem dimensions                 L x W x H	12 x 9 x 5 inches\r\nPower Supply Design	       Full Modular\r\n\r\n80 PLUS GOLD CERTIFIED\r\n10-year limited warranty, guaranteeing long term reliable operation\r\nFully modular design\r\nATX 3.1 & PCIE 5.1', 'MSI MAG A750GL PCIE5', '2026-06-17 02:24:48', 0, 1, 25, 1, 5),
	(51, 36840, 5, 'Model Name	                   MAG A850GL\r\nBrand	                           msi\r\nCompatible Devices	   Desktop Computer\r\nConnector Type	           ATX\r\nOutput Wattage	           850.0\r\nForm Factor	                   ATX\r\nWattage	                   850 watts\r\nCooling Method	           Air\r\nItem dimensions             L x W x H 11.5 x 9.25 x 5 inches\r\nItem Weight	                  6.31 Pounds\r\n\r\n80 PLUS GOLD CERTIFIED\r\n5-year limited warranty, guaranteeing long term reliable operation\r\nFully modular design\r\nPCIE 5.1', 'MSI MAG A850GL PCIE5 White', '2026-06-17 02:29:40', 0, 1, 25, 1, 5);

-- Dumping structure for table powerrig.product_has_color
CREATE TABLE IF NOT EXISTS `product_has_color` (
  `product_has_color_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `color_color_id` int NOT NULL,
  PRIMARY KEY (`product_has_color_id`),
  KEY `fk_product_has_color1_color1_idx` (`color_color_id`),
  KEY `fk_product_has_color1_product1_idx` (`product_id`),
  CONSTRAINT `fk_product_has_color1_color1` FOREIGN KEY (`color_color_id`) REFERENCES `color` (`color_id`),
  CONSTRAINT `fk_product_has_color1_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.product_has_color: ~22 rows (approximately)
INSERT INTO `product_has_color` (`product_has_color_id`, `product_id`, `color_color_id`) VALUES
	(11, 25, 16),
	(12, 26, 17),
	(15, 29, 16),
	(16, 30, 16),
	(17, 31, 16),
	(18, 32, 18),
	(19, 33, 16),
	(20, 34, 16),
	(21, 35, 16),
	(22, 36, 19),
	(23, 37, 16),
	(24, 38, 20),
	(25, 39, 16),
	(26, 40, 20),
	(27, 41, 21),
	(28, 42, 16),
	(29, 43, 16),
	(30, 44, 16),
	(31, 45, 16),
	(32, 46, 16),
	(33, 47, 16),
	(34, 48, 16),
	(35, 49, 16),
	(36, 50, 16),
	(37, 51, 20);

-- Dumping structure for table powerrig.product_img
CREATE TABLE IF NOT EXISTS `product_img` (
  `img_path` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `product_id` int NOT NULL,
  PRIMARY KEY (`img_path`),
  KEY `fk_product_img_product2_idx` (`product_id`),
  CONSTRAINT `fk_product_img_product2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.product_img: ~133 rows (approximately)
INSERT INTO `product_img` (`img_path`, `product_id`) VALUES
	('./resources/product_images/intel_core_i514400_processor_prod26_img0.jpg', 26),
	('./resources/product_images/intel_core_i514400_processor_prod26_img1.jpg', 26),
	('./resources/product_images/intel_core_i514400_processor_prod26_img2.jpg', 26),
	('./resources/product_images/intel_core_i514400_processor_prod26_img3.jpg', 26),
	('./resources/product_images/intel_core_i514400_processor_prod26_img4.jpg', 26),
	('./resources/product_images/intel_core_i514400_processor_prod26_img5.jpg', 26),
	('./resources/product_images/msi_cyborg_15_a13uc_intel_core_i713620h_16gb_ram_512gb_ssd_156inch_fhd_rtx_3050_laptop_prod29_img0.jpg', 29),
	('./resources/product_images/msi_cyborg_15_a13uc_intel_core_i713620h_16gb_ram_512gb_ssd_156inch_fhd_rtx_3050_laptop_prod29_img1.jpg', 29),
	('./resources/product_images/msi_cyborg_15_a13uc_intel_core_i713620h_16gb_ram_512gb_ssd_156inch_fhd_rtx_3050_laptop_prod29_img2.jpg', 29),
	('./resources/product_images/msi_cyborg_15_a13uc_intel_core_i713620h_16gb_ram_512gb_ssd_156inch_fhd_rtx_3050_laptop_prod29_img3.jpg', 29),
	('./resources/product_images/msi_cyborg_15_a13uc_intel_core_i713620h_16gb_ram_512gb_ssd_156inch_fhd_rtx_3050_laptop_prod29_img4.jpg', 29),
	('./resources/product_images/msi_cyborg_15_a13uc_intel_core_i713620h_16gb_ram_512gb_ssd_156inch_fhd_rtx_3050_laptop_prod29_img5.jpg', 29),
	('./resources/product_images/msi_cyborg_15_gaming_black_edition_a13udx_i7_prod25_img0.png', 25),
	('./resources/product_images/msi_cyborg_15_gaming_black_edition_a13udx_i7_prod25_img1.webp', 25),
	('./resources/product_images/msi_cyborg_15_gaming_black_edition_a13udx_i7_prod25_img2.webp', 25),
	('./resources/product_images/msi_cyborg_15_gaming_black_edition_a13udx_i7_prod25_img3.webp', 25),
	('./resources/product_images/msi_cyborg_15_gaming_laptop_prod30_img0.jpg', 30),
	('./resources/product_images/msi_cyborg_15_gaming_laptop_prod30_img1.jpg', 30),
	('./resources/product_images/msi_cyborg_15_gaming_laptop_prod30_img2.jpg', 30),
	('./resources/product_images/msi_cyborg_15_gaming_laptop_prod30_img3.jpg', 30),
	('./resources/product_images/msi_cyborg_15_gaming_laptop_prod30_img4.jpg', 30),
	('./resources/product_images/msi_cyborg_15_gaming_laptop_prod30_img5.jpg', 30),
	('./resources/product_images/msi_forge_gk100_us_prod39_img0.jpg', 39),
	('./resources/product_images/msi_forge_gk100_us_prod39_img1.jpg', 39),
	('./resources/product_images/msi_forge_gk100_us_prod39_img2.jpg', 39),
	('./resources/product_images/msi_forge_gk100_us_prod39_img3.jpg', 39),
	('./resources/product_images/msi_forge_gk100_us_prod39_img4.jpg', 39),
	('./resources/product_images/msi_forge_gk100_us_prod39_img5.jpg', 39),
	('./resources/product_images/msi_forge_gk600_tkl_wireless_sky_prod38_img0.jpg', 38),
	('./resources/product_images/msi_forge_gk600_tkl_wireless_sky_prod38_img1.jpg', 38),
	('./resources/product_images/msi_forge_gk600_tkl_wireless_sky_prod38_img2.jpg', 38),
	('./resources/product_images/msi_forge_gk600_tkl_wireless_sky_prod38_img3.jpg', 38),
	('./resources/product_images/msi_forge_gk600_tkl_wireless_sky_prod38_img4.jpg', 38),
	('./resources/product_images/msi_forge_gk600_tkl_wireless_sky_prod38_img5.jpg', 38),
	('./resources/product_images/msi_gaming_headset_with_microphone_prod36_img0.jpg', 36),
	('./resources/product_images/msi_gaming_headset_with_microphone_prod36_img1.jpg', 36),
	('./resources/product_images/msi_gaming_headset_with_microphone_prod36_img2.jpg', 36),
	('./resources/product_images/msi_gaming_headset_with_microphone_prod36_img3.jpg', 36),
	('./resources/product_images/msi_gaming_headset_with_microphone_prod36_img4.jpg', 36),
	('./resources/product_images/msi_gaming_headset_with_microphone_prod36_img5.jpg', 36),
	('./resources/product_images/msi_gaming_rtx_3050_ventus_2x_6g_oc_graphics_card_prod42_img0.jpg', 42),
	('./resources/product_images/msi_gaming_rtx_3050_ventus_2x_6g_oc_graphics_card_prod42_img1.jpg', 42),
	('./resources/product_images/msi_gaming_rtx_3050_ventus_2x_6g_oc_graphics_card_prod42_img2.jpg', 42),
	('./resources/product_images/msi_gaming_rtx_3050_ventus_2x_6g_oc_graphics_card_prod42_img3.jpg', 42),
	('./resources/product_images/msi_gaming_rtx_3050_ventus_2x_6g_oc_graphics_card_prod42_img4.jpg', 42),
	('./resources/product_images/msi_gaming_rtx_5060_ti_8g_ventus_3x_oc_graphics_card_prod41_img0.jpg', 41),
	('./resources/product_images/msi_gaming_rtx_5060_ti_8g_ventus_3x_oc_graphics_card_prod41_img1.jpg', 41),
	('./resources/product_images/msi_gaming_rtx_5060_ti_8g_ventus_3x_oc_graphics_card_prod41_img2.jpg', 41),
	('./resources/product_images/msi_gaming_rtx_5060_ti_8g_ventus_3x_oc_graphics_card_prod41_img3.jpg', 41),
	('./resources/product_images/msi_gaming_rtx_5060_ti_8g_ventus_3x_oc_graphics_card_prod41_img4.jpg', 41),
	('./resources/product_images/msi_gaming_rtx_5060_ti_8g_ventus_3x_oc_graphics_card_prod41_img5.jpg', 41),
	('./resources/product_images/msi_geforce_rtx_5080_16g_gaming_trio_oc_graphics_card_prod44_img0.jpg', 44),
	('./resources/product_images/msi_geforce_rtx_5080_16g_gaming_trio_oc_graphics_card_prod44_img1.jpg', 44),
	('./resources/product_images/msi_geforce_rtx_5080_16g_gaming_trio_oc_graphics_card_prod44_img2.jpg', 44),
	('./resources/product_images/msi_geforce_rtx_5080_16g_gaming_trio_oc_graphics_card_prod44_img3.jpg', 44),
	('./resources/product_images/msi_geforce_rtx_5080_16g_gaming_trio_oc_graphics_card_prod44_img4.jpg', 44),
	('./resources/product_images/msi_geforce_rtx_5080_16g_gaming_trio_oc_graphics_card_prod44_img5.jpg', 44),
	('./resources/product_images/msi_maestro_300_gaming_headset_prod35_img0.jpg', 35),
	('./resources/product_images/msi_maestro_300_gaming_headset_prod35_img1.jpg', 35),
	('./resources/product_images/msi_maestro_300_gaming_headset_prod35_img2.jpg', 35),
	('./resources/product_images/msi_maestro_300_gaming_headset_prod35_img3.jpg', 35),
	('./resources/product_images/msi_maestro_300_gaming_headset_prod35_img4.jpg', 35),
	('./resources/product_images/msi_maestro_300_gaming_headset_prod35_img5.jpg', 35),
	('./resources/product_images/msi_maestro_500_w_black_gaming_headset_prod37_img0.jpg', 37),
	('./resources/product_images/msi_maestro_500_w_black_gaming_headset_prod37_img1.jpg', 37),
	('./resources/product_images/msi_maestro_500_w_black_gaming_headset_prod37_img2.jpg', 37),
	('./resources/product_images/msi_maestro_500_w_black_gaming_headset_prod37_img3.jpg', 37),
	('./resources/product_images/msi_maestro_500_w_black_gaming_headset_prod37_img4.jpg', 37),
	('./resources/product_images/msi_maestro_500_w_black_gaming_headset_prod37_img5.jpg', 37),
	('./resources/product_images/msi_mag_272qp_qdoled_x24_27inch_wqhd_gaming_monitor_prod46_img0.jpg', 46),
	('./resources/product_images/msi_mag_272qp_qdoled_x24_27inch_wqhd_gaming_monitor_prod46_img1.jpg', 46),
	('./resources/product_images/msi_mag_272qp_qdoled_x24_27inch_wqhd_gaming_monitor_prod46_img2.jpg', 46),
	('./resources/product_images/msi_mag_272qp_qdoled_x24_27inch_wqhd_gaming_monitor_prod46_img3.jpg', 46),
	('./resources/product_images/msi_mag_272qp_qdoled_x24_27inch_wqhd_gaming_monitor_prod46_img4.jpg', 46),
	('./resources/product_images/msi_mag_272qp_qdoled_x24_27inch_wqhd_gaming_monitor_prod46_img5.jpg', 46),
	('./resources/product_images/msi_mag_274cf_x24_27inch_1920_x_1080_fhd_gaming_monitor_prod45_img0.jpg', 45),
	('./resources/product_images/msi_mag_274cf_x24_27inch_1920_x_1080_fhd_gaming_monitor_prod45_img1.jpg', 45),
	('./resources/product_images/msi_mag_274cf_x24_27inch_1920_x_1080_fhd_gaming_monitor_prod45_img2.jpg', 45),
	('./resources/product_images/msi_mag_274cf_x24_27inch_1920_x_1080_fhd_gaming_monitor_prod45_img3.jpg', 45),
	('./resources/product_images/msi_mag_274cf_x24_27inch_1920_x_1080_fhd_gaming_monitor_prod45_img4.jpg', 45),
	('./resources/product_images/msi_mag_274cf_x24_27inch_1920_x_1080_fhd_gaming_monitor_prod45_img5.jpg', 45),
	('./resources/product_images/msi_mag_a750gl_pcie5_prod50_img0.jpg', 50),
	('./resources/product_images/msi_mag_a750gl_pcie5_prod50_img1.jpg', 50),
	('./resources/product_images/msi_mag_a750gl_pcie5_prod50_img2.jpg', 50),
	('./resources/product_images/msi_mag_a750gl_pcie5_prod50_img3.jpg', 50),
	('./resources/product_images/msi_mag_a750gl_pcie5_prod50_img4.jpg', 50),
	('./resources/product_images/msi_mag_a750gl_pcie5_prod50_img5.jpg', 50),
	('./resources/product_images/msi_mag_a850gl_pcie5_white_prod51_img0.jpg', 51),
	('./resources/product_images/msi_mag_a850gl_pcie5_white_prod51_img1.jpg', 51),
	('./resources/product_images/msi_mag_a850gl_pcie5_white_prod51_img2.jpg', 51),
	('./resources/product_images/msi_mag_a850gl_pcie5_white_prod51_img3.jpg', 51),
	('./resources/product_images/msi_mag_a850gl_pcie5_white_prod51_img4.jpg', 51),
	('./resources/product_images/msi_mag_a850gl_pcie5_white_prod51_img5.jpg', 51),
	('./resources/product_images/msi_mag_b850_tomahawk_max_wifi_motherboard_prod49_img0.jpg', 49),
	('./resources/product_images/msi_mag_b850_tomahawk_max_wifi_motherboard_prod49_img1.jpg', 49),
	('./resources/product_images/msi_mag_b850_tomahawk_max_wifi_motherboard_prod49_img2.jpg', 49),
	('./resources/product_images/msi_mag_b850_tomahawk_max_wifi_motherboard_prod49_img3.jpg', 49),
	('./resources/product_images/msi_mag_b850_tomahawk_max_wifi_motherboard_prod49_img4.jpg', 49),
	('./resources/product_images/msi_mag_b850_tomahawk_max_wifi_motherboard_prod49_img5.jpg', 49),
	('./resources/product_images/msi_mag_coreliquid_a12_240_aio_argb_cpu_liquid_cooler_prod48_img0.jpg', 48),
	('./resources/product_images/msi_mag_coreliquid_a12_240_aio_argb_cpu_liquid_cooler_prod48_img1.jpg', 48),
	('./resources/product_images/msi_mag_coreliquid_a12_240_aio_argb_cpu_liquid_cooler_prod48_img2.jpg', 48),
	('./resources/product_images/msi_mag_coreliquid_a12_240_aio_argb_cpu_liquid_cooler_prod48_img3.jpg', 48),
	('./resources/product_images/msi_mag_coreliquid_a12_240_aio_argb_cpu_liquid_cooler_prod48_img4.jpg', 48),
	('./resources/product_images/msi_mag_coreliquid_a12_240_aio_argb_cpu_liquid_cooler_prod48_img5.jpg', 48),
	('./resources/product_images/msi_mag_coreliquid_a15_360_aio_argb_liquid_cooling_prod47_img0.jpg', 47),
	('./resources/product_images/msi_mag_coreliquid_a15_360_aio_argb_liquid_cooling_prod47_img1.jpg', 47),
	('./resources/product_images/msi_mag_coreliquid_a15_360_aio_argb_liquid_cooling_prod47_img2.jpg', 47),
	('./resources/product_images/msi_mag_coreliquid_a15_360_aio_argb_liquid_cooling_prod47_img3.jpg', 47),
	('./resources/product_images/msi_mag_coreliquid_a15_360_aio_argb_liquid_cooling_prod47_img4.jpg', 47),
	('./resources/product_images/msi_mag_coreliquid_a15_360_aio_argb_liquid_cooling_prod47_img5.jpg', 47),
	('./resources/product_images/msi_modern_15a_thin_and_light_daily_laptop_prod32_img0.jpg', 32),
	('./resources/product_images/msi_modern_15a_thin_and_light_daily_laptop_prod32_img1.jpg', 32),
	('./resources/product_images/msi_modern_15a_thin_and_light_daily_laptop_prod32_img2.jpg', 32),
	('./resources/product_images/msi_modern_15a_thin_and_light_daily_laptop_prod32_img3.jpg', 32),
	('./resources/product_images/msi_modern_15a_thin_and_light_daily_laptop_prod32_img4.jpg', 32),
	('./resources/product_images/msi_modern_15a_thin_and_light_daily_laptop_prod32_img5.jpg', 32),
	('./resources/product_images/msi_modern_15_h_156_fhd_touchcreen_laptop_prod31_img0.jpg', 31),
	('./resources/product_images/msi_modern_15_h_156_fhd_touchcreen_laptop_prod31_img1.jpg', 31),
	('./resources/product_images/msi_modern_15_h_156_fhd_touchcreen_laptop_prod31_img2.jpg', 31),
	('./resources/product_images/msi_modern_15_h_156_fhd_touchcreen_laptop_prod31_img3.jpg', 31),
	('./resources/product_images/msi_modern_15_h_156_fhd_touchcreen_laptop_prod31_img4.jpg', 31),
	('./resources/product_images/msi_modern_15_h_156_fhd_touchcreen_laptop_prod31_img5.jpg', 31),
	('./resources/product_images/msi_rtx_5070_12g_gaming_trio_oc_graphics_card_prod43_img0.jpg', 43),
	('./resources/product_images/msi_rtx_5070_12g_gaming_trio_oc_graphics_card_prod43_img1.jpg', 43),
	('./resources/product_images/msi_rtx_5070_12g_gaming_trio_oc_graphics_card_prod43_img2.jpg', 43),
	('./resources/product_images/msi_rtx_5070_12g_gaming_trio_oc_graphics_card_prod43_img3.jpg', 43),
	('./resources/product_images/msi_rtx_5070_12g_gaming_trio_oc_graphics_card_prod43_img4.jpg', 43),
	('./resources/product_images/msi_rtx_5070_12g_gaming_trio_oc_graphics_card_prod43_img5.jpg', 43),
	('./resources/product_images/msi_thin_15_156144hz_gaming_laptop_prod34_img0.jpg', 34),
	('./resources/product_images/msi_thin_15_156144hz_gaming_laptop_prod34_img1.jpg', 34),
	('./resources/product_images/msi_thin_15_156144hz_gaming_laptop_prod34_img2.jpg', 34),
	('./resources/product_images/msi_thin_15_156144hz_gaming_laptop_prod34_img3.jpg', 34),
	('./resources/product_images/msi_thin_15_156144hz_gaming_laptop_prod34_img4.jpg', 34),
	('./resources/product_images/msi_thin_15_156144hz_gaming_laptop_prod34_img5.jpg', 34),
	('./resources/product_images/msi_thin_15_156_144hz_fhd_gaming_laptop_prod33_img0.jpg', 33),
	('./resources/product_images/msi_thin_15_156_144hz_fhd_gaming_laptop_prod33_img1.jpg', 33),
	('./resources/product_images/msi_thin_15_156_144hz_fhd_gaming_laptop_prod33_img2.jpg', 33),
	('./resources/product_images/msi_thin_15_156_144hz_fhd_gaming_laptop_prod33_img3.jpg', 33),
	('./resources/product_images/msi_thin_15_156_144hz_fhd_gaming_laptop_prod33_img4.jpg', 33),
	('./resources/product_images/msi_thin_15_156_144hz_fhd_gaming_laptop_prod33_img5.jpg', 33),
	('./resources/product_images/msi_vigor_gk30_combo_white_prod40_img0.jpg', 40),
	('./resources/product_images/msi_vigor_gk30_combo_white_prod40_img1.jpg', 40),
	('./resources/product_images/msi_vigor_gk30_combo_white_prod40_img2.jpg', 40),
	('./resources/product_images/msi_vigor_gk30_combo_white_prod40_img3.jpg', 40),
	('./resources/product_images/msi_vigor_gk30_combo_white_prod40_img4.jpg', 40),
	('./resources/product_images/msi_vigor_gk30_combo_white_prod40_img5.jpg', 40);

-- Dumping structure for table powerrig.profile_img
CREATE TABLE IF NOT EXISTS `profile_img` (
  `img_path` varchar(100) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  PRIMARY KEY (`img_path`),
  KEY `fk_profile_img_user1_idx` (`user_email`),
  CONSTRAINT `fk_profile_img_user1` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.profile_img: ~1 rows (approximately)
INSERT INTO `profile_img` (`img_path`, `user_email`) VALUES
	('./resources/product/Isuru_6a35a8d480087.jpeg', 'isururathnayaka999@gmail.com');

-- Dumping structure for table powerrig.province
CREATE TABLE IF NOT EXISTS `province` (
  `province_id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `province_name` varchar(15) NOT NULL,
  PRIMARY KEY (`province_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.province: ~9 rows (approximately)
INSERT INTO `province` (`province_id`, `province_name`) VALUES
	(1, 'Western'),
	(2, 'Central'),
	(3, 'Southern'),
	(4, 'North Western'),
	(5, 'Sabaragamuwa'),
	(6, 'Eastern'),
	(7, 'Uva'),
	(8, 'North Central'),
	(9, 'Northern');

-- Dumping structure for table powerrig.shippingcost_by_district
CREATE TABLE IF NOT EXISTS `shippingcost_by_district` (
  `delivery_id` int NOT NULL AUTO_INCREMENT,
  `district_district_id` tinyint NOT NULL,
  `delivery_fee` varchar(45) NOT NULL,
  PRIMARY KEY (`delivery_id`),
  KEY `fk_shippingcost_by_city_district1_idx` (`district_district_id`),
  CONSTRAINT `fk_shippingcost_by_city_district1` FOREIGN KEY (`district_district_id`) REFERENCES `district` (`district_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.shippingcost_by_district: ~22 rows (approximately)
INSERT INTO `shippingcost_by_district` (`delivery_id`, `district_district_id`, `delivery_fee`) VALUES
	(1, 5, '200'),
	(2, 1, '600'),
	(3, 2, '600'),
	(4, 3, '600'),
	(5, 6, '600'),
	(6, 7, '350'),
	(7, 8, '600'),
	(8, 9, '600'),
	(9, 10, '350'),
	(10, 11, '450'),
	(11, 12, '600'),
	(12, 13, '600'),
	(13, 14, '450'),
	(15, 15, '600'),
	(16, 16, '600'),
	(17, 17, '600'),
	(18, 18, '600'),
	(19, 19, '600'),
	(20, 20, '600'),
	(21, 21, '600'),
	(22, 22, '600'),
	(23, 23, '500'),
	(24, 24, '600'),
	(25, 25, '600');

-- Dumping structure for table powerrig.status
CREATE TABLE IF NOT EXISTS `status` (
  `status_id` int NOT NULL AUTO_INCREMENT,
  `status_name` varchar(7) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.status: ~3 rows (approximately)
INSERT INTO `status` (`status_id`, `status_name`) VALUES
	(1, 'Active'),
	(2, 'Disable'),
	(3, 'Block');

-- Dumping structure for table powerrig.user
CREATE TABLE IF NOT EXISTS `user` (
  `email` varchar(50) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `password` varchar(10) NOT NULL,
  `verification_code` varchar(17) DEFAULT NULL,
  `joined_date` datetime NOT NULL,
  `status_status_id` int NOT NULL,
  PRIMARY KEY (`email`),
  KEY `fk_user_status2_idx` (`status_status_id`),
  CONSTRAINT `fk_user_status2` FOREIGN KEY (`status_status_id`) REFERENCES `status` (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.user: ~2 rows (approximately)
INSERT INTO `user` (`email`, `fname`, `lname`, `password`, `verification_code`, `joined_date`, `status_status_id`) VALUES
	('isururathnayaka999@gmail.com', 'Isuru', 'Rathnayaka', 'isuru123', NULL, '2026-06-12 03:34:58', 1),
	('powerriginc@google.com', 'PowerRig', 'Company', 'power123', NULL, '2026-06-12 03:39:10', 1);

-- Dumping structure for table powerrig.user_has_address
CREATE TABLE IF NOT EXISTS `user_has_address` (
  `user_has_address_id` int NOT NULL AUTO_INCREMENT,
  `address_address_id` int NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `district_district_id` tinyint NOT NULL,
  PRIMARY KEY (`user_has_address_id`),
  KEY `fk_user_has_address_address1_idx` (`address_address_id`),
  KEY `fk_user_has_address_user1_idx` (`user_email`),
  KEY `fk_user_has_address_district1_idx` (`district_district_id`),
  CONSTRAINT `fk_user_has_address_address1` FOREIGN KEY (`address_address_id`) REFERENCES `address` (`address_id`),
  CONSTRAINT `fk_user_has_address_district1` FOREIGN KEY (`district_district_id`) REFERENCES `district` (`district_id`),
  CONSTRAINT `fk_user_has_address_user1` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.user_has_address: ~1 rows (approximately)
INSERT INTO `user_has_address` (`user_has_address_id`, `address_address_id`, `user_email`, `district_district_id`) VALUES
	(5, 5, 'isururathnayaka999@gmail.com', 5);

-- Dumping structure for table powerrig.wishlist
CREATE TABLE IF NOT EXISTS `wishlist` (
  `wishlist_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_email` varchar(50) NOT NULL,
  PRIMARY KEY (`wishlist_id`),
  KEY `fk_wishlist_product1_idx` (`product_id`),
  KEY `fk_wishlist_user1_idx` (`user_email`),
  CONSTRAINT `fk_wishlist_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `fk_wishlist_user1` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table powerrig.wishlist: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
