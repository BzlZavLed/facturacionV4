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
-- Table structure for table `usocfdi_catalogo`
--

DROP TABLE IF EXISTS `usocfdi_catalogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usocfdi_catalogo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(4) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `p_moral` int NOT NULL,
  `p_fisica` int NOT NULL,
  `estado` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usocfdi_catalogo`
--

LOCK TABLES `usocfdi_catalogo` WRITE;
/*!40000 ALTER TABLE `usocfdi_catalogo` DISABLE KEYS */;
INSERT INTO `usocfdi_catalogo` VALUES (1,'G01','Adquisición de mercancías.',1,1,1),(2,'G02','Devoluciones, descuentos o bonificaciones.',1,1,1),(3,'G03','Gastos en general.',1,1,1),(4,'I01','Construcciones.',1,1,1),(5,'I02','Mobiliario y equipo de oficina por inversiones.',1,1,1),(6,'I03','Equipo de transporte.',1,1,1),(7,'I04','Equipo de computo y accesorios.',1,1,1),(8,'I05','Dados, troqueles, moldes, matrices y herramental.',1,1,1),(9,'I06','Comunicaciones telefónicas.',1,1,1),(10,'I07','Comunicaciones satelitales.',1,1,1),(11,'I08','Otra maquinaria y equipo.',1,1,1),(12,'D01','Honorarios médicos, dentales y gastos hospitalarios.',1,0,1),(13,'D02','Gastos médicos por incapacidad o discapacidad.',1,0,1),(14,'D03','Gastos funerales.',1,0,1),(15,'D04','Donativos.',1,0,1),(16,'D05','Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación).',1,0,1),(17,'D06','Aportaciones voluntarias al SAR.',1,0,1),(18,'D07','Primas por seguros de gastos médicos.',1,0,1),(19,'D08','Gastos de transportación escolar obligatoria.',1,0,0),(20,'D09','Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones.',1,0,0),(21,'D10','Pagos por servicios educativos (colegiaturas).',1,0,0),(22,'S01','Sin efectos fiscales.',1,1,0),(23,'CP01','Pagos',1,1,0),(24,'CN01','Nómina',1,0,0);
/*!40000 ALTER TABLE `usocfdi_catalogo` ENABLE KEYS */;
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
