-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Tempo de geração: 19/09/2025 às 11:54
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `templats_link`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `head_configs`
--

CREATE TABLE `head_configs` (
  `id` int(11) NOT NULL,
  `pagina` varchar(50) DEFAULT 'global',
  `tema` varchar(50) DEFAULT 'global',
  `meta_title` varchar(60) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `horario_atendimento` text DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `email_contato` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `logo_footer` varchar(255) DEFAULT NULL,
  `descricao_footer` text DEFAULT NULL,
  `copyright_footer` varchar(255) DEFAULT NULL,
  `gtm_head` text DEFAULT NULL,
  `gtm_body` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `head_configs`
--

INSERT INTO `head_configs` (`id`, `pagina`, `tema`, `meta_title`, `meta_description`, `meta_keywords`, `favicon`, `youtube`, `linkedin`, `twitter`, `instagram`, `facebook`, `horario_atendimento`, `endereco`, `whatsapp`, `telefone`, `email_contato`, `logo`, `logo_footer`, `descricao_footer`, `copyright_footer`, `gtm_head`, `gtm_body`, `created_at`, `updated_at`) VALUES
(22, 'global', 'main-Thema', 'Templats Link - Templates e Desenvolvimento Web Profissional', 'Plataforma completa para templates, soluções web e desenvolvimento de sites profissionais. Templates modernos, responsivos e otimizados para SEO.', 'templates, desenvolvimento web, sites, laravel, php, html, css, javascript, bootstrap, responsivo, seo', 'uploads/favicons/favicon-main.webp', 'https://youtube.com/templatslink', 'https://linkedin.com/company/templatslink', 'https://twitter.com/templatslink', 'https://instagram.com/templatslink', 'https://facebook.com/templatslink', 'Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h', 'Rua das Tecnologias, 123 - Centro, São Paulo - SP, 01234-567', '+5511999999999', '+55 (11) 99999-9999', 'contato@templats-link.com', 'uploads/logos/logo-main.png', 'uploads/logos/logo-footer.png', 'Somos especialistas em desenvolvimento web, oferecendo templates modernos e soluções personalizadas para sua empresa.', '© {ano} Templats Link. Todos os direitos reservados.', '<!-- Google Tag Manager --><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!=\'dataLayer\'?\'&l=\'+l:\'\';j.async=true;j.src=\'https://www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);})(window,document,\'script\',\'dataLayer\',\'GTM-XXXXXXX\');</script><!-- End Google Tag Manager -->', '<!-- Google Tag Manager (noscript) --><noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript><!-- End Google Tag Manager (noscript) -->', '2025-09-17 16:29:48', '2025-09-17 16:29:48'),
(23, 'home', 'main-Thema', 'Início - Templats Link | Templates e Desenvolvimento Web', 'Bem-vindo ao Templats Link! Encontre os melhores templates para seu projeto web. Desenvolvimento profissional, responsivo e otimizado.', 'templates, home, início, desenvolvimento web, sites profissionais, responsivo', 'uploads/favicons/favicon-main.webp', 'https://youtube.com/templatslink', 'https://linkedin.com/company/templatslink', 'https://twitter.com/templatslink', 'https://instagram.com/templatslink', 'https://facebook.com/templatslink', 'Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h', 'Rua das Tecnologias, 123 - Centro, São Paulo - SP, 01234-567', '+5511999999999', '+55 (11) 99999-9999', 'contato@templats-link.com', 'uploads/logos/logo-main.png', 'uploads/logos/logo-footer.png', 'Somos especialistas em desenvolvimento web, oferecendo templates modernos e soluções personalizadas para sua empresa.', '© {ano} Templats Link. Todos os direitos reservados.', '', '', '2025-09-17 16:29:48', '2025-09-17 16:29:48'),
(24, 'sobre', 'main-Thema', 'Sobre Nós - Templats Link | Nossa História e Missão', 'Conheça a história do Templats Link. Somos uma empresa especializada em desenvolvimento web com mais de 5 anos de experiência.', 'sobre, empresa, história, missão, desenvolvimento web, experiência', 'uploads/favicons/favicon-main.webp', 'https://youtube.com/templatslink', 'https://linkedin.com/company/templatslink', 'https://twitter.com/templatslink', 'https://instagram.com/templatslink', 'https://facebook.com/templatslink', 'Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h', 'Rua das Tecnologias, 123 - Centro, São Paulo - SP, 01234-567', '+5511999999999', '+55 (11) 99999-9999', 'contato@templats-link.com', 'uploads/logos/logo-main.png', 'uploads/logos/logo-footer.png', 'Somos especialistas em desenvolvimento web, oferecendo templates modernos e soluções personalizadas para sua empresa.', '© {ano} Templats Link. Todos os direitos reservados.', '', '', '2025-09-17 16:29:48', '2025-09-17 16:29:48'),
(25, 'contato', 'main-Thema', 'Contato - Templats Link | Entre em Contato Conosco', 'Entre em contato com o Templats Link. Estamos prontos para ajudar com seu projeto web. Fale conosco por telefone, email ou WhatsApp.', 'contato, telefone, email, whatsapp, suporte, desenvolvimento web', 'uploads/favicons/favicon-main.webp', 'https://youtube.com/templatslink', 'https://linkedin.com/company/templatslink', 'https://twitter.com/templatslink', 'https://instagram.com/templatslink', 'https://facebook.com/templatslink', 'Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h', 'Rua das Tecnologias, 123 - Centro, São Paulo - SP, 01234-567', '+5511999999999', '+55 (11) 99999-9999', 'contato@templats-link.com', 'uploads/logos/logo-main.png', 'uploads/logos/logo-footer.png', 'Somos especialistas em desenvolvimento web, oferecendo templates modernos e soluções personalizadas para sua empresa.', '© {ano} Templats Link. Todos os direitos reservados.', '', '', '2025-09-17 16:29:48', '2025-09-17 16:29:48'),
(26, 'login', 'main-Thema', 'Login - Templats Link | Acesso ao Painel Administrativo', 'Faça login no painel administrativo do Templats Link. Acesso seguro e rápido para gerenciar seu site.', 'login, painel, administrativo, acesso, segurança', 'uploads/favicons/favicon-main.webp', 'https://youtube.com/templatslink', 'https://linkedin.com/company/templatslink', 'https://twitter.com/templatslink', 'https://instagram.com/templatslink', 'https://facebook.com/templatslink', 'Segunda a Sexta: 8h às 18h | Sábado: 8h às 12h', 'Rua das Tecnologias, 123 - Centro, São Paulo - SP, 01234-567', '+5511999999999', '+55 (11) 99999-9999', 'contato@templats-link.com', 'uploads/logos/logo-main.png', 'uploads/logos/logo-footer.png', 'Somos especialistas em desenvolvimento web, oferecendo templates modernos e soluções personalizadas para sua empresa.', '© {ano} Templats Link. Todos os direitos reservados.', '', '', '2025-09-17 16:29:48', '2025-09-17 16:29:48'),
(122, 'Contato', 'Finazze', 'Contato - Finazze', 'Página Contato do tema Finazze. Configure as meta tags específicas desta página.', 'contato, finazze, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(123, 'Sobre', 'Finazze', 'Sobre - Finazze', 'Página Sobre do tema Finazze. Configure as meta tags específicas desta página.', 'sobre, finazze, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(124, 'home', 'Finazze', 'Início - Finazze', 'Bem-vindo ao Finazze. Página inicial com informações sobre nossos serviços.', 'finazze, início, home, página principal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(125, 'footer', 'Finazze', 'Footer - Finazze', 'Página Footer do tema Finazze. Configure as meta tags específicas desta página.', 'footer, finazze, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(126, 'head', 'Finazze', 'Head - Finazze', 'Página Head do tema Finazze. Configure as meta tags específicas desta página.', 'head, finazze, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(127, 'nav', 'Finazze', 'Nav - Finazze', 'Página Nav do tema Finazze. Configure as meta tags específicas desta página.', 'nav, finazze, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(128, 'scripts', 'Finazze', 'Scripts - Finazze', 'Página Scripts do tema Finazze. Configure as meta tags específicas desta página.', 'scripts, finazze, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(129, 'app', 'Finazze', 'App - Finazze', 'Página App do tema Finazze. Configure as meta tags específicas desta página.', 'app, finazze, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(130, 'service', 'Finazze', 'Service - Finazze', 'Página Service do tema Finazze. Configure as meta tags específicas desta página.', 'service, finazze, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(131, 'testimonial', 'Finazze', 'Testimonial - Finazze', 'Página Testimonial do tema Finazze. Configure as meta tags específicas desta página.', 'testimonial, finazze, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(132, 'global', 'Finazze', 'Finazze - Site Profissional', 'Site profissional do tema Finazze. Descubra nossos serviços e entre em contato.', 'finazze, site, profissional, serviços', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(133, '401', 'Lumialto', '401 - Lumialto', 'Página 401 do tema Lumialto. Configure as meta tags específicas desta página.', '401, lumialto, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(134, '404', 'Lumialto', '404 - Lumialto', 'Página 404 do tema Lumialto. Configure as meta tags específicas desta página.', '404, lumialto, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(135, 'contato', 'Lumialto', 'Contato - Lumialto', 'Entre em contato conosco. Estamos prontos para atender suas necessidades.', 'lumialto, contato, telefone, email, suporte', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(136, 'home', 'Lumialto', 'Início - Lumialto', 'Bem-vindo ao Lumialto. Página inicial com informações sobre nossos serviços.', 'lumialto, início, home, página principal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:50:31'),
(137, 'footer', 'Lumialto', 'Footer - Lumialto', 'Página Footer do tema Lumialto. Configure as meta tags específicas desta página.', 'footer, lumialto, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(138, 'head', 'Lumialto', 'Head - Lumialto', 'Página Head do tema Lumialto. Configure as meta tags específicas desta página.', 'head, lumialto, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(139, 'nav', 'Lumialto', 'Nav - Lumialto', 'Página Nav do tema Lumialto. Configure as meta tags específicas desta página.', 'nav, lumialto, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(140, 'scripts', 'Lumialto', 'Scripts - Lumialto', 'Página Scripts do tema Lumialto. Configure as meta tags específicas desta página.', 'scripts, lumialto, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(141, 'lanternagem-e-pintura', 'Lumialto', 'Lanternagem-e-pintura - Lumialto', 'Página Lanternagem-e-pintura do tema Lumialto. Configure as meta tags específicas desta página.', 'lanternagem-e-pintura, lumialto, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(142, 'app', 'Lumialto', 'App - Lumialto', 'Página App do tema Lumialto. Configure as meta tags específicas desta página.', 'app, lumialto, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(143, 'martelinho-de-ouro', 'Lumialto', 'Martelinho-de-ouro - Lumialto', 'Página Martelinho-de-ouro do tema Lumialto. Configure as meta tags específicas desta página.', 'martelinho-de-ouro, lumialto, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(144, 'mecanica-geral', 'Lumialto', 'Mecanica-geral - Lumialto', 'Página Mecanica-geral do tema Lumialto. Configure as meta tags específicas desta página.', 'mecanica-geral, lumialto, página', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(145, 'sobre', 'Lumialto', 'Sobre - Lumialto', 'Conheça mais sobre o Lumialto e nossa história.', 'lumialto, sobre, empresa, história', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(146, 'global', 'Lumialto', 'Lumialto - Site Profissional', 'Site profissional do tema Lumialto. Descubra nossos serviços e entre em contato.', 'lumialto, site, profissional, serviços', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 09:49:27', '2025-09-19 09:49:27');

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2025_09_16_192656_create_rotas_dinamicas_table', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `rotas_dinamicas`
--

CREATE TABLE `rotas_dinamicas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tema` varchar(100) NOT NULL,
  `pagina` varchar(100) NOT NULL,
  `rota` varchar(255) NOT NULL,
  `nome_rota` varchar(100) NOT NULL,
  `controller` varchar(100) NOT NULL DEFAULT 'TemasController',
  `metodo` varchar(100) NOT NULL DEFAULT 'renderizarPaginaDinamica',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `rotas_dinamicas`
--

INSERT INTO `rotas_dinamicas` (`id`, `tema`, `pagina`, `rota`, `nome_rota`, `controller`, `metodo`, `ativo`, `created_at`, `updated_at`) VALUES
(182, 'Finazze', 'service', '/service', 'service', 'TemasController', 'renderizarPaginaDinamica', 1, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(183, 'Finazze', 'testimonial', '/testimonial', 'testimonial', 'TemasController', 'renderizarPaginaDinamica', 1, '2025-09-19 09:35:15', '2025-09-19 09:35:15'),
(184, 'Lumialto', '401', '/401', '401', 'TemasController', 'renderizarPaginaDinamica', 1, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(185, 'Lumialto', '404', '/404', '404', 'TemasController', 'renderizarPaginaDinamica', 1, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(186, 'Lumialto', 'lanternagem-e-pintura', '/lanternagem-e-pintura', 'lanternagem-e-pintura', 'TemasController', 'renderizarPaginaDinamica', 1, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(187, 'Lumialto', 'martelinho-de-ouro', '/martelinho-de-ouro', 'martelinho-de-ouro', 'TemasController', 'renderizarPaginaDinamica', 1, '2025-09-19 09:49:27', '2025-09-19 09:49:27'),
(188, 'Lumialto', 'mecanica-geral', '/mecanica-geral', 'mecanica-geral', 'TemasController', 'renderizarPaginaDinamica', 1, '2025-09-19 09:49:27', '2025-09-19 09:49:27');

-- --------------------------------------------------------

--
-- Estrutura para tabela `temas`
--

CREATE TABLE `temas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `preview_path` varchar(500) DEFAULT NULL,
  `arquivo_path` varchar(500) NOT NULL,
  `ativo` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `temas`
--

INSERT INTO `temas` (`id`, `nome`, `slug`, `preview_path`, `arquivo_path`, `ativo`, `created_at`, `updated_at`) VALUES
(37, 'Finazze', 'finazze', NULL, 'Finazze', 0, '2025-09-19 09:35:16', '2025-09-19 09:35:16'),
(38, 'Lumialto', 'lumialto', NULL, 'Lumialto', 0, '2025-09-19 09:49:28', '2025-09-19 09:49:28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','usuario') NOT NULL DEFAULT 'usuario',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `email_verificado` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `criado_em` timestamp NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `ativo`, `email_verificado`, `remember_token`, `criado_em`, `atualizado_em`, `updated_at`, `created_at`) VALUES
(1, 'Administrador do Sistema', 'admin@templats-link.com', '$2y$12$F2LO7MvBwjk.iMteRnE4wushxlpJTf0qOzJYiMnudo/9ex4QLBdIG', 'admin', 1, '2025-09-13 14:28:37', NULL, '2025-09-13 14:28:37', '2025-09-13 14:32:20', '2025-09-13 14:32:20', '2025-09-13 14:32:20'),
(2, 'Usuário Teste', 'teste@templats-link.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', 1, '2025-09-13 14:28:37', NULL, '2025-09-13 14:28:37', '2025-09-13 14:32:20', '2025-09-13 14:32:20', '2025-09-13 14:32:20'),
(3, 'Usuário Demo', 'demo@templats-link.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', 1, '2025-09-13 14:28:37', NULL, '2025-09-13 14:28:37', '2025-09-13 14:32:20', '2025-09-13 14:32:20', '2025-09-13 14:32:20');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `head_configs`
--
ALTER TABLE `head_configs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pagina_tema` (`pagina`,`tema`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Índices de tabela `rotas_dinamicas`
--
ALTER TABLE `rotas_dinamicas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rotas_dinamicas_tema_pagina_unique` (`tema`,`pagina`),
  ADD KEY `rotas_dinamicas_tema_pagina_index` (`tema`,`pagina`),
  ADD KEY `rotas_dinamicas_tema_ativo_index` (`tema`,`ativo`);

--
-- Índices de tabela `temas`
--
ALTER TABLE `temas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_email_unique` (`email`),
  ADD KEY `usuarios_tipo_index` (`tipo`),
  ADD KEY `usuarios_ativo_index` (`ativo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `head_configs`
--
ALTER TABLE `head_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `rotas_dinamicas`
--
ALTER TABLE `rotas_dinamicas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT de tabela `temas`
--
ALTER TABLE `temas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
