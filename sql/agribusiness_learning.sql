-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 07:09 AM
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
-- Database: `agribusiness_learning`
--

-- --------------------------------------------------------

--
-- Table structure for table `course_table`
--

CREATE TABLE `course_table` (
  `course_id` int(11) NOT NULL,
  `course_title` varchar(255) NOT NULL,
  `course_description` text NOT NULL,
  `course_photo` varchar(255) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `course_category` varchar(100) NOT NULL,
  `course_price` decimal(10,2) DEFAULT 0.00,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_table`
--

INSERT INTO `course_table` (`course_id`, `course_title`, `course_description`, `course_photo`, `instructor_id`, `course_category`, `course_price`, `last_updated`) VALUES
(1, 'Modern Techniques in Organic farming', 'Learn sustainable and eco friendly farming methods', 'uploadedfiles/modern.jpg', 2, 'Crop Farming', 150000.00, '2025-03-18 04:20:37'),
(2, 'Precision: Using Technology in Crop farming', 'Explore how drone, sensors, and AI improve farming efficiency', 'uploadedfiles/1742270633_beach1[1].jpg', 2, 'Agritech', 150000.00, '2025-03-18 04:21:03'),
(3, 'Soil Health & Fertility Management', 'Understand how enhance soil quality for better crop yields', 'uploadedfiles/1742271410_1742271116628_1[1].jpg', 2, 'Crop Farming', 150000.00, '2025-03-18 04:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `learning_table`
--

CREATE TABLE `learning_table` (
  `learning_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `progress` decimal(5,2) DEFAULT 0.00,
  `status` enum('In Progress','Completed') DEFAULT 'In Progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_table`
--

CREATE TABLE `lesson_table` (
  `lesson_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_title` varchar(255) NOT NULL,
  `lesson_description` text DEFAULT NULL,
  `lesson_file` varchar(255) DEFAULT NULL,
  `lesson_duration` time DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizze_table`
--

CREATE TABLE `quizze_table` (
  `quizze_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_A` varchar(255) NOT NULL,
  `option_B` varchar(255) NOT NULL,
  `option_C` varchar(255) NOT NULL,
  `option_D` varchar(255) NOT NULL,
  `correct_answer` char(1) DEFAULT NULL CHECK (`correct_answer` in ('A','B','C','D'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_tel` varchar(20) NOT NULL,
  `user_role` enum('Admin','Instructor','Learner','Supplier') NOT NULL,
  `pro_info` text DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_id`, `name`, `surname`, `user_email`, `password`, `user_tel`, `user_role`, `pro_info`, `reg_date`) VALUES
(1, 'Abeid', 'Mashauri', 'abeidmashauri@gmail.com', '123456', '+255753940450', 'Admin', 'System admin', '2025-03-13 20:15:40'),
(2, 'Siaba', 'David', 'siaba@gmail.com', '123456', '+255626140450', 'Instructor', 'Iam instructor', '2025-03-13 20:20:06'),
(3, 'Yohana', 'Andew', 'yohana@gmail.com', '123456', '+255710940972', 'Learner', 'I like Agribusiness', '2025-03-13 20:20:06'),
(4, 'HAMMY', 'DAX', 'hammydax@gmail.com', '123445', '0675456443', 'Instructor', NULL, '2025-03-14 18:34:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course_table`
--
ALTER TABLE `course_table`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `learning_table`
--
ALTER TABLE `learning_table`
  ADD PRIMARY KEY (`learning_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `lesson_table`
--
ALTER TABLE `lesson_table`
  ADD PRIMARY KEY (`lesson_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `quizze_table`
--
ALTER TABLE `quizze_table`
  ADD PRIMARY KEY (`quizze_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD UNIQUE KEY `user_tel` (`user_tel`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course_table`
--
ALTER TABLE `course_table`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `learning_table`
--
ALTER TABLE `learning_table`
  MODIFY `learning_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lesson_table`
--
ALTER TABLE `lesson_table`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizze_table`
--
ALTER TABLE `quizze_table`
  MODIFY `quizze_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_table`
--
ALTER TABLE `course_table`
  ADD CONSTRAINT `course_table_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `user_table` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `learning_table`
--
ALTER TABLE `learning_table`
  ADD CONSTRAINT `learning_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_table` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `learning_table_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course_table` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_table`
--
ALTER TABLE `lesson_table`
  ADD CONSTRAINT `lesson_table_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course_table` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `quizze_table`
--
ALTER TABLE `quizze_table`
  ADD CONSTRAINT `quizze_table_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course_table` (`course_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
