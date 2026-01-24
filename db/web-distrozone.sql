-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for distrozone
CREATE DATABASE IF NOT EXISTS `distrozone` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `distrozone`;

-- Dumping structure for table distrozone.addresses
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Rumah',
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.addresses: ~0 rows (approximately)
DELETE FROM `addresses`;

-- Dumping structure for table distrozone.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table distrozone.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table distrozone.cart_items
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_variant_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_items_user_id_product_id_unique` (`user_id`,`product_variant_id`),
  KEY `cart_items_product_id_foreign` (`product_variant_id`),
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.cart_items: ~0 rows (approximately)
DELETE FROM `cart_items`;

-- Dumping structure for table distrozone.employees
CREATE TABLE IF NOT EXISTS `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_employee_id_unique` (`employee_id`),
  UNIQUE KEY `employees_nik_unique` (`nik`),
  KEY `employees_user_id_foreign` (`user_id`),
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.employees: ~2 rows (approximately)
DELETE FROM `employees`;
INSERT INTO `employees` (`id`, `employee_id`, `user_id`, `nik`, `name`, `address`, `phone`, `photo`, `created_at`, `updated_at`) VALUES
	(1, 'EMP-20260124-001', 2, '3175012345670001', 'Budi Santoso', 'Jakarta', '081234567891', NULL, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(2, 'EMP-20260124-002', 3, '3175012345670002', 'Siti Rahayu', 'Jakarta', '081234567892', NULL, '2026-01-23 19:32:48', '2026-01-23 19:32:48');

-- Dumping structure for table distrozone.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table distrozone.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table distrozone.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table distrozone.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.migrations: ~0 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2024_01_01_000002_create_employees_table', 1),
	(5, '2024_01_01_000003_create_products_table', 1),
	(6, '2024_01_01_000004_create_transactions_table', 1),
	(7, '2024_01_01_000005_create_transaction_details_table', 1),
	(8, '2024_01_01_000006_create_shipping_rates_table', 1),
	(9, '2024_01_01_000007_create_store_settings_table', 1),
	(10, '2026_01_15_120303_create_personal_access_tokens_table', 1),
	(11, '2026_01_15_162612_create_wishlists_table', 1),
	(12, '2026_01_15_162613_create_cart_items_table', 1),
	(13, '2026_01_16_055630_create_addresses_table', 1),
	(14, '2026_01_16_173708_add_notification_preferences_to_users_table', 1),
	(15, '2026_01_23_171239_restructure_products_table', 1),
	(16, '2026_01_23_171241_create_product_variants_table', 1);

-- Dumping structure for table distrozone.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table distrozone.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table distrozone.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('lengan pendek','lengan panjang') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `base_price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_brand_index` (`brand`),
  KEY `products_type_index` (`type`),
  KEY `products_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.products: ~14 rows (approximately)
DELETE FROM `products`;
INSERT INTO `products` (`id`, `name`, `slug`, `brand`, `type`, `description`, `base_price`, `photo`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Nike Sportswear Essential', 'nike-sportswear-essential', 'Nike', 'lengan pendek', 'Classic Nike tee with embroidered swoosh. Premium cotton blend for ultimate comfort.', 275000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(2, 'Nike Dri-FIT Performance', 'nike-dri-fit-performance', 'Nike', 'lengan pendek', 'Moisture-wicking fabric keeps you dry and comfortable during workouts.', 320000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(3, 'Nike Tech Fleece Hoodie', 'nike-tech-fleece-hoodie', 'Nike', 'lengan panjang', 'Premium fleece with innovative design for warmth without weight.', 890000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(4, 'Adidas Originals Trefoil Tee', 'adidas-originals-trefoil-tee', 'Adidas', 'lengan pendek', 'Iconic trefoil logo on soft cotton. Streetwear essential.', 295000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(5, 'Adidas Performance Training Tee', 'adidas-performance-training-tee', 'Adidas', 'lengan pendek', 'Breathable athletic tee with moisture management.', 310000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(6, 'Adidas Essentials Hoodie', 'adidas-essentials-hoodie', 'Adidas', 'lengan panjang', 'Comfortable cotton-blend hoodie with kangaroo pocket.', 750000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(7, 'Uniqlo AIRism Cotton Tee', 'uniqlo-airism-cotton-tee', 'Uniqlo', 'lengan pendek', 'Innovative AIRism technology for smooth, cool comfort.', 149000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(8, 'Uniqlo Graphic Streetwear Tee', 'uniqlo-graphic-streetwear-tee', 'Uniqlo', 'lengan pendek', 'Bold graphic print on premium cotton. Limited edition design.', 179000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(9, 'Uniqlo Oversized Tee', 'uniqlo-oversized-tee', 'Uniqlo', 'lengan pendek', 'Relaxed oversized fit. Perfect for layering.', 159000.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(10, 'H&M Regular Fit Cotton Tee', 'hm-regular-fit-cotton-tee', 'H&M', 'lengan pendek', 'Basic crew neck tee in soft cotton jersey.', 129000.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(11, 'H&M Printed Tee', 'hm-printed-tee', 'H&M', 'lengan pendek', 'Trendy print design. Regular fit with ribbed crew neck.', 149000.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(12, 'H&M Relaxed Fit Hoodie', 'hm-relaxed-fit-hoodie', 'H&M', 'lengan panjang', 'Soft cotton blend with hood and kangaroo pocket.', 399000.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(13, 'Nike x OFF-WHITE Collaboration Tee', 'nike-x-off-white-collaboration-tee', 'Nike', 'lengan pendek', 'Limited edition collaboration. Collectors item with unique design.', 1250000.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(14, 'Adidas Yeezy Essentials Tee', 'adidas-yeezy-essentials-tee', 'Adidas', 'lengan pendek', 'Yeezy line premium oversized tee with unique colorway.', 980000.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49');

-- Dumping structure for table distrozone.product_variants
CREATE TABLE IF NOT EXISTS `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_product_id_color_size_unique` (`product_id`,`color`,`size`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_index` (`product_id`),
  KEY `product_variants_sku_index` (`sku`),
  KEY `product_variants_color_index` (`color`),
  KEY `product_variants_size_index` (`size`),
  KEY `product_variants_stock_index` (`stock`),
  KEY `product_variants_is_active_index` (`is_active`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.product_variants: ~117 rows (approximately)
DELETE FROM `product_variants`;
INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `color`, `size`, `stock`, `price`, `cost_price`, `photo`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'PRD-001-BLA-S', 'Black', 'S', 15, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(2, 1, 'PRD-001-BLA-M', 'Black', 'M', 25, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(3, 1, 'PRD-001-BLA-L', 'Black', 'L', 30, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(4, 1, 'PRD-001-BLA-XL', 'Black', 'XL', 20, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(5, 1, 'PRD-001-WHI-S', 'White', 'S', 20, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(6, 1, 'PRD-001-WHI-M', 'White', 'M', 30, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(7, 1, 'PRD-001-WHI-L', 'White', 'L', 25, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(8, 1, 'PRD-001-WHI-XL', 'White', 'XL', 15, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(9, 1, 'PRD-001-GRE-M', 'Grey', 'M', 15, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(10, 1, 'PRD-001-GRE-L', 'Grey', 'L', 20, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(11, 1, 'PRD-001-GRE-XL', 'Grey', 'XL', 18, 275000.00, 178750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(12, 2, 'PRD-002-WHI-M', 'White', 'M', 12, 320000.00, 208000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(13, 2, 'PRD-002-WHI-L', 'White', 'L', 15, 320000.00, 208000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(14, 2, 'PRD-002-WHI-XL', 'White', 'XL', 10, 320000.00, 208000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(15, 2, 'PRD-002-NAV-M', 'Navy', 'M', 10, 320000.00, 208000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(16, 2, 'PRD-002-NAV-L', 'Navy', 'L', 12, 320000.00, 208000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(17, 2, 'PRD-002-NAV-XL', 'Navy', 'XL', 8, 320000.00, 208000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(18, 2, 'PRD-002-RED-M', 'Red', 'M', 8, 320000.00, 208000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(19, 2, 'PRD-002-RED-L', 'Red', 'L', 10, 320000.00, 208000.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(20, 3, 'PRD-003-GRE-M', 'Grey', 'M', 8, 890000.00, 578500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(21, 3, 'PRD-003-GRE-L', 'Grey', 'L', 10, 890000.00, 578500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(22, 3, 'PRD-003-GRE-XL', 'Grey', 'XL', 12, 890000.00, 578500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(23, 3, 'PRD-003-GRE-2XL', 'Grey', '2XL', 5, 890000.00, 578500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(24, 3, 'PRD-003-BLA-M', 'Black', 'M', 10, 890000.00, 578500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(25, 3, 'PRD-003-BLA-L', 'Black', 'L', 12, 890000.00, 578500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(26, 3, 'PRD-003-BLA-XL', 'Black', 'XL', 8, 890000.00, 578500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(27, 4, 'PRD-004-BLA-S', 'Black', 'S', 20, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(28, 4, 'PRD-004-BLA-M', 'Black', 'M', 25, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(29, 4, 'PRD-004-BLA-L', 'Black', 'L', 30, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(30, 4, 'PRD-004-BLA-XL', 'Black', 'XL', 15, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(31, 4, 'PRD-004-WHI-S', 'White', 'S', 18, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(32, 4, 'PRD-004-WHI-M', 'White', 'M', 22, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(33, 4, 'PRD-004-WHI-L', 'White', 'L', 20, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(34, 4, 'PRD-004-WHI-XL', 'White', 'XL', 12, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(35, 4, 'PRD-004-NAV-M', 'Navy', 'M', 15, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(36, 4, 'PRD-004-NAV-L', 'Navy', 'L', 18, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(37, 4, 'PRD-004-NAV-XL', 'Navy', 'XL', 10, 295000.00, 191750.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(38, 5, 'PRD-005-RED-M', 'Red', 'M', 12, 310000.00, 201500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(39, 5, 'PRD-005-RED-L', 'Red', 'L', 15, 310000.00, 201500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(40, 5, 'PRD-005-RED-XL', 'Red', 'XL', 10, 310000.00, 201500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(41, 5, 'PRD-005-BLU-M', 'Blue', 'M', 10, 310000.00, 201500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(42, 5, 'PRD-005-BLU-L', 'Blue', 'L', 12, 310000.00, 201500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(43, 5, 'PRD-005-BLU-XL', 'Blue', 'XL', 8, 310000.00, 201500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(44, 5, 'PRD-005-BLA-M', 'Black', 'M', 15, 310000.00, 201500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(45, 5, 'PRD-005-BLA-L', 'Black', 'L', 12, 310000.00, 201500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(46, 6, 'PRD-006-BLA-M', 'Black', 'M', 10, 750000.00, 487500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(47, 6, 'PRD-006-BLA-L', 'Black', 'L', 12, 750000.00, 487500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(48, 6, 'PRD-006-BLA-XL', 'Black', 'XL', 8, 750000.00, 487500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(49, 6, 'PRD-006-GRE-M', 'Grey', 'M', 12, 750000.00, 487500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(50, 6, 'PRD-006-GRE-L', 'Grey', 'L', 10, 750000.00, 487500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(51, 6, 'PRD-006-GRE-XL', 'Grey', 'XL', 6, 750000.00, 487500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(52, 6, 'PRD-006-NAV-L', 'Navy', 'L', 8, 750000.00, 487500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(53, 6, 'PRD-006-NAV-XL', 'Navy', 'XL', 5, 750000.00, 487500.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(54, 7, 'PRD-007-WHI-S', 'White', 'S', 30, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(55, 7, 'PRD-007-WHI-M', 'White', 'M', 40, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(56, 7, 'PRD-007-WHI-L', 'White', 'L', 35, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(57, 7, 'PRD-007-WHI-XL', 'White', 'XL', 25, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(58, 7, 'PRD-007-BLA-S', 'Black', 'S', 28, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(59, 7, 'PRD-007-BLA-M', 'Black', 'M', 38, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(60, 7, 'PRD-007-BLA-L', 'Black', 'L', 32, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(61, 7, 'PRD-007-BLA-XL', 'Black', 'XL', 22, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(62, 7, 'PRD-007-GRE-M', 'Grey', 'M', 25, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(63, 7, 'PRD-007-GRE-L', 'Grey', 'L', 30, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(64, 7, 'PRD-007-GRE-XL', 'Grey', 'XL', 20, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(65, 7, 'PRD-007-NAV-M', 'Navy', 'M', 20, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(66, 7, 'PRD-007-NAV-L', 'Navy', 'L', 25, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(67, 7, 'PRD-007-NAV-XL', 'Navy', 'XL', 15, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(68, 8, 'PRD-008-BLA-M', 'Black', 'M', 18, 179000.00, 116350.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(69, 8, 'PRD-008-BLA-L', 'Black', 'L', 22, 179000.00, 116350.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(70, 8, 'PRD-008-BLA-XL', 'Black', 'XL', 15, 179000.00, 116350.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(71, 8, 'PRD-008-WHI-M', 'White', 'M', 20, 179000.00, 116350.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(72, 8, 'PRD-008-WHI-L', 'White', 'L', 25, 179000.00, 116350.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(73, 8, 'PRD-008-WHI-XL', 'White', 'XL', 18, 179000.00, 116350.00, NULL, 1, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(74, 9, 'PRD-009-GRE-L', 'Grey', 'L', 15, 159000.00, 103350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(75, 9, 'PRD-009-GRE-XL', 'Grey', 'XL', 20, 159000.00, 103350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(76, 9, 'PRD-009-GRE-2XL', 'Grey', '2XL', 12, 159000.00, 103350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(77, 9, 'PRD-009-BEI-L', 'Beige', 'L', 12, 159000.00, 103350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(78, 9, 'PRD-009-BEI-XL', 'Beige', 'XL', 18, 159000.00, 103350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(79, 9, 'PRD-009-BEI-2XL', 'Beige', '2XL', 10, 159000.00, 103350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(80, 9, 'PRD-009-BLA-L', 'Black', 'L', 18, 159000.00, 103350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(81, 9, 'PRD-009-BLA-XL', 'Black', 'XL', 15, 159000.00, 103350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(82, 10, 'PRD-010-WHI-S', 'White', 'S', 40, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(83, 10, 'PRD-010-WHI-M', 'White', 'M', 50, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(84, 10, 'PRD-010-WHI-L', 'White', 'L', 45, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(85, 10, 'PRD-010-WHI-XL', 'White', 'XL', 30, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(86, 10, 'PRD-010-BLA-S', 'Black', 'S', 38, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(87, 10, 'PRD-010-BLA-M', 'Black', 'M', 48, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(88, 10, 'PRD-010-BLA-L', 'Black', 'L', 42, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(89, 10, 'PRD-010-BLA-XL', 'Black', 'XL', 28, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(90, 10, 'PRD-010-GRE-M', 'Grey', 'M', 30, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(91, 10, 'PRD-010-GRE-L', 'Grey', 'L', 35, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(92, 10, 'PRD-010-GRE-XL', 'Grey', 'XL', 25, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(93, 10, 'PRD-010-NAV-M', 'Navy', 'M', 25, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(94, 10, 'PRD-010-NAV-L', 'Navy', 'L', 30, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(95, 10, 'PRD-010-NAV-XL', 'Navy', 'XL', 20, 129000.00, 83850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(96, 11, 'PRD-011-BLA-M', 'Black', 'M', 20, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(97, 11, 'PRD-011-BLA-L', 'Black', 'L', 25, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(98, 11, 'PRD-011-BLA-XL', 'Black', 'XL', 15, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(99, 11, 'PRD-011-WHI-M', 'White', 'M', 18, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(100, 11, 'PRD-011-WHI-L', 'White', 'L', 22, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(101, 11, 'PRD-011-WHI-XL', 'White', 'XL', 12, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(102, 11, 'PRD-011-RED-M', 'Red', 'M', 15, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(103, 11, 'PRD-011-RED-L', 'Red', 'L', 18, 149000.00, 96850.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(104, 12, 'PRD-012-GRE-M', 'Grey', 'M', 15, 399000.00, 259350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(105, 12, 'PRD-012-GRE-L', 'Grey', 'L', 18, 399000.00, 259350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(106, 12, 'PRD-012-GRE-XL', 'Grey', 'XL', 10, 399000.00, 259350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(107, 12, 'PRD-012-BLA-M', 'Black', 'M', 12, 399000.00, 259350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(108, 12, 'PRD-012-BLA-L', 'Black', 'L', 15, 399000.00, 259350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(109, 12, 'PRD-012-BLA-XL', 'Black', 'XL', 8, 399000.00, 259350.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(110, 13, 'PRD-013-WHI-M', 'White', 'M', 3, 1250000.00, 812500.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(111, 13, 'PRD-013-WHI-L', 'White', 'L', 2, 1250000.00, 812500.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(112, 13, 'PRD-013-BLA-M', 'Black', 'M', 2, 1250000.00, 812500.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(113, 13, 'PRD-013-BLA-L', 'Black', 'L', 3, 1250000.00, 812500.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(114, 14, 'PRD-014-BEI-L', 'Beige', 'L', 5, 980000.00, 637000.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(115, 14, 'PRD-014-BEI-XL', 'Beige', 'XL', 3, 980000.00, 637000.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(116, 14, 'PRD-014-BLA-L', 'Black', 'L', 4, 980000.00, 637000.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49'),
	(117, 14, 'PRD-014-BLA-XL', 'Black', 'XL', 4, 980000.00, 637000.00, NULL, 1, '2026-01-23 19:32:49', '2026-01-23 19:32:49');

-- Dumping structure for table distrozone.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.sessions: ~1 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('L7lWVQjO8stPbV7Rub6WD2llyJiWrkVkii44gqgb', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRFdYMUp6YjZOUm1idjNMano1S0taemN4ODFYaEdiMXdUeUFPMXpwbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cy81IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1769222008);

-- Dumping structure for table distrozone.shipping_rates
CREATE TABLE IF NOT EXISTS `shipping_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate_per_kg` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shipping_rates_destination_unique` (`destination`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.shipping_rates: ~8 rows (approximately)
DELETE FROM `shipping_rates`;
INSERT INTO `shipping_rates` (`id`, `destination`, `rate_per_kg`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Jakarta', 24000.00, 1, '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(2, 'Depok', 24000.00, 1, '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(3, 'Bekasi', 25000.00, 1, '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(4, 'Tangerang', 25000.00, 1, '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(5, 'Bogor', 27000.00, 1, '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(6, 'Jawa Barat', 31000.00, 1, '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(7, 'Jawa Tengah', 39000.00, 1, '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(8, 'Jawa Timur', 47000.00, 1, '2026-01-23 19:32:47', '2026-01-23 19:32:47');

-- Dumping structure for table distrozone.store_settings
CREATE TABLE IF NOT EXISTS `store_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.store_settings: ~9 rows (approximately)
DELETE FROM `store_settings`;
INSERT INTO `store_settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'offline_open_time', '10:00', 'time', 'Jam buka toko offline', '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(2, 'offline_close_time', '20:00', 'time', 'Jam tutup toko offline', '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(3, 'offline_closed_day', 'Monday', 'text', 'Hari libur toko offline (bahasa Inggris)', '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(4, 'online_open_time', '10:00', 'time', 'Jam buka toko online', '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(5, 'online_close_time', '17:00', 'time', 'Jam tutup toko online', '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(6, 'store_name', 'DistroZone', 'text', 'Nama toko', '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(7, 'store_address', 'Jln. Raya Pegangsaan Timur No.29H, Kelapa Gading, Jakarta', 'text', 'Alamat toko', '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(8, 'store_description', 'Menjual berbagai macam kaos distro dengan variasi model, warna, dan ukuran', 'text', 'Deskripsi toko', '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(9, 'max_shirts_per_kg', '3', 'integer', 'Maksimal kaos per kilogram untuk perhitungan ongkir', '2026-01-23 19:32:47', '2026-01-23 19:32:47');

-- Dumping structure for table distrozone.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `cashier_id` bigint unsigned DEFAULT NULL,
  `transaction_type` enum('offline','online') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'offline',
  `payment_method` enum('tunai','qris','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('pending','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `shipping_destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight_kg` int DEFAULT NULL,
  `order_status` enum('pending','processing','shipped','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_transaction_code_unique` (`transaction_code`),
  KEY `transactions_user_id_foreign` (`user_id`),
  KEY `transactions_cashier_id_foreign` (`cashier_id`),
  KEY `transactions_verified_by_foreign` (`verified_by`),
  KEY `transactions_transaction_code_index` (`transaction_code`),
  KEY `transactions_transaction_type_index` (`transaction_type`),
  KEY `transactions_order_status_index` (`order_status`),
  KEY `transactions_created_at_index` (`created_at`),
  CONSTRAINT `transactions_cashier_id_foreign` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.transactions: ~0 rows (approximately)
DELETE FROM `transactions`;

-- Dumping structure for table distrozone.transaction_details
CREATE TABLE IF NOT EXISTS `transaction_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `product_variant_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_details_product_id_foreign` (`product_variant_id`),
  KEY `transaction_details_transaction_id_index` (`transaction_id`),
  CONSTRAINT `transaction_details_product_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.transaction_details: ~0 rows (approximately)
DELETE FROM `transaction_details`;

-- Dumping structure for table distrozone.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','kasir','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_notifications` tinyint(1) NOT NULL DEFAULT '1',
  `order_updates` tinyint(1) NOT NULL DEFAULT '1',
  `newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.users: ~4 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `address`, `is_active`, `email_notifications`, `order_updates`, `newsletter`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin DistroZone', 'admin@distrozone.com', NULL, '$2y$12$6Ye.Tq7NqNhHsaA89iYSGOTGstkHUQy9VD3UkXaBkCibHkR6NUd3O', 'admin', '081234567890', 'Jln. Raya Pegangsaan Timur No.29H, Kelapa Gading, Jakarta', 1, 1, 1, 0, NULL, '2026-01-23 19:32:47', '2026-01-23 19:32:47'),
	(2, 'Budi Santoso', 'budi@distrozone.com', NULL, '$2y$12$PjK4IN6glDXqy/INsq/LxeXf0IO42LBR6xi46Y/mRn69Z7bgVw1k.', 'kasir', '081234567891', 'Jakarta', 1, 1, 1, 0, NULL, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(3, 'Siti Rahayu', 'siti@distrozone.com', NULL, '$2y$12$IG1Qa1xe4C85Z9Hx.I8rC.eXiR2pAwQFoVeqZ85yJIpkvy5XwxSVC', 'kasir', '081234567892', 'Jakarta', 1, 1, 1, 0, NULL, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(4, 'Customer Test', 'customer@test.com', NULL, '$2y$12$4VFoB3Phj81eXEHq7G1KtuYyMmWEEisdTiXdqXjvAWH5E3v/IJs8q', 'customer', '081234567893', 'Jakarta Selatan', 1, 1, 1, 0, NULL, '2026-01-23 19:32:48', '2026-01-23 19:32:48'),
	(5, 'padel', 'padel@gmail.com', NULL, '$2y$12$FI0wwS2NFq1zeM3pCQ00TOeQvO9gfD3nhGSOseJk3T6PlCYY7MDim', 'customer', '081234567890', NULL, 1, 1, 1, 0, NULL, '2026-01-23 19:33:08', '2026-01-23 19:33:08');

-- Dumping structure for table distrozone.wishlists
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_variant_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_variant_id`),
  KEY `wishlists_product_id_foreign` (`product_variant_id`),
  CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table distrozone.wishlists: ~0 rows (approximately)
DELETE FROM `wishlists`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
