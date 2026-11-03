-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2025 at 02:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `im2_finalproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discountID` int(11) NOT NULL,
  `discountedamount` decimal(10,2) NOT NULL,
  `discountname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`discountID`, `discountedamount`, `discountname`) VALUES
(1, 0.20, 'Senior Citizen / PWD Discount'),
(2, 0.10, '10% Discount'),
(3, 0.15, '15% Discount'),
(4, 0.20, '20% Discount'),
(5, 0.25, '25% Discount'),
(6, 0.30, '30% Discount'),
(7, 0.35, '35% Discount'),
(8, 0.40, '40% Discount'),
(9, 0.45, '45% Discount'),
(10, 0.50, '50% Discount'),
(11, 0.55, '55% Discount'),
(12, 0.60, '60% Discount'),
(13, 0.65, '65% Discount'),
(14, 0.70, '70% Discount'),
(15, 0.75, '75% Discount'),
(16, 0.80, '80% Discount'),
(17, 0.80, '85% Discount'),
(18, 0.90, '90% Discount'),
(19, 0.90, '95% Discount'),
(20, 1.00, '100% Discount');

-- --------------------------------------------------------

--
-- Table structure for table `orderlines`
--

CREATE TABLE `orderlines` (
  `orderlineID` int(11) NOT NULL,
  `orderID` int(11) DEFAULT NULL,
  `productID` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `totalamount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderlines`
--

INSERT INTO `orderlines` (`orderlineID`, `orderID`, `productID`, `quantity`, `totalamount`) VALUES
(1, 2, 3, 4, 228.00),
(2, 2, 64, 2, 100.00),
(3, 2, 2, 1, 7.00),
(4, 2, 79, 1, 15.00),
(5, 2, 3, 4, 228.00),
(6, 2, 64, 2, 100.00),
(7, 2, 2, 1, 7.00),
(8, 2, 79, 1, 15.00),
(9, 3, 1, 1, 5.00),
(10, 4, 31, 1, 23.00),
(11, 4, 4, 4, 228.00),
(12, 4, 69, 6, 252.00),
(13, 5, 43, 5, 70.00),
(14, 6, 43, 5, 70.00),
(15, 7, 3, 1, 57.00),
(16, 8, 3, 1, 57.00),
(17, 9, 1, 4, 20.00),
(18, 10, 1, 5, 25.00),
(20, 10, 1, 1, 5.00),
(22, 11, 1, 1, 5.00),
(23, 12, 1, 1, 5.00),
(25, 14, 2, 1, 7.00),
(26, 15, 75, 12, 456.00),
(27, 15, 91, 15, 750.00),
(28, 16, 31, 8, 184.00),
(29, 16, 83, 15, 375.00),
(30, 17, 52, 14, 588.00),
(31, 17, 47, 2, 40.00),
(32, 18, 26, 7, 56.00),
(33, 18, 36, 5, 50.00),
(34, 19, 43, 5, 70.00),
(35, 19, 61, 8, 440.00),
(36, 20, 9, 3, 36.00),
(37, 20, 85, 1, 25.00),
(38, 21, 2, 4, 28.00),
(39, 21, 23, 14, 210.00),
(40, 22, 86, 14, 252.00),
(41, 22, 70, 8, 256.00),
(42, 23, 10, 15, 135.00),
(43, 23, 80, 7, 49.00),
(44, 24, 18, 13, 195.00),
(45, 24, 62, 7, 350.00),
(46, 25, 6, 15, 120.00),
(47, 25, 30, 4, 52.00),
(48, 26, 27, 1, 7.00),
(49, 26, 57, 2, 210.00),
(50, 27, 78, 3, 45.00),
(51, 27, 7, 13, 78.00),
(52, 28, 11, 9, 135.00),
(53, 28, 33, 11, 352.00),
(54, 29, 38, 10, 130.00),
(55, 29, 56, 2, 196.00),
(56, 30, 58, 14, 392.00),
(57, 30, 93, 3, 90.00),
(58, 31, 13, 11, 165.00),
(59, 31, 71, 15, 570.00),
(60, 32, 76, 11, 165.00),
(61, 32, 16, 2, 30.00),
(62, 33, 20, 15, 150.00),
(63, 33, 46, 7, 91.00),
(64, 34, 19, 10, 100.00),
(65, 34, 55, 1, 42.00),
(66, 35, 90, 2, 150.00),
(67, 35, 12, 6, 210.00),
(68, 36, 66, 1, 190.00),
(69, 36, 8, 3, 165.00),
(70, 37, 94, 6, 600.00),
(71, 37, 21, 8, 56.00),
(72, 38, 1, 13, 65.00),
(73, 38, 5, 5, 35.00),
(74, 39, 39, 5, 65.00),
(75, 39, 17, 1, 15.00),
(76, 40, 84, 6, 150.00),
(77, 40, 60, 11, 44.00),
(78, 41, 59, 4, 16.00),
(79, 41, 28, 2, 20.00),
(80, 42, 34, 15, 375.00),
(81, 42, 35, 5, 190.00),
(82, 43, 24, 10, 100.00),
(83, 43, 53, 12, 264.00),
(84, 44, 22, 9, 63.00),
(85, 44, 32, 1, 38.00),
(86, 45, 49, 5, 100.00),
(87, 45, 77, 9, 63.00),
(88, 46, 25, 4, 32.00),
(89, 46, 87, 14, 140.00),
(90, 47, 64, 10, 500.00),
(91, 47, 73, 2, 64.00),
(92, 48, 41, 8, 32.00),
(93, 48, 44, 13, 182.00),
(94, 49, 45, 2, 24.00),
(95, 49, 40, 12, 156.00),
(96, 50, 95, 15, 270.00),
(97, 50, 75, 12, 456.00),
(98, 51, 91, 15, 750.00),
(99, 51, 31, 8, 184.00),
(100, 52, 83, 15, 375.00),
(101, 52, 52, 14, 588.00),
(102, 53, 47, 2, 40.00),
(103, 53, 26, 7, 56.00),
(104, 54, 36, 5, 50.00),
(105, 54, 43, 5, 70.00),
(106, 55, 61, 8, 440.00),
(107, 55, 9, 3, 36.00),
(108, 56, 85, 1, 25.00),
(109, 56, 2, 4, 28.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `orderdatetime` datetime NOT NULL,
  `totalamount` decimal(10,2) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Pending',
  `completeddatetime` datetime DEFAULT NULL,
  `paymentmethod` varchar(100) DEFAULT NULL,
  `proofofpayment` tinyint(1) DEFAULT NULL,
  `discountID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `orderdatetime`, `totalamount`, `status`, `completeddatetime`, `paymentmethod`, `proofofpayment`, `discountID`) VALUES
(1, 5, '2025-07-15 17:59:18', 350.00, 'Completed', '2025-07-15 17:59:18', 'COD', 0, NULL),
(2, 5, '2025-07-15 17:59:52', 350.00, 'Pending', NULL, 'COD', 0, NULL),
(3, 5, '2025-07-15 18:17:19', 5.00, 'Completed', '2025-07-23 18:18:44', 'QR', 1, NULL),
(4, 5, '2025-07-15 18:20:10', 402.00, 'Pending', NULL, 'COD', 0, 1),
(5, 5, '2025-07-15 18:39:31', 127.00, 'Cancelled', NULL, NULL, NULL, NULL),
(6, 5, '2025-07-15 21:33:27', 57.00, 'Pending', NULL, 'QR', 1, NULL),
(7, 2, '2025-07-15 23:49:45', 0.00, 'Completed', '2025-06-04 11:38:53', 'COD', 0, 1),
(8, 2, '2025-07-15 23:51:05', 0.00, 'Pending', NULL, 'COD', 0, 1),
(9, 2, '2025-07-16 00:03:57', 16.00, 'Pending', NULL, 'COD', 0, 1),
(10, 5, '2025-07-16 00:09:36', 25.00, 'Pending', NULL, 'COD', 0, NULL),
(11, 5, '2025-07-16 00:11:26', 5.00, 'Cancelled', NULL, NULL, NULL, NULL),
(12, 5, '2025-07-16 00:22:41', 5.00, 'Pending', NULL, 'COD', 0, NULL),
(14, 2, '2025-07-16 02:26:33', 7.00, 'Cancelled', NULL, NULL, NULL, NULL),
(15, 7, '2025-07-15 17:59:18', 1206.00, 'Pending', NULL, 'COD', 0, NULL),
(16, 7, '2025-07-15 17:59:18', 559.00, 'Pending', NULL, 'COD', 0, NULL),
(17, 7, '2025-07-15 17:59:18', 628.00, 'Pending', NULL, 'COD', 0, NULL),
(18, 8, '2025-07-15 17:59:18', 106.00, 'Cancelled', NULL, NULL, NULL, NULL),
(19, 8, '2025-07-15 17:59:18', 510.00, 'Pending', NULL, 'COD', 0, NULL),
(20, 8, '2025-07-15 17:59:18', 61.00, 'Pending', NULL, 'COD', 0, NULL),
(21, 9, '2025-07-15 17:59:18', 238.00, 'Pending', NULL, 'QR', 1, NULL),
(22, 9, '2025-07-15 17:59:18', 508.00, 'Pending', NULL, 'QR', 1, NULL),
(23, 9, '2025-07-15 17:59:18', 184.00, 'Pending', NULL, 'QR', 1, NULL),
(24, 10, '2025-07-15 17:59:18', 545.00, 'Pending', NULL, 'COD', 0, NULL),
(25, 10, '2025-07-15 17:59:18', 172.00, 'Pending', NULL, 'COD', 0, NULL),
(26, 10, '2025-07-15 17:59:18', 217.00, 'Cancelled', NULL, NULL, NULL, NULL),
(27, 11, '2025-07-15 17:59:18', 123.00, 'Pending', NULL, 'QR', 1, NULL),
(28, 11, '2025-07-15 17:59:18', 487.00, 'Pending', NULL, 'QR', 1, NULL),
(29, 11, '2025-07-15 17:59:18', 326.00, 'Pending', NULL, 'QR', 1, NULL),
(30, 12, '2025-07-15 17:59:18', 482.00, 'Pending', NULL, 'QR', 1, NULL),
(31, 12, '2025-07-15 17:59:18', 735.00, 'Pending', NULL, 'QR', 1, NULL),
(32, 12, '2025-07-15 17:59:18', 195.00, 'Pending', NULL, 'QR', 1, NULL),
(33, 13, '2025-07-15 17:59:18', 241.00, 'Cancelled', NULL, NULL, NULL, NULL),
(34, 13, '2025-07-15 17:59:18', 142.00, 'Pending', NULL, 'COD', 0, NULL),
(35, 13, '2025-07-15 17:59:18', 360.00, 'Pending', NULL, 'COD', 0, NULL),
(36, 14, '2025-07-15 17:59:18', 255.00, 'Pending', NULL, 'COD', 0, NULL),
(37, 14, '2025-07-15 17:59:18', 656.00, 'Cancelled', NULL, NULL, NULL, NULL),
(38, 14, '2025-07-15 17:59:18', 100.00, 'Pending', NULL, 'COD', 0, NULL),
(39, 15, '2025-07-15 17:59:18', 80.00, 'Pending', NULL, 'QR', 1, NULL),
(40, 15, '2025-07-15 17:59:18', 194.00, 'Pending', NULL, 'QR', 1, NULL),
(41, 15, '2025-07-15 17:59:18', 36.00, 'Pending', NULL, 'QR', 1, NULL),
(42, 16, '2025-07-15 17:59:18', 565.00, 'Pending', NULL, 'COD', 0, NULL),
(43, 16, '2025-07-15 17:59:18', 364.00, 'Pending', NULL, 'COD', 0, NULL),
(44, 16, '2025-07-15 17:59:18', 101.00, 'Cancelled', NULL, NULL, NULL, NULL),
(45, 17, '2025-07-15 17:59:18', 163.00, 'Pending', NULL, 'QR', 1, NULL),
(46, 17, '2025-07-15 17:59:18', 172.00, 'Pending', NULL, 'QR', 1, NULL),
(47, 17, '2025-07-15 17:59:18', 564.00, 'Pending', NULL, 'QR', 1, NULL),
(48, 18, '2025-07-15 17:59:18', 214.00, 'Pending', NULL, 'COD', 0, NULL),
(49, 18, '2025-07-15 17:59:18', 180.00, 'Pending', NULL, 'COD', 0, NULL),
(50, 18, '2025-07-15 17:59:18', 726.00, 'Cancelled', NULL, NULL, NULL, NULL),
(51, 19, '2025-07-15 17:59:18', 934.00, 'Cancelled', NULL, NULL, NULL, NULL),
(52, 19, '2025-07-15 17:59:18', 963.00, 'Pending', NULL, 'QR', 1, NULL),
(53, 19, '2025-07-15 17:59:18', 96.00, 'Pending', NULL, 'QR', 1, NULL),
(54, 20, '2025-07-15 17:59:18', 120.00, 'Pending', NULL, 'COD', 0, NULL),
(55, 20, '2025-07-15 17:59:18', 476.00, 'Pending', NULL, 'COD', 0, NULL),
(56, 20, '2025-07-15 17:59:18', 53.00, 'Pending', NULL, 'COD', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productID` int(11) UNSIGNED NOT NULL,
  `productname` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stockquantity` int(11) DEFAULT NULL,
  `img` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `productname`, `category`, `price`, `stockquantity`, `img`) VALUES
(1, 'Ajinomoto Seasoning (11g)', 'Cooking', 5.00, 87, 'ajinomoto.jpg'),
(2, 'Knorr Beef Cubes', 'Cooking', 7.00, 99, 'knorr beef cubes.jpg'),
(3, 'Knorr Crab and Corn Soup (55g)', 'Cooking', 57.00, 100, 'knorr crab n corn.jpg'),
(4, 'Knorr Cream of Mushroom Soup (62g)', 'Cooking', 57.00, 100, 'knorr cream of mushroom.jpg'),
(5, 'Knorr Chicken Cubes', 'Cooking', 7.00, 100, 'knorr chicken cubes.jpg'),
(6, 'Maggi Oyster Sauce', 'Cooking', 8.00, 100, 'maggi oyster sauce.jpg'),
(7, 'Maggi Magic Sarap', 'Cooking', 6.00, 100, 'maggi magic sarap.jpg'),
(8, 'Maggi Calamansi Liquid Seasoning (130mL)', 'Cooking', 55.00, 100, 'maggi calamansi liquid seasoning.jpg'),
(9, 'Soy Sauce (200mL)', 'Cooking', 12.00, 100, 'soy sauce.jpg'),
(10, 'Vinegar (200mL)', 'Cooking', 9.00, 100, 'vinegar.jpg'),
(11, 'Bottled Water (500mL)', 'Beverage', 15.00, 100, 'bottled water.jpg'),
(12, 'C2 Apple Green Tea (500mL)', 'Beverage', 35.00, 100, 'c2.jpg'),
(13, 'Chuckie (110mL)', 'Beverage', 15.00, 100, 'chuckie.jpg'),
(14, 'Cobra Energy Drink (300mL)', 'Beverage', 20.00, 100, 'cobra energy drink.jpg'),
(15, 'Gatorade (500mL)', 'Beverage', 40.00, 100, 'gatorade.jpg'),
(16, 'Coke (190mL)', 'Beverage', 15.00, 100, 'coke.jpg'),
(17, 'Royal (190mL)', 'Beverage', 15.00, 100, 'royal.jpg'),
(18, 'Sprite (190mL)', 'Beverage', 15.00, 100, 'sprite.jpg'),
(19, 'Zest-O Apple Juice (200mL)', 'Beverage', 10.00, 100, 'zesto.jpg'),
(20, 'Yakult Cultured Milk', 'Beverage', 10.00, 100, 'yakult.jpg'),
(21, 'Ace Chocolate Crackers', 'Biscuits', 7.00, 100, 'ace choco crackers.jpg'),
(22, 'Hansel Crackers', 'Biscuits', 7.00, 100, 'hansel crackers.jpg'),
(23, 'Rebisco Plain Crackers', 'Biscuits', 15.00, 100, 'rebisco.png'),
(24, 'Cream-O Chocolate Cookies', 'Biscuits', 10.00, 100, 'creamo.jpg'),
(25, 'Presto Creams Cookies', 'Biscuits', 8.00, 100, 'presto.jpg'),
(26, 'Wafrets Choco Wafer', 'Biscuits', 8.00, 100, 'wafrets.jpg'),
(27, 'Pillows Choco Crackers', 'Biscuits', 7.00, 100, 'pillows.png'),
(28, 'Bread Pan', 'Biscuits', 10.00, 100, 'bread pan.png'),
(29, 'Chocolate Wafer', 'Biscuits', 13.00, 100, 'chocolate wafer.png'),
(30, 'Cal Cheese Wafers', 'Biscuits', 13.00, 100, 'cal cheese.jpg'),
(31, 'Holiday Beef Loaf (150g)', 'Canned Goods', 23.00, 100, 'beef loaf.jpg'),
(32, 'Holiday Corned Beef (160g)', 'Canned Goods', 38.00, 100, 'corned beef.jpg'),
(33, 'Holiday Carne Norte (155g)', 'Canned Goods', 32.00, 100, 'carne norte.jpg'),
(34, 'Senorita Sardines (155g)', 'Canned Goods', 25.00, 100, 'sardines.jpg'),
(35, 'Century Tuna (155g)', 'Canned Goods', 38.00, 100, 'century tuna.png'),
(36, 'Lucky Me Beef Noodles', 'Canned Goods', 10.00, 100, 'beef noodles.jpg'),
(37, 'Lucky Me Chicken Noodles', 'Canned Goods', 10.00, 100, 'chicken noodles.png'),
(38, 'Lucky Me Calamansi Pancit Canton', 'Canned Goods', 13.00, 100, 'pancit calamansi.jpg'),
(39, 'Lucky Me Chilimansi Pancit Canton', 'Canned Goods', 13.00, 100, 'pancit chilimansi.jpg'),
(40, 'Lucky Me Extra Hot Pancit Canton', 'Canned Goods', 13.00, 100, 'pancit extra hot.jpg'),
(41, 'Nescafe Coffee Stick', 'Instant Drinks', 4.00, 100, 'nescafe stick.png'),
(42, 'Nescafe Coffee Mate', 'Instant Drinks', 3.00, 100, 'coffee mate.jpg'),
(43, 'Kopiko Brown Coffee', 'Instant Drinks', 14.00, 100, 'kopiko brown.jpg'),
(44, 'Kopiko Black Coffee', 'Instant Drinks', 14.00, 100, 'kopiko black.jpg'),
(45, 'Bear Brand Swak', 'Instant Drinks', 12.00, 100, 'bearbrand.jpg'),
(46, 'Bear Brand Choco Swak', 'Instant Drinks', 13.00, 100, 'bearbrand choco.jpg'),
(47, 'Nestea Iced Tea', 'Instant Drinks', 20.00, 100, 'nestea.jpg'),
(48, 'Milo', 'Instant Drinks', 9.00, 100, 'milo.jpg'),
(49, 'Tang Orange Juice', 'Instant Drinks', 20.00, 100, 'tang orange.jpg'),
(50, 'Tang Pineapple Juice', 'Instant Drinks', 20.00, 100, 'tang pineapple.jpg'),
(51, 'Maxx Cherry (50pcs)', 'Candy', 42.00, 100, 'maxx red.png'),
(52, 'Maxx Honey Lemon (50pcs)', 'Candy', 42.00, 100, 'maxx yellow.png'),
(53, 'Chubby Choco Candy (20pcs)', 'Candy', 22.00, 100, 'chubby choco.jpg'),
(54, 'Chubby Milk Candy (20pcs)', 'Candy', 22.00, 100, 'chubby milk.jpg'),
(55, 'Judge Chewing Gum (50pcs)', 'Candy', 42.00, 100, 'judge gum.png'),
(56, 'White Rabbit Butter Toffee (100pcs)', 'Candy', 98.00, 100, 'white rabbit.jpg'),
(57, 'Snow Bear Candy (100pcs)', 'Candy', 105.00, 100, 'snow bear.png'),
(58, 'Nips Mini Candy-Coated Chocolate (24pcs)', 'Candy', 28.00, 100, 'nips mini.png'),
(59, 'Mello Pastel Marshmallows', 'Candy', 4.00, 100, 'mello marshmallow.png'),
(60, 'Mello Strawberry Marshmallows', 'Candy', 4.00, 100, 'mello strawberry.jpg'),
(61, 'Ganador Oliver Rice (1kg)', 'Rice', 55.00, 100, 'ganador.jpg'),
(62, 'Grandeur Hasmin Rice (1kg)', 'Rice', 50.00, 100, 'grandeur.jpg'),
(63, 'Lion Ivory Grains Rice (1kg)', 'Rice', 53.00, 100, 'lion ivory.jpg'),
(64, 'Dona Conchita Rice (1kg)', 'Rice', 50.00, 100, 'dona conchita.jpg'),
(65, 'Farmboy Jasmine Rice (1kg)', 'Rice', 55.00, 100, 'farmboy jasmine.jpg'),
(66, 'Care Adult Diaper Large (8pcs)', 'Diapers', 190.00, 100, 'care adult diaper.jpg'),
(67, 'EQ Dry Small (4pcs)', 'Diapers', 34.00, 100, 'eq dry s.jpg'),
(68, 'EQ Dry Medium (4pcs)', 'Diapers', 38.00, 100, 'eq dry m.jpg'),
(69, 'EQ Dry Large (4pcs)', 'Diapers', 42.00, 100, 'eq dry l.jpeg'),
(70, 'Pampers Small (4pcs)', 'Diapers', 32.00, 100, 'pampers small.jpg'),
(71, 'Pampers Medium (4pcs)', 'Diapers', 38.00, 100, 'pampers medium.jpg'),
(72, 'Pampers Large (4pcs)', 'Diapers', 42.00, 100, 'pampers large.jpg'),
(73, 'Happy Pants Medium', 'Diapers', 32.00, 100, 'happy medium.jpg'),
(74, 'Happy Pants Large (4pcs)', 'Diapers', 35.00, 100, 'happy large.jpeg'),
(75, 'Happy Pants XLarge (4pcs)', 'Diapers', 38.00, 100, 'happy xl.jpg'),
(76, 'Tide Powder', 'Laundry', 15.00, 100, 'tide.jpg'),
(77, 'Surf Powder', 'Laundry', 7.00, 100, 'surf.png'),
(78, 'Ariel Powder', 'Laundry', 15.00, 100, 'ariel.png'),
(79, 'Breeze Powder', 'Laundry', 15.00, 100, 'breeze.png'),
(80, 'Downy Fabric Conditioner', 'Laundry', 7.00, 100, 'downy.png'),
(81, 'Champion Powder', 'Laundry', 7.00, 100, 'champion powder.jpg'),
(82, 'Wings Powder', 'Laundry', 7.00, 100, 'wings powder.png'),
(83, 'Speed Soap Bar', 'Laundry', 25.00, 100, 'speed soap.jpg'),
(84, 'Pride Soap Bar', 'Laundry', 25.00, 100, 'pride soap.png'),
(85, 'EverClean Soap Bar', 'Laundry', 25.00, 100, 'everclean soap.jpg'),
(86, 'Bioderm (60g)', 'Personal Hygiene', 18.00, 100, 'bioderm.jpg'),
(87, 'Colgate Toothpaste (22g)', 'Personal Hygiene', 10.00, 100, 'colgate toothpaste.jpg'),
(88, 'Cream Silk Conditioner', 'Personal Hygiene', 7.00, 100, 'creamsilk.jpg'),
(89, 'Sunsilk Shampoo', 'Personal Hygiene', 7.00, 100, 'sunsilk.png'),
(90, 'Dove Roll-On Deodorant (25mL)', 'Personal Hygiene', 75.00, 100, 'dove deoderant.jpg'),
(91, 'Casino Ethyl Alcohol (250mL)', 'Personal Hygiene', 50.00, 100, 'casino alcohol.jpg'),
(92, 'Happy Cotton Buds (100pcs)', 'Personal Hygiene', 12.00, 100, 'cotton buds.jpg'),
(93, 'Johnsons Baby Powder (25g)', 'Personal Hygiene', 30.00, 100, 'baby powder.jpeg'),
(94, 'Nursy Wet Wipes (80 pcs)', 'Personal Hygiene', 100.00, 100, 'wet wipes.png'),
(95, 'Colgate Toothbrush', 'Personal Hygiene', 18.00, 100, 'colgate toothbrush.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `roleID` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleID`, `description`) VALUES
(1, 'Admin'),
(2, 'Customer');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `shippingaddress` varchar(100) DEFAULT NULL,
  `contactnumber` varchar(100) DEFAULT NULL,
  `roleID` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `password`, `fullname`, `shippingaddress`, `contactnumber`, `roleID`) VALUES
(1, 'password', 'Test Admin', NULL, NULL, 1),
(2, 'password', 'Test User', NULL, NULL, 2),
(4, '123', 'Theresa Arcelo', 'Liloan', '09458925494', 1),
(5, '123', 'Zyleika Invento', 'Cebu City', '09105550664', 1),
(6, '123', 'Queenie Piloton', 'Consolacion', '09195559249  ', 1),
(7, '<39pX7G3g6C]', 'Jose Rizal', 'Compostela', '02835558654  ', 2),
(8, 'X1{k~u\\+=083', 'Andres Bonifacio', 'Bogo', '02805550620  ', 2),
(9, '9%b2Af5CX@c2', 'Leni Robredo', 'Carcar', '09225556214  ', 2),
(10, 'z[OaH15e>6xf', 'Juan Luna', 'Carmen', '09325557340  ', 2),
(11, 'Vav7-5=1;;4O', 'Melchora Aquino', 'Catmon', '09195556812  ', 2),
(12, '1,G3r2}L,~JW', 'Gabriela Silang', 'Cordova', '09195555418  ', 2),
(13, 'olRJv926rK98', 'Bini Mikha', 'Daan Bantayan', '09325550830  ', 2),
(14, '9xY5oik91LSj', 'Bini Aiah', 'Danao', '09225559564  ', 2),
(15, 'd1k82AwS116u', 'Bini Maloi', 'Dalaguete', '09195550516  ', 2),
(16, '58zkzV0HtVp5', 'Bini Jhoanna', 'Moalboal', '09285552178  ', 2),
(17, '|Ohs613#)bO4', 'Bini Colet', 'Oslob', '09085554432  ', 2),
(18, '89Gk,kf4|+8*', 'Bini Sheena', 'Minglanilla', '09335552457  ', 2),
(19, 'ZbP27#4m*?\\}', 'Bini Stacey', 'Naga', '09295558649  ', 2),
(20, 'xY71zcEvl5i8', 'Bini Gwen', 'Sibonga', '09295550064', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discountID`);

--
-- Indexes for table `orderlines`
--
ALTER TABLE `orderlines`
  ADD PRIMARY KEY (`orderlineID`),
  ADD KEY `orderID` (`orderID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `discountID` (`discountID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roleID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `roleID` (`roleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orderlines`
--
ALTER TABLE `orderlines`
  MODIFY `orderlineID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderlines`
--
ALTER TABLE `orderlines`
  ADD CONSTRAINT `orderlines_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderlines_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`discountID`) REFERENCES `discounts` (`discountID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `roles` (`roleID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
