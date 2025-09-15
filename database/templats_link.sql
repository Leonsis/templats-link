-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Tempo de geração: 15/09/2025 às 03:18
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

INSERT INTO `head_configs` (`id`, `pagina`, `meta_title`, `meta_description`, `meta_keywords`, `favicon`, `youtube`, `linkedin`, `twitter`, `instagram`, `facebook`, `horario_atendimento`, `endereco`, `whatsapp`, `telefone`, `email_contato`, `logo`, `logo_footer`, `descricao_footer`, `copyright_footer`, `gtm_head`, `gtm_body`, `created_at`, `updated_at`) VALUES
(1, 'global', NULL, NULL, NULL, 'uploads/favicons/logo_1757796839.webp', 'https://www.facebook.com/?locale=pt_BR', 'https://www.facebook.com/?locale=pt_BR', 'https://www.facebook.com/?locale=pt_BR', 'https://www.facebook.com/?locale=pt_BR', 'https://www.facebook.com/?locale=pt_BR', NULL, NULL, NULL, NULL, NULL, 'uploads/logos/logo_1757796839.webp', 'uploads/logos/logo_1757796839.webp', 'Somos uma empresa especializada em desenvolvimento web, oferecendo soluções inovadoras e templates profissionais para impulsionar seu negócio digital.', NULL, NULL, NULL, '2025-09-13 18:22:52', '2025-09-13 22:02:35'),
(2, 'home', 'Templats-link - Seu sitema de linkagem de Sites', 'Plataforma completa para templates, soluções web e desenvolvimento de sites profissionais.', 'templates, desenvolvimento web, sites, laravel, php', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-13 15:49:42', '2025-09-13 16:15:53'),
(3, 'sobre', 'Sobre Nós - Templats Link', 'Conheça nossa história, missão e equipe por trás da Templats Link.', 'sobre, empresa, história, missão, equipe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2025-09-13 15:49:42', '2025-09-13 15:49:42'),
(4, 'contato', 'Contato - Templats Link', 'Entre em contato conosco. Estamos aqui para ajudar com suas necessidades de desenvolvimento web.', 'contato, suporte, ajuda, desenvolvimento web', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2025-09-13 15:49:42', '2025-09-13 15:49:42'),
(5, 'login', 'Login - Templats Link', 'Faça login em sua conta Templats Link para acessar o painel administrativo.', 'login, acesso, painel, administração', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2025-09-13 15:49:42', '2025-09-13 15:49:42');

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
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1);

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
(1, 'Tema Padrão', 'tema-padrao', NULL, 'main-Thema', 0, '2025-09-13 22:26:35', '2025-09-14 00:35:53'),
(8, 'finazze', 'finazze', 'temas/previews/finazze_1757815024.png', 'temas/finazze', 0, '2025-09-14 01:57:05', '2025-09-14 01:57:05');

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
  ADD UNIQUE KEY `unique_pagina` (`pagina`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `temas`
--
ALTER TABLE `temas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
