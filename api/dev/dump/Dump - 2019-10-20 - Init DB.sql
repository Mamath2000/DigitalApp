-- MySQL dump 10.13  Distrib 8.0.17, for Win64 (x86_64)
--
-- Host: 192.168.1.196    Database: DA
-- ------------------------------------------------------
-- Server version	8.0.18

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

--
-- Dumping data for table `associates`
--

LOCK TABLES `associates` WRITE;
/*!40000 ALTER TABLE `associates` DISABLE KEYS */;
INSERT INTO `associates` VALUES (1,'Anne','DELORT','ADE',NULL,'anne.delort@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(2,'Anthony','MICHAUD','AMI',NULL,'anthony.michaud@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(3,'Alexandre','OBLET','AOB',NULL,'alexandre.oblet@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(4,'Aicha','REVEL','ARE',NULL,'aicha.revel@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(5,'Céline','BROUSSAN','CBN',NULL,'celine.broussan@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(6,'Constance','BRONCHAIN','CBR',NULL,'constance.bronchain@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(7,'Céline','CÉSAR','CCE',NULL,'celine.cesar@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(8,'Christophe','MARRAILLAC','CMA',NULL,'christophe.marraillac@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(9,'Clémentine','MARTIN','CMN',NULL,'clementine.martin@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(10,'Claire','SAVARIAUD','CSA',NULL,'claire.savariaud@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(11,'Dorian','DEZANNEAU','DDE',NULL,'dorian.dezanneau@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(12,'Damien','MAUREL','DMA',NULL,'damien.maurel@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(13,'David','VELLAR','DVE',NULL,'david.vellar@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(14,'Eric','POCHON','EPO',NULL,'eric.pochon@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(15,'Gaël','ROUZIC','GRO',NULL,'gaël.rouzic@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(16,'Germain','ZOLTEK','GZO',NULL,'germain.zoltek@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(17,'Isabelle','ORVEN','IOR',NULL,'isabelle.orven@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(18,'Léa','BAILLARD','LBA',NULL,'lea.baillard@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(19,'Mathieu','BEAU','MBE',NULL,'mathieu.beau@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(20,'Myriam','BONZON','MBO',NULL,'myriam.bonzon@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(21,'Myriam','DJEMIAI','MDJ',NULL,'myriam.djemiai@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(22,'Michaël','KARAMAN','MKA',NULL,'michaël.karaman@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(23,'Maxence','LOTHE','MLO',NULL,'maxence.lothe@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(24,'Nathalie','RIALLAND','NRI',NULL,'nathalie.rialland@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(25,'Olivier','DATT','ODA',NULL,'olivier.datt@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(26,'Olivier','DUBOIS','ODU',NULL,'olivier.dubois@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(27,'Pierrick','MERLET','PME',NULL,'pierrick.merlet@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(28,'Renan','BEAUFRÈRE','RBE',NULL,'renan.beaufrere@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(29,'Renaud','GAUDIN','RGA',NULL,'renaud.gaudin@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(30,'Sébastien','NORMAND','SNO',NULL,'sebastien.normand@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(31,'Samuel','RAMBAUD','SRA',NULL,'samuel.rambaud@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(32,'Thomas','LITROL','TLI',NULL,'thomas.litrol@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(33,'Tanguy','RALLON','TRA',NULL,'tanguy.rallon@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1),(34,'Vincent','DALGÉ','VDA',NULL,'vincent.dalge@digital-associates.fr',NULL,NULL,5,'2017-07-01','2099-12-31','2019-01-01 00:00:00',1,'2019-01-01 00:00:00',1);
/*!40000 ALTER TABLE `associates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cells`
--

LOCK TABLES `cells` WRITE;
/*!40000 ALTER TABLE `cells` DISABLE KEYS */;
/*!40000 ALTER TABLE `cells` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `history`
--

LOCK TABLES `history` WRITE;
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
/*!40000 ALTER TABLE `history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `linesdef`
--

LOCK TABLES `linesdef` WRITE;
/*!40000 ALTER TABLE `linesdef` DISABLE KEYS */;
INSERT INTO `linesdef` VALUES (1,'MENS','BRUT','Brut - Cadre (€)','',100,0,'','2099-12-31 00:00:00',1,0,0,0),(2,'MENS','NET_PAYE','Net payé (€)','',110,0,'','2099-12-31 00:00:00',1,0,0,0),(3,'MENS','NET','Net avant PAS (€)','',120,0,'','2099-12-31 00:00:00',1,0,0,0),(4,'MENS','COUT_TOTAL','COÛT TOTAL EMPLOYEUR','',199,1,'[BRUT]+[NET]','2099-12-31 00:00:00',1,1,0,0),(5,'MENS','JOURS','NbJours travaillés','',200,0,'','2099-12-31 00:00:00',1,0,0,0),(6,'MENS','TJ','TJM','',210,1,'[PROD_TOTAL] / [JOURS]','2099-12-31 00:00:00',1,1,0,0),(7,'MENS','PREST','Presta services 20%','',220,0,'','2099-12-31 00:00:00',1,0,0,0),(8,'MENS','PREST_AUTRE','Prod. activités annexes','',230,0,'','2099-12-31 00:00:00',1,0,0,0),(9,'MENS','PROD_TOTAL','PRODUCTION TOTALE','',299,1,'[PREST] + [PREST_AUTRE]','2099-12-31 00:00:00',1,1,0,0),(10,'MENS','ACHAT_FO','ACHATS FO CONSO','',399,0,'','2099-12-31 00:00:00',1,0,0,0),(11,'MENS','GESTION','Prestations Gestion Lascii','',400,0,'','2099-12-31 00:00:00',1,0,0,0),(12,'MENS','CHARGE_EXT_FIXE','Charges externes fixes','',410,0,'','2099-12-31 00:00:00',1,0,0,0),(13,'MENS','LOC_VL','Location véhicules','',420,0,'','2099-12-31 00:00:00',1,0,0,0),(14,'MENS','FRAIS_DEPL','Frais déplacements','',430,0,'','2099-12-31 00:00:00',1,0,0,0),(15,'MENS','CHARGE_EXT','AUTRES CHARGES EXTERNES','',499,1,'[GESTION]+[CHARGE_EXT_FIXE]+[LOC_VL]+[FRAIS_DEPL]','2099-12-31 00:00:00',1,1,0,0),(16,'MENS','TAXES','IMPOTS ET TAXES','',599,0,'','2099-12-31 00:00:00',1,0,0,0),(17,'MENS','CHARGE_PERSO_FIXE','Charges de personnel fixes','',600,0,'','2099-12-31 00:00:00',1,0,0,0),(18,'MENS','CHARGE_PERSO_VAR','Charges de personnel variables','',610,0,'','2099-12-31 00:00:00',1,0,0,0),(19,'MENS','INTERESS','Interessement Forfait Social','',620,0,'','2099-12-31 00:00:00',1,0,0,0),(20,'MENS','CHARGE_PERSO','CHARGES DE PERSONNEL','',699,1,'[CHARGE_PERSO_FIXE]+[CHARGE_PERSO_VAR]+[INTERESS]','2099-12-31 00:00:00',1,0,0,0),(21,'MENS','TRESO','E.B.E. APPROCHÉ*','',999,1,'[PROD_TOTAL]-([ACHAT_FO]+[CHARGE_EXT]+[TAXES]+[CHARGE_PERSO])','2099-12-31 00:00:00',1,0,0,0);
/*!40000 ALTER TABLE `linesdef` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `locks`
--

LOCK TABLES `locks` WRITE;
/*!40000 ALTER TABLE `locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (1,'Paye',NULL),(2,'Comptable',NULL);
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `reportsitem`
--

LOCK TABLES `reportsitem` WRITE;
/*!40000 ALTER TABLE `reportsitem` DISABLE KEYS */;
INSERT INTO `reportsitem` VALUES (1,1,1,100),(2,1,2,110),(3,1,3,120),(4,1,4,199),(5,2,5,200),(6,2,6,210),(7,2,7,220),(8,2,8,230),(9,2,9,299),(10,2,10,399),(11,2,11,400),(12,2,12,410),(13,2,13,420),(14,2,14,430),(15,2,15,499),(16,2,16,599),(17,2,17,600),(18,2,18,610),(19,2,19,620),(20,2,20,699),(21,2,21,999);
/*!40000 ALTER TABLE `reportsitem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `type`
--

LOCK TABLES `type` WRITE;
/*!40000 ALTER TABLE `type` DISABLE KEYS */;
INSERT INTO `type` VALUES (1,'UserStatus','Actif',10,'bg-success',NULL),(2,'UserStatus','Inactif',20,'bg-secondary',NULL),(5,'AssociateStatus','Actif/Employé',10,'bg-success',NULL),(6,'AssociateStatus','Inactif',20,'bg-secondary',NULL),(8,'UserProfile','Associé',10,'bg-light',NULL),(9,'UserProfile','Invité',90,NULL,NULL),(10,'UserProfile','Admin',20,'bg-info',NULL);
/*!40000 ALTER TABLE `type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@admin.com','$2y$10$vsT3nOn2j2k.DpTw9VtCGeAikwt5nJbH1GadPWLK1UaebfWQA1BwS',10,1,'2019-08-04 14:26:18',1,'2019-08-22 21:15:51',1),(2,'mamath','mathieu.beau@gmail.com','$2y$10$4ZC.aoIS9IJuJ3QSxOyGE.u50vTuS2smKfQkClYmkVuDxOZRUM8lK',10,1,'2019-08-04 14:25:47',1,'2019-08-22 21:15:51',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-20 14:33:59
