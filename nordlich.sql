-- phpMyAdmin SQL Dump
-- version 5.2.1
-- Generation Time: Aug 21, 2025 at 10:00 AM
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

-- Tables --
DROP TABLE IF EXISTS `departments`, `positions`, `employees`, `users`, `attendance_logs`, `leave_types`, `leave_requests`, `leave_credits`;

CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB;

CREATE TABLE `positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL,
  PRIMARY KEY (`id`), KEY `department_id` (`department_id`)
) ENGINE=InnoDB;

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
  PRIMARY KEY (`id`), UNIQUE KEY `employee_code` (`employee_code`), UNIQUE KEY `email` (`email`), KEY `position_id` (`position_id`)
) ENGINE=InnoDB;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('employee','hr','admin') NOT NULL DEFAULT 'employee',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`), UNIQUE KEY `username` (`username`), UNIQUE KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB;

CREATE TABLE `attendance_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` enum('On Time','Late','Absent','On Leave') NOT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `employee_log_date` (`employee_id`,`log_date`)
) ENGINE=InnoDB;

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB;

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `days` decimal(4,1) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('Pending','Approved','Declined') NOT NULL DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`), KEY `employee_id` (`employee_id`), KEY `leave_type_id` (`leave_type_id`)
) ENGINE=InnoDB;

CREATE TABLE `leave_credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `balance` decimal(5,1) NOT NULL DEFAULT 0.0,
  PRIMARY KEY (`id`), UNIQUE KEY `employee_leave_type` (`employee_id`,`leave_type_id`), KEY `leave_type_id` (`leave_type_id`)
) ENGINE=InnoDB;

-- Sample Data --
INSERT INTO `departments` (`id`, `name`) VALUES (1, 'Pharmacy'),(2, 'Human Resources'),(3, 'Logistics'),(4, 'Accounting');
INSERT INTO `positions` (`id`, `title`, `department_id`) VALUES (1, 'Senior Pharmacist', 1),(2, 'HR Officer', 2), (3, 'Logistics Staff', 3);
INSERT INTO `employees` (`id`, `employee_code`, `full_name`, `email`, `position_id`, `area`, `hire_date`) VALUES
(1, 'EMP-1001', 'Aris Cruz', 'aris.cruz@nordlich.ph', 1, 'Quezon City', '2022-03-15'),
(2, 'EMP-1002', 'Sofia Reyes', 'sofia.reyes@nordlich.ph', 2, 'Makati City', '2023-01-20'),
(3, 'EMP-1003', 'Frank Gomez', 'frank.gomez@nordlich.ph', 3, 'Pasig City', '2024-05-10');

-- The password for 'employee' is 'password'
-- The password for 'hr' is 'password'
-- The password for 'frank' is 'frank001'
INSERT INTO `users` (`id`, `employee_id`, `username`, `password_hash`, `role`) VALUES
(1, 1, 'employee', '$2y$10$wAX8h.g1w3wL0DAp4A4h7uX66e.vhuAIw937F5r08m.xx93aY/tJ.', 'employee'),
(2, 2, 'hr', '$2y$10$wAX8h.g1w3wL0DAp4A4h7uX66e.vhuAIw937F5r08m.xx93aY/tJ.', 'hr'),
(3, 3, 'frank', '$2y$10$lU7i3N8L8a/AkO8.9fGj/uv.W2yR0.33gS/nxlcE9z87s3tT0LzI2', 'employee');

INSERT INTO `attendance_logs` (`employee_id`, `log_date`, `time_in`, `time_out`, `status`) VALUES
(1, '2025-08-20', '07:58:00', '17:02:00', 'On Time'),
(2, '2025-08-20', '08:45:00', '17:50:00', 'Late'),
(3, '2025-08-20', '08:01:00', '17:05:00', 'On Time'),
(1, '2025-08-21', '08:05:00', '17:10:00', 'On Time'),
(2, '2025-08-21', NULL, NULL, 'On Leave'),
(3, '2025-08-21', '09:05:00', '18:00:00', 'Late');

INSERT INTO `leave_types` (`id`, `name`) VALUES (1, 'Vacation'),(2, 'Sick'),(3, 'Paternity'),(4, 'Maternity'),(5, 'Bereavement');
INSERT INTO `leave_credits` (`employee_id`, `leave_type_id`, `balance`) VALUES 
(1, 1, 12.0), (1, 2, 9.0), (1, 3, 7.0), (1, 5, 3.0),
(2, 1, 10.0), (2, 2, 10.0), (2, 4, 105.0), (2, 5, 3.0),
(3, 1, 10.0), (3, 2, 10.0), (3, 3, 7.0), (3, 5, 3.0);

-- Constraints & AUTO_INCREMENT --
ALTER TABLE `departments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `positions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `employees` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `attendance_logs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `leave_types` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `leave_requests` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `leave_credits` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `positions` ADD CONSTRAINT `positions_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;
ALTER TABLE `employees` ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL;
ALTER TABLE `users` ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
ALTER TABLE `attendance_logs` ADD CONSTRAINT `attendance_logs_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
ALTER TABLE `leave_requests` ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`);
ALTER TABLE `leave_credits` ADD CONSTRAINT `leave_credits_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `leave_credits_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE;

COMMIT;
SET FOREIGN_KEY_CHECKS=1;