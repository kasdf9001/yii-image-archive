-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (armv7l)
--
-- Host: localhost    Database: slhk
-- ------------------------------------------------------
-- Server version	5.5.44-0+deb7u1

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
-- Table structure for table `diat`
--

DROP TABLE IF EXISTS `diat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `diat` (
  `cd` int(11) NOT NULL,
  `id` text COLLATE utf8_unicode_ci NOT NULL,
  `pvm` text COLLATE utf8_unicode_ci NOT NULL,
  `valokuvaaja` text COLLATE utf8_unicode_ci NOT NULL,
  `maalaus` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `piirustus` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `ulkokuva` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `sisakuva` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `ilmakuva` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `historiallinen` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `tyomaa` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `esittely` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `ihmisia` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `linnoituslaitteet` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `kuvateksti` text COLLATE utf8_unicode_ci NOT NULL,
  `diateksti` text COLLATE utf8_unicode_ci NOT NULL,
  `kartta` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `tiedostotyyppi` text COLLATE utf8_unicode_ci NOT NULL,
  `aikavarma` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `julkaisuvapaa` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `imageid` int(11) NOT NULL AUTO_INCREMENT,
  `valokuva` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`imageid`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kohteet`
--

DROP TABLE IF EXISTS `kohteet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kohteet` (
  `cdno` int(11) NOT NULL,
  `idno` int(11) NOT NULL,
  `saari` text COLLATE utf8_unicode_ci NOT NULL,
  `rakennus` text COLLATE utf8_unicode_ci NOT NULL,
  `fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-30  9:48:52
