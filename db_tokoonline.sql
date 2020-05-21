-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2020 at 06:14 AM
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
  `POS_TUJUAN` varchar(5) NOT NULL,
  `ID_TRANSAKSI` varchar(30) NOT NULL,
  `ONGKIR_PERKG` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `ID_TRANSAKSI` varchar(30) NOT NULL,
  `TGL_TRANSAKSI` datetime DEFAULT NULL,
  `NAMA_PEMBELI` varchar(30) DEFAULT NULL,
  `NO_HP` varchar(15) DEFAULT NULL,
  `ALAMAT` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(10) NOT NULL,
  `kode_produk` varchar(10) NOT NULL,
  `nama_produk` varchar(30) DEFAULT NULL,
  `stok_produk` int(11) DEFAULT NULL,
  `harga_produk` float DEFAULT NULL,
  `berat_produk` int(5) DEFAULT NULL,
  `file_foto` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `proses_jual`
--

CREATE TABLE `proses_jual` (
  `ID_TRANSAKSI` varchar(30) NOT NULL,
  `ID_PRODUK` int(10) NOT NULL,
  `HARGA_PRODUK` float DEFAULT NULL,
  `JUMLAH_PRDUK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ongkir`
--
ALTER TABLE `ongkir`
  ADD PRIMARY KEY (`POS_TUJUAN`),
  ADD KEY `FK_MENENTUKAN_ONGKIR` (`ID_TRANSAKSI`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`ID_TRANSAKSI`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

ALTER TABLE `produk`
  MODIFY `id_produk` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;
--
-- Indexes for table `proses_jual`
--
ALTER TABLE `proses_jual`
  ADD PRIMARY KEY (`ID_TRANSAKSI`,`ID_PRODUK`),
  ADD KEY `FK_PROSES_JUAL2` (`ID_PRODUK`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ongkir`
--
ALTER TABLE `ongkir`
  ADD CONSTRAINT `FK_MENENTUKAN_ONGKIR` FOREIGN KEY (`ID_TRANSAKSI`) REFERENCES `penjualan` (`ID_TRANSAKSI`);

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
