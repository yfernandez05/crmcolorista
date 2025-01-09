-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--

-- Host: 127.0.0.1    Database: siscolorista
-- create database siscolorista;
-- use siscolorista;
-- ------------------------------------------------------
-- Server version	8.0.28

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
-- Table structure for table `alumnos`
--

DROP TABLE IF EXISTS `alumnos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alumnos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prospecto_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alumnos_user_id_foreign` (`user_id`),
  KEY `alumnos_prospecto_id_foreign` (`prospecto_id`),
  CONSTRAINT `alumnos_prospecto_id_foreign` FOREIGN KEY (`prospecto_id`) REFERENCES `prospectos` (`id`),
  CONSTRAINT `alumnos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumnos`
--

LOCK TABLES `alumnos` WRITE;
/*!40000 ALTER TABLE `alumnos` DISABLE KEYS */;
INSERT INTO `alumnos` VALUES (1,'Mervin','Beier','1981-10-05','stanton.henderson@wyman.com',11,22,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'Judy','Hahn','1994-12-31','harry25@bogan.com',12,24,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'Guiseppe','Mann','1992-03-07','hjohnson@yahoo.com',13,29,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'Floyd','Willms','2013-01-30','crooks.bryana@smith.com',14,31,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'Jessyca','Abernathy','1984-05-24','lockman.jacquelyn@yahoo.com',15,36,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'Bennie','Schiller','2005-11-19','nicholas.dietrich@rempel.com',16,38,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'Elyssa','Kreiger','1996-04-24','nick.torp@gmail.com',17,43,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'Lenny','Crist','1983-04-06','stark.sabrina@medhurst.com',18,45,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'Grady','Doyle','2020-12-04','ellis96@gmail.com',19,50,'E',NULL,NULL,'2022-01-13 01:46:09','2022-01-18 05:31:22'),(10,'Clair','Frami','2012-07-13','xzboncak@gmail.com',20,52,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(11,'Kimberly','Stoltenberg','1984-08-26','upton.palma@rice.com',21,57,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(12,'Aaron','Kihn','1998-02-02','franecki.marcellus@gmail.com',22,62,'E',NULL,'sistemas@padinsolutions.com','2022-01-13 01:46:09','2022-01-13 01:46:09'),(13,'Stanton','Paucek','2017-12-24','borer.zula@gmail.com',23,67,'E',NULL,NULL,'2022-01-13 01:46:09','2022-01-18 05:31:17'),(14,'Myriam','Tremblay','1971-02-13','schneider.xzavier@satterfield.biz',24,72,'E',NULL,NULL,'2022-01-13 01:46:09','2022-01-18 05:31:27'),(15,'Mose','Wolff','2001-06-05','feeney.aimee@ohara.com',25,77,'E',NULL,NULL,'2022-01-13 01:46:10','2022-01-18 05:17:24'),(16,'Abel J.','Palomino Rojas','1987-08-12','abel@gmail.com',NULL,1,'E',NULL,NULL,'2022-01-18 02:41:39','2022-01-18 05:31:09'),(17,'J. Abel','Palomino','1987-08-17','palomino@gmail.com',NULL,1,'A',NULL,NULL,'2022-01-18 04:29:01','2022-01-18 04:29:01'),(18,'Yhonatan updae','Fernandez','1999-05-10','yhonatanfernadezcardenas@gmail.com',NULL,1,'E','sistemas@padinsolutions.com','sistemas@padinsolutions.com',NULL,NULL);
/*!40000 ALTER TABLE `alumnos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calificaciones`
--

DROP TABLE IF EXISTS `calificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calificaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `evaluacion1` double(8,2) NOT NULL,
  `evaluacion2` double(8,2) NOT NULL,
  `evaluacion3` double(8,2) NOT NULL,
  `sustitutorio` double(8,2) NOT NULL,
  `eva_final` double(8,2) NOT NULL,
  `alumno_id` bigint unsigned NOT NULL,
  `curso_id` bigint unsigned NOT NULL,
  `matricula_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calificaciones_alumno_id_foreign` (`alumno_id`),
  KEY `calificaciones_curso_id_foreign` (`curso_id`),
  KEY `calificaciones_matricula_id_foreign` (`matricula_id`),
  KEY `calificaciones_user_id_foreign` (`user_id`),
  CONSTRAINT `calificaciones_alumno_id_foreign` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`),
  CONSTRAINT `calificaciones_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  CONSTRAINT `calificaciones_matricula_id_foreign` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`),
  CONSTRAINT `calificaciones_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calificaciones`
--

LOCK TABLES `calificaciones` WRITE;
/*!40000 ALTER TABLE `calificaciones` DISABLE KEYS */;
INSERT INTO `calificaciones` VALUES (1,45.00,43.00,70.00,44.00,53.00,1,1,1,27,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,76.00,98.00,91.00,86.00,13.00,3,2,2,34,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,69.00,72.00,94.00,15.00,16.00,5,3,3,41,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,92.00,33.00,29.00,96.00,71.00,7,4,4,48,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,28.00,23.00,82.00,40.00,33.00,9,5,5,55,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09');
/*!40000 ALTER TABLE `calificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carreras`
--

DROP TABLE IF EXISTS `carreras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carreras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duracion_meses` int NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carreras_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carreras`
--

LOCK TABLES `carreras` WRITE;
/*!40000 ALTER TABLE `carreras` DISABLE KEYS */;
INSERT INTO `carreras` VALUES (1,'Prof. Max Block Sr.','ratione sit temporibus assumenda',70,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'Jada Cormier','nemo enim doloribus exercitationem',58,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'Prof. Brant Murray','qui ullam non dolore',34,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'Esther Hand','quia dolores enim commodi',17,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'Miss Autumn Franecki Sr.','accusantium fugit illo culpa',49,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'Demarcus Leannon','perspiciatis vero aut sint',73,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'Charity Eichmann Sr.','ut vero quidem aut',75,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'Ora Beier','qui enim quae est',56,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'Tamia Quitzon','est sint minus est',16,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'Giles Deckow I','est et ut aliquam',61,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(11,'Rebecca Parisian','expedita neque omnis dolore',37,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(12,'Mazie Mertz','velit optio nihil sit',84,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(13,'Lavada Keebler','culpa quod omnis qui',85,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(14,'Roma Balistreri','eos consequatur rem quia',13,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(15,'Brown Smith III','quis temporibus quas tempore',38,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(16,'Caleigh Bernhard','eos voluptas quae est',52,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(17,'Yasmine Breitenberg','et accusantium et qui',81,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(18,'Abdullah Kihn','voluptatem est culpa expedita',19,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(19,'Mrs. Ebba Cremin','soluta quibusdam molestias dolorem',65,'E',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(20,'Janae Langworth Jr.','corrupti cupiditate dolor dolores',55,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(21,'Nueva carrea update','dEscricpion carrera update',15,'A','sistemas@padinsolutions.com',NULL,NULL,NULL);
/*!40000 ALTER TABLE `carreras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ciclos`
--

DROP TABLE IF EXISTS `ciclos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ciclos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ciclos_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ciclos`
--

LOCK TABLES `ciclos` WRITE;
/*!40000 ALTER TABLE `ciclos` DISABLE KEYS */;
INSERT INTO `ciclos` VALUES (1,'recusandae atque','praesentium saepe in ea','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'aperiam ducimus','in natus sed corrupti','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'consequuntur accusamus','beatae quasi sit dolorum','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'ut et','magnam est illo id','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'atque ut','voluptas illo perferendis omnis','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'id magnam','totam aut quod ut','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'earum nam','expedita dolor in dolores','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'expedita id','ut temporibus enim occaecati','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'minima earum','est excepturi quod ea','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'nemo voluptas','voluptatibus maxime voluptas sequi','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(11,'eum enim','earum et eveniet dolore','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(12,'cumque et','earum placeat unde dignissimos','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(13,'dolorum corrupti','quo aut ullam sed','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(14,'voluptatem inventore','soluta rerum maiores labore','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(15,'voluptas possimus','consectetur quisquam corporis esse','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(16,'nisi qui','facilis aut placeat aut','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(17,'quos veniam','a et deserunt fugit','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(18,'recusandae sunt','dolor excepturi commodi quidem','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(19,'et quia','et facilis aliquam possimus','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(20,'qui vel','sed aliquam est consequuntur','E',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(21,'nuevo ciclo update','nuevocilo descripcion','A','sistemas@padinsolutions.com',NULL,NULL,NULL);
/*!40000 ALTER TABLE `ciclos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `concepto_pagos`
--

DROP TABLE IF EXISTS `concepto_pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `concepto_pagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `concepto_pagos_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `concepto_pagos`
--

LOCK TABLES `concepto_pagos` WRITE;
/*!40000 ALTER TABLE `concepto_pagos` DISABLE KEYS */;
INSERT INTO `concepto_pagos` VALUES (1,'tempore','quam expedita qui quisquam','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'voluptas','a dolorem consequatur sint','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'neque','aspernatur quam pariatur impedit','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'blanditiis','fugit reiciendis dignissimos molestias','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'facilis','aliquam totam in dolorem','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10');
/*!40000 ALTER TABLE `concepto_pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condiciones`
--

DROP TABLE IF EXISTS `condiciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `condiciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condiciones`
--

LOCK TABLES `condiciones` WRITE;
/*!40000 ALTER TABLE `condiciones` DISABLE KEYS */;
INSERT INTO `condiciones` VALUES (1,'Lysanne Metz Jr.','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'Donny Hartmann','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'Dr. Constantin Cormier II','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'Martin Rowe','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'Matteo Rempel Sr.','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'Mr. Sofia Lehner Jr.','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'Samanta Cummings DDS','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'Karli Rolfson','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'Ms. Elyssa Prosacco MD','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'Hilda Weissnat','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(11,'nuevo nombre update','A','sistemas@padinsolutions.com','sistemas@padinsolutions.com',NULL,NULL),(12,'nueva condifionc update','E','sistemas@padinsolutions.com','sistemas@padinsolutions.com',NULL,NULL);
/*!40000 ALTER TABLE `condiciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cursos_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursos`
--

LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` VALUES (1,'unde','corporis ipsa ipsa molestias','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'maxime','est quasi omnis et','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'accusantium','quod aut ut a','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'fuga','ut maiores ipsum molestiae','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'sed','quisquam dolores architecto sit','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'officia','praesentium fugiat autem ipsum','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(7,'sapiente','optio ad nisi amet','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(8,'quod','rerum vel impedit velit','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(9,'rem','sunt dolorem error sunt','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(10,'minus','laborum nihil debitis et','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(11,'provident','itaque recusandae tempora perferendis','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(12,'debitis','quam beatae enim possimus','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(13,'architecto','fugiat ipsam ipsam perferendis','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(14,'fugiat','eos nihil aut id','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(15,'incidunt','a aliquid quia placeat','E',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(16,'Nuevo Cursoupdaye','nuevo curso desccripcion','A','sistemas@padinsolutions.com',NULL,NULL,NULL);
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_planes`
--

DROP TABLE IF EXISTS `detalle_planes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_planes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` bigint unsigned NOT NULL,
  `curso_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_planes_plan_id_foreign` (`plan_id`),
  KEY `detalle_planes_curso_id_foreign` (`curso_id`),
  CONSTRAINT `detalle_planes_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  CONSTRAINT `detalle_planes_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plan_estudios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_planes`
--

LOCK TABLES `detalle_planes` WRITE;
/*!40000 ALTER TABLE `detalle_planes` DISABLE KEYS */;
INSERT INTO `detalle_planes` VALUES (1,1,6,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(2,2,7,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(3,3,8,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(4,4,9,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(5,5,10,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(6,6,11,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(7,7,12,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(8,8,13,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(9,9,14,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(10,10,15,'2022-01-13 01:46:10','2022-01-13 01:46:10');
/*!40000 ALTER TABLE `detalle_planes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscripciones`
--

DROP TABLE IF EXISTS `inscripciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscripciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `detalle` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` timestamp NOT NULL,
  `alumno_id` bigint unsigned NOT NULL,
  `carrera_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inscripciones_alumno_id_foreign` (`alumno_id`),
  KEY `inscripciones_carrera_id_foreign` (`carrera_id`),
  KEY `inscripciones_user_id_foreign` (`user_id`),
  CONSTRAINT `inscripciones_alumno_id_foreign` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`),
  CONSTRAINT `inscripciones_carrera_id_foreign` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`),
  CONSTRAINT `inscripciones_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscripciones`
--

LOCK TABLES `inscripciones` WRITE;
/*!40000 ALTER TABLE `inscripciones` DISABLE KEYS */;
INSERT INTO `inscripciones` VALUES (1,'in veritatis voluptas molestiae','2022-01-13 01:46:09',2,1,25,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'qui est quo dolorem','2022-01-13 01:46:09',4,2,32,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'quos reiciendis sed voluptas','2022-01-13 01:46:09',6,3,39,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'culpa accusantium sit officiis','2022-01-13 01:46:09',8,4,46,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'quibusdam nisi est error','2022-01-13 01:46:09',10,5,53,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'consequatur perferendis et magni','2022-01-13 01:46:09',11,6,58,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'beatae nostrum ut nisi','2022-01-13 01:46:09',12,7,63,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'odit nihil deserunt reprehenderit','2022-01-13 01:46:09',13,8,68,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'debitis nobis modi molestias','2022-01-13 01:46:09',14,9,73,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'et ex ut tempora','2022-01-13 01:46:10',15,10,78,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10');
/*!40000 ALTER TABLE `inscripciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logerrors`
--

DROP TABLE IF EXISTS `logerrors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logerrors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `classname` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `methodname` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `errormessage` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dateoccurred` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logerrors`
--

LOCK TABLES `logerrors` WRITE;
/*!40000 ALTER TABLE `logerrors` DISABLE KEYS */;
INSERT INTO `logerrors` VALUES (57,'App\\Http\\Controllers\\UserController','store','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'idcuenta\' in \'field list\' (SQL: insert into `users` (`rol_id`, `name`, `email`, `idcuenta`, `idevento`, `idcampania`, `password`, `api_token`, `updated_at`, `created_at`) values (81, Yhonaan, fer@gmail.com, ?, ?, ?, $2y$10$/BFy5MwWrshCBUQFiSp21.b.NT3928AOKL2lf5CKhA4gM1/Q0BIeW, $2y$10$Y24jEcyKCwTY5MuxVhe5/elRsTMwxK5gQ1wQ3vgrMdtinPBepjV/K, 2024-12-19 15:58:41, 2024-12-19 15:58:41))','2024-12-19 20:58:41'),(58,'App\\Http\\Controllers\\UserController','store','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'idcuenta\' in \'field list\' (SQL: insert into `users` (`rol_id`, `name`, `email`, `idcuenta`, `idevento`, `idcampania`, `password`, `api_token`, `updated_at`, `created_at`) values (81, Yhonaan, fer@gmail.com, ?, ?, ?, $2y$10$Ww/0qd4DWwgSz.wixZdAQOgGseOWldznzSs3iYjjXgs4I0ZcdlmfS, $2y$10$BGkk5yJVcbGK5p7tCdTswuFrKA3sfgc8rReMPupvyf65KlbLPI6Yi, 2024-12-19 15:58:51, 2024-12-19 15:58:51))','2024-12-19 20:58:51'),(59,'App\\Http\\Controllers\\UserController','store','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'updated_at\' in \'field list\' (SQL: insert into `users` (`rol_id`, `name`, `last_name`, `specialities`, `email`, `password`, `api_token`, `updated_at`, `created_at`) values (81, Yhonatan, Fernandez, ---, fernandez@gmail.com, $2y$10$ITzhNaHN8pbOYHuOAsbuM.mScaY/imIBUHjohwHSknpPqSvVgtAWi, $2y$10$6C/DUYvwUMI5b7Lr5lyYcOw8V08lN7u6bBtj2Z3py.GJwEnrpWnPG, 2024-12-19 16:12:27, 2024-12-19 16:12:27))','2024-12-19 21:12:27'),(60,'App\\Http\\Controllers\\UserController','store','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'updated_at\' in \'field list\' (SQL: insert into `users` (`rol_id`, `name`, `last_name`, `specialities`, `email`, `password`, `api_token`, `updated_at`, `created_at`) values (81, Yhonatan, Fernandez, ---, fernandez@gmail.com, $2y$10$6eSh9ZgH2XZX7.uC.Ob6pe0rt/hEg9PPl/qsnivyFJ16/vgz0mIgi, $2y$10$DjrRTIDUE5fEYitIdbi5kO1GNerjxsGeCyb8WYQVuMCR7c57BG4gu, 2024-12-19 16:14:08, 2024-12-19 16:14:08))','2024-12-19 21:14:08'),(61,'App\\Http\\Controllers\\UserController','store','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'updated_at\' in \'field list\' (SQL: insert into `users` (`rol_id`, `name`, `last_name`, `specialities`, `email`, `userinsert`, `password`, `api_token`, `updated_at`, `created_at`) values (81, Yhonatan, Fernandez, ---, fernandez@gmail.com, sistemas@padinsolutions.com, $2y$10$LXJhBcCCaUj7leHnsoM3Re9p9pst.p66Ow.VurOgdr/JjZ7641LCy, $2y$10$wmVD/EZsPM2Rx0WlWPAdKujjF4IjRzDYR6x/LxvTfC4m9uF/Wrcve, 2024-12-19 16:17:06, 2024-12-19 16:17:06))','2024-12-19 21:17:06'),(62,'App\\Http\\Controllers\\UserController','store','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'updated_at\' in \'field list\' (SQL: insert into `users` (`rol_id`, `name`, `last_name`, `specialities`, `email`, `userinsert`, `password`, `api_token`, `updated_at`, `created_at`) values (81, Yhonatan, Fernandez, ---, fernandez@gmail.com, sistemas@padinsolutions.com, $2y$10$CSOWWTLBDzg7lo7ww4RVUuofbRb/jDduCVolAnPizCdINP6K/.H86, $2y$10$ZcpRX38mp2Imu.NK9Q8Nkucfb5jIp6YzqMZvFE9KQlihdayKbe2Ia, 2024-12-19 16:18:14, 2024-12-19 16:18:14))','2024-12-19 21:18:14'),(63,'App\\Http\\Controllers\\UserController','store','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'updated_at\' in \'field list\' (SQL: insert into `users` (`rol_id`, `name`, `last_name`, `specialities`, `email`, `userinsert`, `password`, `api_token`, `updated_at`, `created_at`) values (81, Yhonatan, Fernandez, ---, fernandez@gmail.com, sistemas@padinsolutions.com, $2y$10$1HbPG9GV/ogrAwayzKefIuLScFtaxAZluoVQZmcKbFBJeoB5XOeEO, $2y$10$uVwsMjgQGzLM3DZYOqtX/.YrwOh1QFOwF6zBmTWwfRIu3.IsHLmXu, 2024-12-19 16:20:01, 2024-12-19 16:20:01))','2024-12-19 21:20:01'),(64,'App\\Http\\Controllers\\UserController','store','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'updated_at\' in \'field list\' (SQL: insert into `users` (`rol_id`, `name`, `last_name`, `specialities`, `email`, `userinsert`, `password`, `api_token`, `updated_at`, `created_at`) values (82, yhonatan, fernandez, --, rf@gmail.com, sistemas@padinsolutions.com, $2y$10$zoYdRe24g7VS8aH6nB7CxeY.ulOGUCJpRI4A.IJXFzT3Luc82Qlr6, $2y$10$iC5y0EVtzg6kz5X9o3op1uORB.pXy0OKjMvObL4ouqFRBkAARv0Ji, 2024-12-19 16:25:36, 2024-12-19 16:25:36))','2024-12-19 21:25:36'),(65,'App\\Http\\Controllers\\UserController','store','SQLSTATE[42S22]: Column not found: 1054 Unknown column \'userinsert\' in \'field list\' (SQL: insert into `users` (`rol_id`, `name`, `last_name`, `specialities`, `email`, `userinsert`, `password`, `api_token`, `updated_at`, `created_at`) values (82, yhonatan, fernandez, --, rf@gmail.com, sistemas@padinsolutions.com, $2y$10$XsZqbZLR2/m.t0lJ7tcCbO9NAeWYeNXIuuiQ0R2El7yWqhrLL3iQe, $2y$10$7csgISF9kDvEFUTQRXBwWusO0Hn67A/IjkXzm0mAWvA9k5NCQflqW, 2024-12-19 16:29:17, 2024-12-19 16:29:17))','2024-12-19 21:29:17'),(66,'App\\Http\\Controllers\\CondicionController','store','Method App\\Http\\Controllers\\CondicionController::setModel does not exist.','2024-12-19 22:09:43'),(67,'App\\Http\\Controllers\\AlumnoController','store','Data missing','2024-12-19 23:16:35'),(68,'App\\Http\\Controllers\\AlumnoController','store','Data missing','2024-12-19 23:20:11'),(69,'App\\Http\\Controllers\\AlumnoController','store','Data missing','2024-12-19 23:20:38'),(70,'App\\Http\\Controllers\\AlumnoController','update','The separation symbol could not be found\r\nTrailing data','2024-12-19 23:26:00'),(71,'App\\Http\\Controllers\\PlanEstudioController','store','SQLSTATE[23000]: Integrity constraint violation: 1048 Column \'costo\' cannot be null (SQL: insert into `plan_estudios` (`costo`, `duracion_meses`, `carrera_id`, `ciclo_id`, `created_usr`) values (?, 5, 21, 17, sistemas@padinsolutions.com))','2024-12-20 22:36:24');
/*!40000 ALTER TABLE `logerrors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matriculas`
--

DROP TABLE IF EXISTS `matriculas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matriculas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `detalle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` timestamp NOT NULL,
  `inscripcion_id` bigint unsigned NOT NULL,
  `ciclo_id` bigint unsigned NOT NULL,
  `periodo_id` bigint unsigned NOT NULL,
  `condicion_id` bigint unsigned NOT NULL,
  `turno_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `matriculas_inscripcion_id_foreign` (`inscripcion_id`),
  KEY `matriculas_ciclo_id_foreign` (`ciclo_id`),
  KEY `matriculas_periodo_id_foreign` (`periodo_id`),
  KEY `matriculas_condicion_id_foreign` (`condicion_id`),
  KEY `matriculas_turno_id_foreign` (`turno_id`),
  KEY `matriculas_user_id_foreign` (`user_id`),
  CONSTRAINT `matriculas_ciclo_id_foreign` FOREIGN KEY (`ciclo_id`) REFERENCES `ciclos` (`id`),
  CONSTRAINT `matriculas_condicion_id_foreign` FOREIGN KEY (`condicion_id`) REFERENCES `condiciones` (`id`),
  CONSTRAINT `matriculas_inscripcion_id_foreign` FOREIGN KEY (`inscripcion_id`) REFERENCES `inscripciones` (`id`),
  CONSTRAINT `matriculas_periodo_id_foreign` FOREIGN KEY (`periodo_id`) REFERENCES `periodos` (`id`),
  CONSTRAINT `matriculas_turno_id_foreign` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`),
  CONSTRAINT `matriculas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matriculas`
--

LOCK TABLES `matriculas` WRITE;
/*!40000 ALTER TABLE `matriculas` DISABLE KEYS */;
INSERT INTO `matriculas` VALUES (1,'ea fugiat unde praesentium','2022-01-13 01:46:09',1,1,1,1,1,26,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'harum odio qui eius','2022-01-13 01:46:09',2,2,2,2,2,33,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'earum enim aut reprehenderit','2022-01-13 01:46:09',3,3,3,3,3,40,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'recusandae iste harum enim','2022-01-13 01:46:09',4,4,4,4,4,47,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'ipsum autem ut occaecati','2022-01-13 01:46:09',5,5,5,5,5,54,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'quod doloribus sit voluptatem','2022-01-13 01:46:09',6,6,6,6,6,59,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'velit facilis mollitia sequi','2022-01-13 01:46:09',7,7,7,7,7,64,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'voluptate officia sit sit','2022-01-13 01:46:09',8,8,8,8,8,69,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'saepe voluptas a ducimus','2022-01-13 01:46:09',9,9,9,9,9,74,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'eum aut fugit error','2022-01-13 01:46:10',10,10,10,10,10,79,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10');
/*!40000 ALTER TABLE `matriculas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2013_11_03_180123_create_rols_table',1),(2,'2014_10_12_000000_create_users_table',1),(3,'2014_10_12_100000_create_password_resets_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2021_11_02_015724_create_ciclos_table',1),(7,'2021_11_02_020751_create_periodos_table',1),(8,'2021_11_02_205135_create_turnos_table',1),(9,'2021_11_03_024838_create_prospectos_table',1),(10,'2021_11_03_173530_create_alumnos_table',1),(11,'2021_11_03_175204_create_seguimientos_table',1),(12,'2021_11_03_200552_create_carreras_table',1),(13,'2021_11_03_215432_create_condicions_table',1),(14,'2021_11_03_234219_create_inscripcions_table',1),(15,'2021_11_03_234220_create_matriculas_table',1),(16,'2021_11_04_014406_create_cursos_table',1),(17,'2021_11_04_073506_create_concepto_pagos_table',1),(18,'2021_11_04_193248_create_calificacions_table',1),(19,'2021_11_05_005406_create_pagos_table',1),(20,'2021_11_06_025513_create_plan_estudios_table',1),(21,'2021_11_06_213817_create_detalle_plans_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `matricula_id` bigint unsigned NOT NULL,
  `concepto_id` bigint unsigned NOT NULL,
  `monto` decimal(18,2) NOT NULL,
  `detalle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_matricula_id_foreign` (`matricula_id`),
  KEY `pagos_concepto_id_foreign` (`concepto_id`),
  KEY `pagos_user_id_foreign` (`user_id`),
  CONSTRAINT `pagos_concepto_id_foreign` FOREIGN KEY (`concepto_id`) REFERENCES `concepto_pagos` (`id`),
  CONSTRAINT `pagos_matricula_id_foreign` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`),
  CONSTRAINT `pagos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
INSERT INTO `pagos` VALUES (1,6,1,87.00,'ab qui voluptatem sint',60,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(2,7,2,89.00,'voluptatem dignissimos deleniti fugit',65,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(3,8,3,26.00,'aut atque rerum ut',70,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(4,9,4,72.00,'sed laborum non porro',75,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(5,10,5,97.00,'delectus non perspiciatis facere',80,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10');
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `periodos`
--

DROP TABLE IF EXISTS `periodos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `periodos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `periodos_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `periodos`
--

LOCK TABLES `periodos` WRITE;
/*!40000 ALTER TABLE `periodos` DISABLE KEYS */;
INSERT INTO `periodos` VALUES (1,'ipsa','eius in nulla minus','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'necessitatibus','consectetur veritatis quos aperiam','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'eveniet','nisi laboriosam placeat alias','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'similique','vel et ea eaque','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'vero','repudiandae amet laboriosam perspiciatis','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'nihil','id ex error sed','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'cum','autem cum quia cupiditate','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'modi','blanditiis qui rerum ea','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'labore','eum consectetur eveniet ut','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'ab','tempora ducimus minus aut','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10');
/*!40000 ALTER TABLE `periodos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plan_estudios`
--

DROP TABLE IF EXISTS `plan_estudios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plan_estudios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `costo` decimal(18,2) NOT NULL,
  `duracion_meses` int NOT NULL,
  `carrera_id` bigint unsigned NOT NULL,
  `ciclo_id` bigint unsigned NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plan_estudios_carrera_id_foreign` (`carrera_id`),
  KEY `plan_estudios_ciclo_id_foreign` (`ciclo_id`),
  CONSTRAINT `plan_estudios_carrera_id_foreign` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`),
  CONSTRAINT `plan_estudios_ciclo_id_foreign` FOREIGN KEY (`ciclo_id`) REFERENCES `ciclos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan_estudios`
--

LOCK TABLES `plan_estudios` WRITE;
/*!40000 ALTER TABLE `plan_estudios` DISABLE KEYS */;
INSERT INTO `plan_estudios` VALUES (1,93.00,27,11,11,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(2,73.00,11,12,12,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(3,71.00,93,13,13,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(4,25.00,81,14,14,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(5,42.00,48,15,15,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(6,98.00,29,16,16,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(7,39.00,12,17,17,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(8,82.00,42,18,18,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(9,95.00,76,19,19,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(10,37.00,71,20,20,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(11,10.00,15,21,20,'A','sistemas@padinsolutions.com',NULL,NULL,NULL);
/*!40000 ALTER TABLE `plan_estudios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospectos`
--

DROP TABLE IF EXISTS `prospectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prospectos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `procedencia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` timestamp NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prospectos_telefono_unique` (`telefono`),
  UNIQUE KEY `prospectos_correo_unique` (`correo`),
  KEY `prospectos_user_id_foreign` (`user_id`),
  CONSTRAINT `prospectos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospectos`
--

LOCK TABLES `prospectos` WRITE;
/*!40000 ALTER TABLE `prospectos` DISABLE KEYS */;
INSERT INTO `prospectos` VALUES (1,'Danny','Kiehn','1970-02-11','+12564973952','joanie.beatty@example.org','et minima explicabo incidunt fuga consequatur','2022-01-13 01:46:09',2,'A',
NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),
(2,'Mathias','Kessler','1982-11-27','1-769-709-5790','homenick.ibrahim@example.net','temporibus nobis unde et dolorum eos','2022-01-13 01:46:09'
,4,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'Alvera','Sauer','2010-05-10','260.207.3476','gerson.moore@example.net',
'odit dolor ut asperiores nemo eveniet','2022-01-13 01:46:09',6,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),
(4,'Lue','Balistreri','1971-07-31','984.381.9015','prohaska.marcelina@example.com','ut aut impedit aut et et',
'2022-01-13 01:46:09',8,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),
(5,'Miles','Koch','2010-09-28','678.247.1478','zachery85@example.net','cupiditate rem eaque omnis quidem repellat','2022-01-13 01:46:09',10,'A',NULL,
NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),
(6,'Vladimir','Mann','1976-04-19','(725) 414-8111','harvey.viola@example.net','enim aperiam rerum laudantium occaecati laudantium',
'2022-01-13 01:46:09',12,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'Minerva','Skiles','2011-09-02',
'+15649804709','wbauch@example.org','quia praesentium corrupti vel velit sit','2022-01-13 01:46:09',14,'A',NULL,NULL,
'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'Else','Ritchie','1971-04-07','802.435.5889','maryse.mcglynn@example.org',
'aut omnis tempora animi sint quo','2022-01-13 01:46:09',16,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),
(9,'Wilfrid','Rowe','1978-12-01','325-943-3490','alexandrea70@example.net','nihil quae architecto tenetur non autem','2022-01-13 01:46:09',18,'A',
NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'Omer','Schumm','2012-04-16','762-250-5595','lyla.wuckert@example.org',
'doloremque et eos pariatur aut provident','2022-01-13 01:46:09',20,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),
(11,'Tamara','Tremblay','1998-04-11','+1-904-764-5279','carley.waelchi@example.net','labore ducimus eos dolores voluptatem necessitatibus',
'2022-01-13 01:46:09',21,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(12,'Everette','Huel','1982-11-22','203.526.3970',
'oscar.williamson@example.org','est numquam eum ut qui consequuntur','2022-01-13 01:46:09',23,'A',NULL,NULL,
'2022-01-13 01:46:09','2022-01-13 01:46:09'),(13,'Virgie','Connell','1995-08-05','484.315.4710','ghickle@example.com','vero soluta non delectus ut id','2022-01-13 01:46:09',28,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(14,'Damon','Blick','1994-07-21','+1-214-650-4273','krohan@example.net','sit fugiat et non enim est','2022-01-13 01:46:09',30,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(15,'Saige','Watsica','1974-01-07','1-717-592-1353','benton.wolff@example.org','temporibus soluta veniam quam similique sunt','2022-01-13 01:46:09',35,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(16,'Bernie','Dietrich','1992-08-18','1-541-920-1000','krunolfsdottir@example.net','ut qui dolorem voluptas delectus et','2022-01-13 01:46:09',37,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(17,'Porter','Kilback','1984-09-03','+1-820-846-6191','destiney.lowe@example.net','ut at repudiandae est sint aut','2022-01-13 01:46:09',42,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(18,'Destany','Walsh','2022-01-12','+1-918-933-7122','ziemann.parker@example.com','temporibus voluptas corporis voluptatibus rem molestiae','2022-01-13 01:46:09',44,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(19,'Madie','Little','1975-12-15','+1-816-738-8850','herminia04@example.org','qui quisquam occaecati incidunt corrupti omnis','2022-01-13 01:46:09',49,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(20,'Amely','Kiehn','1998-06-17','+1-507-503-6015','cristina.collier@example.net','rerum non ullam et corrupti aperiam','2022-01-13 01:46:09',51,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(21,'Camryn','Yost','2014-09-10','+1-351-392-5498','wveum@example.org','nobis quis autem quasi provident ab','2022-01-13 01:46:09',56,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(22,'Sharon','Carter','1989-01-18','(878) 803-2849','russel68@example.net','quam autem repellat ex at sequi','2022-01-13 01:46:09',61,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(23,'Carolyne','Cartwright','2012-04-14','(712) 355-2433','arne.hermiston@example.org','natus voluptatibus nobis ipsam quas ea','2022-01-13 01:46:09',66,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(24,'Enos','Simonis','2000-08-16','857-466-1586','cornelius60@example.net','et nulla quos quia est corrupti','2022-01-13 01:46:09',71,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(25,'Sebastian','Herzog','2017-05-03','301.514.7434','aisha.langosh@example.net','eum eos qui ab molestiae qui','2022-01-13 01:46:10',76,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10');
/*!40000 ALTER TABLE `prospectos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `userinsert` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userupdate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateinsert` timestamp NULL DEFAULT NULL,
  `dateupdate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'quia','tempore illo odio delectus','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'consequatur','eos non est consequatur','E',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'laboriosam','unde accusantium vitae dicta','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'cumque','dolores sint natus aut','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'laudantium','temporibus aut sunt optio','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'reiciendis','molestiae vel eum sapiente','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'ratione','neque assumenda eaque ad','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'voluptatem','sint exercitationem voluptate consequuntur','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'et','officia illum illo nihil','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'sit','aut culpa illum et','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(11,'aperiam','consequatur nam iste consectetur','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(12,'occaecati','quos maxime nobis explicabo','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(13,'veniam','quaerat sunt et autem','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(14,'id','quia qui quasi excepturi','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(15,'est','optio cupiditate praesentium porro','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(16,'iusto','doloremque dolorum provident quo','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(17,'molestiae','deleniti earum consequuntur adipisci','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(18,'hic','et dolores quibusdam facere','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(19,'dicta','dolor voluptatem voluptatem quisquam','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(20,'reprehenderit','dolor sit distinctio sint','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(21,'autem','recusandae ex autem quis','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(22,'eos','est totam atque inventore','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(23,'inventore','vero ea quidem provident','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(24,'quasi','qui est sit voluptas','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(25,'suscipit','sit velit ea sit','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(26,'aut','voluptatem ad maxime tempore','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(27,'repellendus','et ipsum aut eos','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(28,'voluptatum','itaque et quas ut','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(29,'omnis','repudiandae ullam et perferendis','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(30,'ipsum','rerum beatae aut ipsa','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(31,'qui','numquam in ut corporis','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(32,'fugit','facilis id harum quas','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(33,'dolore','at accusamus laudantium odio','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(34,'nostrum','dolores iusto et quod','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(35,'quidem','iste quia tempora expedita','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(36,'accusamus','inventore est vero facilis','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(37,'in','dolorem perferendis molestias velit','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(38,'officiis','doloremque quae quia impedit','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(39,'natus','magni reiciendis fugiat nihil','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(40,'tenetur','odio recusandae quaerat voluptatem','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(41,'dolores','ea quam dolore quo','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(42,'itaque','adipisci vel aspernatur assumenda','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(43,'culpa','placeat ad quas amet','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(44,'mollitia','velit dolor sunt corporis','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(45,'doloremque','in suscipit nemo nesciunt','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(46,'tempora','eaque molestiae ut doloremque','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(47,'ex','occaecati nulla sapiente repellat','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(48,'placeat','minus quia qui omnis','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(49,'illum','ipsam provident nostrum exercitationem','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(50,'ut','harum vero laboriosam eos','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(51,'aliquid','libero tempora et id','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(52,'quas','nesciunt dolorum debitis ut','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(53,'non','et vitae delectus voluptatem','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(54,'deleniti','fugit ipsum qui aut','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(55,'asperiores','sed rem rerum delectus','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(56,'eius','rerum omnis optio iusto','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(57,'magni','reprehenderit eos nulla aspernatur','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(58,'earum','quisquam dolorum autem sed','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(59,'sequi','quia qui assumenda ducimus','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(60,'dolor','atque adipisci veniam qui','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(61,'dignissimos','non et sit similique','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(62,'iure','animi officiis occaecati cum','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(63,'nobis','odio sit facere recusandae','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(64,'libero','modi qui quos nisi','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(65,'recusandae','sequi qui occaecati sint','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(66,'velit','temporibus eum cum quae','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(67,'sint','porro aut mollitia illum','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(68,'ullam','ducimus ea eum dolor','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(69,'eum','dolore aut aliquid ad','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(70,'quos','et dolorum est incidunt','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(71,'nemo','consectetur et facere id','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(72,'eligendi','id alias id ratione','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(73,'ea','temporibus dolorem consequatur eos','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(74,'doloribus','aut ea omnis repellendus','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(75,'iste','a dignissimos ullam quidem','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(76,'nulla','explicabo est qui labore','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(77,'dolorum','voluptas excepturi voluptates et','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(78,'temporibus','molestias neque rerum pariatur','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(79,'laborum','rem fuga officiis magni','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(80,'corrupti','est voluptates et amet','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 06:30:01'),(81,'Admin Sistema','Administrador del sistema','A',NULL,NULL,'2022-01-13 03:51:29','2022-01-13 03:51:42'),(82,'nuevo rol','nuevo rold e pruebaaaasadasdsd','A','sistemas@padinsolutions.com','sistemas@padinsolutions.com',NULL,NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguimientos`
--

DROP TABLE IF EXISTS `seguimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguimientos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `atencion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `toque` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `respuesta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` timestamp NOT NULL,
  `prospecto_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seguimientos_prospecto_id_foreign` (`prospecto_id`),
  KEY `seguimientos_user_id_foreign` (`user_id`),
  CONSTRAINT `seguimientos_prospecto_id_foreign` FOREIGN KEY (`prospecto_id`) REFERENCES `prospectos` (`id`),
  CONSTRAINT `seguimientos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguimientos`
--

LOCK TABLES `seguimientos` WRITE;
/*!40000 ALTER TABLE `seguimientos` DISABLE KEYS */;
INSERT INTO `seguimientos` VALUES (1,'consequatur et nostrum dicta','veritatis asperiores repellat magnam','voluptas consequatur numquam voluptatibus occaecati non hic perspiciatis','2022-01-13 01:46:09',1,1,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'pariatur sit est quasi','officia reprehenderit recusandae aut','et quasi veritatis totam sit totam inventore omnis','2022-01-13 01:46:09',2,3,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'reprehenderit vel nesciunt nemo','vel aut architecto fugiat','nisi aut qui minima qui vel laboriosam aspernatur','2022-01-13 01:46:09',3,5,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'mollitia autem ut quam','praesentium sunt nostrum odio','alias ex qui et nostrum occaecati et et','2022-01-13 01:46:09',4,7,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'et distinctio iste expedita','officiis dolorem fugit rerum','iste officiis et ipsam voluptas eum veritatis neque','2022-01-13 01:46:09',5,9,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'in dolorem inventore quo','repellendus necessitatibus voluptates et','ducimus quas sunt dolorem minima deserunt nemo non','2022-01-13 01:46:09',6,11,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'dolore voluptas omnis labore','odit repellat maxime cupiditate','aut repellat ex sed quis qui ipsum nisi','2022-01-13 01:46:09',7,13,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'ipsam in enim harum','ut omnis laboriosam asperiores','doloribus repudiandae unde necessitatibus in fuga error velit','2022-01-13 01:46:09',8,15,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'libero modi ad totam','tenetur optio earum sit','rerum et deserunt aut tempore repellendus sit nemo','2022-01-13 01:46:09',9,17,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'tenetur dolore id et','impedit officiis error asperiores','ut quaerat maiores sit magni est cupiditate assumenda','2022-01-13 01:46:09',10,19,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09');
/*!40000 ALTER TABLE `seguimientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turnos`
--

DROP TABLE IF EXISTS `turnos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `turnos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `turnos_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turnos`
--

LOCK TABLES `turnos` WRITE;
/*!40000 ALTER TABLE `turnos` DISABLE KEYS */;
INSERT INTO `turnos` VALUES (1,'ut cum','numquam deserunt aut nobis','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'similique modi','sunt voluptatem beatae accusamus','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'in alias','error neque quo ea','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'quia laboriosam','nemo laborum modi reprehenderit','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'sit rerum','qui accusamus totam itaque','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'ut porro','beatae iste consequuntur dignissimos','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'reiciendis amet','porro sint illum molestiae','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'dicta excepturi','aut velit optio animi','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'iure optio','maxime veniam enim rerum','A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'ea repellendus','porro et nulla recusandae','A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(11,'Mañana','Alumnos que estudian en la mañana','E',NULL,NULL,'2022-01-13 03:58:37','2022-01-13 03:58:37'),(12,'Noche update','Turno noche','A','sistemas@padinsolutions.com',NULL,NULL,NULL);
/*!40000 ALTER TABLE `turnos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialities` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rol_id` bigint unsigned NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_at` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateinsert` timestamp NULL DEFAULT NULL,
  `dateupdate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_api_token_unique` (`api_token`),
  KEY `users_rol_id_foreign` (`rol_id`),
  CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','Sistemas','Padin','sistemas@padinsolutions.com','2022-01-13 01:46:09','$2a$10$f1FTYLkrA8djacqcbOXRRuOnmH3LspLBaa26rTLBWzdWnTHZg9pBe','MPgM0tpmG6FjQW2DvTsVx2XXzAq3uEPzuEQnWzWc6SLjNdx28RXlM25qmKAw','BGrdxeafIzLoHBl97toNaCnhLjtxtXRI7iioA9Yy1523LiaHntmHRRERGStr',1,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(2,'Kenna Mertz','Boyer','et ducimus similique','ktoy@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','O7Xs1NjQ5BZQj7lFriwDzEoLR3ATMQTa6B7pMR5Q0sIdeKB7VAGjHZ7z7AqK','RU6CCq2zGL',2,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(3,'Marcella Batz II','Jakubowski','in vitae facilis','nelda.johns@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','SYp3CfcnaaGNf9t9LWOw1KqHkYJc8RzYMljz3UObgjiSBmBwQu20QFjHUvnb','PxkjKqQDVT',3,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(4,'Laverne Kuhic','Schuppe','dolor illo qui','tremaine.cole@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Ejqz9a96puAQhAjXDMsbMjgXZwpNijKsldGs4TfIGV5qSJQijwsSKMyRduf6','n9wfF1gK5q',4,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(5,'Marlee Lowe','Cremin','eum quidem aut','cummings.tianna@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','QCnuJj9Oarn7cdoDtUzNxkqLfSDwLGWfmu4VR9JLbTM7N1A4LDWNWh7AgHN5','opCGgrLsKc',5,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(6,'Chloe O\'Hara','Johnston','esse pariatur architecto','prosacco.elda@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','pExKhgRdRPxx84iOvpmTlSJbKKXzkAo9favWPVDJoSYIwlaipAuo8QCtxgUt','Mab5yl015h',6,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(7,'Mrs. Verdie Davis','Metz','minima officia perspiciatis','vfeest@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','vYLsnPjdXQKem8aJr8G5O5JbXpEvzGJ5XWUC08BN453ufmlXfITDqn0EeZCM','Gvf6PiWeSj',7,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(8,'Mrs. Freeda Windler Jr.','Schiller','dolorum excepturi consequuntur','alessia81@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','sLv29nJ7OxRPgQBl58xRujNMeozCE6UJKYdixtgEuejoxF0b3V2J6cPXrktj','Fyj6AZ8slU',8,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(9,'Florine Balistreri','Brown','quam dolorum et','daniela.muller@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','O6xJwCU1wCYYiRJeXsf42kY7lbPwLsisaKT4FwJuFHg3TNUVXd3ONSlsxbXO','NghyiZLZ93',9,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(10,'Ms. Ariane Tremblay','Stiedemann','qui illum necessitatibus','aweber@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','drWzFZqnA0wZqGM2sEOouf7ouFulMMvxK0hodjCE1zY9tEntYVGoHKzfeDy7','2ofQNOQV6u',10,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(11,'Rasheed Swaniawski','Schulist','sit repellendus sunt','usawayn@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','WDntyNA7dFQsESE97fPw8d1AapCnuuKwG19kF3joyS3WHR2vlHNsSB16R9ax','ZNnBSYVWP4',11,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(12,'Morgan Effertz','Price','aspernatur molestiae dolorum','charles.walker@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','HeqNem3ctBcLoecADIFj04df7d56xnr2FNRY103ZHxeywufMcwgmnShuQMF5','OJEr00YFrm',12,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(13,'Mr. Clemens Blanda','Corkery','dolor dolore rerum','ashanahan@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','diTJC327uYdIAr9V9E8yNlUKEdmwul4DE3q2Ni9a8WpXZa1KSfsCVL7RcS7o','0AX4dAl15G',13,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(14,'Prof. Wade Tromp DDS','Jacobi','numquam qui qui','bennett19@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','UlTzPl68isUYSs9rus9jC0Fbf74mewL4RVrZZP0lkHsoIv819UU0fDYDODU6','GRzYI51XqP',14,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(15,'Prof. Sarai Schaefer IV','Kunde','cupiditate laboriosam unde','terry.polly@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','CDN9DrIZ8R8V5Ns0MzEvkfGxxH9bweK9uD0P8NexU68c6TtWft94fOyV9c0U','u1Z07AWeJV',15,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(16,'Prof. Lisette Wolff','Willms','illo at alias','azieme@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','dqTlzrlgzQFgFpHlwlGG882yjOa73KWQjPINdfMqNps7piRcyFtWE4BZRkfg','O48zZTgW5B',16,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(17,'Michelle Wisozk','Casper','ut et iure','annabelle12@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','WdBv9E2QBIITzXcmlvWAn6Tsn7k7mpticT55l2EQ9YuMX92chqZhnRG1dN1S','NlseCWpW73',17,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(18,'Ceasar Heller','Pfeffer','minima labore ut','abel.jenkins@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','w9bT5qeBIWewQBApyhhjFFF2Sidey6nnRKENM3HjNFBdywLMkT37WkeYcux3','sqdJC3Ffr9',18,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(19,'Prof. Keira Goodwin DDS','Kutch','dolorem consequuntur fuga','tristian06@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','YV5xDnixWg9qsbGzj6rgRRx99rYVmjTncMK7jrY5ffB4kavl15q5T2sYvkWt','jk4mgqbs5P',19,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(20,'Georgiana Raynor','Reinger','dolorem quia eos','nwintheiser@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','cKcOjoFB1ek2m2XxOmMmN7kVEb6qUwjxzSjpBpLTRLBvxJQoiSyZ8GnJAHmf','hyIng2FvX8',20,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(21,'Margret Davis','Morissette','perspiciatis ea repudiandae','oconroy@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Z6LswbxaNrGIXCOJ1RtFzLJt2EDS0PYaLfujeW39nZIoH4jrdSdBY2E338PM','RPhmD0NXVc',21,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(22,'Robin Boehm','Smith','deleniti natus harum','rdavis@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','52gedp0j3Nh36bNv9M2FqTA9cA9yTm9bDbIyjTVCeLZFrNX92jH4AbcW7rDo','GfGfCFjOKz',22,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(23,'Reagan Harris','Cole','nisi et non','kunze.robyn@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','8OeJfMYdHJS7T8D4H1QT7Fe2dX0khhMCiPxVceSKMQlqoLavMlyH2qkhP7et','Jwz1coOxKg',23,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(24,'Carroll Rowe','Luettgen','sint laborum animi','marlee.zemlak@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','FkToWsQfM77ghUMRn5hBBhiAGk4eLeowANbSXwLlHvVq6Qa6ynjZmD5OywyG','GBofGypxSb',24,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(25,'Ms. Gabriella Nitzsche','Renner','id fuga iste','prosacco.dedric@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','JzwmtxXyK0KlATjP3Mb9UcR8fNFfhNtAErTQYfco0x5jJqleSDLAIeKhDJqE','swLuMF1AL3',25,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(26,'Harry Ryan MD','Haley','impedit perspiciatis minus','cordell74@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','MmgCm2EK2foCqvBpmgflJRBYovAKbpHT3dr1jj3iv9lqpkP4Hdyhynmj42sW','dpUHtxKi7H',26,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(27,'Elvie Goodwin','Boyer','qui ut minima','rutherford.kaylie@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','IK2LbtR2CwymGDIxXqRMLII9xS4ePHZC1SXGkslHcr7RrGZtMesu6tJpIKy3','sydsFmVcPz',27,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(28,'Mr. Isac Deckow','Stracke','aspernatur dignissimos velit','schmeler.deborah@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','cZnHGQ3eBrUnpYfj7V3QbMPfprFUtGjN00KC2zPFUR8IxpW6Ri2xptz0yAG1','QBmnCuHtyP',28,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(29,'Miles Hickle','Rempel','modi quas doloremque','mckenzie.rahul@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','UusLZ2Ilsq9QPbjySKtaU6KRP3juQ2WUVslSGg8fgXNwYRKPtgqlAfqYkIHS','8AN5bVr1iD',29,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(30,'Dr. Kacey Bogisich DDS','Price','magni et eligendi','xweber@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','pCeZJfXlazIORX6r1476A2l6kqespmYVKwDRevK3d12xoa36UynY3He8qUza','yaLbg3WBia',30,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(31,'Dayne Smith Jr.','Jones','molestiae dolores distinctio','blanda.angus@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','DDrFsW5uG9sp1ktWWkPijGDmBpUCJtz4feg3mYHIMA3pETEXoYgUdxIMb0mX','W7JoO3LBos',31,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(32,'Dr. Mackenzie Hayes Sr.','Kuvalis','cupiditate quod perspiciatis','madelyn.jenkins@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0brZvKD3XM2Gt72XAroRfWpaLwH0EMBTmoRKvFyPxxkoQi4NeBgNfNNQ2Uwl','3amQMulSpT',32,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(33,'Prof. Chaim Jaskolski','Orn','repellendus rerum eaque','runolfsdottir.myah@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','eMjyfmytAI8ABZnS5u1YpW2WBp8m8Qd41Voi2AkZD24bEtdyxp1QJc9bNPkv','19EsuZyJUA',33,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(34,'Mortimer Baumbach','Jerde','possimus et ullam','gay99@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','oGA7GYNhZVjCnGr3XkjLXp7v0z87oU4KAKz1KF23DN8YIMoeEJXdCTn8pweT','sm2k4XAjv9',34,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(35,'Celia Towne','Mills','impedit dolore aut','estelle41@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','hAGu2f2b9TIePtpw0c1RY49iDiAHorzH36qwNz31aqvpkItDdWA5WC8WIkoQ','R0H2TPFWTv',35,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(36,'Kelsi Mohr','Carter','similique sit velit','grady.rafael@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','pym6i309elly6FXWrb8RTb8pHRrp2gRD6s5tcU0fGU98A8CTZ92eMP82KAS1','UbXVVD16YD',36,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(37,'Orpha Wehner','Gibson','debitis tenetur nihil','clangworth@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','QoPxqvRItXrpJB3yItoKO8A38n5tt0ALdF2TeilCAMlkzk4I45Ljv9b2UosA','b1eiqwWbaS',37,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(38,'Jett Pollich','Veum','nihil sit temporibus','kendall65@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','PKyVGsdeLwTajbXMVuAaRNJl12CyPXYUiFhclE4pmVQwmK5PeF87u47mJ633','mI1IArVHz1',38,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(39,'Kory Collier','Boyle','exercitationem debitis veritatis','izabella.mckenzie@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','A7GZvWfruLv8iPtWUK2hCgKpGHFSOuaRSKGKvNxLm7JLP1w7JEabN6xcvsGJ','U3jW9xfnO6',39,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(40,'Maddison Mueller','Lebsack','magnam eum vel','cassin.billy@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','ugbAQSsHu4oCED6XoRrTUodKso2f7IkiE6BKSZiFUbdeyw0StqePMBqlgY7D','a1s0xtwzDt',40,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(41,'Stephanie Crooks','Goyette','sit numquam ea','boehm.clarissa@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','tVrTbdyjw8ZvPHAj2GSM3OlTIQXqSkl1GzMwGfijhmJEk5mBHkWbAVA14MBR','AmlOMNn8Dc',41,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(42,'Dovie Zieme DVM','Hickle','facilis sed autem','pollich.jovan@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','XUZzc6okYBBLfEUQOhiGiu0livHFT90biLGSyWGJvWIagHZU66pgWSA6VDhA','Xv45wo3PyU',42,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(43,'Miss Susan Waters','Stracke','ut expedita sed','mohr.nella@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','UyX9uQlvV4yEEiK7JqpY2IVfUYMNNjWRVySxsoXRBD1j0VatF5sXfwwC7yVI','76irzwhXDW',43,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(44,'Allen Will','Feeney','tempora voluptatem dolorum','jo.heathcote@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','hLQCN1qfyhM2HdzKcVyOQb7VPQPPd2DBFquWWfQOhtH8IumIlfHStJtQIhKo','hOcvbWLX13',44,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(45,'Matilda Paucek','Kulas','quia sit voluptatibus','rgislason@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','qtWBy202sFKMOs0rEkDOUna686YlodBpJzH4NaZfdnByKqUuEJXFm5cUzTsv','1ICCU3oNWo',45,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(46,'Queen Mante','Weber','consequatur illum sit','tina.goyette@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','hNAJNhYkvYbTF5B6RUOBrfoFVJ3oWEmxybsjWvdUn5trIEd8VUWsWsnKcxHm','hbxTo0Law3',46,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(47,'Dr. Eloy Schinner','Sauer','voluptatum ullam rerum','bauch.randi@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','V0paqyMYt7RE2Xz8R3uBuDzy7O9SS7zltGoWFS0pj1sWgzqr2JeToZTOmHS1','h8zOyKb6wV',47,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(48,'Evalyn Hahn','Towne','porro quod ea','zakary.ebert@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','o5NSIiXVHaVkB1uWUSHvLfYp7kSeJSiTX4SbkVoX8h6Orj7E02FGUxxVubB6','OchyoDakzb',48,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(49,'Prof. Reece Miller DVM','Conroy','sequi ad rem','clittel@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','jaViggU29bsmXzx7haX2drWUXrn4BbS66c07jp3Mvak43MRGECLlADkPu3Ul','Ac2vj8Gn1X',49,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(50,'Gerardo Bode','Hirthe','quas aperiam veritatis','qschmidt@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','3OsP0YXUBVnYauTCycG0MupZzspSkelM0JD1ZLXgK0ekBDZLXScECkKuCtVf','ybUChS2MEG',50,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(51,'Mrs. Estelle Hettinger','Pouros','quia consequatur odio','queenie79@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','WCnNfchzLwjjZ5Fw6t3WdnnzJS2h1buRvKHp7wioqJ9yVfalHdvi44c1gMeB','ZTVad31l0j',51,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(52,'Suzanne Leuschke','Gaylord','aut aperiam ut','cassandre75@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','cQoXC1Y8ZCEyIapSoSikNCvUNlJu8j8GOYp8fXptrHrpDGOUL9rN7m4xoYqS','VGjlWBM2kk',52,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(53,'Dr. Nathanial Marquardt DVM','Graham','facilis alias id','eortiz@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','VBHokl5Gibz0z8oPQk6uTLcRpk2NY2DyxDH5OfE94MbMINgZ3iNYGIVHOpBJ','vve7Kxz8FG',53,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(54,'Haley Steuber','Zboncak','accusantium earum necessitatibus','tremblay.matt@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','a5j4VP29ofpKAD8CutkPG3P45MMsda7BWW7ENQ0EJMp39TS10cUghitqnWf9','FVYoBvIqqa',54,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(55,'Prof. Lambert Muller III','Okuneva','nihil ab provident','beer.herminio@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','2vOHjAkwP5cVqjOsGSolWLXIjAasDEGGdUxWapk0VlNCLdbXui46U8btglVj','xBcsxtZvVW',55,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(56,'Natalia Stokes','Ziemann','aliquid velit deserunt','ahmad57@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','mGKdfl1uhF4nNioCDCVvoaxuRpbAbCaEVEITdWIsCJBjTeWwEnFBvFlGQlaC','09BpDWimPN',56,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(57,'Pearl Feil PhD','Kirlin','inventore reiciendis voluptas','pgreenholt@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','ZK6wCVZB8PK3pNTt9QXEzEsXkXyR6lnGOHSyMULyV2MA3HsNPGiwWgQAn37e','kCosQGRyhw',57,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(58,'Mr. Janick Lowe','Pouros','ut odit harum','white.meggie@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','8FQK73dVecEJBBtErYUYZW6E1sXFuqKWLZOhvLaSxjlHWyC0csdXFDWhr8ia','Dknk6D4PPo',58,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(59,'Marlon Gleason','Crooks','dolorem praesentium est','lupe.stokes@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','PfPOfVXyBzhcZpQRsxdzESQLSDTARWhmzU3Whsb93U9ZVY9BBXoQiImqbBKK','CVuiU2TGo1',59,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(60,'Prof. Junior Hahn I','Barton','et quia voluptatem','darrion.lubowitz@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Rl8pEQWkc8h2JbPfFzo8oSRLO5S2V07tkFwXBWpAzuh8Vue1KRBAGGylsNy7','bjGSVIzfF9',60,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(61,'Nicholas Cole','Cruickshank','dignissimos et quaerat','friesen.chester@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','DMe85Z6XzJcMFVFKcYUFeGcuXK8mi1NEkoa9pxZkjU4OjiXUXjoE8JmBhZGx','CRNT5NwyE0',61,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(62,'Candida Leannon','Schmeler','dolore similique maiores','nora.schaden@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','ksW4By0HEh5Gb15nNDb8nANvWjsxT0qLhEEtlGZuBznJGhNNw41nUoNGqZvF','sr9vtgmpvO',62,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(63,'Dr. Cara Kiehn','Osinski','et occaecati sunt','ari68@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','XUkNPHSrOJW5tqAH5ExXzdUEIeVKXGQShT15tZcu4YpFyfGKKwVX2cAuJoyC','kV062dL1ZO',63,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(64,'Deontae Jerde','Padberg','quo quam ab','satterfield.carlee@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','u4baHbYOc7Ml6eft4ZQRF8dzhs3MuVIkCBpsXDj0cACOeuQAHKqD1rbgyOgg','BfdJQQFIMY',64,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(65,'Omer Heidenreich','Reichert','quo sapiente laudantium','hester.kub@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','iAnV1IiR10nQNCLmU9DMKoLeDpZTjmaIGxzdixYrgBofmhtT4XuYIrsHASwn','7CPzRAKM8t',65,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(66,'Tiffany Lesch MD','McDermott','ipsum officia perspiciatis','yjacobs@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','oabrlgCHW90R13oMOhOk37LNRsUKjeM5NE6oP07K1U9FhWJGLKCw3PxEjLTc','wIwfG7FhKL',66,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(67,'Miss Mafalda Schroeder I','Ritchie','numquam incidunt vel','udonnelly@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','utQ0yfZugsBW83HV6yzdAA58T1DYPIY6O4q5oOMNxUCyAkuHDyoFMvaFBJnv','4jS5xIImyO',67,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(68,'Verdie Smith','Ernser','corrupti dolorem molestias','brannon21@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','CB2DuXqjoIDGFyWj411lVSjs8fExv139N0t15YnHoFRd2KR2rXDLTz3qRPPW','3JtqCAvtNd',68,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(69,'Bessie Rau','Conroy','omnis magni possimus','ruth.jacobi@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','hq1ZwczBfwywYPhMqjbScM6Yh4YkH9UcU98pXwuCsWreGgeXJIfj1N0jw3oC','4jZqfm0F6f',69,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(70,'Evelyn Little','Hermiston','nesciunt aliquam quis','rrunte@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Hdv3gIiiPC1nXtroduDoYNkIk5jUIRsjrUCS89qxBIr7uJDeSIyGbS8IwNIW','n2qNduuWI5',70,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(71,'Ms. Mae Lakin Sr.','Wisoky','aperiam quis quis','klueilwitz@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Zrq6OwF9kuwjwKLXLJV7fygrWUachsCCTZ7xbRaCdhRjclI9Z5U3Yh68AMSO','J8Btv1qnDC',71,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(72,'Rhoda Wolf I','Kozey','at deserunt consequatur','katarina47@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','NndAO0mLZO3ebn87QBtJKpwCVz0YrLzzEqI56NMSGaUFoIHCUHCsOTS7iO4E','unj66z6Zg8',72,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(73,'Leif Stroman','Mills','odio sit magni','jacobson.rahul@example.net','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','ifoBGjzLDyxKdvKVOu7pEb8C3NSFIL1XIm71lLxSEIXxv0qW55MZKfZVmaiK','PzjFB9h5uN',73,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(74,'Arjun Morissette','Klocko','quia odio ut','metz.malinda@example.org','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','noCvSbBuH8U38vHM997qtyIMv7zgiPGFQjjfwHdFNGxBDvELgi3W0EHaKgnV','k9nJN8mdVu',74,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(75,'Emerson Schimmel Sr.','McGlynn','non assumenda eum','nborer@example.com','2022-01-13 01:46:09','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','0WIdpE6KDFVAXGmMpW8fk2BXYIK2j2RcGhgeJt8NaskblyqWitk7MbBq1v9a','dnJmXXP3sz',75,'A',NULL,NULL,'2022-01-13 01:46:09','2022-01-13 01:46:09'),(76,'Holly Halvorson','Jakubowski','est quam consectetur','hailey.glover@example.com','2022-01-13 01:46:10','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','yTO6qsKguicA3WHmTqRYU5LKTCxosyM7TYeEjpJb8CWLK31YEG5jdvUyvCIP','5o3mFW4QaS',76,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(77,'Daisy Fay','Jacobi','non est alias','sklocko@example.net','2022-01-13 01:46:10','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','8sPRPKWSp22NBKUhlsnTF9EyHlE5rCgFoahat88bSZQFDv6Bda5jmtC7JclS','yrz8apKnqg',77,'A',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 01:46:10'),(78,'Zackary Connelly DDS','Grady','odio cumque quod','wilkinson.ressie@example.org','2022-01-13 01:46:10','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','HnsTyn99msoP25f1nwdLrWxe1RVzdEGily1ZujLvju0tP24prBlfYzGihVvF','OY6n21xV7L',78,'A',NULL,'2024-12-19 16:29:58','2022-01-13 01:46:10','2022-01-13 01:46:10'),(79,'Madge Nitzsche','Marvin','praesentium aut et','brigitte.wehner@example.com','2022-01-13 01:46:10','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','sQ2TWUFM6LlULIuWhPHQESenmxAtk0hoIF11XJ0Af8UWyBzOB4o1aH1yi6nW','nPpMnGwJ4w',79,'A',NULL,'2024-12-19 16:29:57','2022-01-13 01:46:10','2022-01-13 01:46:10'),(80,'Beulah Hermiston DVM','Littel','esse et est','fabshire@example.org','2022-01-13 01:46:10','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','5qvhfOlBtmn4AP6E9PBAk2FS04J97joG99cOCz31BqvJZYWT3Hh8QJHJfOSt','Tcf2nw2WfZ',80,'E',NULL,NULL,'2022-01-13 01:46:10','2022-01-13 03:28:29'),(81,'Abel','Palomino Rojas',NULL,'abelpr_@gmail.com',NULL,'$2y$10$6cpywCIToahFupa/FtlMhu7..sXZC5lAi0quvV9x3m0rX96U6GnIi','qPwx0j1OvpzWPEcC4MtjhdnjXdhQYeVdqQtLS5oWGPgxBgxchVNRo54O8Q2l',NULL,81,'A',NULL,NULL,'2022-01-13 03:57:17','2022-01-13 03:57:17'),(82,'yhonatan r','fernandez','--','rf@gmail.com',NULL,'$2y$10$gDoBX0WGLgNJ4rT8R0zVquDxmPOqM5EE028oF0895j1KecB7pKCQW','$2y$10$ZKkl5WMhkEmYme34t0tGQuMH9wksDzTPDsS.V.fQOQ8zhBHNGDpOO',NULL,82,'A','2024-12-19 16:29:35','2024-12-19 16:30:03',NULL,NULL),(83,'Administrador','Sistema','---','admin@gmail.com',NULL,'$2y$10$71Lp5c9WvENPNo1r58GHjugfXJbXl7R0h9Ns2dHR6.M4haAoFT1Mq','$2y$10$M1knHFI2o7lUwBYilhNikur47xYwtw8NA3MrUUgd1.OpO9qgo3KMi',NULL,1,'A','2024-12-20 18:13:22','2024-12-20 18:13:22',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'siscolorista'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-08 16:37:18

-- --------- tabla asistencias
-- ---------
-- ---------
-- ---------

CREATE TABLE `asistencias` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `alumno_id` BIGINT UNSIGNED NOT NULL,
  `fecha_asistencia` TIMESTAMP NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE
);


ALTER TABLE alumnos 
ADD COLUMN `curso` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `asesoracargo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN  `dni` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `distrito` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `celular` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `empresa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `cargodesempenia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `colegiosegundario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `inicioclases` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `turno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `pago` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
ADD COLUMN `fecha_inscripcion` date NULL;
