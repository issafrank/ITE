-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2025 at 09:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nordlich`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_logs`
--

CREATE TABLE `attendance_logs` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` enum('On Time','Late','Absent','Leave') NOT NULL DEFAULT 'On Time',
  `area` varchar(100) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_logs`
--

INSERT INTO `attendance_logs` (`id`, `employee_id`, `log_date`, `time_in`, `time_out`, `status`, `area`, `notes`, `created_at`, `updated_at`) VALUES
(5, 12, '2025-08-17', '00:00:00', '00:00:00', 'On Time', NULL, '', '2025-08-17 13:48:15', '2025-08-17 16:59:54'),
(6, 9, '2025-08-17', '21:48:00', '21:48:00', 'On Time', 'Makati Area', '', '2025-08-17 13:48:30', '2025-08-17 13:48:30'),
(7, 6, '2025-08-17', '21:48:00', '21:48:00', 'Late', 'Cavite Area', '', '2025-08-17 13:48:55', '2025-08-17 13:48:55');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_id` int(4) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `area` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `employment_type` varchar(20) NOT NULL,
  `hire_date` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `full_name`, `email`, `area`, `department`, `position`, `salary`, `employment_type`, `hire_date`, `status`, `notes`, `created_at`) VALUES
(6, 0, 'qwe', 'admin@gmail.com', 'QC', 'HR', 'dfsadf', 0.00, 'Full-time', '2025-09-06', 'Probationary', '', '2025-08-16 11:25:50'),
(9, 3, 'qeqe', 'qeqe@gmail.com', 'Manila', 'Logistics', '1313', 0.00, 'Full-time', '2025-08-29', 'Probationary', '13123', '2025-08-16 17:00:38'),
(12, 1, 'curry', '22018244@bcp.edu.ph', 'Bulacan', 'Logistics', 'dsfas', 0.00, 'Full-time', '2025-08-17', 'Probationary', '', '2025-08-17 13:28:50'),
(13, 6, 'Maria Test', 'maria@test.com', 'Makati Area', 'HR', 'HR Officer', 28000.00, 'Full-Time', '2023-01-15', 'Active', NULL, '2025-08-17 14:00:09');

-- --------------------------------------------------------

--
-- Table structure for table `employee_archive`
--

CREATE TABLE `employee_archive` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `area` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `position` varchar(150) NOT NULL,
  `hire_date` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `deleted_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_archive`
--

INSERT INTO `employee_archive` (`id`, `employee_id`, `full_name`, `area`, `department`, `position`, `hire_date`, `status`, `deleted_on`) VALUES
(3, '1', '1', 'QC', 'Pharmacy', 'asdad', '2025-08-20', 'Active', '2025-08-17 04:14:16'),
(4, '9', 'John Sample', 'Cavite Area', 'Logistics', 'Warehouse Staff', '0000-00-00', '', '2025-08-17 17:05:22');

-- --------------------------------------------------------

--
-- Table structure for table `leave_credits`
--

CREATE TABLE `leave_credits` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `vacation` int(11) DEFAULT 10,
  `sick` int(11) DEFAULT 10,
  `paternity_maternity` int(11) DEFAULT 3,
  `bereavement` int(11) DEFAULT 3,
  `awol_suspended` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_credits`
--

INSERT INTO `leave_credits` (`id`, `employee_id`, `vacation`, `sick`, `paternity_maternity`, `bereavement`, `awol_suspended`) VALUES
(4, 6, 8, 6, 3, 1, 0),
(5, 9, 5, 9, 1, 1, 1),
(6, 12, 9, 4, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_type` varchar(50) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `leave_type`, `date_from`, `date_to`, `reason`, `status`, `requested_at`) VALUES
(1, 6, 'Vacation', '2025-08-20', '2025-08-22', 'Family event', 'Approved', '2025-08-17 15:22:23'),
(2, 9, 'Sick', '2025-08-15', '2025-08-16', 'Flu', 'Declined', '2025-08-17 15:22:23'),
(3, 12, 'Bereavement', '2025-08-18', '2025-08-18', 'Funeral', 'Pending', '2025-08-17 15:22:23');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_sent`
--

CREATE TABLE `payroll_sent` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `area` varchar(50) DEFAULT NULL,
  `pay_period_start` date DEFAULT NULL,
  `pay_period_end` date DEFAULT NULL,
  `basic_salary` decimal(10,2) DEFAULT NULL,
  `total_deductions` decimal(10,2) DEFAULT NULL,
  `net_pay` decimal(10,2) DEFAULT NULL,
  `present_days` int(11) DEFAULT NULL,
  `late_days` int(11) DEFAULT NULL,
  `absent_days` int(11) DEFAULT NULL,
  `leave_days` int(11) DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `performance_trials`
--

CREATE TABLE `performance_trials` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `trial_type` varchar(80) DEFAULT NULL,
  `checkin_frequency` enum('Daily','Weekly','Biweekly','Monthly') DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `objective` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `notify_email` varchar(150) DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `status` enum('Ongoing','Completed','Failed') DEFAULT 'Ongoing',
  `result_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `performance_trials`
--

INSERT INTO `performance_trials` (`id`, `employee_id`, `trial_type`, `checkin_frequency`, `start_date`, `end_date`, `objective`, `notes`, `notify_email`, `attachment_path`, `status`, `result_notes`, `created_at`, `updated_at`) VALUES
(1, 6, 'Behavioral', 'Weekly', '2025-08-18', '2025-08-31', 'asfsf', 'sfsaf', 'asdfas@gmail.com', 'uploads/trials/1755451805_Screenshot__4_.png', 'Completed', NULL, '2025-08-17 17:30:05', '2025-08-17 17:48:04'),
(2, 1, 'Behavioral', 'Daily', '2025-08-18', '2025-08-31', 'sdf', 'sfasf', 'fsafsf@gmail.com', 'uploads/trials/1755452910_Screenshot__1_.png', 'Ongoing', NULL, '2025-08-17 17:48:30', NULL),
(3, 6, 'Return-to-Work', 'Daily', '2025-08-18', '2025-08-31', '', '', 'fsafsf@gmail.com', NULL, 'Completed', NULL, '2025-08-17 17:55:34', '2025-08-17 17:55:37');

-- --------------------------------------------------------

--
-- Table structure for table `termination_actions`
--

CREATE TABLE `termination_actions` (
  `id` int(11) NOT NULL,
  `termination_id` int(11) NOT NULL,
  `action_taken` enum('Requested','Approved','Declined','Completed') NOT NULL,
  `remarks` text DEFAULT NULL,
  `acted_by` varchar(100) DEFAULT NULL,
  `acted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `termination_requests`
--

CREATE TABLE `termination_requests` (
  `id` int(11) NOT NULL,
  `trial_id` int(11) DEFAULT NULL,
  `request_source` enum('Trial','Manual') DEFAULT 'Trial',
  `employee_id` int(11) NOT NULL,
  `position_snapshot` varchar(80) DEFAULT NULL,
  `area_snapshot` varchar(80) DEFAULT NULL,
  `salary_snapshot` decimal(10,2) DEFAULT NULL,
  `termination_type` enum('For Cause','Redundancy','End of Contract','Other') DEFAULT 'Other',
  `reason` text DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `requested_by` varchar(100) NOT NULL DEFAULT 'HR',
  `decision_by` varchar(100) DEFAULT NULL,
  `decision_notes` text DEFAULT NULL,
  `decided_at` datetime DEFAULT NULL,
  `status` enum('Pending','Approved','Declined','Completed') DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `termination_requests`
--

INSERT INTO `termination_requests` (`id`, `trial_id`, `request_source`, `employee_id`, `position_snapshot`, `area_snapshot`, `salary_snapshot`, `termination_type`, `reason`, `effective_date`, `requested_by`, `decision_by`, `decision_notes`, `decided_at`, `status`, `notes`, `attachment_path`, `created_at`, `updated_at`) VALUES
(1, 2, 'Trial', 1, 'dsfas', 'Bulacan', 0.00, '', 'afasf', '2025-08-18', 'HR', NULL, NULL, NULL, 'Pending', 'asdfsfsa', 'uploads/termreq/1755452931_Screenshot__2_.png', '2025-08-17 17:48:51', '2025-08-17 17:48:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_logs`
--
ALTER TABLE `attendance_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_emp_date` (`employee_id`,`log_date`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employee_archive`
--
ALTER TABLE `employee_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_credits`
--
ALTER TABLE `leave_credits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_leave_employee` (`employee_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_sent`
--
ALTER TABLE `payroll_sent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `performance_trials`
--
ALTER TABLE `performance_trials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `termination_actions`
--
ALTER TABLE `termination_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `termination_id` (`termination_id`);

--
-- Indexes for table `termination_requests`
--
ALTER TABLE `termination_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_termreq_status` (`status`),
  ADD KEY `idx_termreq_emp` (`employee_id`),
  ADD KEY `idx_termreq_trial` (`trial_id`),
  ADD KEY `idx_termreq_created` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_logs`
--
ALTER TABLE `attendance_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employee_archive`
--
ALTER TABLE `employee_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_credits`
--
ALTER TABLE `leave_credits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payroll_sent`
--
ALTER TABLE `payroll_sent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `performance_trials`
--
ALTER TABLE `performance_trials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `termination_actions`
--
ALTER TABLE `termination_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `termination_requests`
--
ALTER TABLE `termination_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance_logs`
--
ALTER TABLE `attendance_logs`
  ADD CONSTRAINT `fk_att_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_credits`
--
ALTER TABLE `leave_credits`
  ADD CONSTRAINT `fk_leave_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `performance_trials`
--
ALTER TABLE `performance_trials`
  ADD CONSTRAINT `performance_trials_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `termination_actions`
--
ALTER TABLE `termination_actions`
  ADD CONSTRAINT `termination_actions_ibfk_1` FOREIGN KEY (`termination_id`) REFERENCES `termination_requests` (`id`);

--
-- Constraints for table `termination_requests`
--
ALTER TABLE `termination_requests`
  ADD CONSTRAINT `termination_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
