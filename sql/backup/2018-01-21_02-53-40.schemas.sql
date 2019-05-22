-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: dbproject_app
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1

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
-- Table structure for table `AIMER`
--

DROP TABLE IF EXISTS `AIMER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AIMER` (
  `IDUSER` int(11) NOT NULL,
  `IDTWEET` int(11) NOT NULL,
  `AIMERDATE` date DEFAULT NULL,
  PRIMARY KEY (`IDUSER`,`IDTWEET`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CONCERNER`
--

DROP TABLE IF EXISTS `CONCERNER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONCERNER` (
  `IDTWEET` int(11) NOT NULL,
  `IDHASH` int(11) NOT NULL,
  PRIMARY KEY (`IDTWEET`,`IDHASH`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `HASHTAG`
--

DROP TABLE IF EXISTS `HASHTAG`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HASHTAG` (
  `IDHASH` int(11) NOT NULL AUTO_INCREMENT,
  `HDATE` date DEFAULT NULL,
  `NOMTAG` char(32) DEFAULT NULL,
  PRIMARY KEY (`IDHASH`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MENTION`
--

DROP TABLE IF EXISTS `MENTION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MENTION` (
  `IDUSER` int(11) NOT NULL,
  `IDTWEET` int(11) NOT NULL,
  `MENTDATE` date DEFAULT NULL,
  PRIMARY KEY (`IDUSER`,`IDTWEET`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SUIVRE`
--

DROP TABLE IF EXISTS `SUIVRE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SUIVRE` (
  `IDUSER_SUIVRE` int(11) NOT NULL,
  `IDUSER_SUIVI` int(11) NOT NULL,
  `SUIV_DATE` date NOT NULL,
  `DATESEEN` datetime DEFAULT NULL,
  PRIMARY KEY (`IDUSER_SUIVRE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TWEET`
--

DROP TABLE IF EXISTS `TWEET`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TWEET` (
  `IDTWEET` int(11) NOT NULL AUTO_INCREMENT,
  `IDUSER` int(11) NOT NULL,
  `IDTWEET_RÉDONDRE` int(11) NOT NULL,
  `TWDATE` date DEFAULT NULL,
  `CONTIENT` char(32) DEFAULT NULL,
  PRIMARY KEY (`IDTWEET`),
  KEY `FK_TWEET_USER` (`IDUSER`),
  KEY `FK_TWEET_TWEET` (`IDTWEET_RÉDONDRE`),
  CONSTRAINT `TWEET_ibfk_1` FOREIGN KEY (`IDUSER`) REFERENCES `USER` (`IDUSER`),
  CONSTRAINT `TWEET_ibfk_2` FOREIGN KEY (`IDTWEET_RÉDONDRE`) REFERENCES `TWEET` (`IDTWEET`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `USER`
--

DROP TABLE IF EXISTS `USER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER` (
  `IDUSER` int(11) NOT NULL AUTO_INCREMENT,
  `NOMU` char(32) DEFAULT NULL,
  `INSDATE` date DEFAULT NULL,
  `ADRESSE` char(32) DEFAULT NULL,
  `MP` char(32) DEFAULT NULL,
  `AVATAR_PATH` varchar(50) NOT NULL,
  `PRENOMUSER` char(20) NOT NULL,
  PRIMARY KEY (`IDUSER`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-21  1:56:17
