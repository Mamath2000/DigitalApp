CREATE SCHEMA `DA` DEFAULT CHARACTER SET utf8 ;


-- MySQL dump 10.13  Distrib 8.0.17, for Win64 (x86_64)
--
-- Host: localhost    Database: api
-- ------------------------------------------------------
-- Server version	8.0.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `associates`
--

DROP TABLE IF EXISTS `associates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `associates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `trig` varchar(4) NOT NULL,
  `idUsers` bigint(20) unsigned DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `contactNumber` varchar(64) DEFAULT NULL,
  `address` text,
  `idAssociateStatus` bigint(20) unsigned NOT NULL COMMENT '1=actif/employee,2=inactif,9=archive',
  `startDate` date DEFAULT NULL,
  `endDate` date NOT NULL DEFAULT '2099-12-31',
  `creationDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` bigint(20) unsigned DEFAULT 1,
  `lastUpdateDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdateBy` bigint(20) unsigned DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='Accosiates of Digital Associate';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cells`
--

DROP TABLE IF EXISTS `cells`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cells` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idLinesDef` bigint(20) unsigned NOT NULL,
  `idAssociates` bigint(20) unsigned NOT NULL,
  `refLabel` varchar(45) NOT NULL,
  `year` int(4) NOT NULL,
  `value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `source` varchar(45) DEFAULT NULL,
  `dateValueDate` date NOT NULL,
  `dateRealDate` date NOT NULL,
  `isReadonly` tinyint(4) NOT NULL DEFAULT '0',
  `creationDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` bigint(20) unsigned DEFAULT 1,
  `lastUpdateDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdateBy` bigint(20) unsigned DEFAULT 1,
  `isHidden` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `dateValIdx` (`dateValueDate`),
  KEY `dateRealIdx` (`dateRealDate`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `refType` varchar(100) NOT NULL,
  `refId` bigint(20) unsigned NOT NULL,
  `operation` varchar(10) DEFAULT NULL,
  `colName` varchar(200) DEFAULT NULL,
  `oldValue` mediumtext,
  `newValue` mediumtext,
  `operationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idUsers` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historyUser` (`idUsers`),
  KEY `historyRef` (`refType`,`refId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `linesdef`
--

DROP TABLE IF EXISTS `linesdef`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `linesdef` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `code` varchar(45) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `sortOrder` int(3) NOT NULL,
  `isCalculate` tinyint(4) DEFAULT '0',
  `calculationRule` varchar(100) DEFAULT NULL,
  `validityEndDate` datetime NOT NULL DEFAULT '2099-12-31 00:00:00',
  `hasAutoSum` tinyint(4) NOT NULL DEFAULT '1',
  `isReadonly` tinyint(4) NOT NULL DEFAULT '0',
  `isHidden` tinyint(4) NOT NULL DEFAULT '0',
  `canNull` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lineCode_UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reportsitem`
--

DROP TABLE IF EXISTS `reportsitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reportsitem` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idReports` bigint(20) unsigned NOT NULL,
  `idLinesDef` bigint(20) unsigned NOT NULL,
  `sortOrder` int(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lindef_FK_idx` (`idLinesDef`),
  KEY `FK1_idx` (`idReports`),
  CONSTRAINT `FK1` FOREIGN KEY (`idReports`) REFERENCES `reports` (`id`),
  CONSTRAINT `FK2` FOREIGN KEY (`idLinesDef`) REFERENCES `linesdef` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `type`
--

DROP TABLE IF EXISTS `type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `scope` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sortOrder` int(3) unsigned DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `description` mediumtext,
  PRIMARY KEY (`id`),
  KEY `typeScope` (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(512) NOT NULL,
  `idUserProfile` bigint(20) unsigned NOT NULL COMMENT '1=Associé,2=invité,9=Admin',
  `idUserStatus` bigint(20) unsigned NOT NULL COMMENT '1=actif,2=inactif,3=register,9=archive',
  `creationDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` bigint(20) unsigned DEFAULT 1,
  `lastUpdateDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdateBy` bigint(20) unsigned DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `locks`
--

DROP TABLE IF EXISTS `locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idAssociates` bigint(20) unsigned NOT NULL,
  `idReports` bigint(20) unsigned NOT NULL,
  `isLock` tinyint(4) DEFAULT '1',
  `year` int(4) NOT NULL,
  `creationDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` bigint(20) unsigned DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;



/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-10 15:23:33

