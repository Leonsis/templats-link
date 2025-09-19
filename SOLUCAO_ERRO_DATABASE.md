# 🔧 Solução para o Erro "Database connection [] not configured"

## 📋 Problemas Identificados e Soluções

### ✅ **Problema 1: Arquivo config/database.php ausente**

**Status:** RESOLVIDO ✅

- **Causa:** O arquivo de configuração do banco não existia
- **Solução:** Arquivo `config/database.php` foi criado com todas as configurações

### ❌ **Problema 2: Driver PDO MySQL não instalado**

**Status:** PENDENTE ⚠️

- **Erro:** `could not find driver (Connection: mysql)`
- **Causa:** O PHP não tem o driver `pdo_mysql` instalado

## 🔧 Soluções Disponíveis

### **Opção 1: Habilitar PDO MySQL no XAMPP (RECOMENDADO)**

1. **Abra o arquivo `php.ini`:**

   - Localização: `C:\xampp\php\php.ini`
   - Ou use: XAMPP Control Panel → Apache → Config → PHP (php.ini)

2. **Procure pela linha:**

   ```ini
   ;extension=pdo_mysql
   ```

3. **Remova o `;` para descomentar:**

   ```ini
   extension=pdo_mysql
   ```

4. **Salve o arquivo e reinicie o Apache** no XAMPP Control Panel

5. **Teste a conexão:**
   ```bash
   php artisan migrate:status
   ```

### **Opção 2: Usar SQLite (Alternativa Rápida)**

Se não conseguir habilitar o MySQL, pode usar SQLite:

1. **Edite o arquivo `.env` e altere:**

   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

2. **Crie o arquivo de banco SQLite:**

   ```bash
   touch database/database.sqlite
   ```

3. **Execute as migrations:**
   ```bash
   php artisan migrate
   ```

### **Opção 3: Verificar Extensões PHP**

Execute no terminal para verificar extensões disponíveis:

```bash
php -m | findstr pdo
php -m | findstr mysql
```

## 🚀 Após Resolver o Driver

1. **Execute os scripts SQL:**

   - `database_setup.sql`
   - `usuarios_table.sql`

2. **Teste o login:**

   - Email: `admin@templats-link.com`
   - Senha: `admin123`

3. **Acesse o dashboard:**
   - URL: `http://localhost/templats-link/public/dashboard`

## 📞 Se Ainda Houver Problemas

1. **Verifique se o MySQL está rodando** no XAMPP
2. **Confirme as credenciais** no arquivo `.env`
3. **Verifique se o banco `templats_link` foi criado**
4. **Execute:** `php artisan config:clear`

## ✅ Status Atual

- ✅ Arquivo `config/database.php` criado
- ✅ Configuração de autenticação funcionando
- ✅ Dashboard criado e funcional
- ⚠️ Driver PDO MySQL precisa ser habilitado
- ⚠️ Banco de dados precisa ser criado

**Próximo passo:** Habilitar o driver PDO MySQL no XAMPP
