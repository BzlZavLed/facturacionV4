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
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombreCliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razonCliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfcCliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emailCliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usoCfdiCliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personaFisicaCliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bunit` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'Benjamin Zavala','benjamin','ZALB930802QD1','benjaminzavala74@gmail.com','G01','fisica','2022-05-10 05:12:14','2022-05-10 05:12:14','FEN'),(2,'Benjamin Zavala','benjamin','ZALB930802QD2','benjaminzavala74@gmail.com','G01','fisica','2022-05-10 05:16:51','2022-05-10 05:16:51','FEN'),(3,'Andrea Penagos','Andy','PRSA981211DR3','andypenagos0110@gmail.com','G01','fisica','2022-05-10 06:07:56','2022-05-10 06:07:56','FEN'),(4,'Andrea Penagos 2','Andy','PESA981211DR4','andypenagos0110@gmail.com','G01','fisica','2022-05-10 06:14:20','2022-05-10 06:14:20','FEN'),(5,'Andrea Penagos Sanchez','Andy','PTSA981211DR5','andypenagos0110@gmail.com','G01','fisica','2022-05-10 06:15:22','2022-05-10 06:15:22','FEN'),(6,'Andrea Penagos Sanchez 2','Andy','PYSA981211DR6','benjaminzavala74@gmail.com','G01','fisica','2022-05-10 06:16:07','2022-05-10 06:16:07','FEN'),(7,'Andrea Penagos 3','Andy 3','POSA981211DR7','andypenagos0110@gmail.com','G01','fisica','2022-05-10 06:23:31','2022-05-10 06:23:31','FEN'),(8,'Andrea Penagos 4','Andy','PUSA981211DR8','benjaminzavala74@gmail.com','G01','fisica','2022-05-10 06:25:03','2022-05-10 06:25:03','FEN'),(9,'Andrea Penagos 5','Andy','PASA981211DR9','benjaminzavala74@gmail.com','G01','fisica','2022-05-10 06:25:56','2022-05-10 06:25:56','FEN'),(10,'Andrea Penagos','Andy','PESA981211DR4','andypenagos0111@gmail.com','G01','fisica','2022-05-10 06:35:07','2022-05-10 06:35:07','FEN');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
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
