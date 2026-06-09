# Solução: Eventos Customizados Separados

## Visão Geral

Implementei funções separadas para enviar eventos customizados para o Google Tag Manager e Google Analytics, diferenciando entre:

1. **Botão Flutuante WhatsApp** - Eventos específicos do formulário do botão flutuante
2. **Formulários da Página** - Eventos específicos dos formulários principais da página

## Funções Implementadas

### 1. `sendFloatingButtonEvent(userData)`

**Propósito**: Enviar eventos específicos do botão flutuante WhatsApp

**Eventos enviados**:
- **dataLayer**: `'Floating Button WhatsApp'`
- **gtag**: `'floating_button_whatsapp'`

**Parâmetros**:
```javascript
{
    name: 'Nome do usuário',
    surname: 'Sobrenome',
    email: 'email@exemplo.com',
    phone: '11999999999',
    country: 'Brasil',
    cep: '01234-567',
    region: 'São Paulo',
    city: 'São Paulo',
    street: 'Rua Exemplo',
    service: 'whatsapp',
    source: 'floating_button'
}
```

### 2. `sendPageFormEvent(userData)`

**Propósito**: Enviar eventos específicos dos formulários da página

**Eventos enviados**:
- **dataLayer**: `'Page Form Lead'`
- **gtag**: `'page_form_lead'`

**Parâmetros**: Mesma estrutura do `sendFloatingButtonEvent`, mas com `source: 'page_form'`

### 3. `sendCustomEvent(userData)` (Atualizada)

**Propósito**: Função genérica que determina automaticamente qual função específica usar

**Lógica**:
- Se `userData.source === 'floating_button'` → chama `sendFloatingButtonEvent()`
- Se `userData.source === 'page_form'` → chama `sendPageFormEvent()`
- Caso contrário → usa evento genérico (compatibilidade)

## Como Usar

### Para Botão Flutuante WhatsApp

```javascript
// Dados do usuário
const userData = {
    name: 'João Silva',
    email: 'joao@exemplo.com',
    phone: '11999999999',
    country: 'Brasil',
    service: 'whatsapp',
    source: 'floating_button'
};

// Enviar evento específico
sendFloatingButtonEvent(userData);

// OU usar a função genérica (recomendado)
sendCustomEvent(userData);
```

### Para Formulários da Página

```javascript
// Dados do usuário
const userData = {
    name: 'Maria Santos',
    email: 'maria@exemplo.com',
    phone: '11888888888',
    country: 'Brasil',
    service: 'contato',
    source: 'page_form'
};

// Enviar evento específico
sendPageFormEvent(userData);

// OU usar a função genérica (recomendado)
sendCustomEvent(userData);
```

## Eventos no Google Tag Manager

### Botão Flutuante WhatsApp
```javascript
dataLayer.push({
    'event': 'Floating Button WhatsApp',
    'event_category': 'WhatsApp',
    'event_action': 'Formulario Preenchido',
    'event_label': 'Botao Flutuante',
    'source': 'floating_button',
    // ... outros dados do usuário
});
```

### Formulários da Página
```javascript
dataLayer.push({
    'event': 'Page Form Lead',
    'event_category': 'Formulario',
    'event_action': 'Formulario Preenchido',
    'event_label': 'Formulario da Pagina',
    'source': 'page_form',
    // ... outros dados do usuário
});
```

## Eventos no Google Analytics (gtag)

### Botão Flutuante WhatsApp
```javascript
gtag('event', 'floating_button_whatsapp', {
    'event_category': 'WhatsApp',
    'event_action': 'Formulario Preenchido',
    'event_label': 'Botao Flutuante',
    'source': 'floating_button',
    // ... outros dados do usuário
});
```

### Formulários da Página
```javascript
gtag('event', 'page_form_lead', {
    'event_category': 'Formulario',
    'event_action': 'Formulario Preenchido',
    'event_label': 'Formulario da Pagina',
    'source': 'page_form',
    // ... outros dados do usuário
});
```

## Vantagens da Implementação

1. **Separação Clara**: Eventos distintos para diferentes origens
2. **Rastreamento Preciso**: Possibilidade de analisar conversões por fonte
3. **Compatibilidade**: Função genérica mantém compatibilidade com código existente
4. **Flexibilidade**: Fácil adição de novos tipos de eventos
5. **Debugging**: Logs específicos para cada tipo de evento

## Configuração no GTM

Para configurar no Google Tag Manager, crie triggers baseados nos eventos:

- **Trigger Botão Flutuante**: `event` equals `Floating Button WhatsApp`
- **Trigger Formulário Página**: `event` equals `Page Form Lead`

## Logs de Debug

Cada função gera logs específicos no console:
- `"Evento do botão flutuante enviado:"` - para eventos do botão flutuante
- `"Evento do formulário da página enviado:"` - para eventos dos formulários
- `"Evento customizado genérico enviado:"` - para eventos genéricos

## Arquivo Modificado

- `resources/views/floatingButton/index.blade.php` - Implementação das funções
