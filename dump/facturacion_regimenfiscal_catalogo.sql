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
-- Table structure for table `regimenfiscal_catalogo`
--

DROP TABLE IF EXISTS `regimenfiscal_catalogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regimenfiscal_catalogo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(3) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion` varchar(300) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `estado` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regimenfiscal_catalogo`
--

LOCK TABLES `regimenfiscal_catalogo` WRITE;
/*!40000 ALTER TABLE `regimenfiscal_catalogo` DISABLE KEYS */;
INSERT INTO `regimenfiscal_catalogo` VALUES (1,'601','General de Ley Personas Morales',1),(2,'603','Personas Morales con Fines no Lucrativos',1),(3,'605','Sueldos y Salarios e Ingresos Asimilados a Salarios',1),(4,'606','Arrendamiento',1),(5,'607','Régimen de Enajenación o Adquisición de Bienes',1),(6,'608','Demás ingresos',1),(7,'610','Residentes en el Extranjero sin Establecimiento Permanente en México',1),(8,'611','Ingresos por Dividendos (socios y accionistas)',1),(9,'612','Personas Físicas con Actividades Empresariales y Profesionales',1),(10,'614','Ingresos por intereses',1),(11,'615','Régimen de los ingresos por obtención de premios',1),(12,'616','Sin obligaciones fiscales',1),(13,'620','Sociedades Cooperativas de Producción que optan por diferir sus ingresos',1),(14,'621','Incorporación Fiscal',1),(15,'622','Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras',1),(16,'623','Opcional para Grupos de Sociedades',1),(17,'624','Coordinados',1),(18,'625','Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas',1),(19,'626','Régimen Simplificado de Confianza',1);
/*!40000 ALTER TABLE `regimenfiscal_catalogo` ENABLE KEYS */;
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
