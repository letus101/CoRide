-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 04 juin 2024 à 21:26
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `coride`
--

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `RESERVATION_ID` int(11) NOT NULL,
  `TRIP_ID` int(11) NOT NULL,
  `STATE_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `DRIVER_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`RESERVATION_ID`, `TRIP_ID`, `STATE_ID`, `USER_ID`, `DRIVER_ID`) VALUES
(31, 36, 1, 1, 8),
(33, 38, 1, 1, 8),
(34, 41, 1, 1, 10);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `ROLE_ID` int(11) NOT NULL,
  `ROLE_NAME` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`ROLE_ID`, `ROLE_NAME`) VALUES
(1, 'Driver'),
(2, 'Passenger');

-- --------------------------------------------------------

--
-- Structure de la table `state`
--

CREATE TABLE `state` (
  `STATE_ID` int(11) NOT NULL,
  `STATE_NAME` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `state`
--

INSERT INTO `state` (`STATE_ID`, `STATE_NAME`) VALUES
(1, 'Pending'),
(2, 'Accepted'),
(3, 'Rejected'),
(4, 'Canceled');

-- --------------------------------------------------------

--
-- Structure de la table `trip`
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
  `AVAILABLE_SEATS` int(11) DEFAULT NULL,
  `PRICE_PER_PASSENGER` float(8,2) DEFAULT NULL,
  `DESCRIPTION` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trip`
--

INSERT INTO `trip` (`TRIP_ID`, `USER_ID`, `DEPARTURE_CITY`, `DEPARTURE_LOCATION`, `ARRIVAL_`, `ARRIVAL_1`, `DEPARTURE_TIME`, `TRIP_START_DATE`, `AVAILABLE_SEATS`, `PRICE_PER_PASSENGER`, `DESCRIPTION`) VALUES
(36, 8, 'casa', 'a', 'rabat', 'b', '18:13:00', '2024-06-04', 1, 320.00, 'tfytfy'),
(38, 8, 'casa', 'sidi moumen', 'tanger', 'bni makata', '19:22:00', '2024-06-03', 2, 100.00, 'shshj'),
(41, 10, 'casa', 'a', 'rabat', 'b', '20:50:00', '2024-06-03', 1, 320.00, 'dwdq');

-- --------------------------------------------------------

--

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `USER_ID` int(11) NOT NULL,
  `ROLE_ID` int(11) NOT NULL,
  `FNAME` varchar(50) DEFAULT NULL,
  `LNAME` varchar(50) DEFAULT NULL,
  `EMAIL` varchar(150) DEFAULT NULL,
  `PASSWORD_HASH` varchar(128) DEFAULT NULL,
  `PHONE` varchar(10) DEFAULT NULL,
  `AVATAR` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`USER_ID`, `ROLE_ID`, `FNAME`, `LNAME`, `EMAIL`, `PASSWORD_HASH`, `PHONE`, `AVATAR`) VALUES
(1, 2,  'Oussama', 'TAKI', 'oussamataki366@gmail.com', '$2y$10$BRItCLyhf/GEmLWN2frJ3.sUK7.H37jQLTla.dsuxOsq4hdZROhi6', '0387355356', 'avatar_1.jpeg'),
(2, 1,  'oussama', 'taki', 'oussamataki@gmail.com', '$2y$10$D9qiS9tpHeRXCy7xp272zO0D8QCor0R9AliRAnlFHdM0r/AZBpUqG', '0387355356', '10109025853.jpg'),
(3, 1,  'oussama', 'taki', 'Mime@gmail.com', '$2y$10$vp7exvY0vwSqE8bCCInAMuLYsAjsV.y45Kd/9jaqkdbbG7lf0TPyu', '0387355356', '10109025851.jpg'),
(4, 1,  'oussama', 'taki', 'oussamataki1@gmail.com', '$2y$10$XIObWnl8LxdrS7OK.V/AKude.93KfoMmB9KXp5bvv6QORSFM1pu4q', '0387355567', 'avatar_4.jpeg'),
(5, 1,  'dd', 'rr', 'oussamataki3@gmail.com', '$2y$10$TTbJ2diXNO6g.IKcs7y7PO/G.NmdProWG2LAUMVgrSNTJb.3KMR9m', '0387355356', '10109025851.jpg'),
(6, 1,  'oussama', 'taki', 'oussamataki36@gmail.com', '$2y$10$.Dry73u.eg.j14L.NXzDEOMB0A6j/HPujivxO.fVt0mcv8Gcbnedy', '0387355356', '10109025851.jpg'),
(7, 2,  'oussama', 'TAKI', 'admin@gmail.com', '$2y$10$eiL2apHPGEgWv27y9SHoAO754myWuwgDsq1djeRj2Rve/2KSFjl7S', '0387355356', 'avatar_7.jpeg'),
(8, 1,  'hakim', 'mime', 'Mime@gmail.com1', '$2y$10$bTTp4/zqZn8zzreCbGgf0Ov6o/Sj/2sb1CnFmlW6U4IO4akrfnC1q', '0387355356', 'download.jpeg'),
(9, 2,  'oussama', 'taki', 'oussamataki666@gmail.com', '$2y$10$g2PzoboH7hI5h3tJV0Q98ex73feaPzlqzamUclhuVNFtJaCwkXhKi', '0387355567', 'download (1).jpeg'),
(10, 1, 'farid', 'oussama', 'oussamafarid@gmail.com', '$2y$10$d1KO4I7zOxR1EwvTZRjk7O8yw5ieE4vpiLrxCJFOVafXgSIE/RGC6', '0387355356', 'download (1).jpeg'),
(11, 1, 'youssef ', 'ketaje', 'oussamataki3666@gmail.com', '$2y$10$xNu6KsbbFzHjcAIGN1nKJOvxD3SsIU8/08hOo4gDXXFFGBVp/3zpu', '0387355356', 'download.jpeg');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `user`

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`RESERVATION_ID`),
  ADD KEY `FK_RESERVAT_HAVE_STATE` (`STATE_ID`),
  ADD KEY `FK_RESERVAT_IS_MADE_F_TRIP` (`TRIP_ID`),
  ADD KEY `FK_RESERVAT_MAKE_USER` (`USER_ID`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`ROLE_ID`);

--
-- Index pour la table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`STATE_ID`);

--
-- Index pour la table `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`TRIP_ID`),
  ADD KEY `FK_TRIP_PROPOSE_USER` (`USER_ID`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_ID`),
  ADD KEY `FK_USER_POSSESS_ROLE` (`ROLE_ID`),

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `RESERVATION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `ROLE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `state`
--
ALTER TABLE `state`
  MODIFY `STATE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `trip`
--
ALTER TABLE `trip`
  MODIFY `TRIP_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `FK_RESERVAT_HAVE_STATE` FOREIGN KEY (`STATE_ID`) REFERENCES `state` (`STATE_ID`),
  ADD CONSTRAINT `FK_RESERVAT_IS_MADE_F_TRIP` FOREIGN KEY (`TRIP_ID`) REFERENCES `trip` (`TRIP_ID`),
  ADD CONSTRAINT `FK_RESERVAT_MAKE_USER` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Contraintes pour la table `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `FK_TRIP_PROPOSE_USER` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_USER_POSSESS_ROLE` FOREIGN KEY (`ROLE_ID`) REFERENCES `role` (`ROLE_ID`),
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
