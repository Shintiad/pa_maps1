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


-- Dumping database structure for pa_maps1
CREATE DATABASE IF NOT EXISTS `pa_maps1` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pa_maps1`;

-- Dumping structure for table pa_maps1.failed_jobs
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

-- Dumping data for table pa_maps1.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table pa_maps1.kasus_penyakits
CREATE TABLE IF NOT EXISTS `kasus_penyakits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tahun_id` bigint unsigned NOT NULL,
  `kecamatan_id` bigint unsigned NOT NULL,
  `penyakit_id` bigint unsigned NOT NULL,
  `terjangkit` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kasus_penyakits_tahun_id_foreign` (`tahun_id`),
  KEY `kasus_penyakits_kecamatan_id_foreign` (`kecamatan_id`),
  KEY `kasus_penyakits_penyakit_id_foreign` (`penyakit_id`),
  CONSTRAINT `kasus_penyakits_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kasus_penyakits_penyakit_id_foreign` FOREIGN KEY (`penyakit_id`) REFERENCES `penyakits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kasus_penyakits_tahun_id_foreign` FOREIGN KEY (`tahun_id`) REFERENCES `tahuns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pa_maps1.kasus_penyakits: ~0 rows (approximately)
INSERT INTO `kasus_penyakits` (`id`, `tahun_id`, `kecamatan_id`, `penyakit_id`, `terjangkit`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 123, '2024-08-13 05:11:36', '2024-08-13 05:11:36');

-- Dumping structure for table pa_maps1.kecamatans
CREATE TABLE IF NOT EXISTS `kecamatans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pa_maps1.kecamatans: ~27 rows (approximately)
INSERT INTO `kecamatans` (`id`, `nama_kecamatan`, `created_at`, `updated_at`) VALUES
	(1, 'Sukorame', '2024-08-12 08:12:41', '2024-08-12 08:12:43'),
	(2, 'Bluluk', '2024-08-12 08:12:52', '2024-08-12 08:12:53'),
	(3, 'Ngimbang', '2024-08-12 08:13:01', '2024-08-12 08:13:02'),
	(4, 'Sambeng', '2024-08-12 08:13:12', '2024-08-12 08:13:12'),
	(5, 'Mantup', '2024-08-12 08:13:21', '2024-08-12 08:13:22'),
	(6, 'Kembangbahu', '2024-08-12 08:13:33', '2024-08-12 08:13:34'),
	(7, 'Sugio', '2024-08-12 08:14:07', '2024-08-12 08:14:08'),
	(8, 'Kedungpring', '2024-08-12 08:14:19', '2024-08-12 08:14:20'),
	(9, 'Modo', '2024-08-12 08:14:29', '2024-08-12 08:14:30'),
	(10, 'Babat', '2024-08-12 08:14:39', '2024-08-12 08:14:40'),
	(11, 'Pucuk', '2024-08-12 08:14:48', '2024-08-12 08:14:49'),
	(12, 'Sukodadi', '2024-08-12 08:15:00', '2024-08-12 08:15:01'),
	(13, 'Lamongan', '2024-08-12 08:15:12', '2024-08-12 08:15:13'),
	(14, 'Tikung', '2024-08-12 08:15:25', '2024-08-12 08:15:26'),
	(15, 'Sarirejo', '2024-08-12 08:15:49', '2024-08-12 08:15:50'),
	(16, 'Deket', '2024-08-12 08:16:05', '2024-08-12 08:16:06'),
	(17, 'Glagah', '2024-08-12 08:16:15', '2024-08-12 08:16:16'),
	(18, 'Karangbinangun', '2024-08-12 08:16:31', '2024-08-12 08:16:32'),
	(19, 'Kalitengah', '2024-08-12 08:16:44', '2024-08-12 08:16:45'),
	(20, 'Turi', '2024-08-12 08:16:57', '2024-08-12 08:16:57'),
	(21, 'Karanggeneng', '2024-08-12 08:17:10', '2024-08-12 08:17:54'),
	(22, 'Sekaran', '2024-08-12 08:18:12', '2024-08-12 08:18:13'),
	(23, 'Maduran', '2024-08-12 08:18:29', '2024-08-12 08:18:30'),
	(24, 'Laren', '2024-08-13 12:53:56', '2024-08-13 12:53:57'),
	(25, 'Solokuro', '2024-08-12 08:19:00', '2024-08-12 08:19:01'),
	(26, 'Paciran', '2024-08-12 08:19:16', '2024-08-12 08:19:17'),
	(27, 'Brondong', '2024-08-12 08:19:27', '2024-08-12 08:19:27');

-- Dumping structure for table pa_maps1.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pa_maps1.migrations: ~0 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2024_08_12_043335_create_tahuns_table', 2),
	(6, '2024_08_12_043402_create_penyakits_table', 2),
	(7, '2024_08_12_043435_create_kecamatans_table', 2),
	(8, '2024_08_12_043647_create_penduduks_table', 2),
	(9, '2024_08_12_044228_create_kasus_penyakits_table', 2);

-- Dumping structure for table pa_maps1.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pa_maps1.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table pa_maps1.penduduks
CREATE TABLE IF NOT EXISTS `penduduks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tahun_id` bigint unsigned NOT NULL,
  `kecamatan_id` bigint unsigned NOT NULL,
  `jumlah_penduduk` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penduduks_tahun_id_foreign` (`tahun_id`),
  KEY `penduduks_kecamatan_id_foreign` (`kecamatan_id`),
  CONSTRAINT `penduduks_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penduduks_tahun_id_foreign` FOREIGN KEY (`tahun_id`) REFERENCES `tahuns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pa_maps1.penduduks: ~52 rows (approximately)
INSERT INTO `penduduks` (`id`, `tahun_id`, `kecamatan_id`, `jumlah_penduduk`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 20447, '2024-08-13 08:09:20', '2024-08-13 05:18:03'),
	(2, 1, 2, 22318, '2024-08-13 01:37:14', '2024-08-13 05:21:43'),
	(3, 1, 3, 46261, '2024-08-13 05:22:03', '2024-08-13 05:22:03'),
	(4, 1, 4, 46816, '2024-08-13 05:22:38', '2024-08-13 05:22:38'),
	(5, 1, 5, 44314, '2024-08-13 05:23:13', '2024-08-13 05:23:13'),
	(6, 1, 6, 47500, '2024-08-13 05:24:02', '2024-08-13 05:24:02'),
	(7, 1, 7, 53699, '2024-08-13 05:27:54', '2024-08-13 05:27:54'),
	(8, 1, 8, 47618, '2024-08-13 05:28:39', '2024-08-13 05:28:39'),
	(9, 1, 9, 44572, '2024-08-13 12:30:57', '2024-08-13 12:30:58'),
	(10, 1, 10, 74402, '2024-08-13 12:36:37', '2024-08-13 12:36:39'),
	(11, 1, 11, 35179, '2024-08-13 12:36:38', '2024-08-13 12:36:41'),
	(13, 1, 12, 51794, '2024-08-13 05:37:50', '2024-08-13 05:37:50'),
	(14, 1, 13, 71369, '2024-08-13 05:38:12', '2024-08-13 05:38:12'),
	(15, 1, 14, 45254, '2024-08-13 05:44:33', '2024-08-13 05:44:33'),
	(16, 1, 15, 22881, '2024-08-13 05:44:48', '2024-08-13 05:44:48'),
	(17, 1, 16, 43229, '2024-08-13 05:45:07', '2024-08-13 05:45:07'),
	(18, 1, 17, 34425, '2024-08-13 05:45:25', '2024-08-13 05:46:12'),
	(19, 1, 18, 32355, '2024-08-13 05:45:57', '2024-08-13 05:45:57'),
	(20, 1, 19, 29205, '2024-08-13 05:46:39', '2024-08-13 05:46:39'),
	(21, 1, 20, 47733, '2024-08-13 05:47:00', '2024-08-13 05:47:00'),
	(22, 1, 21, 32884, '2024-08-13 05:47:16', '2024-08-13 05:47:16'),
	(23, 1, 22, 28661, '2024-08-13 05:47:37', '2024-08-13 05:47:37'),
	(24, 1, 23, 21351, '2024-08-13 05:48:03', '2024-08-13 05:48:03'),
	(25, 1, 24, 30807, '2024-08-13 05:49:13', '2024-08-13 05:54:56'),
	(26, 1, 25, 42315, '2024-08-13 05:55:16', '2024-08-13 05:55:16'),
	(27, 1, 26, 101416, '2024-08-13 05:55:33', '2024-08-13 05:55:33'),
	(28, 1, 27, 69575, '2024-08-13 05:55:52', '2024-08-13 05:55:52'),
	(29, 2, 1, 20980, '2024-08-13 05:57:22', '2024-08-13 05:57:22'),
	(30, 2, 2, 22386, '2024-08-13 05:57:41', '2024-08-13 05:57:41'),
	(31, 2, 3, 47666, '2024-08-13 05:58:10', '2024-08-13 05:58:10'),
	(32, 2, 4, 49122, '2024-08-13 05:58:38', '2024-08-13 05:58:38'),
	(33, 2, 5, 45479, '2024-08-13 06:19:25', '2024-08-13 06:19:25'),
	(34, 2, 6, 49235, '2024-08-13 06:19:49', '2024-08-13 06:19:49'),
	(35, 2, 7, 54684, '2024-08-13 06:21:31', '2024-08-13 06:21:31'),
	(36, 2, 8, 47577, '2024-08-13 06:23:27', '2024-08-13 06:23:27'),
	(37, 2, 9, 44884, '2024-08-13 06:24:15', '2024-08-13 06:24:15'),
	(38, 2, 10, 76258, '2024-08-13 06:24:42', '2024-08-13 06:24:42'),
	(39, 2, 11, 35338, '2024-08-13 06:25:04', '2024-08-13 06:25:04'),
	(40, 2, 12, 52258, '2024-08-13 06:25:27', '2024-08-13 06:25:27'),
	(41, 2, 13, 72769, '2024-08-13 06:26:36', '2024-08-13 06:26:36'),
	(42, 2, 14, 46599, '2024-08-13 06:30:47', '2024-08-13 06:30:47'),
	(43, 2, 15, 23509, '2024-08-13 06:31:17', '2024-08-13 06:31:17'),
	(44, 2, 16, 44891, '2024-08-13 06:31:36', '2024-08-13 06:31:36'),
	(45, 2, 17, 35282, '2024-08-13 06:32:28', '2024-08-13 06:32:28'),
	(46, 2, 18, 32714, '2024-08-13 06:32:49', '2024-08-13 06:32:49'),
	(47, 2, 19, 29180, '2024-08-13 06:33:08', '2024-08-13 06:33:08'),
	(48, 2, 20, 49010, '2024-08-13 06:35:48', '2024-08-13 06:35:48'),
	(49, 2, 21, 32405, '2024-08-13 06:36:46', '2024-08-13 06:36:46'),
	(50, 2, 22, 27994, '2024-08-13 06:37:08', '2024-08-13 06:37:08'),
	(51, 2, 23, 20695, '2024-08-13 06:37:34', '2024-08-13 06:37:34'),
	(52, 2, 24, 29411, '2024-08-13 06:37:52', '2024-08-13 06:37:52'),
	(53, 2, 25, 40982, '2024-08-13 06:38:13', '2024-08-13 06:38:13'),
	(54, 2, 26, 102431, '2024-08-13 06:38:39', '2024-08-13 06:38:39'),
	(55, 2, 27, 70698, '2024-08-13 06:39:24', '2024-08-13 06:39:24'),
	(56, 3, 1, 21010, '2024-08-13 08:09:48', '2024-08-13 08:09:48'),
	(57, 3, 2, 22419, '2024-08-13 08:10:04', '2024-08-13 08:10:04'),
	(58, 3, 3, 47739, '2024-08-13 08:10:21', '2024-08-13 08:10:21'),
	(59, 3, 4, 49197, '2024-08-13 08:10:40', '2024-08-13 08:10:40'),
	(60, 3, 5, 45548, '2024-08-13 08:10:58', '2024-08-13 08:10:58'),
	(61, 3, 6, 49310, '2024-08-13 08:11:43', '2024-08-13 08:11:43'),
	(62, 3, 7, 54766, '2024-08-13 08:11:59', '2024-08-13 08:11:59'),
	(63, 3, 8, 47649, '2024-08-13 08:12:16', '2024-08-13 08:12:16'),
	(64, 3, 9, 44952, '2024-08-13 08:12:37', '2024-08-13 08:12:37'),
	(65, 3, 10, 76372, '2024-08-13 08:13:24', '2024-08-13 08:13:24'),
	(66, 3, 11, 35391, '2024-08-13 08:13:43', '2024-08-13 08:13:43'),
	(67, 3, 12, 52336, '2024-08-13 08:13:58', '2024-08-13 08:13:58'),
	(68, 3, 13, 72880, '2024-08-13 08:14:16', '2024-08-13 08:14:16'),
	(69, 3, 14, 46670, '2024-08-13 08:14:33', '2024-08-13 08:14:33'),
	(70, 3, 15, 23545, '2024-08-13 08:15:10', '2024-08-13 08:15:10'),
	(71, 3, 16, 44958, '2024-08-13 08:16:16', '2024-08-13 08:16:16'),
	(72, 3, 17, 35335, '2024-08-13 08:16:31', '2024-08-13 08:16:31'),
	(73, 3, 18, 32763, '2024-08-13 08:17:00', '2024-08-13 08:17:00'),
	(74, 3, 19, 29225, '2024-08-13 08:17:18', '2024-08-13 08:17:18'),
	(75, 3, 20, 49085, '2024-08-13 08:17:36', '2024-08-13 08:17:36'),
	(76, 3, 21, 32454, '2024-08-13 08:17:52', '2024-08-13 08:17:52'),
	(77, 3, 22, 28037, '2024-08-13 08:18:11', '2024-08-13 08:18:11'),
	(78, 3, 23, 20726, '2024-08-13 08:18:28', '2024-08-13 08:18:28'),
	(79, 3, 24, 29456, '2024-08-13 08:18:48', '2024-08-13 08:18:48'),
	(80, 3, 25, 41044, '2024-08-13 08:19:06', '2024-08-13 08:19:06'),
	(81, 3, 26, 102585, '2024-08-13 08:19:53', '2024-08-13 08:19:53'),
	(82, 3, 27, 70805, '2024-08-13 08:20:11', '2024-08-13 08:20:11');

-- Dumping structure for table pa_maps1.penyakits
CREATE TABLE IF NOT EXISTS `penyakits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_penyakit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pa_maps1.penyakits: ~7 rows (approximately)
INSERT INTO `penyakits` (`id`, `nama_penyakit`, `created_at`, `updated_at`) VALUES
	(1, 'DBD', '2024-08-12 08:07:47', '2024-08-12 08:07:49'),
	(2, 'Malaria', '2024-08-12 08:08:16', '2024-08-12 08:08:18'),
	(3, 'Hepatitits B', '2024-08-12 08:08:30', '2024-08-12 08:08:30'),
	(4, 'Kusta', '2024-08-12 08:08:41', '2024-08-12 08:08:42'),
	(5, 'Tuberkulosis', '2024-08-12 08:08:57', '2024-08-12 08:08:58'),
	(6, 'Filariasis', '2024-08-12 08:09:10', '2024-08-12 08:09:11'),
	(7, 'Campak', '2024-08-12 08:09:22', '2024-08-12 08:09:22');

-- Dumping structure for table pa_maps1.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pa_maps1.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table pa_maps1.tahuns
CREATE TABLE IF NOT EXISTS `tahuns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tahun` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pa_maps1.tahuns: ~3 rows (approximately)
INSERT INTO `tahuns` (`id`, `tahun`, `created_at`, `updated_at`) VALUES
	(1, '2020', '2024-08-12 07:57:30', '2024-08-12 07:57:31'),
	(2, '2021', '2024-08-12 07:59:21', '2024-08-12 07:59:22'),
	(3, '2022', '2024-08-12 07:59:45', '2024-08-12 07:59:46');

-- Dumping structure for table pa_maps1.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'phone cannot be empty',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pa_maps1.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `phone`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'shintiadewi789@gmail.com', '2024-08-11 04:23:36', '085806819449', '$2y$12$ply.3Sp5o5d8aHh9yUm0Ze30A.NO0EtkzeRRcXrRa89riHLdS4ZxC', 1, NULL, '2024-08-11 04:23:20', '2024-08-11 04:23:36'),
	(3, 'Shintia Dewi', 'shintiaaa789@gmail.com', '2024-08-12 02:00:16', '085706736122', '$2y$12$oxnEQrDV0Z7A2QLnjeDrVeccmdmg7AGIPi6fCKLn1y4Z8.xxrmxIC', 0, NULL, '2024-08-12 01:59:57', '2024-08-12 02:00:16');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
