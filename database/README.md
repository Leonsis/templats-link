# Configuração do Banco de Dados - Templats-link

## 📋 Instruções de Instalação

### 1. Configurar o Banco de Dados

Execute os seguintes scripts SQL na ordem:

1. **Primeiro:** `database_setup.sql` - Cria o banco e tabelas básicas
2. **Segundo:** `usuarios_table.sql` - Cria a tabela usuarios e insere dados do admin

### 2. Configurar o Arquivo .env

Crie ou edite o arquivo `.env` na raiz do projeto com as seguintes configurações:

```env
APP_NAME="Templats-link"
APP_ENV=local
APP_KEY=base64:SUA_CHAVE_AQUI
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=templats_link
DB_USERNAME=root
DB_PASSWORD=

# Configurações de Autenticação
AUTH_GUARD=web
AUTH_PROVIDER=usuarios
```

### 3. Gerar Chave da Aplicação

Execute o comando para gerar a chave da aplicação:

```bash
php artisan key:generate
```

### 4. Testar a Conexão

Execute o comando para testar a conexão com o banco:

```bash
php artisan migrate:status
```

## 👤 Usuários Criados

### 🔐 **CREDENCIAIS DE ACESSO**

#### **Administrador do Sistema**

- **Email:** `admin@templats-link.com`
- **Senha:** `admin123`
- **Tipo:** `admin`
- **Status:** `ativo`
- **Função:** Administrador completo do sistema

#### **Usuário Teste**

- **Email:** `teste@templats-link.com`
- **Senha:** `teste123`
- **Tipo:** `usuario`
- **Status:** `ativo`
- **Função:** Usuário comum para testes

#### **Usuário Demo**

- **Email:** `demo@templats-link.com`
- **Senha:** `demo123`
- **Tipo:** `usuario`
- **Status:** `ativo`
- **Função:** Usuário de demonstração

## 🔧 Estrutura das Tabelas

### Tabela `usuarios`

- `id` - ID único do usuário (auto increment)
- `nome` - Nome completo do usuário
- `email` - Email único do usuário
- `senha` - Senha hasheada com bcrypt
- `tipo` - Tipo do usuário (admin ou usuario)
- `ativo` - Status do usuário (1 = ativo, 0 = inativo)
- `email_verificado` - Data de verificação do email
- `remember_token` - Token para "lembrar de mim"
- `criado_em` - Data de criação do registro
- `atualizado_em` - Data da última atualização

### Tabelas Auxiliares

- `sessions` - Sessões do Laravel
- `cache` - Cache do sistema
- `cache_locks` - Locks de cache
- `jobs` - Filas de jobs
- `failed_jobs` - Jobs que falharam

## 🚀 Comandos Úteis

```bash
# Verificar status das migrations
php artisan migrate:status

# Executar migrations
php artisan migrate

# Limpar cache
php artisan cache:clear

# Limpar configurações
php artisan config:clear

# Verificar rotas
php artisan route:list
```

## 🔐 Segurança

- As senhas estão hasheadas com bcrypt
- Use senhas fortes em produção
- Configure HTTPS em produção
- Mantenha o arquivo .env seguro
- Apenas usuários ativos podem fazer login
- Sistema de autenticação configurado para tabela `usuarios`

## 📞 Suporte

Se encontrar problemas:

1. Verifique se o MySQL está rodando
2. Confirme as credenciais do banco no .env
3. Verifique se o banco `templats_link` foi criado
4. Execute os scripts SQL na ordem correta
5. Teste o login com as credenciais do admin: `admin@templats-link.com` / `admin123`
