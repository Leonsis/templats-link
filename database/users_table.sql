-- =====================================================
-- Script SQL para criação da tabela users
-- Projeto: Templats-link
-- =====================================================

-- Criar tabela users
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Inserir usuário administrador
-- =====================================================

-- Usuário Admin
-- Email: admin@templats-link.com
-- Senha: admin123
-- Hash da senha gerado com bcrypt
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@templats-link.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW());

-- =====================================================
-- Usuários de exemplo adicionais (opcional)
-- =====================================================

-- Usuário Teste
-- Email: teste@templats-link.com
-- Senha: teste123
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Usuário Teste', 'teste@templats-link.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW());

-- =====================================================
-- Comandos úteis para verificar os dados
-- =====================================================

-- Verificar se a tabela foi criada
-- SHOW TABLES LIKE 'users';

-- Verificar estrutura da tabela
-- DESCRIBE users;

-- Verificar usuários inseridos
-- SELECT id, name, email, created_at FROM users;

-- =====================================================
-- Informações de acesso
-- =====================================================
/*
USUÁRIO ADMINISTRADOR:
- Email: admin@templats-link.com
- Senha: admin123

USUÁRIO TESTE:
- Email: teste@templats-link.com
- Senha: teste123

NOTA: As senhas estão hasheadas com bcrypt. 
Para gerar novos hashes, use: Hash::make('sua_senha') no Laravel
ou online: https://bcrypt-generator.com/
*/
