-- MySQL dump 10.17  Distrib 10.3.12-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: football-community
-- ------------------------------------------------------
-- Server version	10.3.12-MariaDB-1:10.3.12+maria~bionic

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `messages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '(DC2Type:json_array)',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
INSERT INTO `chat` VALUES (1,1,2,'[{\"user\":1,\"message\":\"Cze\\u015b\\u0107\",\"date\":\"2019-05-18 19:32:22\"},{\"user\":2,\"message\":\"Cze\\u015b\\u0107\",\"date\":\"2019-05-18 19:33:53\"},{\"user\":1,\"message\":\"Jak si\\u0119 masz?\",\"date\":\"2019-05-18 19:34:06\"},{\"user\":2,\"message\":\"Dobrze, a Ty?\",\"date\":\"2019-05-18 19:34:15\"},{\"user\":1,\"message\":\"Ja te\\u017c \",\"date\":\"2019-05-18 19:34:29\"}]',NULL);
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_user`
--

DROP TABLE IF EXISTS `chat_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_user` (
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`chat_id`,`user_id`),
  KEY `IDX_2B0F4B081A9A7125` (`chat_id`),
  KEY `IDX_2B0F4B08A76ED395` (`user_id`),
  CONSTRAINT `FK_2B0F4B081A9A7125` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2B0F4B08A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_user`
--

LOCK TABLES `chat_user` WRITE;
/*!40000 ALTER TABLE `chat_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_comment_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `reactions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '(DC2Type:json_array)',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526C4B89032C` (`post_id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  KEY `IDX_9474526CBF2AF943` (`parent_comment_id`),
  CONSTRAINT `FK_9474526C4B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_9474526CBF2AF943` FOREIGN KEY (`parent_comment_id`) REFERENCES `comment` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (1,2,2,NULL,'Super! Oby tak dalej!','[]','2019-05-18 19:37:36','2019-05-18 19:37:36'),(2,2,1,1,'Tak, ManUtd <3','[]','2019-05-18 19:38:17','2019-05-18 19:38:17'),(3,4,1,NULL,'Tak jest!','[]','2019-05-18 19:38:56','2019-05-18 19:38:56');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_user`
--

DROP TABLE IF EXISTS `group_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_user` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`),
  KEY `IDX_A4C98D39FE54D947` (`group_id`),
  KEY `IDX_A4C98D39A76ED395` (`user_id`),
  CONSTRAINT `FK_A4C98D39A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A4C98D39FE54D947` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_user`
--

LOCK TABLES `group_user` WRITE;
/*!40000 ALTER TABLE `group_user` DISABLE KEYS */;
INSERT INTO `group_user` VALUES (1,2),(2,1);
/*!40000 ALTER TABLE `group_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F06D3970A76ED395` (`user_id`),
  CONSTRAINT `FK_F06D3970A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,1,'Manchester United','Grupa klubu Manchester United','2019-05-18 19:24:57','2019-05-18 19:24:57'),(2,2,'Chelsea London','Grupa fanów Chelsea London','2019-05-18 19:35:30','2019-05-18 19:35:30');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_creation` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045F4B89032C` (`post_id`),
  CONSTRAINT `FK_C53D045F4B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('20190518171428','2019-05-18 17:14:33');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `reactions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '(DC2Type:json_array)',
  PRIMARY KEY (`id`),
  KEY `IDX_5A8A6C8DA76ED395` (`user_id`),
  KEY `IDX_5A8A6C8DFE54D947` (`group_id`),
  CONSTRAINT `FK_5A8A6C8DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_5A8A6C8DFE54D947` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,1,1,'Seria ośmiu zwycięstw to za mało. Manchester United musi wygrywać trofea','David de Gea przyznał, że imponująca seria ośmiu zwycięstw Manchesteru United nie będzie miała znaczenia, jeśli drużyna nie zdoła się zakwalifikować do rozgrywek Ligi Mistrzów. Hiszpan zdaje sobie sprawę, że \"Czerwone Diabły\" muszą zdobywać trofea.','2019-05-18 19:28:24','2019-05-18 19:28:24','[{\"user\":2,\"reaction\":\"like\"}]'),(2,1,1,'MANCHESTER UNITED WYGRYWA LIGĘ EUROPY!','Tym razem młodość i fantazja nie wystarczyły, żeby doprowadzić sprawę do końca – mimo problemów z kontuzjami Manchester United bez większych problemów rozprawił się z Ajaxem Amsterdam i wygrał Ligę Europy. Zwycięstwo to oznacza, że „Czerwone Diabły” zapewniły sobie upragniony awans do fazy grupowej Ligi Mistrzów!','2019-05-18 19:31:41','2019-05-18 19:31:41','[{\"user\":2,\"reaction\":\"like\"},{\"user\":1,\"reaction\":\"like\"}]'),(3,2,2,'CHELSEA LONDYN NIE CHCE GONZALO HIGUAINA','Higuain trafił na Stamford Bridge w styczniu tego roku na półroczne wypożyczenie. \"The Blues\" mają możliwość przedłużenia umowy o kolejny rok za blisko 16 milionów funtów lub wykupienia zawodnika za prawie 32 miliony. Chelsea z tej opcji nie skorzysta. Na Stamford Bridge są rozczarowani grą Higuaina. W 21 meczach argentyński napastnik strzelił tylko pięć goli.','2019-05-18 19:36:17','2019-05-18 19:36:17','[{\"user\":2,\"reaction\":\"like\"}]'),(4,2,2,'Chelsea pokonuje po rzutach karnych Eintracht Frankfurt i melduje się w finale Ligii Europejskiej!','Chelsea Londyn - Eintracht Frankfurt 1:1 po dogr. (1:0, 1:1), karne 4:3\r\n\r\nChelsea Londyn - Eintracht Frankfurt 1:1 po dogr. (1:0, 1:1), karne 4:3\r\n\r\nRzuty karne: 0:1 Sebastien Haller, 1:1 Ross Barkley, 1:2 Luka Jović, 1:2 Cesar Azpilicueta (Kevin Trapp obronił), 1:3 Jonathan de Guzman, 2:3 Jorginho, 2:3 Martin Hinteregger (Kepa Arrizabalaga obronił), 3:3 David Luiz, 3:3 Goncalo Paciencia (Kepa Arrizabalaga obronił), 4:3 Eden Hazard.','2019-05-18 19:37:11','2019-05-18 19:37:11','[{\"user\":2,\"reaction\":\"like\"},{\"user\":1,\"reaction\":\"like\"}]');
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'test1','test1@test1.com','test1','test1','no added','$2y$13$7B/CFFLnBAAEvMEb.Ch7Wu7j4DrM16MdyDawpmnC12ZAfluSUA9xe','2019-05-18 19:40:54'),(2,'test2','test2@test2.com','test2','test2','no added','$2y$13$oaxChCDAIQWC/cBWr9bPm.f3yzqFPfIqEoEU1/BphS9HrFA8M.3rG','2019-05-18 19:39:53');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-18 20:31:36
