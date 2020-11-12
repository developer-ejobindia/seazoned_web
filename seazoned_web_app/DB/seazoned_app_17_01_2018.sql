-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 17, 2018 at 03:39 PM
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
(1, 1, 'Jeet Chowdhury', '18, Vidyasagar Lane', 'Konnagar', 'West Bengal', 80, '9876543210', 'jeet.mlindia@gmail.com', 1);

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
(1, 'Afghanistan'),
(2, 'Albania'),
(3, 'Algeria'),
(4, 'Andorra'),
(5, 'Angola'),
(6, 'Antigua and Barbuda'),
(7, 'Argentina'),
(8, 'Armenia'),
(9, 'Australia'),
(10, 'Austria'),
(11, 'Azerbaijan'),
(12, 'Bahamas, The'),
(13, 'Bahrain'),
(14, 'Bangladesh'),
(15, 'Barbados'),
(16, 'Belarus'),
(17, 'Belgium'),
(18, 'Belize'),
(19, 'Benin'),
(20, 'Bhutan'),
(21, 'Bolivia'),
(22, 'Bosnia and Herzegovina'),
(23, 'Botswana'),
(24, 'Brazil'),
(25, 'Brunei'),
(26, 'Bulgaria'),
(27, 'Burkina Faso'),
(28, 'Burma'),
(29, 'Burundi'),
(30, 'Cambodia'),
(31, 'Cameroon'),
(32, 'Canada'),
(33, 'Cape Verde'),
(34, 'Central Africa'),
(35, 'Chad'),
(36, 'Chile'),
(37, 'China'),
(38, 'Colombia'),
(39, 'Comoros'),
(40, 'Congo, Democratic Republic of the'),
(41, 'Costa Rica'),
(42, 'Cote dIvoire'),
(43, 'Crete'),
(44, 'Croatia'),
(45, 'Cuba'),
(46, 'Cyprus'),
(47, 'Czech Republic'),
(48, 'Denmark'),
(49, 'Djibouti'),
(50, 'Dominican Republic'),
(51, 'East Timor'),
(52, 'Ecuador'),
(53, 'Egypt'),
(54, 'El Salvador'),
(55, 'Equatorial Guinea'),
(56, 'Eritrea'),
(57, 'Estonia'),
(58, 'Ethiopia'),
(59, 'Fiji'),
(60, 'Finland'),
(61, 'France'),
(62, 'Gabon'),
(63, 'Gambia, The'),
(64, 'Georgia'),
(65, 'Germany'),
(66, 'Ghana'),
(67, 'Greece'),
(68, 'Grenada'),
(69, 'Guadeloupe'),
(70, 'Guatemala'),
(71, 'Guinea'),
(72, 'Guinea-Bissau'),
(73, 'Guyana'),
(74, 'Haiti'),
(75, 'Holy See'),
(76, 'Honduras'),
(77, 'Hong Kong'),
(78, 'Hungary'),
(79, 'Iceland'),
(80, 'India'),
(81, 'Indonesia'),
(82, 'Iran'),
(83, 'Iraq'),
(84, 'Ireland'),
(85, 'Israel'),
(86, 'Italy'),
(87, 'Ivory Coast'),
(88, 'Jamaica'),
(89, 'Japan'),
(90, 'Jordan'),
(91, 'Kazakhstan'),
(92, 'Kenya'),
(93, 'Kiribati'),
(94, 'Korea, North'),
(95, 'Korea, South'),
(96, 'Kosovo'),
(97, 'Kuwait'),
(98, 'Kyrgyzstan'),
(99, 'Laos'),
(100, 'Latvia'),
(101, 'Lebanon'),
(102, 'Lesotho'),
(103, 'Liberia'),
(104, 'Libya'),
(105, 'Liechtenstein'),
(106, 'Lithuania'),
(107, 'Macau'),
(108, 'Macedonia'),
(109, 'Madagascar'),
(110, 'Malawi'),
(111, 'Malaysia'),
(112, 'Maldives'),
(113, 'Mali'),
(114, 'Malta'),
(115, 'Marshall Islands'),
(116, 'Mauritania'),
(117, 'Mauritius'),
(118, 'Mexico'),
(119, 'Micronesia'),
(120, 'Moldova'),
(121, 'Monaco'),
(122, 'Mongolia'),
(123, 'Montenegro'),
(124, 'Morocco'),
(125, 'Mozambique'),
(126, 'Namibia'),
(127, 'Nauru'),
(128, 'Nepal'),
(129, 'Netherlands'),
(130, 'New Zealand'),
(131, 'Nicaragua'),
(132, 'Niger'),
(133, 'Nigeria'),
(134, 'North Korea'),
(135, 'Norway'),
(136, 'Oman'),
(137, 'Pakistan'),
(138, 'Palau'),
(139, 'Panama'),
(140, 'Papua New Guinea'),
(141, 'Paraguay'),
(142, 'Peru'),
(143, 'Philippines'),
(144, 'Poland'),
(145, 'Portugal'),
(146, 'Qatar'),
(147, 'Romania'),
(148, 'Russia'),
(149, 'Rwanda'),
(150, 'Saint Lucia'),
(151, 'Saint Vincent and the Grenadines'),
(152, 'Samoa'),
(153, 'San Marino'),
(154, 'Sao Tome and Principe'),
(155, 'Saudi Arabia'),
(156, 'Scotland'),
(157, 'Senegal'),
(158, 'Serbia'),
(159, 'Seychelles'),
(160, 'Sierra Leone'),
(161, 'Singapore'),
(162, 'Slovakia'),
(163, 'Slovenia'),
(164, 'Solomon Islands'),
(165, 'Somalia'),
(166, 'South Africa'),
(167, 'South Korea'),
(168, 'Spain'),
(169, 'Sri Lanka'),
(170, 'Sudan'),
(171, 'Suriname'),
(172, 'Swaziland'),
(173, 'Sweden'),
(174, 'Switzerland'),
(175, 'Syria'),
(176, 'Taiwan'),
(177, 'Tajikistan'),
(178, 'Tanzania'),
(179, 'Thailand'),
(180, 'Tibet'),
(181, 'Timor-Leste'),
(182, 'Togo'),
(183, 'Tonga'),
(184, 'Trinidad and Tobago'),
(185, 'Tunisia'),
(186, 'Turkey'),
(187, 'Turkmenistan'),
(188, 'Tuvalu'),
(189, 'Uganda'),
(190, 'Ukraine'),
(191, 'United Arab Emirates'),
(192, 'United Kingdom'),
(193, 'United States'),
(194, 'Uruguay'),
(195, 'Uzbekistan'),
(196, 'Vanuatu'),
(197, 'Venezuela'),
(198, 'Vietnam'),
(199, 'Yemen'),
(200, 'Zambia'),
(201, 'Zimbabwe');

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
(1, 2, 1, 'Grass Cleaner Corporation', 'We Clean Very Sharp....', '9384_1516197020_Grass_Cleaner_Corporation.jpg', 'Kolkata, WB, India'),
(2, 3, 2, 'Leaf Cleaning Corporation', 'We Clean Leaf Very Clearly....', '3687_1516197262_Leaf_Cleaning_Corporation.jpg', 'Jalpaiguri, WB, India'),
(3, 4, 3, 'Lawn Treatment Corporation', 'Lawn Cleaner Very Professional....', '3417_1516197454_Lawn_Treatment_Corporation.jpg', 'Egra, WB, India'),
(4, 5, 4, 'Lawn Aeration Service Provider', 'Lawn Aeration Services...', '3962_1516197778_Lawn_Aeration.jpg', 'Kolkata, WB, India'),
(5, 6, 5, 'Sprinkler Winterizing Corporation', 'Sprinkler Winterizing Services....', '5610_1516197987_Sprinkler_Winterizing.jpg', 'Kolkata, WB, India'),
(6, 7, 6, 'Pool Cleaning Corporation', 'Pool Cleaning Corporation...', '2083_1516198192_Pool_Cleaning.jpg', 'Jharkhandi, JH, India'),
(7, 8, 7, 'Snow Removal', 'Snow Cleaner Corporation...', '7643_1516198737_Snow_Removal.jpg', 'Kolkata, WB, India');

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
(9, 2, 2, 1, '0 - 0.25', '50.00'),
(10, 2, 2, 1, '0.25 - 0.5', '85.00'),
(11, 2, 2, 1, '0.5 - 0.75', '120.00'),
(12, 2, 2, 1, '0.75 - 1', '155.00'),
(13, 2, 2, 1, '1 - 1.25', '190.00'),
(14, 2, 2, 1, '1.25 - 1.5', '225.00'),
(15, 2, 2, 1, '1.5 - 1.75', '260.00'),
(16, 2, 2, 1, '1.75 - 2', '295.00'),
(17, 2, 2, 1, '2 - 2.25', '330.00'),
(18, 2, 2, 1, '2.25 - 2.5', '365.00'),
(19, 2, 2, 1, '2.5 - 2.75', '400.00'),
(20, 2, 2, 6, 'Light', '25.00'),
(21, 2, 2, 6, 'Medium', '50.00'),
(22, 2, 2, 6, 'Heavy', '75.00'),
(23, 2, 2, 6, 'Over the top', '150.00'),
(24, 3, 3, 1, '0 - 0.25', '50.00'),
(25, 3, 3, 1, '0.25 - 0.5', '85.00'),
(26, 3, 3, 1, '0.5 - 0.75', '120.00'),
(27, 3, 3, 1, '0.75 - 1', '155.00'),
(28, 3, 3, 1, '1 - 1.25', '190.00'),
(29, 3, 3, 1, '1.25 - 1.5', '225.00'),
(30, 3, 3, 1, '1.5 - 1.75', '260.00'),
(31, 3, 3, 1, '1.75 - 2', '295.00'),
(32, 3, 3, 1, '2 - 2.25', '330.00'),
(33, 3, 3, 1, '2.25 - 2.5', '365.00'),
(34, 3, 3, 1, '2.5 - 2.75', '400.00'),
(35, 3, 3, 1, '2.75 - 3', '435.00'),
(36, 3, 3, 1, '3 - 3.25', '470.00'),
(37, 3, 3, 1, '3.25 - 3.5', '505.00'),
(38, 3, 3, 1, '3.5 - 3.75', '540.00'),
(39, 3, 3, 1, '3.75 - 4', '575.00'),
(40, 3, 3, 1, '4 - 4.25', '610.00'),
(41, 3, 3, 1, '4.25 - 4.5', '645.00'),
(42, 3, 3, 1, '4.5 - 4.75', '680.00'),
(43, 3, 3, 1, '4.75 - 5', '715.00'),
(44, 3, 3, 1, '5 - 5.25', '750.00'),
(45, 3, 3, 1, '5.25 - 5.5', '785.00'),
(46, 3, 3, 1, '5.5 - 5.75', '820.00'),
(47, 3, 3, 1, '5.75 - 6', '855.00'),
(48, 3, 3, 1, '6 - 6.25', '890.00'),
(49, 3, 3, 1, '6.25 - 6.5', '925.00'),
(50, 3, 3, 1, '6.5 - 6.75', '960.00'),
(51, 3, 3, 1, '6.75 - 7', '995.00'),
(52, 3, 3, 1, '7 - 7.25', '1030.00'),
(53, 3, 3, 1, '7.25 - 7.5', '1065.00'),
(54, 3, 3, 1, '7.5 - 7.75', '1100.00'),
(55, 3, 3, 1, '7.75 - 8', '1135.00'),
(56, 4, 4, 1, '0 - 0.25', '80.00'),
(57, 4, 4, 1, '0.25 - 0.5', '130.00'),
(58, 4, 4, 1, '0.5 - 0.75', '180.00'),
(59, 4, 4, 1, '0.75 - 1', '230.00'),
(60, 4, 4, 1, '1 - 1.25', '280.00'),
(61, 4, 4, 1, '1.25 - 1.5', '330.00'),
(62, 4, 4, 1, '1.5 - 1.75', '380.00'),
(63, 4, 4, 1, '1.75 - 2', '430.00'),
(64, 4, 4, 1, '2 - 2.25', '480.00'),
(65, 4, 4, 1, '2.25 - 2.5', '530.00'),
(66, 4, 4, 1, '2.5 - 2.75', '580.00'),
(67, 4, 4, 1, '2.75 - 3', '630.00'),
(68, 4, 4, 1, '3 - 3.25', '680.00'),
(69, 4, 4, 1, '3.25 - 3.5', '730.00'),
(70, 5, 5, 1, '0 - 0.25', '25.00'),
(71, 5, 5, 1, '0.25 - 0.5', '43.00'),
(72, 5, 5, 1, '0.5 - 0.75', '61.00'),
(73, 5, 5, 1, '0.75 - 1', '79.00'),
(74, 5, 5, 1, '1 - 1.25', '97.00'),
(75, 5, 5, 1, '1.25 - 1.5', '115.00'),
(76, 5, 5, 1, '1.5 - 1.75', '133.00'),
(77, 5, 5, 11, '0 - 3', '50.00'),
(78, 5, 5, 11, '3 - 6', '80.00'),
(79, 5, 5, 11, '6 - 9', '110.00'),
(80, 6, 6, 7, 'Chlorine', '50.00'),
(81, 6, 6, 7, 'Saline', '30.00'),
(82, 6, 6, 8, 'Yes', '50.00'),
(83, 6, 6, 8, 'No', '0.00'),
(84, 6, 6, 9, 'Inground', '250.00'),
(85, 6, 6, 9, 'Above Ground', '180.00'),
(86, 6, 6, 10, 'Relatively Clear', '75.00'),
(87, 6, 6, 10, 'Moderately Cloudy', '150.00'),
(88, 6, 6, 10, 'Heavy Algae Present', '300.00'),
(109, 7, 7, 3, '2', '250.00'),
(110, 7, 7, 3, '4', '450.00'),
(111, 7, 7, 3, '6', '650.00'),
(112, 7, 7, 3, '8', '850.00'),
(113, 7, 7, 4, 'Straight', '80.00'),
(114, 7, 7, 4, 'Circular', '150.00'),
(115, 7, 7, 4, 'Incline', '250.00'),
(116, 7, 7, 5, 'Front Door Walk Way', '50.00'),
(117, 7, 7, 5, 'Stairs and Front Landing', '150.00'),
(118, 7, 7, 5, 'Side Door Walk Way', '80.00'),
(119, 1, 1, 1, '0 - 0.25', '50.00'),
(120, 1, 1, 1, '0.25 - 0.5', '85.00'),
(121, 1, 1, 1, '0.5 - 0.75', '120.00'),
(122, 1, 1, 1, '0.75 - 1', '155.00'),
(123, 1, 1, 1, '1 - 1.25', '190.00'),
(124, 1, 1, 1, '1.25 - 1.5', '225.00'),
(125, 1, 1, 2, '0 - 6', '80.00'),
(126, 1, 1, 2, '>6', '145.00');

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
(1, 1, 1, 'Every 7 days', '5.00'),
(2, 1, 1, 'Every 10 days', '7.00'),
(3, 1, 1, 'Every 14 days', '12.00'),
(4, 1, 1, 'Just Once', '0.00'),
(5, 2, 2, 'Every 7 days', '5.00'),
(6, 2, 2, 'Every 10 days', '8.00'),
(7, 2, 2, 'Every 14 days', '15.00'),
(8, 2, 2, 'Just Once', '0.00'),
(9, 3, 3, 'Every 7 days', '0.00'),
(10, 3, 3, 'Every 10 days', '0.00'),
(11, 3, 3, 'Every 14 days', '5.00'),
(12, 3, 3, 'Just Once', '0.00'),
(13, 4, 4, 'Every 7 days', '0.00'),
(14, 4, 4, 'Every 10 days', '0.00'),
(15, 4, 4, 'Every 14 days', '0.00'),
(16, 4, 4, 'Just Once', '0.00'),
(17, 5, 5, 'Every 7 days', '0.00'),
(18, 5, 5, 'Every 10 days', '0.00'),
(19, 5, 5, 'Every 14 days', '0.00'),
(20, 5, 5, 'Just Once', '0.00'),
(21, 6, 6, 'Every 7 days', '0.00'),
(22, 6, 6, 'Every 10 days', '0.00'),
(23, 6, 6, 'Every 14 days', '0.00'),
(24, 6, 6, 'Just Once', '0.00'),
(25, 7, 7, 'Every 7 days', '0.00'),
(26, 7, 7, 'Every 10 days', '0.00'),
(27, 7, 7, 'Every 14 days', '0.00'),
(28, 7, 7, 'Just Once', '0.00');

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
(1, 1, 1, 'Monday', '12:00:00', '16:00:00'),
(2, 1, 1, 'Tuesday', '12:00:00', '16:00:00'),
(3, 1, 1, 'Wednesday', '12:00:00', '16:00:00'),
(4, 1, 1, 'Thrusday', '12:00:00', '16:00:00'),
(5, 1, 1, 'Friday', '12:00:00', '16:00:00'),
(6, 2, 2, 'Sunday', '12:00:00', '18:00:00'),
(7, 3, 3, 'Saturday', '12:00:00', '18:00:00'),
(8, 3, 3, 'Sunday', '12:00:00', '18:00:00'),
(9, 4, 4, 'Monday', '12:00:00', '18:00:00'),
(10, 4, 4, 'Tuesday', '12:00:00', '18:00:00'),
(11, 4, 4, 'Wednesday', '12:00:00', '18:00:00'),
(12, 4, 4, 'Thrusday', '12:00:00', '18:00:00'),
(13, 4, 4, 'Friday', '12:00:00', '18:00:00'),
(14, 5, 5, 'Sunday', '12:00:00', '18:00:00'),
(15, 6, 6, 'Saturday', '12:00:00', '18:00:00'),
(16, 6, 6, 'Sunday', '12:00:00', '18:00:00'),
(17, 7, 7, 'Monday', '12:00:00', '18:00:00'),
(18, 7, 7, 'Tuesday', '12:00:00', '18:00:00'),
(19, 7, 7, 'Wednesday', '12:00:00', '18:00:00'),
(20, 7, 7, 'Thrusday', '12:00:00', '18:00:00'),
(21, 7, 7, 'Friday', '12:00:00', '18:00:00'),
(22, 7, 7, 'Saturday', '12:00:00', '18:00:00');

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
(4, 3, 'sumanta.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(5, 3, 'saikat.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(6, 3, 'arunava.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(7, 3, 'aftab.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1),
(8, 3, 'tanay.mlindia@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Landscaper', 1);

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
(1, 1, 'Jeet', 'Chowdhury', 'jeet.mlindia@gmail.com', '9876543210', '1989-02-09', '18, Vidyasagar Lane', 'Konnagar', 'West Bengal', '80', '8615_1516196524_TweakJC.jpg'),
(2, 2, 'Sauvik', 'Dey', 'sauvik.mlindia@gmail.com', '9876543210', NULL, 'Keshtopur, Kolkata, West Bengal, India', 'Kolkata', 'WB', 'India', '2938_1516199451_Avatar_3.png'),
(3, 3, 'Krishnendu', 'Biswas', 'krishnendu.mlindia@gmail.com', '9876543210', NULL, 'Jalpaiguri, West Bengal, India', 'Jalpaiguri', 'WB', 'India', NULL),
(4, 4, 'Sumanta', 'Jana', 'sumanta.mlindia@gmail.com', '9876543210', NULL, 'Egra, West Bengal, India', 'Egra', 'WB', 'India', NULL),
(5, 5, 'Saikat', 'Maity', 'saikat.mlindia@gmail.com', '9876543210', NULL, 'Behala, Kolkata, West Bengal, India', 'Kolkata', 'WB', 'India', NULL),
(6, 6, 'Arunava', 'Guha', 'arunava.mlindia@gmail.com', '9876543210', NULL, 'Shyambazar, Hati Bagan, Kolkata, West Bengal, India', 'Kolkata', 'WB', 'India', NULL),
(7, 7, 'Aftab', 'Khan', 'aftab.mlindia@gmail.com', '9876543210', NULL, 'Jharkhandi, Jharkhand, India', 'Jharkhandi', 'JH', 'India', NULL),
(8, 8, 'Tanay', 'Bhattacharyya', 'tanay.mlindia@gmail.com', '9876543210', NULL, 'Keshtopur, Kolkata, West Bengal, India', 'Kolkata', 'WB', 'India', '6638_1516198823_Avatar_3.png');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `book_services`
--
ALTER TABLE `book_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `favorite_landscapers`
--
ALTER TABLE `favorite_landscapers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landscapers`
--
ALTER TABLE `landscapers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `service_ratings`
--
ALTER TABLE `service_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_times`
--
ALTER TABLE `service_times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
