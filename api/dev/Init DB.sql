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
-- Dumping data for table `linesdef`
--

LOCK TABLES `linesdef` WRITE;
/*!40000 ALTER TABLE `linesdef` DISABLE KEYS */;
INSERT INTO `linesdef` VALUES 
    (1,'MENS','BRUT','Brut - Cadre (€)','',100,0,'','2099-12-31 00:00:00',1,0,0,0),
    (2,'MENS','NET_PAYE','Net payé (€)','',110,0,'','2099-12-31 00:00:00',1,0,0,0),
    (3,'MENS','NET','Net avant PAS (€)','',120,0,'','2099-12-31 00:00:00',1,0,0,0),
    (4,'MENS','COUT_TOTAL','COÛT TOTAL EMPLOYEUR','',199,1,'[BRUT]+[NET]','2099-12-31 00:00:00',1,1,0,0),
    (5,'MENS','JOURS','NbJours travaillés','',200,0,'','2099-12-31 00:00:00',1,0,0,0),
    (6,'MENS','TJ','TJM','',210,1,'[PROD_TOTAL] / [JOURS]','2099-12-31 00:00:00',1,1,0,0),
    (7,'MENS','PREST','Presta services 20%','',220,0,'','2099-12-31 00:00:00',1,0,0,0),
    (8,'MENS','PREST_AUTRE','Prod. activités annexes','',230,0,'','2099-12-31 00:00:00',1,0,0,0),
    (9,'MENS','PROD_TOTAL','PRODUCTION TOTALE','',299,1,'[PREST] + [PREST_AUTRE]','2099-12-31 00:00:00',1,1,0,0),
    (10,'MENS','ACHAT_FO','ACHATS FO CONSO','',399,0,'','2099-12-31 00:00:00',1,0,0,0),
    (11,'MENS','GESTION','Prestations Gestion Lascii','',400,0,'','2099-12-31 00:00:00',1,0,0,0),
    (12,'MENS','CHARGE_EXT_FIXE','Charges externes fixes','',410,0,'','2099-12-31 00:00:00',1,0,0,0),
    (13,'MENS','LOC_VL','Location véhicules','',420,0,'','2099-12-31 00:00:00',1,0,0,0),
    (14,'MENS','FRAIS_DEPL','Frais déplacements','',430,0,'','2099-12-31 00:00:00',1,0,0,0),
    (15,'MENS','CHARGE_EXT','AUTRES CHARGES EXTERNES','',499,1,'[GESTION]+[CHARGE_EXT_FIXE]+[LOC_VL]+[FRAIS_DEPL]','2099-12-31 00:00:00',1,1,0,0),
    (16,'MENS','TAXES','IMPOTS ET TAXES','',599,0,'','2099-12-31 00:00:00',1,0,0,0),
    (17,'MENS','CHARGE_PERSO_FIXE','Charges de personnel fixes','',600,0,'','2099-12-31 00:00:00',1,0,0,0),
    (18,'MENS','CHARGE_PERSO_VAR','Charges de personnel variables','',610,0,'','2099-12-31 00:00:00',1,0,0,0),
    (19,'MENS','INTERESS','Interessement Forfait Social','',620,0,'','2099-12-31 00:00:00',1,0,0,0),
    (20,'MENS','CHARGE_PERSO','CHARGES DE PERSONNEL','',699,1,'[CHARGE_PERSO_FIXE]+[CHARGE_PERSO_VAR]+[INTERESS]','2099-12-31 00:00:00',1,0,0,0),
    (21,'MENS','TRESO','E.B.E. APPROCHÉ*','',999,1,'[PROD_TOTAL]-([ACHAT_FO]+[CHARGE_EXT]+[TAXES]+[CHARGE_PERSO])','2099-12-31 00:00:00',1,0,0,0);
/*!40000 ALTER TABLE `linesdef` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES 
    (1,'Paye',NULL),
    (2,'Comptable',NULL);
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `reportsitem`
--

LOCK TABLES `reportsitem` WRITE;
/*!40000 ALTER TABLE `reportsitem` DISABLE KEYS */;
INSERT INTO `reportsitem` VALUES 
    (1,1,1,100),     (2,1,2,110),
    (3,1,3,120),     (4,1,4,199),
    (5,2,5,200),     (6,2,6,210),
    (7,2,7,220),     (8,2,8,230),
    (9,2,9,299),     (10,2,10,399),
    (11,2,11,400),   (12,2,12,410),
    (13,2,13,420),   (14,2,14,430),
    (15,2,15,499),   (16,2,16,599),
    (17,2,17,600),   (18,2,18,610),
    (19,2,19,620),   (20,2,20,699),
    (21,2,21,999);
/*!40000 ALTER TABLE `reportsitem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `type`
--

LOCK TABLES `type` WRITE;
/*!40000 ALTER TABLE `type` DISABLE KEYS */;
INSERT INTO `type` VALUES 
    (1,'UserStatus','Actif',10,'bg-success',NULL),
    (2,'UserStatus','Inactif',20,'bg-secondary',NULL),
    (5,'AssociateStatus','Actif/Employé',10,'bg-success',NULL),
    (6,'AssociateStatus','Inactif',20,'bg-secondary',NULL),
    (8,'UserProfile','Associé',10,'bg-light',NULL),
    (9,'UserProfile','Invité',90,NULL,NULL),
    (10,'UserProfile','Admin',20,'bg-info',NULL);
/*!40000 ALTER TABLE `type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES 
    (1,'admin','admin@admin.com','$2y$10$vsT3nOn2j2k.DpTw9VtCGeAikwt5nJbH1GadPWLK1UaebfWQA1BwS',10,1,'2019-08-04 14:26:18',1,'2019-08-22 21:15:51',1),
    (2,'mamath','mathieu.beau@gmail.com','$2y$10$4ZC.aoIS9IJuJ3QSxOyGE.u50vTuS2smKfQkClYmkVuDxOZRUM8lK',10,1,'2019-08-04 14:25:47',1,'2019-08-22 21:15:51',1);
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

-- Dump completed on 2019-10-10 15:23:33