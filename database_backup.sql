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
-- Table structure for table `commentComments`
--

DROP TABLE IF EXISTS `commentComments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commentComments` (
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentComments`
--

LOCK TABLES `commentComments` WRITE;
/*!40000 ALTER TABLE `commentComments` DISABLE KEYS */;
INSERT INTO `commentComments` VALUES (1,1,'2020-04-15 10:17:08','resposta a comentario',0,3),(2,1,'2020-04-15 15:27:25','daww',12,9),(3,3,'2020-04-15 15:27:25','dawwdd',12,11),(4,4,'2020-04-15 15:27:25','dawawadw',12,10),(5,1,'2020-04-15 15:29:34','daww',12,9),(6,1,'2020-04-15 15:29:36','daww',12,9);
/*!40000 ALTER TABLE `commentComments` ENABLE KEYS */;
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
  `likes` int DEFAULT '0',
  `post` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fg_postComments_author` (`author`),
  KEY `fg_postComments_post` (`post`),
  CONSTRAINT `fg_postComments_author` FOREIGN KEY (`author`) REFERENCES `persons` (`id`),
  CONSTRAINT `fg_postComments_post` FOREIGN KEY (`post`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postComments`
--

LOCK TABLES `postComments` WRITE;
/*!40000 ALTER TABLE `postComments` DISABLE KEYS */;
INSERT INTO `postComments` VALUES (1,3,'2020-04-14 18:37:03','Cdwaa',5,1),(2,1,'2020-04-14 18:37:03','dawdwa',4,2),(3,5,'2020-04-14 18:37:03','dawdwad',3,3),(4,1,'2020-04-14 18:37:03','Cdwaa',11,1),(5,5,'2020-04-14 18:37:03','Cdwaa',10,1),(6,3,'2020-04-15 14:16:20','Cdwaa2',5,1),(7,1,'2020-04-15 14:16:20','dawdwa2',4,2),(8,5,'2020-04-15 14:16:20','dawdwad2',3,3),(9,1,'2020-04-15 14:16:20','Cdwaaw2',11,1),(10,5,'2020-04-15 14:16:20','Cdwaa2',10,1),(11,3,'2020-04-15 14:59:14','Cdwaa2',5,1),(12,1,'2020-04-15 14:59:14','dawdwa2',4,2),(13,5,'2020-04-15 14:59:14','dawdwad2',3,3);
/*!40000 ALTER TABLE `postComments` ENABLE KEYS */;
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
  `likes` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_postsAuthor` (`author`),
  CONSTRAINT `fk_postsAuthor` FOREIGN KEY (`author`) REFERENCES `persons` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,'2020-04-13 11:52:12','Bom dia, sou o João',0),(2,3,'2020-04-13 11:52:49','Bom dia, sou a Maria',0),(3,1,'2020-04-13 20:39:44','Buenas tardes',0);
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

-- Dump completed on 2020-04-16  8:28:45
