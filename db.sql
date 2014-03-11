CREATE DATABASE  IF NOT EXISTS `mes` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `mes`;
-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (i686)
--
-- Host: 127.0.0.1    Database: mes
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.10.2-log

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
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `realname` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `mail_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_mail_id` (`mail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attachments`
--

LOCK TABLES `attachments` WRITE;
/*!40000 ALTER TABLE `attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `sending_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient_id` (`recipient_id`),
  KEY `sending_id` (`sending_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `links`
--

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;
INSERT INTO `links` VALUES (1,'http://127.0.0.1/mes/unsuscribe?recipient=key=$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC&sending=48redirect=',180,'2014-02-21 14:56:31',48),(2,'http://127.0.0.1/mes/unsuscribe?recipient=key=$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC&sending=49redirect=',181,'2014-02-21 15:02:36',49),(3,'http://127.0.0.1/mes/unsubscribe?recipient=182&key=$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC&sending=50redirect=',182,'2014-02-21 15:04:01',50),(4,'http://127.0.0.1/mes/unsubscribe?recipient=183&key=$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC&sending=51redirect=',183,'2014-02-21 16:12:54',51),(5,'http://127.0.0.1/mes/unsubscribe?recipient=184&key=$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC&sending=52redirect=',184,'2014-02-21 16:51:09',52);
/*!40000 ALTER TABLE `links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailinglists`
--

DROP TABLE IF EXISTS `mailinglists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailinglists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` text,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mailinglists_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailinglists`
--

LOCK TABLES `mailinglists` WRITE;
/*!40000 ALTER TABLE `mailinglists` DISABLE KEYS */;
INSERT INTO `mailinglists` VALUES (4,1,'lista22','','2013-03-25 15:03:34'),(5,1,'nuova','nuova','2013-03-26 16:16:22'),(6,0,'qqqq','qqqqq','2013-07-26 08:18:31'),(7,0,'aaaaa','aaaaaa','2013-07-26 08:19:10'),(8,0,'aaaaaaaa','aaaaaaaa','2013-07-26 08:19:38'),(9,1,'deded','ededed','2013-07-26 08:19:57'),(19,1,'fff','ffff','2013-07-26 08:32:53'),(20,1,'lista per test invio','','2013-08-13 10:30:48'),(21,1,'lista test disiscrizione','','2014-02-21 13:44:40');
/*!40000 ALTER TABLE `mailinglists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailinglists_members`
--

DROP TABLE IF EXISTS `mailinglists_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailinglists_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `mailinglist_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`member_id`,`mailinglist_id`),
  KEY `mailinglists_members_mailinglist_id` (`mailinglist_id`),
  KEY `mailinglists_members_member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8
/*!50100 PARTITION BY HASH (mailinglist_id)
PARTITIONS 1024 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailinglists_members`
--

LOCK TABLES `mailinglists_members` WRITE;
/*!40000 ALTER TABLE `mailinglists_members` DISABLE KEYS */;
INSERT INTO `mailinglists_members` VALUES (7,5,4),(8,6,4),(9,7,4),(10,8,4),(11,9,4),(12,10,4),(13,11,4),(14,12,4),(15,13,4),(20,15,4),(23,18,4),(28,23,4),(39,37,4),(46,42,4),(50,46,4),(53,4,4),(16,14,5),(18,16,5),(19,17,5),(21,15,5),(22,18,5),(24,19,5),(25,20,5),(26,21,5),(27,23,5),(29,26,5),(30,27,5),(31,28,5),(32,29,5),(33,30,5),(34,31,5),(35,32,5),(36,33,5),(37,34,5),(38,35,5),(41,38,5),(52,11,5),(42,38,9),(47,43,9),(55,49,9),(56,50,9),(43,39,19),(44,40,19),(45,41,19),(51,4,20),(54,47,20);
/*!40000 ALTER TABLE `mailinglists_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailinglists_sendings`
--

DROP TABLE IF EXISTS `mailinglists_sendings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailinglists_sendings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sending_id` int(11) NOT NULL,
  `mailinglist_id` int(11) NOT NULL,
  `mailinglist_name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mailinglists_sendings_mailinglist_id` (`mailinglist_id`),
  KEY `mailinglists_sendings_sending_id` (`sending_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailinglists_sendings`
--

LOCK TABLES `mailinglists_sendings` WRITE;
/*!40000 ALTER TABLE `mailinglists_sendings` DISABLE KEYS */;
INSERT INTO `mailinglists_sendings` VALUES (1,52,21,'lista test disiscrizione'),(2,56,20,'lista per test invio'),(3,56,21,'lista test disiscrizione');
/*!40000 ALTER TABLE `mailinglists_sendings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailinglists_unsubscriptions`
--

DROP TABLE IF EXISTS `mailinglists_unsubscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailinglists_unsubscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mailinglist_id` int(11) NOT NULL,
  `unsubscription_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mailinglists_unsubscriptions_mailinglist_id` (`mailinglist_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailinglists_unsubscriptions`
--

LOCK TABLES `mailinglists_unsubscriptions` WRITE;
/*!40000 ALTER TABLE `mailinglists_unsubscriptions` DISABLE KEYS */;
INSERT INTO `mailinglists_unsubscriptions` VALUES (1,21,4);
/*!40000 ALTER TABLE `mailinglists_unsubscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mails`
--

DROP TABLE IF EXISTS `mails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `subject` varchar(255) NOT NULL,
  `html` longtext,
  `text` longtext,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mails_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mails`
--

LOCK TABLES `mails` WRITE;
/*!40000 ALTER TABLE `mails` DISABLE KEYS */;
INSERT INTO `mails` VALUES (14,1,'asd','','boh','<p>email in formato email con link e immagini</p>\r\n\r\n<p><a href=\"http://www.google.it\">link1</a></p>\r\n\r\n<p><img alt=\"\" height=\"63\" src=\"http://127.0.0.1/mes/webroot/file_manager/files/1/msnms.ico\" width=\"63\" /></p>\r\n\r\n<p><a href=\"http://www.facebook.com\">link 2</a></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><a href=\"/boh/index.html\"><img alt=\"\" height=\"109\" src=\"http://127.0.0.1/mes/webroot/file_manager/files/1/msnms_1.ico\" width=\"109\" /></a></p>\r\n','email in formato testo standard','2013-03-26 16:14:58','2013-08-29 10:37:58'),(15,1,'sdacfc','sdcvsdc','sdcsd','','','2013-08-29 09:05:57','2014-02-25 13:47:57'),(16,1,'qweqe','','qweqwe','<p>prova invio email</p>\r\n','','2013-08-29 13:30:53','2013-10-30 09:29:31'),(17,1,'email prova variabili','','Prova variabili','<p>Gentile {{name}} {{surname}} ,</p>\r\n\r\n<p>Ciao</p>\r\n','Gentile {{name}} {{surname}},\r\n\r\nCiao','2014-02-17 11:39:43','2014-02-17 11:39:43'),(18,1,'Prova disiscrizione','Prova disiscrizione','Prova disiscrizione','<p>Per disiscriverti clicca <a href=\"{{unsubscribe()}}\">QUI</a></p>\r\n','','2014-02-21 13:38:51','2014-02-21 14:15:23');
/*!40000 ALTER TABLE `mails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `memberfields`
--

DROP TABLE IF EXISTS `memberfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memberfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `in_grid` tinyint(1) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `memberfields_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `memberfields`
--

LOCK TABLES `memberfields` WRITE;
/*!40000 ALTER TABLE `memberfields` DISABLE KEYS */;
INSERT INTO `memberfields` VALUES (2,'descrizione2','description',1,0,1),(3,'Data di nascita','data_nascita',3,1,1),(4,'Nome','name',0,1,1),(5,'Cognome','surname',0,1,1),(6,'attivo','attivo',2,1,1);
/*!40000 ALTER TABLE `memberfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `memberfieldvalues`
--

DROP TABLE IF EXISTS `memberfieldvalues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memberfieldvalues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value_varchar` varchar(255) DEFAULT NULL,
  `value_text` mediumtext,
  `value_boolean` tinyint(1) DEFAULT NULL,
  `value_date` date DEFAULT NULL,
  `member_id` int(11) NOT NULL,
  `memberfield_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`memberfield_id`,`member_id`),
  UNIQUE KEY `memberfieldvalues_member_id_memberfield_id` (`member_id`,`memberfield_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8
/*!50100 PARTITION BY KEY (member_id,memberfield_id)
PARTITIONS 1024 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `memberfieldvalues`
--

LOCK TABLES `memberfieldvalues` WRITE;
/*!40000 ALTER TABLE `memberfieldvalues` DISABLE KEYS */;
INSERT INTO `memberfieldvalues` VALUES (27,'',NULL,NULL,NULL,44,4),(7,NULL,'asdasda',NULL,NULL,50,2),(17,'thomas',NULL,NULL,NULL,4,4),(20,NULL,NULL,NULL,NULL,60,3),(24,'',NULL,NULL,NULL,45,5),(16,'goma',NULL,NULL,NULL,60,5),(23,'',NULL,NULL,NULL,45,4),(5,NULL,NULL,NULL,'2014-02-11',49,3),(28,'',NULL,NULL,NULL,44,5),(19,NULL,'',NULL,NULL,60,2),(22,NULL,NULL,NULL,NULL,45,3),(4,NULL,'dsfds',NULL,NULL,49,2),(21,NULL,'',NULL,NULL,45,2),(18,'schiavello',NULL,NULL,NULL,4,5),(29,NULL,NULL,1,NULL,60,6),(9,NULL,'',NULL,NULL,4,2),(25,NULL,'',NULL,NULL,44,2),(8,NULL,NULL,NULL,'2014-02-19',50,3),(26,NULL,NULL,NULL,NULL,44,3),(10,NULL,NULL,NULL,'1991-11-23',4,3),(15,'gnoma2',NULL,NULL,NULL,60,4);
/*!40000 ALTER TABLE `memberfieldvalues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `members_user_id` (`user_id`),
  KEY `members_secret` (`secret`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES (4,'thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC','2013-03-25 16:18:02','2014-02-21 16:50:31',1),(5,'lo@lol.it','$2a$10$eZPyIX6LnfjhDEUJazMAUO6ajH7I5Ig31CNvyg4xbSgdslqMEv.Gu','2013-03-26 11:05:44','2013-03-26 11:05:44',1),(6,'lo@lol.it','$2a$10$sdfRkjV1.d6CSugeLvsxXOQ3nFUWMvNn.XA5UDT.5qEwLeOGki.7G','2013-03-26 12:10:00','2013-03-26 12:10:00',1),(7,'lo@lol2.it','$2a$10$07lYJbyfZNIWe4NpH9DZXeB.IRtzqVAlSNrlJu8Q.r8OOi6MfCGCq','2013-03-26 12:10:33','2013-03-26 12:10:33',1),(8,'lo@lol2.it','$2a$10$jQWmmtmnTzNgeLhZsOxx6OcxeH8rnHJ/WNtf5cchoahaTXokA/zJy','2013-03-26 12:10:33','2013-03-26 12:10:33',1),(9,'lo@lol.it','$2a$10$UMbY0n859xS6mck5ePHYQOj.LdhVua1mYGEa38pSpOI46C3ZD8qIe','2013-03-26 14:48:26','2013-03-26 14:48:26',1),(10,'aaaa@aaa.it','$2a$10$hDRHYFeAoiwwujs3mBHUye1cEpHLtOtpjJHQWnf3/NDhkaCcGSVIa','2013-03-26 15:05:20','2013-03-26 15:05:20',1),(11,'thomas.schiavello@two-b.it','$2a$10$63gk15KcVStlPLfy108BnOpyxtGMEchZONET5hvT1XWDfZyyndPJS','2013-03-26 15:07:15','2013-08-13 10:51:44',1),(12,'emailasdasdasd@example.com','$2a$10$cvLtlGJKEijq.bJs4pyYI.WZFmTrKckD9ozCewunKdWvpAoGr8OYK','2013-03-26 15:08:14','2013-04-08 15:10:48',1),(13,'asd@asd.it','$2a$10$3d2Rl2x1byOhUCuPUxsXje3352kzpKxNl790H6HGHXCLgjPd2rR0e','2013-03-26 15:08:43','2013-03-26 15:08:43',1),(14,'sbiri@sburu.it','$2a$10$A.BSVeHYthXlTI0IEN6nVusQ9Hq.TdETzGWM2166CcB1qoIOLkSc6','2013-03-26 16:16:57','2013-03-26 16:16:57',1),(15,'email1$@example.com','$2a$10$K04hbtUEL/k8RnjHbcNi1e.Zar7r499eF.Ft5PuV50wDAlI01KXvG','2013-04-08 12:37:10','2013-04-08 15:32:36',1),(16,'Email2@asds.com','$2a$10$nw54nWZAcB8dQE4zudoDmurvopvscDtMoXEIgbkyZqDK.uAOTFWkG','2013-04-08 12:37:10','2013-04-08 12:37:10',1),(17,'email3@example.com','$2a$10$Xi62R2HjK5nk2oG2MgtVj.lAN8NvKko9CZ7DX9Eh9jAq2bmW9iaOS','2013-04-08 12:37:10','2013-04-08 12:37:10',1),(18,'ciccio.pasticcio@cicciolandia.com','$2a$10$tgv1F2as1P81U8HAV.DgWOnTx6MeJ63U1Dl3vpVc2B8fTE575Fqn.','2013-04-08 16:55:31','2013-04-08 16:55:31',1),(19,'add@dee.it','$2a$10$UoKNLVROTCy8zIEubwpYseyGzI0D9/MtxB3qi9HicOoJanZmCGbMC','2013-04-08 17:24:08','2013-04-08 17:24:08',1),(20,'Ssdsds.lool@gmail.it','$2a$10$rzIpLG7MqJE3WaEL0cuJ0.W.Mn1liTfUBufEchukeyzEb8Rhbfaay','2013-04-08 17:26:06','2013-04-08 17:26:06',1),(21,'Ssdsds.lool@gmaisdaaasl.it','$2a$10$6cu03Hl6Vt8pWIBhYrAdkup9rTzAnFiTP86iCGrdyXsdmFBhiSbVu','2013-04-08 17:32:20','2013-04-08 17:32:20',1),(22,'asd@asd2.it','$2a$10$XL430wprK2sJLvggekUUo.ZOrVZChvaWUEc4q.mOH4631zFVVSfWC','2013-04-19 11:39:40','2013-04-19 11:39:40',1),(23,'email2_keororo@example.com','$2a$10$dXzYEcpsjP1rzwRgK.UYLO3JLfmaqec.g2lGZZ4oglTWXOvkm4enm','2013-04-19 11:44:11','2013-04-19 11:44:11',1),(24,'email1_keororo@example.com','$2a$10$S7HM4atH.nipsBMLfEIh7eWUcpfiYUrZmaDPzuQI7KnoxUkQBhEpW','2013-04-19 11:45:22','2013-04-19 11:45:22',1),(25,'email3_keororo@example.com','$2a$10$sYCeoa1KTsUGylGeVgFVwe0OqA/pnbSOFAfyWF4a4VtwDdBpa6YcG','2013-04-19 11:45:22','2013-04-19 11:45:22',1),(26,'ddee@eded.it','$2a$10$rMObwHw8kEZ95awXiL5W5usXC892ZnvHSxwpAXJt8WHEGh3NBDHhq','2013-07-25 14:19:00','2013-07-25 14:19:00',1),(27,'asassds@lol.fff','$2a$10$jEkNKruEJIT3P7NfNwv1SeKcmm4FDheXfMRA2TNiM1kAa7DdRy.lW','2013-07-25 14:21:41','2013-07-25 14:21:41',1),(28,'c@c.com','$2a$10$psFQLtIJZO11TiOov0o1uuuTlW0rRYZOUEAEJ1TQuvUtD02Z9gc6m','2013-07-25 14:34:24','2013-07-25 14:34:24',1),(29,'c@c.cog','$2a$10$qLTYpc3ZHWkoDn9XgxPcFOG0zWEiGNf1J2rvudPXQ0lZKErsNdZYa','2013-07-25 14:35:29','2013-07-25 14:35:29',1),(30,'c@c.cox','$2a$10$k0r1jRIGAHvdcBKQW6JX7udVZGXQ49pZjUjp2tYaXSM8Uehf5khAa','2013-07-25 14:35:51','2013-07-25 14:35:51',1),(31,'c@c.coz','$2a$10$ObyMoyA7XTiILy6xLuI86eUgrF4ApICxxscsj4.vMvojRYRGlEz46','2013-07-25 14:36:15','2013-07-25 14:36:15',1),(32,'a@a.asd','$2a$10$apTnrLIU.HU3P0UV3ij4Jeb5K.dxMwMy4xhwWvKlTbsRwaahuIINC','2013-07-25 14:38:03','2013-07-25 14:38:03',1),(33,'ttt@ttt.com','$2a$10$EQNHkirEQP7JWoLLF8c3w.LSFiSiaVZuwuEu8Ya6FVqPcMqdeRR/a','2013-07-25 15:16:58','2013-07-25 15:16:58',1),(34,'ttt@ttt.cog','$2a$10$if1dwwucFU7p/nAiz7QgKuEfmRoAtzp0ZK/swXEhOkZtjf5sT/ebi','2013-07-25 15:17:20','2013-07-25 15:17:20',1),(35,'ttt@ttt.con','$2a$10$aP.mvwmyPrQTvIDs36Ce7.Kjuf5Rxi67CDzPXZjFAY9GI1JzjhBLG','2013-07-25 15:18:32','2013-07-25 15:18:32',1),(36,'strimpel@strimpel.it','','2013-07-26 08:48:20','2013-07-26 08:48:20',1),(37,'cicci@c.it','','2013-07-26 08:54:25','2013-07-26 08:54:25',1),(38,'xxx@xxx.xxx','$2a$10$ATH6TmIjCITKKRB5uGjLQ.f1Chak7gV.EV0mx/paX/lsY9Yfqdbuy','2013-07-26 09:08:11','2013-07-26 09:19:13',1),(39,'ciao@bau.it','','2013-07-26 13:19:39','2013-07-26 13:19:39',1),(40,'mmm@mmm.it','','2013-07-26 13:53:05','2013-07-26 13:53:05',1),(41,'zxcz@xcxc.it','','2013-07-26 13:53:39','2013-07-26 13:53:39',1),(42,'ninna@nanna.it','','2013-07-30 12:47:19','2013-07-30 12:47:19',1),(43,'nonna@nnn.it','','2013-07-30 12:48:02','2013-07-30 12:48:02',1),(44,'cis@ccc.net','','2013-07-30 12:51:10','2014-02-21 10:58:20',1),(45,'xxx@xxx.xxz','','2013-07-30 12:51:54','2014-02-21 10:57:15',1),(46,'sbem@sbem.it','','2013-07-30 12:52:19','2013-08-27 13:33:05',1),(47,'gne@gne.it','','2013-08-27 14:08:26','2013-08-27 14:08:26',1),(48,'p@p.it','$2a$10$OyYy5ejdX36g2CWVDRBrF.dRU1k2t9lcYRHBruIYHDwUhywQMElVm','2014-02-07 09:07:50','2014-02-07 09:07:50',1),(49,'p@pp.it','','2014-02-07 09:08:38','2014-02-07 11:23:51',1),(50,'vfvfvf@fvfvf.it','','2014-02-07 11:11:09','2014-02-07 11:22:17',1),(60,'ciccicci@cucucu.com','$2a$10$gqOzqqGrngznIfITmZ6CuO87hBaf0C4vTbvNFMjrDrf2Ph2N3goMe','2014-02-11 15:17:03','2014-03-06 16:09:43',1);
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipients`
--

DROP TABLE IF EXISTS `recipients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sended` tinyint(1) NOT NULL DEFAULT '0',
  `sended_time` varchar(50) DEFAULT NULL,
  `errors` tinyint(1) DEFAULT '0',
  `errors_details` text,
  `sending_id` int(11) NOT NULL,
  `opened` tinyint(1) DEFAULT '0',
  `opened_time` datetime DEFAULT NULL,
  `device` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `member_id` int(11) NOT NULL,
  `member_data` longtext NOT NULL,
  `member_email` varchar(255) NOT NULL,
  `member_secret` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`sending_id`),
  KEY `recipients_sending_id` (`sending_id`),
  KEY `recipients_member_secret` (`member_secret`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8
/*!50100 PARTITION BY LINEAR HASH (sending_id)
PARTITIONS 1024 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipients`
--

LOCK TABLES `recipients` WRITE;
/*!40000 ALTER TABLE `recipients` DISABLE KEYS */;
INSERT INTO `recipients` VALUES (7,0,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,11,'','thomas.schiavello@two-b.it','$2a$10$63gk15KcVStlPLfy108BnOpyxtGMEchZONET5hvT1XWDfZyyndPJS'),(9,0,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,13,'','asd@asd.it','$2a$10$3d2Rl2x1byOhUCuPUxsXje3352kzpKxNl790H6HGHXCLgjPd2rR0e'),(10,0,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,15,'','email1$@example.com','$2a$10$K04hbtUEL/k8RnjHbcNi1e.Zar7r499eF.Ft5PuV50wDAlI01KXvG'),(11,0,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,18,'','ciccio.pasticcio@cicciolandia.com','$2a$10$tgv1F2as1P81U8HAV.DgWOnTx6MeJ63U1Dl3vpVc2B8fTE575Fqn.'),(12,0,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,23,'','email2_keororo@example.com','$2a$10$dXzYEcpsjP1rzwRgK.UYLO3JLfmaqec.g2lGZZ4oglTWXOvkm4enm'),(13,0,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,37,'','cicci@c.it',''),(14,0,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,42,'','ninna@nanna.it',''),(15,0,NULL,1,NULL,1,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,46,'','sbem@sbem.it',''),(16,0,NULL,1,NULL,1,1,'0000-00-00 00:00:00','pc','Linux','Firefox',NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(18,1,'1377263432',0,NULL,3,NULL,NULL,NULL,'Linux','Firefox',NULL,NULL,NULL,NULL,14,'','sbiri@sburu.it','$2a$10$A.BSVeHYthXlTI0IEN6nVusQ9Hq.TdETzGWM2166CcB1qoIOLkSc6'),(19,1,'1377263457',0,NULL,3,NULL,NULL,NULL,'Mac','Safari',NULL,NULL,NULL,NULL,16,'','Email2@asds.com','$2a$10$nw54nWZAcB8dQE4zudoDmurvopvscDtMoXEIgbkyZqDK.uAOTFWkG'),(20,1,'1377263460',0,NULL,3,NULL,NULL,NULL,'Mac',NULL,NULL,NULL,NULL,NULL,17,'','email3@example.com','$2a$10$Xi62R2HjK5nk2oG2MgtVj.lAN8NvKko9CZ7DX9Eh9jAq2bmW9iaOS'),(21,1,'1377263463',0,NULL,3,NULL,NULL,NULL,'Mac',NULL,NULL,NULL,NULL,NULL,15,'','email1$@example.com','$2a$10$K04hbtUEL/k8RnjHbcNi1e.Zar7r499eF.Ft5PuV50wDAlI01KXvG'),(22,0,'1377255876',1,NULL,3,NULL,NULL,NULL,'Android',NULL,NULL,NULL,NULL,NULL,18,'','ciccio.pasticcio@cicciolandia.com','$2a$10$tgv1F2as1P81U8HAV.DgWOnTx6MeJ63U1Dl3vpVc2B8fTE575Fqn.'),(23,0,'1377255880',1,NULL,3,NULL,NULL,NULL,'Ios',NULL,NULL,NULL,NULL,NULL,19,'','add@dee.it','$2a$10$UoKNLVROTCy8zIEubwpYseyGzI0D9/MtxB3qi9HicOoJanZmCGbMC'),(24,0,'1377092146',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,20,'','Ssdsds.lool@gmail.it','$2a$10$rzIpLG7MqJE3WaEL0cuJ0.W.Mn1liTfUBufEchukeyzEb8Rhbfaay'),(25,0,'1377092149',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,21,'','Ssdsds.lool@gmaisdaaasl.it','$2a$10$6cu03Hl6Vt8pWIBhYrAdkup9rTzAnFiTP86iCGrdyXsdmFBhiSbVu'),(26,0,'1377092151',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,23,'','email2_keororo@example.com','$2a$10$dXzYEcpsjP1rzwRgK.UYLO3JLfmaqec.g2lGZZ4oglTWXOvkm4enm'),(27,0,'1377092154',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,26,'','ddee@eded.it','$2a$10$rMObwHw8kEZ95awXiL5W5usXC892ZnvHSxwpAXJt8WHEGh3NBDHhq'),(28,0,'1377092156',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,27,'','asassds@lol.fff','$2a$10$jEkNKruEJIT3P7NfNwv1SeKcmm4FDheXfMRA2TNiM1kAa7DdRy.lW'),(29,0,'1377092159',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,28,'','c@c.com','$2a$10$psFQLtIJZO11TiOov0o1uuuTlW0rRYZOUEAEJ1TQuvUtD02Z9gc6m'),(30,0,'1377092161',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,29,'','c@c.cog','$2a$10$qLTYpc3ZHWkoDn9XgxPcFOG0zWEiGNf1J2rvudPXQ0lZKErsNdZYa'),(31,0,'1377092164',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,30,'','c@c.cox','$2a$10$k0r1jRIGAHvdcBKQW6JX7udVZGXQ49pZjUjp2tYaXSM8Uehf5khAa'),(32,0,'1377092167',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,31,'','c@c.coz','$2a$10$ObyMoyA7XTiILy6xLuI86eUgrF4ApICxxscsj4.vMvojRYRGlEz46'),(33,0,'1377092170',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,32,'','a@a.asd','$2a$10$apTnrLIU.HU3P0UV3ij4Jeb5K.dxMwMy4xhwWvKlTbsRwaahuIINC'),(34,0,'1377254760',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,33,'','ttt@ttt.com','$2a$10$EQNHkirEQP7JWoLLF8c3w.LSFiSiaVZuwuEu8Ya6FVqPcMqdeRR/a'),(35,0,'1377254903',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,34,'','ttt@ttt.cog','$2a$10$if1dwwucFU7p/nAiz7QgKuEfmRoAtzp0ZK/swXEhOkZtjf5sT/ebi'),(36,0,'1377254906',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,35,'','ttt@ttt.con','$2a$10$aP.mvwmyPrQTvIDs36Ce7.Kjuf5Rxi67CDzPXZjFAY9GI1JzjhBLG'),(37,0,'1377254908',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,38,'','xxx@xxx.xxx','$2a$10$ATH6TmIjCITKKRB5uGjLQ.f1Chak7gV.EV0mx/paX/lsY9Yfqdbuy'),(38,0,'1377254911',1,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'','thomas.schiavello@two-b.it','$2a$10$63gk15KcVStlPLfy108BnOpyxtGMEchZONET5hvT1XWDfZyyndPJS'),(39,0,NULL,1,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(40,0,NULL,1,NULL,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(45,0,NULL,1,NULL,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,38,'','xxx@xxx.xxx','$2a$10$ATH6TmIjCITKKRB5uGjLQ.f1Chak7gV.EV0mx/paX/lsY9Yfqdbuy'),(46,0,NULL,1,NULL,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,43,'','nonna@nnn.it',''),(47,0,NULL,1,NULL,8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,38,'','xxx@xxx.xxx','$2a$10$ATH6TmIjCITKKRB5uGjLQ.f1Chak7gV.EV0mx/paX/lsY9Yfqdbuy'),(48,0,NULL,1,NULL,8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,43,'','nonna@nnn.it',''),(49,1,'1383126023',0,NULL,9,1,'2013-10-30 09:46:27','Pc','unknown','Default Browser',NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(50,1,'1383126026',0,NULL,9,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(51,1,'1383126682',0,NULL,10,1,'2013-10-30 09:52:26','Pc','unknown','Default Browser',NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(52,1,'1383126686',0,NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(53,1,'1383142565',0,NULL,11,1,'2013-10-30 14:17:56','Pc','Linux','Chrome',NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(54,1,'1383142569',0,NULL,11,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(55,1,'1383144743',0,NULL,12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(56,1,'1383144746',0,NULL,12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(57,1,'1383144197',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,'','lo@lol.it','$2a$10$eZPyIX6LnfjhDEUJazMAUO6ajH7I5Ig31CNvyg4xbSgdslqMEv.Gu'),(58,1,'1383144200',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,'','lo@lol.it','$2a$10$sdfRkjV1.d6CSugeLvsxXOQ3nFUWMvNn.XA5UDT.5qEwLeOGki.7G'),(59,1,'1383144206',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,'','lo@lol2.it','$2a$10$07lYJbyfZNIWe4NpH9DZXeB.IRtzqVAlSNrlJu8Q.r8OOi6MfCGCq'),(60,1,'1383144210',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'','lo@lol2.it','$2a$10$jQWmmtmnTzNgeLhZsOxx6OcxeH8rnHJ/WNtf5cchoahaTXokA/zJy'),(61,1,'1383144213',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'','lo@lol.it','$2a$10$UMbY0n859xS6mck5ePHYQOj.LdhVua1mYGEa38pSpOI46C3ZD8qIe'),(62,1,'1383144216',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'','aaaa@aaa.it','$2a$10$hDRHYFeAoiwwujs3mBHUye1cEpHLtOtpjJHQWnf3/NDhkaCcGSVIa'),(63,1,'1383144222',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'','thomas.schiavello@two-b.it','$2a$10$63gk15KcVStlPLfy108BnOpyxtGMEchZONET5hvT1XWDfZyyndPJS'),(64,1,'1383144226',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,12,'','emailasdasdasd@example.com','$2a$10$cvLtlGJKEijq.bJs4pyYI.WZFmTrKckD9ozCewunKdWvpAoGr8OYK'),(65,1,'1383144232',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,'','asd@asd.it','$2a$10$3d2Rl2x1byOhUCuPUxsXje3352kzpKxNl790H6HGHXCLgjPd2rR0e'),(66,1,'1383144238',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15,'','email1$@example.com','$2a$10$K04hbtUEL/k8RnjHbcNi1e.Zar7r499eF.Ft5PuV50wDAlI01KXvG'),(67,1,'1383144244',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,18,'','ciccio.pasticcio@cicciolandia.com','$2a$10$tgv1F2as1P81U8HAV.DgWOnTx6MeJ63U1Dl3vpVc2B8fTE575Fqn.'),(68,1,'1383144248',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,23,'','email2_keororo@example.com','$2a$10$dXzYEcpsjP1rzwRgK.UYLO3JLfmaqec.g2lGZZ4oglTWXOvkm4enm'),(69,1,'1383144252',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,37,'','cicci@c.it',''),(70,1,'1383144258',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,42,'','ninna@nanna.it',''),(71,1,'1383144263',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,46,'','sbem@sbem.it',''),(72,1,'1383144266',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(73,1,'1383144270',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(74,1,'1383144274',0,NULL,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(75,1,'1383144742',0,NULL,14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(76,1,'1383144745',0,NULL,14,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(77,1,'1383144963',0,NULL,15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(78,1,'1383144966',0,NULL,15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(79,1,'1383657811',0,NULL,16,1,'2013-11-05 13:23:47','Pc','unknown','Default Browser',NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(80,1,'1383657814',0,NULL,16,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(81,1,'1383657931',0,NULL,17,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(82,1,'1383657935',0,NULL,17,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(83,1,'1383658002',0,NULL,18,1,'2013-11-05 13:26:54','Pc','unknown','Default Browser',NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(84,1,'1383658006',0,NULL,18,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(85,1,'1383658356',0,NULL,19,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(86,1,'1383658360',0,NULL,19,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(87,1,'1383658567',0,NULL,20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(88,1,'1383658570',0,NULL,20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(89,1,'1383658607',0,NULL,21,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(90,1,'1383658610',0,NULL,21,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(91,1,'1383658996',0,NULL,22,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(92,1,'1383658999',0,NULL,22,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(93,1,'1383659199',0,NULL,23,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(94,1,'1383659201',0,NULL,23,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(95,1,'1383659808',0,NULL,24,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(96,0,NULL,1,NULL,24,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(97,1,'1383660083',0,NULL,25,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(98,1,'1383660086',0,NULL,25,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(99,1,'1383660326',0,NULL,26,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(100,1,'1383660329',0,NULL,26,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(101,1,'1383660638',0,NULL,27,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(102,1,'1383660642',0,NULL,27,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(103,1,'1383660905',0,NULL,28,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(104,1,'1383660907',0,NULL,28,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(105,1,'1383661167',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,'','lo@lol.it','$2a$10$eZPyIX6LnfjhDEUJazMAUO6ajH7I5Ig31CNvyg4xbSgdslqMEv.Gu'),(106,1,'1383661171',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,'','lo@lol.it','$2a$10$sdfRkjV1.d6CSugeLvsxXOQ3nFUWMvNn.XA5UDT.5qEwLeOGki.7G'),(107,1,'1383661175',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,'','lo@lol2.it','$2a$10$07lYJbyfZNIWe4NpH9DZXeB.IRtzqVAlSNrlJu8Q.r8OOi6MfCGCq'),(108,1,'1383661178',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'','lo@lol2.it','$2a$10$jQWmmtmnTzNgeLhZsOxx6OcxeH8rnHJ/WNtf5cchoahaTXokA/zJy'),(109,1,'1383661182',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'','lo@lol.it','$2a$10$UMbY0n859xS6mck5ePHYQOj.LdhVua1mYGEa38pSpOI46C3ZD8qIe'),(110,1,'1383661186',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'','aaaa@aaa.it','$2a$10$hDRHYFeAoiwwujs3mBHUye1cEpHLtOtpjJHQWnf3/NDhkaCcGSVIa'),(111,1,'1383661192',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'','thomas.schiavello@two-b.it','$2a$10$63gk15KcVStlPLfy108BnOpyxtGMEchZONET5hvT1XWDfZyyndPJS'),(112,1,'1383661196',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,12,'','emailasdasdasd@example.com','$2a$10$cvLtlGJKEijq.bJs4pyYI.WZFmTrKckD9ozCewunKdWvpAoGr8OYK'),(113,1,'1383661201',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,'','asd@asd.it','$2a$10$3d2Rl2x1byOhUCuPUxsXje3352kzpKxNl790H6HGHXCLgjPd2rR0e'),(114,1,'1383661204',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15,'','email1$@example.com','$2a$10$K04hbtUEL/k8RnjHbcNi1e.Zar7r499eF.Ft5PuV50wDAlI01KXvG'),(115,1,'1383661207',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,18,'','ciccio.pasticcio@cicciolandia.com','$2a$10$tgv1F2as1P81U8HAV.DgWOnTx6MeJ63U1Dl3vpVc2B8fTE575Fqn.'),(116,1,'1383661210',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,23,'','email2_keororo@example.com','$2a$10$dXzYEcpsjP1rzwRgK.UYLO3JLfmaqec.g2lGZZ4oglTWXOvkm4enm'),(117,1,'1383661214',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,37,'','cicci@c.it',''),(118,1,'1383661217',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,42,'','ninna@nanna.it',''),(119,1,'1383661221',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,46,'','sbem@sbem.it',''),(120,1,'1383661224',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(121,1,'1383661227',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(122,1,'1383661230',0,NULL,29,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(123,1,'1383661382',0,NULL,30,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(124,1,'1383661385',0,NULL,30,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(125,1,'1391531470',0,NULL,31,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(126,1,'1391531486',0,NULL,31,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(127,1,'1391533203',0,NULL,32,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'','thomas.schiavello@gmail.com','$2a$10$aohJxik5Nrwu1u79U7.F1.ORJBCITINZTTHjUYv9jncl28DEEBHce'),(128,1,'1391533207',0,NULL,32,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'','gne@gne.it',''),(147,1,'1392637322',0,NULL,36,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(148,1,'1392637326',0,NULL,36,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'{\"id\":\"47\",\"email\":\"gne@gne.it\",\"secret\":\"\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','gne@gne.it',''),(149,1,'1392637548',0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,'{\"id\":\"5\",\"email\":\"lo@lol.it\",\"secret\":\"$2a$10$eZPyIX6LnfjhDEUJazMAUO6ajH7I5Ig31CNvyg4xbSgdslqMEv.Gu\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','lo@lol.it','$2a$10$eZPyIX6LnfjhDEUJazMAUO6ajH7I5Ig31CNvyg4xbSgdslqMEv.Gu'),(150,1,'1392637556',0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,'{\"id\":\"6\",\"email\":\"lo@lol.it\",\"secret\":\"$2a$10$sdfRkjV1.d6CSugeLvsxXOQ3nFUWMvNn.XA5UDT.5qEwLeOGki.7G\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','lo@lol.it','$2a$10$sdfRkjV1.d6CSugeLvsxXOQ3nFUWMvNn.XA5UDT.5qEwLeOGki.7G'),(151,1,'1392637561',0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,'{\"id\":\"7\",\"email\":\"lo@lol2.it\",\"secret\":\"$2a$10$07lYJbyfZNIWe4NpH9DZXeB.IRtzqVAlSNrlJu8Q.r8OOi6MfCGCq\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','lo@lol2.it','$2a$10$07lYJbyfZNIWe4NpH9DZXeB.IRtzqVAlSNrlJu8Q.r8OOi6MfCGCq'),(152,1,'1392637563',0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,'{\"id\":\"8\",\"email\":\"lo@lol2.it\",\"secret\":\"$2a$10$jQWmmtmnTzNgeLhZsOxx6OcxeH8rnHJ\\/WNtf5cchoahaTXokA\\/zJy\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','lo@lol2.it','$2a$10$jQWmmtmnTzNgeLhZsOxx6OcxeH8rnHJ/WNtf5cchoahaTXokA/zJy'),(153,1,'1392637567',0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,'{\"id\":\"9\",\"email\":\"lo@lol.it\",\"secret\":\"$2a$10$UMbY0n859xS6mck5ePHYQOj.LdhVua1mYGEa38pSpOI46C3ZD8qIe\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','lo@lol.it','$2a$10$UMbY0n859xS6mck5ePHYQOj.LdhVua1mYGEa38pSpOI46C3ZD8qIe'),(154,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,'{\"id\":\"10\",\"email\":\"aaaa@aaa.it\",\"secret\":\"$2a$10$hDRHYFeAoiwwujs3mBHUye1cEpHLtOtpjJHQWnf3\\/NDhkaCcGSVIa\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','aaaa@aaa.it','$2a$10$hDRHYFeAoiwwujs3mBHUye1cEpHLtOtpjJHQWnf3/NDhkaCcGSVIa'),(155,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'{\"id\":\"11\",\"email\":\"thomas.schiavello@two-b.it\",\"secret\":\"$2a$10$63gk15KcVStlPLfy108BnOpyxtGMEchZONET5hvT1XWDfZyyndPJS\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','thomas.schiavello@two-b.it','$2a$10$63gk15KcVStlPLfy108BnOpyxtGMEchZONET5hvT1XWDfZyyndPJS'),(156,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,12,'{\"id\":\"12\",\"email\":\"emailasdasdasd@example.com\",\"secret\":\"$2a$10$cvLtlGJKEijq.bJs4pyYI.WZFmTrKckD9ozCewunKdWvpAoGr8OYK\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','emailasdasdasd@example.com','$2a$10$cvLtlGJKEijq.bJs4pyYI.WZFmTrKckD9ozCewunKdWvpAoGr8OYK'),(157,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,'{\"id\":\"13\",\"email\":\"asd@asd.it\",\"secret\":\"$2a$10$3d2Rl2x1byOhUCuPUxsXje3352kzpKxNl790H6HGHXCLgjPd2rR0e\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','asd@asd.it','$2a$10$3d2Rl2x1byOhUCuPUxsXje3352kzpKxNl790H6HGHXCLgjPd2rR0e'),(158,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15,'{\"id\":\"15\",\"email\":\"email1$@example.com\",\"secret\":\"$2a$10$K04hbtUEL\\/k8RnjHbcNi1e.Zar7r499eF.Ft5PuV50wDAlI01KXvG\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','email1$@example.com','$2a$10$K04hbtUEL/k8RnjHbcNi1e.Zar7r499eF.Ft5PuV50wDAlI01KXvG'),(159,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,18,'{\"id\":\"18\",\"email\":\"ciccio.pasticcio@cicciolandia.com\",\"secret\":\"$2a$10$tgv1F2as1P81U8HAV.DgWOnTx6MeJ63U1Dl3vpVc2B8fTE575Fqn.\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','ciccio.pasticcio@cicciolandia.com','$2a$10$tgv1F2as1P81U8HAV.DgWOnTx6MeJ63U1Dl3vpVc2B8fTE575Fqn.'),(160,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,23,'{\"id\":\"23\",\"email\":\"email2_keororo@example.com\",\"secret\":\"$2a$10$dXzYEcpsjP1rzwRgK.UYLO3JLfmaqec.g2lGZZ4oglTWXOvkm4enm\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','email2_keororo@example.com','$2a$10$dXzYEcpsjP1rzwRgK.UYLO3JLfmaqec.g2lGZZ4oglTWXOvkm4enm'),(161,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,37,'{\"id\":\"37\",\"email\":\"cicci@c.it\",\"secret\":\"\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','cicci@c.it',''),(162,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,42,'{\"id\":\"42\",\"email\":\"ninna@nanna.it\",\"secret\":\"\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','ninna@nanna.it',''),(163,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,46,'{\"id\":\"46\",\"email\":\"sbem@sbem.it\",\"secret\":\"\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','sbem@sbem.it',''),(164,0,NULL,0,NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(165,1,'1392637975',0,NULL,38,1,'2014-02-17 11:53:53','Pc','unknown','Default Browser',NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(166,1,'1392637977',0,NULL,38,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'{\"id\":\"47\",\"email\":\"gne@gne.it\",\"secret\":\"\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','gne@gne.it',''),(167,1,'1392638096',0,NULL,39,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(168,1,'1392638104',0,NULL,39,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'{\"id\":\"47\",\"email\":\"gne@gne.it\",\"secret\":\"\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','gne@gne.it',''),(169,1,'1392638187',0,NULL,40,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(170,1,'1392638192',0,NULL,40,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'{\"id\":\"47\",\"email\":\"gne@gne.it\",\"secret\":\"\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','gne@gne.it',''),(171,1,'1392638226',0,NULL,41,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(172,1,'1392638229',0,NULL,41,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'{\"id\":\"47\",\"email\":\"gne@gne.it\",\"secret\":\"\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','gne@gne.it',''),(175,1,'1392992788',0,NULL,43,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(176,1,'1392993083',0,NULL,44,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(177,1,'1392994323',0,NULL,45,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(178,1,'1392994399',0,NULL,46,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(179,1,'1392994546',0,NULL,47,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(180,1,'1392994576',0,NULL,48,1,'2014-02-21 14:56:30','Pc','unknown','Default Browser',NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(181,1,'1392994907',0,NULL,49,1,'2014-02-21 15:02:36','Pc','unknown','Default Browser',NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(182,1,'1392995028',0,NULL,50,1,'2014-02-21 15:04:01','Pc','unknown','Default Browser',NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(183,1,'1392999154',0,NULL,51,1,'2014-02-21 16:12:54','Pc','unknown','Default Browser',NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(184,1,'1393001459',0,NULL,52,1,'2014-02-21 16:51:09','Pc','unknown','Default Browser',NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(185,0,NULL,0,NULL,54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(186,0,NULL,0,NULL,54,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'{\"id\":\"47\",\"email\":\"gne@gne.it\",\"secret\":\"\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','gne@gne.it',''),(189,0,NULL,0,NULL,56,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'{\"id\":\"4\",\"email\":\"thomas.schiavello@gmail.com\",\"secret\":\"$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg\\/TPVKhOaPhOcLC\",\"description\":\"\",\"data_nascita\":\"1991-11-23\",\"name\":\"thomas\",\"surname\":\"schiavello\"}','thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC'),(190,0,NULL,0,NULL,56,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,47,'{\"id\":\"47\",\"email\":\"gne@gne.it\",\"secret\":\"\",\"description\":null,\"data_nascita\":null,\"name\":null,\"surname\":null}','gne@gne.it','');
/*!40000 ALTER TABLE `recipients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sendings`
--

DROP TABLE IF EXISTS `sendings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sendings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient_count` int(11) NOT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `mailinglist_ids` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `errors` tinyint(1) DEFAULT '0',
  `errors_details` text,
  `type` int(11) NOT NULL,
  `mail_id` int(11) NOT NULL,
  `smtp_id` int(11) NOT NULL,
  `smtp_email` varchar(255) NOT NULL,
  `time` varchar(50) DEFAULT NULL,
  `note` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `started` varchar(50) DEFAULT NULL,
  `ended` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sendings_mail_id` (`mail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sendings`
--

LOCK TABLES `sendings` WRITE;
/*!40000 ALTER TABLE `sendings` DISABLE KEYS */;
INSERT INTO `sendings` VALUES (1,0,NULL,'',2,1,NULL,2,14,2,'doshu_mes@yahoo.it',NULL,'','2013-08-13 14:17:27','2013-08-13 14:17:27','1383125157','1383125198'),(3,0,NULL,'',2,1,NULL,0,14,1,'thomas.schiavello@gmail.com',NULL,'','2013-08-21 10:39:11','2013-08-21 10:39:11','1383125157','1383125163'),(4,0,NULL,'',2,1,NULL,0,14,1,'thomas.schiavello@gmail.com',NULL,'','2013-08-27 14:26:36','2013-08-27 14:26:36','1383125157','1383125158'),(7,0,NULL,'',2,1,NULL,0,14,1,'thomas.schiavello@gmail.com',NULL,'','2013-08-28 08:53:13','2013-08-28 08:53:13','1383125157','1383125158'),(8,0,NULL,'',2,1,NULL,0,14,2,'doshu_mes@yahoo.it',NULL,'','2013-08-28 08:53:39','2013-08-28 08:53:39','1383125157','1383125167'),(9,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-10-30 09:29:49','2013-10-30 09:40:20','1383126020','1383126026'),(10,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-10-30 09:51:18','2013-10-30 09:51:18','1383126678','1383126686'),(11,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-10-30 14:16:02','2013-10-30 14:16:02','1383142562','1383142570'),(12,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com','1383144000','','2013-10-30 14:38:44','2013-10-30 14:38:44','1383144740','1383144746'),(13,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-10-30 14:43:10','2013-10-30 14:43:10','1383144190','1383144274'),(14,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com','1383144600','','2013-10-30 14:48:57','2013-10-30 14:48:57','1383144739','1383144745'),(15,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-10-30 14:54:55','2013-10-30 14:54:55','1383144959','1383144966'),(16,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com','1383145200','','2013-10-30 14:55:30','2013-10-30 14:55:30','1383657806','1383657815'),(17,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-11-05 13:25:27','2013-11-05 13:25:27','1383657927','1383657935'),(18,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-11-05 13:26:39','2013-11-05 13:26:39','1383657999','1383658006'),(19,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com','1383658200','','2013-11-05 13:27:22','2013-11-05 13:27:22','1383658353','1383658360'),(20,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-11-05 13:36:03','2013-11-05 13:36:03','1383658563','1383658570'),(21,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-11-05 13:36:43','2013-11-05 13:36:43','1383658603','1383658610'),(22,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com','1383658800','','2013-11-05 13:37:13','2013-11-05 13:37:13','1383658993','1383658999'),(23,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com','1383659100','','2013-11-05 13:43:40','2013-11-05 13:43:40','1383659195','1383659202'),(24,0,NULL,'',2,1,NULL,0,16,1,'thomas.schiavello@gmail.com','1383659400','','2013-11-05 13:47:00','2013-11-05 13:47:00','1383659804','1383659808'),(25,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com','1383660000','','2013-11-05 13:57:28','2013-11-05 13:57:28','1383660080','1383660086'),(26,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com','1383660300','','2013-11-05 14:01:55','2013-11-05 14:01:55','1383660322','1383660329'),(27,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com','1383660600','','2013-11-05 14:05:48','2013-11-05 14:05:48','1383660634','1383660642'),(28,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com','1383660900','','2013-11-05 14:13:38','2013-11-05 14:13:38','1383660900','1383660907'),(29,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-11-05 14:19:24','2013-11-05 14:19:24','1383661164','1383661230'),(30,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2013-11-05 14:22:58','2013-11-05 14:22:58','1383661378','1383661385'),(31,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-04 16:27:41','2014-02-04 16:27:41','1391531454','1391593254'),(32,0,NULL,'',2,0,NULL,0,16,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-04 16:59:56','2014-02-04 16:59:56','1391591449','1391593254'),(36,0,NULL,'',2,0,NULL,2,17,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-17 11:40:03','2014-02-17 11:40:03','1392637318','1392637326'),(38,0,NULL,'',2,0,NULL,0,17,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-17 11:49:05','2014-02-17 11:49:05','1392637968','1392637978'),(39,0,NULL,'',2,0,NULL,0,17,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-17 11:54:50','2014-02-17 11:54:50','1392638090','1392638104'),(40,0,NULL,'',2,0,NULL,0,17,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-17 11:56:24','2014-02-17 11:56:24','1392638184','1392638192'),(41,0,NULL,'',2,0,NULL,0,17,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-17 11:57:00','2014-02-17 11:57:00','1392638221','1392638229'),(43,0,NULL,'21',2,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-21 14:19:53','2014-02-21 14:26:24','1392992785','1392993000'),(44,0,NULL,'21',2,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-21 14:31:20','2014-02-21 14:31:20','1392993080','1392993083'),(45,0,NULL,'21',2,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-21 14:43:55','2014-02-21 14:43:55','1392994319','1392994352'),(46,0,NULL,'21',2,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-21 14:53:14','2014-02-21 14:53:14','1392994394','1392994399'),(47,0,NULL,'21',2,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-21 14:55:38','2014-02-21 14:55:38','1392994539','1392994546'),(48,0,NULL,'21',2,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-21 14:56:13','2014-02-21 14:56:13','1392994573','1392994576'),(49,0,NULL,'21',2,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-21 15:01:43','2014-02-21 15:01:43','1392994904','1392994907'),(50,0,NULL,'21',2,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-21 15:03:43','2014-02-21 15:03:43','1392995023','1392995028'),(51,0,NULL,'21',2,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-21 16:12:30','2014-02-21 16:12:30','1392999150','1392999154'),(52,0,NULL,'21',2,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-21 16:50:55','2014-02-21 16:50:55','1393001455','1393001459'),(53,0,'Ciao bau','',0,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-24 10:17:46','2014-02-24 10:17:46',NULL,NULL),(54,0,'asdasd','',0,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'asdasdsdaasd','2014-02-24 10:20:01','2014-02-24 10:20:01',NULL,NULL),(56,0,'ededededed','',0,0,NULL,0,18,1,'thomas.schiavello@gmail.com',NULL,'','2014-02-24 10:21:49','2014-02-24 10:21:49',NULL,NULL);
/*!40000 ALTER TABLE `sendings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `smtps`
--

DROP TABLE IF EXISTS `smtps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smtps` (
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` int(11) NOT NULL,
  `enctype` varchar(50) NOT NULL,
  `authtype` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `smpts_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `smtps`
--

LOCK TABLES `smtps` WRITE;
/*!40000 ALTER TABLE `smtps` DISABLE KEYS */;
INSERT INTO `smtps` VALUES ('thomas.schiavello@gmail.com','Thomas Schiavello','smtp.gmail.com',465,'tls','LOGIN','thomas.schiavello@gmail.com','DoshuShodan231191',1,1,'2013-04-18 10:57:38','2013-10-30 09:39:12'),('doshu_mes@yahoo.it','Doshu Yahoo','smtp.mail.yahoo.it',465,'none','LOGIN','doshu_mes','DoshuShodan231191',2,1,'2013-08-13 10:24:50','2014-02-03 22:17:29');
/*!40000 ALTER TABLE `smtps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tempattachments`
--

DROP TABLE IF EXISTS `tempattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tempattachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tempname` varchar(255) NOT NULL,
  `realname` varchar(255) NOT NULL,
  `validated` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tempattachments_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tempattachments`
--

LOCK TABLES `tempattachments` WRITE;
/*!40000 ALTER TABLE `tempattachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tempattachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unsubscriptions`
--

DROP TABLE IF EXISTS `unsubscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unsubscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `member_email` varchar(255) NOT NULL,
  `sending_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`,`member_id`,`sending_id`,`recipient_id`),
  KEY `unsubscriptions_member_id` (`member_id`),
  KEY `unsubscriptions_sending_id` (`sending_id`),
  KEY `unsubscriptions_recipient_id` (`recipient_id`),
  KEY `unsubscriptions_id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8
/*!50100 PARTITION BY HASH (sending_id)
PARTITIONS 1024 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unsubscriptions`
--

LOCK TABLES `unsubscriptions` WRITE;
/*!40000 ALTER TABLE `unsubscriptions` DISABLE KEYS */;
INSERT INTO `unsubscriptions` VALUES (3,4,'thomas.schiavello@gmail.com',52,184,'2014-02-21 16:51:09'),(4,4,'thomas.schiavello@gmail.com',52,184,'2014-02-24 11:30:55');
/*!40000 ALTER TABLE `unsubscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `timezone` varchar(50) NOT NULL DEFAULT 'Europe/Rome',
  `filemanager_quota` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Thomas','Schiavello','doshu','$2a$10$qFZS3b.2Nq2xlAB4falYreXu8gxN0GMEfVFjaeVTzaw8Ph1r7kpQ6','Europe/Rome',NULL,1);
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

-- Dump completed on 2014-03-11 14:27:23
