-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--
-- Host: localhost    Database: facturacion
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
-- Table structure for table `emisor`
--

DROP TABLE IF EXISTS `emisor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emisor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `razon_emisor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfc_emisor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regimen_emisor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `c_postal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bunit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_emisor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zona` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `versionDonataria` float(7,1) DEFAULT NULL,
  `leyendaDonataria` mediumtext COLLATE utf8mb4_unicode_ci,
  `fechaDonataria` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permisoDonataria` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emisor`
--

LOCK TABLES `emisor` WRITE;
/*!40000 ALTER TABLE `emisor` DISABLE KEYS */;
INSERT INTO `emisor` VALUES (1,'Benjamin Zavala','ZALB930802QD2','603','29010','S18','benjaminzavala74@gmail.com',NULL,'2022-05-20 04:07:15','2022-05-20 04:52:33',NULL,NULL,NULL,NULL),(2,'Filantropica y Educativa del Noreste','FEN1708920GH2','615','67515','FEN','benjaminzavala74@gmail.com',1,'2022-05-20 04:08:33','2022-05-25 07:22:04',1.0,'Esto es un donativo con un texto muy largo que no se que significa pero si lo termino me puedo dormir para probar que si funciona se debe de guardar esto en la base de datos para validar que todo esta de manera correcta','2012-05-01','ETR-A1'),(4,'Benjamin Zavala','ZALB930802QD3','604','29015','S18','benjaminzavala74@gmail.com',NULL,'2022-05-20 04:53:08','2022-05-20 04:53:08',NULL,NULL,NULL,NULL),(5,'Filantropica y Educativa del Noreste','FEN1708920TR3','606','67515','FEN','benjaminzavala74@gmail.com',1,'2022-05-25 03:56:43','2022-05-25 07:23:04',1.0,'Esto es un donativo con un texto muy largo que no se que significa pero si lo termino me puedo dormir para probar que si funciona se debe de guardar esto en la base de datos para validar que todo esta de manera correcta y agregamos mas texto para seguir probando la longitud de este dilema antes de dormir','2013-08-12','TRE35G/A');
/*!40000 ALTER TABLE `emisor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-02 13:11:52
