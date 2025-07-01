CREATE DATABASE  IF NOT EXISTS `exam_management` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `exam_management`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: exam_management
-- ------------------------------------------------------
-- Server version	8.0.42

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
-- Table structure for table `question_responses`
--

DROP TABLE IF EXISTS `question_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `question_responses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `test_id` int NOT NULL,
  `question_id` int NOT NULL,
  `selected_option` varchar(1) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `question_responses_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `question_responses_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `question_responses_chk_1` CHECK ((`selected_option` in (_utf8mb4'A',_utf8mb4'B',_utf8mb4'C',_utf8mb4'D')))
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_responses`
--

LOCK TABLES `question_responses` WRITE;
/*!40000 ALTER TABLE `question_responses` DISABLE KEYS */;
INSERT INTO `question_responses` VALUES (135,16,83,66,'C','2025-07-01 23:06:17'),(136,16,83,67,'D','2025-07-01 23:06:17'),(137,16,83,68,'B','2025-07-01 23:06:17'),(155,16,86,70,'C','2025-07-02 00:23:35'),(156,16,86,71,'B','2025-07-02 00:23:35'),(157,16,86,72,'C','2025-07-02 00:23:35'),(158,16,86,73,'A','2025-07-02 00:23:35'),(159,16,86,74,'B','2025-07-02 00:23:35'),(160,16,86,75,'C','2025-07-02 00:23:35'),(161,16,86,76,'D','2025-07-02 00:23:35'),(162,16,86,77,'D','2025-07-02 00:23:35');
/*!40000 ALTER TABLE `question_responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `test_id` int NOT NULL,
  `content` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `correct` varchar(1) NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `questions_chk_1` CHECK ((`correct` in (_utf8mb4'A',_utf8mb4'B',_utf8mb4'C',_utf8mb4'D')))
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (66,83,'adawdhkadhjh','ahdjwhda','haldwjdahdu','ăhdljdhaj','ăljdadaww','C',3.33),(67,83,'ădkajhdj','alwjkdjakdj','uahwudhaidh','uahuwhdu','uhdsuhad','D',3.33),(68,83,'ădhaikdhkj','ahkwdhkajhd','kjahkjdhwjh','khkajhdj','khakjhw','C',3.33),(70,86,'ădad','adwwad','adwad','adwad','adwa','C',1.25),(71,86,'ădadad','adwdad','adwad','ăd','ad','B',1.25),(72,86,'dfg','áda','ằ','adw','ădad','C',1.25),(73,86,'sfsg','sgeghf','dfd','sdc','sdfsf','A',1.25),(74,86,'adwasa','fasfa','câc','sfawf','à','B',1.25),(75,86,'adwdwad','adsa','đâ','sdawd','ădf','C',1.25),(76,86,'adwad','ưdad','uawd','ădada','ưdad','D',1.25),(77,86,'adwa','adad','adwa','dưa','đâ','D',1.25);
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_responses`
--

DROP TABLE IF EXISTS `test_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test_responses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `test_id` int DEFAULT NULL,
  `test_taker_id` int DEFAULT NULL,
  `score` float DEFAULT NULL,
  `submitted_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','completed') NOT NULL DEFAULT 'completed',
  PRIMARY KEY (`id`),
  KEY `test_taker_id` (`test_taker_id`),
  KEY `test_responses_ibfk_1` (`test_id`),
  CONSTRAINT `test_responses_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `test_responses_ibfk_2` FOREIGN KEY (`test_taker_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_responses`
--

LOCK TABLES `test_responses` WRITE;
/*!40000 ALTER TABLE `test_responses` DISABLE KEYS */;
INSERT INTO `test_responses` VALUES (20,83,16,6.67,'2025-07-01 23:06:17','completed'),(21,86,16,10,'2025-07-02 00:23:35','completed');
/*!40000 ALTER TABLE `test_responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tests`
--

DROP TABLE IF EXISTS `tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `test_creator_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `duration` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `share_code` varchar(10) DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT '0',
  `open_time` datetime DEFAULT NULL,
  `close_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `share_code` (`share_code`),
  KEY `test_creator_id` (`test_creator_id`),
  CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`test_creator_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tests`
--

LOCK TABLES `tests` WRITE;
/*!40000 ALTER TABLE `tests` DISABLE KEYS */;
INSERT INTO `tests` VALUES (82,8,'adwad','ădada',15,'2025-07-01 20:41:10','R9Q41S',0,'2025-07-01 20:41:00','2025-08-01 20:45:00'),(83,8,'ưdadawjkd','ădjladjnawdâd',15,'2025-07-01 20:46:03','3OCTV7',0,'2025-07-01 20:51:00','2025-07-10 20:45:00'),(85,8,'á','zsa',32,'2025-07-02 00:08:26','6ZIZHI',0,'2025-07-02 00:08:00','2025-07-02 00:10:00'),(86,8,'ăda','ădad',10,'2025-07-02 00:12:11','B8T4T5',0,'2025-07-02 00:15:00','2025-07-02 00:34:00');
/*!40000 ALTER TABLE `tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','creator','taker') DEFAULT 'taker',
  `fullname` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','locked') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (8,'quantran1109','$2y$10$zixw5a2sYwAVDgw9KyR0KO/8tBLKpO0GUN2OKwgOU5PznDVBNNXRi','quantranhoang24@gmail.com','creator','Trần Hoàng Quâncs','2025-06-25 01:56:52','active'),(9,'quan11092005','$2y$10$dME2Vjdalo/Fv.GKCfYZXuStC.lRTodBb64jarKOl8jDMgZcNrxau','quantranhoang24@gmail.com','creator',NULL,'2025-06-25 02:40:26','active'),(13,'adafdwf','$2y$10$fbZqNFxBFmTli7HXaCN2J.XPg4.51W.EH0gPVi5EPuQuwkRABdJRW','quantranhoang243@gmail.com','creator',NULL,'2025-06-25 02:47:27','active'),(14,'fqafa','$2y$10$KFyn1GSyQDdVVd8jQNFhP.J/JR3i62EzSucWrkXGHTV5M/f3ISPDC','ad@hadw.com','creator',NULL,'2025-06-25 03:20:47','active'),(16,'quan1192005','$2y$10$M5s0h5vrrs0y7Sq1q7q8quZgTidivbOqUMaEtxb63PNJkxTJwq3qm','quantranhoang24@gmail.com','taker','Trần Hoàng Quân','2025-06-25 03:45:05','active'),(17,'quan12','$2y$10$.V20/o1D6Kv7MxtDTmsWvuclpa6.0Il/FbFAoE2widHYYf6Iltgr.','quantran@gmail.com','creator',NULL,'2025-06-29 09:49:54','active'),(19,'admin','$2y$10$uiZ4Wh8uCF0aSfZ79s3hqOaKbJYi167wRa/U2At2nBGQTnxNz.Fhu','admin@gmail.com','admin','admin','2025-07-01 19:37:10','active');
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

-- Dump completed on 2025-07-02  1:18:42
