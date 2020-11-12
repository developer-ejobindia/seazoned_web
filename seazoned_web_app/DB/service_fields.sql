-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2018 at 02:07 PM
-- Server version: 5.6.24
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seazoned_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `service_fields`
--

CREATE TABLE `service_fields` (
  `id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service_fields`
--

INSERT INTO `service_fields` (`id`, `field_name`, `description`) VALUES
(1, 'lawn_area', NULL),
(2, 'grass_length', NULL),
(3, 'no_of_cars', NULL),
(4, 'driveway_type', NULL),
(5, 'service_type', NULL),
(6, 'leaf_accumulation', NULL),
(7, 'water_type', NULL),
(8, 'include_spa', NULL),
(9, 'pool_type', NULL),
(10, 'pool_state', NULL),
(11, 'no_of_zones', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `service_fields`
--
ALTER TABLE `service_fields`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `service_fields`
--
ALTER TABLE `service_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
