/**
 * Sistema de Analytics e Tracking Avançado - Templats-Link
 * Sistema completo de tracking com funcionalidades avançadas
 * Versão: 2.0
 */

// Configurações globais do sistema
const AnalyticsConfig = {
    debug: false, // Reduzido para false para evitar poluição do console
    sessionTimeout: 30 * 60 * 1000, // 30 minutos
    scrollThreshold: 25, // 25% de scroll
    timeThreshold: 10, // 10 segundos na página
    autoTrack: true
};

// Armazenamento de dados da sessão
const SessionData = {
    sessionId: generateSessionId(),
    startTime: Date.now(),
    pageViews: 0,
    events: [],
    userData: null,
    scrollDepth: 0,
    timeOnPage: 0
};

// Gerar ID único da sessão
function generateSessionId() {
    return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

// Função para enviar eventos customizados (reutilizada do modal WhatsApp)
function sendCustomEvent(userData) {
    // Verificar se dataLayer está disponível (Google Tag Manager)
    if (typeof dataLayer !== 'undefined') {
        // Enviar evento customizado com dados do usuário
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
            'source': userData.source || 'unknown',
            'timestamp': new Date().toISOString()
        });

        console.log('Evento customizado enviado para GTM:', userData);
    } else {
        console.warn('Google Tag Manager (dataLayer) não encontrado');
    }

    // Verificar se gtag está disponível (Google Analytics)
    if (typeof gtag !== 'undefined') {
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
            'service': userData.service,
            'source': userData.source || 'unknown'
        });

        console.log('Evento customizado enviado para GA4:', userData);
    } else {
        console.warn('Google Analytics (gtag) não encontrado');
    }
}

/**
 * Função para capturar dados de formulários de contato
 * @param {Object} formData - Dados do formulário
 * @param {string} source - Origem do formulário (footer, floating_button, etc.)
 */
function captureContactForm(formData, source = 'unknown') {
    const eventData = {
        name: formData.name || '',
        surname: formData.surname || '',
        email: formData.email || '',
        phone: formData.phone || formData.telefone || '',
        country: formData.country || 'Brasil',
        cep: formData.cep || '',
        region: formData.region || '',
        city: formData.city || '',
        street: formData.street || '',
        service: formData.service || 'contact',
        source: source
    };

    sendCustomEvent(eventData);
}

/**
 * Função para capturar dados de leads do WhatsApp
 * @param {Object} leadData - Dados do lead
 */
function captureWhatsAppLead(leadData) {
    const eventData = {
        name: leadData.name || '',
        surname: leadData.surname || '',
        email: leadData.email || '',
        phone: leadData.phone || leadData.telefone || '',
        country: leadData.country || 'Brasil',
        cep: leadData.cep || '',
        region: leadData.region || '',
        city: leadData.city || '',
        street: leadData.street || '',
        service: 'whatsapp',
        source: 'floating_button'
    };

    sendCustomEvent(eventData);
}

/**
 * Função para capturar eventos de página
 * @param {string} pageName - Nome da página
 * @param {string} action - Ação realizada
 */
function capturePageEvent(pageName, action = 'view') {
    if (typeof dataLayer !== 'undefined') {
        dataLayer.push({
            'event': 'page_interaction',
            'page_name': pageName,
            'action': action,
            'timestamp': new Date().toISOString()
        });
    }

    if (typeof gtag !== 'undefined') {
        gtag('event', action, {
            'page_name': pageName,
            'event_category': 'page_interaction'
        });
    }
}

/**
 * Função para capturar eventos de submit de formulário
 * @param {string} formId - ID do formulário
 * @param {string} formName - Nome do formulário
 * @param {Object} formData - Dados do formulário
 * @param {string} source - Origem do formulário
 */
function captureFormSubmit(formId, formName, formData = {}, source = 'contact_form') {
    // Evento para Google Tag Manager
    if (typeof dataLayer !== 'undefined') {
        dataLayer.push({
            'event': 'form_submit',
            'form_id': formId,
            'form_name': formName,
            'form_source': source,
            'form_data': formData,
            'timestamp': new Date().toISOString()
        });
    }

    // Evento para Google Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'form_submit', {
            'form_id': formId,
            'form_name': formName,
            'form_source': source,
            'event_category': 'form_interaction',
            'event_label': formName
        });
    }

    console.log('Evento form_submit capturado:', {
        formId,
        formName,
        source,
        formData
    });
}

/**
 * Função para verificar se os sistemas de analytics estão carregados
 */
function checkAnalyticsStatus() {
    const status = {
        gtm: typeof dataLayer !== 'undefined',
        ga4: typeof gtag !== 'undefined',
        timestamp: new Date().toISOString()
    };

    console.log('Status dos sistemas de analytics:', status);
    return status;
}

/**
 * Sistema de tracking avançado de comportamento
 */
class AdvancedAnalytics {
    constructor() {
        this.init();
    }

    init() {
        if (AnalyticsConfig.autoTrack) {
            this.trackPageView();
            this.trackScrollDepth();
            this.trackTimeOnPage();
            this.trackClicks();
            this.trackFormInteractions();
            this.trackSessionData();
        }
    }

    /**
     * Track de visualização de página
     */
    trackPageView() {
        SessionData.pageViews++;
        const pageData = {
            page: window.location.pathname,
            title: document.title,
            referrer: document.referrer,
            sessionId: SessionData.sessionId,
            timestamp: new Date().toISOString()
        };

        this.sendEvent('page_view', pageData);
    }

    /**
     * Track de profundidade de scroll
     */
    trackScrollDepth() {
        let maxScroll = 0;
        const trackScroll = () => {
            const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
            if (scrollPercent > maxScroll) {
                maxScroll = scrollPercent;
                SessionData.scrollDepth = maxScroll;

                // Enviar evento a cada 25% de scroll
                if (maxScroll >= AnalyticsConfig.scrollThreshold && maxScroll % 25 === 0) {
                    this.sendEvent('scroll_depth', {
                        depth: maxScroll,
                        sessionId: SessionData.sessionId
                    });
                }
            }
        };

        window.addEventListener('scroll', trackScroll, { passive: true });
    }

    /**
     * Track de tempo na página
     */
    trackTimeOnPage() {
        const startTime = Date.now();

        setInterval(() => {
            SessionData.timeOnPage = Math.round((Date.now() - startTime) / 1000);

            // Enviar evento a cada 30 segundos
            if (SessionData.timeOnPage % 30 === 0 && SessionData.timeOnPage > 0) {
                this.sendEvent('time_on_page', {
                    seconds: SessionData.timeOnPage,
                    sessionId: SessionData.sessionId
                });
            }
        }, 1000);

        // Track quando sair da página
        window.addEventListener('beforeunload', () => {
            this.sendEvent('page_exit', {
                timeOnPage: SessionData.timeOnPage,
                scrollDepth: SessionData.scrollDepth,
                sessionId: SessionData.sessionId
            });
        });
    }

    /**
     * Track de cliques em elementos
     */
    trackClicks() {
        document.addEventListener('click', (e) => {
            const element = e.target;
            const clickData = {
                element: element.tagName,
                id: element.id || '',
                className: element.className || '',
                text: element.textContent?.substring(0, 100) || '',
                href: element.href || '',
                sessionId: SessionData.sessionId,
                timestamp: new Date().toISOString()
            };

            this.sendEvent('click', clickData);
        });
    }

    /**
     * Track de interações com formulários
     */
    trackFormInteractions() {
        // Track focus em campos
        document.addEventListener('focus', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
                this.sendEvent('form_field_focus', {
                    fieldName: e.target.name || '',
                    fieldType: e.target.type || '',
                    formId: e.target.closest('form')?.id || '',
                    sessionId: SessionData.sessionId
                });
            }
        }, true);

        // Track blur em campos
        document.addEventListener('blur', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
                this.sendEvent('form_field_blur', {
                    fieldName: e.target.name || '',
                    fieldType: e.target.type || '',
                    formId: e.target.closest('form')?.id || '',
                    sessionId: SessionData.sessionId
                });
            }
        }, true);
    }

    /**
     * Track de dados da sessão
     */
    trackSessionData() {
        // Salvar dados da sessão no localStorage
        setInterval(() => {
            localStorage.setItem('analytics_session', JSON.stringify(SessionData));
        }, 5000);

        // Recuperar dados da sessão se existir
        const savedSession = localStorage.getItem('analytics_session');
        if (savedSession) {
            try {
                const parsed = JSON.parse(savedSession);
                if (parsed.sessionId !== SessionData.sessionId) {
                    // Nova sessão
                    this.sendEvent('new_session', {
                        sessionId: SessionData.sessionId,
                        previousSession: parsed.sessionId
                    });
                }
            } catch (e) {
                console.warn('Erro ao recuperar dados da sessão:', e);
            }
        }
    }

    /**
     * Enviar evento para analytics
     */
    sendEvent(eventName, eventData) {
        // Adicionar aos eventos da sessão
        SessionData.events.push({
            name: eventName,
            data: eventData,
            timestamp: new Date().toISOString()
        });

        // Enviar para GTM
        if (typeof dataLayer !== 'undefined') {
            dataLayer.push({
                'event': eventName,
                'session_id': SessionData.sessionId,
                'analytics_data': eventData,
                'timestamp': new Date().toISOString()
            });
        }

        // Enviar para GA4
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, {
                'session_id': SessionData.sessionId,
                'custom_parameters': eventData
            });
        }

        // Log apenas eventos importantes quando debug estiver ativo
        if (AnalyticsConfig.debug && ['page_view', 'form_submit', 'user_provided_data'].includes(eventName)) {
            console.log(`Analytics Event: ${eventName}`, eventData);
        }
    }

    /**
     * Obter dados completos da sessão
     */
    getSessionData() {
        return {
            ...SessionData,
            currentTime: Date.now(),
            totalTime: Date.now() - SessionData.startTime
        };
    }
}

// Inicializar sistema avançado
const advancedAnalytics = new AdvancedAnalytics();

// Exportar funções para uso global
window.sendCustomEvent = sendCustomEvent;
window.captureContactForm = captureContactForm;
window.captureWhatsAppLead = captureWhatsAppLead;
window.capturePageEvent = capturePageEvent;
window.captureFormSubmit = captureFormSubmit;
window.checkAnalyticsStatus = checkAnalyticsStatus;
window.advancedAnalytics = advancedAnalytics;
window.AnalyticsConfig = AnalyticsConfig;
window.SessionData = SessionData;

// Funções utilitárias para desenvolvedores
window.getAnalyticsSession = () => advancedAnalytics.getSessionData();
window.getAnalyticsEvents = () => SessionData.events;
window.clearAnalyticsSession = () => {
    localStorage.removeItem('analytics_session');
    SessionData.events = [];
    SessionData.startTime = Date.now();
};

// Log de inicialização apenas se debug estiver ativo
if (AnalyticsConfig.debug) {
    console.log('🚀 Sistema de Analytics Templats-Link v2.0 carregado');
    console.log('📊 Funcionalidades disponíveis:', {
        'sendCustomEvent': 'Enviar eventos customizados',
        'captureContactForm': 'Capturar formulários de contato',
        'captureWhatsAppLead': 'Capturar leads do WhatsApp',
        'captureFormSubmit': 'Capturar submits de formulários',
        'getAnalyticsSession': 'Obter dados da sessão',
        'getAnalyticsEvents': 'Obter eventos da sessão',
        'clearAnalyticsSession': 'Limpar dados da sessão'
    });
}