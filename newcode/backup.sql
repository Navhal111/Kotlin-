-- MySQL dump 10.13  Distrib 5.7.18, for Linux (x86_64)
--
-- Host: localhost    Database: basepro
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.16.04.1

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
-- Table structure for table `admin_auth`
--

DROP TABLE IF EXISTS `admin_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL,
  `matrimony_profile` int(3) DEFAULT NULL,
  `blood_doner` int(3) DEFAULT NULL,
  `business_profile` int(3) DEFAULT NULL,
  `noklari_profile` int(3) DEFAULT NULL,
  `seva_orgnization` int(3) DEFAULT NULL,
  `advertisment` int(3) DEFAULT NULL,
  `corporater` int(3) DEFAULT NULL,
  `survay_orgnization` int(3) DEFAULT NULL,
  `aboutus` int(3) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_auth`
--

LOCK TABLES `admin_auth` WRITE;
/*!40000 ALTER TABLE `admin_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_mn`
--

DROP TABLE IF EXISTS `admin_mn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_mn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `token` text,
  `token_exp` text,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_mn`
--

LOCK TABLES `admin_mn` WRITE;
/*!40000 ALTER TABLE `admin_mn` DISABLE KEYS */;
INSERT INTO `admin_mn` VALUES (1,'admin','admin','68f3f2f772ca84ac4b45593132333435','2017-05-31 16:11:16',NULL);
/*!40000 ALTER TABLE `admin_mn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth`
--

DROP TABLE IF EXISTS `auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `token` text,
  `exp_time` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth`
--

LOCK TABLES `auth` WRITE;
/*!40000 ALTER TABLE `auth` DISABLE KEYS */;
INSERT INTO `auth` VALUES (1,1,'50619435b4a196834b45593132333435','2017-05-31 11:58:09'),(2,1,'542a37520f117c4c4b45593132333435','2017-05-31 11:58:10'),(3,1,'c73a14429a4018a24b45593132333435','2017-05-31 11:58:11'),(4,1,'ff8a37d198e9c3494b45593132333435','2017-05-31 11:58:42'),(5,1,'d7adac38d264eeed4b45593132333435','2017-05-31 12:21:02');
/*!40000 ALTER TABLE `auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `city`
--

DROP TABLE IF EXISTS `city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `city`
--

LOCK TABLES `city` WRITE;
/*!40000 ALTER TABLE `city` DISABLE KEYS */;
/*!40000 ALTER TABLE `city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distric`
--

DROP TABLE IF EXISTS `distric`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `distric` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `distric` varchar(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distric`
--

LOCK TABLES `distric` WRITE;
/*!40000 ALTER TABLE `distric` DISABLE KEYS */;
/*!40000 ALTER TABLE `distric` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `education_met`
--

DROP TABLE IF EXISTS `education_met`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `education_met` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `education` varchar(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `education_met`
--

LOCK TABLES `education_met` WRITE;
/*!40000 ALTER TABLE `education_met` DISABLE KEYS */;
INSERT INTO `education_met` VALUES (1,'BE',1);
/*!40000 ALTER TABLE `education_met` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `family_met`
--

DROP TABLE IF EXISTS `family_met`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `family_met` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) DEFAULT NULL,
  `father_name` varchar(20) DEFAULT NULL,
  `father_no` int(10) DEFAULT NULL,
  `mother_name` varchar(20) DEFAULT NULL,
  `mother_no` int(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `family_met`
--

LOCK TABLES `family_met` WRITE;
/*!40000 ALTER TABLE `family_met` DISABLE KEYS */;
/*!40000 ALTER TABLE `family_met` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `occupation_met`
--

DROP TABLE IF EXISTS `occupation_met`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `occupation_met` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `occupation` varchar(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `occupation_met`
--

LOCK TABLES `occupation_met` WRITE;
/*!40000 ALTER TABLE `occupation_met` DISABLE KEYS */;
/*!40000 ALTER TABLE `occupation_met` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_met`
--

DROP TABLE IF EXISTS `profile_met`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_met` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gen_id` int(11) DEFAULT NULL,
  `relation` varchar(20) DEFAULT NULL,
  `profile_name` varchar(20) DEFAULT NULL,
  `surname` varchar(20) DEFAULT NULL,
  `subcast` varchar(20) DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `full_name` varchar(50) DEFAULT NULL,
  `living` varchar(50) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `birth_time` time DEFAULT NULL,
  `hobby` varchar(50) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `about` varchar(50) DEFAULT NULL,
  `education` varchar(20) DEFAULT NULL,
  `occupation` varchar(20) DEFAULT NULL,
  `income_status` varchar(10) DEFAULT NULL,
  `income` int(11) DEFAULT NULL,
  `total_income` int(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone_num` int(10) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_met`
--

LOCK TABLES `profile_met` WRITE;
/*!40000 ALTER TABLE `profile_met` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile_met` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_met`
--

DROP TABLE IF EXISTS `request_met`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_met` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_met`
--

LOCK TABLES `request_met` WRITE;
/*!40000 ALTER TABLE `request_met` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_met` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sibling_met`
--

DROP TABLE IF EXISTS `sibling_met`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sibling_met` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `married_status` varchar(10) DEFAULT NULL,
  `phone_number` int(10) DEFAULT NULL,
  `living` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sibling_met`
--

LOCK TABLES `sibling_met` WRITE;
/*!40000 ALTER TABLE `sibling_met` DISABLE KEYS */;
/*!40000 ALTER TABLE `sibling_met` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `state`
--

DROP TABLE IF EXISTS `state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `state`
--

LOCK TABLES `state` WRITE;
/*!40000 ALTER TABLE `state` DISABLE KEYS */;
/*!40000 ALTER TABLE `state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_admin`
--

DROP TABLE IF EXISTS `sub_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_admin`
--

LOCK TABLES `sub_admin` WRITE;
/*!40000 ALTER TABLE `sub_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `sub_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `email` text,
  `password` text,
  `phone_num` varchar(10) DEFAULT NULL,
  `birth_date` text,
  `gender` varchar(7) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `distric` varchar(20) DEFAULT NULL,
  `sub_distric` varchar(20) DEFAULT NULL,
  `pincode` int(11) DEFAULT NULL,
  `otp_status` tinyint(4) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` text,
  `token_exp` text,
  `payment` int(11) DEFAULT NULL,
  `end_date` varchar(30) DEFAULT NULL,
  `user_block` tinyint(4) DEFAULT '1',
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'ritesh','navhal','milan@gmail.com','12345','1234567890','11-12-1995','male','india','guj','surat','surat','surat',395006,0,'2017-05-31 11:50:34',NULL,NULL,NULL,NULL,1,1),(2,'ritesh','navhal','light@gmail.com','12345','9737846981','11-12-1995','male','india','guj','surat','surat','surat',395006,0,'2017-05-31 11:55:18',NULL,NULL,NULL,NULL,1,1),(3,'milan','ritesh','lightritesh@gmail.com','12345','9737846981','11-12-1995','male','india','guj','surat','surat','surat',395006,0,'2017-05-31 11:56:22',NULL,NULL,NULL,NULL,0,1);
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

-- Dump completed on 2017-05-31 15:25:47
