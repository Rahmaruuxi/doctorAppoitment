-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 04:23 PM
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
-- Database: `doctor_appointment`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `role_id`) VALUES
(3, 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `status`) VALUES
(5, 10, 20, '2025-01-11', '16:00:00', 'confirmed'),
(6, 9, 16, '2025-01-11', '14:00:00', 'confirmed'),
(7, 11, 17, '2025-01-11', '10:00:00', 'pending'),
(8, 11, 21, '2025-01-11', '14:00:00', 'confirmed'),
(13, 3, 19, '2025-01-12', '16:00:00', 'confirmed'),
(15, 3, 19, '2025-01-12', '14:00:00', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_phone` varchar(15) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `available_times` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `name`, `email`, `contact_phone`, `specialty`, `available_times`) VALUES
(1, 4, 'Dr. Mike Johnson', 'malik@gmail.com', '5675467457343', 'Pediatrician', '10:00am \r\n2:00pm'),
(2, 4, 'Dr. John Doe', 'Dr.JohnDoe@gmail.com', '090898987', 'Cardiologist', '1:00pm\r\n4:00pm'),
(3, 4, 'Dr. Jane Smith', 'Dr.JaneSmith@gmail.com', '654545344324', 'Neurologist', '7:00am \r\n4:00pm'),
(14, 4, 'Dr. John Doe', 'dr.johndoe1@gmail.com', '5551234567', 'Cardiologist', '9:00am \r\n5:00pm'),
(15, 4, 'Dr. Emily Taylor', 'dr.emilytaylor1@gmail.com', '5559876543', 'Orthopedic', '8:00am \r\n3:00pm'),
(16, 4, 'Dr. Michael Johnson', 'dr.michaeljohnson1@gmail.com', '5552223333', 'Pediatrician', '10:00am \r\n6:00pm'),
(17, 4, 'Dr. Sarah Lee', 'dr.sarahlee1@gmail.com', '5554445555', 'Dermatologist', '8:00am \r\n4:00pm'),
(18, 4, 'Dr. David Brown', 'dr.davidbrown1@gmail.com', '5556667777', 'Psychiatrist', '9:00am \r\n5:00pm'),
(19, 4, 'Dr. Lisa Green', 'dr.lisagreen1@gmail.com', '5558889999', 'Gynecologist', '7:30am \r\n4:30pm'),
(20, 4, 'Dr. Robert Wilson', 'dr.robertwilson1@gmail.com', '5551112233', 'General Practitioner', '8:30am \r\n5:30pm'),
(21, 4, 'Dr. Jennifer Adams', 'dr.jenniferadams1@gmail.com', '5553334455', 'Endocrinologist', '10:00am \r\n6:00pm');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `name`, `email`, `contact_phone`, `address`) VALUES
(3, 4, 'rahma abdirahman mukhtar ', 'rahmaruun442@gmail.com', '78687676', 'dayniile'),
(4, 5, 'sabaah abdirahman mukhtar', 'sabaah@gmail.com', '78687676', 'norway'),
(5, 6, 'xamse abdirahman mukhtar', 'xamse@gmail.com', '767576565', 'dayniile'),
(6, 7, 'abdirahman mukhtar', 'abdirahman@gmail.com', '655654654', 'hodan'),
(8, 11, 'yahye ali ahmed', 'yahye@gmail.com', '78687676', 'warta nabada'),
(9, 12, 'maryan dahir mohamuud', 'maryandahir557@gmail', '78767575', 'hodan'),
(10, 14, 'ifraah ahmed ali', 'ifraah@gmail.com', '98778', 'warta nabada'),
(11, 15, 'ayaan ahmed eymow', 'ayaan@gmail.com', '78676', 'kaaran'),
(12, 16, 'shankaroon ahmed ali', 'shankaroon@gmail.com', '6775765', 'ghhfhg'),
(13, 19, 'xawa ahmed farah', 'xawa@gmail.com', '677856464', 'hodan'),
(14, 20, 'ibrahim maxamed maxamud', 'ibrahim@gmail.com', '6765765674', 'hodan'),
(15, 21, 'farhiyo ahmed ali', 'farhiyo@gmail.com', '675456463', 'hodan'),
(16, 22, 'xaliimo maxamed faarah', 'xaliimo@gmail.com', '45342323', 'ceelasha biyaha'),
(18, 25, 'hani ahmed ali', 'hani@gmail.com', '654534543', 'hodan');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'doctor'),
(3, 'patient');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `stdNo` int(11) NOT NULL,
  `stdName` varchar(100) DEFAULT NULL,
  `stdAddress` varchar(255) DEFAULT NULL,
  `stddob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stdNo`, `stdName`, `stdAddress`, `stddob`) VALUES
(1, 'John Doe', '123 Elm St', '2000-05-15'),
(2, 'Jane Smith', '456 Oak St', '1999-08-22'),
(3, 'Alice Johnson', '789 Pine St', '2001-02-10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role_id`) VALUES
(4, 'rahmaruun442@gmail.com', '11', 3),
(5, 'sabaah@gmail.com', '11', 3),
(6, 'xamse@gmail.com', '11', 3),
(7, 'abdirahman@gmail.com', '11', 3),
(8, 'anisa@gmail.com', '11', 3),
(9, 'rahma@gmail.com', '11', 1),
(11, 'yahye@gmail.com', '11', 3),
(12, 'maryandahir557@gmail', '11', 3),
(13, 'ra12@gmail.com', '11', 2),
(14, 'ifraah@gmail.com', '11', 3),
(15, 'ayaan@gmail.com', '11', 3),
(16, 'shankaroon@gmail.com', '11', 3),
(18, 'maryan@gmail.com', '11', 1),
(19, 'xawa@gmail.com', '11', 3),
(20, 'ibrahim@gmail.com', '11', 3),
(21, 'farhiyo@gmail.com', '11', 3),
(22, 'xaliimo@gmail.com', '11', 3),
(23, 'xaliy@gmail.com', '11', 3),
(25, 'hani@gmail.com', '11', 3),
(26, 'abdiaziz@gmail.com', '11', 3),
(27, 'rahma123@gmail.com', '11', 2),
(28, 'tt@gmail.com', '11', 2),
(31, 'rahma442222@gmail.com', '11', 2),
(32, 'amiira@gmail.com', 'rahma11', 1),
(33, 'yacquub@gmail.com', 'yacquub', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stdNo`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `admins_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
