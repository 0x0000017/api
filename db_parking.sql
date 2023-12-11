-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2023 at 01:17 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_parking`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_parklogs`
--

CREATE TABLE `tbl_parklogs` (
  `p_id` int(11) NOT NULL,
  `p_name` text NOT NULL,
  `p_phone` text NOT NULL,
  `p_vbrand` text NOT NULL,
  `p_vmodel` text NOT NULL,
  `p_plateNo` text NOT NULL,
  `p_time` text NOT NULL,
  `p_out` text DEFAULT NULL,
  `p_date` text NOT NULL,
  `p_spot` int(11) NOT NULL,
  `p_vtype` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_parklogs`
--

INSERT INTO `tbl_parklogs` (`p_id`, `p_name`, `p_phone`, `p_vbrand`, `p_vmodel`, `p_plateNo`, `p_time`, `p_out`, `p_date`, `p_spot`, `p_vtype`, `status`) VALUES
(35, 'Kelly Asales', '9023214522', 'Suzuki', 'Raider', 'GCS123', '07:30', '2023-12-11 6:17 PM', '2023-12-04', 1, 'motorcycle', 0),
(36, 'Ronsal Salero', '9201235222', 'Toyota', 'XZX', 'GCS002', '08:30', '2023-12-11 6:18 PM', '2023-12-04', 1, 'car', 0),
(37, 'Fukiko Mikoto', '9102352488', 'Honda', 'TMX', 'GCS003', '09:30', '2023-12-11 6:26 PM', '2023-12-05', 2, 'motorcycle', 0),
(38, 'Masuka Sukanako', '9102587422', 'Lamborghini', 'XXZ', 'GCS005', '10:30', '2023-12-09 5:30 PM', '2023-12-06', 3, 'car', 0),
(39, 'Renz Dale', '9123456789', 'Honda', 'TMX', 'HTM999', '07:30', '2023-12-09 6:05 PM', '2023-12-05', 1, 'motorcycle', 0),
(40, 'Dale Renz', '9123452369', 'Toyota', 'ZZZ', 'XXX123', '10:30', '2023-12-09 6:05 PM', '2023-12-07', 2, 'car', 0),
(41, 'Henry', '0', 'Lamborghini', 'Ferrari', 'X99', '07:45', NULL, '2023-12-11', 9, 'motorcycle', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `username`, `password`) VALUES
(1, 'adrian', '$2y$10$M4uMEpMUD8PVUl6AIZ/If.bpdIepL.d2FLKJU9I8.EulnX5HKVA1C'),
(2, 'admin', '$2y$10$IHvFkomyqXftHsPryzLhKOoYnuGjU6kGlbfI1z4M6czAsLbc7tMYO');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_parklogs`
--
ALTER TABLE `tbl_parklogs`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_parklogs`
--
ALTER TABLE `tbl_parklogs`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
