-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2025 at 07:10 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+08:00";

--
-- Database: `nordlich`
--
CREATE DATABASE IF NOT EXISTS `nordlich` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `nordlich`;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_code` varchar(20) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `position_id` int(11) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `status` enum('Active','Probationary','Inactive') NOT NULL DEFAULT 'Probationary',
  `photo_path` varchar(255) DEFAULT 'assets/images/default-avatar.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_code` (`employee_code`),
  UNIQUE KEY `email` (`email`),
  KEY `position_id` (`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('employee','hr','admin') NOT NULL DEFAULT 'employee',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_logs`
--
DROP TABLE IF EXISTS `attendance_logs`;
CREATE TABLE `attendance_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` enum('On Time','Late','Absent','On Leave') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_log_date` (`employee_id`,`log_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--
DROP TABLE IF EXISTS `leave_types`;
CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--
DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `days` decimal(4,1) NOT NULL COMMENT 'Number of days requested',
  `reason` text NOT NULL,
  `status` enum('Pending','Approved','Declined') NOT NULL DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `leave_type_id` (`leave_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_credits`
--
DROP TABLE IF EXISTS `leave_credits`;
CREATE TABLE `leave_credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `balance` decimal(5,1) NOT NULL DEFAULT 0.0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_leave_type` (`employee_id`,`leave_type_id`),
  KEY `leave_type_id` (`leave_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- POPULATING TABLES WITH NEW SAMPLE DATA
--
-- --------------------------------------------------------

INSERT INTO `departments` (`id`, `name`) VALUES (1, 'Pharmacy'),(2, 'Human Resources'),(3, 'Logistics'),(4, 'Accounting');
INSERT INTO `positions` (`id`, `title`, `department_id`) VALUES (1, 'Senior Pharmacist', 1),(2, 'HR Officer', 2),(3, 'Warehouse Manager', 3),(4, 'Junior Accountant', 4),(5, 'Logistics Staff', 3);
INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `email`, `position_id`, `area`, `hire_date`, `status`, `photo_path`) VALUES (1, 'EMP-1001', 'Dr. Aris Cruz', 'aris.cruz@nordlich.ph', 1, 'Quezon City', '2022-03-15', 'Active', 'uploads/aris_cruz.png'),(2, 'EMP-1002', 'Sofia Reyes', 'sofia.reyes@nordlich.ph', 2, 'Makati City', '2023-01-20', 'Active', 'uploads/sofia_reyes.png'),(3, 'EMP-1003', 'Benjie Santos', 'benjie.santos@nordlich.ph', 3, 'Pasig City', '2022-11-05', 'Active', 'uploads/benjie_santos.png'),(4, 'EMP-1004', 'Carla Gomez', 'carla.gomez@nordlich.ph', 4, 'Quezon City', '2025-06-01', 'Probationary', 'assets/images/default-avatar.png'),(5, 'EMP-1005', 'Marco Diaz', 'marco.diaz@nordlich.ph', 5, 'Pasig City', '2025-02-10', 'Active', 'assets/images/default-avatar.png');
INSERT INTO `users` (`id`, `employee_id`, `username`, `password_hash`, `role`, `is_active`) VALUES (1, 1, 'aris.cruz', '$2y$10$NotARealHash.PleaseReplaceThisWithARealOne', 'employee', 1),(2, 2, 'sofia.reyes', '$2y$10$NotARealHash.PleaseReplaceThisWithARealOne', 'hr', 1),(3, 3, 'benjie.santos', '$2y$10$NotARealHash.PleaseReplaceThisWithARealOne', 'employee', 1),(4, 4, 'carla.gomez', '$2y$10$NotARealHash.PleaseReplaceThisWithARealOne', 'employee', 1),(5, 5, 'marco.diaz', '$2y$10$NotARealHash.PleaseReplaceThisWithARealOne', 'employee', 1);
INSERT INTO `attendance_logs` (`id`, `employee_id`, `log_date`, `time_in`, `time_out`, `status`) VALUES (1, 1, '2025-08-18', '08:00:00', '17:05:00', 'On Time'),(2, 2, '2025-08-18', '09:15:00', '18:02:00', 'Late'),(3, 3, '2025-08-18', '07:55:00', '17:00:00', 'On Time'),(4, 4, '2025-08-18', '08:05:00', '17:03:00', 'On Time'),(5, 5, '2025-08-18', NULL, NULL, 'Absent'),(6, 1, '2025-08-19', '07:58:00', '17:01:00', 'On Time'),(7, 2, '2025-08-19', '08:30:00', '17:35:00', 'Late'),(8, 3, '2025-08-19', NULL, NULL, 'On Leave');
INSERT INTO `leave_types` (`id`, `name`) VALUES (1, 'Vacation'),(2, 'Sick'),(3, 'Paternity'),(4, 'Maternity'),(5, 'Bereavement');
INSERT INTO `leave_requests` (`id`, `employee_id`, `leave_type_id`, `date_from`, `date_to`, `days`, `reason`, `status`, `requested_at`) VALUES (1, 3, 2, '2025-08-19', '2025-08-19', '1.0', 'Fever and flu symptoms.', 'Approved', '2025-08-18 10:30:00'),(2, 5, 1, '2025-09-05', '2025-09-10', '6.0', 'Family vacation to Palawan.', 'Pending', '2025-08-19 11:00:00'),(3, 1, 2, '2025-07-21', '2025-07-21', '1.0', 'Dental appointment.', 'Declined', '2025-07-15 16:00:00');
INSERT INTO `leave_credits` (`employee_id`, `leave_type_id`, `balance`) VALUES (1, 1, 12.0), (1, 2, 9.0), (1, 3, 7.0), (1, 5, 3.0),(2, 1, 10.0), (2, 2, 10.0), (2, 4, 105.0), (2, 5, 3.0),(3, 1, 8.0), (3, 2, 5.0), (3, 3, 7.0), (3, 5, 2.0),(4, 1, 2.0), (4, 2, 3.0), (4, 3, 7.0), (4, 5, 3.0),(5, 1, 10.0), (5, 2, 10.0), (5, 3, 7.0), (5, 5, 3.0);

-- --------------------------------------------------------
--
-- AUTO_INCREMENT and INDEX settings
--
-- --------------------------------------------------------

ALTER TABLE `departments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `positions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `employees` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `attendance_logs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `leave_types` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `leave_requests` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `leave_credits` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- ADDING CONSTRAINTS
--
ALTER TABLE `positions` ADD CONSTRAINT `positions_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;
ALTER TABLE `employees` ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL;
ALTER TABLE `users` ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
ALTER TABLE `attendance_logs` ADD CONSTRAINT `attendance_logs_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
ALTER TABLE `leave_requests` ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`);
ALTER TABLE `leave_credits` ADD CONSTRAINT `leave_credits_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `leave_credits_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

COMMIT;
SET FOREIGN_KEY_CHECKS=1;