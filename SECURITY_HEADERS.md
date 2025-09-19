# Headers de Segurança - Templats Link

## Visão Geral

O sistema Templats Link implementa automaticamente headers de segurança em **todas as páginas**, incluindo temas instalados, através de um middleware global.

## Implementação

### Middleware SecurityHeaders

**Localização**: `app/Http/Middleware/SecurityHeaders.php`

**Registro**: O middleware está registrado no grupo `web` do Kernel (`app/Http/Kernel.php`), garantindo que seja aplicado em todas as rotas web.

### Headers Aplicados Automaticamente

#### 1. Content Security Policy (CSP)
```php
$csp = "default-src 'self'; "
     . "script-src 'self' https://ajax.googleapis.com https://d3e54v103j8qbb.cloudfront.net https://www.googletagmanager.com https://www.youtube.com https://s.ytimg.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com 'unsafe-inline'; "
     . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; "
     . "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com; "
     . "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com; "
     . "child-src 'self' https://www.youtube.com https://www.youtube-nocookie.com; "
     . "media-src 'self' https://www.youtube.com https://*.googlevideo.com; "
     . "img-src 'self' data: https://i.ytimg.com https://yt3.ggpht.com https://d3e54v103j8qbb.cloudfront.net; "
     . "connect-src 'self' https://www.google-analytics.com https://www.youtube.com https://s.ytimg.com https://www.google.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;";
```

#### 2. X-Frame-Options
```
X-Frame-Options: SAMEORIGIN
```
- Proteção contra Clickjacking
- Permite que a página seja exibida apenas em frames do mesmo domínio

#### 3. X-Content-Type-Options
```
X-Content-Type-Options: nosniff
```
- Evita que o navegador tente adivinhar o tipo do conteúdo
- Força o navegador a respeitar o Content-Type declarado

#### 4. X-XSS-Protection
```
X-XSS-Protection: 1; mode=block
```
- Proteção básica contra XSS (legacy)
- Navegadores modernos ignoram, mas mantém compatibilidade

#### 5. Referrer-Policy
```
Referrer-Policy: no-referrer
```
- Controla informações de referrer enviadas com requisições

#### 6. Remoção de Headers Sensíveis
```php
@header_remove('X-Powered-By');
@header_remove('Server');
```
- Remove cabeçalhos que podem expor informações do servidor

## Aplicação Automática

### ✅ **Funciona Automaticamente Para:**

1. **Todas as páginas do sistema**
2. **Temas instalados** (main-Thema, Lumialto, Finazze, etc.)
3. **Páginas dinâmicas** criadas pelos temas
4. **Admin Panel**
5. **Páginas de autenticação**

### 🔧 **Como Funciona:**

1. **Middleware Global**: Registrado no grupo `web` do Kernel
2. **Aplicação Automática**: Executado em todas as requisições HTTP
3. **Sem Configuração Manual**: Não é necessário adicionar headers manualmente nos temas
4. **Transparente**: Funciona sem interferir no desenvolvimento

## Fontes Permitidas no CSP

### Scripts
- `'self'` (domínio atual)
- `https://ajax.googleapis.com` (Google APIs)
- `https://d3e54v103j8qbb.cloudfront.net` (CloudFront)
- `https://www.googletagmanager.com` (Google Tag Manager)
- `https://www.youtube.com` (YouTube)
- `https://s.ytimg.com` (YouTube Static)
- `https://cdn.jsdelivr.net` (jsDelivr CDN)
- `https://cdnjs.cloudflare.com` (Cloudflare CDN)
- `'unsafe-inline'` (scripts inline)

### Estilos
- `'self'`
- `'unsafe-inline'` (CSS inline)
- `https://fonts.googleapis.com` (Google Fonts)
- `https://cdn.jsdelivr.net`
- `https://cdnjs.cloudflare.com`

### Fontes
- `'self'`
- `data:` (URLs de dados)
- `https://fonts.gstatic.com` (Google Fonts)
- `https://cdnjs.cloudflare.com`

### Frames/Iframes
- `'self'`
- `https://www.youtube.com`
- `https://www.youtube-nocookie.com`

### Imagens
- `'self'`
- `data:`
- `https://i.ytimg.com` (YouTube Thumbnails)
- `https://yt3.ggpht.com` (YouTube Profile Images)
- `https://d3e54v103j8qbb.cloudfront.net`

### Conexões
- `'self'`
- `https://www.google-analytics.com`
- `https://www.youtube.com`
- `https://s.ytimg.com`
- `https://www.google.com`
- `https://cdn.jsdelivr.net`
- `https://cdnjs.cloudflare.com`

## Vantagens

### ✅ **Segurança Automática**
- Headers aplicados automaticamente
- Não requer configuração manual por tema
- Proteção consistente em todo o sistema

### ✅ **Compatibilidade**
- Suporte a CDNs populares
- Compatível com YouTube, Google Analytics
- Funciona com Bootstrap, Font Awesome

### ✅ **Manutenibilidade**
- Configuração centralizada
- Fácil atualização
- Sem duplicação de código

## Verificação

Para verificar se os headers estão sendo aplicados:

1. **Ferramentas do Desenvolvedor**:
   - Abra DevTools (F12)
   - Vá para aba "Network"
   - Recarregue a página
   - Clique em qualquer requisição
   - Verifique aba "Response Headers"

2. **Comando cURL**:
```bash
curl -I https://seu-dominio.com
```

3. **Online Tools**:
   - https://securityheaders.com/
   - https://observatory.mozilla.org/

## Conclusão

O sistema Templats Link implementa **automaticamente** todas as tags de segurança necessárias em todas as páginas, incluindo temas instalados. Não é necessário adicionar headers manualmente nos temas - eles são aplicados globalmente via middleware.

**Status**: ✅ **IMPLEMENTADO E FUNCIONANDO**
