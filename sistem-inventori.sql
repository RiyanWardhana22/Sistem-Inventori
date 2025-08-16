-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 16, 2025 at 06:32 AM
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
-- Table structure for table `bahan`
--

CREATE TABLE `bahan` (
  `nama_bahan` varchar(255) NOT NULL,
  `jumlah_bahan` int NOT NULL,
  `satuan_jumlah` varchar(255) NOT NULL,
  `tanggal_expired` date DEFAULT NULL,
  `status` enum('Layak','Rusak','Expired') NOT NULL DEFAULT 'Layak'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bahan`
--

INSERT INTO `bahan` (`nama_bahan`, `jumlah_bahan`, `satuan_jumlah`, `tanggal_expired`, `status`) VALUES
('Sumpit', 10, 'buah', NULL, 'Rusak'),
('Tepung Kunci', 10, 'kg', '2026-03-12', 'Layak'),
('Tepung Maizena', 15, 'kg', '2026-01-01', 'Layak'),
('Tepung Segitiga Biru', 40, 'kg', '2025-12-31', 'Expired'),
('Toples 300ml', 20, 'buah', NULL, 'Layak');

-- --------------------------------------------------------

--
-- Table structure for table `bs_bahan`
--

CREATE TABLE `bs_bahan` (
  `id_bs` int NOT NULL,
  `nama_bahan` varchar(255) NOT NULL,
  `tanggal_bs` date DEFAULT NULL,
  `jumlah_bahan` int NOT NULL,
  `satuan_jumlah` varchar(255) NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bs_bahan`
--

INSERT INTO `bs_bahan` (`id_bs`, `nama_bahan`, `tanggal_bs`, `jumlah_bahan`, `satuan_jumlah`, `keterangan`) VALUES
(12, 'Tepung Segitiga Biru', '2025-08-13', 10, 'kg', 'Bahan Expired'),
(13, 'Sumpit', '2025-08-13', 5, 'buah', 'Rusak');

-- --------------------------------------------------------

--
-- Table structure for table `opname_bahan`
--

CREATE TABLE `opname_bahan` (
  `id_opname` int NOT NULL,
  `nama_bahan` varchar(255) NOT NULL,
  `tanggal_opname` date NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `stok_awal` varchar(255) DEFAULT NULL,
  `stok_akhir` varchar(255) DEFAULT NULL,
  `penggunaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `bs` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `opname_bahan`
--

INSERT INTO `opname_bahan` (`id_opname`, `nama_bahan`, `tanggal_opname`, `kode`, `stok_awal`, `stok_akhir`, `penggunaan`, `bs`) VALUES
(2, 'Sumpit', '2025-08-13', NULL, '10', '0', '5', '5'),
(3, 'Tepung Segitiga Biru', '2025-08-13', 'OP0001', '40', '20', '10', '10');

-- --------------------------------------------------------

--
-- Table structure for table `opname_produk`
--

CREATE TABLE `opname_produk` (
  `id_opname` int NOT NULL,
  `kode_produk` varchar(50) NOT NULL,
  `tanggal_opname` date NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `stok_awal` varchar(255) DEFAULT NULL,
  `stok_akhir` varchar(255) DEFAULT NULL,
  `penjualan` varchar(255) DEFAULT NULL,
  `bs` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `opname_produk`
--

INSERT INTO `opname_produk` (`id_opname`, `kode_produk`, `tanggal_opname`, `nama_produk`, `kode`, `stok_awal`, `stok_akhir`, `penjualan`, `bs`) VALUES
(3, '98687', '2025-08-12', 'Kue Pisang', '25KPIS', '43', '16', '14', '13'),
(6, '3344', '2025-08-13', 'Kue Coklat', '25KCOK', '50', '25', '20', '5'),
(7, '1001', '2025-08-13', 'Kue Strawberry', '25KSTW', '50', '45', '0', '5'),
(8, '1002', '2025-08-13', 'Bolu Bakar', '25BBKR', '25', '20', '0', '5'),
(9, '1003', '2025-08-13', 'Kue Ulang Tahun', '25KUTH', '15', '0', '15', '0');

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
(2, 'oka', '$2y$10$LKZddb1Ko0WSAGLEjX.tve0lfmAZv1je3KRxBZiJO1CVCoW8rKEJq', 'Oka Gaming', 'admin'),
(3, 'yusuf_aja', '$2y$10$BQLUoyYsluDeASr7BLrbqezaOvXN8aMPN2d9z6odTwIzxz96.zIo6', 'AHMAD YUSUF AL-HAFIZ', 'admin'),
(4, 'awak', '$2y$10$RmOhNcUIg.pIP2hHP60UpOMgd.2NlrNyQafRuk3Bj9vIxnWit4jZy', 'awak', 'pegawai');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `kode_produk` varchar(50) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `jumlah_produk` int NOT NULL,
  `tanggal_produksi` date NOT NULL,
  `tanggal_expired` date NOT NULL,
  `harga_produk` int NOT NULL,
  `keterangan` enum('Layak','Expired') NOT NULL DEFAULT 'Layak'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`kode_produk`, `nama_produk`, `jumlah_produk`, `tanggal_produksi`, `tanggal_expired`, `harga_produk`, `keterangan`) VALUES
('1001', 'Kue Strawberry', 50, '2025-08-15', '2025-10-15', 15000, 'Layak'),
('1002', 'Bolu Bakar', 25, '2025-08-13', '2025-08-20', 20000, 'Layak'),
('1003', 'Kue Ulang Tahun', 15, '2025-10-15', '2025-12-15', 45000, 'Layak'),
('3344', 'Kue Coklat', 50, '2025-08-12', '2025-08-17', 10000, 'Layak'),
('98687', 'Kue Pisang', 43, '2025-08-15', '2025-10-15', 25000, 'Layak');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_stok`
--

CREATE TABLE `riwayat_stok` (
  `id_riwayat` int NOT NULL,
  `kode_produk` varchar(50) NOT NULL,
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
  `kode_produk` varchar(50) NOT NULL,
  `tanggal_bs` date NOT NULL,
  `jumlah` int NOT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stok_bs`
--

INSERT INTO `stok_bs` (`id_bs`, `kode_produk`, `tanggal_bs`, `jumlah`, `keterangan`) VALUES
(7, '98687', '2025-08-12', 13, 'Expired'),
(8, '1001', '2025-08-12', 5, 'Rusak'),
(9, '1002', '2025-08-13', 5, 'Gosong'),
(10, '3344', '2025-08-13', 5, 'Barang rusak karena terjatuh');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`nama_bahan`);

--
-- Indexes for table `bs_bahan`
--
ALTER TABLE `bs_bahan`
  ADD PRIMARY KEY (`id_bs`),
  ADD KEY `nama_bahan` (`nama_bahan`);

--
-- Indexes for table `opname_bahan`
--
ALTER TABLE `opname_bahan`
  ADD PRIMARY KEY (`id_opname`),
  ADD KEY `nama_bahan` (`nama_bahan`);

--
-- Indexes for table `opname_produk`
--
ALTER TABLE `opname_produk`
  ADD PRIMARY KEY (`id_opname`),
  ADD KEY `id_opname` (`kode_produk`);

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
  ADD PRIMARY KEY (`kode_produk`);

--
-- Indexes for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_riwayat` (`kode_produk`);

--
-- Indexes for table `stok_bs`
--
ALTER TABLE `stok_bs`
  ADD PRIMARY KEY (`id_bs`),
  ADD KEY `id_bs` (`kode_produk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bs_bahan`
--
ALTER TABLE `bs_bahan`
  MODIFY `id_bs` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `opname_bahan`
--
ALTER TABLE `opname_bahan`
  MODIFY `id_opname` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `opname_produk`
--
ALTER TABLE `opname_produk`
  MODIFY `id_opname` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  MODIFY `id_riwayat` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_bs`
--
ALTER TABLE `stok_bs`
  MODIFY `id_bs` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
