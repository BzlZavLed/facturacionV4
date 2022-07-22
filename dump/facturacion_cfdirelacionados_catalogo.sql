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
-- Table structure for table `cfdirelacionados_catalogo`
--

DROP TABLE IF EXISTS `cfdirelacionados_catalogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cfdirelacionados_catalogo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(2) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion` varchar(250) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `estado` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cfdirelacionados_catalogo`
--

LOCK TABLES `cfdirelacionados_catalogo` WRITE;
/*!40000 ALTER TABLE `cfdirelacionados_catalogo` DISABLE KEYS */;
INSERT INTO `cfdirelacionados_catalogo` VALUES (1,'01','Nota de crédito de los documentos relacionados',1),(2,'02','Nota de débito de los documentos relacionados',1),(3,'03','Devolución de mercancía sobre facturas o traslados previos',1),(4,'04','Sustitución de los CFDI previos',1),(5,'05','Traslados de mercancias facturados previamente',0),(6,'06','Factura generada por los traslados previos',0),(7,'07','CFDI por aplicación de anticipo',0),(8,'08','Factura generada por pagos en parcialidades',0),(9,'09','Factura generada por pagos diferidos',0);
/*!40000 ALTER TABLE `cfdirelacionados_catalogo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-02 13:11:53
