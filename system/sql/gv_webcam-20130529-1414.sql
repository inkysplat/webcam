-- MySQL dump 10.11
--
-- Host: localhost    Database: gandv_webcam
-- ------------------------------------------------------
-- Server version	5.0.96-community

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
  `datetime` timestamp NOT NULL default CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_cache`
--

LOCK TABLES `api_cache` WRITE;
/*!40000 ALTER TABLE `api_cache` DISABLE KEYS */;
INSERT INTO `api_cache` VALUES ('lastfm','3ce1947e377449e0225da1fbde4e4f9a','2013-05-29 11:22:38'),('twitter','fa5eb9700933738694a2c181fc8c11d7','2013-05-29 11:22:38');
/*!40000 ALTER TABLE `api_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webcam_images`
--

DROP TABLE IF EXISTS `webcam_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webcam_images` (
  `image_id` int(11) NOT NULL auto_increment,
  `filename` char(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `url` varchar(255) default NULL,
  `size` int(11) NOT NULL,
  `mime_type` varchar(12) NOT NULL,
  `hash` char(64) NOT NULL,
  `uploaded` tinyint(1) NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `datetime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webcam_images`
--

LOCK TABLES `webcam_images` WRITE;
/*!40000 ALTER TABLE `webcam_images` DISABLE KEYS */;
INSERT INTO `webcam_images` VALUES (1,'video0-20130528-214437.jpg','',NULL,19054,'image/jpeg','1496a35759270c480ecd67dab53a3382',0,0,'2013-05-28 20:44:37'),(2,'video0-20130528-214721.jpg','/var/www/sites/work/workcam-beta/public/webcam/2013/05/28/video0-20130528-214721.jpg',NULL,19054,'image/jpeg','d41d8cd98f00b204e9800998ecf8427e',0,0,'2013-05-28 20:47:21'),(3,'video0-20130528-215015.jpg','/var/www/sites/work/workcam-beta/public/webcam/2013/05/28/video0-20130528-215015.jpg',NULL,19054,'image/jpeg','d41d8cd98f00b204e9800998ecf8427e',0,0,'2013-05-28 20:50:15'),(4,'video0-20130528-215108.jpg','/var/www/sites/work/workcam-beta/public/webcam/2013/05/28/video0-20130528-215108.jpg',NULL,19054,'image/jpeg','d41d8cd98f00b204e9800998ecf8427e',0,0,'2013-05-28 20:51:08'),(5,'video0-20130528-215403.jpg','/var/www/sites/work/workcam-beta/public/webcam/2013/05/28/video0-20130528-215403.jpg',NULL,19054,'image/jpeg','d41d8cd98f00b204e9800998ecf8427e',0,0,'2013-05-28 20:54:03'),(6,'video0-20130528-215445.jpg','/var/www/sites/work/workcam-beta/public/webcam/2013/05/28/video0-20130528-215445.jpg',NULL,19054,'image/jpeg','d59721f6efe2a4c586be94879b47f2b3',0,0,'2013-05-28 20:54:45'),(7,'webcam.jpg','/var/www/sites/work/workcam-beta/public/webcam.jpg',NULL,19054,'image/jpeg','cfcd208495d565ef66e7dff9f98764da',0,0,'2013-05-28 21:03:26'),(8,'webcam.jpg','/var/www/sites/work/workcam-beta/public/webcam.jpg',NULL,19054,'image/jpeg','d59721f6efe2a4c586be94879b47f2b3',0,0,'2013-05-28 21:04:06'),(9,'webcam.jpg','/var/www/sites/work/workcam-beta/public/webcam.jpg',NULL,19054,'image/jpeg','d59721f6efe2a4c586be94879b47f2b3',1,1,'2013-05-28 21:11:19'),(10,'video0-20130528-2328.jpg','/home/webcam/public_html/webcam/2013/05/28/video0-20130528-2328.jpg',NULL,69301,'image/jpeg','29eadd7ac0bb09d49903d0de9923a97d',1,1,'2013-05-28 23:28:16'),(11,'video0-20130529-0829.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0829.jpg',NULL,60157,'image/jpeg','9338e8b83926a799ee89b7c7d597dbf2',1,1,'2013-05-29 08:29:27'),(12,'video0-20130529-0830.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0830.jpg',NULL,174642,'image/jpeg','9c35c1b4eae6ea01e60024bc7269334f',1,0,'2013-05-29 08:30:03'),(13,'video0-20130529-0835.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0835.jpg',NULL,177508,'image/jpeg','3b107a530c80a6e718334c7f7f20161d',1,0,'2013-05-29 08:35:02'),(14,'video0-20130529-0840.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0840.jpg',NULL,171879,'image/jpeg','ee6fdeaf7e4bc7af216cba613a912189',1,0,'2013-05-29 08:40:03'),(15,'video0-20130529-0845.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0845.jpg',NULL,172874,'image/jpeg','8d7d38d6cec50c5f13df3f8c6b2101cb',1,1,'2013-05-29 08:45:03'),(16,'video0-20130529-0850.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0850.jpg',NULL,171125,'image/jpeg','b09c16fcd90889cfdaa59ac9ac76894e',1,1,'2013-05-29 08:50:03'),(17,'video0-20130529-0855.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0855.jpg',NULL,171822,'image/jpeg','17d3d7832094f2d9ec1a8460f850d021',1,1,'2013-05-29 08:55:02'),(18,'video0-20130529-0900.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0900.jpg',NULL,172933,'image/jpeg','d7904a09636903bc957a4fee1a677244',1,1,'2013-05-29 09:00:04'),(19,'video0-20130529-0905.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0905.jpg',NULL,170288,'image/jpeg','3dcd76d261dd6f7d5a1cc7f8b4b9b6ee',1,1,'2013-05-29 09:05:03'),(20,'video0-20130529-0910.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0910.jpg',NULL,172100,'image/jpeg','05c1cba6b4da2a75b32e2fa50831bb8f',1,1,'2013-05-29 09:10:02'),(21,'video0-20130529-0915.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0915.jpg',NULL,172797,'image/jpeg','d6a74239bd06b41646f35268b5780391',1,1,'2013-05-29 09:15:03'),(22,'video0-20130529-0920.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0920.jpg',NULL,170164,'image/jpeg','d0bee1daa5afd93d1ee1c05d5f508934',1,1,'2013-05-29 09:20:03'),(23,'video0-20130529-0925.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0925.jpg',NULL,177750,'image/jpeg','ba714a104e41d7abd8bafd95ad44797d',1,1,'2013-05-29 09:25:02'),(24,'video0-20130529-0930.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0930.jpg',NULL,172067,'image/jpeg','9b7871152b1f2df3bada2a16f536df2f',1,1,'2013-05-29 09:30:03'),(25,'video0-20130529-0935.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0935.jpg',NULL,176758,'image/jpeg','39682b31dca89bd13a156323f2931807',1,1,'2013-05-29 09:35:03'),(26,'video0-20130529-0940.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0940.jpg',NULL,177764,'image/jpeg','e386f9cd5f07f1d3a36e78fd57e40ca8',1,1,'2013-05-29 09:40:04'),(27,'video0-20130529-0945.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0945.jpg',NULL,176098,'image/jpeg','b5f95dc9fce7c3181497a01d26836700',1,1,'2013-05-29 09:45:02'),(28,'video0-20130529-0950.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0950.jpg',NULL,176017,'image/jpeg','fe1cf1a352151395c0c566a363b725af',1,1,'2013-05-29 09:50:03'),(29,'video0-20130529-0955.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-0955.jpg',NULL,182368,'image/jpeg','0f74e3a0f5aaa33519ba2ec770d42eb4',1,1,'2013-05-29 09:55:02'),(30,'video0-20130529-1000.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1000.jpg',NULL,176971,'image/jpeg','f863cb061bcd37d9681779ff4e7d169f',1,1,'2013-05-29 10:00:04'),(31,'video0-20130529-1005.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1005.jpg',NULL,175405,'image/jpeg','90b6d8c69e391a440410c816b1b82822',1,1,'2013-05-29 10:05:02'),(32,'video0-20130529-1010.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1010.jpg',NULL,175879,'image/jpeg','cc16b00252afadea2a60b71e91643c71',1,1,'2013-05-29 10:10:03'),(33,'video0-20130529-1015.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1015.jpg',NULL,177488,'image/jpeg','be1979723de37feaa3c80c01c37a0024',1,1,'2013-05-29 10:15:03'),(34,'video0-20130529-1020.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1020.jpg',NULL,176871,'image/jpeg','c99b095bf6ab47ad0e773e1775dcf1fb',1,1,'2013-05-29 10:20:02'),(35,'video0-20130529-1025.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1025.jpg',NULL,176814,'image/jpeg','2ae893d0f1992ee7bba3711a4bd83705',1,1,'2013-05-29 10:25:02'),(36,'video0-20130529-1030.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1030.jpg',NULL,180257,'image/jpeg','88d4befcc3f61ee076ec834cff41b54f',1,1,'2013-05-29 10:30:04'),(37,'video0-20130529-1035.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1035.jpg',NULL,181823,'image/jpeg','2d3d2df0814c92137db8429ebb54885c',1,1,'2013-05-29 10:35:02'),(38,'video0-20130529-1040.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1040.jpg',NULL,182004,'image/jpeg','b2530c2a53c3a0421e80ebf843e763ee',1,1,'2013-05-29 10:40:04'),(39,'video0-20130529-1045.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1045.jpg',NULL,187247,'image/jpeg','f20760106716afdfbd8388b443af34a0',1,1,'2013-05-29 10:45:02'),(40,'video0-20130529-1050.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1050.jpg',NULL,182136,'image/jpeg','41ca9557d45439d0ec710a705a872ee8',1,1,'2013-05-29 10:50:03'),(41,'video0-20130529-1055.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1055.jpg',NULL,178453,'image/jpeg','7347e6e698ed627027082fded72516e8',1,1,'2013-05-29 10:55:02'),(42,'video0-20130529-1100.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1100.jpg',NULL,181593,'image/jpeg','b156cc80bc6f1055f9c2650fd6f45522',1,1,'2013-05-29 11:00:04'),(43,'video0-20130529-1105.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1105.jpg',NULL,179109,'image/jpeg','fa8e5b8f4d285ea5d17f2d5478dcc708',1,1,'2013-05-29 11:05:04'),(44,'video0-20130529-1110.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1110.jpg',NULL,184020,'image/jpeg','4336da3ecc60b8a40a71be48bbaea1a2',1,1,'2013-05-29 11:10:06'),(45,'video0-20130529-1115.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1115.jpg',NULL,182693,'image/jpeg','fc9080302e70e0cb9edba409569b691c',1,1,'2013-05-29 11:15:04'),(46,'video0-20130529-1120.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1120.jpg',NULL,187683,'image/jpeg','a3daa8a0ad64a8075d3b196297ec3b54',1,1,'2013-05-29 11:20:04'),(47,'video0-20130529-1125.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1125.jpg',NULL,177276,'image/jpeg','6d484e7e97654f5f51626948ed354d17',1,1,'2013-05-29 11:25:03'),(48,'video0-20130529-1130.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1130.jpg',NULL,180596,'image/jpeg','02cf572bb473bb9ba9a923d6ec9f79e8',1,1,'2013-05-29 11:30:06'),(49,'video0-20130529-1135.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1135.jpg',NULL,183966,'image/jpeg','364f177155c956d2ee6d4c9ac822d48f',1,1,'2013-05-29 11:35:04'),(50,'video0-20130529-1140.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1140.jpg',NULL,181956,'image/jpeg','46e57d014274f645d09ad48968f6e056',1,1,'2013-05-29 11:40:05'),(51,'video0-20130529-1145.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1145.jpg',NULL,184235,'image/jpeg','391d7152fcf799847a205a61f1b36164',1,1,'2013-05-29 11:45:04'),(52,'video0-20130529-1150.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1150.jpg',NULL,182929,'image/jpeg','474b811ba2401e46f3c8e9344503560c',1,1,'2013-05-29 11:50:05'),(53,'video0-20130529-1155.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1155.jpg',NULL,180655,'image/jpeg','fc633988c24c64a9e18168a7c035d3ba',1,1,'2013-05-29 11:55:03'),(54,'video0-20130529-1200.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1200.jpg',NULL,178149,'image/jpeg','f2f6164a5e7003dfff93b4cbb87c81a9',1,1,'2013-05-29 12:00:05'),(55,'video0-20130529-1205.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1205.jpg',NULL,179020,'image/jpeg','916ca1ec54a31b65c9d40c03807690ec',1,1,'2013-05-29 12:05:04'),(56,'video0-20130529-1210.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1210.jpg',NULL,179909,'image/jpeg','4d4c0c9cffa15b02c38b92759e7e1d1b',1,1,'2013-05-29 12:10:05'),(57,'video0-20130529-1215.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1215.jpg',NULL,180445,'image/jpeg','9a7c0b5af78bbb2d8eca5e24a16656d8',1,1,'2013-05-29 12:15:03'),(58,'video0-20130529-1220.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1220.jpg',NULL,177952,'image/jpeg','f3df53b22d1fd99c83f17bb7cb62522a',1,1,'2013-05-29 12:20:03'),(59,'video0-20130529-1225.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1225.jpg',NULL,177109,'image/jpeg','4ed4068473cd95b4be8d02cc5d34341c',1,1,'2013-05-29 12:25:02'),(60,'video0-20130529-1230.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1230.jpg',NULL,178640,'image/jpeg','e856dbc2a3b8543a0605e9b087fe27fe',1,1,'2013-05-29 12:30:03'),(61,'video0-20130529-1235.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1235.jpg',NULL,178668,'image/jpeg','ee301025a942889e79eb2b51d5877775',1,1,'2013-05-29 12:35:02'),(62,'video0-20130529-1240.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1240.jpg',NULL,177054,'image/jpeg','03b5adc20a82ee755e4ff042716fcaaa',1,1,'2013-05-29 12:40:03'),(63,'video0-20130529-1245.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1245.jpg',NULL,176955,'image/jpeg','7a833919745e83d3aa81a91a4a39a28f',1,1,'2013-05-29 12:45:02'),(64,'video0-20130529-1250.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1250.jpg',NULL,179082,'image/jpeg','814050329a33794861f7d2855a4cf068',1,1,'2013-05-29 12:50:02'),(65,'video0-20130529-1255.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1255.jpg',NULL,182174,'image/jpeg','c349c608c16a72584baea7526bb1d493',1,1,'2013-05-29 12:55:02'),(66,'video0-20130529-1300.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1300.jpg',NULL,182958,'image/jpeg','82874fdc36f3a63522ba759659dd3a52',1,1,'2013-05-29 13:00:04'),(67,'video0-20130529-1305.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1305.jpg',NULL,184128,'image/jpeg','10a783e46bd6adafb776edd545c66b65',1,1,'2013-05-29 13:05:02'),(68,'video0-20130529-1310.jpg','/home/webcam/public_html/webcam/2013/05/29/video0-20130529-1310.jpg',NULL,180696,'image/jpeg','6780d868bf28d95070697174a680a858',1,1,'2013-05-29 13:10:02');
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

-- Dump completed on 2013-05-29 14:14:07
