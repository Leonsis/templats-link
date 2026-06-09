# 📧 Configuração de Email Gmail - Templats Link

## ✅ **Configurações Aplicadas**

As configurações de email SMTP do Gmail foram aplicadas no arquivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-de-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="seu-email@gmail.com"
MAIL_FROM_NAME="Templats Link"
```

## 🔧 **Próximos Passos para Configurar**

### **1. Substitua os Valores no .env**

Edite o arquivo `.env` e substitua:

- `seu-email@gmail.com` → **Seu email do Gmail**
- `sua-senha-de-app` → **Sua senha de app do Gmail**

### **2. Configure a Senha de App do Gmail**

#### **Passo 1: Ativar Verificação em 2 Etapas**
1. Acesse: https://myaccount.google.com/security
2. Vá em "Verificação em duas etapas"
3. Ative a verificação em 2 etapas

#### **Passo 2: Gerar Senha de App**
1. Acesse: https://myaccount.google.com/apppasswords
2. Selecione "Email" como aplicativo
3. Selecione "Outro" como dispositivo
4. Digite "Templats Link" como nome
5. Clique em "Gerar"
6. **COPIE A SENHA GERADA** (16 caracteres)

#### **Passo 3: Atualizar .env**
```env
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop  # Senha de app gerada
```

## 🧪 **Testar a Configuração**

### **1. Teste Básico**
```bash
php artisan test:email seu-email@gmail.com
```

### **2. Teste de Lead**
```bash
php artisan tinker
```
```php
$dadosLead = [
    'nome' => 'João Silva',
    'email' => 'joao@teste.com',
    'telefone' => '+5511999999999',
    'origem' => 'Teste'
];
\App\Helpers\EmailHelper::enviarNotificacaoLead($dadosLead, 'seu-email@gmail.com');
```

## 📋 **Configuração Completa do .env**

```env
# Configurações de Email SMTP Gmail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-de-app-gerada
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="seu-email@gmail.com"
MAIL_FROM_NAME="Templats Link"
```

## 🔍 **Verificar se Está Funcionando**

### **1. Verificar Logs**
```bash
Get-Content storage/logs/laravel.log -Tail 20
```

### **2. Verificar Configuração**
```bash
php artisan tinker
```
```php
echo config('mail.default');
echo config('mail.mailers.smtp.host');
```

## ⚠️ **Importante**

- **NÃO use sua senha normal do Gmail**
- **Use APENAS senha de app** (16 caracteres)
- **Mantenha a senha de app segura**
- **Teste sempre antes de usar em produção**

## 🎯 **Resultado Esperado**

Após configurar corretamente:

✅ **Emails serão enviados via SMTP**  
✅ **Leads chegarão no seu Gmail**  
✅ **Sistema funcionará em produção**  
✅ **Logs mostrarão sucesso**  

## 📞 **Suporte**

Se encontrar problemas:

1. Verifique se a verificação em 2 etapas está ativa
2. Confirme se a senha de app foi gerada corretamente
3. Teste com o comando `php artisan test:email`
4. Verifique os logs em `storage/logs/laravel.log`
