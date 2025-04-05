-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2025 at 03:58 AM
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

--
-- Dumping data for table `antrian_bojongsari`
--

INSERT INTO `antrian_bojongsari` (`id`, `device_id`, `generated_number`, `date`, `tanggal`, `random_number`, `nama`, `ip_address`, `telepon`) VALUES
(116, '6d7e96b103f1e45c86ec607cd40250f6', NULL, '2025-04-05', '2025-04-05', 6, 'Apand', '::1', '32432414');

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

--
-- Dumping data for table `antrian_karangsatria`
--

INSERT INTO `antrian_karangsatria` (`id`, `device_id`, `generated_number`, `date`, `tanggal`, `random_number`, `nama`, `ip_address`, `telepon`) VALUES
(120, '6d7e96b103f1e45c86ec607cd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsad', '::1', 'asdsa213213'),
(121, '6d7e96b103f1e45c86ec607casdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsad', '::1', 'asdsa213213'),
(122, '6d7e96b103f1e45c86ec607cadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(123, '6d7e96b103f1e4asdsad5c86ec607cadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(124, '6d7e96b103f1e45c86ec607cadasdsadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(125, '6d7e96b103f1e45c86ec607casdsadadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(126, '6d7e96b103f1e45casdsadsad86ec607casdsadadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(127, '6d7e96b103f1e45c86ec607cadasdsadsadsaasdsadsadd40250f6sadsad', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(128, '6d7e96b103f1e45c86ec607casdasdadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(129, '6d7e96b103f1e45casdsadsasdsadad86ec607casdsadadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(130, '6d7e96b103f1e45casdasdasdsasadsasdsadad86ec607casdsadadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(131, '6d7e96b103f1e45c86ec607cassadasdasddsadadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(132, '6d7e96b103f1e45c86ec607csadsadsadasdsadadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(133, '6d7e96b103f1e4asdsadasdsad5c86ec607cadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(134, '6d7e96b103f1e45c86ec607cadasdsadsadsaasdsadsadd40250f6asdsad', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(135, '6d7e96b103fsadasdsad1e45casdsadsad86ec607casdsadadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(136, '6d7e96b103fsadsadsa1e4asdsad5c86ec607cadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(137, '6d7e96b103f1e45casdasdasdsasadsasdsadad86ec607casdsadadsadasdsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(138, '6d7e96b103f1easdsad45c86ec607csadsadsadasdsadadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(139, '6d7e96b103asdsadsafsadsadsa1e4asdsad5c86ec607cadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(140, '6d7e96b1asdsadsa03fsadsadsa1e4asdsad5c86ec607cadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(141, '6d7e96b103fsadsadadsadsa1e4asdsad5c86ec607cadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(142, '6d7e96basdsadsad103f1e45c86ec607csadsadsadasdsadadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(143, '6d7e96basdsada1asdsadsa03fsadsadsa1e4asdsad5c86ec607cadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(144, '6d7e96b103fasdsadas1easdsad45c86ec607csadsadsadasdsadadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(145, '6d7e9asdsad6b103f1e45c86ec607casdasdadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(146, '6d7e96b103f1e45casdasdasdsasadsasdsadad86ec607casdsadadsaasdsadaddasdsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(147, '6d7e96asdsadab103fsadsadadsadsa1e4asdsad5c86ec607cadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213'),
(148, '6d7e96b1asdsadaasdsadsa03fsadsadsa1e4asdsad5c86ec607cadsadsaasdsadsadd40250f6', NULL, '2025-04-05', '2025-04-05', 9, 'asdsadsadsadsadasdsadsad', '::1', 'asdsa213213');

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

--
-- Dumping data for table `log_antrian_karangsatria`
--

INSERT INTO `log_antrian_karangsatria` (`id`, `device_id`, `ip_address`, `random_number`, `nama`, `telepon`, `tanggal`, `timestamp`) VALUES
(44, '6d7e96b103f1e45c86ec607cd40250f6', '::1', 9, 'apand', '0829849324', '2025-04-04', '2025-04-04 08:17:52');

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
(1, 'jam_reset', '07:20');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `log_antrian_bojongsari`
--
ALTER TABLE `log_antrian_bojongsari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `log_antrian_karangsatria`
--
ALTER TABLE `log_antrian_karangsatria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `lokasi_radius`
--
ALTER TABLE `lokasi_radius`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
