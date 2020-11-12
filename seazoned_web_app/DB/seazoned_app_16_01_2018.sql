-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 16, 2018 at 04:36 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
-- Table structure for table `address_books`
--

CREATE TABLE `address_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` longtext NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) NOT NULL,
  `country` int(11) DEFAULT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `primary_address` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `address_books`
--

INSERT INTO `address_books` (`id`, `user_id`, `name`, `address`, `city`, `state`, `country`, `contact_number`, `email_address`, `primary_address`) VALUES
(1, 1, 'Tom Wilson', '29 Sleepy Hollow Drive', 'Hamilton Parish', 'Bermuda', 1, '(123) 123-456', 'office@example.com', 1),
(2, 1, 'Mike Doe', '12345 Little Lonsdale St', 'Melbourne', 'Melbourne', 1, '(123) 123-456', 'office@example.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `book_services`
--

CREATE TABLE `book_services` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `landscaper_id` int(11) NOT NULL,
  `address_book_id` int(11) DEFAULT NULL,
  `service_price_id` int(11) DEFAULT NULL,
  `service_date` date NOT NULL,
  `service_time` time NOT NULL,
  `additional_note` text,
  `service_price` decimal(10,2) NOT NULL,
  `lawn_area` varchar(100) DEFAULT NULL,
  `grass_length` varchar(100) DEFAULT NULL,
  `no_of_cars` int(11) DEFAULT NULL,
  `driveway_type` varchar(100) DEFAULT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `leaf_accumulation` varchar(100) DEFAULT NULL,
  `water_type` varchar(100) DEFAULT NULL,
  `include_spa` varchar(100) DEFAULT NULL,
  `pool_type` varchar(100) DEFAULT NULL,
  `pool_state` varchar(100) DEFAULT NULL,
  `no_of_zones` int(11) DEFAULT NULL,
  `order_no` varchar(100) DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `book_services`
--

INSERT INTO `book_services` (`id`, `customer_id`, `landscaper_id`, `address_book_id`, `service_price_id`, `service_date`, `service_time`, `additional_note`, `service_price`, `lawn_area`, `grass_length`, `no_of_cars`, `driveway_type`, `service_type`, `leaf_accumulation`, `water_type`, `include_spa`, `pool_type`, `pool_state`, `no_of_zones`, `order_no`, `completion_date`, `status`) VALUES
(7, 1, 2, 2, NULL, '0000-00-00', '08:25:00', 'Blah Blah', '24.00', '0.75 - 1', NULL, NULL, NULL, NULL, 'Over the top', NULL, NULL, NULL, NULL, NULL, 'OD1516014237963034', NULL, 0),
(8, 1, 1, 1, NULL, '0000-00-00', '02:05:00', 'Some Data', '16.00', '0.25 - 0.5', '>6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'OD1516016734367881', NULL, 1),
(9, 1, 5, 1, NULL, '2018-01-20', '06:55:00', 'Clean It', '58.00', NULL, NULL, 4, 'Incline', 'Stairs and Front Landing', NULL, NULL, NULL, NULL, NULL, NULL, 'OD1516031821149344', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `country_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_name`) VALUES
(1, 'USA'),
(2, 'UK'),
(3, 'India'),
(4, 'Australia');

-- --------------------------------------------------------

--
-- Table structure for table `favorite_landscapers`
--

CREATE TABLE `favorite_landscapers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `landscaper_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `landscapers`
--

CREATE TABLE `landscapers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `profile_image` longtext,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `landscapers`
--

INSERT INTO `landscapers` (`id`, `user_id`, `service_id`, `name`, `description`, `profile_image`, `location`) VALUES
(1, 2, 1, 'Grass Cleaner Company', 'Grass Cleaner', '4782_1516001367_Avatar_2.png', 'Bidhannagar, WB, India'),
(2, 3, 2, 'Leaf Cleaner Corporation', 'Leaf Cleaning Service', NULL, 'Jalpaiguri, WB, India'),
(3, 4, 5, 'Winter Zone', 'Winter Cleaning....', '3774_1516022136_Avatar_1.png', 'Kolkata, WB, India'),
(4, 5, 6, 'Pool Cleaner', 'Pool Cleaner', NULL, 'Jalpaiguri, WB, India'),
(5, 6, 7, 'Snow Cleaner', 'Snow Cleaner', NULL, 'Kolkata, WB, India'),
(6, 7, 3, 'Lawn Cleaner', 'Lawn Clear Service', NULL, 'Kolkata, WB, India'),
(7, 8, 3, 'Lawn Cleaner', 'Lawn Clear Service', NULL, 'Kolkata, WB, India'),
(8, 9, 4, 'Aeration Service', 'Blah Blah Blah', NULL, 'Kolkata, WB, India');

-- --------------------------------------------------------

--
-- Table structure for table `payment_accounts`
--

CREATE TABLE `payment_accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_details` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `additional_information` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment_percentages`
--

CREATE TABLE `payment_percentages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `percentage` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text,
  `logo_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `description`, `logo_name`) VALUES
(1, 'Mowing And Edging', 'Mowing and Edging', 'mowing.svg'),
(2, 'Leaf Removal', 'Leaf Removal', 'leaf-removal.svg'),
(3, 'Lawn Treatment', 'Lawn Treatment', 'lawn-treatment.svg'),
(4, 'Aeration', 'Aeration', 'aeration.png'),
(5, 'Sprinkler Winterizing', 'Sprinkler Winterizing', 'sprinkler.svg'),
(6, 'Pool Cleaning & Upkeep', 'Pool Cleaning & Upkeep', 'swimming-pool-ladder.svg'),
(7, 'Snow Removal', 'Snow Removal', 'snow-removal.svg');

-- --------------------------------------------------------

--
-- Table structure for table `service_details`
--

CREATE TABLE `service_details` (
  `id` int(11) NOT NULL,
  `landscaper_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_field_id` int(11) NOT NULL,
  `service_field_value` varchar(255) NOT NULL,
  `service_field_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service_details`
--

INSERT INTO `service_details` (`id`, `landscaper_id`, `service_id`, `service_field_id`, `service_field_value`, `service_field_price`) VALUES
(7, 2, 2, 1, '0 - 0.25', '5.00'),
(8, 2, 2, 1, '0.25 - 0.5', '8.00'),
(9, 2, 2, 1, '0.5 - 0.75', '11.00'),
(10, 2, 2, 1, '0.75 - 1', '14.00'),
(11, 2, 2, 6, 'Light', '2.00'),
(12, 2, 2, 6, 'Medium', '5.00'),
(13, 2, 2, 6, 'Heavy', '7.00'),
(14, 2, 2, 6, 'Over the top', '10.00'),
(15, 3, 5, 1, '0 - 0.25', '5.00'),
(16, 3, 5, 1, '0.25 - 0.5', '8.00'),
(17, 3, 5, 1, '0.5 - 0.75', '11.00'),
(18, 3, 5, 1, '0.75 - 1', '14.00'),
(19, 3, 5, 11, '0 - 3', '8.00'),
(20, 3, 5, 11, '3 - 6', '12.00'),
(21, 4, 6, 7, 'Chlorine', '10.00'),
(22, 4, 6, 7, 'Saline', '5.00'),
(23, 4, 6, 8, 'Yes', '12.00'),
(24, 4, 6, 8, 'No', '0.00'),
(25, 4, 6, 9, 'Inground', '50.00'),
(26, 4, 6, 9, 'Above Ground', '30.00'),
(27, 4, 6, 10, 'Relatively Clear', '10.00'),
(28, 4, 6, 10, 'Moderately Cloudy', '30.00'),
(29, 4, 6, 10, 'Heavy Algae Present', '50.00'),
(38, 6, 3, 1, '0 - 0.25', '5.00'),
(39, 6, 3, 1, '0.25 - 0.5', '8.00'),
(40, 6, 3, 1, '0.5 - 0.75', '11.00'),
(41, 6, 3, 1, '0.75 - 1', '14.00'),
(42, 7, 3, 1, '0 - 0.25', '5.00'),
(43, 7, 3, 1, '0.25 - 0.5', '8.00'),
(44, 7, 3, 1, '0.5 - 0.75', '11.00'),
(45, 7, 3, 1, '0.75 - 1', '14.00'),
(46, 8, 4, 1, '0 - 0.25', '5.00'),
(47, 8, 4, 1, '0.25 - 0.5', '13.00'),
(48, 8, 4, 1, '0.5 - 0.75', '21.00'),
(49, 8, 4, 1, '0.75 - 1', '29.00'),
(50, 8, 4, 1, '1 - 1.25', '37.00'),
(51, 8, 4, 1, '1.25 - 1.5', '45.00'),
(52, 8, 4, 1, '1.5 - 1.75', '53.00'),
(53, 8, 4, 1, '1.75 - 2', '61.00'),
(62, 5, 7, 3, '2', '8.00'),
(63, 5, 7, 4, 'Straight', '5.00'),
(64, 5, 7, 4, 'Circular', '10.00'),
(65, 5, 7, 4, 'Incline', '25.00'),
(66, 5, 7, 5, 'Front Door Walk Way', '2.00'),
(67, 5, 7, 5, 'Stairs and Front Landing', '25.00'),
(68, 5, 7, 5, 'Side Door Walk Way', '10.00'),
(82, 1, 1, 1, '0 - 0.25', '15.00'),
(83, 1, 1, 1, '0.25 - 0.5', '25.00'),
(84, 1, 1, 2, '0 - 6', '10.00'),
(85, 1, 1, 2, '>6', '16.00');

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

-- --------------------------------------------------------

--
-- Table structure for table `service_images`
--

CREATE TABLE `service_images` (
  `id` int(11) NOT NULL,
  `book_service_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `service_image` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `service_payments`
--

CREATE TABLE `service_payments` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `book_service_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `service_prices`
--

CREATE TABLE `service_prices` (
  `id` int(11) NOT NULL,
  `landscaper_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_frequency` varchar(100) NOT NULL,
  `discount_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service_prices`
--

INSERT INTO `service_prices` (`id`, `landscaper_id`, `service_id`, `service_frequency`, `discount_price`) VALUES
(1, 7, 3, 'Every 7 days', '3.00'),
(2, 7, 3, 'Every 10 days', '4.00'),
(3, 7, 3, 'Every 14 days', '5.00'),
(4, 7, 3, 'Just Once', '0.00'),
(5, 8, 4, 'Every 7 days', '2.00'),
(6, 8, 4, 'Every 10 days', '3.00'),
(7, 8, 4, 'Every 14 days', '5.00'),
(8, 8, 4, 'Just Once', '1.00');

-- --------------------------------------------------------

--
-- Table structure for table `service_ratings`
--

CREATE TABLE `service_ratings` (
  `id` int(11) NOT NULL,
  `landscaper_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `initiated_by` int(11) NOT NULL,
  `rating_value` int(11) DEFAULT NULL,
  `review` longtext,
  `log_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `service_times`
--

CREATE TABLE `service_times` (
  `id` int(11) NOT NULL,
  `landscaper_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_day` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service_times`
--

INSERT INTO `service_times` (`id`, `landscaper_id`, `service_id`, `service_day`, `start_time`, `end_time`) VALUES
(1, 1, 1, 'Monday', '12:30:00', '06:30:00'),
(2, 1, 1, 'Wednesday', '12:30:00', '06:30:00'),
(3, 1, 1, 'Friday', '12:30:00', '06:30:00'),
(4, 2, 2, 'Saturday', '08:00:00', '14:00:00'),
(5, 2, 2, 'Sunday', '08:00:00', '14:00:00'),
(6, 3, 5, 'Monday', '10:00:00', '20:00:00'),
(7, 4, 6, 'Monday', '10:00:00', '17:00:00'),
(8, 5, 7, 'Friday', '06:00:00', '15:00:00'),
(9, 6, 3, 'Sunday', '08:00:00', '14:00:00'),
(10, 7, 3, 'Sunday', '08:00:00', '14:00:00'),
(11, 8, 4, 'Wednesday', '08:00:00', '15:00:00'),
(12, 8, 4, 'Friday', '10:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `profile_id`, `username`, `password`, `user_type`, `active`) VALUES
(1, 2, 'jeet.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Users', 1),
(2, 3, 'sauvik.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(3, 3, 'krishnendu.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(4, 3, 'arunava.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(5, 3, 'mou.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(6, 3, 'tanay.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(7, 3, 'aftab.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(8, 3, 'aftab.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(9, 3, 'sourav.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(255) NOT NULL,
  `profile_image` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `first_name`, `last_name`, `email`, `phone_number`, `date_of_birth`, `address`, `city`, `state`, `country`, `profile_image`) VALUES
(1, 1, 'Jeet', 'Chowdhury', 'jeet.mlindia@gmail.com', '9038789974', '1989-02-09', '18, Vidyasagar Lane', 'Konnagar', 'West Bengal', '3', '3913_1516001204_TweakJC.jpg'),
(2, 2, 'Sauvik', 'Dey', 'sauvik.mlindia@gmail.com', '9876543210', NULL, 'Salt Lake City, West Bengal, India', 'Bidhannagar', 'WB', 'India', NULL),
(3, 3, 'Krishnendu', 'Biswas', 'krishnendu.mlindia@gmail.com', '9876543210', NULL, 'Jalpaiguri, West Bengal, India', 'Jalpaiguri', 'WB', 'India', NULL),
(4, 4, 'Arunava', 'Guha', 'arunava.mlindia@gmail.com', '9876543210', NULL, 'Shyambazar, Hati Bagan, Kolkata, West Bengal, India', 'Kolkata', 'WB', 'India', NULL),
(5, 5, 'Moumita', 'Neogi', 'mou.mlindia@gmail.com', '9876543210', NULL, 'Jalpaiguri, West Bengal, India', 'Jalpaiguri', 'WB', 'India', NULL),
(6, 6, 'Tanay', 'Bhattacharyya', 'tanay.mlindia@gmail.com', '9876543210', NULL, 'Sector II, Kolkata, West Bengal, India', 'Kolkata', 'WB', 'India', NULL),
(7, 7, 'Aftab', 'Khan', 'aftab.mlindia@gmail.com', '9876543210', NULL, 'Kolkata Station, Belgachia, Kolkata, West Bengal, India', 'Kolkata', 'WB', 'India', NULL),
(8, 8, 'Aftab', 'Khan', 'aftab.mlindia@gmail.com', '9876543210', NULL, 'Kolkata Station, Belgachia, Kolkata, West Bengal, India', 'Kolkata', 'WB', 'India', NULL),
(9, 9, 'Sourav', 'Palit', 'sourav.mlindia@gmail.com', '9876543210', NULL, 'Sector III, Kolkata, West Bengal, India', 'Kolkata', 'WB', 'India', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_messages`
--

CREATE TABLE `user_messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `msg_id` int(11) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `description` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `profile` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `profile`) VALUES
(1, 'Administrator'),
(2, 'Users'),
(3, 'Landscaper');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address_books`
--
ALTER TABLE `address_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `country` (`country`);

--
-- Indexes for table `book_services`
--
ALTER TABLE `book_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `landscaper_id` (`landscaper_id`),
  ADD KEY `address_book_id` (`address_book_id`),
  ADD KEY `service_price_id` (`service_price_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorite_landscapers`
--
ALTER TABLE `favorite_landscapers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `landscaper_id` (`landscaper_id`);

--
-- Indexes for table `landscapers`
--
ALTER TABLE `landscapers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `payment_accounts`
--
ALTER TABLE `payment_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment_percentages`
--
ALTER TABLE `payment_percentages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_details`
--
ALTER TABLE `service_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `landscaper_id` (`landscaper_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `service_field_id` (`service_field_id`);

--
-- Indexes for table `service_fields`
--
ALTER TABLE `service_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_images`
--
ALTER TABLE `service_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_service_id` (`book_service_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `service_payments`
--
ALTER TABLE `service_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `book_service_id` (`book_service_id`);

--
-- Indexes for table `service_prices`
--
ALTER TABLE `service_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `landscaper_id` (`landscaper_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `service_ratings`
--
ALTER TABLE `service_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `landscaper_id` (`landscaper_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `service_times`
--
ALTER TABLE `service_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `landscaper_id` (`landscaper_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profile_id` (`profile_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_messages`
--
ALTER TABLE `user_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address_books`
--
ALTER TABLE `address_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `book_services`
--
ALTER TABLE `book_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `favorite_landscapers`
--
ALTER TABLE `favorite_landscapers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landscapers`
--
ALTER TABLE `landscapers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payment_accounts`
--
ALTER TABLE `payment_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_percentages`
--
ALTER TABLE `payment_percentages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service_details`
--
ALTER TABLE `service_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `service_fields`
--
ALTER TABLE `service_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `service_images`
--
ALTER TABLE `service_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_payments`
--
ALTER TABLE `service_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_prices`
--
ALTER TABLE `service_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_ratings`
--
ALTER TABLE `service_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_times`
--
ALTER TABLE `service_times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_messages`
--
ALTER TABLE `user_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address_books`
--
ALTER TABLE `address_books`
  ADD CONSTRAINT `address_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `address_books_ibfk_2` FOREIGN KEY (`country`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `book_services`
--
ALTER TABLE `book_services`
  ADD CONSTRAINT `book_services_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `book_services_ibfk_2` FOREIGN KEY (`landscaper_id`) REFERENCES `landscapers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `book_services_ibfk_3` FOREIGN KEY (`address_book_id`) REFERENCES `address_books` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `book_services_ibfk_4` FOREIGN KEY (`service_price_id`) REFERENCES `service_prices` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `favorite_landscapers`
--
ALTER TABLE `favorite_landscapers`
  ADD CONSTRAINT `favorite_landscapers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `favorite_landscapers_ibfk_2` FOREIGN KEY (`landscaper_id`) REFERENCES `landscapers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `landscapers`
--
ALTER TABLE `landscapers`
  ADD CONSTRAINT `landscapers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `landscapers_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `payment_accounts`
--
ALTER TABLE `payment_accounts`
  ADD CONSTRAINT `payment_accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `payment_percentages`
--
ALTER TABLE `payment_percentages`
  ADD CONSTRAINT `payment_percentages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `service_images`
--
ALTER TABLE `service_images`
  ADD CONSTRAINT `service_images_ibfk_1` FOREIGN KEY (`book_service_id`) REFERENCES `book_services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `service_images_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `service_payments`
--
ALTER TABLE `service_payments`
  ADD CONSTRAINT `service_payments_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `service_payments_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `service_payments_ibfk_3` FOREIGN KEY (`book_service_id`) REFERENCES `book_services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `service_prices`
--
ALTER TABLE `service_prices`
  ADD CONSTRAINT `service_prices_ibfk_1` FOREIGN KEY (`landscaper_id`) REFERENCES `landscapers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `service_prices_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `service_ratings`
--
ALTER TABLE `service_ratings`
  ADD CONSTRAINT `service_ratings_ibfk_1` FOREIGN KEY (`landscaper_id`) REFERENCES `landscapers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `service_ratings_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `service_times`
--
ALTER TABLE `service_times`
  ADD CONSTRAINT `service_times_ibfk_1` FOREIGN KEY (`landscaper_id`) REFERENCES `landscapers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `service_times_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `user_profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_messages`
--
ALTER TABLE `user_messages`
  ADD CONSTRAINT `user_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
