-- MySQL dump 10.15  Distrib 10.0.34-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: libadminenhanced
-- ------------------------------------------------------
-- Server version	10.0.34-MariaDB-0ubuntu0.16.04.1

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

USE `libadminenhanced`;

--
-- Table structure for table `admininstitution`
--

DROP TABLE IF EXISTS `admininstitution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admininstitution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,

  /* hier einen Schlüssel legen??*/
  `idcode` varchar(50)  DEFAULT NULL,

  `name` varchar(200)  DEFAULT NULL,
  `id_postadresse` INT(11)  DEFAULT NULL,
  /* email: entspricht institutioneller email adresse */
  `email` varchar(100) DEFAULT NULL,
  `id_kontakt` INT(11)  DEFAULT NULL,
  /*korrespondenzsprache: [deutsch|französisch|italienisch] */
  `korrespondenzsprache` enum('g', 'f', 'i')  DEFAULT NULL,
  `bfscode` varchar(50)  DEFAULT NULL,
  `ipadresse` VARCHAR(30) DEFAULT NULL,
  /* zusage_beitrag: [ja|nein] */
  `zusage_beitrag` enum('ja', 'nein', 'offen')  DEFAULT NULL,
  `id_kostenbeitrag` int(11)  DEFAULT NULL,
  `bemerkung_kostenbeitrag` text  DEFAULT NULL,
  `kostenbeitrag_basiert_auf` enum('summe_verbundbibliotheken', 'bfs_zahlen', 'anzahl_aufnahmen', 'freiwilliger_beitrag', 'recherchierte_bfs_zahlen') DEFAULT NULL,
  `adresse_rechnung_gleich_post` enum('ja', 'nein')  DEFAULT 'ja',
  `id_rechnungsadresse` int(11)  DEFAULT NULL,
  `id_kontakt_rechnung` int(11)  DEFAULT NULL,
  /* mwst: [ja|nein] */
  `mwst` enum('ja', 'nein') DEFAULT NULL,
  /*grund_mwst_frei: [bfk|gemeinwesen] */
  `grund_mwst_frei` enum('bfk', 'gemeinwesen')  DEFAULT NULL,
  /* e_rechnung: [ja|nein] */
  `e_rechnung` enum('ja', 'nein')  DEFAULT NULL,
  `bemerkung_rechnung` text  DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_kontakt` (`id_kontakt`),
  KEY `fk_postadresse` (`id_postadresse`),
  KEY `fk_adresse_rechnung` (`id_rechnungsadresse`),
  KEY `fk_kontakt_rechnung` (`id_kontakt_rechnung`),
  KEY `fk_kostenbeitrag` (`id_kostenbeitrag`),


  CONSTRAINT `fk_kontakt1` FOREIGN KEY (`id_kontakt`) REFERENCES `kontakt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_adresse1` FOREIGN KEY (`id_postadresse`) REFERENCES `adresse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_adresse_rechnung1` FOREIGN KEY (`id_rechnungsadresse`) REFERENCES `adresse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_kontakt_rechnung1` FOREIGN KEY (`id_kontakt_rechnung`) REFERENCES `kontakt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_kostenbeitrag1` FOREIGN KEY (`id_kostenbeitrag`) REFERENCES `kostenbeitrag` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION

) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `adresse`
--

DROP TABLE IF EXISTS `adresse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adresse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `strasse` varchar(150) DEFAULT NULL,
  `nummer` varchar(20) DEFAULT NULL,
  `zusatz` text,
  `plz` mediumint(9) DEFAULT NULL,
  `ort` varchar(150) DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  `canton` varchar(10) DEFAULT NULL,
  `name_organisation_rechnung` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1342 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `label_de` varchar(100) DEFAULT NULL,
  `label_fr` varchar(100) DEFAULT NULL,
  `label_it` varchar(100) DEFAULT NULL,
  `label_en` varchar(100) DEFAULT NULL,
  `notes` text,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `institution`
--

DROP TABLE IF EXISTS `institution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `institution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bib_code` varchar(50) DEFAULT NULL,
  `sys_code` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `label_de` varchar(100) DEFAULT NULL,
  `label_fr` varchar(100) DEFAULT NULL,
  `label_it` varchar(100) DEFAULT NULL,
  `label_en` varchar(100) DEFAULT NULL,
  `name_de` varchar(200) DEFAULT NULL,
  `name_fr` varchar(200) DEFAULT NULL,
  `name_it` varchar(200) DEFAULT NULL,
  `name_en` varchar(200) DEFAULT NULL,
  `url_de` varchar(255) DEFAULT NULL,
  `url_fr` varchar(255) DEFAULT NULL,
  `url_it` varchar(255) DEFAULT NULL,
  `url_en` varchar(255) DEFAULT NULL,
  `url_web_en` varchar(255) DEFAULT NULL,
  `url_web_it` varchar(255) DEFAULT NULL,
  `url_web_fr` varchar(255) DEFAULT NULL,
  `url_web_de` varchar(255) DEFAULT NULL,
  `address` text,
  `adresszusatz` text,
  `zip` mediumint(9) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  `canton` varchar(10) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `skype` varchar(45) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `twitter` varchar(30) DEFAULT NULL,
  `coordinates` varchar(100) DEFAULT NULL,
  `isil` varchar(45) DEFAULT NULL,
  `notes` text,
  `notes_public_it` text,
  `notes_public_en` text,
  `notes_public_fr` text,
  `notes_public_de` text,
  `id_kontakt` int(11) DEFAULT NULL,
  /*korrespondenzsprache: [deutsch|französisch|italienisch] */
  `korrespondenzsprache` enum('g', 'f', 'i')  DEFAULT NULL,
  `bfscode` varchar(50) DEFAULT NULL,
  `worldcat_ja_nein` enum('ja', 'nein') DEFAULT NULL,
  `worldcat_symbol` varchar(30) DEFAULT NULL,
  `cbslibrarycode` varchar(50) DEFAULT NULL,
  `verrechnungbeitrag` enum('direkt', 'keine_verrechnung', 'ueber_institution', 'ueber_leitbibliothek') DEFAULT NULL,
  `zusage_beitrag` enum('ja', 'nein', 'offen')  DEFAULT NULL,
  `id_kostenbeitrag` int(11) DEFAULT NULL,
  `bemerkung_kostenbeitrag` text,
  `kostenbeitrag_basiert_auf` enum('bfs_zahlen', 'anzahl_aufnahmen', 'freiwilliger_beitrag', 'recherchierte_bfs_zahlen') DEFAULT NULL,
  `adresse_rechnung_gleich_post` enum('ja', 'nein')  DEFAULT 'ja',
  `id_rechnungsadresse` int(11) DEFAULT NULL,
  `id_postadresse` int(11) DEFAULT NULL,
  `id_kontakt_rechnung` int(11) DEFAULT NULL,
  `mwst` enum('ja', 'nein') DEFAULT NULL,
  `grund_mwst_frei` enum('bfk', 'gemeinwesen')  DEFAULT NULL,
  `e_rechnung` enum('ja', 'nein')  DEFAULT NULL,
  `bemerkung_rechnung` text,
  PRIMARY KEY (`id`),
  KEY `fk_kontakt` (`id_kontakt`),
  KEY `fk_kostenbeitrag` (`id_kostenbeitrag`),
  KEY `fk_rechnungsadresse` (`id_rechnungsadresse`),
  KEY `fk_postadresse` (`id_postadresse`),
  KEY `fk_rechnung_kontakt` (`id_kontakt_rechnung`),
  CONSTRAINT `fk_kontakt` FOREIGN KEY (`id_kontakt`) REFERENCES `kontakt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_kostenbeitrag` FOREIGN KEY (`id_kostenbeitrag`) REFERENCES `kostenbeitrag` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_postadresse` FOREIGN KEY (`id_postadresse`) REFERENCES `adresse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rechnung_kontakt` FOREIGN KEY (`id_kontakt_rechnung`) REFERENCES `kontakt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_rechnungsadresse` FOREIGN KEY (`id_rechnungsadresse`) REFERENCES `adresse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2812 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kontakt`
--

DROP TABLE IF EXISTS `kontakt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kontakt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `vorname` varchar(100) DEFAULT NULL,
  `anrede` enum('f', 'm') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kostenbeitrag`
--

DROP TABLE IF EXISTS `kostenbeitrag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kostenbeitrag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `j2018` decimal(7,2) DEFAULT NULL,
  `j2019` decimal(7,2) DEFAULT NULL,
  `j2020` decimal(7,2) DEFAULT NULL,
  `j2021` decimal(7,2) DEFAULT NULL,
  `j2022` decimal(7,2) DEFAULT NULL,
  `j2023` decimal(7,2) DEFAULT NULL,
  `j2024` decimal(7,2) DEFAULT NULL,
  `j2025` decimal(7,2) DEFAULT NULL,
  `j2026` decimal(7,2) DEFAULT NULL,
  `j2027` decimal(7,2) DEFAULT NULL,
  `j2028` decimal(7,2) DEFAULT NULL,
  `j2029` decimal(7,2) DEFAULT NULL,
  `j2030` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `id_view` int(11) DEFAULT NULL,
  `host` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mm_group_view`
--

DROP TABLE IF EXISTS `mm_group_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mm_group_view` (
  `id_group` int(11) NOT NULL,
  `id_view` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_view`,`id_group`),
  KEY `group` (`id_group`),
  KEY `view` (`id_view`),
  CONSTRAINT `fk_mm_group_view_group1` FOREIGN KEY (`id_group`) REFERENCES `group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mm_group_view_view1` FOREIGN KEY (`id_view`) REFERENCES `view` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mm_institution_group_view`
--

DROP TABLE IF EXISTS `mm_institution_group_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mm_institution_group_view` (
  `id_view` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `id_institution` int(11) NOT NULL,
  `is_favorite` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_group`,`id_institution`,`id_view`),
  KEY `fk_link_institution1` (`id_institution`),
  KEY `fk_link_view1` (`id_view`),
  CONSTRAINT `fk_link_institution1` FOREIGN KEY (`id_institution`) REFERENCES `institution` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_link_network1` FOREIGN KEY (`id_group`) REFERENCES `group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_link_view1` FOREIGN KEY (`id_view`) REFERENCES `view` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mn_institution_admininstitution`
--

DROP TABLE IF EXISTS `mn_institution_admininstitution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mn_institution_admininstitution` (
  `id_institution` int(11) NOT NULL,
  `id_admininstitution` int(11) NOT NULL,
  `relation_type` varchar(30) NOT NULL,
  PRIMARY KEY (`id_institution`,`id_admininstitution`),
  KEY `instititution` (`id_institution`),
  KEY `admininstitution` (`id_admininstitution`),
  CONSTRAINT `mn_inst_admin_admininstitution1` FOREIGN KEY (`id_admininstitution`) REFERENCES `admininstitution` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `mn_inst_admin_institution1` FOREIGN KEY (`id_institution`) REFERENCES `institution` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `view`
--

DROP TABLE IF EXISTS `view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `label` varchar(45) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-03 18:27:50
