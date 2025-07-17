-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 17, 2025 at 06:27 AM
-- Server version: 8.0.42
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem-inventori`
--

-- --------------------------------------------------------

--
-- Table structure for table `opname_produk`
--

CREATE TABLE `opname_produk` (
  `id_opname` int NOT NULL,
  `id_produk` int NOT NULL,
  `tanggal_opname` date NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `stok_awal` varchar(255) DEFAULT NULL,
  `stok_akhir` varchar(255) DEFAULT NULL,
  `penjualan` varchar(255) DEFAULT NULL,
  `bs` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `opname_produk`
--

INSERT INTO `opname_produk` (`id_opname`, `id_produk`, `tanggal_opname`, `kode`, `stok_awal`, `stok_akhir`, `penjualan`, `bs`) VALUES
(4, 8, '2025-07-16', '', '', '6', '', ''),
(5, 11, '2025-07-16', '', '', '6', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int NOT NULL,
  `nama_pengaturan` varchar(100) NOT NULL,
  `nilai_pengaturan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_pengaturan`, `nilai_pengaturan`) VALUES
(1, 'NAMA_WEBSITE', 'Silmarils Cookies Dessert'),
(2, 'PATH_FAVICON', 'assets/img/favicon_1752571406.png');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `level` enum('admin','pegawai') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `nama_lengkap`, `level`) VALUES
(1, 'riyan22', '$2y$10$5BuuEqYOjpnW4jjDxuiYsO4IDigV5QBhGgXB5KuwdN1LtRPXG2gqC', 'Riyan Wardhana', 'admin'),
(2, 'oka', '$2y$10$LKZddb1Ko0WSAGLEjX.tve0lfmAZv1je3KRxBZiJO1CVCoW8rKEJq', 'Oka Gaming', 'pegawai');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `kode_sku` varchar(50) DEFAULT NULL,
  `nama_produk` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `kode_sku`, `nama_produk`) VALUES
(8, '', 'Roti Coklat'),
(11, '', 'Roti Strawbery');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_stok`
--

CREATE TABLE `riwayat_stok` (
  `id_riwayat` int NOT NULL,
  `id_produk` int DEFAULT NULL,
  `jumlah` int NOT NULL,
  `tipe_transaksi` enum('masuk','keluar','bs','penyesuaian') NOT NULL,
  `keterangan` text,
  `tanggal_transaksi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stok_bs`
--

CREATE TABLE `stok_bs` (
  `id_bs` int NOT NULL,
  `id_produk` int NOT NULL,
  `tanggal_bs` date NOT NULL,
  `jumlah` int NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stok_bs`
--

INSERT INTO `stok_bs` (`id_bs`, `id_produk`, `tanggal_bs`, `jumlah`, `keterangan`) VALUES
(4, 8, '2025-07-16', 2, ''),
(5, 11, '2025-07-16', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `opname_produk`
--
ALTER TABLE `opname_produk`
  ADD PRIMARY KEY (`id_opname`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_pengaturan` (`nama_pengaturan`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `stok_bs`
--
ALTER TABLE `stok_bs`
  ADD PRIMARY KEY (`id_bs`),
  ADD KEY `id_produk` (`id_produk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `opname_produk`
--
ALTER TABLE `opname_produk`
  MODIFY `id_opname` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  MODIFY `id_riwayat` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_bs`
--
ALTER TABLE `stok_bs`
  MODIFY `id_bs` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `opname_produk`
--
ALTER TABLE `opname_produk`
  ADD CONSTRAINT `opname_produk_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD CONSTRAINT `riwayat_stok_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `stok_bs`
--
ALTER TABLE `stok_bs`
  ADD CONSTRAINT `stok_bs_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
