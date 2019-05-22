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
-- Dumping data for table `AIMER`
--

LOCK TABLES `AIMER` WRITE;
/*!40000 ALTER TABLE `AIMER` DISABLE KEYS */;
/*!40000 ALTER TABLE `AIMER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `CONCERNER`
--

LOCK TABLES `CONCERNER` WRITE;
/*!40000 ALTER TABLE `CONCERNER` DISABLE KEYS */;
/*!40000 ALTER TABLE `CONCERNER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `HASHTAG`
--

LOCK TABLES `HASHTAG` WRITE;
/*!40000 ALTER TABLE `HASHTAG` DISABLE KEYS */;
/*!40000 ALTER TABLE `HASHTAG` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `MENTION`
--

LOCK TABLES `MENTION` WRITE;
/*!40000 ALTER TABLE `MENTION` DISABLE KEYS */;
/*!40000 ALTER TABLE `MENTION` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `SUIVRE`
--

LOCK TABLES `SUIVRE` WRITE;
/*!40000 ALTER TABLE `SUIVRE` DISABLE KEYS */;
INSERT INTO `SUIVRE` VALUES (3,4,'2018-01-21 00:00:00',NULL,NULL),(6,5,'2018-01-21 00:00:00',NULL,NULL);
/*!40000 ALTER TABLE `SUIVRE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `TWEET`
--

LOCK TABLES `TWEET` WRITE;
/*!40000 ALTER TABLE `TWEET` DISABLE KEYS */;
/*!40000 ALTER TABLE `TWEET` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `USER`
--

LOCK TABLES `USER` WRITE;
/*!40000 ALTER TABLE `USER` DISABLE KEYS */;
INSERT INTO `USER` VALUES (3,'song','2018-01-21','song@gmail.com','song','','song'),(4,'songsong','2018-01-21','songsong@gmail.com','songsong','','songsong'),(5,'haha','2018-01-21','haha@gmail.com','hahah','','haha'),(6,'nihao','2018-01-21','nihao@gmail.com','nihao','','nihao');
/*!40000 ALTER TABLE `USER` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-21  4:07:51
