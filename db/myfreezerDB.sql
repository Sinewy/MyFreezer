CREATE DATABASE  IF NOT EXISTS `myfridge` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `myfridge`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: myfridge
-- ------------------------------------------------------
-- Server version	5.6.12-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `age`
--

DROP TABLE IF EXISTS `age`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `age` (
  `AgeGroup` varchar(10) NOT NULL,
  PRIMARY KEY (`AgeGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `age`
--

LOCK TABLES `age` WRITE;
/*!40000 ALTER TABLE `age` DISABLE KEYS */;
INSERT INTO `age` VALUES (' '),('15 or less'),('16 - 25'),('26 - 35'),('36 - 50'),('51 or more');
/*!40000 ALTER TABLE `age` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `ContentID` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(90) NOT NULL,
  `Amount` varchar(45) DEFAULT NULL,
  `Quantity` int(11) NOT NULL DEFAULT '1',
  `PackingDate` date DEFAULT NULL,
  `DrawerID` int(11) NOT NULL,
  `TimeCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TimeModified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ContentID`),
  UNIQUE KEY `ContentID_UNIQUE` (`ContentID`),
  KEY `drawerid_idx` (`DrawerID`),
  CONSTRAINT `drawerid` FOREIGN KEY (`DrawerID`) REFERENCES `drawer` (`DrawerID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content`
--

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` VALUES (1,'Domace cesnje izkoscicene','700g',3,'2011-05-05',2,'2014-07-11 22:49:39',NULL),(3,'Kokosova potica','Mala rola',1,'2014-07-08',2,'2014-07-11 22:51:00',NULL),(12,'polpetki zadetki','6',5,NULL,2,'2014-07-30 20:52:52',NULL),(14,'Novi contentek','komadici',1,NULL,1,'2014-07-31 15:03:12',NULL),(15,'Se en contentek','',1,NULL,1,'2014-07-31 15:04:13',NULL),(16,'novi cont','0.5kg',1,NULL,1,'2014-07-31 15:18:01',NULL),(24,'Con1','2',2,NULL,39,'2014-08-14 21:48:08',NULL),(25,'Poticka','polovixak',1,'2014-08-13',39,'2014-08-14 21:48:08',NULL),(31,'Conte nt za nov predla','',1,NULL,41,'2014-08-15 08:59:16',NULL),(32,'Pa se en da jih je vec','',1,NULL,41,'2014-08-15 08:59:16',NULL);
/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `Country` varchar(30) NOT NULL,
  PRIMARY KEY (`Country`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (' '),('Austria'),('Croatia'),('Denmark'),('Germany'),('Italy'),('Slovenia'),('Switzerland'),('UK'),('USA');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drawer`
--

DROP TABLE IF EXISTS `drawer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drawer` (
  `DrawerID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Description` varchar(90) DEFAULT NULL,
  `FreezerID` int(11) NOT NULL,
  `TimeCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`DrawerID`),
  UNIQUE KEY `DrawerID_UNIQUE` (`DrawerID`),
  KEY `freezerid_idx` (`FreezerID`),
  CONSTRAINT `freezerid` FOREIGN KEY (`FreezerID`) REFERENCES `freezer` (`FreezerID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drawer`
--

LOCK TABLES `drawer` WRITE;
/*!40000 ALTER TABLE `drawer` DISABLE KEYS */;
INSERT INTO `drawer` VALUES (1,'drawer3','v tem predalu je mmmmm mm',7,'2014-07-11 21:53:54'),(2,'theONE','some fine stuff',7,'2014-07-11 22:17:21'),(39,'Noicarski p','',7,'2014-08-14 21:48:08'),(41,'Prvi predalcek','Nov a skrinjica',7,'2014-08-15 08:59:16');
/*!40000 ALTER TABLE `drawer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `freezer`
--

DROP TABLE IF EXISTS `freezer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `freezer` (
  `FreezerID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Description` varchar(90) DEFAULT NULL,
  `Location` varchar(45) DEFAULT NULL,
  `Make` varchar(45) DEFAULT NULL,
  `TimeCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`FreezerID`),
  UNIQUE KEY `FreezerID_UNIQUE` (`FreezerID`),
  KEY `userid_idx` (`UserID`),
  CONSTRAINT `userid` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `freezer`
--

LOCK TABLES `freezer` WRITE;
/*!40000 ALTER TABLE `freezer` DISABLE KEYS */;
INSERT INTO `freezer` VALUES (2,'Boser','Tele je kr ena','','','2014-07-10 10:43:11',37),(7,'Nowa SKQ','BLa describbica','Loca2','MakerJon','2014-07-11 08:44:22',38),(14,'Nova','Opisna','Lokacij','Maker','2014-08-15 08:57:38',38);
/*!40000 ALTER TABLE `freezer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gender`
--

DROP TABLE IF EXISTS `gender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gender` (
  `GenderType` varchar(15) NOT NULL,
  PRIMARY KEY (`GenderType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gender`
--

LOCK TABLES `gender` WRITE;
/*!40000 ALTER TABLE `gender` DISABLE KEYS */;
INSERT INTO `gender` VALUES (' '),('Female'),('Male');
/*!40000 ALTER TABLE `gender` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(80) NOT NULL,
  `Username` varchar(45) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `FirstName` varchar(45) DEFAULT NULL,
  `LastName` varchar(45) DEFAULT NULL,
  `Gender` varchar(45) DEFAULT NULL,
  `AgeGroup` varchar(45) DEFAULT NULL,
  `Country` varchar(45) DEFAULT NULL,
  `Avatar` longblob,
  `Activation` varchar(45) DEFAULT NULL,
  `TimeCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `UserID_UNIQUE` (`UserID`),
  UNIQUE KEY `Username_UNIQUE` (`Username`),
  UNIQUE KEY `Email_UNIQUE` (`Email`),
  KEY `Age_idx` (`AgeGroup`),
  KEY `Gender_idx` (`Gender`),
  KEY `Country_idx` (`Country`),
  CONSTRAINT `AgeGroup` FOREIGN KEY (`AgeGroup`) REFERENCES `age` (`AgeGroup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Country` FOREIGN KEY (`Country`) REFERENCES `country` (`Country`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Gender` FOREIGN KEY (`Gender`) REFERENCES `gender` (`GenderType`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (37,'nina@nian.si','nina','$2y$10$NjYyNTg3MTMyMDQ3ZjM0N.iXcxvzRE2tACJqgYr5EQ579NaOb.zBG','nina','nina','Female','26 - 35','Slovenia',NULL,'1','2014-07-03 20:32:28'),(38,'jure@jure.si','jure','$2y$10$YmUzNWVmMjBhZTRiMDhmMOSDK0lt0YP4qpv9sVtR8FkPbKJitdWE6','jure','jure','Male','26 - 35','Italy',NULL,'1','2014-07-10 10:37:02');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'myfridge'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-08-16 19:56:05
