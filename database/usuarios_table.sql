-- =====================================================
-- Script SQL para criação da tabela usuarios
-- Projeto: Templats-link
-- =====================================================

-- Criar tabela usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('admin','usuario') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usuario',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `email_verificado` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_email_unique` (`email`),
  KEY `usuarios_tipo_index` (`tipo`),
  KEY `usuarios_ativo_index` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Inserir usuário administrador
-- =====================================================

-- Usuário Admin
-- Email: admin@templats-link.com
-- Senha: admin123
-- Hash da senha gerado com bcrypt
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `ativo`, `email_verificado`, `remember_token`, `criado_em`, `atualizado_em`) VALUES
(1, 'Administrador do Sistema', 'admin@templats-link.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW(), NULL, NOW(), NOW());

-- =====================================================
-- Usuários de exemplo adicionais (opcional)
-- =====================================================

-- Usuário Teste
-- Email: teste@templats-link.com
-- Senha: teste123
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `ativo`, `email_verificado`, `remember_token`, `criado_em`, `atualizado_em`) VALUES
(2, 'Usuário Teste', 'teste@templats-link.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', 1, NOW(), NULL, NOW(), NOW());

-- Usuário Demo
-- Email: demo@templats-link.com
-- Senha: demo123
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `ativo`, `email_verificado`, `remember_token`, `criado_em`, `atualizado_em`) VALUES
(3, 'Usuário Demo', 'demo@templats-link.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario', 1, NOW(), NULL, NOW(), NOW());

-- =====================================================
-- Comandos úteis para verificar os dados
-- =====================================================

-- Verificar se a tabela foi criada
-- SHOW TABLES LIKE 'usuarios';

-- Verificar estrutura da tabela
-- DESCRIBE usuarios;

-- Verificar usuários inseridos
-- SELECT id, nome, email, tipo, ativo, criado_em FROM usuarios;

-- Verificar apenas usuários admin
-- SELECT * FROM usuarios WHERE tipo = 'admin';

-- Verificar usuários ativos
-- SELECT * FROM usuarios WHERE ativo = 1;

-- =====================================================
-- Informações de acesso
-- =====================================================
/*
USUÁRIO ADMINISTRADOR:
- Email: admin@templats-link.com
- Senha: admin123
- Tipo: admin
- Status: ativo

USUÁRIO TESTE:
- Email: teste@templats-link.com
- Senha: teste123
- Tipo: usuario
- Status: ativo

USUÁRIO DEMO:
- Email: demo@templats-link.com
- Senha: demo123
- Tipo: usuario
- Status: ativo

NOTA: As senhas estão hasheadas com bcrypt. 
Para gerar novos hashes, use: Hash::make('sua_senha') no Laravel
ou online: https://bcrypt-generator.com/

ESTRUTURA DA TABELA:
- id: ID único do usuário (auto increment)
- nome: Nome completo do usuário
- email: Email único do usuário
- senha: Senha hasheada com bcrypt
- tipo: Tipo do usuário (admin ou usuario)
- ativo: Status do usuário (1 = ativo, 0 = inativo)
- email_verificado: Data de verificação do email
- remember_token: Token para "lembrar de mim"
- criado_em: Data de criação do registro
- atualizado_em: Data da última atualização
*/
