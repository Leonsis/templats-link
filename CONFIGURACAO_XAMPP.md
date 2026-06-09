# Configuração do Projeto Laravel no XAMPP

## 📋 Passos para Configurar no XAMPP

### 1. Habilitar mod_rewrite no Apache
1. Abra o XAMPP Control Panel
2. Clique em "Config" ao lado do Apache
3. Selecione "Apache (httpd.conf)"
4. Procure por `#LoadModule rewrite_module modules/mod_rewrite.so`
5. Remova o `#` para descomentar a linha
6. Salve o arquivo e reinicie o Apache

### 2. Configurar Virtual Host
1. Abra o arquivo `/Applications/XAMPP/xamppfiles/etc/httpd.conf`
2. Procure por `# Virtual hosts` e descomente a linha:
   ```
   Include etc/extra/httpd-vhosts.conf
   ```
3. Abra o arquivo `/Applications/XAMPP/xamppfiles/etc/extra/httpd-vhosts.conf`
4. Adicione o conteúdo do arquivo `templats-link.conf` no final
5. Salve e reinicie o Apache

**Nota:** O Virtual Host está configurado para apontar para a raiz do projeto (`/Applications/XAMPP/xamppfiles/htdocs/templats-link`), não para o diretório `public`. O sistema usa o `index.php` da raiz que carrega automaticamente o Laravel.

### 3. Configurar hosts do sistema
1. Abra o arquivo `/etc/hosts` com sudo:
   ```bash
   sudo nano /etc/hosts
   ```
2. Adicione a linha:
   ```
   127.0.0.1 templats-link.local
   ```
3. Salve o arquivo

### 4. Acessar o projeto
- URL: http://templats-link.local
- Ou diretamente: http://localhost/templats-link

## 🔧 Alternativa Simples (Sem Virtual Host)

Se preferir não configurar Virtual Host, você pode:

1. Acessar diretamente: `http://localhost/templats-link`
2. O sistema agora funciona diretamente da raiz do projeto

## ⚠️ Importante

- Certifique-se de que o Apache está rodando no XAMPP
- O mod_rewrite deve estar habilitado
- As permissões dos arquivos devem estar corretas (775 para diretórios, 664 para arquivos)

## 🚀 Testando

Após a configuração, acesse:
- http://templats-link.local (com Virtual Host)
- http://localhost/templats-link (sem Virtual Host)
