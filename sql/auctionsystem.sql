-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 25, 2021 at 11:28 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auctionsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `auction`
--

CREATE TABLE `auction` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_slovak_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Is the auction title when combined with name',
  `description` text COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `starting_bid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `time_limit` time DEFAULT NULL,
  `minimum_bid_increase` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bidding_interval` time DEFAULT NULL,
  `awaiting_approval` tinyint(1) NOT NULL DEFAULT 1,
  `author_id` int(10) UNSIGNED NOT NULL,
  `type_id` int(10) UNSIGNED NOT NULL,
  `ruleset_id` int(10) UNSIGNED NOT NULL,
  `approver_id` int(10) UNSIGNED DEFAULT NULL,
  `winner_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `auction`
--

INSERT INTO `auction` (`id`, `name`, `date`, `description`, `starting_bid`, `time_limit`, `minimum_bid_increase`, `bidding_interval`, `awaiting_approval`, `author_id`, `type_id`, `ruleset_id`, `approver_id`, `winner_id`) VALUES
(43, 'Predam obraz', '2021-12-01 15:00:00', 'Van gogh', 100000, '02:00:00', 0, NULL, 0, 11, 1, 1, 9, 0),
(44, 'Vylet do karibiku', '2021-11-25 16:09:00', 'uzavreta aukcia', 100, NULL, 0, NULL, 0, 10, 1, 2, 9, 15),
(45, 'Aukcia', '2021-11-25 16:43:00', 'S bid increasom', 1, '05:00:00', 10, NULL, 0, 16, 1, 1, 9, 0),
(46, 'Kupim loptu', '2021-11-25 16:47:00', 'Kto da najnizsiu cenu ?', 500, NULL, 0, NULL, 0, 14, 2, 1, 9, 0),
(48, 'Uzavreta', '2021-11-25 21:11:00', 'Klesajuca', 1000, NULL, 0, NULL, 0, 11, 2, 2, 9, 0),
(49, 'S viac fotkami', '2021-11-25 21:34:56', 'a tymi istymi', 1, NULL, 0, NULL, 1, 8, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auction_photo`
--

CREATE TABLE `auction_photo` (
  `id` int(10) UNSIGNED NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_slovak_ci NOT NULL,
  `auction_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `auction_photo`
--

INSERT INTO `auction_photo` (`id`, `path`, `auction_id`) VALUES
(7, '970px-Van_Gogh_-_Starry_Night_-_Google_Art_Project.jpg', 43),
(8, 'karibik-po-hurikanech-01.jpg', 44),
(10, 'close-up-official-fifa-world-cup-ball-brazuca-kyiv-ukraine-may-grass-ukraine-championship-game-fc-dynamo-41342431.jpg', 46),
(12, 'auction.png', 48),
(13, '970px-Van_Gogh_-_Starry_Night_-_Google_Art_Project.jpg', 49),
(14, 'auction.png', 49);

-- --------------------------------------------------------

--
-- Table structure for table `auction_ruleset`
--

CREATE TABLE `auction_ruleset` (
  `id` int(10) UNSIGNED NOT NULL,
  `ruleset` varchar(255) COLLATE utf8mb4_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `auction_ruleset`
--

INSERT INTO `auction_ruleset` (`id`, `ruleset`) VALUES
(1, 'open'),
(2, 'closed');

-- --------------------------------------------------------

--
-- Table structure for table `auction_type`
--

CREATE TABLE `auction_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `auction_type`
--

INSERT INTO `auction_type` (`id`, `type`) VALUES
(1, 'ascending-bid'),
(2, 'descending-bid');

-- --------------------------------------------------------

--
-- Table structure for table `bid`
--

CREATE TABLE `bid` (
  `id` int(10) UNSIGNED NOT NULL,
  `value` int(10) UNSIGNED NOT NULL,
  `auction_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `awaiting_approval` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `bid`
--

INSERT INTO `bid` (`id`, `value`, `auction_id`, `user_id`, `awaiting_approval`) VALUES
(34, 0, 43, 14, 0),
(35, 0, 43, 15, 1),
(36, 0, 43, 10, 0),
(38, 200, 44, 15, 0),
(39, 150, 44, 16, 0),
(40, 11, 45, 11, 0),
(41, 21, 45, 14, 0),
(43, 400, 46, 11, 0),
(44, 0, 46, 15, 0),
(45, 900, 48, 14, 0),
(46, 800, 48, 10, 0),
(47, 50, 45, 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_slovak_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_slovak_ci NOT NULL,
  `mail` varchar(255) CHARACTER SET ascii NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_slovak_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `registered_since` timestamp NOT NULL DEFAULT current_timestamp(),
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `mail`, `password`, `address`, `registered_since`, `role_id`) VALUES
(8, 'Admin', 'Admin', 'admin', 'admin', '', '2021-11-19 19:10:47', 1),
(9, 'Licitator', '1', 'licitator1', 'licitator', '', '2021-11-19 19:11:57', 2),
(10, 'Licitator', '2', 'licitator2', 'licitator', '', '2021-11-19 19:12:55', 2),
(11, 'Uzivatel', '1', 'uzivatel1', 'uzivatel', '', '2021-11-19 19:13:50', 3),
(14, 'Uzivatel', '2', 'uzivatel2', 'uzivatel', '', '2021-11-23 09:18:27', 3),
(15, 'Uzivatel', '3', 'uzivatel3', 'uzivatel', '', '2021-11-23 09:18:53', 3),
(16, 'Uzivatel', '4', 'uzivatel4', 'uzivatel', '', '2021-11-23 13:55:14', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_slovak_ci NOT NULL,
  `authority_level` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role`, `authority_level`) VALUES
(1, 'Admin', 4),
(2, 'Auctioneer', 3),
(3, 'User', 2),
(4, 'Visitor', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auction`
--
ALTER TABLE `auction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auction_photo`
--
ALTER TABLE `auction_photo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auction_ruleset`
--
ALTER TABLE `auction_ruleset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auction_type`
--
ALTER TABLE `auction_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bid`
--
ALTER TABLE `bid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auction`
--
ALTER TABLE `auction`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `auction_photo`
--
ALTER TABLE `auction_photo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `auction_ruleset`
--
ALTER TABLE `auction_ruleset`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auction_type`
--
ALTER TABLE `auction_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bid`
--
ALTER TABLE `bid`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
