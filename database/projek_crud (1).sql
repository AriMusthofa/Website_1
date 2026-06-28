-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2026 at 02:20 AM
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
-- Database: `projek_crud`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `destinasi_id` int(11) NOT NULL,
  `guide_id` int(11) DEFAULT NULL,
  `nama_customer` varchar(100) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jumlah_orang` int(11) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status` enum('Menunggu Guide','Guide Ditugaskan','Diterima Guide','Guide Menolak') DEFAULT 'Menunggu Guide',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `metode_bayar` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `user_id`, `destinasi_id`, `guide_id`, `nama_customer`, `whatsapp`, `catatan`, `tanggal`, `jumlah_orang`, `total_harga`, `status`, `created_at`, `bukti_pembayaran`, `metode_bayar`) VALUES
(2, 1, 14, 9, 'Ari Musthofa', '083456789012', '', '2026-06-06', 2, 100000.00, 'Diterima Guide', '2026-06-01 11:57:30', NULL, NULL),
(10, 0, 15, 10, 'Rohmat Ari', '+6283129650994', '', '2026-06-16', 1, 50000.00, 'Diterima Guide', '2026-06-02 02:46:20', NULL, NULL),
(11, 0, 15, 11, 'Rohmat Ari', '+6283129650994', '', '2026-06-16', 1, 50000.00, 'Diterima Guide', '2026-06-02 02:56:37', NULL, NULL),
(12, 0, 9, 12, 'Ari Musthofa', '+6283129650994', '', '2026-06-14', 3, 300000.00, 'Diterima Guide', '2026-06-02 06:39:01', NULL, NULL),
(13, 0, 6, 10, 'Rohmat Ari', '+6283129650994', '', '2026-06-28', 1, 300000.00, 'Diterima Guide', '2026-06-08 23:48:01', NULL, NULL),
(14, 0, 17, 9, 'Rohmat Ari', '+6283129650994', '', '2026-06-21', 2, 100000.00, 'Diterima Guide', '2026-06-20 03:09:27', NULL, NULL),
(15, 0, 12, 9, 'Rohmat Ari', '+6283129650994', '', '2026-06-21', 1, 70000.00, 'Diterima Guide', '2026-06-20 03:27:42', NULL, NULL),
(16, 0, 19, 9, 'Rohmat Ari', '+6283129650994', '', '2026-06-21', 1, 50000.00, 'Diterima Guide', '2026-06-20 03:29:11', NULL, NULL),
(17, 0, 18, 9, 'Rohmat Ari', '+6283129650994', '', '2026-06-22', 1, 50000.00, 'Diterima Guide', '2026-06-20 03:35:20', NULL, NULL),
(18, 7, 26, 9, 'Rohmat Ari', '+6283129650994', '', '2026-06-22', 1, 50000.00, 'Diterima Guide', '2026-06-20 03:44:20', NULL, 'kas'),
(19, 7, 19, 10, 'Rohmat Ari', '+6283129650994', '', '2026-06-21', 1, 50000.00, 'Diterima Guide', '2026-06-20 03:54:52', NULL, 'kas'),
(20, 7, 10, NULL, 'Rohmat Ari', '+6283129650994', '', '2026-06-21', 1, 100000.00, '', '2026-06-20 04:43:12', NULL, 'kas'),
(21, 7, 21, NULL, 'Rohmat Ari', '+6283129650994', '', '2026-06-24', 1, 50000.00, '', '2026-06-23 00:36:43', NULL, 'kas'),
(22, 7, 16, NULL, 'Rohmat Ari', '+6283129650994', '', '2026-06-24', 1, 50000.00, '', '2026-06-23 02:48:47', NULL, 'kas'),
(23, 7, 21, NULL, 'Alex', '083456789100', '', '2026-06-25', 1, 50000.00, '', '2026-06-24 00:10:46', NULL, 'kas');

-- --------------------------------------------------------

--
-- Table structure for table `destinasi`
--

CREATE TABLE `destinasi` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `altitude` varchar(50) NOT NULL,
  `difficulty` enum('Mudah','Menengah','Sulit') NOT NULL,
  `diff_key` varchar(20) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `dur_key` varchar(30) NOT NULL,
  `price` varchar(50) NOT NULL,
  `price_num` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `popular` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinasi`
--

INSERT INTO `destinasi` (`id`, `name`, `altitude`, `difficulty`, `diff_key`, `duration`, `dur_key`, `price`, `price_num`, `image`, `popular`, `created_at`) VALUES
(6, 'Gunung Rinjani', '3.726 mdpl', 'Sulit', 'sulit', '2 - 3 hari', '2-3-hari', 'Mulai Rp. 300.000/orang/hari', 300000, '6a1e97f50240c_destinasi.png', 1, '2026-05-28 06:15:07'),
(7, 'Bukit Sempana', '2.329 mdpl', 'Sulit', 'sulit', '4 - 7 jam', '4-7-jam', 'Mulai Rp.100.000/orang/hari', 100000, '6a192d57899da_destinasi.jpg', 0, '2026-05-29 06:08:23'),
(8, 'Bukit Gedong', '2.200 mdpl', 'Mudah', 'mudah', '2 - 5 jam', '2-5-jam', 'Mulai Rp.100.000/orang/hari', 100000, '6a1bab01efd89_destinasi.jpg', 0, '2026-05-29 06:25:42'),
(9, 'Bukit Kondo', '1.937 mdpl', 'Menengah', 'menengah', '4 - 6 jam', '4-6-jam', 'Mulai Rp.100.000/orang/hari', 100000, '6a1e98186b343_destinasi.jpg', 0, '2026-05-29 06:27:06'),
(10, 'Bukit Anak Dara', '1.923 mdpl', 'Mudah', 'mudah', '4 - 6 jam', '4-6-jam', 'Mulai Rp.100.000/orang/hari', 100000, '6a1bab3bc6ee4_destinasi.jpg', 0, '2026-05-29 06:28:00'),
(11, 'Bukit Pergasingan', '1.805 mdpl', 'Mudah', 'mudah', '4 - 6 jam', '4-6-jam', 'Mulai Rp.100.000/orang/hari', 100000, '6a1bab4cbe9b2_destinasi.jpg', 0, '2026-05-29 06:29:23'),
(12, 'Bukit Bao Daya', '1.600 mdpl', 'Mudah', 'mudah', '2 - 4 jam', '2-4-jam', 'Mulai Rp.70.000/orang/hari', 70000, '6a1bab6f5ee58_destinasi.jpg', 0, '2026-05-29 06:30:36'),
(13, 'Bukit Jaran Kurus', '2.251 mdpl', 'Mudah', 'mudah', '3 - 4 jam', '3-4-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1bab8c885bb_destinasi.jpg', 0, '2026-05-31 03:22:20'),
(14, 'Bukit Nanggi', '2.030 mdpl', 'Mudah', 'mudah', '4 - 6 jam', '4-6-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1babecb77bc_destinasi.jpg', 0, '2026-05-31 03:33:00'),
(15, 'Savana Dandaun', '1.300 mdpl', 'Mudah', 'mudah', '1 - 2 jam', '1-2-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1bac332f4e8_destinasi.jpg', 0, '2026-05-31 03:34:11'),
(16, 'Savana Propok', '1.600 mdpl', 'Mudah', 'mudah', '3 - 4 jam', '3-4-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1bac784f050_destinasi.jpg', 0, '2026-05-31 03:35:20'),
(17, 'Bukit Amben', '2.100 mdpl', 'Menengah', 'menengah', '3 - 4 jam', '3-4-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1e9895a941a_destinasi.jpg', 0, '2026-05-31 03:36:21'),
(18, 'Bukit Selong', '1.800 mdpl', 'Mudah', 'mudah', '10 - 20 menit', '10-20-menit', 'Mulai Rp.50.000/orang/hari', 50000, '6a1bad1159290_destinasi.jpg', 0, '2026-05-31 03:37:53'),
(19, 'Bukit Pal Jepang', '2.300 mdpl', 'Mudah', 'mudah', '4 - 5 jam', '4-5-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1bad78ebe7b_destinasi.jpg', 0, '2026-05-31 03:39:36'),
(20, 'Bukit Loang Dares', '2.381 mdpl', 'Mudah', 'mudah', '4 - 5 jam', '4-5-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1badb1ee71d_destinasi.jpg', 0, '2026-05-31 03:40:33'),
(21, 'Bukit Lincak', '2.100 mdpl', 'Mudah', 'mudah', '3 - 4 jam', '3-4-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1badf832073_destinasi.jpg', 0, '2026-05-31 03:41:44'),
(22, 'Savana Kanji', '1.700 mdpl', 'Mudah', 'mudah', '2 - 3 jam', '2-3-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1bae50cf7c0_destinasi.png', 0, '2026-05-31 03:43:12'),
(23, 'Bukit Batu Nunggang', '1.600 mdpl', 'Mudah', 'mudah', '2 - 3 jam', '2-3-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1bae7facc67_destinasi.png', 0, '2026-05-31 03:43:59'),
(25, 'Bukit Lahamban', '1.590 mdpl', 'Mudah', 'mudah', '2 - 3 jam', '2-3-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1e9a826ebc2_destinasi.png', 0, '2026-06-02 08:55:30'),
(26, 'Bukit Telaga', '1.700 mdpl', 'Mudah', 'mudah', '2 - 3 jam', '2-3-jam', 'Mulai Rp.50.000/orang/hari', 50000, '6a1e9ac06bbec_destinasi.jpg', 0, '2026-06-02 08:56:32');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `status_baca` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `pesan`, `status_baca`, `created_at`) VALUES
(9, 9, 'Booking baru ditugaskan kepada Anda. ID Booking: 2', 0, '2026-06-13 02:44:23'),
(10, 10, 'Booking baru ditugaskan kepada Anda. ID Booking: 10', 0, '2026-06-13 02:44:36'),
(11, 11, 'Booking baru ditugaskan kepada Anda. ID Booking: 11', 0, '2026-06-13 02:45:24'),
(12, 12, 'Booking baru ditugaskan kepada Anda. ID Booking: 12', 0, '2026-06-13 02:45:31'),
(13, 13, 'Booking baru ditugaskan kepada Anda. ID Booking: 13', 0, '2026-06-13 02:45:36'),
(14, 10, 'Booking baru ditugaskan kepada Anda. ID Booking: 13', 0, '2026-06-13 03:31:20'),
(15, 9, 'Booking baru ditugaskan kepada Anda. ID Booking: 14', 0, '2026-06-20 03:50:11'),
(16, 9, 'Booking baru ditugaskan kepada Anda. ID Booking: 15', 0, '2026-06-20 03:50:16'),
(17, 9, 'Booking baru ditugaskan kepada Anda. ID Booking: 16', 0, '2026-06-20 03:50:23'),
(18, 9, 'Booking baru ditugaskan kepada Anda. ID Booking: 17', 0, '2026-06-20 03:50:27'),
(19, 9, 'Booking baru ditugaskan kepada Anda. ID Booking: 18', 0, '2026-06-20 03:50:31'),
(20, 10, 'Booking baru ditugaskan kepada Anda. ID Booking: 19', 0, '2026-06-20 03:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guide','customer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(7, 'Ari Lasso', 'ari', '$2y$10$lG3TBgF4pfMhoKL.lub2K.jClkvIxoakNwVuFHjquirYv.lOZcZv2', 'customer'),
(8, 'Arya Kamandanu', 'arya', '$2y$10$PoEeKDLJ.Kof9rmlFMhDpOTXdIkLzf1yBF8wA./xE3oGg74a2VYbO', 'customer'),
(9, 'Rohmat Ari', 'guide1', '$2y$10$soRwUuWYr.bEUifous2Orua0pxJTQjo.pnyUofSQkwFRgDa1Mokji', 'guide'),
(10, 'Putra Jaya', 'guide2', '$2y$10$vy0LalS3qTL.XB7KLeX6k.fU//U7fz/VacM0lEth1lMhV4SmB1jja', 'guide'),
(11, 'Sulaiman', 'guide3', '$2y$10$8UAci151ybFN5FO33k8ovu5kNU6vNptrrmq7jNelcAhkVB/iRpoxa', 'guide'),
(12, 'Zahid Faruqi', 'guide4', '$2y$10$LYe7r6wZ4nUuAAbWAhHMSelGhqda3Hp5gYyxu8HPfTOvjJsMSzMPq', 'guide'),
(13, 'Sataruddin', 'guide5', '$2y$10$1OqyhwoJZkLSc0iwpqGAMeDLqk2rWGYEeM46cyxYH.WAS8C61SFJ6', 'guide');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `destinasi_id` (`destinasi_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `destinasi`
--
ALTER TABLE `destinasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `destinasi`
--
ALTER TABLE `destinasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`guide_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
