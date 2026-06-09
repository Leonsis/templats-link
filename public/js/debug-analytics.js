/**
 * Script de Debug para Sistema de Analytics - Templats-Link
 * Use este script para diagnosticar problemas
 */

// Função para verificar se todas as funções estão disponíveis
function debugAnalyticsSystem() {
    console.log('=== DEBUG DO SISTEMA DE ANALYTICS ===');

    // 1. Verificar se as funções estão disponíveis
    const functions = [
        'sendCustomEvent',
        'openWhatsAppModal',
        'closeWhatsAppModal',
        'checkAnalyticsStatus'
    ];

    functions.forEach(funcName => {
        if (typeof window[funcName] === 'function') {
            console.log(`✅ ${funcName} - Disponivel`);
        } else {
            console.error(`❌ ${funcName} - NAO encontrada`);
        }
    });

    // 2. Verificar se o modal existe
    const modal = document.getElementById('whatsappModal');
    if (modal) {
        console.log('✅ Modal WhatsApp encontrado');
    } else {
        console.error('❌ Modal WhatsApp NAO encontrado');
    }

    // 3. Verificar se o formulário existe
    const form = document.getElementById('whatsappForm');
    if (form) {
        console.log('✅ Formulario WhatsApp encontrado');
    } else {
        console.error('❌ Formulario WhatsApp NAO encontrado');
    }

    // 4. Verificar botões flutuantes
    const floatingButtons = document.querySelectorAll('.floating-btn');
    console.log(`📱 Botões flutuantes encontrados: ${floatingButtons.length}`);

    floatingButtons.forEach((btn, index) => {
        console.log(`Botão ${index + 1}:`, {
            class: btn.className,
            onclick: btn.onclick ? 'Sim' : 'Não',
            href: btn.href || 'N/A'
        });
    });

    // 5. Verificar sistemas de analytics
    if (typeof checkAnalyticsStatus === 'function') {
        const status = checkAnalyticsStatus();
        console.log('📊 Status dos sistemas:', status);
    }

    // 6. Verificar se há erros JavaScript
    const originalError = console.error;
    let errorCount = 0;

    console.error = function(...args) {
        errorCount++;
        originalError.apply(console, args);
    };

    // 7. Verificar se as funções do modal estão disponíveis (sem testar abertura)
    if (typeof openWhatsAppModal === 'function') {
        console.log('✅ Função openWhatsAppModal disponível');
    } else {
        console.error('❌ Função openWhatsAppModal não encontrada');
    }

    if (typeof closeWhatsAppModal === 'function') {
        console.log('✅ Função closeWhatsAppModal disponível');
    } else {
        console.error('❌ Função closeWhatsAppModal não encontrada');
    }

    console.log('=== FIM DO DEBUG ===');
    console.log(`Total de erros encontrados: ${errorCount}`);

    return {
        functionsAvailable: functions.filter(f => typeof window[f] === 'function').length,
        modalExists: !!modal,
        formExists: !!form,
        floatingButtonsCount: floatingButtons.length,
        errorCount: errorCount
    };
}

// Função para testar envio de evento
function testEventSending() {
    console.log('🧪 Testando envio de evento...');

    if (typeof sendCustomEvent === 'function') {
        const testData = {
            name: 'Teste',
            surname: 'Debug',
            email: 'teste@debug.com',
            phone: '+5511999999999',
            country: 'Brasil',
            cep: '01234-567',
            region: 'São Paulo',
            city: 'São Paulo',
            street: 'Rua Teste, 123',
            service: 'debug',
            source: 'debug_script'
        };

        try {
            sendCustomEvent(testData);
            console.log('✅ Evento de teste enviado com sucesso');
            return true;
        } catch (error) {
            console.error('❌ Erro ao enviar evento:', error);
            return false;
        }
    } else {
        console.error('❌ Função sendCustomEvent não encontrada');
        return false;
    }
}

// Função para verificar problemas de codificação
function checkEncodingIssues() {
    console.log('🔍 Verificando problemas de codificação...');

    const scripts = document.querySelectorAll('script');
    let encodingIssues = 0;

    scripts.forEach((script, index) => {
        if (script.textContent) {
            // Verificar caracteres não-ASCII problemáticos
            const problematicChars = script.textContent.match(/[^\x20-\x7E\n\r\t]/g);
            if (problematicChars) {
                console.warn(`⚠️ Script ${index + 1} contém caracteres problemáticos:`, problematicChars.slice(0, 10));
                encodingIssues++;
            }
        }
    });

    console.log(`Total de problemas de codificação encontrados: ${encodingIssues}`);
    return encodingIssues;
}

// Executar debug automaticamente
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(debugAnalyticsSystem, 1000);
    });
} else {
    setTimeout(debugAnalyticsSystem, 1000);
}

// Disponibilizar funções globalmente
window.debugAnalyticsSystem = debugAnalyticsSystem;
window.testEventSending = testEventSending;
window.checkEncodingIssues = checkEncodingIssues;

console.log('🔧 Script de debug carregado. Use debugAnalyticsSystem() para executar o debug completo.');