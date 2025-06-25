-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 03:40 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apotek_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id_obat` int(11) NOT NULL,
  `nama_obat` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `tgl_kadaluarsa` date NOT NULL,
  `kadaluarsa` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id_obat`, `nama_obat`, `kategori`, `satuan`, `harga`, `deskripsi`, `stok`, `tgl_kadaluarsa`, `kadaluarsa`) VALUES
(1, 'Paracetamol', 'Analgesik', 'Strip', 5000, 'Obat penurun demam dan nyeri', -6, '2025-12-31', NULL),
(2, 'Amoxicillin', 'Antibiotik', 'Strip', 8000, 'Antibiotik untuk infeksi bakteri', 5, '2025-12-31', '2025-12-31'),
(5, 'Amoxicillin', 'tablet', 'Strip', 12000, 'Obat antibiotik', 30, '2025-12-31', '2025-12-31'),
(6, 'Paracetamol', 'Analgesik', 'Tablet', 5000, 'Obat penurun demam', 50, '2025-11-30', NULL),
(7, 'Cetirizine', 'Antihistamin', 'Tablet', 6000, 'Obat alergi', 40, '2026-01-15', NULL),
(8, 'Ibuprofen', 'Analgesik', 'Tablet', 7000, 'Pereda nyeri dan inflamasi', 24, '2026-02-28', NULL),
(9, 'Vitamin C', 'Suplemen', 'Tablet', 3000, 'Vitamin harian', 17, '2025-09-15', NULL),
(10, 'Metformin', 'Antidiabetik', 'Tablet', 9000, 'Obat diabetes', 45, '2025-10-20', NULL),
(11, 'Omeprazole', 'Antasida', 'Kapsul', 8000, 'Obat lambung', 16, '2026-03-10', NULL),
(12, 'Loperamide', 'Antidiare', 'Tablet', 4000, 'Obat diare', 25, '2025-08-25', NULL),
(13, 'Simvastatin', 'Kolesterol', 'Tablet', 9500, 'Obat penurun kolesterol', 32, '2026-01-05', NULL),
(14, 'Dexamethasone', 'Kortikosteroid', 'Tablet', 8500, 'Obat radang', 18, '2025-10-10', NULL),
(15, 'Diazepam', 'Psikotropika', 'Tablet', 10000, 'Obat penenang', -2, '2025-12-20', NULL),
(16, 'Ranitidine', 'Antasida', 'Tablet', 6000, 'Obat maag', 28, '2025-07-15', NULL),
(17, 'Furosemide', 'Diuretik', 'Tablet', 7500, 'Obat tekanan darah', 27, '2025-11-01', NULL),
(18, 'Azithromycin', 'Antibiotik', 'Kapsul', 13000, 'Obat infeksi bakteri', 9, '2026-04-01', NULL),
(19, 'Chloramphenicol', 'Antibiotik', 'Kapsul', 7000, 'Obat infeksi mata', 12, '2025-12-15', NULL),
(20, 'Salbutamol', 'Asma', 'Tablet', 6500, 'Obat asma', 16, '2026-01-12', NULL),
(21, 'Mefenamic Acid', 'Analgesik', 'Tablet', 5500, 'Obat nyeri haid', 30, '2025-09-05', NULL),
(22, 'Antasida DOEN', 'tablet', 'Tablet', 3500, 'Obat lambung', 34, '2025-10-28', NULL),
(23, 'Lansoprazole', 'Antasida', 'Kapsul', 7800, 'Obat tukak lambung', 21, '2025-11-30', NULL),
(24, 'Ciprofloxacin', 'Antibiotik', 'Tablet', 11000, 'Obat infeksi saluran kemih', 25, '2025-12-01', NULL),
(26, 'baygon', 'Antibiotik', NULL, 5000, NULL, 52, '2025-06-16', NULL),
(28, 'Komik', 'tablet', NULL, 1200, NULL, 20, '2025-06-30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `total_harga` decimal(10,2) NOT NULL DEFAULT 0.00,
  `supplier` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `id_obat`, `jumlah`, `tanggal`, `total_harga`, `supplier`) VALUES
(5, 8, 5, '2025-06-19', '11500.00', 'pt kalbe'),
(6, 17, 5, '2025-06-19', '9000.00', 'pt kalbe');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status` enum('Menunggu','Diproses','Selesai','Ditolak') DEFAULT 'Menunggu',
  `notifikasi_dibaca` tinyint(1) NOT NULL DEFAULT 0,
  `total_harga` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `id_user`, `id_obat`, `jumlah`, `tanggal`, `bukti_pembayaran`, `status`, `notifikasi_dibaca`, `total_harga`) VALUES
(1, 7, 9, 12, '2025-06-16', NULL, 'Diproses', 1, '36000.00'),
(2, 7, 11, 2, '2025-06-16', '1750060499_XA.png', 'Selesai', 1, '16000.00'),
(3, 7, 15, 5, '2025-06-16', '1750063434_qr_1_2025-05-10.png', 'Selesai', 1, '50000.00'),
(4, 9, 26, 2, '2025-06-17', '1750126318_XA.png', 'Selesai', 1, '10000.00'),
(5, 9, 9, 6, '2025-06-17', '1750129357_qr_3_2025-05-10.png', 'Selesai', 1, '18000.00'),
(7, 10, 2, 9, '2025-06-17', '1750140810_qr_1_2025-05-10.png', 'Selesai', 1, '72000.00'),
(8, 11, 2, 10, '2025-06-17', '1750169523_XA.png', 'Selesai', 1, '80000.00'),
(9, 13, 9, 10, '2025-06-19', '1750314106_qr_3_2025-05-10.png', 'Selesai', 1, '30000.00'),
(10, 14, 8, 5, '2025-06-19', '1750335271_qr_1_2025-05-10 (1).png', 'Selesai', 1, '35000.00'),
(11, 14, 1, 2, '2025-06-19', '1750335862_qr_3_2025-05-10.png', 'Selesai', 1, '10000.00'),
(12, 14, 9, 3, '2025-06-19', '1750336820_XA.png', 'Selesai', 1, '9000.00'),
(13, 13, 15, 2, '2025-06-19', '1750337066_qr_3_2025-05-10.png', 'Diproses', 1, '20000.00'),
(14, 7, 24, 2, '2025-06-19', '1750338027_WhatsApp Image 2025-05-21 at 14.02.29.jpeg', 'Selesai', 1, '22000.00');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `total_harga` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `id_obat`, `jumlah`, `tanggal`, `total_harga`) VALUES
(2, 26, 4, '2025-06-16', '20000.00'),
(3, 18, 6, '2025-06-16', '78000.00'),
(4, 26, 2, '2025-06-17', '10000.00'),
(5, 2, 10, '2025-06-17', '80000.00'),
(6, 2, 2, '2025-06-19', '16000.00'),
(7, 2, 2, '2025-06-19', '16000.00'),
(8, 2, 5, '2025-06-23', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `user_id`, `id_obat`, `jumlah`, `total_harga`, `tanggal`, `status`) VALUES
(1, 7, 26, 5, '0.00', '2025-06-19', 'approved'),
(2, 25, 2, 6, '0.00', '2025-06-23', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `stok_obat`
--

CREATE TABLE `stok_obat` (
  `id_stok` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl_kadaluarsa` date NOT NULL,
  `tgl_masuk` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stok_obat`
--

INSERT INTO `stok_obat` (`id_stok`, `id_obat`, `jumlah`, `tgl_kadaluarsa`, `tgl_masuk`) VALUES
(1, 1, 100, '2025-12-01', '2025-06-16'),
(2, 1, 50, '2026-03-15', '2025-06-16'),
(3, 2, 30, '2025-11-01', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','kasir','gudang','pelanggan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`) VALUES
(1, 'messi', 'messi', '$2y$10$COPkx3/SGA7wlwR/I4Ec7esPxkJK8H1bH9obSIdYTel/Y5qnwYE6y', 'pelanggan'),
(2, 'botak', 'botak', '$2y$10$5e3RleGTeuSspx11x.IWqucFyO9l40YmYq78pNiwRKlRAm4F4CEvS', 'admin'),
(4, 'boas', 'boas', '$2y$10$AvabylQ8.47Qd.3dTIjA5.cGYDXDnz5InHi7OaoMmP8.XOEknWwt6', 'gudang'),
(5, 'wati', 'wati', '$2y$10$GQkrsUFS/PO/E0bfzyKY3eVDfKxM1YtnQforM1H2XDV7Sl5URoQAu', 'kasir'),
(6, 'budi', 'budi', '$2y$10$mrHIohP6pgfHsDFsot7.5OHTMpmnVTA15ubQa26vcLeV66vslrOGS', 'kasir'),
(7, 'abid', 'abid', '$2y$10$7w1e0keEUy80hB2LlRKunenRJtNuascGgNqdkmts5dh1BxznyT7Du', 'pelanggan'),
(8, 'suri', 'suri', '$2y$10$iWibP/UgcRkxBk.BVsyzKeZYWQwTcKiIjo6HNSEfXYZEfScjxYPEC', 'gudang'),
(9, 'Dewi Angin', 'dewi', '$2y$10$wYiD5.tYgQjrLfBvklIVCupA60Ia3qBzt77jB06WHtcxcP4LQz0pC', 'pelanggan'),
(10, 'dora', 'dora', '$2y$10$bFQpaI8t6Kn4JhFHXz5xdOzVprK/bKw/FmijwcdRfud2ze5Klf4Wy', 'pelanggan'),
(11, 'bos', 'bos', '$2y$10$Kyrm3fJE4AcwimguieTgp.WNDtbHEoz7jdpHHADfXgChhmvXlZm8q', 'pelanggan'),
(12, 'SUPARMAN', 'super', '$2y$10$Jmiaf89XuWoWnt3I6A6gVe2tbA8Syexl.5ZnlPMOVR9c4NTg7N3rC', 'gudang'),
(13, 'FITRI', 'fitri', '$2y$10$qTGSk8CFRVqgzIiyQhrpz.RV6WzUZJOkkKxnwlTsVd8OZDxF4W17i', 'pelanggan'),
(14, 'puspa', 'puspa', '$2y$10$TSDmSYaDCM4HfKhStdtB7OaykFj7FyJ/9Fdw7H7LGcHlsTmYRVnmq', 'pelanggan'),
(15, 'joko', 'joko', '$2y$10$PjTixPpVzW866OqoAZlMCeCrT1FxaCU5nTxSoZv/hhB.Zawt2kZPe', 'gudang'),
(16, 'bobo', 'bobo', '$2y$10$MvY.9CMDDz8g/OgfimUR4e0vHUuENhKYsec26F3aBfqfEdEQoMkfG', 'gudang'),
(17, 'bowo', 'bowo', '$2y$10$U45WMScJzRH6xgkBAIEr..BEGKC9ekJEFAFzrrdA7DgLx5S5jZ15.', 'gudang'),
(18, 'megawati', 'mega', '$2y$10$VJVwpP2tQp1ogmbGHjd10eF/x.dSiw4Md2dZITOTI8k9C8jcSSs9u', 'kasir'),
(20, 'JERUK', 'JERUK', '$2y$10$KhLL0wzzWKTu1k9URyzOEuyeYf2J.SdaRWlXzATpfTfdKoDUQWQv.', 'kasir'),
(21, 'MARTIN', 'MARTIN', '$2y$10$yNJ.1EW6aku3BLyaeOZ11eG2YHM/lCEfkG7VKZ7NpIRnG1Jwc1TfG', 'pelanggan'),
(23, 'PRABOWO', 'subianto', '$2y$10$OVubXREiDpdtO1Q0YLxttuVJyf7EDUNznOdgaGG7VpZOuizoRfx6K', 'admin'),
(24, 'bambang', 'bejo', '$2y$10$7SCYTpmd/hVyZSuVDwfqe.yrKZnes7DudwMNi6tMGbTynTDldWUWK', 'gudang'),
(25, 'JUANDA', 'juanda', '$2y$10$aDBdfzozbwJqh5Apf5UG0u4beUqDF.3M/EEKeblBD7Si4/gO1SKsm', 'pelanggan'),
(26, 'wati', 'wati1', '$2y$10$r4aQhQPas6.ZBSqwIt.20uXUCtKLeBZay/nq41FR4HV4zAIE.o8cq', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `stok_obat`
--
ALTER TABLE `stok_obat`
  ADD PRIMARY KEY (`id_stok`),
  ADD KEY `id_obat` (`id_obat`);

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
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stok_obat`
--
ALTER TABLE `stok_obat`
  MODIFY `id_stok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`);

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`);

--
-- Constraints for table `stok_obat`
--
ALTER TABLE `stok_obat`
  ADD CONSTRAINT `stok_obat_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
