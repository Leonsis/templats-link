# Sistema de Analytics - Templats-Link

## Visão Geral

O sistema de analytics do Templats-Link foi implementado para capturar e enviar eventos customizados para Google Tag Manager (GTM) e Google Analytics (GA4), permitindo rastreamento detalhado de conversões e comportamento do usuário.

## Arquivos Implementados

### 1. JavaScript Global (`public/js/analytics.js`)
- **Função Principal**: `sendCustomEvent(userData)`
- **Funções Auxiliares**: `captureContactForm()`, `captureWhatsAppLead()`, `capturePageEvent()`
- **Verificação de Status**: `checkAnalyticsStatus()`

### 2. Integração com Botões Flutuantes (`resources/views/floatingButton/index.blade.php`)
- Função `sendCustomEvent()` integrada ao modal WhatsApp
- Captura automática de dados após envio bem-sucedido
- Fallback para dados básicos quando `event_data` não está disponível

### 3. Controladores Modificados
- **LeadController**: Retorna `event_data` com dados de geolocalização
- **ContatoController**: Inclui dados de evento na sessão
- **Método de Geolocalização**: `getGeoDataByIP()` em ambos os controladores

### 4. Scripts de Captura (`resources/views/analytics/contact-form-script.blade.php`)
- Captura automática de formulários de contato
- Eventos de cliques em botões de contato
- Processamento de dados da sessão

## Como Funciona

### 1. Fluxo de Dados

```
Usuário preenche formulário
    ↓
Backend processa e obtém geolocalização
    ↓
Dados retornados como event_data
    ↓
JavaScript captura os dados
    ↓
sendCustomEvent() envia para GTM/GA4
```

### 2. Estrutura de Dados

```javascript
const eventData = {
    name: 'João',
    surname: 'Silva',
    email: 'joao@email.com',
    phone: '+5511999999999',
    country: 'Brasil',
    cep: '01234-567',
    region: 'São Paulo',
    city: 'São Paulo',
    street: 'Rua das Flores, 123',
    service: 'contact', // ou 'whatsapp'
    source: 'footer_form' // ou 'floating_button'
};
```

### 3. Eventos Enviados

#### Google Tag Manager
```javascript
dataLayer.push({
    'event': 'Lead Footer Formulario',
    'email': userData.email,
    'telefone': userData.phone,
    'nome': userData.name,
    'sobrenome': userData.surname,
    'pais': userData.country,
    'cep': userData.cep,
    'regiao': userData.region,
    'cidade': userData.city,
    'rua': userData.street,
    'service': userData.service,
    'timestamp': new Date().toISOString()
});
```

#### Google Analytics
```javascript
gtag('event', 'user_provided_data', {
    'email': userData.email,
    'telefone': userData.phone,
    'nome': userData.name,
    'sobrenome': userData.surname,
    'pais': userData.country,
    'cep': userData.cep,
    'regiao': userData.region,
    'cidade': userData.city,
    'rua': userData.street,
    'service': userData.service
});
```

## Como Usar

### 1. Uso Automático
O sistema funciona automaticamente em:
- Formulários de contato
- Botões flutuantes do WhatsApp
- Qualquer formulário com `action` contendo "contato" ou "contact"

### 2. Uso Manual
```javascript
// Capturar dados de formulário
const formData = {
    name: 'João Silva',
    email: 'joao@email.com',
    phone: '+5511999999999',
    // ... outros dados
};

sendCustomEvent(formData);
```

### 3. Captura de Eventos de Página
```javascript
capturePageEvent('home', 'view');
capturePageEvent('contato', 'form_submit');
```

## Configuração

### 1. Google Tag Manager
- Adicionar o código GTM no `<head>` da página
- Configurar triggers para o evento `Lead Footer Formulario`
- Criar variáveis para capturar os dados do evento

### 2. Google Analytics
- Configurar GA4 com gtag
- Criar eventos customizados para `user_provided_data`
- Configurar conversões baseadas nos eventos

### 3. Verificação de Status
```javascript
// Verificar se os sistemas estão carregados
const status = checkAnalyticsStatus();
console.log(status);
// { gtm: true, ga4: true, timestamp: "2024-01-15T10:30:00.000Z" }
```

## Benefícios

### 1. Analytics Avançado
- Rastreamento detalhado de conversões
- Dados geográficos para segmentação
- Comportamento do usuário por fonte

### 2. Marketing Inteligente
- Retargeting baseado em dados reais
- Segmentação geográfica
- Personalização de conteúdo

### 3. Business Intelligence
- Relatórios detalhados de conversão
- Análise de efetividade por canal
- Identificação de tendências

## Troubleshooting

### 1. Eventos não aparecem no GTM/GA4
- Verificar se `dataLayer` e `gtag` estão carregados
- Verificar console para erros JavaScript
- Usar `checkAnalyticsStatus()` para diagnóstico

### 2. Dados de geolocalização incorretos
- Verificar se a API ipapi.co está funcionando
- Verificar logs do servidor para erros de HTTP
- Dados de fallback são usados em caso de erro

### 3. Formulários não são capturados
- Verificar se o formulário tem `action` contendo "contato"
- Verificar se o script está carregado na página
- Verificar console para erros JavaScript

## Exemplos de Uso

### 1. Formulário de Contato Simples
```html
<form action="/contato" method="POST">
    <input name="nome" type="text" required>
    <input name="email" type="email" required>
    <input name="telefone" type="tel">
    <button type="submit">Enviar</button>
</form>
```

### 2. Botão de Contato
```html
<a href="tel:+5511999999999" class="contact-button">Ligar</a>
<a href="mailto:contato@site.com" class="contact-button">Email</a>
```

### 3. Evento Manual
```javascript
// Capturar evento manual
const eventData = {
    name: 'João',
    surname: 'Silva',
    email: 'joao@email.com',
    phone: '+5511999999999',
    country: 'Brasil',
    service: 'contact',
    source: 'manual'
};

sendCustomEvent(eventData);
```

## Conclusão

O sistema de analytics do Templats-Link fornece uma solução completa para rastreamento de conversões e comportamento do usuário, com integração automática e manual, dados geográficos e compatibilidade com GTM e GA4.
