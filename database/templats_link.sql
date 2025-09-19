-- MySQL dump 10.13  Distrib 9.3.0, for macos13.7 (x86_64)
--
-- Host: 127.0.0.1    Database: templats_link
-- ------------------------------------------------------
-- Server version	9.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `head_configs`
--

DROP TABLE IF EXISTS `head_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `head_configs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pagina` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'global',
  `tema` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'global',
  `meta_title` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `horario_atendimento` text COLLATE utf8mb4_unicode_ci,
  `endereco` text COLLATE utf8mb4_unicode_ci,
  `whatsapp` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_contato` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_footer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao_footer` text COLLATE utf8mb4_unicode_ci,
  `copyright_footer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gtm_head` text COLLATE utf8mb4_unicode_ci,
  `gtm_body` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_pagina_tema` (`pagina`,`tema`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `head_configs`
--

LOCK TABLES `head_configs` WRITE;
/*!40000 ALTER TABLE `head_configs` DISABLE KEYS */;
INSERT INTO `head_configs` VALUES (22,'global','main-Thema','Templats Link - Templates e Desenvolvimento Web Profissional','Plataforma completa para templates, soluções web e desenvolvimento de sites profissionais. Templates modernos, responsivos e otimizados para SEO.','templates, desenvolvimento web, sites, laravel, php, html, css, javascript, bootstrap, responsivo, seo','uploads/favicons/favicon-main.webp','https://youtube.com/templatslink','https://linkedin.com/company/templatslink','https://twitter.com/templatslink','https://instagram.com/templatslink','https://facebook.com/templatslink','Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h','Rua das Tecnologias, 123 - Centro, São Paulo - SP, 01234-567','+5511999999999','+55 (11) 99999-9999','contato@templats-link.com','uploads/logos/logo-main.png','uploads/logos/logo-footer.png','Somos especialistas em desenvolvimento web, oferecendo templates modernos e soluções personalizadas para sua empresa.','© {ano} Templats Link. Todos os direitos reservados.','<!-- Google Tag Manager --><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!=\'dataLayer\'?\'&l=\'+l:\'\';j.async=true;j.src=\'https://www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);})(window,document,\'script\',\'dataLayer\',\'GTM-XXXXXXX\');</script><!-- End Google Tag Manager -->','<!-- Google Tag Manager (noscript) --><noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript><!-- End Google Tag Manager (noscript) -->','2025-09-17 16:29:48','2025-09-17 16:29:48'),(23,'home','main-Thema','Início - Templats Link | Templates e Desenvolvimento Web','Bem-vindo ao Templats Link! Encontre os melhores templates para seu projeto web. Desenvolvimento profissional, responsivo e otimizado.','templates, home, início, desenvolvimento web, sites profissionais, responsivo','uploads/favicons/favicon-main.webp','https://youtube.com/templatslink','https://linkedin.com/company/templatslink','https://twitter.com/templatslink','https://instagram.com/templatslink','https://facebook.com/templatslink','Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h','Rua das Tecnologias, 123 - Centro, São Paulo - SP, 01234-567','+5511999999999','+55 (11) 99999-9999','contato@templats-link.com','uploads/logos/logo-main.png','uploads/logos/logo-footer.png','Somos especialistas em desenvolvimento web, oferecendo templates modernos e soluções personalizadas para sua empresa.','© {ano} Templats Link. Todos os direitos reservados.','','','2025-09-17 16:29:48','2025-09-17 16:29:48'),(24,'sobre','main-Thema','Sobre Nós - Templats Link | Nossa História e Missão','Conheça a história do Templats Link. Somos uma empresa especializada em desenvolvimento web com mais de 5 anos de experiência.','sobre, empresa, história, missão, desenvolvimento web, experiência','uploads/favicons/favicon-main.webp','https://youtube.com/templatslink','https://linkedin.com/company/templatslink','https://twitter.com/templatslink','https://instagram.com/templatslink','https://facebook.com/templatslink','Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h','Rua das Tecnologias, 123 - Centro, São Paulo - SP, 01234-567','+5511999999999','+55 (11) 99999-9999','contato@templats-link.com','uploads/logos/logo-main.png','uploads/logos/logo-footer.png','Somos especialistas em desenvolvimento web, oferecendo templates modernos e soluções personalizadas para sua empresa.','© {ano} Templats Link. Todos os direitos reservados.','','','2025-09-17 16:29:48','2025-09-17 16:29:48'),(25,'contato','main-Thema','Contato - Templats Link | Entre em Contato Conosco','Entre em contato com o Templats Link. Estamos prontos para ajudar com seu projeto web. Fale conosco por telefone, email ou WhatsApp.','contato, telefone, email, whatsapp, suporte, desenvolvimento web','uploads/favicons/favicon-main.webp','https://youtube.com/templatslink','https://linkedin.com/company/templatslink','https://twitter.com/templatslink','https://instagram.com/templatslink','https://facebook.com/templatslink','Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h','Rua das Tecnologias, 123 - Centro, São Paulo - SP, 01234-567','+5511999999999','+55 (11) 99999-9999','contato@templats-link.com','uploads/logos/logo-main.png','uploads/logos/logo-footer.png','Somos especialistas em desenvolvimento web, oferecendo templates modernos e soluções personalizadas para sua empresa.','© {ano} Templats Link. Todos os direitos reservados.','','','2025-09-17 16:29:48','2025-09-17 16:29:48'),(26,'login','main-Thema','Login - Templats Link | Acesso ao Painel Administrativo','Faça login no painel administrativo do Templats Link. Acesso seguro e rápido para gerenciar seu site.','login, painel, administrativo, acesso, segurança','uploads/favicons/favicon-main.webp','https://youtube.com/templatslink','https://linkedin.com/company/templatslink','https://twitter.com/templatslink','https://instagram.com/templatslink','https://facebook.com/templatslink','Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h','Rua das Tecnologias, 123 - Centro, São Paulo - SP, 01234-567','+5511999999999','+55 (11) 99999-9999','contato@templats-link.com','uploads/logos/logo-main.png','uploads/logos/logo-footer.png','Somos especialistas em desenvolvimento web, oferecendo templates modernos e soluções personalizadas para sua empresa.','© {ano} Templats Link. Todos os direitos reservados.','','','2025-09-17 16:29:48','2025-09-17 16:29:48'),(56,'401','Lumialto','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-18 14:42:54','2025-09-18 14:42:54'),(57,'404','Lumialto','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-18 14:42:54','2025-09-18 14:42:54'),(58,'contato','Lumialto','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-18 14:42:54','2025-09-18 14:42:54'),(59,'home','Lumialto','Teste','Teste','Teste',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-18 14:42:54','2025-09-19 18:51:08'),(60,'lanternagem-e-pintura','Lumialto','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-18 14:42:54','2025-09-18 14:42:54'),(61,'martelinho-de-ouro','Lumialto','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-18 14:42:54','2025-09-18 14:42:54'),(62,'mecanica-geral','Lumialto','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-18 14:42:54','2025-09-18 14:42:54'),(63,'sobre','Lumialto','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-18 14:42:54','2025-09-18 14:42:54'),(64,'footer','Lumialto','Footer - Lumialto','Página Footer do tema Lumialto. Configure as meta tags específicas desta página.','footer, lumialto, página',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-19 18:37:23','2025-09-19 18:37:23'),(65,'head','Lumialto','Head - Lumialto','Página Head do tema Lumialto. Configure as meta tags específicas desta página.','head, lumialto, página',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-19 18:37:23','2025-09-19 18:37:23'),(66,'nav','Lumialto','Nav - Lumialto','Página Nav do tema Lumialto. Configure as meta tags específicas desta página.','nav, lumialto, página',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-19 18:37:23','2025-09-19 18:37:23'),(67,'scripts','Lumialto','Scripts - Lumialto','Página Scripts do tema Lumialto. Configure as meta tags específicas desta página.','scripts, lumialto, página',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-19 18:37:23','2025-09-19 18:37:23'),(68,'app','Lumialto','App - Lumialto','Página App do tema Lumialto. Configure as meta tags específicas desta página.','app, lumialto, página',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-19 18:37:23','2025-09-19 18:37:23'),(69,'global','Lumialto','Lumialto - Site Profissional','Site profissional do tema Lumialto. Descubra nossos serviços e entre em contato.','lumialto, site, profissional, serviços',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-19 18:37:23','2025-09-19 18:37:23');
/*!40000 ALTER TABLE `head_configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `html_tags_especificas`
--

DROP TABLE IF EXISTS `html_tags_especificas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `html_tags_especificas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tema` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pagina` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_html` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `html_tags_especificas_tema_pagina_unique` (`tema`,`pagina`),
  KEY `html_tags_especificas_tema_pagina_index` (`tema`,`pagina`),
  KEY `html_tags_especificas_tema_index` (`tema`),
  KEY `html_tags_especificas_pagina_index` (`pagina`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `html_tags_especificas`
--

LOCK TABLES `html_tags_especificas` WRITE;
/*!40000 ALTER TABLE `html_tags_especificas` DISABLE KEYS */;
INSERT INTO `html_tags_especificas` VALUES (9,'Lumialto','sobre','<html data-wf-page=\"68c416a22b30085ae86c551e\" data-wf-site=\"68c415b9a393ddff965ffc67\">','2025-09-18 15:08:28','2025-09-18 15:08:28'),(10,'Lumialto','mecanica-geral','<html data-wf-page=\"68c416bbb7f0c73646924920\" data-wf-site=\"68c415b9a393ddff965ffc67\">','2025-09-18 15:08:28','2025-09-18 15:08:28'),(11,'Lumialto','martelinho-de-ouro','<html data-wf-page=\"68c416d56c53e02b1ca7846a\" data-wf-site=\"68c415b9a393ddff965ffc67\">','2025-09-18 15:08:28','2025-09-18 15:08:28'),(12,'Lumialto','lanternagem-e-pintura','<html data-wf-page=\"68c416edfd82a365bd85de69\" data-wf-site=\"68c415b9a393ddff965ffc67\">','2025-09-18 15:08:28','2025-09-18 15:08:28'),(13,'Lumialto','home','<html data-wf-page=\"68c415baa393ddff965ffce1\" data-wf-site=\"68c415b9a393ddff965ffc67\">','2025-09-18 15:08:28','2025-09-18 15:08:28'),(14,'Lumialto','contato','<html data-wf-page=\"68c41705c11fbbc98aa599a9\" data-wf-site=\"68c415b9a393ddff965ffc67\">','2025-09-18 15:08:28','2025-09-18 15:08:28'),(15,'Lumialto','404','<html data-wf-page=\"68c415baa393ddff965ffce3\" data-wf-site=\"68c415b9a393ddff965ffc67\">','2025-09-18 15:08:28','2025-09-18 15:08:28'),(16,'Lumialto','401','<html data-wf-page=\"68c415baa393ddff965ffce2\" data-wf-site=\"68c415b9a393ddff965ffc67\">','2025-09-18 15:08:28','2025-09-18 15:08:28');
/*!40000 ALTER TABLE `html_tags_especificas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_12_14_000001_create_personal_access_tokens_table',1),(2,'2025_09_16_192656_create_rotas_dinamicas_table',2),(3,'2025_09_16_191738_add_tema_column_to_head_configs_table',3),(4,'2025_09_18_091653_create_html_tags_especificas_table',4),(5,'2025_09_18_131500_create_tema_html_tags_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
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
-- Table structure for table `rotas_dinamicas`
--

DROP TABLE IF EXISTS `rotas_dinamicas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rotas_dinamicas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tema` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pagina` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome_rota` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `controller` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'TemasController',
  `metodo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'renderizarPaginaDinamica',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rotas_dinamicas_tema_pagina_unique` (`tema`,`pagina`),
  KEY `rotas_dinamicas_tema_pagina_index` (`tema`,`pagina`),
  KEY `rotas_dinamicas_tema_ativo_index` (`tema`,`ativo`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rotas_dinamicas`
--

LOCK TABLES `rotas_dinamicas` WRITE;
/*!40000 ALTER TABLE `rotas_dinamicas` DISABLE KEYS */;
INSERT INTO `rotas_dinamicas` VALUES (141,'Lumialto','401','/401','401','TemasController','renderizarPaginaDinamica',1,'2025-09-19 18:37:23','2025-09-19 18:37:23'),(142,'Lumialto','404','/404','404','TemasController','renderizarPaginaDinamica',1,'2025-09-19 18:37:23','2025-09-19 18:37:23'),(143,'Lumialto','lanternagem-e-pintura','/lanternagem-e-pintura','lanternagem-e-pintura','TemasController','renderizarPaginaDinamica',1,'2025-09-19 18:37:23','2025-09-19 18:37:23'),(144,'Lumialto','martelinho-de-ouro','/martelinho-de-ouro','martelinho-de-ouro','TemasController','renderizarPaginaDinamica',1,'2025-09-19 18:37:23','2025-09-19 18:37:23'),(145,'Lumialto','mecanica-geral','/mecanica-geral','mecanica-geral','TemasController','renderizarPaginaDinamica',1,'2025-09-19 18:37:23','2025-09-19 18:37:23');
/*!40000 ALTER TABLE `rotas_dinamicas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tema_html_tags`
--

DROP TABLE IF EXISTS `tema_html_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tema_html_tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags_html` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tema_html_tags_tema_unique` (`tema`),
  KEY `tema_html_tags_tema_index` (`tema`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tema_html_tags`
--

LOCK TABLES `tema_html_tags` WRITE;
/*!40000 ALTER TABLE `tema_html_tags` DISABLE KEYS */;
INSERT INTO `tema_html_tags` VALUES (1,'Lumialto','{\"401\": \"<html data-wf-page=\\\"68c415baa393ddff965ffce2\\\" data-wf-site=\\\"68c415b9a393ddff965ffc67\\\">\", \"404\": \"<html data-wf-page=\\\"68c415baa393ddff965ffce3\\\" data-wf-site=\\\"68c415b9a393ddff965ffc67\\\">\", \"home\": \"<html data-wf-page=\\\"68c415baa393ddff965ffce1\\\" data-wf-site=\\\"68c415b9a393ddff965ffc67\\\">\", \"sobre\": \"<html data-wf-page=\\\"68c416a22b30085ae86c551e\\\" data-wf-site=\\\"68c415b9a393ddff965ffc67\\\">\", \"contato\": \"<html data-wf-page=\\\"68c41705c11fbbc98aa599a9\\\" data-wf-site=\\\"68c415b9a393ddff965ffc67\\\">\", \"mecanica-geral\": \"<html data-wf-page=\\\"68c416bbb7f0c73646924920\\\" data-wf-site=\\\"68c415b9a393ddff965ffc67\\\">\", \"martelinho-de-ouro\": \"<html data-wf-page=\\\"68c416d56c53e02b1ca7846a\\\" data-wf-site=\\\"68c415b9a393ddff965ffc67\\\">\", \"lanternagem-e-pintura\": \"<html data-wf-page=\\\"68c416edfd82a365bd85de69\\\" data-wf-site=\\\"68c415b9a393ddff965ffc67\\\">\"}','2025-09-18 16:34:49','2025-09-18 16:34:49');
/*!40000 ALTER TABLE `tema_html_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temas`
--

DROP TABLE IF EXISTS `temas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `temas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `preview_path` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `arquivo_path` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `ativo` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temas`
--

LOCK TABLES `temas` WRITE;
/*!40000 ALTER TABLE `temas` DISABLE KEYS */;
INSERT INTO `temas` VALUES (28,'Lumialto','lumialto',NULL,'Lumialto',1,'2025-09-18 15:08:28','2025-09-18 15:15:54');
/*!40000 ALTER TABLE `temas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('admin','usuario') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usuario',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `email_verificado` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_email_unique` (`email`),
  KEY `usuarios_tipo_index` (`tipo`),
  KEY `usuarios_ativo_index` (`ativo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Administrador do Sistema','admin@templats-link.com','$2y$12$F2LO7MvBwjk.iMteRnE4wushxlpJTf0qOzJYiMnudo/9ex4QLBdIG','admin',1,'2025-09-13 14:28:37',NULL,'2025-09-13 14:28:37','2025-09-13 14:32:20','2025-09-13 14:32:20','2025-09-13 14:32:20'),(2,'Usuário Teste','teste@templats-link.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','usuario',1,'2025-09-13 14:28:37',NULL,'2025-09-13 14:28:37','2025-09-13 14:32:20','2025-09-13 14:32:20','2025-09-13 14:32:20'),(3,'Usuário Demo','demo@templats-link.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','usuario',1,'2025-09-13 14:28:37',NULL,'2025-09-13 14:28:37','2025-09-13 14:32:20','2025-09-13 14:32:20','2025-09-13 14:32:20');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'templats_link'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-19 16:56:18
