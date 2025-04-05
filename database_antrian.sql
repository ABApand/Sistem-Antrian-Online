-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2025 at 05:56 AM
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
-- Database: `antrian`
--

-- --------------------------------------------------------

--
-- Table structure for table `antrian_bojongsari`
--

CREATE TABLE `antrian_bojongsari` (
  `id` int(11) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `generated_number` int(11) DEFAULT NULL,
  `date` date NOT NULL DEFAULT curdate(),
  `tanggal` date DEFAULT curdate(),
  `random_number` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `telepon` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `antrian_karangsatria`
--

CREATE TABLE `antrian_karangsatria` (
  `id` int(11) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `generated_number` int(11) DEFAULT NULL,
  `date` date NOT NULL DEFAULT curdate(),
  `tanggal` date DEFAULT curdate(),
  `random_number` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `telepon` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_antrian_bojongsari`
--

CREATE TABLE `log_antrian_bojongsari` (
  `id` int(11) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `random_number` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_antrian_karangsatria`
--

CREATE TABLE `log_antrian_karangsatria` (
  `id` int(11) NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `random_number` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_radius`
--

CREATE TABLE `lokasi_radius` (
  `id` int(11) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `radius` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi_radius`
--

INSERT INTO `lokasi_radius` (`id`, `lokasi`, `radius`) VALUES
(1, 'karang_satria', 50),
(2, 'bojongsari', 50);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`) VALUES
(1, 'jam_reset', '07:20'),
(2, 'admin_pin', '1111');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antrian_bojongsari`
--
ALTER TABLE `antrian_bojongsari`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_id` (`device_id`,`date`);

--
-- Indexes for table `antrian_karangsatria`
--
ALTER TABLE `antrian_karangsatria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_id` (`device_id`,`date`);

--
-- Indexes for table `log_antrian_bojongsari`
--
ALTER TABLE `log_antrian_bojongsari`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_antrian_karangsatria`
--
ALTER TABLE `log_antrian_karangsatria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi_radius`
--
ALTER TABLE `lokasi_radius`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `antrian_bojongsari`
--
ALTER TABLE `antrian_bojongsari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `antrian_karangsatria`
--
ALTER TABLE `antrian_karangsatria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_antrian_bojongsari`
--
ALTER TABLE `log_antrian_bojongsari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `log_antrian_karangsatria`
--
ALTER TABLE `log_antrian_karangsatria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lokasi_radius`
--
ALTER TABLE `lokasi_radius`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
