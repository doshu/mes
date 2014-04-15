CREATE DATABASE  IF NOT EXISTS `mes` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `mes`;
-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (i686)
--
-- Host: 127.0.0.1    Database: mes
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.10.2

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attachments`
--

LOCK TABLES `attachments` WRITE;
/*!40000 ALTER TABLE `attachments` DISABLE KEYS */;
INSERT INTO `attachments` VALUES (1,'20131213_091444.jpg','c9d4f85e1ad871db447d35c081c685d8',16);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `links`
--

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailinglists`
--

LOCK TABLES `mailinglists` WRITE;
/*!40000 ALTER TABLE `mailinglists` DISABLE KEYS */;
INSERT INTO `mailinglists` VALUES (4,1,'lista22','','2013-03-25 15:03:34'),(5,1,'nuova','nuova','2013-03-26 16:16:22'),(6,0,'qqqq','qqqqq','2013-07-26 08:18:31'),(7,0,'aaaaa','aaaaaa','2013-07-26 08:19:10'),(8,0,'aaaaaaaa','aaaaaaaa','2013-07-26 08:19:38'),(9,1,'deded','ededed','2013-07-26 08:19:57'),(20,1,'lista per test invio','','2013-08-13 10:30:48'),(21,1,'lista test disiscrizione','','2014-02-21 13:44:40'),(22,1,'xilie','','2014-04-01 15:49:25');
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
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8
/*!50100 PARTITION BY HASH (mailinglist_id)
PARTITIONS 1024 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailinglists_members`
--

LOCK TABLES `mailinglists_members` WRITE;
/*!40000 ALTER TABLE `mailinglists_members` DISABLE KEYS */;
INSERT INTO `mailinglists_members` VALUES (7,5,4),(8,6,4),(9,7,4),(10,8,4),(11,9,4),(12,10,4),(13,11,4),(14,12,4),(15,13,4),(20,15,4),(23,18,4),(28,23,4),(39,37,4),(46,42,4),(50,46,4),(16,14,5),(18,16,5),(19,17,5),(21,15,5),(22,18,5),(24,19,5),(25,20,5),(26,21,5),(27,23,5),(29,26,5),(30,27,5),(31,28,5),(32,29,5),(33,30,5),(34,31,5),(35,32,5),(36,33,5),(37,34,5),(38,35,5),(41,38,5),(52,11,5),(58,61,5),(59,62,5),(42,38,9),(47,43,9),(67,4,20),(64,67,21),(66,4,21),(65,68,22);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailinglists_sendings`
--

LOCK TABLES `mailinglists_sendings` WRITE;
/*!40000 ALTER TABLE `mailinglists_sendings` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailinglists_unsubscriptions`
--

LOCK TABLES `mailinglists_unsubscriptions` WRITE;
/*!40000 ALTER TABLE `mailinglists_unsubscriptions` DISABLE KEYS */;
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
  `html_spam_point` int(11) DEFAULT NULL,
  `text_spam_point` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mails_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mails`
--

LOCK TABLES `mails` WRITE;
/*!40000 ALTER TABLE `mails` DISABLE KEYS */;
INSERT INTO `mails` VALUES (16,1,'qweqe','','qweqwe','<p>prova invio email</p>\r\n','','2013-08-29 13:30:53','2014-03-28 14:11:10',NULL,NULL),(17,1,'email prova variabili','','Prova variabili','<p>Gentile {{name}} {{surname}} ,</p>\r\n\r\n<p>Ciao</p>\r\n','Gentile {{name}} {{surname}},\r\n\r\nCiao','2014-02-17 11:39:43','2014-02-17 11:39:43',NULL,NULL),(18,1,'Prova disiscrizione','Prova disiscrizione','Prova disiscrizione','<p>Per disiscriverti clicca <a href=\"{{unsubscribe()}}\">QUI</a></p>\r\n\r\n<p><img alt=\"\" src=\"http://www.powamail.tk/mes/webroot/file_manager/files/1/944250_596049927080238_41769568_n_2.png\" style=\"height:543px; width:498px\" /></p>\r\n\r\n<p>&nbsp;</p>\r\n','','2014-02-21 13:38:51','2014-04-08 12:41:24',0,0),(19,1,'prova newsletter','','prova newsletter','<table align=\"center\" bgcolor=\"#998D80\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n			<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"620\">\r\n				<tbody>\r\n					<tr>\r\n						<td height=\"30\">&nbsp;</td>\r\n					</tr>\r\n					<tr>\r\n						<td height=\"86\" width=\"620\">\r\n						<table bgcolor=\"#A6C880\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"left\" valign=\"top\" width=\"38\">&nbsp;</td>\r\n									<td bg-color=\"#488EB0\" height=\"86\"><img alt=\"\" src=\"http://127.0.0.1/mes/webroot/file_manager/files/1/msnms_9.ico\" style=\"width: 16px; height: 16px;\" /></td>\r\n									<td align=\"right\" valign=\"middle\">11/03/2014</td>\r\n									<td align=\"right\" valign=\"top\" width=\"38\">&nbsp;</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align=\"center\" valign=\"top\" width=\"620\">\r\n						<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"left\" valign=\"top\" width=\"8\">&nbsp;</td>\r\n									<td align=\"left\" valign=\"top\">\r\n									<table bgcolor=\"#F5F8EF\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td height=\"30\" valign=\"top\" width=\"30\">&nbsp;</td>\r\n												<td valign=\"top\">&nbsp;</td>\r\n												<td height=\"30\" valign=\"top\" width=\"30\">&nbsp;</td>\r\n											</tr>\r\n											<tr>\r\n												<td valign=\"top\">&nbsp;</td>\r\n												<td valign=\"top\">\r\n												<p><font color=\"#574F44\" face=\"Arial, Helvetica, sans-serif\" size=\"4\">Gentile cliente,</font></p>\r\n\r\n												<p><font color=\"#574F44\" face=\"Arial, Helvetica, sans-serif\" size=\"2\">lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at dui metus. Nam turpis augue, suscipit at commodo id, eleifend at est. Sed sapien orci, gravida vel ultricies nec, mollis quis est. Donec sollicitudin bibendum accumsan.<br />\r\n												<br />\r\n												Distinti saluti<br />\r\n												<em>La Direzione</em></font></p>\r\n												</td>\r\n												<td valign=\"top\">&nbsp;</td>\r\n											</tr>\r\n											<tr>\r\n												<td height=\"30\" valign=\"top\" width=\"30\">&nbsp;</td>\r\n												<td valign=\"top\">&nbsp;</td>\r\n												<td height=\"30\" valign=\"top\" width=\"30\">&nbsp;</td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n									</td>\r\n									<td align=\"left\" valign=\"top\" width=\"8\">&nbsp;</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td width=\"620\">\r\n						<table bgcolor=\"#A6C880\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"left\" valign=\"top\" width=\"38\">&nbsp;</td>\r\n									<td align=\"left\" height=\"40\" valign=\"middle\"><font face=\"Georgia, Times New Roman, Times, serif\" size=\"4\"><em>Scopri l&#39;offerta tipo A</em></font></td>\r\n									<td align=\"right\" valign=\"middle\"><font face=\"Arial, Helvetica, sans-serif\" size=\"3\"><strong>&euro; 189,90</strong></font></td>\r\n									<td align=\"right\" valign=\"top\" width=\"38\">&nbsp;</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"620\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"left\" valign=\"top\" width=\"8\">&nbsp;</td>\r\n									<td>\r\n									<table bgcolor=\"#F5F8EF\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td valign=\"top\">&nbsp;</td>\r\n												<td height=\"10\" valign=\"top\">&nbsp;</td>\r\n												<td valign=\"top\">&nbsp;</td>\r\n											</tr>\r\n											<tr>\r\n												<td valign=\"top\" width=\"30\">&nbsp;</td>\r\n												<td valign=\"top\">\r\n												<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n													<tbody>\r\n														<tr>\r\n															<td align=\"left\" valign=\"top\" width=\"230\"><img height=\"153\" src=\"immagini/img_01.jpg\" width=\"230\" /></td>\r\n															<td width=\"15\">&nbsp;</td>\r\n															<td valign=\"top\">\r\n															<p><font color=\"#617C3F\" face=\"Arial, Helvetica, sans-serif\" size=\"4\">OFFERTA TIPO A</font></p>\r\n\r\n															<p><font color=\"#574F44\" face=\"Georgia, Times New Roman, Times, serif\" size=\"2\">l</font><font color=\"#574F44\" face=\"Georgia, Times New Roman, Times, serif\" size=\"2\">orem ipsum dolor sit amet, consectetur adipiscing elit. Donec at dui metus. Nam turpis augue, suscipit at commodo id, eleifend at est. Sed sapien orci, gravida vel ultricies nec, mollis quis est.</font></p>\r\n\r\n															<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n																<tbody>\r\n																	<tr>\r\n																		<td valign=\"middle\"><a href=\"#\" style=\"text-decoration: none;\"><font color=\"#574E44\" face=\"Arial, Helvetica, sans-serif\" size=\"2\"><strong><font color=\"#574E44\">Dettagli</font></strong></font></a></td>\r\n																		<td valign=\"middle\" width=\"10\">&nbsp;</td>\r\n																		<td valign=\"middle\">&nbsp;</td>\r\n																	</tr>\r\n																</tbody>\r\n															</table>\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n												</td>\r\n												<td valign=\"top\" width=\"30\">&nbsp;</td>\r\n											</tr>\r\n											<tr>\r\n												<td valign=\"top\">&nbsp;</td>\r\n												<td height=\"30\" valign=\"top\">&nbsp;</td>\r\n												<td valign=\"top\">&nbsp;</td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n									</td>\r\n									<td align=\"left\" valign=\"top\" width=\"8\">&nbsp;</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n\r\n						<table bgcolor=\"#A6C880\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"left\" valign=\"top\" width=\"38\">&nbsp;</td>\r\n									<td align=\"left\" height=\"40\" valign=\"middle\"><font face=\"Georgia, Times New Roman, Times, serif\" size=\"4\"><em>Scopri l&#39;offerta tipo B</em></font></td>\r\n									<td align=\"right\" valign=\"middle\"><font face=\"Arial, Helvetica, sans-serif\" size=\"3\"><strong>&euro; 209,90</strong></font></td>\r\n									<td align=\"right\" valign=\"top\" width=\"38\">&nbsp;</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"620\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"left\" valign=\"top\" width=\"8\">&nbsp;</td>\r\n									<td>\r\n									<table bgcolor=\"#F5F8EF\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td valign=\"top\">&nbsp;</td>\r\n												<td height=\"10\" valign=\"top\">&nbsp;</td>\r\n												<td valign=\"top\">&nbsp;</td>\r\n											</tr>\r\n											<tr>\r\n												<td valign=\"top\" width=\"30\">&nbsp;</td>\r\n												<td valign=\"top\">\r\n												<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n													<tbody>\r\n														<tr>\r\n															<td valign=\"top\">\r\n															<p><font color=\"#617C3F\" face=\"Arial, Helvetica, sans-serif\" size=\"4\">OFFERTA TIPO B </font></p>\r\n\r\n															<p><font color=\"#574F44\" face=\"Georgia, Times New Roman, Times, serif\" size=\"2\">l</font><font color=\"#574F44\" face=\"Georgia, Times New Roman, Times, serif\" size=\"2\">orem ipsum dolor sit amet, consectetur adipiscing elit. Donec at dui metus. Nam turpis augue, suscipit at commodo id, eleifend at est. Sed sapien orci, gravida vel ultricies nec, mollis quis est.</font></p>\r\n\r\n															<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n																<tbody>\r\n																	<tr>\r\n																		<td valign=\"middle\"><a href=\"#\" style=\"text-decoration: none;\"><font color=\"#574E44\" face=\"Arial, Helvetica, sans-serif\" size=\"2\"><strong><font color=\"#574E44\">Scopri sul nostro sito</font></strong></font></a></td>\r\n																		<td valign=\"middle\" width=\"10\">&nbsp;</td>\r\n																		<td valign=\"middle\">&nbsp;</td>\r\n																	</tr>\r\n																</tbody>\r\n															</table>\r\n															</td>\r\n															<td width=\"15\">&nbsp;</td>\r\n															<td align=\"left\" valign=\"top\" width=\"230\"><img height=\"153\" src=\"immagini/img_02.jpg\" width=\"230\" /></td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n												</td>\r\n												<td valign=\"top\" width=\"30\">&nbsp;</td>\r\n											</tr>\r\n											<tr>\r\n												<td valign=\"top\">&nbsp;</td>\r\n												<td height=\"30\" valign=\"top\">&nbsp;</td>\r\n												<td valign=\"top\">&nbsp;</td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n									</td>\r\n									<td align=\"left\" valign=\"top\" width=\"8\">&nbsp;</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n\r\n						<table bgcolor=\"#A6C880\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"left\" valign=\"top\" width=\"38\">&nbsp;</td>\r\n									<td align=\"left\" height=\"40\" valign=\"middle\"><font face=\"Georgia, Times New Roman, Times, serif\" size=\"4\"><em>Scopri l&#39;offerta tipo C</em></font></td>\r\n									<td align=\"right\" valign=\"middle\"><font face=\"Arial, Helvetica, sans-serif\" size=\"3\"><strong>&euro; 169,90</strong></font></td>\r\n									<td align=\"right\" valign=\"top\" width=\"38\">&nbsp;</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"620\">\r\n							<tbody>\r\n								<tr>\r\n									<td align=\"left\" valign=\"top\" width=\"8\">&nbsp;</td>\r\n									<td>\r\n									<table bgcolor=\"#F5F8EF\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td valign=\"top\">&nbsp;</td>\r\n												<td height=\"10\" valign=\"top\">&nbsp;</td>\r\n												<td valign=\"top\">&nbsp;</td>\r\n											</tr>\r\n											<tr>\r\n												<td valign=\"top\" width=\"30\">&nbsp;</td>\r\n												<td valign=\"top\">\r\n												<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n													<tbody>\r\n														<tr>\r\n															<td align=\"left\" valign=\"top\" width=\"230\"><img height=\"153\" src=\"immagini/img_03.jpg\" width=\"230\" /></td>\r\n															<td width=\"15\">&nbsp;</td>\r\n															<td valign=\"top\">\r\n															<p><font color=\"#617C3F\" face=\"Arial, Helvetica, sans-serif\" size=\"4\">OFFERTA TIPO C</font></p>\r\n\r\n															<p><font color=\"#574F44\" face=\"Georgia, Times New Roman, Times, serif\" size=\"2\">l</font><font color=\"#574F44\" face=\"Georgia, Times New Roman, Times, serif\" size=\"2\">orem ipsum dolor sit amet, consectetur adipiscing elit. Donec at dui metus. Nam turpis augue, suscipit at commodo id, eleifend at est. Sed sapien orci, gravida vel ultricies nec, mollis quis est.</font></p>\r\n\r\n															<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n																<tbody>\r\n																	<tr>\r\n																		<td valign=\"middle\"><a href=\"#\" style=\"text-decoration: none;\"><font color=\"#574E44\" face=\"Arial, Helvetica, sans-serif\" size=\"2\"><strong><font color=\"#574E44\">Prenota subito!</font></strong></font></a></td>\r\n																		<td valign=\"middle\" width=\"10\">&nbsp;</td>\r\n																		<td valign=\"middle\">&nbsp;</td>\r\n																	</tr>\r\n																</tbody>\r\n															</table>\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n												</td>\r\n												<td valign=\"top\" width=\"30\">&nbsp;</td>\r\n											</tr>\r\n											<tr>\r\n												<td valign=\"top\">&nbsp;</td>\r\n												<td height=\"30\" valign=\"top\">&nbsp;</td>\r\n												<td valign=\"top\">&nbsp;</td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n									</td>\r\n									<td align=\"left\" valign=\"top\" width=\"8\">&nbsp;</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n\r\n			<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"620\">\r\n				<tbody>\r\n					<tr>\r\n						<td align=\"center\" bgcolor=\"#867A6C\" height=\"40\" valign=\"middle\"><font color=\"#ffffff\" face=\"Georgia, Times New Roman, Times, serif\" size=\"2\"><em>Indirizzo | Telefono | E-mail | <a href=\"#\" style=\"text-decoration: none;\"><font color=\"#ffffff\" face=\"Georgia, Times New Roman, Times, serif\" size=\"2\"><font color=\"#fffffff\">Sito web</font></font></a></em></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td height=\"30\">&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n','CIALIS','2014-03-12 16:32:56','2014-03-31 14:30:59',0,64),(21,1,'zc','','zx','','','2014-04-10 16:01:11','2014-04-10 16:01:11',0,0),(22,1,'fff','','ffff','','','2014-04-10 16:02:40','2014-04-10 16:02:40',0,0),(24,1,'asd','','vfvf','','','2014-04-11 09:54:52','2014-04-11 10:07:31',0,0);
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `memberfieldvalues_member_id_memberfield_id` (`member_id`,`memberfield_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `memberfieldvalues`
--

LOCK TABLES `memberfieldvalues` WRITE;
/*!40000 ALTER TABLE `memberfieldvalues` DISABLE KEYS */;
INSERT INTO `memberfieldvalues` VALUES (9,NULL,'',NULL,NULL,4,2),(10,NULL,NULL,NULL,'1991-11-23',4,3),(15,'gnoma2',NULL,NULL,NULL,60,4),(16,'goma',NULL,NULL,NULL,60,5),(17,'thomas',NULL,NULL,NULL,4,4),(18,'schiavello',NULL,NULL,NULL,4,5),(19,NULL,'',NULL,NULL,60,2),(20,NULL,NULL,NULL,NULL,60,3),(21,NULL,'',NULL,NULL,45,2),(22,NULL,NULL,NULL,NULL,45,3),(23,'',NULL,NULL,NULL,45,4),(24,'',NULL,NULL,NULL,45,5),(25,NULL,'',NULL,NULL,44,2),(26,NULL,NULL,NULL,NULL,44,3),(27,'',NULL,NULL,NULL,44,4),(28,'',NULL,NULL,NULL,44,5),(29,NULL,NULL,1,NULL,60,6),(30,NULL,NULL,0,NULL,4,6);
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
  `valid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `members_user_id` (`user_id`),
  KEY `members_secret` (`secret`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES (4,'thomas.schiavello@gmail.com','$2a$10$Lf4Tt952PArx52V7bq3Guu8vRz8w8tafSxOCmeg/TPVKhOaPhOcLC','2013-03-25 16:18:02','2014-04-15 10:00:07',1,1),(5,'lo@lol.it','$2a$10$eZPyIX6LnfjhDEUJazMAUO6ajH7I5Ig31CNvyg4xbSgdslqMEv.Gu','2013-03-26 11:05:44','2013-03-26 11:05:44',1,NULL),(6,'lo@lol.it','$2a$10$sdfRkjV1.d6CSugeLvsxXOQ3nFUWMvNn.XA5UDT.5qEwLeOGki.7G','2013-03-26 12:10:00','2013-03-26 12:10:00',1,NULL),(7,'lo@lol2.it','$2a$10$07lYJbyfZNIWe4NpH9DZXeB.IRtzqVAlSNrlJu8Q.r8OOi6MfCGCq','2013-03-26 12:10:33','2013-03-26 12:10:33',1,NULL),(8,'lo@lol2.it','$2a$10$jQWmmtmnTzNgeLhZsOxx6OcxeH8rnHJ/WNtf5cchoahaTXokA/zJy','2013-03-26 12:10:33','2013-03-26 12:10:33',1,NULL),(9,'lo@lol.it','$2a$10$UMbY0n859xS6mck5ePHYQOj.LdhVua1mYGEa38pSpOI46C3ZD8qIe','2013-03-26 14:48:26','2013-03-26 14:48:26',1,NULL),(10,'aaaa@aaa.it','$2a$10$hDRHYFeAoiwwujs3mBHUye1cEpHLtOtpjJHQWnf3/NDhkaCcGSVIa','2013-03-26 15:05:20','2013-03-26 15:05:20',1,NULL),(11,'thomas.schiavello@two-b.it','$2a$10$63gk15KcVStlPLfy108BnOpyxtGMEchZONET5hvT1XWDfZyyndPJS','2013-03-26 15:07:15','2013-08-13 10:51:44',1,1),(12,'emailasdasdasd@example.com','$2a$10$cvLtlGJKEijq.bJs4pyYI.WZFmTrKckD9ozCewunKdWvpAoGr8OYK','2013-03-26 15:08:14','2013-04-08 15:10:48',1,NULL),(13,'asd@asd.it','$2a$10$3d2Rl2x1byOhUCuPUxsXje3352kzpKxNl790H6HGHXCLgjPd2rR0e','2013-03-26 15:08:43','2013-03-26 15:08:43',1,NULL),(14,'sbiri@sburu.it','$2a$10$A.BSVeHYthXlTI0IEN6nVusQ9Hq.TdETzGWM2166CcB1qoIOLkSc6','2013-03-26 16:16:57','2013-03-26 16:16:57',1,NULL),(15,'email1$@example.com','$2a$10$K04hbtUEL/k8RnjHbcNi1e.Zar7r499eF.Ft5PuV50wDAlI01KXvG','2013-04-08 12:37:10','2013-04-08 15:32:36',1,NULL),(16,'Email2@asds.com','$2a$10$nw54nWZAcB8dQE4zudoDmurvopvscDtMoXEIgbkyZqDK.uAOTFWkG','2013-04-08 12:37:10','2013-04-08 12:37:10',1,NULL),(17,'email3@example.com','$2a$10$Xi62R2HjK5nk2oG2MgtVj.lAN8NvKko9CZ7DX9Eh9jAq2bmW9iaOS','2013-04-08 12:37:10','2013-04-08 12:37:10',1,NULL),(18,'ciccio.pasticcio@cicciolandia.com','$2a$10$tgv1F2as1P81U8HAV.DgWOnTx6MeJ63U1Dl3vpVc2B8fTE575Fqn.','2013-04-08 16:55:31','2013-04-08 16:55:31',1,NULL),(19,'add@dee.it','$2a$10$UoKNLVROTCy8zIEubwpYseyGzI0D9/MtxB3qi9HicOoJanZmCGbMC','2013-04-08 17:24:08','2013-04-08 17:24:08',1,NULL),(20,'Ssdsds.lool@gmail.it','$2a$10$rzIpLG7MqJE3WaEL0cuJ0.W.Mn1liTfUBufEchukeyzEb8Rhbfaay','2013-04-08 17:26:06','2013-04-08 17:26:06',1,NULL),(21,'Ssdsds.lool@gmaisdaaasl.it','$2a$10$6cu03Hl6Vt8pWIBhYrAdkup9rTzAnFiTP86iCGrdyXsdmFBhiSbVu','2013-04-08 17:32:20','2013-04-08 17:32:20',1,NULL),(22,'asd@asd2.it','$2a$10$XL430wprK2sJLvggekUUo.ZOrVZChvaWUEc4q.mOH4631zFVVSfWC','2013-04-19 11:39:40','2013-04-19 11:39:40',1,NULL),(23,'email2_keororo@example.com','$2a$10$dXzYEcpsjP1rzwRgK.UYLO3JLfmaqec.g2lGZZ4oglTWXOvkm4enm','2013-04-19 11:44:11','2013-04-19 11:44:11',1,NULL),(24,'email1_keororo@example.com','$2a$10$S7HM4atH.nipsBMLfEIh7eWUcpfiYUrZmaDPzuQI7KnoxUkQBhEpW','2013-04-19 11:45:22','2013-04-19 11:45:22',1,NULL),(25,'email3_keororo@example.com','$2a$10$sYCeoa1KTsUGylGeVgFVwe0OqA/pnbSOFAfyWF4a4VtwDdBpa6YcG','2013-04-19 11:45:22','2013-04-19 11:45:22',1,NULL),(26,'ddee@eded.it','$2a$10$rMObwHw8kEZ95awXiL5W5usXC892ZnvHSxwpAXJt8WHEGh3NBDHhq','2013-07-25 14:19:00','2013-07-25 14:19:00',1,NULL),(27,'asassds@lol.fff','$2a$10$jEkNKruEJIT3P7NfNwv1SeKcmm4FDheXfMRA2TNiM1kAa7DdRy.lW','2013-07-25 14:21:41','2013-07-25 14:21:41',1,NULL),(28,'c@c.com','$2a$10$psFQLtIJZO11TiOov0o1uuuTlW0rRYZOUEAEJ1TQuvUtD02Z9gc6m','2013-07-25 14:34:24','2013-07-25 14:34:24',1,0),(29,'c@c.cog','$2a$10$qLTYpc3ZHWkoDn9XgxPcFOG0zWEiGNf1J2rvudPXQ0lZKErsNdZYa','2013-07-25 14:35:29','2013-07-25 14:35:29',1,0),(30,'c@c.cox','$2a$10$k0r1jRIGAHvdcBKQW6JX7udVZGXQ49pZjUjp2tYaXSM8Uehf5khAa','2013-07-25 14:35:51','2013-07-25 14:35:51',1,0),(31,'c@c.coz','$2a$10$ObyMoyA7XTiILy6xLuI86eUgrF4ApICxxscsj4.vMvojRYRGlEz46','2013-07-25 14:36:15','2013-07-25 14:36:15',1,0),(32,'a@a.asd','$2a$10$apTnrLIU.HU3P0UV3ij4Jeb5K.dxMwMy4xhwWvKlTbsRwaahuIINC','2013-07-25 14:38:03','2013-07-25 14:38:03',1,0),(33,'ttt@ttt.com','$2a$10$EQNHkirEQP7JWoLLF8c3w.LSFiSiaVZuwuEu8Ya6FVqPcMqdeRR/a','2013-07-25 15:16:58','2013-07-25 15:16:58',1,2),(34,'ttt@ttt.cog','$2a$10$if1dwwucFU7p/nAiz7QgKuEfmRoAtzp0ZK/swXEhOkZtjf5sT/ebi','2013-07-25 15:17:20','2013-07-25 15:17:20',1,0),(35,'ttt@ttt.con','$2a$10$aP.mvwmyPrQTvIDs36Ce7.Kjuf5Rxi67CDzPXZjFAY9GI1JzjhBLG','2013-07-25 15:18:32','2013-07-25 15:18:32',1,0),(36,'strimpel@strimpel.it','','2013-07-26 08:48:20','2013-07-26 08:48:20',1,NULL),(37,'cicci@c.it','','2013-07-26 08:54:25','2013-07-26 08:54:25',1,NULL),(38,'xxx@xxx.xxx','$2a$10$ATH6TmIjCITKKRB5uGjLQ.f1Chak7gV.EV0mx/paX/lsY9Yfqdbuy','2013-07-26 09:08:11','2013-07-26 09:19:13',1,0),(39,'ciao@bau.it','','2013-07-26 13:19:39','2013-07-26 13:19:39',1,NULL),(40,'mmm@mmm.it','','2013-07-26 13:53:05','2013-07-26 13:53:05',1,NULL),(41,'zxcz@xcxc.it','','2013-07-26 13:53:39','2013-07-26 13:53:39',1,NULL),(42,'ninna@nanna.it','','2013-07-30 12:47:19','2013-07-30 12:47:19',1,NULL),(43,'nonna@nnn.it','','2013-07-30 12:48:02','2013-07-30 12:48:02',1,NULL),(44,'cis@ccc.net','','2013-07-30 12:51:10','2014-02-21 10:58:20',1,NULL),(45,'xxx@xxx.xxz','','2013-07-30 12:51:54','2014-02-21 10:57:15',1,NULL),(46,'sbem@sbem.it','','2013-07-30 12:52:19','2013-08-27 13:33:05',1,NULL),(60,'ciccicci@cucucu.com','$2a$10$gqOzqqGrngznIfITmZ6CuO87hBaf0C4vTbvNFMjrDrf2Ph2N3goMe','2014-02-11 15:17:03','2014-03-06 16:09:43',1,2),(61,'culo@camicia.com','','2014-03-26 14:17:40','2014-03-26 14:17:40',1,NULL),(62,'culo@camicia2.com','$2a$10$jvrAF0QaNvuFO0eMviBlI.kfDkoJrdyD2A/AGh1/Y2DmgTOdXqmf.','2014-03-26 14:20:43','2014-03-26 14:20:43',1,NULL),(67,'23thomas@libero.it','$2a$10$D1OkwrurAspw1KQE75Ud8eG80UO4INtIhswtkX/lCmowVWS2KVwnq','2014-04-01 10:31:20','2014-04-01 10:31:20',1,NULL),(68,'xiliebass@gmail.com','$2a$10$XuZbemIFTgOr5diIIQB4fuy8ILmfsadpqWQNzz.zIlLOSQLNIbhvq','2014-04-01 15:50:06','2014-04-01 15:50:06',1,NULL);
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
  `user_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`sending_id`),
  KEY `recipients_sending_id` (`sending_id`),
  KEY `recipients_member_secret` (`member_secret`),
  KEY `recipients_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*!50100 PARTITION BY LINEAR HASH (sending_id)
PARTITIONS 1024 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipients`
--

LOCK TABLES `recipients` WRITE;
/*!40000 ALTER TABLE `recipients` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sendings`
--

LOCK TABLES `sendings` WRITE;
/*!40000 ALTER TABLE `sendings` DISABLE KEYS */;
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
INSERT INTO `smtps` VALUES ('thomas.schiavello@gmail.com','Thomas Schiavello','smtp.gmail.com',465,'tls','LOGIN','thomas.schiavello@gmail.com','DoshuShodan231191',1,1,'2013-04-18 10:57:38','2014-03-18 10:21:26'),('doshu_mes@yahoo.it','Doshu Yahoo','smtp.mail.yahoo.it',465,'none','LOGIN','doshu_mes','DoshuShodan231191',2,1,'2013-08-13 10:24:50','2014-02-03 22:17:29');
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
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `html` longtext,
  `text` longtext,
  `user_id` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `templates_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` VALUES (3,'prova','asdasdsa','<p>ciao {{name}} {{surname}}</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><img alt=\"\" src=\"http://127.0.0.1/mes/webroot/file_manager/files/1/944250_596049927080238_41769568_n_2.png\" style=\"width: 498px; height: 543px;\" /></p>\r\n','solo testo','1','2014-03-20 10:29:36','2014-03-20 15:24:22');
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*!50100 PARTITION BY HASH (sending_id)
PARTITIONS 1024 */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unsubscriptions`
--

LOCK TABLES `unsubscriptions` WRITE;
/*!40000 ALTER TABLE `unsubscriptions` DISABLE KEYS */;
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

-- Dump completed on 2014-04-15 12:25:49
