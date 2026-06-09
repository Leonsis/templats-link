/**
 * Script de Teste para Sistema de Analytics - Templats-Link
 * Execute este script no console do navegador para testar o sistema
 */

// Função de teste para verificar se o sistema está funcionando
function testAnalyticsSystem() {
    console.log('=== TESTE DO SISTEMA DE ANALYTICS ===');

    // 1. Verificar se o arquivo analytics.js foi carregado
    if (typeof sendCustomEvent === 'function') {
        console.log('✅ Função sendCustomEvent encontrada');
    } else {
        console.error('❌ Função sendCustomEvent NÃO encontrada');
        return false;
    }

    // 2. Verificar status dos sistemas de analytics
    if (typeof checkAnalyticsStatus === 'function') {
        const status = checkAnalyticsStatus();
        console.log('📊 Status dos sistemas:', status);

        if (status.gtm) {
            console.log('✅ Google Tag Manager (dataLayer) disponível');
        } else {
            console.warn('⚠️ Google Tag Manager (dataLayer) não encontrado');
        }

        if (status.ga4) {
            console.log('✅ Google Analytics (gtag) disponível');
        } else {
            console.warn('⚠️ Google Analytics (gtag) não encontrado');
        }
    } else {
        console.error('❌ Função checkAnalyticsStatus não encontrada');
    }

    // 3. Testar envio de evento customizado
    console.log('🧪 Testando envio de evento customizado...');

    const testData = {
        name: 'João',
        surname: 'Silva',
        email: 'joao@teste.com',
        phone: '+5511999999999',
        country: 'Brasil',
        cep: '01234-567',
        region: 'São Paulo',
        city: 'São Paulo',
        street: 'Rua Teste, 123',
        service: 'test',
        source: 'test_script'
    };

    try {
        sendCustomEvent(testData);
        console.log('✅ Evento de teste enviado com sucesso');
        console.log('📋 Dados enviados:', testData);
    } catch (error) {
        console.error('❌ Erro ao enviar evento de teste:', error);
    }

    // 4. Verificar se há formulários de contato na página
    const contactForms = document.querySelectorAll('form[action*="contato"], form[action*="contact"]');
    console.log(`📝 Formulários de contato encontrados: ${contactForms.length}`);

    contactForms.forEach((form, index) => {
        console.log(`Formulário ${index + 1}:`, form.action);
    });

    // 5. Verificar se há botões de contato
    const contactButtons = document.querySelectorAll('a[href*="tel:"], a[href*="mailto:"], button[onclick*="contato"]');
    console.log(`🔗 Botões de contato encontrados: ${contactButtons.length}`);

    contactButtons.forEach((button, index) => {
        console.log(`Botão ${index + 1}:`, button.href || button.onclick);
    });

    // 6. Verificar se há botões flutuantes
    const floatingButtons = document.querySelectorAll('.floating-btn');
    console.log(`🎯 Botões flutuantes encontrados: ${floatingButtons.length}`);

    floatingButtons.forEach((button, index) => {
        console.log(`Botão flutuante ${index + 1}:`, button.className);
    });

    console.log('=== FIM DO TESTE ===');
    return true;
}

// Função para simular dados de evento da sessão
function simulateSessionEventData() {
    console.log('🧪 Simulando dados de evento da sessão...');

    const mockEventData = {
        name: 'Maria',
        surname: 'Santos',
        email: 'maria@teste.com',
        phone: '+5511888888888',
        country: 'Brasil',
        cep: '04567-890',
        region: 'Rio de Janeiro',
        city: 'Rio de Janeiro',
        street: 'Avenida Teste, 456',
        service: 'contact',
        source: 'footer_form'
    };

    if (typeof sendCustomEvent === 'function') {
        sendCustomEvent(mockEventData);
        console.log('✅ Dados simulados enviados:', mockEventData);
    } else {
        console.error('❌ Função sendCustomEvent não disponível');
    }
}

// Função para testar captura de formulários
function testFormCapture() {
    console.log('🧪 Testando captura de formulários...');

    const forms = document.querySelectorAll('form');
    console.log(`📝 Total de formulários na página: ${forms.length}`);

    forms.forEach((form, index) => {
        console.log(`Formulário ${index + 1}:`, {
            action: form.action,
            method: form.method,
            hasName: form.querySelector('input[name="nome"], input[name="name"]') ? 'Sim' : 'Não',
            hasEmail: form.querySelector('input[name="email"]') ? 'Sim' : 'Não',
            hasPhone: form.querySelector('input[name="telefone"], input[name="phone"]') ? 'Sim' : 'Não'
        });
    });
}

// Script de teste - NÃO executar automaticamente em produção
// Para executar manualmente, use: testAnalyticsSystem()
console.log('🔧 Script de teste carregado. Use testAnalyticsSystem() para executar o teste completo.');

// Disponibilizar funções globalmente para teste manual
window.testAnalyticsSystem = testAnalyticsSystem;
window.simulateSessionEventData = simulateSessionEventData;
window.testFormCapture = testFormCapture;

console.log('🔧 Script de teste carregado. Use testAnalyticsSystem() para executar o teste completo.');