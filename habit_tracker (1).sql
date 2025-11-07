-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2025 at 11:35 AM
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
-- Database: `habit_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmins`
--

CREATE TABLE `tbladmins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `login_count` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmins`
--

INSERT INTO `tbladmins` (`admin_id`, `username`, `email`, `password_hash`, `created_at`, `is_active`, `login_count`) VALUES
(6, 'fa_admin', 'admin@gmail.com', '$2y$10$.KARi07loh14qeu2m2pv8eSXpx6VU7DhCdCzILcHPLstA84PYhJVW', '2025-11-02 14:14:10', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblauthlogs`
--

CREATE TABLE `tblauthlogs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` enum('success','failure') NOT NULL,
  `device_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblauthlogs`
--

INSERT INTO `tblauthlogs` (`log_id`, `user_id`, `timestamp`, `ip_address`, `status`, `device_info`) VALUES
(2, 4, '2025-11-05 15:26:40', '::1', 'success', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36');

-- --------------------------------------------------------

--
-- Table structure for table `tblhabitlogs`
--

CREATE TABLE `tblhabitlogs` (
  `log_id` int(11) NOT NULL,
  `habit_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `completed` tinyint(1) DEFAULT 0,
  `status` enum('done','missed') DEFAULT 'done'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblhabitlogs`
--

INSERT INTO `tblhabitlogs` (`log_id`, `habit_id`, `log_date`, `completed`, `status`) VALUES
(6, 10, '2025-11-02', 0, 'done'),
(7, 10, '2025-11-05', 0, 'done'),
(8, 9, '2025-11-05', 0, 'done'),
(9, 8, '2025-11-05', 0, 'done'),
(10, 7, '2025-11-05', 0, 'done');

-- --------------------------------------------------------

--
-- Table structure for table `tblhabits`
--

CREATE TABLE `tblhabits` (
  `habit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `habit_name` varchar(100) NOT NULL,
  `frequency` enum('daily','weekly','monthly') DEFAULT 'daily',
  `category` varchar(100) DEFAULT NULL,
  `status` enum('active','completed','paused') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `goal_days` int(11) DEFAULT 30,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblhabits`
--

INSERT INTO `tblhabits` (`habit_id`, `user_id`, `habit_name`, `frequency`, `category`, `status`, `created_at`, `deleted_at`, `goal_days`, `name`, `start_date`) VALUES
(7, 4, 'Drink Water', 'daily', 'Health', 'active', '2025-11-02 13:31:46', NULL, 30, '', '2025-11-02'),
(8, 4, 'sleep', 'daily', '', 'active', '2025-11-02 14:38:12', NULL, 30, '', '2025-11-02'),
(9, 4, 'Drink Water', 'daily', 'Health', 'active', '2025-11-02 14:44:17', NULL, 30, '', '2025-11-02'),
(10, 4, 'Drink Water', 'daily', 'Health', 'active', '2025-11-02 14:45:01', NULL, 30, '', '2025-11-02');

-- --------------------------------------------------------

--
-- Table structure for table `tblhabittemplates`
--

CREATE TABLE `tblhabittemplates` (
  `template_id` int(11) NOT NULL,
  `habit_name` varchar(255) NOT NULL,
  `frequency` enum('Daily','Weekly') NOT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblhabittemplates`
--

INSERT INTO `tblhabittemplates` (`template_id`, `habit_name`, `frequency`, `category`) VALUES
(1, 'Drink Water', 'Daily', 'Health'),
(2, 'Read 10 Pages', 'Daily', 'Study'),
(3, 'Exercise', 'Weekly', 'Fitness'),
(4, 'Meditate', 'Daily', 'Wellness'),
(5, 'Sleep 8 Hours', 'Daily', 'Health'),
(6, 'Journal Your Day', 'Daily', 'Mindfulness'),
(7, 'Plan Weekly Goals', 'Weekly', 'Productivity'),
(8, 'Practice Coding', 'Daily', 'Skill'),
(9, 'Stretch for 10 Minutes', 'Daily', 'Fitness'),
(10, 'Limit Screen Time', 'Daily', 'Wellness');

-- --------------------------------------------------------

--
-- Table structure for table `tblreportdetails`
--

CREATE TABLE `tblreportdetails` (
  `detail_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `habit_id` int(11) NOT NULL,
  `completion_rate` decimal(5,2) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblreports`
--

CREATE TABLE `tblreports` (
  `report_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `report_title` varchar(100) NOT NULL,
  `generated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`user_id`, `username`, `email`, `password_hash`, `created_at`, `role`) VALUES
(4, 'faysal', 'fa@gmail.com', '$2y$10$XNGB7TQS//OCGOf/.QuHZe2D1z48LN3aWi5V.nzk7gwTaC7Cr/Bsm', '2025-11-02 13:03:00', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmins`
--
ALTER TABLE `tbladmins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tblauthlogs`
--
ALTER TABLE `tblauthlogs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblhabitlogs`
--
ALTER TABLE `tblhabitlogs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `habit_id` (`habit_id`);

--
-- Indexes for table `tblhabits`
--
ALTER TABLE `tblhabits`
  ADD PRIMARY KEY (`habit_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblhabittemplates`
--
ALTER TABLE `tblhabittemplates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `tblreportdetails`
--
ALTER TABLE `tblreportdetails`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `habit_id` (`habit_id`);

--
-- Indexes for table `tblreports`
--
ALTER TABLE `tblreports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmins`
--
ALTER TABLE `tbladmins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblauthlogs`
--
ALTER TABLE `tblauthlogs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblhabitlogs`
--
ALTER TABLE `tblhabitlogs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblhabits`
--
ALTER TABLE `tblhabits`
  MODIFY `habit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblhabittemplates`
--
ALTER TABLE `tblhabittemplates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblreportdetails`
--
ALTER TABLE `tblreportdetails`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblreports`
--
ALTER TABLE `tblreports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblauthlogs`
--
ALTER TABLE `tblauthlogs`
  ADD CONSTRAINT `tblauthlogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tblusers` (`user_id`);

--
-- Constraints for table `tblhabitlogs`
--
ALTER TABLE `tblhabitlogs`
  ADD CONSTRAINT `tblhabitlogs_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `tblhabits` (`habit_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblhabits`
--
ALTER TABLE `tblhabits`
  ADD CONSTRAINT `tblhabits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tblusers` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblreportdetails`
--
ALTER TABLE `tblreportdetails`
  ADD CONSTRAINT `tblreportdetails_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `tblreports` (`report_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblreportdetails_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tblusers` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblreportdetails_ibfk_3` FOREIGN KEY (`habit_id`) REFERENCES `tblhabits` (`habit_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblreports`
--
ALTER TABLE `tblreports`
  ADD CONSTRAINT `tblreports_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `tbladmins` (`admin_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
