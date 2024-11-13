-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 13, 2024 at 02:05 PM
-- Server version: 8.0.40-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blacklist`
--

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `website_id` int DEFAULT NULL,
  `action` enum('add','remove') DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `user_id`, `website_id`, `action`, `status`, `created_at`) VALUES
(11, 5, 4, 'add', 'approved', '2024-11-12 20:44:22'),
(12, 5, 5, 'add', 'approved', '2024-11-12 20:50:26'),
(13, 5, 6, 'add', 'rejected', '2024-11-12 20:50:28'),
(14, 5, 5, 'remove', 'rejected', '2024-11-12 20:54:15'),
(15, 5, 5, 'remove', 'approved', '2024-11-12 20:54:51'),
(16, 1, 6, 'add', 'approved', '2024-11-12 20:59:04'),
(17, 1, 4, 'add', 'approved', '2024-11-12 21:06:55'),
(18, 1, 5, 'add', 'rejected', '2024-11-12 21:12:03'),
(19, 1, 6, 'remove', 'approved', '2024-11-12 21:13:17'),
(20, 1, 6, 'add', 'approved', '2024-11-13 09:46:20'),
(21, 1, 5, 'add', 'approved', '2024-11-13 09:53:08'),
(22, 5, 7, 'add', 'approved', '2024-11-13 10:16:05'),
(23, 5, 8, 'add', 'approved', '2024-11-13 10:16:13'),
(24, 5, 9, 'add', 'approved', '2024-11-13 10:16:20'),
(25, 5, 10, 'add', 'approved', '2024-11-13 10:17:12'),
(26, 1, 11, 'add', 'approved', '2024-11-13 10:20:29'),
(27, 1, 12, 'add', 'approved', '2024-11-13 10:21:25'),
(28, 1, 13, 'add', 'approved', '2024-11-13 10:21:37'),
(29, 1, 14, 'add', 'approved', '2024-11-13 10:22:41'),
(30, 1, 15, 'add', 'approved', '2024-11-13 10:23:00'),
(31, 1, 7, 'remove', 'approved', '2024-11-13 12:05:50'),
(32, 1, 16, 'add', 'pending', '2024-11-13 13:17:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'admin', '$2y$10$oN7PdmeXA/0PgyzOP2psKOo7vt1Skb3ZAV61xmrGyxWe8TyZHJbCe', 'a@gmail.com', 'admin'),
(5, 'kristupas', '$2y$10$rSzeSLbwpii91LtbIHrySef8TxsMvZom.eVx/c4w03PkzaWVFMs4m', 'kondrataviciuskristupas@gmail.com', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `websites`
--

CREATE TABLE `websites` (
  `id` int NOT NULL,
  `url` varchar(255) NOT NULL,
  `is_blacklisted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `websites`
--

INSERT INTO `websites` (`id`, `url`, `is_blacklisted`) VALUES
(4, 'https://www.gogo.com/', 1),
(5, 'https://www.google.com/', 1),
(6, 'https://www.youtube.com/', 1),
(7, 'https://www.bing.com/', 0),
(8, 'https://www.trains.com/', 1),
(9, 'https://www.woods.com/', 1),
(10, 'https://darkwood.fandom.com/wiki/Darkwood', 1),
(11, 'https://color.adobe.com/create/color-wheel', 1),
(12, 'https://uais.cr.ktu.lt/', 1),
(13, 'https://mail.google.com/', 1),
(14, 'https://store.huion.com/', 1),
(15, 'https://getcomposer.org/', 1),
(16, 'https://www.foobar.com/', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `website_id` (`website_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `websites`
--
ALTER TABLE `websites`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `websites`
--
ALTER TABLE `websites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`website_id`) REFERENCES `websites` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
