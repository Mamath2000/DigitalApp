-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le :  mer. 28 août 2019 à 21:54
-- Version du serveur :  10.3.15-MariaDB-1:10.3.15+maria~bionic-log
-- Version de PHP :  7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `api`
--

-- --------------------------------------------------------

--
-- Structure de la table `associate`
--

DROP TABLE IF EXISTS `associate`;
CREATE TABLE `associate` (
  `id` bigint(20) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `trig` varchar(4) NOT NULL,
  `idUser` bigint(20) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `contactNumber` varchar(64) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `idAssociateStatus` bigint(20) NOT NULL COMMENT '1=actif/employee,2=inactif,9=archive',
  `creationDateTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `createdBy` bigint(20) DEFAULT NULL,
  `lastUpdateDateTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastUpdateBy` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Accosiates of Digital Associate';

--
-- Déchargement des données de la table `associate`
--

INSERT INTO `associate` (`id`, `firstname`, `name`, `trig`, `idUser`, `email`, `contactNumber`, `address`, `idAssociateStatus`, `creationDateTime`, `createdBy`, `lastUpdateDateTime`, `lastUpdateBy`) VALUES
(2, 'Anthony', 'Michaud', 'AMI', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:21', 1, '2019-08-06 13:48:21', 1),
(3, 'Alexandre', 'Oblet', 'AOB', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:21', 1, '2019-08-06 13:48:21', 1),
(4, 'Aicha', 'Revel', 'ARE', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:22', 1, '2019-08-06 13:48:22', 1),
(6, 'Céline', 'Broussan', 'CBN', 51, NULL, NULL, NULL, 5, '2019-08-06 13:48:22', 1, '2019-08-26 15:00:42', 1),
(7, 'Céline', 'César', 'CCE', 38, 'aaa@aa.a', '13245679', NULL, 5, '2019-08-06 13:48:22', 1, '2019-08-26 14:52:35', 1),
(8, 'Christophe', 'Marraillac', 'CMA', 53, NULL, NULL, NULL, 5, '2019-08-06 13:48:22', 1, '2019-08-28 21:22:45', 1),
(9, 'Clémentine', 'Martin', 'CMN', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:22', 1, '2019-08-06 13:48:22', 1),
(10, 'Claire', 'Savariaud', 'CSA', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:22', 1, '2019-08-06 13:48:22', 1),
(12, 'Damien', 'Maurel', 'DMA', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:22', 1, '2019-08-06 13:48:22', 1),
(13, 'David', 'Vellar', 'DVE', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:22', 1, '2019-08-06 13:48:22', 1),
(14, 'Eric', 'Pochon', 'EPO', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:22', 1, '2019-08-06 13:48:22', 1),
(15, 'Gaël', 'Rouzic', 'GRO', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:22', 1, '2019-08-06 13:48:22', 1),
(17, 'Isabelle', 'Orven', 'IOR', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:22', 1, '2019-08-06 13:48:22', 1),
(18, 'Léa', 'Baillard', 'LBA', 34, '', '', '', 1, '2019-08-06 13:48:22', 1, '2019-08-13 21:34:23', 1),
(19, 'Mathieu', 'Beau', 'MBE', 2, 'mathieu.beau@gmail.com', '0763035181', '1 rue du lac, 86190 Poitiers', 5, '2019-08-06 13:48:22', 1, '2019-08-26 14:59:54', 1),
(20, 'Myriam1aaz', 'Bonzon123', 'MBO3', 2, 'dcvdc@dcd.cdxxxaaa', '0012451240000', '', 5, '2019-08-06 13:48:22', 1, '2019-08-26 14:33:09', 1),
(24, 'Nathalie', 'Rialland', 'NRI', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:23', 1, '2019-08-06 13:48:23', 1),
(25, 'Olivier', 'DATT', 'ODA', 1, 'fdfgdf', '123456654', '', 6, '2019-08-06 13:48:23', 1, '2019-08-26 14:20:40', 1),
(27, 'Pierrick', 'Merlet', 'PME', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:23', 1, '2019-08-06 13:48:23', 1),
(30, 'Sébastien', 'Normand', 'SNO', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:23', 1, '2019-08-06 13:48:23', 1),
(31, 'Samuel', 'Rambaud', 'SRA', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:23', 1, '2019-08-06 13:48:23', 1),
(32, 'Thomas', 'Litrol', 'TLI', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:23', 1, '2019-08-06 13:48:23', 1),
(33, 'Tanguy', 'RALLON', 'TRA', NULL, NULL, NULL, NULL, 1, '2019-08-06 13:48:23', 1, '2019-08-06 13:48:23', 1),
(34, 'Vincent', 'Dalgé', 'VDA', 54, 'a@aa.a', NULL, NULL, 5, '2019-08-06 13:48:23', 1, '2019-08-26 14:44:35', 1);

-- --------------------------------------------------------

--
-- Structure de la table `element`
--

DROP TABLE IF EXISTS `element`;
CREATE TABLE `element` (
  `id` bigint(20) NOT NULL,
  `idGroupDef` bigint(20) NOT NULL,
  `idLineDef` bigint(20) NOT NULL,
  `idAssociate` bigint(20) NOT NULL,
  `value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `source` varchar(45) DEFAULT NULL,
  `dateValueDate` date NOT NULL,
  `dateRealDate` date NOT NULL,
  `isReadonly` tinyint(4) NOT NULL DEFAULT 0,
  `creationDateTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `createdBy` bigint(20) DEFAULT NULL,
  `lastUpdateDateTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastUpdateBy` bigint(20) DEFAULT NULL,
  `idHidden` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `element`
--

INSERT INTO `element` (`id`, `idGroupDef`, `idLineDef`, `idAssociate`, `value`, `source`, `dateValueDate`, `dateRealDate`, `isReadonly`, `creationDateTime`, `createdBy`, `lastUpdateDateTime`, `lastUpdateBy`, `idHidden`) VALUES
(1, 1, 1, 19, '3.00', 'Import', '2019-03-01', '2019-03-01', 0, '2019-08-24 13:21:37', NULL, '2019-08-24 13:21:37', NULL, 0),
(2, 1, 1, 19, '16.50', 'Import', '2019-04-01', '2019-04-01', 0, '2019-08-24 13:21:37', NULL, '2019-08-24 13:21:37', NULL, 0),
(3, 1, 1, 19, '16.00', 'Import', '2019-05-01', '2019-05-01', 0, '2019-08-24 13:21:37', NULL, '2019-08-24 13:21:37', NULL, 0),
(4, 1, 1, 19, '17.00', 'Import', '2019-06-01', '2019-06-01', 0, '2019-08-24 13:21:37', NULL, '2019-08-24 13:21:37', NULL, 0),
(5, 1, 2, 19, '735.00', 'Import', '2019-03-01', '2019-03-01', 0, '2019-08-24 13:21:37', NULL, '2019-08-24 13:21:37', NULL, 0),
(6, 1, 2, 19, '735.00', 'Import', '2019-04-01', '2019-04-01', 0, '2019-08-24 13:21:37', NULL, '2019-08-24 13:21:37', NULL, 0),
(7, 1, 2, 19, '735.00', 'Import', '2019-05-01', '2019-05-01', 0, '2019-08-24 13:21:37', NULL, '2019-08-24 13:21:37', NULL, 0),
(8, 1, 2, 19, '735.00', 'Import', '2019-06-01', '2019-06-01', 0, '2019-08-24 13:21:37', NULL, '2019-08-24 13:21:37', NULL, 0),
(9, 1, 3, 6, '2205.00', 'Import', '2019-03-01', '2019-03-01', 0, '2019-08-24 13:21:37', NULL, '2019-08-24 13:21:37', NULL, 0),
(10, 1, 3, 19, '12127.50', 'Import', '2019-04-01', '2019-04-01', 0, '2019-08-24 13:21:37', NULL, '2019-08-24 13:21:37', NULL, 0),
(11, 1, 3, 19, '11760.00', 'Import', '2019-05-01', '2019-05-01', 0, '2019-08-24 13:21:38', NULL, '2019-08-24 13:21:38', NULL, 0),
(12, 1, 3, 19, '12495.00', 'Import', '2019-06-01', '2019-06-01', 0, '2019-08-24 13:21:38', NULL, '2019-08-24 13:21:38', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `groupdef`
--

DROP TABLE IF EXISTS `groupdef`;
CREATE TABLE `groupdef` (
  `id` bigint(20) NOT NULL,
  `code` varchar(45) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `sortOrder` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `groupdef`
--

INSERT INTO `groupdef` (`id`, `code`, `name`, `description`, `sortOrder`) VALUES
(1, 'FACTU', 'Facturation', 'Elements relatifs à la facturation', 10),
(2, 'FRAIS', 'Frais', 'Frais de l\'associé. (IK, frais de repas, autres...)', 20),
(3, 'PAIE', 'Paie', 'Elements de la paie.', 30),
(4, 'FACTU', 'Facturation', 'Elements relatifs à la facturation', 10),
(5, 'FRAIS', 'Frais', 'Frais de l\'associé. (IK, frais de repas, autres...)', 20),
(6, 'PAIE', 'Paie', 'Elements de la paie.', 30),
(7, 'FACTU', 'Facturation', 'Elements relatifs à la facturation', 10),
(8, 'FRAIS', 'Frais', 'Frais de l\'associé. (IK, frais de repas, autres...)', 20),
(9, 'PAIE', 'Paie', 'Elements de la paie.', 30);

-- --------------------------------------------------------

--
-- Structure de la table `linedef`
--

DROP TABLE IF EXISTS `linedef`;
CREATE TABLE `linedef` (
  `id` bigint(20) NOT NULL,
  `idGroupDef` bigint(20) NOT NULL,
  `code` varchar(45) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `sortOrder` int(3) NOT NULL,
  `isCalculate` tinyint(4) DEFAULT 0,
  `calculationRule` varchar(100) DEFAULT NULL,
  `hasAutoSum` tinyint(4) NOT NULL DEFAULT 1,
  `isReadonly` tinyint(4) NOT NULL DEFAULT 0,
  `isHidden` tinyint(4) NOT NULL DEFAULT 0,
  `validityEndDate` datetime NOT NULL DEFAULT '2099-12-31 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `linedef`
--

INSERT INTO `linedef` (`id`, `idGroupDef`, `code`, `name`, `description`, `sortOrder`, `isCalculate`, `calculationRule`, `hasAutoSum`, `isReadonly`, `isHidden`, `validityEndDate`) VALUES
(1, 1, 'WORK', 'Jours', 'Nombre de jours facturable', 100, 0, NULL, 1, 0, 0, '2099-12-31 00:00:00'),
(2, 1, 'TJ', 'TJM', 'Prix de vente. (calculé sur la base de la fac et des jours travaillés)', 140, 1, 'FAC/WORK', 1, 0, 0, '2099-12-31 00:00:00'),
(3, 1, 'FAC', 'Facturé', 'Montant facturé', 180, 0, NULL, 1, 0, 0, '2099-12-31 00:00:00'),
(4, 1, 'INCOME', 'Encaiss. HT', 'Montant réellement encaissés.', 220, 0, NULL, 1, 1, 0, '2099-12-31 00:00:00'),
(5, 1, 'CACHE', 'Test Caché', 'Ligne normalement cachée', 250, 0, NULL, 0, 1, 1, '2099-12-31 00:00:00'),
(6, 2, 'TRJ', 'nbre trajets', '', 200, 0, '', 1, 0, 0, '2099-12-31 00:00:00'),
(7, 2, 'KM/T', 'nbre km/trajets', '', 210, 0, '', 0, 0, 0, '2099-12-31 00:00:00'),
(8, 2, 'Tx/km', 'Taux', '', 220, 0, '', 0, 0, 0, '2099-12-31 00:00:00'),
(9, 2, 'IK', 'IK', '', 230, 1, '(Tx/km)*(TRJ)*(KM/T)', 1, 1, 0, '2099-12-31 00:00:00'),
(10, 2, 'FRAIS', 'frais', '', 240, 0, '', 1, 0, 0, '2099-12-31 00:00:00'),
(11, 2, 'REMB', 'frais versé', '', 250, 0, '', 1, 1, 0, '2099-12-31 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `taux`
--

DROP TABLE IF EXISTS `taux`;
CREATE TABLE `taux` (
  `id` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `name` varchar(256) NOT NULL,
  `code` varchar(20) NOT NULL,
  `value` decimal(10,3) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `createdBy` int(11) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modifiedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE `type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scope` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sortOrder` int(3) UNSIGNED DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`id`, `scope`, `name`, `sortOrder`, `color`, `description`) VALUES
(1, 'UserStatus', 'Actif', 10, 'bg-success', NULL),
(2, 'UserStatus', 'Inactif', 20, 'bg-secondary', NULL),
(3, 'UserStatus', 'Entregistré', 30, 'bg-warning', NULL),
(5, 'AssociateStatus', 'Actif/Employé', 10, 'bg-success', NULL),
(6, 'AssociateStatus', 'Inactif', 20, 'bg-secondary', NULL),
(8, 'UserProfile', 'Associé', 10, 'bg-light', NULL),
(9, 'UserProfile', 'Invité', 90, NULL, NULL),
(10, 'UserProfile', 'Admin', 20, 'bg-info', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` bigint(20) NOT NULL,
  `name` varchar(32) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(512) NOT NULL,
  `idUserProfile` bigint(20) NOT NULL COMMENT '1=Associé,2=invité,9=Admin',
  `idUserStatus` bigint(20) NOT NULL COMMENT '1=actif,2=inactif,3=register,9=archive',
  `creationDateTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `createdBy` bigint(20) DEFAULT NULL,
  `lastUpdateDateTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastUpdateBy` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Users of application';

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `idUserProfile`, `idUserStatus`, `creationDateTime`, `createdBy`, `lastUpdateDateTime`, `lastUpdateBy`) VALUES
(1, 'admin', 'admin@admin.com', '$2y$10$vsT3nOn2j2k.DpTw9VtCGeAikwt5nJbH1GadPWLK1UaebfWQA1BwS', 10, 1, '2019-08-04 14:26:18', 1, '2019-08-24 14:09:04', 1),
(2, 'mamath', 'mathieu.beau@gmail.com', '$2y$10$4ZC.aoIS9IJuJ3QSxOyGE.u50vTuS2smKfQkClYmkVuDxOZRUM8lK', 10, 1, '2019-08-04 14:25:47', 1, '2019-08-24 14:09:10', 1),
(23, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.comaaa&', '$2y$10$QiKsFT/wiVj5DcliEIqN4.8gaCkoPkkLiC6MWPZMiKlv5AWgXrMm2', 10, 1, '2019-08-07 00:20:21', 2, '2019-08-26 01:38:17', 1),
(37, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com', '$2y$10$5iQX6PghefWaKClPkootsOO0CmGe7bMcgSv9S.762ZKrWyHXfp3Qq', 8, 1, '2019-08-25 17:18:13', 1, '2019-08-25 17:18:13', 1),
(40, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com5', '$2y$10$w3nNdenW2dVQSTDJcgXNpuN30uDjq41TkmcYGBJuiXfpFN5cmy.nO', 8, 2, '2019-08-25 17:32:49', 1, '2019-08-26 00:09:26', 1),
(42, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com1236', '$2y$10$GBxVTLlq1qdGxU4eeE2FSeElj1JSBn4m0o6gC/PABdKqvYj4Ad6eK', 8, 3, '2019-08-25 17:42:44', 1, '2019-08-26 00:18:17', 1),
(44, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com', '$2y$10$Q9gNjKclENsG067C4PaGc.twZ4tovbxNJQXzrlQ90LKe1AcmsaUq6', 8, 1, '2019-08-25 17:48:02', 1, '2019-08-25 17:48:02', 1),
(46, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.coma123', '$2y$10$1hax8jTSVg5FQUjiEPeslOZE33r3jBigzz8f6vp0M6MX79YV.Sx7m', 8, 3, '2019-08-25 17:48:26', 1, '2019-08-26 00:24:24', 1),
(47, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com123', '$2y$10$6kOumgxjn24KfJyiCx.Rp.hnJaDrC0wX.3SPCIdOk.hrD0clOx7E.', 8, 1, '2019-08-25 17:48:40', 1, '2019-08-26 13:26:40', 1),
(48, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com', '$2y$10$TAsW7m0RkxSSckAVnohDZehIurl3pdvr7prpCCatfBbBEqwrFm2Sq', 8, 1, '2019-08-25 17:48:57', 1, '2019-08-25 17:48:57', 1),
(49, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.comaa', '$2y$10$eyJrqTnlzpBuw7wkcx7mL.Zlq2iSU6MdND9jzWEef3FYRxY8rpUTq', 8, 3, '2019-08-25 17:50:14', 1, '2019-08-26 00:24:48', 1),
(50, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.comerty', '$2y$10$f2V2h93SEFYHMsudsdAh4uCi5D3Glh9NyJBlkSWULmaYQp1ddJNYq', 10, 3, '2019-08-25 17:54:28', 1, '2019-08-26 01:24:56', 1),
(51, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com', '$2y$10$M0LSEtGdOmqsYNdNwPI24.5UX3P8FniAZuMQFXqx1u/O0hcDlWPQa', 8, 1, '2019-08-25 17:55:13', 1, '2019-08-25 17:55:13', 1),
(52, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com', '$2y$10$8FG.rZbdXm4Bi6mLG/WR5eQLtIeNteI44uuZxIMYDYwg6XtRmZphO', 8, 1, '2019-08-25 17:58:23', 1, '2019-08-25 17:58:23', 1),
(53, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com2aa', '$2y$10$/cJjFU5vM/DoY.J.VM.96uqors015m/nW2D6Eyh3jPEh/IeXpYK8u', 8, 2, '2019-08-25 23:03:10', 1, '2019-08-26 01:10:45', 1),
(54, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com2', '$2y$10$KnYY8/Wu65JhNHjFS5JPOe2a1W2ecONydU4R8i0MY9aoewmAe.uqu', 8, 1, '2019-08-25 23:09:03', 1, '2019-08-25 23:09:03', 1),
(55, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com2', '$2y$10$ubxLHlscc5K2U9cCqugG6uKRb5qlGDy6FZjSXac5FC1f2KJn.PSgK', 8, 1, '2019-08-25 23:11:30', 1, '2019-08-25 23:11:30', 1),
(56, 'Math - test UPDATEaaa', 'mathieu.beau@gmail.com2', '$2y$10$N.ddH.ndOQGoNU.nFzjy8uj7jMOTUUng8f/jYyFklvCtv6KC7fesm', 8, 1, '2019-08-25 23:38:04', 1, '2019-08-25 23:38:04', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `associate`
--
ALTER TABLE `associate`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `element`
--
ALTER TABLE `element`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dateValIdx` (`dateValueDate`),
  ADD KEY `dateRealIdx` (`dateRealDate`);

--
-- Index pour la table `groupdef`
--
ALTER TABLE `groupdef`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `linedef`
--
ALTER TABLE `linedef`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `taux`
--
ALTER TABLE `taux`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typeScope` (`scope`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `element`
--
ALTER TABLE `element`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `groupdef`
--
ALTER TABLE `groupdef`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `linedef`
--
ALTER TABLE `linedef`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `taux`
--
ALTER TABLE `taux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
