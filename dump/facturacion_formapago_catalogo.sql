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
-- Table structure for table `formapago_catalogo`
--

DROP TABLE IF EXISTS `formapago_catalogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formapago_catalogo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(2) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `estado` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formapago_catalogo`
--

LOCK TABLES `formapago_catalogo` WRITE;
/*!40000 ALTER TABLE `formapago_catalogo` DISABLE KEYS */;
INSERT INTO `formapago_catalogo` VALUES (1,'01','Efectivo',1),(2,'02','Cheque nominativo',1),(3,'03','Transferencia electrónica de fondos',1),(4,'04','Tarjeta de crédito',1),(5,'05','Monedero electrónico',0),(6,'06','Dinero electrónico',0),(7,'08','Vales de despensa',0),(8,'12','Dación en pago',0),(9,'13','Pago por subrogación',0),(10,'14','Pago por consignación',0),(11,'15','Condonación',0),(12,'17','Compensación',0),(13,'23','Novación',0),(14,'24','Confusión',0),(15,'25','Remisión de deuda',0),(16,'26','Prescripción o caducidad',0),(17,'27','A satisfacción del acreedor',0),(18,'28','Tarjeta de débito',0),(19,'29','Tarjeta de servicios',0),(20,'30','Aplicación de anticipos',0),(21,'31','Intermediario pagos',0),(22,'99','Por definir',0);
/*!40000 ALTER TABLE `formapago_catalogo` ENABLE KEYS */;
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
