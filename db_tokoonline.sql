-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 23, 2020 at 04:23 AM
-- Server version: 10.3.22-MariaDB-1ubuntu1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_tokoonline`
--

-- --------------------------------------------------------

--
-- Table structure for table `ongkir`
--

CREATE TABLE `ongkir` (
  `ID_POS` int(11) NOT NULL,
  `KODE_POS_TUJUAN` varchar(10) NOT NULL,
  `KODE_POS_ASAL` varchar(10) NOT NULL,
  `biaya_ongkir` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ongkir`
--

INSERT INTO `ongkir` (`ID_POS`, `KODE_POS_TUJUAN`, `KODE_POS_ASAL`, `biaya_ongkir`) VALUES
(1, '40151', '29464', 30000),
(2, '60271', '29464', 45000);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `ID_TRANSAKSI` varchar(30) NOT NULL,
  `TGL_TRANSAKSI` date DEFAULT NULL,
  `NAMA_PEMBELI` varchar(30) DEFAULT NULL,
  `NO_HP` varchar(15) DEFAULT NULL,
  `ALAMAT` text DEFAULT NULL,
  `TOTAL_PEMBAYARAN` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`ID_TRANSAKSI`, `TGL_TRANSAKSI`, `NAMA_PEMBELI`, `NO_HP`, `ALAMAT`, `TOTAL_PEMBAYARAN`) VALUES
('TRX00001', '2020-05-23', 'Irvan Kadhafi', '081927145985', 'xdda', 585000);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `ID_PRODUK` int(10) NOT NULL,
  `KODE_PRODUK` varchar(10) NOT NULL,
  `NAMA_PRODUK` varchar(30) DEFAULT NULL,
  `STOK_PRODUK` int(11) DEFAULT NULL,
  `HARGA_PRODUK` float DEFAULT NULL,
  `BERAT_PRODUK` float DEFAULT NULL,
  `FILE_FOTO` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`ID_PRODUK`, `KODE_PRODUK`, `NAMA_PRODUK`, `STOK_PRODUK`, `HARGA_PRODUK`, `BERAT_PRODUK`, `FILE_FOTO`) VALUES
(1, 'BRG001', 'Oreo Supreme', 4, 500000, 1, 'oreo_supreme-62.jpg'),
(2, 'BRG002', 'Bibit Lele', 100, 80000, 15, 'Bibit Lele-42.jpg'),
(3, 'BRG003', 'Kaos Putih', 14, 85000, 1, 'Kaos-51.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `proses_jual`
--

CREATE TABLE `proses_jual` (
  `ID_TRANSAKSI` varchar(30) NOT NULL,
  `ID_PRODUK` int(10) NOT NULL,
  `HARGA_PRODUK` float DEFAULT NULL,
  `JUMLAH_PRODUK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `proses_jual`
--

INSERT INTO `proses_jual` (`ID_TRANSAKSI`, `ID_PRODUK`, `HARGA_PRODUK`, `JUMLAH_PRODUK`) VALUES
('TRX00001', 1, 500000, 1),
('TRX00001', 3, 85000, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ongkir`
--
ALTER TABLE `ongkir`
  ADD PRIMARY KEY (`ID_POS`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`ID_TRANSAKSI`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`ID_PRODUK`);

--
-- Indexes for table `proses_jual`
--
ALTER TABLE `proses_jual`
  ADD PRIMARY KEY (`ID_TRANSAKSI`,`ID_PRODUK`),
  ADD KEY `FK_PROSES_JUAL2` (`ID_PRODUK`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ongkir`
--
ALTER TABLE `ongkir`
  MODIFY `ID_POS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `ID_PRODUK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `proses_jual`
--
ALTER TABLE `proses_jual`
  ADD CONSTRAINT `FK_PROSES_JUAL` FOREIGN KEY (`ID_TRANSAKSI`) REFERENCES `penjualan` (`ID_TRANSAKSI`),
  ADD CONSTRAINT `FK_PROSES_JUAL2` FOREIGN KEY (`ID_PRODUK`) REFERENCES `produk` (`ID_PRODUK`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
