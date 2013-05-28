-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: gv_webcam
-- ------------------------------------------------------
-- Server version	5.5.31-0ubuntu0.12.10.1

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
-- Table structure for table `api_cache`
--

DROP TABLE IF EXISTS `api_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_cache` (
  `api_name` char(12) NOT NULL,
  `hash` char(64) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_cache`
--

LOCK TABLES `api_cache` WRITE;
/*!40000 ALTER TABLE `api_cache` DISABLE KEYS */;
INSERT INTO `api_cache` VALUES ('lastfm','635ca26352ba642e550461a291d3d0f3','2013-05-28 22:11:04'),('twitter','d6497fd91e1bc59fd82e7b53022e51ea','2013-05-28 22:11:04');
/*!40000 ALTER TABLE `api_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webcam_images`
--

DROP TABLE IF EXISTS `webcam_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webcam_images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` char(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `mime_type` varchar(12) NOT NULL,
  `hash` char(64) NOT NULL,
  `uploaded` tinyint(1) NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webcam_images`
--

LOCK TABLES `webcam_images` WRITE;
/*!40000 ALTER TABLE `webcam_images` DISABLE KEYS */;
INSERT INTO `webcam_images` VALUES (1,'video0-20130528-214437.jpg','',19054,'image/jpeg','1496a35759270c480ecd67dab53a3382',0,0,'2013-05-28 20:44:37'),(2,'video0-20130528-214721.jpg','/var/www/sites/work/workcam-beta/public/webcam/2013/05/28/video0-20130528-214721.jpg',19054,'image/jpeg','d41d8cd98f00b204e9800998ecf8427e',0,0,'2013-05-28 20:47:21'),(3,'video0-20130528-215015.jpg','/var/www/sites/work/workcam-beta/public/webcam/2013/05/28/video0-20130528-215015.jpg',19054,'image/jpeg','d41d8cd98f00b204e9800998ecf8427e',0,0,'2013-05-28 20:50:15'),(4,'video0-20130528-215108.jpg','/var/www/sites/work/workcam-beta/public/webcam/2013/05/28/video0-20130528-215108.jpg',19054,'image/jpeg','d41d8cd98f00b204e9800998ecf8427e',0,0,'2013-05-28 20:51:08'),(5,'video0-20130528-215403.jpg','/var/www/sites/work/workcam-beta/public/webcam/2013/05/28/video0-20130528-215403.jpg',19054,'image/jpeg','d41d8cd98f00b204e9800998ecf8427e',0,0,'2013-05-28 20:54:03'),(6,'video0-20130528-215445.jpg','/var/www/sites/work/workcam-beta/public/webcam/2013/05/28/video0-20130528-215445.jpg',19054,'image/jpeg','d59721f6efe2a4c586be94879b47f2b3',0,0,'2013-05-28 20:54:45'),(7,'webcam.jpg','/var/www/sites/work/workcam-beta/public/webcam.jpg',19054,'image/jpeg','cfcd208495d565ef66e7dff9f98764da',0,0,'2013-05-28 21:03:26'),(8,'webcam.jpg','/var/www/sites/work/workcam-beta/public/webcam.jpg',19054,'image/jpeg','d59721f6efe2a4c586be94879b47f2b3',0,0,'2013-05-28 21:04:06'),(9,'webcam.jpg','/var/www/sites/work/workcam-beta/public/webcam.jpg',19054,'image/jpeg','d59721f6efe2a4c586be94879b47f2b3',1,1,'2013-05-28 21:11:19');
/*!40000 ALTER TABLE `webcam_images` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-05-28 23:22:50
