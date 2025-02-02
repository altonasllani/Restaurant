-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2025 at 07:13 AM
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
-- Database: `restaurantdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `contactmessages`
--

CREATE TABLE `contactmessages` (
  `MessageID` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Message` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactmessages`
--

INSERT INTO `contactmessages` (`MessageID`, `FullName`, `Email`, `Message`, `CreatedAt`) VALUES
(13, 'albin', 'albinzena2004@gmail.com', 'pershendetje', '2025-02-01 22:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `ImageID` int(11) NOT NULL,
  `ImagePath` varchar(255) NOT NULL,
  `PageSection` enum('index','menu','about') NOT NULL,
  `UploadedBy` int(11) DEFAULT NULL,
  `UploadedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`ImageID`, `ImagePath`, `PageSection`, `UploadedBy`, `UploadedAt`) VALUES
(1, 'uploads/breakfast.jpg', 'menu', NULL, '2025-01-30 01:59:24'),
(2, 'uploads/breakfast.jpg', 'menu', NULL, '2025-01-30 02:01:53'),
(4, 'uploads/breakfast.jpg', 'menu', NULL, '2025-01-30 04:53:12'),
(5, 'uploads/breakfast1.jpg', 'menu', NULL, '2025-01-30 04:54:13'),
(6, 'uploads/breakfast1.jpg', 'menu', NULL, '2025-01-30 04:54:41'),
(7, 'uploads/breakfast2.jpg', 'menu', NULL, '2025-01-30 04:55:29'),
(8, 'uploads/breakfast2.jpg', 'menu', NULL, '2025-01-30 04:56:36'),
(9, 'uploads/breakfast3.jpg', 'menu', NULL, '2025-01-30 04:57:32'),
(10, 'uploads/Lunch1.jpg', 'menu', NULL, '2025-01-30 04:58:42'),
(11, 'uploads/Lunch2.jpg', 'menu', NULL, '2025-01-30 04:59:44'),
(12, 'uploads/lunch3.jpg', 'menu', NULL, '2025-01-30 05:00:37'),
(13, 'uploads/lunch4.jpg', 'menu', NULL, '2025-01-30 05:01:55'),
(14, 'uploads/dinner1.jpg', 'menu', NULL, '2025-01-30 05:02:53'),
(15, 'uploads/dinner2.jpg', 'menu', NULL, '2025-01-30 05:03:37'),
(16, 'uploads/dinner3.jpg', 'menu', NULL, '2025-01-30 05:07:50'),
(17, 'uploads/dinner4.jpg', 'menu', NULL, '2025-01-30 05:09:20'),
(21, 'uploads/drink3.jpg', 'index', NULL, '2025-01-31 16:34:10'),
(22, 'uploads/drink3.jpg', 'index', NULL, '2025-01-31 16:36:40'),
(23, 'uploads/dinner3.jpg', 'index', NULL, '2025-01-31 20:32:04'),
(24, 'uploads/dinner4.jpg', 'index', NULL, '2025-01-31 20:33:28'),
(25, 'uploads/drink1.jpg', 'index', NULL, '2025-01-31 20:34:40'),
(26, 'uploads/drink2.jpg', 'index', NULL, '2025-01-31 20:35:51'),
(28, 'uploads/drink1.jpg', 'index', NULL, '2025-02-02 04:14:08'),
(29, 'uploads/drink2.jpg', 'index', NULL, '2025-02-02 04:14:43'),
(30, 'uploads/drink3.jpg', 'index', NULL, '2025-02-02 04:15:45'),
(31, 'uploads/drink4.jpg', 'index', NULL, '2025-02-02 04:17:01'),
(32, 'uploads/drink5.jpg', 'index', NULL, '2025-02-02 04:18:02'),
(33, 'uploads/drink14.jpg', 'index', NULL, '2025-02-02 04:30:41'),
(34, 'uploads/drink18.jpg', 'index', NULL, '2025-02-02 04:47:35'),
(35, 'uploads/drink15.jpg', 'index', NULL, '2025-02-02 04:48:32'),
(36, 'uploads/drink7.jpg', 'index', NULL, '2025-02-02 04:49:22'),
(37, 'uploads/drink16.jpg', 'index', NULL, '2025-02-02 04:50:07'),
(38, 'uploads/drink9.jpg', 'index', NULL, '2025-02-02 04:52:39'),
(39, 'uploads/drink10.jpg', 'index', NULL, '2025-02-02 04:53:44'),
(40, 'uploads/drink12.jpg', 'index', NULL, '2025-02-02 04:54:53'),
(41, 'uploads/drink6.jpg', 'index', NULL, '2025-02-02 04:55:40'),
(42, 'uploads/drink17.jpg', 'index', NULL, '2025-02-02 05:52:52'),
(43, 'uploads/dessert1.jpg', 'index', NULL, '2025-02-02 05:59:14'),
(44, 'uploads/dessert2.jpeg', 'index', NULL, '2025-02-02 06:01:58'),
(45, 'uploads/dessert3.jpg', 'index', NULL, '2025-02-02 06:02:55'),
(46, 'uploads/dessert4.jpeg', 'index', NULL, '2025-02-02 06:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `MenuItemID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `ImageID` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`MenuItemID`, `Name`, `Description`, `Price`, `Category`, `ImageID`, `CreatedAt`) VALUES
(4, 'The classic Omlette', 'Delicious omlette with fresh ingredients.', 6.30, 'Breakfast', 4, '2025-01-30 04:53:12'),
(6, 'Light Food with Coffee', 'Perfect light meal with coffee', 5.70, 'Breakfast', 6, '2025-01-30 04:54:41'),
(8, 'Best Combination for Breakfast', 'The best way to start your day', 7.00, 'Breakfast', 8, '2025-01-30 04:56:36'),
(9, 'Eggs, Bread and Pancakes', 'Classic breakfast combo', 9.00, 'Breakfast', 9, '2025-01-30 04:57:32'),
(10, 'Homemade Hamburgers', 'Juicy burger with crispy fries', 8.00, 'Lunch', 10, '2025-01-30 04:58:42'),
(11, 'Sandwich with Fries', 'Tasty sandwich served with fries', 7.50, 'Lunch', 11, '2025-01-30 04:59:44'),
(12, 'Chicken Beef with Fries', 'Grilled chicken and beef with fries', 7.50, 'Lunch', 12, '2025-01-30 05:00:37'),
(13, 'French Tacos with Special Sauce', 'Savory French tacos with a unique sauce', 9.50, 'Lunch', 13, '2025-01-30 05:01:55'),
(14, 'Beef Meat With Special Sauce', 'Tender beef served with a rich sauce', 13.00, 'Dinner', 14, '2025-01-30 05:02:53'),
(15, 'Combination of Meat', 'A delicious mix of different meats', 15.00, 'Dinner', 15, '2025-01-30 05:03:37'),
(21, 'Delicious shrimp', 'Delicious shrimp, perfectly cooked.', 14.50, 'Dinner', 23, '2025-01-31 20:32:04'),
(22, 'Chicken Shawarma with Potatoes', 'Chicken Shawarma with crispy potatoes.', 12.00, 'Dinner', 24, '2025-01-31 20:33:28'),
(26, 'Coca Cola', 'Coca Cola 0.25ml', 2.00, 'Carbonated Drinks', 28, '2025-02-02 04:14:08'),
(27, 'Coca Cola zero sugar', 'Coca Cola zero sugar 0.25ml', 2.00, 'Carbonated Drinks', 29, '2025-02-02 04:14:43'),
(28, 'Fanta Orange', 'Fanta Orange 0.25ml', 2.00, 'Carbonated Drinks', 30, '2025-02-02 04:15:45'),
(29, 'Fanta Tropical', 'Fanta Tropical 0.25ml', 2.00, 'Carbonated Drinks', 31, '2025-02-02 04:17:01'),
(31, 'Golden Eagle', 'Golden Eagle 0.25ml', 2.00, 'Energy Drinks', 33, '2025-02-02 04:30:41'),
(32, 'Golden Eagle red edition', 'Golden Eagle red edition 0.25ml', 2.00, 'Energy Drinks', 34, '2025-02-02 04:47:35'),
(33, 'Golden Eagle junior', 'Golden Eagle junior 0.25ml', 2.00, 'Energy Drinks', 35, '2025-02-02 04:48:32'),
(34, 'Orange Soda', 'Orange Soda 0.25ml', 2.00, 'Energy Drinks', 36, '2025-02-02 04:49:22'),
(35, 'Apple Juice', 'Apple Juice 0.25ml', 2.00, 'Fruit Drinks', 37, '2025-02-02 04:50:07'),
(36, 'Strawberry Juice', 'Strawberry Juice 0.25ml', 2.00, 'Fruit Drinks', 38, '2025-02-02 04:52:39'),
(38, 'Peach Juice', 'Peach Juice 0.25ml', 2.00, 'Fruit Drinks', 40, '2025-02-02 04:54:53'),
(40, 'Orange Juice', 'Orange Juice 0.25ml', 2.00, 'Fruit Drinks', 42, '2025-02-02 05:52:52'),
(42, 'Ice Cream Sandwich Cake', 'Ice Cream Sandwich Cake\r\n', 3.50, 'Dessert', 43, '2025-02-02 05:59:14'),
(43, 'Chocolate Layered Sweet Treat', 'Chocolate Layered Sweet Treat\r\n', 4.00, 'Dessert', 44, '2025-02-02 06:01:58'),
(44, 'Chocolate and Berries Yogurt Dessert', 'Chocolate and Berries Yogurt Dessert\r\n', 4.00, 'Dessert', 45, '2025-02-02 06:02:55'),
(45, 'Ice cream desserts', 'Ice cream desserts', 3.50, 'Dessert', 46, '2025-02-02 06:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `ReservationID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `FullName` varchar(100) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `ReservationDate` date NOT NULL,
  `ReservationTime` time NOT NULL,
  `Guests` int(11) NOT NULL CHECK (`Guests` >= 1 and `Guests` <= 20),
  `TablePreference` enum('indoor','outdoor','window','corner') NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`ReservationID`, `UserID`, `FullName`, `PhoneNumber`, `ReservationDate`, `ReservationTime`, `Guests`, `TablePreference`, `CreatedAt`) VALUES
(2, 2, 'albin', '12323252336', '2025-02-11', '07:54:00', 5, 'indoor', '2025-02-02 03:54:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Role` enum('admin','user') DEFAULT 'user',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `Role`, `CreatedAt`) VALUES
(1, 'albin', '$2y$10$y8avPDTNNojoYF.anyuyQeTOU.uBe08LytrMgRUeY39.fyIsHHMwC', 'albinzena@gmail.com', 'admin', '2025-02-02 02:24:39'),
(2, 'albin1', '$2y$10$UGPFFne.Toyf3Ryb3zkr6.SCCE6f4QQ2eE.Jao2ZEifHVcWtD8Dqu', 'albinzena1@gmail.com', 'user', '2025-02-02 03:53:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contactmessages`
--
ALTER TABLE `contactmessages`
  ADD PRIMARY KEY (`MessageID`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`ImageID`),
  ADD KEY `UploadedBy` (`UploadedBy`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`MenuItemID`),
  ADD KEY `ImageID` (`ImageID`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`ReservationID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contactmessages`
--
ALTER TABLE `contactmessages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `ImageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `MenuItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `ReservationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`UploadedBy`) REFERENCES `users` (`UserID`) ON DELETE SET NULL;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`ImageID`) REFERENCES `images` (`ImageID`) ON DELETE SET NULL;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
