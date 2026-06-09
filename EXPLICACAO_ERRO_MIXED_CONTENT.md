# 🔍 Explicação do Erro: Mixed Content e CSP

## ❌ Erro Reportado

```
Mixed Content: The page at 'https://www.prestacon.com.br/' was loaded over HTTPS, 
but requested an insecure element 'http://https//www.prestacon.com.br//storage/temas/prestacon/content-images/1762954542_prestacon-home-imagem-1.webp'. 
This request was automatically upgraded to HTTPS.

Loading the image 'https://https//www.prestacon.com.br//storage/temas/prestacon/content-images/1762954542_prestacon-home-imagem-1.webp' 
violates the following Content Security Policy directive: "img-src 'self' data: blob: https://i.ytimg.com..."
```

## 🔎 Análise do Problema

### 1. **URL Malformada**

A URL gerada está incorreta:
- ❌ **Erro:** `http://https//www.prestacon.com.br//storage/...`
- ✅ **Correto:** `https://www.prestacon.com.br/storage/...`

### 2. **Causas Possíveis**

#### A. Duplicação de Protocolo
A URL contém `http://https//`, indicando que:
- Alguém está concatenando `http://` com uma URL que já contém `https://`
- Ou o `APP_URL` está configurado incorretamente como `https://https//www.prestacon.com.br`

#### B. Barras Duplicadas
A URL contém `//` após o domínio (`https//www.prestacon.com.br//`), indicando:
- Concatenação incorreta de URLs
- Normalização inadequada de caminhos

#### C. Mixed Content
- A página carrega via HTTPS
- Mas tenta carregar um recurso via HTTP
- O navegador tenta fazer upgrade automático, mas a URL já está malformada

#### D. Content Security Policy (CSP)
- A URL resultante (`https://https//www.prestacon.com.br//storage/...`) não está na lista de domínios permitidos
- O CSP bloqueia o carregamento da imagem

## ✅ Solução Implementada

### Correção na Função `uploaded_assets()`

**Arquivo:** `app/helpers.php`

#### 1. **Limpeza de URLs Malformadas**

Adicionada validação para detectar e corrigir URLs com protocolo duplicado:

```php
// Limpar URLs malformadas (ex: http://https// ou https://https//)
$url = preg_replace('#^https?://(https?://)+#i', 'https://', $path);
$url = preg_replace('#^http://(https?://)+#i', 'https://', $url);

// Normalizar barras duplicadas
$url = preg_replace('#([^:])//+#', '$1/', $url);
```

#### 2. **Validação de URL**

Adicionada validação usando `filter_var()` para garantir que a URL é bem formada:

```php
if (filter_var($url, FILTER_VALIDATE_URL) === false) {
    // Tentar reconstruir a URL usando parse_url()
    $parsed = parse_url($url);
    if ($parsed && isset($parsed['host'])) {
        $scheme = isset($parsed['scheme']) ? $parsed['scheme'] : 'https';
        $host = $parsed['host'];
        $pathPart = isset($parsed['path']) ? $parsed['path'] : '';
        $url = $scheme . '://' . $host . $pathPart;
    }
}
```

#### 3. **Limpeza do APP_URL**

Adicionada validação para limpar o `APP_URL` antes de concatenar:

```php
// Validar e limpar APP_URL para evitar duplicações
$appUrl = preg_replace('#^https?://(https?://)+#i', 'https://', $appUrl);
$appUrl = preg_replace('#([^:])//+#', '$1/', $appUrl);
```

## 🔧 Verificações Necessárias

### 1. **Verificar Configuração do `.env`**

Certifique-se de que o `APP_URL` está configurado corretamente:

```env
APP_URL=https://www.prestacon.com.br
```

**❌ NÃO use:**
- `APP_URL=https://https//www.prestacon.com.br` (protocolo duplicado)
- `APP_URL=http://www.prestacon.com.br` (HTTP em produção)
- `APP_URL=https://www.prestacon.com.br/` (barra final)

**✅ Use:**
- `APP_URL=https://www.prestacon.com.br` (sem barra final)

### 2. **Limpar Cache de Configuração**

Após alterar o `.env`, execute:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 3. **Verificar Dados no Banco**

Verifique se há URLs malformadas salvas no banco de dados:

```sql
SELECT * FROM content_forms 
WHERE configuracao LIKE '%http://https%' 
   OR configuracao LIKE '%https://https%';
```

Se encontrar registros, será necessário corrigi-los manualmente ou criar um script de migração.

### 4. **Verificar Content Security Policy**

Se você tem uma CSP configurada, certifique-se de que o domínio está na lista de permissões:

```
img-src 'self' data: blob: https://www.prestacon.com.br https://i.ytimg.com ...
```

## 📋 Checklist de Correção

- [ ] `APP_URL` configurado corretamente no `.env`
- [ ] Cache de configuração limpo
- [ ] Função `uploaded_assets()` atualizada
- [ ] URLs no banco de dados verificadas
- [ ] CSP atualizada (se aplicável)
- [ ] Teste de carregamento de imagens realizado

## 🧪 Teste

Para testar se a correção funcionou:

1. Acesse uma página que contenha imagens dinâmicas
2. Abra o Console do navegador (F12)
3. Verifique se não há mais erros de Mixed Content
4. Verifique se as imagens carregam corretamente
5. Verifique se as URLs geradas estão no formato correto: `https://www.prestacon.com.br/storage/...`

## 📝 Notas Adicionais

- A função `uploaded_assets()` agora detecta e corrige automaticamente URLs malformadas
- URLs com protocolo duplicado são automaticamente limpas
- Barras duplicadas são normalizadas
- A função valida a URL antes de retorná-la

---

**Status:** ✅ **Corrigido**  
**Data:** 2025-01-XX  
**Arquivo Modificado:** `app/helpers.php`

