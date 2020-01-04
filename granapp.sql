-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 03, 2020 at 11:34 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `granapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`admin_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cupon`
--

DROP TABLE IF EXISTS `cupon`;
CREATE TABLE IF NOT EXISTS `cupon` (
  `cupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `pv_id` int(11) NOT NULL,
  `cupon_code` varchar(20) NOT NULL,
  `new_price` double NOT NULL,
  PRIMARY KEY (`cupon_id`),
  KEY `product_id` (`pv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cupon`
--

INSERT INTO `cupon` (`cupon_id`, `pv_id`, `cupon_code`, `new_price`) VALUES
(4, 5, 'jag', 0.6),
(5, 10, 'baka', 0.3),
(6, 6, 'dada', 1),
(8, 13, 'fty', 1.9),
(11, 1, 'dfg', 0.7);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `user_id`) VALUES
(1, 15),
(2, 16),
(3, 29),
(4, 30);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `information` int(255) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `grammage` double NOT NULL,
  `unit` varchar(5) NOT NULL,
  `date_added` date DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `grammage`, `unit`, `date_added`) VALUES
(4, 'jagode', 0, '', '2019-05-14'),
(5, 'jabuke', 1000, '', '2019-05-29'),
(12, 'Drina ', 50, '', '2019-06-11'),
(13, 'Jagode', 200, 'g', '2019-06-06'),
(14, 'Jafa', 2, 'g', '2019-06-08'),
(15, 'Burek', 1500, '', '2019-03-23'),
(18, 'Cips', 50, '', '2019-06-17'),
(27, 'Cola', 2, 'l', '2019-06-18'),
(29, 'Frondi', 150, 'g', '2019-06-19'),
(30, 'jabuke', 1, 'kg', '2019-06-07'),
(31, 'Burek', 1.5, 'kg', '2019-06-04');

-- --------------------------------------------------------

--
-- Table structure for table `product-vendor`
--

DROP TABLE IF EXISTS `product-vendor`;
CREATE TABLE IF NOT EXISTS `product-vendor` (
  `pv_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `vendor_id` int(11) NOT NULL,
  PRIMARY KEY (`pv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product-vendor`
--

INSERT INTO `product-vendor` (`pv_id`, `product_id`, `price`, `vendor_id`) VALUES
(1, 5, 5.45, 17),
(3, 11, 5.45, 17),
(4, 12, 5.45, 17),
(5, 13, 5.45, 17),
(6, 14, 5.45, 17),
(7, 15, 10, 17),
(8, 16, 5.45, 17),
(9, 17, 20, 17),
(10, 18, 5.45, 17),
(11, 19, 3, 17),
(12, 20, 2, 17),
(13, 21, 2, 17),
(14, 22, 5.45, 17),
(15, 23, 4, 17),
(16, 24, 1, 17),
(17, 25, 2, 17),
(18, 26, 2, 17),
(19, 27, 2.3, 17),
(20, 28, 2.1, 17),
(21, 29, 2, 17),
(22, 30, 3, 15),
(23, 31, 3.5, 15);

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

DROP TABLE IF EXISTS `record`;
CREATE TABLE IF NOT EXISTS `record` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_string` varchar(255) NOT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `record`
--

INSERT INTO `record` (`record_id`, `record_string`) VALUES
(64, 'User with id 1 updated his information'),
(2, 'User with id 1 updated his information'),
(3, ' Vendor with user id 11 has deleted product with id 22'),
(4, ' Vendor with user id 11 has updated product with id 13'),
(5, 'User with id 11 updated cupon with id 11'),
(37, 'User with id 20 has been deleted'),
(7, ' Vendor with user id 11 has deleted cupon with id 7'),
(8, ' Vendor with user id 11 has deleted cupon with id 14'),
(9, ' Vendor with user id 11 has deleted cupon with id 16'),
(10, ' Vendor with user id 11 has deleted cupon with id 17'),
(11, ' Vendor with user id 11 has deleted cupon with id 15'),
(12, ' Vendor with user id 11 has deleted cupon with id 18'),
(13, ' Vendor with user id 11 has deleted cupon with id 19'),
(14, 'Vendor with user id 11 has updated cupon with id 20'),
(15, 'Vendor with user id 11 has added product with id 25'),
(16, 'Vendor with user id 11 has added product with id 26'),
(25, 'User with id 1 updated his information'),
(18, ' Vendor with user id 11 has deleted product with id 26'),
(19, ' Vendor with user id 11 has deleted product with id 25'),
(20, 'Vendor with user id 11 has added product with id 28'),
(21, ' Vendor with user id 11 has deleted product with id 28'),
(22, 'Vendor with user id 11 has added product with id 29'),
(23, ' Vendor with user id 11 has updated product with id 23'),
(24, 'User with id 11 updated his password'),
(26, ' Vendor with user id 1 has updated his information'),
(27, 'User with id 1 updated his password'),
(28, 'User with id 1 updated his password'),
(29, 'User with id 1 updated his information'),
(30, 'User with id 1 updated his information'),
(31, 'User with id 1 updated his password'),
(32, 'User with id 1 updated his password'),
(33, 'Vendor with user id 19 has been created'),
(34, 'User with id 19 has been deleted'),
(35, 'User with id 19 has been deleted'),
(36, 'User with id 19 has been deleted'),
(38, 'Vendor with user id 21 has been created'),
(39, 'User with id 21 has been deleted'),
(40, 'User with id 22 has been created'),
(41, 'User with id 23 has been created'),
(42, 'User with id 1 updated his information'),
(43, 'User with id 1 updated his information'),
(44, 'User with id 24 has been created'),
(45, 'User with id 25 has been created'),
(46, 'User with id 25 has been deleted'),
(47, 'User with id 26 has been created'),
(48, 'User with id 11 has been deleted'),
(49, 'User with id 27 has been created'),
(50, 'User with id 28 has been created'),
(51, 'Customer with user id 29 has been created'),
(52, ' Vendor with user id 28 has updated product with id 13'),
(53, ' Vendor with user id 1 has deleted product with id 24'),
(54, 'Vendor with user id 1 updated cupon with id 3'),
(55, ' Vendor with user id 1 has deleted cupon with id 3'),
(56, 'Vendor with user id 26 has added product with id 30'),
(57, 'Vendor with user id 26 has added product with id 31'),
(58, 'Customer with user id 30 has been created'),
(59, 'User with id 1 updated his information'),
(60, 'User with id 30 has been deleted'),
(61, ' Vendor with user id 1 has deleted product with id 23'),
(62, ' Vendor with user id 1 has deleted cupon with id 13'),
(63, ' Vendor with user id 1 has deleted cupon with id 20'),
(65, ' Vendor with user id 28 has deleted product with id 11'),
(66, ' Vendor with user id 28 has deleted cupon with id 12'),
(67, 'Customer with user id 30 has been created');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type_id` int(11) NOT NULL COMMENT '1 - admin, 2-user, 3 - vendor',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `lastname`, `address`, `email`, `password`, `user_type_id`) VALUES
(1, 'Aldin', 'Berisa', 'Hadzici', 'aldin@gmail.com', '$2y$10$hnJDx3.3Fb57oU5z/DtfGu66h.LEk6Fk8h1LbWhWacoaOcSd1w5RC', 1),
(12, 'amra', 'berisa', 'Grive', 'amra@berisa.com', '$2y$10$hnJDx3.3Fb57oU5z/DtfGu66h.LEk6Fk8h1LbWhWacoaOcSd1w5RC', 2),
(15, 'alem', 'berisa', 'Berlin', 'alem@berisa.com', 'aldin', 2),
(16, 'Emela', 'Karovic', 'Pazaric 27', 'e_karovci@gmail.com', 'dada', 2),
(26, 'Beri', 'Mouse', 'Hadzici', 'info@kventum.ba', '$2y$10$hnJDx3.3Fb57oU5z/DtfGu66h.LEk6Fk8h1LbWhWacoaOcSd1w5RC', 3),
(28, 'campo', 'berisa', 'grivici', 'campo@thedog.com', '$2y$10$hnJDx3.3Fb57oU5z/DtfGu66h.LEk6Fk8h1LbWhWacoaOcSd1w5RC', 3),
(29, 'Amir', 'HadÅ¾iÄ‡', 'Sarajevo', 'amir@hadzic.com', '$2y$10$sE78QcG6Dclu3tDQPGxeSOvjt3.QzJqGOP/Y4WBIpc0eDd6tMKARa', 2),
(30, 'Aldin', 'BeriÅ¡a', 'Hadzici', 'aldin_berisa15@outlook.com', '$2y$10$Q/o2lnR5rhHWzWWUu8nnau9OoED6vOQoNFm5SgYiBTILg9vtYiuYa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
CREATE TABLE IF NOT EXISTS `vendor` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company` varchar(50) NOT NULL,
  PRIMARY KEY (`vendor_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`vendor_id`, `user_id`, `company`) VALUES
(15, 26, 'DogoCity'),
(17, 28, 'Bingo');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cupon`
--
ALTER TABLE `cupon`
  ADD CONSTRAINT `cupon_ibfk_1` FOREIGN KEY (`pv_id`) REFERENCES `product-vendor` (`pv_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vendor`
--
ALTER TABLE `vendor`
  ADD CONSTRAINT `vendor_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
