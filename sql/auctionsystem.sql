-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2021 at 12:32 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Is the auction title when combined with name',
  `description` text COLLATE utf8mb4_slovak_ci,
  `starting_bid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `time_limit` time DEFAULT NULL,
  `minimum_bid_increase` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `bidding_interval` time DEFAULT NULL,
  `awaiting_approval` tinyint(1) NOT NULL DEFAULT '1',
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
(1, 'Hadajte sa o MIKROVLNKU', '2021-10-24 19:42:48', 'Mam staru mikrovlnku.\r\nNech vyhra najlepsi!!', 200, '00:30:00', 0, NULL, 1, 1, 1, 2, NULL, NULL),
(2, 'Monitor', '2021-10-24 19:47:06', 'Monitor. \r\nKdo chce nech da cenu', 150, NULL, 0, NULL, 0, 3, 2, 1, 2, NULL);

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
(1, 'placeholder.png', 1),
(2, 'placeholder.png', 1),
(3, 'placeholder.png', 2);

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
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `bid`
--

INSERT INTO `bid` (`id`, `value`, `auction_id`, `user_id`) VALUES
(1, 500, 2, 1);

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
  `address` varchar(255) COLLATE utf8mb4_slovak_ci NOT NULL,
  `registered_since` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `mail`, `password`, `address`, `registered_since`, `role_id`) VALUES
(1, 'Matej', 'Jurik', 'matej.jurik@neexistujem.com', 'tvarims@zes0mhasH', 'Doma', '2021-10-24 19:40:25', 1),
(2, 'Marek', 'Micek', 'micko@mail.com', 'HaShAkoHrom456@xD', 'Tiez doma, pohodicka', '2021-10-24 19:44:06', 2),
(3, 'Peter', 'Rucek', 'petrik@mail.com', 'hasHUJEMcelyDen123', 'Nepoviem', '2021-10-24 19:44:43', 3);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auction_photo`
--
ALTER TABLE `auction_photo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
