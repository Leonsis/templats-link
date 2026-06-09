# Configuração de Senha de App do Gmail

## Erro Atual
```
534-5.7.9 Application-specific password required
```

## Solução Passo a Passo

### 1. Acesse sua Conta Google
- Vá para: https://myaccount.google.com/
- Faça login com a conta `dev@gmail.com`

### 2. Ative a Verificação em Duas Etapas
- Vá em **Segurança** → **Verificação em duas etapas**
- Se não estiver ativada, **ATIVE AGORA**
- Siga as instruções para configurar

### 3. Gere uma Senha de App
- Ainda em **Segurança**, procure por **"Senhas de app"**
- Clique em **"Senhas de app"**
- Selecione **"Email"** como tipo de app
- Digite um nome como "Templats Link"
- Clique em **"Gerar"**
- **COPIE A SENHA GERADA** (16 caracteres)

### 4. Atualize o arquivo .env
Substitua a linha:
```env
MAIL_PASSWORD=Dev2025
```

Por:
```env
MAIL_PASSWORD=SUA_SENHA_DE_APP_AQUI
```

### 5. Teste o Envio
Execute o teste novamente:
```bash
php test_email_final.php
```

## Importante
- A senha de app é diferente da senha normal
- Tem 16 caracteres sem espaços
- É gerada apenas uma vez
- Se perder, precisa gerar uma nova

## Verificação
Após configurar, o teste deve mostrar:
```
✅ SUCESSO! Email enviado com sucesso!
📧 Verifique a caixa de entrada de: caiolenni@gmail.com
```
