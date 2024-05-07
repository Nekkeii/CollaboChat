-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: chat-projet
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateur` (
  `idutilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`idutilisateur`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_role` (`role_id`),
  CONSTRAINT `fk_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES (1,'$2y$10$jJRkoPMjq0H73tZdMg8cruADOjjeNM6VyxkfXWAsx4MJ812/ZnQl.','jadsiala15@gmail.com','2024-05-05 15:46:20','jad','siala','uploads/luffy serieux.jpg',NULL),(2,'$2y$10$sSsDrF.Ipa4UVgdkYVmt/eLil0j1IA6MxafxjL.18qujo22nobMDW','messi@gmail.com','2024-05-05 18:10:36','shy','messi','uploads/messi.jpg',NULL),(3,'$2y$10$B8fgcU/FFiDrACMm1OYwV.IfTbg.QtJr7fdRB1gfTrDe/v5mc62Li','s@gmail.com','2024-05-05 19:02:46','souhail','siala','uploads/luffy sourir gear5.jpg',NULL),(4,'$2y$10$1goC/mLMTNn3SR/qIGYic.jIY50h3jcJ/ji4GddSLxcD81pxuEUti','onepiece@gmail.com','2024-05-05 19:14:57','luffy','pirate','uploads/luffy gear 5.jpg',NULL),(5,'$2y$10$yX.7ZZG5Z39Vr.YXj7hXhe7VQnWXHiLHJEaaUUGJLq8UkewHY6vgO','mario@gmail.com','2024-05-05 23:49:22','mario','ballotelli','uploads/ballotelli.jpg',NULL),(6,'$2y$10$em4TRrne3EHDkupphrMETuX721yatiSFeO1LYJLVqnX5kWonb7MaK','sla@gmail.com','2024-05-05 23:54:18','tanjiro','kamado','uploads/tanjiro.jpg',1),(7,'$2y$10$Ooi/qFlUoCWnLgKORKcKweuY8Hf.Owm3U.2IJJAM1L/gBJWVshwAy','jadoujadou@gmail.com','2024-05-06 00:03:31','jadou ','prime ','uploads/lufy egg head .png',3),(8,'$2y$10$zI9A1/6qRxgX0Lx4VULfkeTMyyT20dp75pKIJtIGpNWTAQxMwsaoq','sam@gmail.com','2024-05-06 00:08:34','samurai','passe','uploads/musashi .jpg',2),(10,'$2y$10$aGx0uGmpNGGMDBTqra6SmODSz7OG/69nga9qvyLyqhQXMB5E07csW','test@gmail.com','2024-05-06 01:01:55','test','test','uploads/hasbullahh.jpg',3);
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-07 10:07:27
