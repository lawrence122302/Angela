-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: rbwebsite
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `admin_cred`
--

DROP TABLE IF EXISTS `admin_cred`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_cred` (
  `sr_no` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(150) NOT NULL,
  `admin_pass` varchar(150) NOT NULL,
  `is_super_admin` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_cred`
--

LOCK TABLES `admin_cred` WRITE;
/*!40000 ALTER TABLE `admin_cred` DISABLE KEYS */;
INSERT INTO `admin_cred` VALUES (1,'lawrence','password',0),(2,'superadmin','superpassword',1);
/*!40000 ALTER TABLE `admin_cred` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_details`
--

DROP TABLE IF EXISTS `booking_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_details` (
  `sr_no` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `total_pay` int(11) NOT NULL,
  `room_no` varchar(150) DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `phonenum` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  PRIMARY KEY (`sr_no`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `booking_details_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_details`
--

LOCK TABLES `booking_details` WRITE;
/*!40000 ALTER TABLE `booking_details` DISABLE KEYS */;
INSERT INTO `booking_details` VALUES (64,65,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(65,66,'test',111,123,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(66,67,'test',111,123,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(67,68,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(68,69,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(69,70,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(70,71,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(71,72,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(72,73,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(73,74,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(74,75,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(75,76,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(76,77,'test',111,111,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr'),(77,78,'test',111,123,'test','Lawrence Lasmarias','09165656123','dsfdhhtfr');
/*!40000 ALTER TABLE `booking_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_order`
--

DROP TABLE IF EXISTS `booking_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_order` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime NOT NULL,
  `arrival` int(11) NOT NULL DEFAULT 0,
  `refund` int(11) DEFAULT NULL,
  `booking_status` varchar(100) NOT NULL DEFAULT 'pending',
  `order_id` varchar(150) NOT NULL,
  `trans_id` varchar(200) NOT NULL DEFAULT 'walk-in',
  `trans_amt` int(11) NOT NULL,
  `trans_status` varchar(100) NOT NULL DEFAULT 'pending',
  `trans_resp_msg` varchar(200) DEFAULT NULL,
  `rate_review` int(11) DEFAULT NULL,
  `datentime` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`booking_id`),
  KEY `user_id` (`user_id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `booking_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`),
  CONSTRAINT `booking_order_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_order`
--

LOCK TABLES `booking_order` WRITE;
/*!40000 ALTER TABLE `booking_order` DISABLE KEYS */;
INSERT INTO `booking_order` VALUES (65,1,9,'2024-10-29 20:00:00','2024-10-30 06:00:00',1,NULL,'booked','ORD_18429571','',56,'reserved',NULL,1,'2024-10-08 18:21:07'),(66,1,9,'2024-11-08 08:00:00','2024-11-08 18:00:00',1,NULL,'booked','ORD_16421836','',62,'reserved',NULL,0,'2024-10-09 15:17:30'),(67,1,9,'2024-11-08 20:00:00','2024-11-09 06:00:00',0,0,'cancelled','ORD_13622267','',123,'reserved',NULL,NULL,'2024-10-09 15:18:05'),(68,1,9,'2024-12-05 08:00:00','2024-12-05 18:00:00',0,NULL,'pending','ORD_17649246','',111,'pending',NULL,NULL,'2024-10-09 15:19:53'),(69,1,9,'2024-11-05 08:00:00','2024-11-05 18:00:00',0,NULL,'pending','ORD_12601423','',56,'pending',NULL,NULL,'2024-10-09 15:42:57'),(70,1,9,'2024-11-05 20:00:00','2024-11-06 06:00:00',0,NULL,'pending','ORD_12252939','',56,'pending',NULL,NULL,'2024-10-09 15:43:07'),(71,1,9,'2024-11-11 20:00:00','2024-11-12 06:00:00',0,NULL,'pending','ORD_19527221','',56,'pending',NULL,NULL,'2024-10-09 15:43:16'),(72,1,9,'2024-11-12 08:00:00','2024-11-12 18:00:00',0,NULL,'pending','ORD_16848458','',111,'pending',NULL,NULL,'2024-10-09 15:43:29'),(73,1,9,'2024-11-12 20:00:00','2024-11-13 06:00:00',0,NULL,'pending','ORD_19358528','',111,'pending',NULL,NULL,'2024-10-09 15:43:39'),(74,1,9,'2024-11-13 20:00:00','2024-11-14 06:00:00',0,NULL,'pending','ORD_17722063','',111,'pending',NULL,NULL,'2024-10-09 15:43:48'),(75,1,9,'2024-10-23 20:00:00','2024-10-24 06:00:00',0,NULL,'pending','ORD_15237649','walk-in',56,'pending',NULL,NULL,'2024-10-13 23:43:54'),(76,1,9,'2024-10-30 20:00:00','2024-10-31 06:00:00',0,NULL,'pending','ORD_1165286','custom',56,'pending',NULL,NULL,'2024-10-13 23:44:10'),(77,1,9,'2024-10-31 20:00:00','2024-11-01 06:00:00',0,NULL,'pending','ORD_1291615','GCASH777777777777777777777',56,'pending',NULL,NULL,'2024-10-13 23:49:15'),(78,1,9,'2024-11-01 20:00:00','2024-11-02 06:00:00',0,NULL,'reserved','ORD_18455750','walk-in',62,'reserved',NULL,NULL,'2024-10-13 23:49:44');
/*!40000 ALTER TABLE `booking_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carousel`
--

DROP TABLE IF EXISTS `carousel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carousel` (
  `sr_no` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(150) NOT NULL,
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carousel`
--

LOCK TABLES `carousel` WRITE;
/*!40000 ALTER TABLE `carousel` DISABLE KEYS */;
INSERT INTO `carousel` VALUES (6,'IMG_79045.png'),(28,'IMG_41680.png'),(29,'IMG_94426.jpeg');
/*!40000 ALTER TABLE `carousel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_details`
--

DROP TABLE IF EXISTS `contact_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_details` (
  `sr_no` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(50) NOT NULL,
  `gmap` varchar(100) NOT NULL,
  `pn1` bigint(20) NOT NULL,
  `pn2` bigint(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fb` varchar(100) NOT NULL,
  `insta` varchar(100) NOT NULL,
  `tw` varchar(100) NOT NULL,
  `iframe` varchar(300) NOT NULL,
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_details`
--

LOCK TABLES `contact_details` WRITE;
/*!40000 ALTER TABLE `contact_details` DISABLE KEYS */;
INSERT INTO `contact_details` VALUES (1,'Angela','https://maps.app.goo.gl/bD5JE7NdBXFnhiR99',26311111111111,632222222222,'angela_example@gm.com','https://www.facebook.com/','https://www.facebook.com/','https://www.facebook.com/','https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7722.039692034821!2d121.16414800000001!3d14.597945!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b916524f4a2d:0xa0f823518f211091!2sAngela\'s Resort 1!5e0!3m2!1sen!2sph!4v1724870148142!5m2!1sen!2sph');
/*!40000 ALTER TABLE `contact_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facilities`
--

DROP TABLE IF EXISTS `facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facilities`
--

LOCK TABLES `facilities` WRITE;
/*!40000 ALTER TABLE `facilities` DISABLE KEYS */;
INSERT INTO `facilities` VALUES (16,'IMG_32270.svg','Wifi','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere eget tortor sed finibus. Fusce quam enim, faucibus sed vehicula non, tempus vitae dui. Duis ac porttitor sapien.'),(17,'IMG_30941.svg','Air Conditioner','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere eget tortor sed finibus. Fusce quam enim, faucibus sed vehicula non, tempus vitae dui. Duis ac porttitor sapien.'),(18,'IMG_79179.svg','Geyser','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere eget tortor sed finibus. Fusce quam enim, faucibus sed vehicula non, tempus vitae dui. Duis ac porttitor sapien.'),(19,'IMG_16272.svg','Room Heater','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere eget tortor sed finibus. Fusce quam enim, faucibus sed vehicula non, tempus vitae dui. Duis ac porttitor sapien.'),(20,'IMG_84773.svg','Spa','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere eget tortor sed finibus. Fusce quam enim, faucibus sed vehicula non, tempus vitae dui. Duis ac porttitor sapien.'),(21,'IMG_35721.svg','Television','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere eget tortor sed finibus. Fusce quam enim, faucibus sed vehicula non, tempus vitae dui. Duis ac porttitor sapien.');
/*!40000 ALTER TABLE `facilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `features`
--

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
INSERT INTO `features` VALUES (6,'bedroom'),(7,'balcony'),(8,'kitchen');
/*!40000 ALTER TABLE `features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rating_review`
--

DROP TABLE IF EXISTS `rating_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rating_review` (
  `sr_no` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` varchar(200) NOT NULL,
  `seen` int(11) NOT NULL DEFAULT 0,
  `datentime` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sr_no`),
  KEY `booking_id` (`booking_id`),
  KEY `room_id` (`room_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `rating_review_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`),
  CONSTRAINT `rating_review_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  CONSTRAINT `rating_review_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rating_review`
--

LOCK TABLES `rating_review` WRITE;
/*!40000 ALTER TABLE `rating_review` DISABLE KEYS */;
INSERT INTO `rating_review` VALUES (9,65,9,1,5,'Vey food!',1,'2024-10-08 18:29:42');
/*!40000 ALTER TABLE `rating_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_facilities`
--

DROP TABLE IF EXISTS `room_facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_facilities` (
  `sr_no` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `facilities_id` int(11) NOT NULL,
  PRIMARY KEY (`sr_no`),
  KEY `facilities id` (`facilities_id`),
  KEY `room id` (`room_id`),
  CONSTRAINT `facilities id` FOREIGN KEY (`facilities_id`) REFERENCES `facilities` (`id`) ON UPDATE NO ACTION,
  CONSTRAINT `room id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_facilities`
--

LOCK TABLES `room_facilities` WRITE;
/*!40000 ALTER TABLE `room_facilities` DISABLE KEYS */;
INSERT INTO `room_facilities` VALUES (96,5,17),(97,7,17),(98,7,18),(99,7,19),(100,7,20),(101,7,21),(102,8,16),(103,8,17),(109,9,16);
/*!40000 ALTER TABLE `room_facilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_features`
--

DROP TABLE IF EXISTS `room_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_features` (
  `sr_no` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `features_id` int(11) NOT NULL,
  PRIMARY KEY (`sr_no`),
  KEY `features id` (`features_id`),
  KEY `rm id` (`room_id`),
  CONSTRAINT `features id` FOREIGN KEY (`features_id`) REFERENCES `features` (`id`) ON UPDATE NO ACTION,
  CONSTRAINT `rm id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_features`
--

LOCK TABLES `room_features` WRITE;
/*!40000 ALTER TABLE `room_features` DISABLE KEYS */;
INSERT INTO `room_features` VALUES (54,5,6),(55,5,7),(56,5,8),(57,7,6),(58,7,7),(59,7,8),(60,8,6),(61,8,7),(77,9,6);
/*!40000 ALTER TABLE `room_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room_images`
--

DROP TABLE IF EXISTS `room_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room_images` (
  `sr_no` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `thumb` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`sr_no`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room_images`
--

LOCK TABLES `room_images` WRITE;
/*!40000 ALTER TABLE `room_images` DISABLE KEYS */;
INSERT INTO `room_images` VALUES (17,5,'IMG_78683.png',1);
/*!40000 ALTER TABLE `room_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `price` int(11) NOT NULL,
  `price2` int(11) NOT NULL,
  `price3` int(11) NOT NULL,
  `price4` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `adult` int(11) NOT NULL,
  `description` varchar(350) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `removed` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,'simple room',43,0,0,0,1,43,'This is description.',1,1),(2,'simple room 3',23,0,0,0,1,432,'This is agfdsdssgs.',1,1),(3,'Simple room 1',132,0,0,0,1,42,'Description',1,1),(4,'Simple room 1',111,222,333,444,1,24,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum beatae nulla porro quam pariatur natus magnam dolorem dicta amet rem saepe, perspiciatis soluta sit ullam blanditiis quia! Repellat, similique placeat?',1,1),(5,'Supreme deluxe room',111,222,333,444,1,9,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum beatae nulla porro quam pariatur natus magnam dolorem dicta amet rem saepe, perspiciatis soluta sit ullam blanditiis quia! Repellat, similique placeat? Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum beatae nulla porro quam pariatur natus magnam dolorem dicta amet rem sae',1,0),(6,'Supreme deluxe room',111,222,333,444,1,9,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum beatae nulla porro quam pariatur natus magnam dolorem dicta amet rem saepe, perspiciatis soluta sit ullam blanditiis quia! Repellat, similique placeat? Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum beatae nulla porro quam pariatur natus magnam dolorem dicta amet rem sae',1,1),(7,'Simple room 2',111,222,333,444,1,46,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum beatae nulla porro quam pariatur natus magnam dolorem dicta amet rem saepe, perspiciatis soluta sit ullam blanditiis quia! Repellat, similique placeat?',1,0),(8,'Simple room 5',111,222,333,444,1,123,'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum beatae nulla porro quam pariatur natus magnam dolorem dicta amet rem saepe, perspiciatis soluta sit ullam blanditiis quia! Repellat, similique placeat?',1,0),(9,'test',111,123,123,123,1,123,'test',1,0);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `sr_no` int(11) NOT NULL AUTO_INCREMENT,
  `site_title` varchar(50) NOT NULL,
  `site_about` varchar(250) NOT NULL,
  `shutdown` tinyint(1) NOT NULL,
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'Angela\'s Private Pool','Celebrate with your family at Angela’s Resort, where you can enjoy a refreshing dip in our sparkling pool and take in the relaxing, overlooking view of the Metro. Located in the peaceful city of Antipolo, Rizal, we are just a few minutes away from th',0);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_cred`
--

DROP TABLE IF EXISTS `user_cred`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_cred` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `address` varchar(120) NOT NULL,
  `phonenum` varchar(100) NOT NULL,
  `pincode` int(11) NOT NULL,
  `dob` date NOT NULL,
  `profile` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `is_verified` int(11) NOT NULL DEFAULT 0,
  `token` varchar(200) DEFAULT NULL,
  `t_expire` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `datentime` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_cred`
--

LOCK TABLES `user_cred` WRITE;
/*!40000 ALTER TABLE `user_cred` DISABLE KEYS */;
INSERT INTO `user_cred` VALUES (1,'Lawrence Lasmarias','lawrencelasmarias1@gmail.com','dsfdhhtfr','09165656123',1920,'2024-09-15','IMG_60411.jpeg','$2y$10$EzY.duYqFWx82Igx/EVyA.aYgYChK1Gueqfdl8sgSsyCdeTfyfAO6',1,NULL,NULL,1,'2024-09-15 21:38:40'),(2,'asd','gdsfdssdgsd@gmail.com','dsfdhhtfr','352342',34,'2024-09-15','IMG_47326.jpeg','$2y$10$rWke.PkTcH0RGmV.Z.qcWODINRTFYaXUd48yFb.t2nmvw7oRz/4Oa',1,'e9227a75d5cf1e29890101bec2dbc652',NULL,0,'2024-09-15 22:11:24'),(4,'Lawrence Lasmarias','lawrencelaias1@gmail.com','sadasdasda','09165656122',1920,'2024-10-03','IMG_87838.jpeg','$2y$10$c.DXtR4xbsCln7lGhRj4.uFhe/ZRnygldXBvIeuv/hk223VZwaZGi',0,'1c1118b1f749b8b0db497fcb9a4b7942',NULL,1,'2024-10-03 03:08:26'),(5,'Lawrence Lasmarias','lawrencelaiaasdasds1@gmail.com','sadasdasda','0916554354353',1920,'2024-10-03','IMG_38863.jpeg','$2y$10$a33HQsasl/8VsXEiB0tqeOm1VW0DzG7xI5S9ivvQW/koZ.U64vi9C',0,'b10fda36e729afcf0e7d554d15a6ffe8',NULL,1,'2024-10-03 03:14:38'),(6,'Lawrence','renzcapricorn1223z7@gmail.com','a','09123456789',1920,'2024-10-04','IMG_67146.jpeg','$2y$10$R5QiTA0vjFZdCN1/IV3u8.55vIPewmcldtqNsWgCd.Ofzw/Tl0doi',1,'183e0b61f21641acc49f84983301392d',NULL,1,'2024-10-04 17:17:48');
/*!40000 ALTER TABLE `user_cred` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_queries`
--

DROP TABLE IF EXISTS `user_queries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_queries` (
  `sr_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` varchar(500) NOT NULL,
  `datentime` datetime NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`sr_no`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_queries`
--

LOCK TABLES `user_queries` WRITE;
/*!40000 ALTER TABLE `user_queries` DISABLE KEYS */;
INSERT INTO `user_queries` VALUES (18,'asd@gmail.com','asd@gmail.com','asd@gmail.com','asd@gmail.com','2024-09-02 00:00:00',1),(19,'asd@gmail.com','asd@gmail.com','asd@gmail.com','asd@gmail.com','2024-09-02 00:00:00',1),(20,'gdfdfg@gmail.com','gdfdfg@gmail.com','gdfdfg@gmail.com gdfdfg@gmail.com gdfdfg@gmail.com','gdfdfg@gmail.com gdfdfg@gmail.com gdfdfg@gmail.com gdfdfg@gmail.com gdfdfg@gmail.com gdfdfg@gmail.com','2024-09-04 00:00:00',1),(21,'asd@gmail.com','asd@gmail.com','asd@gmail.com asd@gmail.com asd@gmail.com','asd@gmail.com asd@gmail.com vasd@gmail.com','2024-09-04 00:00:00',1),(22,'as1111111d@gmail.com','as1111111d@gmail.com','as1111111d@gmail.com as1111111d@gmail.com as1111111d@gmail.com','as1111111d@gmail.com as1111111d@gmail.com as1111111d@gmail.com','2024-09-04 00:00:00',1);
/*!40000 ALTER TABLE `user_queries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-23 19:37:26
