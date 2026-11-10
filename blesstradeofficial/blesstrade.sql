--- BlessTrade Township Marketplace Database Schema
-- South Africa Townships C2C Marketplace


START TRANSACTION;
SET time_zone = "+02:00"; -- South Africa Standard Time (SAST)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blesstrade`
--
CREATE DATABASE IF NOT EXISTS `blesstrade` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `blesstrade`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `role` enum('buyer','seller','admin') NOT NULL DEFAULT 'buyer',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `phone`, `location`, `role`) VALUES
(1, 'Supervisor Admin', 'admin@blesstrade.co.za', '$2y$10$tZ2X1uVCOxsz1pM2gV37fe7i9S9/6vQp7oWh4fNTe9b4O8D3SkeEa', '+27 81 234 5678', 'Johannesburg, GP', 'admin'),
(2, 'Thabo Nkosi', 'thabo@blesstrade.co.za', '$2y$10$w3/7v3VpXREqQ3fW8xH.Ue.k9mshvAnjH9z9oBeY0Sje7Z7Qvj8f.', '+27 82 999 1122', 'Soweto, Johannesburg', 'seller'),
(3, 'Lindiwe Dlamini', 'lindiwe@blesstrade.co.za', '$2y$10$w3/7v3VpXREqQ3fW8xH.Ue.k9mshvAnjH9z9oBeY0Sje7Z7Qvj8f.', '+27 81 000 1111', 'Orlando, Soweto', 'buyer'),
(4, 'Sipho Zuma', 'sipho@blesstrade.co.za', '$2y$10$w3/7v3VpXREqQ3fW8xH.Ue.k9mshvAnjH9z9oBeY0Sje7Z7Qvj8f.', '+27 83 222 3333', 'Alexandra, Johannesburg', 'seller'),
(5, 'Nomsa Khumalo', 'nomsa@blesstrade.co.za', '$2y$10$w3/7v3VpXREqQ3fW8xH.Ue.k9mshvAnjH9z9oBeY0Sje7Z7Qvj8f.', '+27 84 555 6677', 'Tembisa, GP', 'buyer');

-- Note: Password for 'admin' is 'admin' (hashed via bcrypt)
-- Note: Password for other accounts is 'password123' (hashed via bcrypt)

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`) VALUES
(1, 'Appliances & Home', 'home'),
(2, 'Phones & Electronics', 'smartphone'),
(3, 'Fashion & Apparel', 'shirt'),
(4, 'Automotive & Spares', 'car'),
(5, 'Furniture', 'sofa'),
(6, 'Other Goods', 'package');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_condition` enum('new','used') NOT NULL DEFAULT 'used',
  `seller_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('available','sold') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `price`, `category_id`, `item_condition`, `seller_id`, `image`, `status`) VALUES
(1, 'Defy Fridge Freezer Combo', 'Double-door fridge with pristine refrigeration compartment, clean metallic finish, and energy-saving setup. Used for 1 year in Soweto. Self-pickup needed.', 2450.00, 1, 'used', 2, 'assets/images/fridge_combo.jpg', 'available'),
(2, 'Samsung Galaxy S21 Smart Phone', '64GB storage, unlocked, working excellently. Comes with charger block and clear protection casing. Minor screen scratch at bottom frame.', 3800.00, 2, 'used', 4, 'assets/images/samsung_galaxy.jpg', 'available'),
(3, 'Genuine Leather Men Jacket', 'Size Large, solid protective stitching, dark brown color. Windproof and elegant for chilly South African township winters.', 850.00, 3, 'used', 2, 'assets/images/leather_jacket.jpg', 'available'),
(4, 'Defy Electric Microwave 20 Litres', 'Compact digital model with auto defrost options. Runs at 700 watts. Perfectly clean interiors and boxed controls.', 1200.00, 1, 'new', 4, 'assets/images/defy_microwave.jpg', 'available'),
(5, '4-Plate Electric Stove & Oven', 'Standing stove unit with swift warning heating cycles. Used, fully working oven burners. Moving sale.', 1800.00, 1, 'used', 2, 'assets/images/stove_combo.jpg', 'sold');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `buyer_phone` varchar(30) NOT NULL,
  `buyer_location` varchar(100) NOT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_id`, `buyer_id`, `seller_id`, `amount`, `buyer_phone`, `buyer_location`, `status`) VALUES
(1, 5, 3, 2, 1800.00, '+27 81 000 1111', 'Orlando, Soweto', 'active');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
