-- =============================================================================
-- VAULTLAUNDRY DATABASE SCHEMA
-- Source of truth: Laravel migrations in database/migrations
-- Target documentation format: MySQL / MariaDB compatible DDL
--
-- Notes:
-- 1. This file is documentation and optional manual-import reference.
-- 2. Runtime database changes must still be made through Laravel migrations.
-- 3. Invoice and report features are computed from bookings/payments and do not
--    have physical tables in the current application schema.
-- =============================================================================

-- =============================================================================
-- VAULTLAUNDRY DATABASE SCHEMA
-- Target Database Engine: MySQL / MariaDB
-- 
-- CATATAN:
-- 1. Schema ini dibuat untuk keperluan dokumentasi atau import manual ke MySQL/MariaDB.
-- 2. Aplikasi utama VAULTLAUNDRY tetap menggunakan Laravel Migrations sebagai
--    sumber kebenaran (source of truth) struktur database.
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- USERS
-- =============================================================================

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(255) NOT NULL DEFAULT 'user',
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` TEXT NULL DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- FRAMEWORK SUPPORT TABLES
-- =============================================================================

CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL DEFAULT NULL,
  `cancelled_at` INT NULL DEFAULT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- SERVICES
-- =============================================================================

CREATE TABLE `services` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `price_per_kg` DECIMAL(10,2) NOT NULL,
  `estimated_days` INT NOT NULL DEFAULT 2,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- CUSTOMERS
-- =============================================================================

CREATE TABLE `customers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NULL DEFAULT NULL,
  `address` TEXT NULL DEFAULT NULL,
  `gender` ENUM('male', 'female') NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_user_id_foreign` (`user_id`),
  CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- BOOKINGS
-- =============================================================================

CREATE TABLE `bookings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_code` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `customer_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `service_id` BIGINT UNSIGNED NOT NULL,
  `booking_date` DATE NOT NULL,
  `estimated_finish_date` DATE NULL DEFAULT NULL,
  `weight` DECIMAL(8,2) NULL DEFAULT NULL,
  `total_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `pickup_type` ENUM('antar_sendiri', 'pickup') NOT NULL DEFAULT 'antar_sendiri',
  `status` ENUM('booking_masuk', 'diterima', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil', 'dibatalkan') NOT NULL DEFAULT 'booking_masuk',
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_customer_id_foreign` (`customer_id`),
  KEY `bookings_service_id_foreign` (`service_id`),
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- PAYMENTS
-- =============================================================================

CREATE TABLE `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` BIGINT UNSIGNED NOT NULL,
  `payment_code` VARCHAR(255) NOT NULL,
  `payment_date` DATETIME NOT NULL,
  `payment_method` ENUM('cash', 'transfer', 'ewallet') NOT NULL,
  `amount_paid` DECIMAL(12,2) NOT NULL,
  `total_bill` DECIMAL(12,2) NOT NULL,
  `change_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` ENUM('unpaid', 'partial', 'paid') NOT NULL DEFAULT 'unpaid',
  `notes` TEXT NULL DEFAULT NULL,
  `processed_by` BIGINT UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_code_unique` (`payment_code`),
  KEY `payments_booking_id_foreign` (`booking_id`),
  KEY `payments_processed_by_foreign` (`processed_by`),
  CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- INVOICES
-- =============================================================================
-- No physical `invoices` table exists.
-- Payment invoices are generated as PDF receipts from:
-- payments -> bookings -> customers/services, with processed_by -> users.

-- =============================================================================
-- REPORTS
-- =============================================================================
-- No physical `reports` table exists.
-- Transaction and revenue reports are computed from the `payments` table and
-- eager-loaded booking/customer/service relationships.

-- =============================================================================
-- SAMPLE DATA SEEDING
-- Mirrors the current Laravel seeders for manual demo imports.
-- =============================================================================

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@laundry.test', '2026-05-26 12:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, NOW(), NOW()),
(2, 'Kasir', 'kasir@laundry.test', '2026-05-26 12:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kasir', NULL, NOW(), NOW()),
(3, 'User', 'user@laundry.test', '2026-05-26 12:00:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, NOW(), NOW());

INSERT INTO `services` (`id`, `name`, `description`, `price_per_kg`, `estimated_days`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Cuci Kering', 'Layanan cuci tanpa setrika, cocok untuk pakaian sehari-hari.', 8000.00, 2, 1, NOW(), NOW()),
(2, 'Cuci Setrika', 'Layanan cuci lengkap dengan setrika rapi.', 12000.00, 3, 1, NOW(), NOW()),
(3, 'Setrika Saja', 'Layanan setrika pakaian yang sudah dicuci sendiri.', 5000.00, 1, 1, NOW(), NOW()),
(4, 'Laundry Express', 'Layanan cepat selesai dalam 1 hari.', 18000.00, 1, 1, NOW(), NOW()),
(5, 'Laundry Sepatu', 'Perawatan dan pencucian sepatu.', 25000.00, 3, 1, NOW(), NOW()),
(6, 'Laundry Bedcover', 'Cuci bedcover, sprei, dan linen besar.', 15000.00, 4, 1, NOW(), NOW());

INSERT INTO `customers` (`id`, `user_id`, `name`, `phone`, `address`, `gender`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 'Andi Pratama', '081234567890', 'Jl. Melati No. 12', 'male', 'Pelanggan reguler, preferensi parfum lembut.', NOW(), NOW()),
(2, NULL, 'Siti Aminah', '082112223333', 'Jl. Kenanga No. 5', 'female', 'Sering menggunakan layanan express.', NOW(), NOW()),
(3, NULL, 'Budi Santoso', '085677889900', 'Perumahan Harmoni Blok C3', 'male', NULL, NOW(), NOW()),
(4, NULL, 'Rina Lestari', NULL, 'Jl. Anggrek No. 18', 'female', 'Minta pakaian dipisah warna terang dan gelap.', NOW(), NOW());

INSERT INTO `bookings` (`id`, `booking_code`, `user_id`, `customer_id`, `service_id`, `booking_date`, `estimated_finish_date`, `weight`, `total_price`, `pickup_type`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'LDY-2026-0001', 3, 1, 2, '2026-05-26', '2026-05-29', 4.50, 54000.00, 'antar_sendiri', 'booking_masuk', 'Pakaian harian, parfum lembut.', NOW(), NOW()),
(2, 'LDY-2026-0002', NULL, 2, 4, '2026-05-26', '2026-05-27', 3.25, 58500.00, 'pickup', 'diterima', 'Pickup sore hari.', NOW(), NOW()),
(3, 'LDY-2026-0003', NULL, 3, 1, '2026-05-25', '2026-05-27', 6.00, 48000.00, 'antar_sendiri', 'dicuci', NULL, NOW(), NOW()),
(4, 'LDY-2026-0004', NULL, 4, 3, '2026-05-24', '2026-05-25', 2.75, 13750.00, 'pickup', 'selesai', 'Pisahkan pakaian warna terang dan gelap.', NOW(), NOW());

INSERT INTO `payments` (`id`, `booking_id`, `payment_code`, `payment_date`, `payment_method`, `amount_paid`, `total_bill`, `change_amount`, `payment_status`, `notes`, `processed_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'PAY-2026-0001', '2026-05-26 09:00:00', 'cash', 0.00, 54000.00, -54000.00, 'unpaid', 'Belum ada pembayaran.', NULL, NOW(), NOW()),
(2, 2, 'PAY-2026-0002', '2026-05-26 10:30:00', 'transfer', 25000.00, 58500.00, -33500.00, 'partial', 'Pembayaran DP.', 2, NOW(), NOW()),
(3, 3, 'PAY-2026-0003', '2026-05-26 13:15:00', 'ewallet', 48000.00, 48000.00, 0.00, 'paid', 'Pembayaran lunas via e-wallet.', 2, NOW(), NOW()),
(4, 4, 'PAY-2026-0004', '2026-05-26 16:00:00', 'cash', 23750.00, 13750.00, 10000.00, 'paid', 'Pembayaran tunai dengan kembalian.', 1, NOW(), NOW());
