-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2024 at 12:32 PM
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
-- Database: `coride`
--

-- --------------------------------------------------------

--
-- Table structure for table `carimage`
--

CREATE TABLE `carimage` (
  `IMAGE_ID` int(11) NOT NULL,
  `TRIP_ID` int(11) NOT NULL,
  `PATH` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `RESERVATION_ID` int(11) NOT NULL,
  `TRIP_ID` int(11) NOT NULL,
  `STATE_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `START_DATE` date DEFAULT NULL,
  `END_DATE` date DEFAULT NULL,
  `NB_SEATS_RESERVED` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `ROLE_ID` int(11) NOT NULL,
  `ROLE_NAME` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `STATE_ID` int(11) NOT NULL,
  `STATE_NAME` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip`
--

CREATE TABLE `trip` (
  `TRIP_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `DEPARTURE_CITY` varchar(50) DEFAULT NULL,
  `DEPARTURE_LOCATION` varchar(150) DEFAULT NULL,
  `ARRIVAL_` varchar(50) DEFAULT NULL,
  `ARRIVAL_1` varchar(150) DEFAULT NULL,
  `DEPARTURE_TIME` time DEFAULT NULL,
  `TRIP_START_DATE` date DEFAULT NULL,
  `TRIP_END_DATE` date DEFAULT NULL,
  `AVAILABLE_SEATS` int(11) DEFAULT NULL,
  `PRICE_PER_PASSENGER` float(8,2) DEFAULT NULL,
  `DESCRIPTION` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `USER_ID` int(11) NOT NULL,
  `ROLE_ID` int(11) NOT NULL,
  `TRIP_ID` int(11) DEFAULT NULL,
  `FNAME` varchar(50) DEFAULT NULL,
  `LNAME` varchar(50) DEFAULT NULL,
  `EMAIL` varchar(150) DEFAULT NULL,
  `PASSWORD_HASH` varchar(128) DEFAULT NULL,
  `PHONE` varchar(10) DEFAULT NULL,
  `AVATAR` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carimage`
--
ALTER TABLE `carimage`
  ADD PRIMARY KEY (`IMAGE_ID`),
  ADD KEY `FK_CARIMAGE_DISPLAY_TRIP` (`TRIP_ID`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`RESERVATION_ID`),
  ADD KEY `FK_RESERVAT_HAVE_STATE` (`STATE_ID`),
  ADD KEY `FK_RESERVAT_IS_MADE_F_TRIP` (`TRIP_ID`),
  ADD KEY `FK_RESERVAT_MAKE_USER` (`USER_ID`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`ROLE_ID`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`STATE_ID`);

--
-- Indexes for table `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`TRIP_ID`),
  ADD KEY `FK_TRIP_PROPOSE_USER` (`USER_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_ID`),
  ADD KEY `FK_USER_POSSESS_ROLE` (`ROLE_ID`),
  ADD KEY `FK_USER_PROPOSE2_TRIP` (`TRIP_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carimage`
--
ALTER TABLE `carimage`
  ADD CONSTRAINT `FK_CARIMAGE_DISPLAY_TRIP` FOREIGN KEY (`TRIP_ID`) REFERENCES `trip` (`TRIP_ID`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `FK_RESERVAT_HAVE_STATE` FOREIGN KEY (`STATE_ID`) REFERENCES `state` (`STATE_ID`),
  ADD CONSTRAINT `FK_RESERVAT_IS_MADE_F_TRIP` FOREIGN KEY (`TRIP_ID`) REFERENCES `trip` (`TRIP_ID`),
  ADD CONSTRAINT `FK_RESERVAT_MAKE_USER` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `FK_TRIP_PROPOSE_USER` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_USER_POSSESS_ROLE` FOREIGN KEY (`ROLE_ID`) REFERENCES `role` (`ROLE_ID`),
  ADD CONSTRAINT `FK_USER_PROPOSE2_TRIP` FOREIGN KEY (`TRIP_ID`) REFERENCES `trip` (`TRIP_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
