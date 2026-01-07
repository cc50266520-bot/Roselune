-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 06:23 PM
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
-- Database: `roselune`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `variant_id`, `qty`, `added_at`) VALUES
(26, 2, 2, NULL, 1, '2026-01-06 07:18:28');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `payment_method` varchar(30) NOT NULL DEFAULT 'cash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`, `address`, `phone`, `payment_method`) VALUES
(1, 2, 58.00, 'pending', '2025-12-23 09:23:57', NULL, NULL, 'cash'),
(2, 2, 58.00, 'pending', '2025-12-23 09:26:30', NULL, NULL, 'cash'),
(3, 2, 104.00, 'pending', '2025-12-23 12:31:47', NULL, NULL, 'cash'),
(4, 2, 58.00, 'pending', '2025-12-24 09:38:33', NULL, NULL, 'cash'),
(5, 2, 43.50, 'pending', '2025-12-24 10:01:53', NULL, NULL, 'cash'),
(6, 2, 24.99, 'pending', '2025-12-24 10:40:50', NULL, NULL, 'cash'),
(7, 2, 26.00, 'pending', '2025-12-24 10:43:50', 'lebanon', '81218409', 'card'),
(8, 2, 69.50, 'pending', '2025-12-24 10:48:22', 'lebanon', '81218409', 'cash'),
(9, 2, 14.50, 'pending', '2025-12-29 07:44:06', 'lebanon', '81218409', 'cash'),
(10, 2, 14.50, 'pending', '2025-12-29 07:44:48', 'tripoli _ qalamoun', '70676998', 'card'),
(11, 3, 299.00, 'pending', '2025-12-29 08:01:59', 'lebanon', '81218409', 'cash'),
(12, 2, 115.00, 'pending', '2025-12-30 17:28:23', 'WAKRA', '81218409', 'cash'),
(13, 2, 273.00, 'pending', '2026-01-05 05:31:23', 'tripoli _ qalamoun', '81218409', 'cash'),
(14, 5, 66.00, 'pending', '2026-01-05 05:50:00', 'qalamoun/614', '81518409', 'cash'),
(15, 2, 165.00, 'pending', '2026-01-05 12:35:30', 'TYRE', '8121814516', 'cash');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `price` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`, `price`) VALUES
(1, 1, 3, 4, 14.50),
(2, 2, 3, 4, 14.50),
(3, 3, 2, 4, 26.00),
(4, 4, 3, 4, 14.50),
(5, 5, 3, 3, 14.50),
(6, 6, 1, 1, 24.99),
(7, 7, 2, 1, 26.00),
(8, 8, 2, 1, 26.00),
(9, 8, 3, 3, 14.50),
(10, 9, 3, 1, 14.50),
(11, 10, 3, 1, 14.50),
(12, 11, NULL, 13, 23.00),
(13, 12, NULL, 5, 23.00),
(14, 13, 14, 5, 48.00),
(15, 13, 18, 1, 33.00),
(16, 14, 16, 2, 33.00),
(17, 15, 18, 5, 33.00);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`, `used`) VALUES
(1, 3, '99210116dc3b806abdfc6045287954f080e68f455f47cebc143c25ecc5d42167', '2025-12-29 09:01:44', 0),
(2, 4, 'a2099fa357056bb3fb81ba7908c9d3e2823d79aba82099b0947d0ed65eb78e67', '2025-12-29 09:03:04', 0),
(3, 4, '6df29b701dd1c2817cd7a5389209fb639ecb26d08e893753474eddf879ae0b04', '2025-12-29 09:03:35', 0),
(4, 4, 'a074f0fb4dfdcd61103ab77621ece999cc0ab42c1e6fe93442eb5a552b4c01ec', '2025-12-29 09:03:44', 0),
(5, 3, '857e50ca0613f006eb3196cb8432d7d21b3feb61c28ee751638213777155f16a', '2026-01-04 07:53:13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `shade` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(50) NOT NULL DEFAULT 'universal',
  `skin_tone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `shade`, `created_at`, `category`, `skin_tone`) VALUES
(1, 'Roselune Radiant Foundation - Light Special', 'Light coverage foundation for fair skin', 24.99, 'assets/uploads/prod_1767530505_9300.jpeg', 'light', '2025-11-26 10:01:05', 'foundation', 'light'),
(2, 'Roselune Matte Foundation - Medium', 'Medium coverage foundation', 26.00, 'assets/uploads/prod_1767530555_5742.jpeg', 'medium', '2025-11-26 10:01:05', 'foundation', 'medium'),
(3, 'Roselune Velvet Blush - Coral', 'Warm coral blush', 14.50, 'assets/uploads/prod_1767530622_2673.jpeg', 'universal', '2025-11-26 10:01:05', 'blush', 'universal'),
(8, 'Dior Lipstick', 'Color 373 Rose Celestial - pink', 15.00, 'assets/uploads/prod_1767513687_6363.jpeg', NULL, '2026-01-04 08:01:27', 'universal', NULL),
(9, 'Dior Lipstick', 'Color 730 Star - a copper red', 15.00, 'assets/uploads/prod_1767513715_5615.jpeg', NULL, '2026-01-04 08:01:55', 'universal', NULL),
(10, 'Dior Lipstick', 'Color 628 Pink Bow - plum', 15.00, 'assets/uploads/prod_1767513735_4467.jpeg', NULL, '2026-01-04 08:02:15', 'universal', NULL),
(11, 'Dior Blush', 'Color 015 Cherry - a cherry red', 48.00, 'assets/uploads/prod_1767513772_2200.jpeg', NULL, '2026-01-04 08:02:52', 'universal', NULL),
(12, 'Blush', 'Color 012 Rosewood - 012 Rosewood', 48.00, 'assets/uploads/prod_1767513823_5938.jpeg', NULL, '2026-01-04 08:03:43', 'universal', NULL),
(13, 'Blush', 'Color 006 Berry - a deep plum', 48.00, 'assets/uploads/prod_1767513850_8160.jpeg', NULL, '2026-01-04 08:04:00', 'universal', NULL),
(14, 'Blush', 'Color 001 Pink - a subtle pink', 48.00, 'assets/uploads/prod_1767513878_2505.jpeg', NULL, '2026-01-04 08:04:38', 'universal', NULL),
(15, 'Femty Beauty  Concealer', 'Color Medium 330W - warm golden undertones', 33.00, 'assets/uploads/prod_1767513943_9725.jpeg', NULL, '2026-01-04 08:05:43', 'universal', NULL),
(16, 'Femty Beauty  Concealer', 'Color Medium 335W - warm peach undertones', 33.00, 'assets/uploads/prod_1767513996_9473.jpeg', NULL, '2026-01-04 08:06:30', 'universal', NULL),
(17, 'Femty Beauty  Concealer', 'Color Light Medium 230W - warm golden undertones', 33.00, 'assets/uploads/prod_1767514075_7145.jpeg', NULL, '2026-01-04 08:07:55', 'universal', NULL),
(18, 'Femty Beauty Concealer', 'Color Light 150N - neutral undertones', 33.00, 'assets/uploads/prod_1767514134_5358.jpeg', NULL, '2026-01-04 08:08:19', 'universal', NULL),
(20, 'Rosélune Deep Glow Foundation', 'Full coverage foundation specially formulated for deep and dark skin tones', 28.00, 'assets/uploads/prod_1767702501_8429.jpg', NULL, '2026-01-06 12:26:03', 'foundation', 'dark');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_name` varchar(100) NOT NULL,
  `variant_value` varchar(100) NOT NULL,
  `extra_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_admin`, `created_at`) VALUES
(1, 'ad', 'ad@gmail.com', '$2y$10$76xIn2mvDm49HUsXCbK32OSqaDbyPOc9xUARw7B5PEKbIa6Ef.Naa', 1, '2025-11-26 11:26:26'),
(2, 'CHARBEL', 'CHARBEL@GMAIL.COM', '$2y$10$ASksxCNTDf.Fu.rZBqXxtu4riw4.4ujRZybgBxq7rACb0SQ5NhcH2', 1, '2025-12-23 07:17:37'),
(3, 'Hasnaa Chakik', 'Hasnaa@gmail.com', '$2y$10$Fgg7OOeaoik/uWaLSmJrY.B2r52QJO8aaIszHb/kUkQAerfbLSx46', 1, '2025-12-29 07:46:12'),
(4, 'CHARBEL', 'c.alchami@imar.com', '$2y$10$mCqIb0xe5edfX/90MnIIVOLRnK2ffypUokgS/K6EZ106pnWeuPNRm', 1, '2025-12-29 07:47:50'),
(5, 'hasnaa', 'chakik@gmail.com', '$2y$10$mEabSU9ny4WY3MpVUBaxqeXXcF1Ze3e.Zz9w9fkE4TPKjZRU.QWEm', 1, '2026-01-04 06:35:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
