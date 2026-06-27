-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql202.infinityfree.com
-- Generation Time: Jun 26, 2026 at 08:28 PM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41299182_cloudspaceph`
--

-- --------------------------------------------------------

--
-- Table structure for table `cloudspaceph_users`
--

CREATE TABLE `cloudspaceph_users` (
  `id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `passoword` varchar(255) NOT NULL,
  `phone_number` int(255) DEFAULT NULL,
  `date_created` date NOT NULL,
  `date_updated` date DEFAULT NULL,
  `account_type` int(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cloudspaceph_users`
--

INSERT INTO `cloudspaceph_users` (`id`, `username`, `passoword`, `phone_number`, `date_created`, `date_updated`, `account_type`, `email`) VALUES
(5, 'wut@gmail.com', '$2y$10$PTq5mpEQ8Yrv21pP8IMOouUzvoJoRUq8FpYuyLMiW51XwRbhAk90e', NULL, '2026-06-25', NULL, 1, 'wut@gmail.com'),
(4, 'kylepro32chanel@gmail.com', '$2y$10$Opw6EmsnNeXGNWG2.6RsZ.V3u6cM27n1ZY6BgK3AQm916br96rWCm', NULL, '2026-06-25', NULL, 1, 'kylepro32chanel@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cloudspaceph_users`
--
ALTER TABLE `cloudspaceph_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cloudspaceph_users`
--
ALTER TABLE `cloudspaceph_users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
