-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2025 at 11:24 PM
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
-- Database: `cruciweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dimensions` varchar(20) NOT NULL,
  `difficulty` enum('beginner','intermediate','expert') NOT NULL,
  `words` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`words`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `creator_id`, `name`, `dimensions`, `difficulty`, `words`, `created_at`) VALUES
(18, 13, 'Colors Game', '6x6', 'intermediate', '[[\"\",\"S\",\"K\",\"Y\",\"\",\"R\"],[\"\",\"O\",\"\",\"B\",\"L\",\"U\"],[\"\",\"G\",\"R\",\"E\",\"E\",\"N\"],[\"\",\"O\",\"\",\"\",\"R\",\"\"],[\"\",\"O\",\"\",\"\",\"O\",\"\"],[\"\",\"D\",\"\",\"\",\"I\",\"\"]]', '2025-01-01 19:51:12'),
(19, 13, 'Food Example', '8x8', 'intermediate', '[[\"T\",\"A\",\"R\",\"T\",\"E\",\"\",\"\",\"\"],[\"\",\"M\",\"I\",\"E\",\"L\",\"\",\"V\",\"\"],[\"\",\"\",\"Z\",\"\",\"\",\"P\",\"I\",\"\"],[\"\",\"\",\"\",\"\",\"\",\"O\",\"N\",\"P\"],[\"B\",\"E\",\"U\",\"R\",\"R\",\"M\",\"\",\"A\"],[\"\",\"\",\"\",\"\",\"\",\"M\",\"\",\"I\"],[\"\",\"G\",\"L\",\"A\",\"C\",\"E\",\"\",\"N\"],[\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"]]', '2025-01-01 19:58:42'),
(20, 13, 'Celebrities Game', '13x13', 'expert', '[[\"Z\",\"I\",\"D\",\"N\",\"E\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"L\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"M\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"A\",\"\",\"C\",\"\",\"\",\"\",\"\",\"\",\"\"],[\"C\",\"O\",\"T\",\"I\",\"L\",\"L\",\"A\",\"R\",\"D\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"E\",\"\",\"S\",\"\",\"\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"H\",\"\",\"T\",\"\",\"H\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"\",\"P\",\"A\",\"R\",\"A\",\"D\",\"I\",\"S\",\"\"],[\"\",\"\",\"\",\"\",\"M\",\"A\",\"R\",\"C\",\"E\",\"A\",\"U\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"A\",\"D\",\"J\",\"A\",\"N\",\"I\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"M\",\"A\",\"U\",\"R\",\"E\",\"S\",\"M\",\"O\",\"\"],[\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"L\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"]]', '2025-01-01 20:04:41');

-- --------------------------------------------------------

--
-- Table structure for table `hints`
--

CREATE TABLE `hints` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `hints` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`hints`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hints`
--

INSERT INTO `hints` (`id`, `game_id`, `hints`) VALUES
(12, 18, '{\"row\":{\"1\":\"sky\",\"2\":\"blu\",\"3\":\"green\"},\"col\":{\"B\":\"so good\",\"E\":\"le roi\",\"F\":\"run\"}}'),
(13, 19, '{\"row\":{\"1\":\"Tarte\",\"2\":\"Miel\",\"5\":\"Beurre\",\"7\":\"Glace\"},\"col\":{\"C\":\"Riz\",\"F\":\"Pomme\",\"G\":\"Vin\",\"H\":\"Pain\"}}'),
(14, 20, '{\"row\":{\"1\":\"Zidane\",\"5\":\"Cotillard\",\"8\":\"Paradis\",\"9\":\"Marceau\",\"10\":\"Adjani\",\"11\":\"Mauresmo\"},\"col\":{\"A\":\"Elmaleh\",\"G\":\"Casta\",\"I\":\"Haenel\"}}');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('anonymous','registered','admin') NOT NULL DEFAULT 'registered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `created_at`, `role`) VALUES
(11, 'Hikari Inoue', 'hikariinoue@gmail.com', '$2y$10$YWqHyxG534gOGaPuUyMae.XziPJ7JnkkifcoSsJlQ6EGG9WfjsaDe', '2025-01-01 19:24:59', 'admin'),
(13, 'Ren Okamoto', 'renokamoto@hotmail.fr', '$2y$10$x3bOVNuOQI9g78QWlguaUeXmHXop77y2VLiMrVh2yRRl3Fpg.Hy4y', '2025-01-01 19:40:03', 'registered');

-- --------------------------------------------------------

--
-- Table structure for table `user_attempts`
--

CREATE TABLE `user_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `game_id` int(11) NOT NULL,
  `progress` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`progress`)),
  `completed` tinyint(1) DEFAULT 0,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `finished_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_attempts`
--

INSERT INTO `user_attempts` (`id`, `user_id`, `game_id`, `progress`, `completed`, `started_at`, `finished_at`) VALUES
(8, 13, 20, '[{\"row\":0,\"col\":0,\"value\":\"+\"},{\"row\":0,\"col\":1,\"value\":\"+\"},{\"row\":0,\"col\":2,\"value\":\"+\"},{\"row\":0,\"col\":3,\"value\":\"+\"},{\"row\":0,\"col\":4,\"value\":\"+\"},{\"row\":1,\"col\":4,\"value\":\"S\"},{\"row\":2,\"col\":4,\"value\":\"D\"},{\"row\":3,\"col\":4,\"value\":\"S\"},{\"row\":3,\"col\":6,\"value\":\"+\"},{\"row\":4,\"col\":0,\"value\":\"+\"},{\"row\":4,\"col\":1,\"value\":\"+\"},{\"row\":4,\"col\":2,\"value\":\"+\"},{\"row\":4,\"col\":3,\"value\":\"+\"},{\"row\":4,\"col\":4,\"value\":\"+\"},{\"row\":4,\"col\":5,\"value\":\"+\"},{\"row\":4,\"col\":6,\"value\":\"A\"},{\"row\":4,\"col\":7,\"value\":\"+\"},{\"row\":4,\"col\":8,\"value\":\"+\"},{\"row\":5,\"col\":4,\"value\":\"+\"},{\"row\":5,\"col\":6,\"value\":\"A\"},{\"row\":6,\"col\":4,\"value\":\"+\"},{\"row\":6,\"col\":6,\"value\":\"+\"},{\"row\":6,\"col\":8,\"value\":\"+\"},{\"row\":7,\"col\":5,\"value\":\"+\"},{\"row\":7,\"col\":6,\"value\":\"A\"},{\"row\":7,\"col\":7,\"value\":\"+\"},{\"row\":7,\"col\":8,\"value\":\"A\"},{\"row\":7,\"col\":9,\"value\":\"+\"},{\"row\":7,\"col\":10,\"value\":\"+\"},{\"row\":7,\"col\":11,\"value\":\"+\"},{\"row\":8,\"col\":4,\"value\":\"+\"},{\"row\":8,\"col\":5,\"value\":\"+\"},{\"row\":8,\"col\":6,\"value\":\"+\"},{\"row\":8,\"col\":7,\"value\":\"+\"},{\"row\":8,\"col\":8,\"value\":\"+\"},{\"row\":8,\"col\":9,\"value\":\"+\"},{\"row\":8,\"col\":10,\"value\":\"+\"},{\"row\":9,\"col\":4,\"value\":\"+\"},{\"row\":9,\"col\":5,\"value\":\"+\"},{\"row\":9,\"col\":6,\"value\":\"+\"},{\"row\":9,\"col\":7,\"value\":\"+\"},{\"row\":9,\"col\":8,\"value\":\"+\"},{\"row\":9,\"col\":9,\"value\":\"+\"},{\"row\":10,\"col\":4,\"value\":\"+\"},{\"row\":10,\"col\":5,\"value\":\"+\"},{\"row\":10,\"col\":6,\"value\":\"+\"},{\"row\":10,\"col\":7,\"value\":\"+\"},{\"row\":10,\"col\":8,\"value\":\"+\"},{\"row\":10,\"col\":9,\"value\":\"+\"},{\"row\":10,\"col\":10,\"value\":\"+\"},{\"row\":10,\"col\":11,\"value\":\"+\"},{\"row\":11,\"col\":8,\"value\":\"A\"}]', 0, '2025-01-01 20:14:03', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_id` (`creator_id`);

--
-- Indexes for table `hints`
--
ALTER TABLE `hints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_attempts`
--
ALTER TABLE `user_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `hints`
--
ALTER TABLE `hints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_attempts`
--
ALTER TABLE `user_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `hints`
--
ALTER TABLE `hints`
  ADD CONSTRAINT `hints_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);

--
-- Constraints for table `user_attempts`
--
ALTER TABLE `user_attempts`
  ADD CONSTRAINT `user_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_attempts_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
