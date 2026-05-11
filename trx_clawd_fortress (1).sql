-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2026 at 10:36 AM
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
-- Database: `trx_clawd_fortress`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `full_name`, `email`, `contact_number`, `company_name`, `address`, `profile_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Rafael Dela Cruz', 'rafael@northpeak.ph', '+63 917 100 1001', 'North Peak Systems', 'Makati City, Metro Manila', NULL, 'Active', '2026-02-12 14:40:00', NULL),
(2, 'Ariana Velasco', 'ariana@blueforge.ph', '+63 917 100 1002', 'BlueForge Logistics', 'Pasig City, Metro Manila', NULL, 'Active', '2026-02-25 14:40:00', NULL),
(3, 'Noel Serrano', 'noel@zenbyte.ph', '+63 917 100 1003', 'ZenByte Digital', 'Cebu City, Cebu', NULL, 'Inactive', '2026-03-09 14:40:00', NULL),
(4, 'Eliza Montemayor', 'eliza@aurelia.ph', '+63 917 100 1004', 'Aurelia HealthTech', 'Taguig City, Metro Manila', NULL, 'Active', '2026-03-18 14:40:00', NULL),
(5, 'Miguel Reyes', 'miguel@blueforge.ph', '+63 917 100 1005', 'BlueForge Logistics', 'Davao City, Davao del Sur', NULL, 'Active', '2026-03-30 14:40:00', NULL),
(6, 'Trisha Santos', 'trisha@neonworks.ph', '+63 917 100 1006', 'NeonWorks Studio', 'Bacoor, Cavite', NULL, 'Inactive', '2026-04-06 14:40:00', NULL),
(7, 'Patricia Luna', 'patricia@atlasworks.ph', '+63 917 100 1007', 'Atlas Works', 'Kawit, Cavite', NULL, 'Active', '2026-04-08 14:40:00', '2026-04-16 22:52:40'),
(8, 'Jerome Navarro', 'jerome@skyport.ph', '+63 917 100 1008', 'SkyPort Holdings', 'Quezon City, Metro Manila', NULL, 'Active', '2026-04-11 14:40:00', NULL),
(9, 'Sonia Villar', 'sonia@solis.ph', '+63 917 100 1009', 'Solis Energy', 'Iloilo City, Iloilo', NULL, 'Inactive', '2026-04-13 14:40:00', NULL),
(10, 'Dane Flores', 'dane@primecrest.ph', '+63 917 100 1010', 'PrimeCrest Ventures', 'Baguio City, Benguet', NULL, 'Active', '2026-04-14 14:40:00', NULL),
(11, 'Zacahriah Feliz', 'beatriciesr@gmail.com', '+63 917 100 1021', 'Dini-Flower Shop', 'Kawit, Cavite', NULL, 'Active', '2026-04-16 22:54:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_portal_accounts`
--

CREATE TABLE `client_portal_accounts` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_portal_accounts`
--

INSERT INTO `client_portal_accounts` (`id`, `client_id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 1, 'rafael.client', '$2y$10$LWttsYS0KEPvtuEEzf2iC.mKED/rvk/lABKZhYihGwjNcskZQ6RYm\r\n', '2026-04-15 14:40:00', NULL),
(2, 7, 'patricia.client', '$2y$10$vFpJmRRIvQf0ZbgW7ktmM.yLjgTWlYWgLG/sk46xntenjHEsW1fte', '2026-04-15 14:40:00', '2026-04-16 22:54:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin', '$2y$10$TFZbXc0v29LNQAxTullnJOQd8tkJx3tVOxPj4a3JZGyDjrWdmjHse', 'client', '2026-04-15 14:40:00', NULL),
(2, 'Zacahriah Feliz', 'macxln', '$2y$10$TFZbXc0v29LNQAxTullnJOQd8tkJx3tVOxPj4a3JZGyDjrWdmjHse', 'client', '2026-04-15 16:32:38', NULL),
(3, 'Jamsine Blues', 'jasmineblues', '$2y$10$t8p14UPdzqnp.KjZhuUPnOkhfYGB9EKzxbKRpVeh0tf.aStcOOnH.', 'client', '2026-04-15 17:59:40', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `client_portal_accounts`
--
ALTER TABLE `client_portal_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_id` (`client_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `client_portal_accounts`
--
ALTER TABLE `client_portal_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `client_portal_accounts`
--
ALTER TABLE `client_portal_accounts`
  ADD CONSTRAINT `fk_client_portal_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
