-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 05:54 PM
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
-- Database: `supermarket`
--

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `OfferID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Discount` int(11) NOT NULL,
  `QuantityReqd` int(11) NOT NULL,
  `OfferDescription` varchar(100) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`OfferID`, `ProductID`, `Discount`, `QuantityReqd`, `OfferDescription`, `StartDate`, `EndDate`) VALUES
(401, 1001, 20, 3, '20% OFF on buying 3kgs', '2025-04-01', '2025-05-01'),
(402, 1002, 15, 3, '15% OFF on buying 3kgs', '2025-06-01', '2025-07-01'),
(403, 1003, 40, 5, '40% OFF on buying 5kgs', '2025-08-01', '2025-09-01'),
(404, 1004, 25, 1, 'BUY 1 GET 1 FREE', '2025-12-01', '2025-12-31'),
(405, 1010, 30, 5, '30% OFF on buying 5kgs', '2025-04-07', '2025-04-15'),
(406, 1011, 20, 3, '20% OFF on buying 3kgs', '2025-04-07', '2025-04-15'),
(407, 1014, 40, 1, '40% OFF on buying 1kgs', '2025-04-01', '2025-04-30'),
(408, 1015, 40, 2, '40% OFF on buying 2kgs', '2025-04-01', '2025-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(30) NOT NULL,
  `Category` varchar(30) NOT NULL,
  `Price` int(11) NOT NULL,
  `Supplier` varchar(30) NOT NULL,
  `Description` varchar(100) NOT NULL,
  `imageURL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `Category`, `Price`, `Supplier`, `Description`, `imageURL`) VALUES
(1001, 'Apple', 'Fruits🍎', 60, 'Fresh Farms', 'Crisp and juicy apples', 'apple.jpg'),
(1002, 'Banana', 'Fruits🍎', 50, 'Green Harvest', 'Sweet ripe bananas', 'banana.jpg'),
(1003, 'Mango', 'Fruits🍎', 150, 'Mango Orchard', 'Seasonal Alphonso mangoes.', 'mango.jpg'),
(1004, 'Milk', 'Dairy🥛', 60, 'Dairy Delight', 'Full cream milk.', 'milk.jpg'),
(1005, 'Curd', 'Dairy🥛', 40, 'Dairy Delight', 'Fresh homemade-style curd.', 'curd.jpg'),
(1006, 'Rice', 'Grains🌾', 80, 'Agro Exports', 'Premium basmati rice.', 'rice.jpg'),
(1007, 'Dal', 'Grains🌾', 90, 'Agro Exports', 'High-quality musoor dal.', 'dal.jpg'),
(1008, 'Maggi', 'Snacks🍪', 12, 'Nestle India', 'Instant 2-minute noodles.', 'maggi.jpg'),
(1009, 'Ice Cream', 'Frozen🍦', 200, 'Chill treats', 'Creamy vanilla ice cream.', 'icecream.jpg'),
(1010, 'Potato', 'Vegetables🥦', 30, 'Fresh Farms', 'Fresh organic potatoes', 'potato.jpg'),
(1011, 'Onion', 'Vegetables🥦', 40, 'Green Harvest', 'Red onions of fine quality.', 'onion.jpg'),
(1012, 'Soap', 'Toiletries🧼', 45, 'Dove', 'Gentle moisturizing soap.', 'soap.jpg'),
(1013, 'Shampoo', 'Toiletries🧼', 200, 'Dove', 'Anti-dandruff shampoo.', 'shampoo.jpg'),
(1014, 'Salt', 'Groceries🛍️ ', 20, 'Everyday Essentials', 'Refined iodized salt.', 'salt.jpg'),
(1015, 'Sugar', 'Groceries🛍️ ', 40, 'Agro Exports', 'Pure granulated sugar.', 'sugar.jpg'),
(1016, 'Cookies', 'Snacks🍪', 35, 'Nestle India', 'Freshly baked cookies', 'cookie.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `SaleID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `QuantitySold` int(11) NOT NULL,
  `SaleDate` date NOT NULL,
  `TotalAmount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`SaleID`, `ProductID`, `QuantitySold`, `SaleDate`, `TotalAmount`) VALUES
(301, 1001, 3, '0000-00-00', 360),
(302, 1008, 25, '0000-00-00', 300),
(303, 1004, 20, '0000-00-00', 1200),
(304, 1007, 10, '0000-00-00', 900),
(305, 1012, 5, '0000-00-00', 225);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `Stock ID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `QuantityAvailable` int(11) NOT NULL,
  `ReorderLevel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`Stock ID`, `ProductID`, `QuantityAvailable`, `ReorderLevel`) VALUES
(201, 1001, 50, 10),
(202, 1002, 45, 20),
(203, 1003, 30, 5),
(204, 1004, 200, 50),
(205, 1005, 180, 40),
(206, 1006, 250, 50),
(207, 1007, 150, 30),
(208, 1008, 500, 100),
(209, 1009, 80, 20),
(210, 1010, 400, 100),
(211, 1011, 300, 70),
(212, 1012, 0, 30),
(213, 1013, 100, 25),
(214, 1014, 500, 100),
(215, 1015, 400, 80),
(216, 1016, 22, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`OfferID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`SaleID`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`Stock ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
