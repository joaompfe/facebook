-- MySQL dump 10.13  Distrib 8.0.19, for Linux (x86_64)
--
-- Host: localhost    Database: facebook
-- ------------------------------------------------------
-- Server version	8.0.19-0ubuntu0.19.10.3

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
-- Current Database: `facebook`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `facebook` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `facebook`;

--
-- Table structure for table `commentLikes`
--

DROP TABLE IF EXISTS `commentLikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commentLikes` (
  `comment` int NOT NULL,
  `person` int NOT NULL,
  PRIMARY KEY (`comment`,`person`),
  KEY `fk_commentLikes_person` (`person`),
  CONSTRAINT `fk_commentLikes_comment` FOREIGN KEY (`comment`) REFERENCES `postComments` (`id`),
  CONSTRAINT `fk_commentLikes_person` FOREIGN KEY (`person`) REFERENCES `persons` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentLikes`
--

LOCK TABLES `commentLikes` WRITE;
/*!40000 ALTER TABLE `commentLikes` DISABLE KEYS */;
/*!40000 ALTER TABLE `commentLikes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commentReplies`
--

DROP TABLE IF EXISTS `commentReplies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commentReplies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author` int DEFAULT NULL,
  `creationTime` datetime DEFAULT CURRENT_TIMESTAMP,
  `text` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `likes` int DEFAULT '0',
  `comment` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fg_commentComments_author` (`author`),
  KEY `fg_commentComments_comment` (`comment`),
  CONSTRAINT `fg_commentComments_author` FOREIGN KEY (`author`) REFERENCES `persons` (`id`),
  CONSTRAINT `fg_commentComments_comment` FOREIGN KEY (`comment`) REFERENCES `postComments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentReplies`
--

LOCK TABLES `commentReplies` WRITE;
/*!40000 ALTER TABLE `commentReplies` DISABLE KEYS */;
INSERT INTO `commentReplies` VALUES (1,1,'2020-04-15 10:17:08','resposta a comentario',0,3),(2,1,'2020-04-15 15:27:25','daww',12,9),(3,3,'2020-04-15 15:27:25','dawwdd',12,11),(4,4,'2020-04-15 15:27:25','dawawadw',12,10),(5,1,'2020-04-15 15:29:34','daww',12,9),(6,1,'2020-04-15 15:29:36','daww',12,9),(7,1,'2020-04-16 12:09:28','',0,3),(8,1,'2020-04-16 12:09:33','',0,3),(9,1,'2020-04-16 12:25:47','oij',0,3),(10,1,'2020-04-16 12:26:13','kop',0,3),(11,1,'2020-04-16 12:37:03','dawd',0,3),(12,1,'2020-04-16 13:38:19','ola',0,2),(13,1,'2020-04-16 13:48:33','eq',0,3),(14,1,'2020-04-16 13:48:49','k',0,4),(15,1,'2020-04-16 13:48:53','gyg',0,2),(16,1,'2020-04-16 13:49:03','fgf',0,4),(17,1,'2020-04-16 13:52:41','dwa',0,3),(18,1,'2020-04-16 13:52:47','daw',0,23),(19,1,'2020-04-16 13:55:35','dwa',0,23),(20,1,'2020-04-16 13:56:04','oi',0,24),(21,1,'2020-04-16 13:57:16','oj',0,25),(22,1,'2020-04-16 13:57:19','op',0,25),(23,1,'2020-04-16 13:58:50','dwa',0,26),(24,1,'2020-04-16 15:19:35','fdp',0,25),(25,1,'2020-04-16 15:21:01','l',0,25),(26,1,'2020-04-16 15:23:39','dwad',0,25),(27,1,'2020-04-16 15:25:13','hooij',0,12),(28,1,'2020-04-16 15:25:15','ohi',0,7),(29,1,'2020-04-16 15:25:29','mp',0,2),(30,1,'2020-04-16 15:25:50','ll',0,11),(31,1,'2020-04-16 23:47:18','foda-se',0,23),(32,1,'2020-04-16 23:48:02','l',0,3),(33,1,'2020-04-16 23:50:14','ll',0,26),(34,1,'2020-04-16 23:51:24','fse',0,3),(35,1,'2020-04-16 23:51:44','g',0,2),(36,1,'2020-04-16 23:51:55','ç',0,2),(37,1,'2020-04-16 23:52:14','ç',0,3),(38,1,'2020-04-16 23:52:21','ç',0,3),(39,1,'2020-04-16 23:52:58','ç',0,3),(40,1,'2020-04-16 23:53:06','lol',0,12),(41,1,'2020-04-16 23:53:19','lol',0,4),(42,1,'2020-04-22 09:48:12','wadaw',0,22),(43,1,'2020-04-22 10:05:16','çç',0,25),(44,1,'2020-04-22 10:07:12','l',0,3),(45,1,'2020-04-22 10:10:17','z',0,3),(46,1,'2020-04-22 10:19:07','xz',0,3),(47,1,'2020-04-25 16:16:13','ç',0,29),(48,1,'2020-04-25 17:53:39','d',0,30),(49,1,'2020-04-25 19:25:04','çp',0,30),(50,1,'2020-04-25 20:37:04','ppp',0,30),(51,1,'2020-04-25 20:49:19','DD',0,27),(52,1,'2020-04-26 10:22:22','ss',0,30),(53,1,'2020-04-26 11:59:31','popo',0,38),(54,1,'2020-04-26 12:00:52','popo',0,30),(55,1,'2020-04-26 12:17:13','popopo',0,38),(56,1,'2020-04-26 12:19:32','kl',0,27),(57,1,'2020-04-26 12:19:36','h',0,31),(58,1,'2020-04-26 12:19:42','ojp',0,39),(59,1,'2020-04-26 12:25:11','ip7',0,40),(60,1,'2020-04-26 12:25:15','lp',0,40),(61,1,'2020-04-26 12:25:30','p',0,41),(62,1,'2020-04-26 12:38:47','popo',0,40),(63,1,'2020-04-26 12:38:56','ib',0,42),(64,1,'2020-04-26 12:38:59','oj',0,42),(65,1,'2020-04-26 12:39:17','poppopopopop',0,3),(66,1,'2020-04-26 12:46:31','ctorsxi',0,42),(67,1,'2020-04-26 12:46:35','ypfih',0,42),(68,1,'2020-04-26 12:46:55','lugyc',0,43),(69,1,'2020-04-26 12:46:57','iuyg',0,43),(70,1,'2020-04-26 12:47:04','itfr',0,3),(71,1,'2020-04-26 12:47:07','oiyug',0,2),(72,1,'2020-04-26 12:59:01','ouhi',0,2),(73,1,'2020-04-26 13:01:12','oijuhgtyf',0,43),(74,1,'2020-04-26 13:01:23','oi',0,44),(75,1,'2020-04-26 13:01:26','j',0,3),(76,1,'2020-04-26 13:01:39','jiu',0,45),(77,1,'2020-04-26 13:01:39','',0,45),(78,1,'2020-04-26 13:07:05','mk',0,47);
/*!40000 ALTER TABLE `commentReplies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persons`
--

DROP TABLE IF EXISTS `persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `fullName` varchar(60) COLLATE utf8_unicode_ci GENERATED ALWAYS AS (concat(`firstName`,_utf8mb3' ',`lastName`)) VIRTUAL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `gender` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  CONSTRAINT `ck_persons_gender` CHECK (((`gender` = _utf8mb4'M') or (`gender` = _utf8mb4'F')))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persons`
--

LOCK TABLES `persons` WRITE;
/*!40000 ALTER TABLE `persons` DISABLE KEYS */;
INSERT INTO `persons` (`id`, `firstName`, `lastName`, `email`, `password`, `birthday`, `gender`) VALUES (1,'João','Fé','jf@sapo.pt','123','2000-10-25','M'),(3,'Maria','Manuela','mm@gmail.com','123','1905-01-01','F'),(4,'afonso','gay','af@gmail.com','123','1929-06-01','M'),(5,'Miguel Angel','Félix Gallardo','mfg@gmail.com','123','1905-01-01','M');
/*!40000 ALTER TABLE `persons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postComments`
--

DROP TABLE IF EXISTS `postComments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `postComments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author` int DEFAULT NULL,
  `creationTime` datetime DEFAULT CURRENT_TIMESTAMP,
  `text` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fg_postComments_author` (`author`),
  KEY `fg_postComments_post` (`post`),
  CONSTRAINT `fg_postComments_author` FOREIGN KEY (`author`) REFERENCES `persons` (`id`),
  CONSTRAINT `fg_postComments_post` FOREIGN KEY (`post`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postComments`
--

LOCK TABLES `postComments` WRITE;
/*!40000 ALTER TABLE `postComments` DISABLE KEYS */;
INSERT INTO `postComments` VALUES (1,3,'2020-04-14 18:37:03','Cdwaa',1),(2,1,'2020-04-14 18:37:03','dawdwa',2),(3,5,'2020-04-14 18:37:03','dawdwad',3),(4,1,'2020-04-14 18:37:03','Cdwaa',1),(5,5,'2020-04-14 18:37:03','Cdwaa',1),(6,3,'2020-04-15 14:16:20','Cdwaa2',1),(7,1,'2020-04-15 14:16:20','dawdwa2',2),(8,5,'2020-04-15 14:16:20','dawdwad2',3),(9,1,'2020-04-15 14:16:20','Cdwaaw2',1),(10,5,'2020-04-15 14:16:20','Cdwaa2',1),(11,3,'2020-04-15 14:59:14','Cdwaa2',1),(12,1,'2020-04-15 14:59:14','dawdwa2',2),(13,5,'2020-04-15 14:59:14','dawdwad2',3),(14,1,'2020-04-16 11:50:07','kop',3),(15,1,'2020-04-16 11:51:07','jo',3),(16,1,'2020-04-16 11:54:49','lk',3),(17,1,'2020-04-16 11:56:35','po',3),(18,1,'2020-04-16 11:56:39','po',3),(19,1,'2020-04-16 11:58:33','dwa',3),(20,1,'2020-04-16 11:58:38','dwaaa',3),(21,1,'2020-04-16 11:59:43','',3),(22,1,'2020-04-16 12:09:37','',3),(23,1,'2020-04-16 13:52:43','dwa',3),(24,1,'2020-04-16 13:55:59','ojo',3),(25,1,'2020-04-16 13:57:13','io',3),(26,1,'2020-04-16 13:58:46','dwa',3),(27,1,'2020-04-16 15:25:18','opk+',2),(28,1,'2020-04-16 15:25:35','l',1),(29,1,'2020-04-22 10:23:04','çççççç',3),(30,1,'2020-04-25 17:53:34','dwad',4),(31,1,'2020-04-25 20:37:24','ppp787',2),(32,1,'2020-04-26 10:22:14','as',4),(33,1,'2020-04-26 11:13:24','ççççç',3),(34,1,'2020-04-26 11:13:27','',3),(35,1,'2020-04-26 11:14:30','d',4),(36,1,'2020-04-26 11:15:12','ç',4),(37,1,'2020-04-26 11:20:00','l',4),(38,1,'2020-04-26 11:57:24','ççççççççççççççççççççç',4),(39,1,'2020-04-26 12:19:39','çplpl',2),(40,1,'2020-04-26 12:25:07','ppk',4),(41,1,'2020-04-26 12:25:26','piou',1),(42,1,'2020-04-26 12:38:53','oji',4),(43,1,'2020-04-26 12:46:52','poihugyftx',4),(44,1,'2020-04-26 13:01:20','lk',3),(45,1,'2020-04-26 13:01:36','uygtf',2),(46,1,'2020-04-26 13:02:40','dawdaw',4),(47,1,'2020-04-26 13:04:58','kkko',4),(48,1,'2020-04-26 13:16:32','pokihugcf',4);
/*!40000 ALTER TABLE `postComments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postLikes`
--

DROP TABLE IF EXISTS `postLikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `postLikes` (
  `post` int NOT NULL,
  `person` int NOT NULL,
  PRIMARY KEY (`post`,`person`),
  KEY `fk_postLikes_person` (`person`),
  CONSTRAINT `fk_postLikes_person` FOREIGN KEY (`person`) REFERENCES `persons` (`id`),
  CONSTRAINT `fk_postLikes_post` FOREIGN KEY (`post`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postLikes`
--

LOCK TABLES `postLikes` WRITE;
/*!40000 ALTER TABLE `postLikes` DISABLE KEYS */;
INSERT INTO `postLikes` VALUES (1,1),(2,1),(3,1),(1,3),(4,4),(1,5);
/*!40000 ALTER TABLE `postLikes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author` int DEFAULT NULL,
  `creationTime` datetime DEFAULT CURRENT_TIMESTAMP,
  `text` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_postsAuthor` (`author`),
  CONSTRAINT `fk_postsAuthor` FOREIGN KEY (`author`) REFERENCES `persons` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,'2020-04-13 11:52:12','Bom dia, sou o João'),(2,3,'2020-04-13 11:52:49','Bom dia, sou a Maria'),(3,1,'2020-04-13 20:39:44','Buenas tardes'),(4,1,'2020-04-16 09:22:48','testest');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-26 14:02:48
