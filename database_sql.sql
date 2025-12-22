-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 22, 2025 at 07:08 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uas2`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `ukuran` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warna` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `produk_id`, `session_id`, `quantity`, `ukuran`, `warna`, `created_at`, `updated_at`) VALUES
(7, 2, 113, NULL, 2, NULL, NULL, '2025-12-16 20:23:36', '2025-12-16 20:23:36');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `detail_id` int NOT NULL,
  `pesanan_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `date`, `description`, `amount`, `created_at`, `updated_at`) VALUES
(1, '2025-12-16', 'gaji', 20000, '2025-12-16 01:17:25', '2025-12-16 01:17:25'),
(2, '2025-12-17', 'tumbas es', 1000, '2025-12-16 20:21:42', '2025-12-16 20:21:42'),
(3, '2025-12-17', 'gaji karyawan', 50000, '2025-12-16 20:54:11', '2025-12-16 20:54:11');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `nama_kategori`, `slug`, `deskripsi`, `created_at`) VALUES
(1, 'Jersey Dewasa', 'jersey-dewasa', 'Koleksi jersey dewasa', '2025-12-16 20:58:57'),
(3, 'Jersey Anak', 'jersey-anak', 'Setelan jersey anak', '2025-12-16 20:58:57'),
(12, 'Sepatu Sepak Bola', 'sepatu-sepak-bola', 'Berikut adalah beberapa kategori sepatu sepak bola', '2025-12-17 03:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `keranjang_id` int NOT NULL,
  `user_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keuangan`
--

CREATE TABLE `keuangan` (
  `keuangan_id` int NOT NULL,
  `jenis_transaksi` enum('pemasukan','pengeluaran') NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `deskripsi` text,
  `tanggal_transaksi` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_pembayaran`
--

CREATE TABLE `laporan_pembayaran` (
  `laporan_id` int NOT NULL,
  `periode_awal` date DEFAULT NULL,
  `periode_akhir` date DEFAULT NULL,
  `total_pembayaran` int DEFAULT NULL,
  `total_nominal` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_pesanan`
--

CREATE TABLE `laporan_pesanan` (
  `laporan_id` int NOT NULL,
  `periode_awal` date DEFAULT NULL,
  `periode_akhir` date DEFAULT NULL,
  `total_pesanan` int DEFAULT NULL,
  `total_pendapatan` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_transaksi`
--

CREATE TABLE `laporan_transaksi` (
  `laporan_id` int NOT NULL,
  `periode_awal` date DEFAULT NULL,
  `periode_akhir` date DEFAULT NULL,
  `total_transaksi` int DEFAULT NULL,
  `total_nilai` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_12_16_090500_create_expenses_table', 1),
(2, '2025_12_16_070500_create_cart_table', 2),
(3, '0001_01_01_000000_create_users_table', 3),
(4, '0001_01_01_000001_create_cache_table', 3),
(5, '0001_01_01_000002_create_jobs_table', 3),
(6, '2025_12_13_041359_create_produk_table', 4),
(7, '2025_12_13_041417_create_brands_table', 4),
(8, '2025_12_13_041423_create_categories_table', 4),
(9, '2025_12_13_041432_create_newsletter_subscribers_table', 4),
(10, '2025_12_13_073547_create_sessions_table', 5),
(11, '2025_12_13_152441_create_produk_views_table', 6),
(12, '2025_12_15_000001_add_name_to_users_table', 7),
(13, '2025_12_16_000001_create_orders_tables', 8),
(14, '2025_12_16_060000_add_payment_fields_to_orders_table', 8),
(15, '2025_12_16_070100_create_notifications_table', 9),
(16, '2025_12_16_134707_create_vouchers_table', 10),
(17, '2025_12_16_164334_add_order_id_to_reviews_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(2, 2, 'order', 'Status Pesanan Diperbarui', 'Status pesanan #1 berubah menjadi: Diproses', 'http://127.0.0.1:8000/account/orders/1', 'fa-box', 0, NULL, '2025-12-16 09:04:25', '2025-12-16 09:04:25'),
(3, 2, 'order', 'Status Pesanan Diperbarui', 'Status pesanan #1 berubah menjadi: Dikirim', 'http://127.0.0.1:8000/account/orders/1', 'fa-box', 0, NULL, '2025-12-16 09:04:30', '2025-12-16 09:04:30'),
(4, 2, 'order', 'Status Pesanan Diperbarui', 'Status pesanan #1 berubah menjadi: Selesai', 'http://127.0.0.1:8000/account/orders/1', 'fa-box', 1, '2025-12-16 09:30:14', '2025-12-16 09:04:40', '2025-12-16 09:30:14'),
(5, 1, 'order', 'Pesanan Baru #2', 'Pembeli melakukan checkout total Rp 679.600', 'http://127.0.0.1:8000/admin/orders', 'fa-shopping-bag', 1, '2025-12-16 18:42:47', '2025-12-16 18:29:42', '2025-12-16 18:42:47'),
(6, 2, 'order', 'Status Pesanan Diperbarui', 'Status pesanan #2 berubah menjadi: Diproses', 'http://127.0.0.1:8000/account/orders/2', 'fa-box', 0, NULL, '2025-12-16 18:36:57', '2025-12-16 18:36:57'),
(7, 2, 'order', 'Status Pesanan Diperbarui', 'Status pesanan #2 berubah menjadi: Dikirim', 'http://127.0.0.1:8000/account/orders/2', 'fa-box', 0, NULL, '2025-12-16 18:44:37', '2025-12-16 18:44:37'),
(8, 2, 'order', 'Status Pesanan Diperbarui', 'Status pesanan #2 berubah menjadi: Selesai', 'http://127.0.0.1:8000/account/orders/2', 'fa-box', 1, '2025-12-16 20:23:15', '2025-12-16 18:44:39', '2025-12-16 20:23:15'),
(9, 1, 'promo', 'diskon boyy', 'diskon 10%', NULL, 'fa-bullhorn', 1, '2025-12-17 21:10:29', '2025-12-16 20:22:37', '2025-12-17 21:10:29'),
(11, 1, 'order', 'Pesanan Baru #3', 'Pembeli melakukan checkout total Rp 436.689', 'http://127.0.0.1:8000/admin/orders', 'fa-shopping-bag', 1, '2025-12-17 21:10:29', '2025-12-16 20:46:57', '2025-12-17 21:10:29'),
(12, 3, 'order', 'Status Pesanan Diperbarui', 'Status pesanan #3 berubah menjadi: Diproses', 'http://127.0.0.1:8000/account/orders/3', 'fa-box', 0, NULL, '2025-12-16 20:53:01', '2025-12-16 20:53:01'),
(13, 3, 'order', 'Status Pesanan Diperbarui', 'Status pesanan #3 berubah menjadi: Dikirim', 'http://127.0.0.1:8000/account/orders/3', 'fa-box', 0, NULL, '2025-12-16 20:53:14', '2025-12-16 20:53:14'),
(14, 3, 'order', 'Status Pesanan Diperbarui', 'Status pesanan #3 berubah menjadi: Selesai', 'http://127.0.0.1:8000/account/orders/3', 'fa-box', 0, NULL, '2025-12-16 20:53:26', '2025-12-16 20:53:26'),
(15, 1, 'promo', 'promo awal bulan', 'terima promo ini', NULL, 'fa-bullhorn', 1, '2025-12-17 21:10:29', '2025-12-16 20:55:20', '2025-12-17 21:10:29'),
(16, 2, 'promo', 'promo awal bulan', 'terima promo ini', NULL, 'fa-bullhorn', 0, NULL, '2025-12-16 20:55:20', '2025-12-16 20:55:20'),
(17, 3, 'promo', 'promo awal bulan', 'terima promo ini', NULL, 'fa-bullhorn', 0, NULL, '2025-12-16 20:55:20', '2025-12-16 20:55:20'),
(18, 1, 'order', 'Pesanan Baru #4', 'Pembeli melakukan checkout total Rp 502.000', 'http://127.0.0.1:8000/admin/orders', 'fa-shopping-bag', 1, '2025-12-18 05:44:06', '2025-12-17 22:35:13', '2025-12-18 05:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baru',
  `subtotal` bigint NOT NULL DEFAULT '0',
  `tax` bigint NOT NULL DEFAULT '0',
  `shipping` bigint NOT NULL DEFAULT '0',
  `total` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `admin_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `status`, `subtotal`, `tax`, `shipping`, `total`, `created_at`, `updated_at`, `payment_status`, `payment_method`, `payment_proof`, `paid_at`, `admin_note`) VALUES
(1, 2, 'selesai', 100000, 11000, 0, 113500, '2025-12-16 05:54:02', '2025-12-16 05:54:02', 'cod', 'cod', NULL, NULL, 'Alamat: ainin | 082141668053 | kediri | kediri 6666. Catatan: -. Pengiriman: regular | Voucher: -. Biaya layanan: 2500 | Diskon voucher: 0 | Diskon ongkir: 15000'),
(2, 2, 'selesai', 610000, 67100, 0, 679600, '2025-12-16 18:29:41', '2025-12-16 18:29:41', 'cod', 'cod', NULL, NULL, 'Alamat: Ivan | 082141668053 | Jl. Penanggungan No. 54 | Kediri 666785. Catatan: cpt dikirim y min. Pengiriman: regular | Voucher: -. Biaya layanan: 2500 | Diskon voucher: 0 | Diskon ongkir: 15000'),
(3, 3, 'selesai', 409180, 45009, 0, 436689, '2025-12-16 20:46:57', '2025-12-16 20:46:57', 'cod', 'cod', NULL, NULL, 'Alamat: shofwan | 082141668053 | Jl. Penanggungan No. 54 | Kediri 666785. Catatan: -. Pengiriman: regular | Voucher: PROMO20K. Biaya layanan: 2500 | Diskon voucher: 20000 | Diskon ongkir: 15000'),
(4, 4, 'menunggu_pembayaran', 450000, 49500, 0, 502000, '2025-12-17 22:35:13', '2025-12-17 22:35:13', 'cod', 'cod', NULL, NULL, 'Alamat: alii | 082141668053 | inggrissss | kediri 6666. Catatan: -. Pengiriman: regular | Voucher: -. Biaya layanan: 2500 | Diskon voucher: 0 | Diskon ongkir: 15000');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `produk_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 28, 2, 50000, '2025-12-16 05:54:02', '2025-12-16 05:54:02'),
(2, 2, 116, 1, 460000, '2025-12-16 18:29:41', '2025-12-16 18:29:41'),
(3, 2, 103, 1, 150000, '2025-12-16 18:29:41', '2025-12-16 18:29:41'),
(4, 3, 113, 1, 499000, '2025-12-16 20:46:57', '2025-12-16 20:46:57'),
(5, 4, 119, 1, 450000, '2025-12-17 22:35:13', '2025-12-17 22:35:13');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `pembayaran_id` int NOT NULL,
  `pesanan_id` int NOT NULL,
  `metode_pembayaran` enum('transfer','cod','ewallet','kartu_kredit') NOT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `status_pembayaran` enum('pending','lunas','gagal') DEFAULT 'pending',
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `tanggal_bayar` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengiriman`
--

CREATE TABLE `pengiriman` (
  `pengiriman_id` int NOT NULL,
  `pesanan_id` int NOT NULL,
  `no_resi` varchar(100) DEFAULT NULL,
  `kurir` varchar(50) DEFAULT NULL,
  `status_pengiriman` enum('dikemas','dikirim','dalam_perjalanan','tiba','selesai') DEFAULT 'dikemas',
  `tanggal_kirim` timestamp NULL DEFAULT NULL,
  `tanggal_tiba` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `pesanan_id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `status_pesanan` enum('pending','diproses','dikirim','selesai','dibatalkan') DEFAULT 'pending',
  `alamat_pengiriman` text,
  `catatan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `produk_id` int NOT NULL,
  `kategori_id` int DEFAULT NULL,
  `nama_produk` varchar(200) NOT NULL,
  `deskripsi` text,
  `harga` decimal(15,2) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terjual` int NOT NULL DEFAULT '0',
  `rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_reviews` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `discount` int NOT NULL DEFAULT '0',
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `stok` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`produk_id`, `kategori_id`, `nama_produk`, `deskripsi`, `harga`, `gambar`, `created_at`, `updated_at`, `terjual`, `rating`, `total_reviews`, `is_active`, `discount`, `is_new`, `is_featured`, `stok`) VALUES
(72, 1, 'Jersey Bulutangkis Yonex Sleeveless - Putih', 'Jersey tanpa lengan dari Yonex untuk kenyamanan dan kebebasan bergerak. Warna putih dengan grafis dinamis biru dan merah. (Ukuran L Dewasa)', 125000.00, 'Jersey Bulutangkis Yonex  - Putih.jpg', '2025-12-16 20:58:57', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(73, 1, 'Jersey Home FC Barcelona 2024/2025', 'Jersey kandang FC Barcelona dengan garis biru merah ikonik dan sponsor Spotify. (Ukuran L Dewasa)', 150000.00, 'Jersey Home FC Barcelona 2024_2025.jpg', '2025-12-16 20:58:57', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(74, 1, 'Jersey Away Atletico Madrid - Biru Tua', 'Jersey tandang Atletico Madrid warna biru tua dengan sponsor Riyadh Air. (Ukuran L Dewasa)', 150000.00, 'Jersey Away Atletico Madrid - Biru Tua.jpg', '2025-12-16 20:58:57', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(75, 1, 'Jersey Away Real Madrid - Navy', 'Jersey tandang Real Madrid warna navy dengan aksen hijau neon. (Ukuran L Dewasa)', 150000.00, 'Jersey Away Real Madrid - Navy.jpg', '2025-12-16 20:58:57', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(76, 1, 'Jersey Home Real Madrid - Putih', 'Jersey kandang Real Madrid putih klasik dengan detail kuning. (Ukuran L Dewasa)', 150000.00, 'Jersey Home Real Madrid - Putih.jpg', '2025-12-16 20:58:57', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(77, 1, 'Jersey Home Arsenal - Merah/Putih', 'Jersey kandang Arsenal merah putih klasik Adidas. (Ukuran L Dewasa)', 150000.00, 'Jersey Home Arsenal - Merah_Putih.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 50),
(78, 1, 'Jersey Home Chelsea 2024/2025 - Biru', 'Jersey kandang Chelsea biru royal musim terbaru. (Ukuran L Dewasa)', 150000.00, 'Jersey Home Chelsea 2024_2025.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:57', 0, 0.00, 0, 1, 0, 1, 0, 50),
(79, 1, 'Jersey Home Manchester City 2024/2025 - Biru Langit', 'Jersey kandang Manchester City warna sky blue. (Ukuran L Dewasa)', 150000.00, 'Jersey Home Manchester City.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:57', 0, 0.00, 0, 1, 0, 1, 0, 50),
(80, 1, 'Jersey Home Tottenham Hotspur 2024/2025 - Putih', 'Jersey kandang Tottenham Hotspur putih dengan aksen navy. (Ukuran L Dewasa)', 150000.00, 'Jersey Home Tottenham Hotspur 2024_2025 - Putih.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 50),
(81, 1, 'Jersey Home Wolverhampton Wanderers - Kuning Emas', 'Jersey kandang Wolves warna old gold. (Ukuran L Dewasa)', 150000.00, 'Jersey Home Wolverhampton Wanderers - Kuning Emas.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 50),
(82, 1, 'Jersey Home Newcastle United 2024/2025 - Hitam/Putih', 'Jersey kandang Newcastle United garis hitam putih. (Ukuran L Dewasa)', 150000.00, 'Jersey Home Newcastle United 2024_2025 - Hitam_Putih.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 50),
(83, 1, 'Jersey Home Aston Villa 2023/2024 - Claret/Biru', 'Jersey kandang Aston Villa claret and blue. (Ukuran L Dewasa)', 150000.00, 'Jersey Home Aston Villa 2023_2024 .jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:57', 0, 0.00, 0, 1, 0, 1, 0, 50),
(84, 1, 'Jersey Home Everton 2024/2025 - Biru Royal', 'Jersey kandang Everton biru royal. (Ukuran L Dewasa)', 150000.00, 'Jersey Home Everton 2024_2025 - Biru Royal.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 50),
(85, 1, 'Jersey Home Brighton & Hove Albion - Biru/Putih', 'Jersey kandang Brighton garis biru putih. (Ukuran L Dewasa)', 150000.00, 'Jersey Home Brighton & Hove Albion - Biru_Putih.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 50),
(86, NULL, 'Sepatu Bola Ortuseight - Hitam/Hijau Neon', 'Sepatu bola Ortuseight hitam dengan aksen hijau neon.', 280000.00, 'Sepatu Bola Ortuseight - Hitam_Hijau Neon.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 30),
(87, NULL, 'Sepatu Bola Nike Air Zoom - Biru', 'Sepatu bola Nike Air Zoom biru untuk akselerasi cepat.', 350000.00, 'Sepatu Bola Nike Air Zoom - Biru.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 30),
(88, NULL, 'Sepatu Bola Adidas Predator - Hitam/Merah', 'Sepatu bola Adidas Predator hitam dengan zona grip merah.', 499000.00, 'Sepatu Bola Adidas - Hitam_Emas.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:57', 0, 0.00, 0, 1, 0, 1, 0, 35),
(89, NULL, 'Sepatu Bola Puma Future Ultimate - Biru/Oranye', 'Sepatu bola Puma Future Ultimate untuk playmaker.', 480000.00, 'Sepatu Bola Puma Future Ultimate - Biru Elektrik_Oranye.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:57', 0, 0.00, 0, 1, 0, 1, 0, 30),
(90, 3, 'Setelan Jersey Anak Liverpool FC - Merah', 'Setelan jersey anak Liverpool warna merah. (Usia 8–12 Tahun)', 95000.00, 'Setelan Jersey Anak Liverpool FC - Merah.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 40),
(91, 3, 'Setelan Jersey Anak Al Nassr - Kuning/Biru', 'Setelan jersey anak Al Nassr kuning biru. (Usia 8–12 Tahun)', 95000.00, 'Setelan Jersey Anak Al Nassr - Kuning_Biru.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 40),
(92, 3, 'Setelan Jersey Anak Real Madrid Home - Putih', 'Setelan jersey anak Real Madrid putih klasik. (Usia 8–12 Tahun)', 95000.00, 'Setelan Jersey Anak Real Madrid Home - Putihjpg.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 40),
(93, 3, 'Setelan Jersey Anak FC Barcelona Home - Biru/Merah', 'Setelan jersey anak FC Barcelona biru merah. (Usia 8–12 Tahun)', 95000.00, 'Setelan Jersey Anak FC Barcelona Home - Biru_Merahjpg.jpg', '2025-12-16 20:58:57', '2025-12-16 14:05:21', 0, 0.00, 0, 1, 0, 1, 0, 40),
(94, 1, 'Jersey Bulutangkis Yonex Sleeveless - Putih', 'Jersey tanpa lengan Yonex untuk kenyamanan maksimal', 125000.00, 'Jersey Bulutangkis Yonex  - Putih.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(95, 1, 'Jersey Home FC Barcelona 2024/2025', 'Jersey kandang Barcelona musim terbaru', 150000.00, 'Jersey Home FC Barcelona 2024_2025.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(96, 1, 'Jersey Away Atletico Madrid - Biru Tua', 'Jersey tandang Atletico Madrid elegan', 150000.00, 'Jersey Away Atletico Madrid - Biru Tua.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(97, 1, 'Jersey Away Real Madrid - Navy', 'Jersey tandang Real Madrid navy modern', 150000.00, 'Jersey Away Real Madrid - Navy.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(98, 1, 'Jersey Home Real Madrid - Putih', 'Jersey kandang Real Madrid klasik', 150000.00, 'Jersey Home Real Madrid - Putih.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(99, 1, 'Jersey Home Arsenal - Merah Putih', 'Jersey kandang Arsenal autentik', 150000.00, 'Jersey Home Arsenal - Merah_Putih.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(100, 1, 'Jersey Home Chelsea 2024/2025', 'Jersey Chelsea biru royal', 150000.00, 'Jersey Home Chelsea 2024_2025.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(101, 1, 'Jersey Home Manchester City 2024/2025', 'Jersey Man City sky blue', 150000.00, 'Jersey Home Manchester City.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(102, 1, 'Jersey Home Tottenham Hotspur 2024/2025', 'Jersey Spurs putih navy', 150000.00, 'Jersey Home Tottenham Hotspur 2024_2025 - Putih.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(103, 1, 'Jersey Home Wolverhampton Wanderers', 'Jersey Wolves kuning emas', 150000.00, 'Jersey Home Wolverhampton Wanderers - Kuning Emas.jpg', '2025-12-16 21:56:41', '2025-12-16 18:29:41', 1, 0.00, 0, 1, 0, 1, 0, 49),
(104, 1, 'Jersey Home Newcastle United 2024/2025', 'Jersey Newcastle hitam putih', 150000.00, 'Jersey Home Newcastle United 2024_2025 - Hitam_Putih.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(105, 1, 'Jersey Home Aston Villa 2023/2024', 'Jersey Aston Villa claret blue', 150000.00, 'Jersey Home Aston Villa 2023_2024 .jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(106, 1, 'Jersey Home Everton 2024/2025', 'Jersey Everton biru royal', 150000.00, 'Jersey Home Everton 2024_2025 - Biru Royal.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(107, 1, 'Jersey Home Brighton & Hove Albion', 'Jersey Brighton biru putih', 150000.00, 'Jersey Home Brighton & Hove Albion - Biru_Putih.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 50),
(108, 12, 'Sepatu Bola Ortuseight Hitam Hijau', 'Ortuseight hitam hijau neon', 280000.00, 'Sepatu Bola Ortuseight - Hitam_Hijau Neon.jpg', '2025-12-16 21:56:41', '2025-12-17 19:39:51', 0, 0.00, 0, 1, 0, 1, 0, 30),
(109, NULL, 'Sepatu Bola Nike Air Zoom Biru', 'Nike Air Zoom biru cepat', 350000.00, 'Sepatu Bola Nike Air Zoom - Biru.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 30),
(110, NULL, 'Sepatu Bola Nike Hijau Stabilo', 'Nike hijau stabilo mencolok', 320000.00, 'Sepatu Bola Nike - Hijau Stabilo.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 30),
(111, NULL, 'Sepatu Bola Adidas Hitam Emas', 'Adidas hitam emas premium', 330000.00, 'Sepatu Bola Adidas - Hitam_Emas.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 30),
(112, NULL, 'Nike Mercurial Vapor Blue Purple', 'Mercurial Vapor edisi cepat', 550000.00, 'Sepatu Bola Nike Air Zoom - Biru.jpg', '2025-12-16 21:56:41', '2025-12-16 15:03:47', 0, 0.00, 0, 1, 0, 1, 0, 25),
(113, 12, 'Adidas Predator Hitam Merah', 'Predator kontrol tendangan', 499000.00, 'Sepatu Bola Adidas - Hitam_Emas.jpg', '2025-12-16 21:56:41', '2025-12-17 19:38:15', 1, 0.00, 0, 1, 0, 1, 0, 34),
(114, 12, 'Puma Future Ultimate', 'Puma Future playmaker', 480000.00, 'Sepatu Bola Asics DS Light - Putih_Biru.jpg', '2025-12-16 21:56:41', '2025-12-17 19:38:26', 0, 0.00, 0, 1, 0, 1, 0, 30),
(115, 12, 'Mizuno Morelia Neo III', 'Mizuno ringan presisi', 580000.00, 'Sepatu Bola Umbro Speciali - Biru_Putih.jpg', '2025-12-16 21:56:41', '2025-12-17 19:38:41', 0, 0.00, 0, 1, 0, 1, 0, 20),
(116, 12, 'Asics DS Light', 'Asics ringan nyaman', 460000.00, 'Sepatu Bola Asics DS Light - Putih_Biru.jpg', '2025-12-16 21:56:41', '2025-12-17 19:38:53', 1, 0.00, 0, 1, 0, 1, 0, 24),
(117, 12, 'Lotto Solista 300', 'Lotto Solista kecepatan', 420000.00, 'Sepatu Bola Lotto Solista 300 - Oranye_Hitam.jpg', '2025-12-16 21:56:41', '2025-12-17 19:39:05', 0, 0.00, 0, 1, 0, 1, 0, 25),
(118, 12, 'Diadora Brasil', 'Diadora klasik Italia', 475000.00, 'Sepatu Bola Diadora Brasil - Kuning_Hitam.jpg', '2025-12-16 21:56:41', '2025-12-17 19:39:17', 0, 0.00, 0, 1, 0, 1, 0, 20),
(119, 12, 'Umbro Speciali', 'Umbro klasik kontrol', 450000.00, 'Sepatu Bola Umbro Speciali - Biru_Putih.jpg', '2025-12-16 21:56:41', '2025-12-17 22:35:13', 1, 0.00, 0, 1, 0, 1, 0, 27),
(120, 12, 'New Balance Tekela', 'NB Tekela kreator serangan', 525000.00, 'Sepatu Bola New Balance Tekela - Biru Laut_Hitam.jpg', '2025-12-16 21:56:41', '2025-12-17 19:39:37', 0, 0.00, 0, 1, 0, 1, 0, 22),
(121, NULL, 'Under Armour Magnetico', 'UA Magnetico zero break in', 510000.00, 'Sepatu Bola Under Armour Magnetico - Merah_Putih.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 22),
(122, NULL, 'Adidas X Crazyfast', 'Adidas X kecepatan tinggi', 530000.00, 'Sepatu Bola Adidas X Crazyfast - Putih_Hijau Neon.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 30),
(123, NULL, 'Nike Phantom GX', 'Nike Phantom kontrol bola', 540000.00, 'Sepatu Bola Nike - Hijau Stabilo.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 25),
(124, 3, 'Set Jersey Anak Liverpool', 'Set jersey anak Liverpool', 95000.00, 'Setelan Jersey Anak Liverpool FC - Merah.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40),
(125, 3, 'Set Jersey Anak Al Nassr', 'Set jersey anak Al Nassr', 95000.00, 'Setelan Jersey Anak Al Nassr - Kuning_Biru.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40),
(126, 3, 'Set Jersey Anak Juventus', 'Set jersey anak Juventus', 95000.00, 'Setelan Jersey Anak Juventus - Hitam_Putih.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40),
(127, 3, 'Set Jersey Anak Manchester United', 'Set jersey anak MU', 95000.00, 'Setelan Jersey Anak Manchester United Home - Merah.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40),
(128, 3, 'Set Jersey Anak Nusantara Air', 'Set jersey anak Nusantara', 95000.00, 'Setelan Jersey Anak Nusantara Air - Merah_Emas.png', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40),
(129, 3, 'Set Jersey Anak Oceanic Energy', 'Set jersey anak Oceanic', 95000.00, 'Setelan Jersey Anak Oceanic Energy - Biru Tua_Cyan.png', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40),
(130, 3, 'Set Jersey Anak Terra Nova', 'Set jersey anak Terra Nova', 95000.00, 'Setelan Jersey Anak Terra Nova - Hijau_Putih.png', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40),
(131, 3, 'Set Jersey Anak Galactic Explorers', 'Set jersey anak Galactic', 95000.00, 'Setelan Jersey Anak Galactic Explorers - Ungu_Teal.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40),
(132, 3, 'Set Jersey Anak Azzurri Junior', 'Set jersey anak Azzurri', 95000.00, 'Setelan Jersey Anak Azzurri Junior - Biru Langit.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40),
(133, 3, 'Set Jersey Anak Real Madrid', 'Set jersey anak Real Madrid', 95000.00, 'Setelan Jersey Anak Real Madrid Home - Putihjpg.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40),
(134, 3, 'Set Jersey Anak Barcelona', 'Set jersey anak Barcelona', 95000.00, 'Setelan Jersey Anak FC Barcelona Home - Biru_Merahjpg.jpg', '2025-12-16 21:56:41', '2025-12-16 15:09:10', 0, 0.00, 0, 1, 0, 1, 0, 40);

-- --------------------------------------------------------

--
-- Table structure for table `produk_images`
--

CREATE TABLE `produk_images` (
  `id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk_promo`
--

CREATE TABLE `produk_promo` (
  `id` bigint UNSIGNED NOT NULL,
  `produk_id` int NOT NULL,
  `promo_id` bigint UNSIGNED NOT NULL,
  `promo_price` decimal(12,2) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk_promo`
--

INSERT INTO `produk_promo` (`id`, `produk_id`, `promo_id`, `promo_price`, `stock`, `is_active`, `start_date`, `end_date`) VALUES
(12, 124, 1, 84550.00, 12, 1, '2025-12-14', '2025-12-31'),
(13, 113, 1, 409180.00, 10, 1, '2025-12-14', '2025-12-31'),
(14, 74, 1, 124500.00, 6, 1, '2025-12-14', '2025-12-31'),
(15, 95, 1, 132000.00, 7, 1, '2025-12-14', '2025-12-31'),
(16, 125, 1, 66500.00, 7, 1, '2025-12-14', '2025-12-31'),
(17, 98, 1, 129000.00, 18, 1, '2025-12-14', '2025-12-31'),
(18, 121, 1, 357000.00, 16, 1, '2025-12-14', '2025-12-31'),
(19, 112, 1, 429000.00, 17, 1, '2025-12-14', '2025-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `produk_views`
--

CREATE TABLE `produk_views` (
  `id` bigint UNSIGNED NOT NULL,
  `produk_id` int NOT NULL,
  `view_count` int NOT NULL DEFAULT '1' COMMENT 'Number of views for this day',
  `viewed_at` date NOT NULL COMMENT 'Date of views',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`id`, `title`, `description`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Promo Akhir Tahun', 'Diskon spesial akhir tahun untuk produk pilihan', '2025-12-14', '2025-12-31', 1, '2025-12-14 15:38:52', '2025-12-14 15:38:52');

-- --------------------------------------------------------

--
-- Table structure for table `retur`
--

CREATE TABLE `retur` (
  `retur_id` int NOT NULL,
  `pesanan_id` int NOT NULL,
  `user_id` int NOT NULL,
  `alasan` text NOT NULL,
  `status_retur` enum('diajukan','disetujui','ditolak','diproses','selesai') DEFAULT 'diajukan',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `rating` int NOT NULL DEFAULT '5',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `images` json DEFAULT NULL,
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `produk_id`, `order_id`, `rating`, `comment`, `images`, `is_verified_purchase`, `created_at`, `updated_at`) VALUES
(1, 2, 28, NULL, 5, NULL, NULL, 0, '2025-12-16 06:35:08', '2025-12-16 06:35:08'),
(2, 2, 28, 1, 3, 'mantap', NULL, 1, '2025-12-16 09:45:30', '2025-12-16 09:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_stok`
--

CREATE TABLE `riwayat_stok` (
  `riwayat_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `jenis_transaksi` enum('masuk','keluar','update') NOT NULL,
  `jumlah` int NOT NULL,
  `stok_sebelum` int DEFAULT NULL,
  `stok_sesudah` int DEFAULT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('02bNaWxobyBse3q5po2UwZrHkCFJDVwf48EMgktA', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoialB1aWtmdnJEZlRrbXJsNmhORGtONGpuTHk3QU9kQjV0dktwbE5ObCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1766063620),
('RyZISM2pEr7oqKY9UzkbqcvoF160JXWc9TYA2iRH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZUlLOTRYTWN4d2RNUW5JNWhITFlyOVZ6bkk5YzJhN3lMRzVQMU1CUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1766386879);

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `stok_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `jumlah_stok` int NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksi_id` int NOT NULL,
  `pesanan_id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_transaksi` decimal(15,2) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `status_transaksi` enum('success','pending','failed') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','pelanggan') NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `alamat` text,
  `no_telepon` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`, `nama_lengkap`, `alamat`, `no_telepon`, `created_at`, `updated_at`, `name`) VALUES
(1, 'admin', '$2y$12$Fp8R3ITt.Nk6xkaWakZXP.3YPI5P1YmAnHvwNLyMpwd9OtTG2rCxS', 'admin@gmail.com', 'admin', 'admin', NULL, NULL, '2025-12-15 09:04:08', '2025-12-15 16:13:42', NULL),
(2, 'pelanggan', '$2y$12$wE8AgSq2JNoZEmRCROYk8ORxDLfUP7qexZlzaLUSwj7Zzu8s8BI1u', 'pelanggan@gmail.com', 'pelanggan', 'pelanggan', NULL, NULL, '2025-12-15 21:07:58', '2025-12-15 21:07:58', NULL),
(3, 'shofwan', '$2y$12$.mXb7pr84cAzIuJNKjD0RuxF2oANGvJkRaSwrZ2YPSCawpknabmBa', 'shofwan@gmail.com', 'pelanggan', 'shofwan', NULL, NULL, '2025-12-16 20:45:14', '2025-12-16 20:45:14', NULL),
(4, 'ali', '$2y$12$rgWDGGR3me9IgFFvRfZOf.WPAQAkIYe7XVx30/KG05WVagW2LjZWe', 'alisantosa@gmail.com', 'pelanggan', 'alisantosa', NULL, NULL, '2025-12-17 21:51:13', '2025-12-17 21:51:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `discount_type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `discount_value` bigint NOT NULL,
  `min_purchase` bigint NOT NULL DEFAULT '0',
  `max_discount` bigint DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `quota` int NOT NULL DEFAULT '0' COMMENT '0 for unlimited',
  `used_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `title`, `description`, `discount_type`, `discount_value`, `min_purchase`, `max_discount`, `start_date`, `end_date`, `is_active`, `quota`, `used_count`, `created_at`, `updated_at`) VALUES
(1, 'FLASH50', 'Flash Sale 50%', 'Diskon 50% maksimal Rp 50.000. Hanya hari ini!', 'percent', 50, 0, 50000, '2025-12-16', '2025-12-17', 1, 100, 0, '2025-12-16 06:48:05', '2025-12-16 06:48:05'),
(2, 'AKHIRTAHUN', 'Diskon Akhir Tahun', 'Potongan Rp 10.000 untuk pembelian minimal Rp 100.000', 'fixed', 10000, 100000, NULL, '2025-12-16', '2025-12-31', 1, 0, 0, '2025-12-16 06:48:05', '2025-12-16 06:48:05'),
(3, 'ONGKIRHEMAT', 'Hemat Ongkir', 'Potongan ongkir Rp 15.000', 'fixed', 15000, 50000, NULL, '2025-12-16', '2025-12-19', 1, 50, 0, '2025-12-16 06:48:05', '2025-12-16 06:48:05');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `fk_detail_pesanan` (`pesanan_id`),
  ADD KEY `fk_detail_produk` (`produk_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_date_index` (`date`),
  ADD KEY `expenses_created_at_index` (`created_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`keranjang_id`),
  ADD UNIQUE KEY `uk_user_produk` (`user_id`,`produk_id`),
  ADD KEY `fk_keranjang_produk` (`produk_id`),
  ADD KEY `idx_keranjang_user` (`user_id`);

--
-- Indexes for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`keuangan_id`);

--
-- Indexes for table `laporan_pembayaran`
--
ALTER TABLE `laporan_pembayaran`
  ADD PRIMARY KEY (`laporan_id`);

--
-- Indexes for table `laporan_pesanan`
--
ALTER TABLE `laporan_pesanan`
  ADD PRIMARY KEY (`laporan_id`);

--
-- Indexes for table `laporan_transaksi`
--
ALTER TABLE `laporan_transaksi`
  ADD PRIMARY KEY (`laporan_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_index` (`order_id`),
  ADD KEY `order_items_produk_id_index` (`produk_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`pembayaran_id`),
  ADD KEY `idx_pembayaran_pesanan` (`pesanan_id`);

--
-- Indexes for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`pengiriman_id`),
  ADD KEY `idx_pengiriman_pesanan` (`pesanan_id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`pesanan_id`),
  ADD KEY `idx_pesanan_user` (`user_id`),
  ADD KEY `idx_pesanan_status` (`status_pesanan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`produk_id`),
  ADD KEY `idx_produk_kategori` (`kategori_id`);

--
-- Indexes for table `produk_images`
--
ALTER TABLE `produk_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk_promo`
--
ALTER TABLE `produk_promo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_produk_promo` (`produk_id`,`promo_id`),
  ADD KEY `promo_id` (`promo_id`);

--
-- Indexes for table `produk_views`
--
ALTER TABLE `produk_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_produk_id` (`produk_id`),
  ADD KEY `idx_viewed_at` (`viewed_at`),
  ADD KEY `idx_produk_id_viewed_at` (`produk_id`,`viewed_at`);

--
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `retur`
--
ALTER TABLE `retur`
  ADD PRIMARY KEY (`retur_id`),
  ADD KEY `fk_retur_user` (`user_id`),
  ADD KEY `idx_retur_pesanan` (`pesanan_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_produk_id_index` (`produk_id`),
  ADD KEY `reviews_user_id_index` (`user_id`),
  ADD KEY `reviews_rating_index` (`rating`);

--
-- Indexes for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD PRIMARY KEY (`riwayat_id`),
  ADD KEY `fk_riwayat_produk` (`produk_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`stok_id`),
  ADD KEY `idx_stok_produk` (`produk_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`transaksi_id`),
  ADD KEY `fk_transaksi_pesanan` (`pesanan_id`),
  ADD KEY `idx_transaksi_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`),
  ADD KEY `vouchers_code_index` (`code`),
  ADD KEY `vouchers_is_active_index` (`is_active`),
  ADD KEY `vouchers_start_date_end_date_index` (`start_date`,`end_date`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `detail_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `keranjang_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `keuangan_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_pembayaran`
--
ALTER TABLE `laporan_pembayaran`
  MODIFY `laporan_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_pesanan`
--
ALTER TABLE `laporan_pesanan`
  MODIFY `laporan_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_transaksi`
--
ALTER TABLE `laporan_transaksi`
  MODIFY `laporan_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `pembayaran_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `pengiriman_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `pesanan_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `produk_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `produk_images`
--
ALTER TABLE `produk_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk_promo`
--
ALTER TABLE `produk_promo`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `produk_views`
--
ALTER TABLE `produk_views`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `retur`
--
ALTER TABLE `retur`
  MODIFY `retur_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  MODIFY `riwayat_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `stok_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksi_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `fk_detail_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`pesanan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`) ON DELETE CASCADE;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `fk_keranjang_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_keranjang_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`pesanan_id`) ON DELETE CASCADE;

--
-- Constraints for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD CONSTRAINT `fk_pengiriman_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`pesanan_id`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_pesanan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE SET NULL;

--
-- Constraints for table `produk_promo`
--
ALTER TABLE `produk_promo`
  ADD CONSTRAINT `produk_promo_ibfk_1` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `produk_promo_ibfk_2` FOREIGN KEY (`promo_id`) REFERENCES `promo` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produk_views`
--
ALTER TABLE `produk_views`
  ADD CONSTRAINT `fk_produk_views_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`) ON DELETE CASCADE;

--
-- Constraints for table `retur`
--
ALTER TABLE `retur`
  ADD CONSTRAINT `fk_retur_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`pesanan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_retur_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD CONSTRAINT `fk_riwayat_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`) ON DELETE CASCADE;

--
-- Constraints for table `stok`
--
ALTER TABLE `stok`
  ADD CONSTRAINT `fk_stok_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`pesanan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
