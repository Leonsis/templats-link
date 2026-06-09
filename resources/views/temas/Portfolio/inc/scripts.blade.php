<!-- JavaScript otimizado - carregar de forma assíncrona -->
<script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=68b82d5cdad81f04d6aadee1" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous" defer></script>
<script src="{{ asset('temas/Portfolio/assets/js/main.js') }}" type="text/javascript" defer></script>
<!-- <script>
// Garantir que o Webflow seja inicializado após carregamento dos scripts deferidos
window.addEventListener('load', function() {
    if (typeof window.Webflow !== 'undefined') {
        window.Webflow.push(function() {
            console.log('Webflow inicializado com sucesso');
        });
    } else {
        console.log('Webflow não encontrado');
    }
});
</script> -->

<!-- Analytics System - Templats-Link -->
<script src="{{ asset('js/analytics.js') }}" defer></script>

<!-- Contact Form Analytics -->
@include('analytics.contact-form-script')

<!-- Test Analytics (temporário) -->
@if(config('app.debug'))
{{-- Scripts de teste removidos da produção --}}
{{-- <script src="{{ asset('js/test-analytics.js') }}"></script> --}}
{{-- <script src="{{ asset('js/debug-analytics.js') }}"></script> --}}
@endif

<!-- Suprimir erros de recursos bloqueados no console -->
<script>
(function() {
    'use strict';
    
    // Interceptar e filtrar mensagens de erro no console
    const originalError = console.error;
    const originalWarn = console.warn;
    
    console.error = function(...args) {
        const message = args.join(' ');
        const blockedPatterns = [
            'ERR_BLOCKED_BY_CLIENT',
            'doubleclick.net',
            'googleads.g.doubleclick.net',
            'googleadservices.com',
            'Failed to load resource: net::ERR_BLOCKED_BY_CLIENT'
        ];
        
        // Não exibir erros de recursos bloqueados
        if (!blockedPatterns.some(pattern => message.includes(pattern))) {
            originalError.apply(console, args);
        }
    };
    
    console.warn = function(...args) {
        const message = args.join(' ');
        const blockedPatterns = [
            'ERR_BLOCKED_BY_CLIENT',
            'doubleclick',
            'googleads'
        ];
        
        // Não exibir avisos de recursos bloqueados
        if (!blockedPatterns.some(pattern => message.includes(pattern))) {
            originalWarn.apply(console, args);
        }
    };
    
    // Suprimir promessas rejeitadas de recursos bloqueados
    window.addEventListener('unhandledrejection', function(e) {
        if (e.reason) {
            const message = e.reason.message || e.reason.toString() || '';
            const blockedPatterns = [
                'NetworkError',
                'ERR_BLOCKED_BY_CLIENT',
                'doubleclick',
                'googleads',
                'A network error occurred'
            ];
            
            if (blockedPatterns.some(pattern => message.includes(pattern))) {
                e.preventDefault();
                return false;
            }
        }
    });
    
    // Interceptar erros de recursos (imagens, scripts, etc.)
    window.addEventListener('error', function(e) {
        if (e.target && e.target.tagName) {
            const url = e.target.src || e.target.href || '';
            const blockedDomains = [
                'doubleclick.net',
                'googleadservices.com',
                'googleads.g.doubleclick.net'
            ];
            
            // Suprimir erros de recursos bloqueados
            if (blockedDomains.some(domain => url.includes(domain))) {
                e.stopImmediatePropagation();
                return false;
            }
        }
        
        // Suprimir erros de conexão do YouTube que não são críticos
        if (e.message && e.message.includes('ERR_CONNECTION_CLOSED')) {
            const target = e.target;
            if (target && target.tagName === 'IFRAME' && target.src && target.src.includes('youtube.com')) {
                e.stopImmediatePropagation();
                return false;
            }
        }
    }, true);
    
    // Tratamento de erro para iframes do YouTube
    document.addEventListener('DOMContentLoaded', function() {
        const youtubeIframes = document.querySelectorAll('iframe[src*="youtube.com"]');
        youtubeIframes.forEach(function(iframe) {
            iframe.addEventListener('error', function(e) {
                // Silenciar erros de iframe do YouTube
                e.stopImmediatePropagation();
                return false;
            }, true);
        });
    });
})();
</script>

<!-- CLS Prevention Script - Prevenir Layout Shift -->
<script>
(function() {
    'use strict';
    
    // Função para garantir que imagens reservem espaço e não causem CLS
    function preventImageLayoutShift() {
        const images = document.querySelectorAll('img:not([width]):not([height])');
        
        images.forEach(function(img) {
            // Se a imagem já carregou, definir dimensões
            if (img.complete && img.naturalWidth && img.naturalHeight) {
                if (!img.hasAttribute('width') && !img.hasAttribute('height')) {
                    // Usar aspect-ratio se disponível
                    if (!img.style.aspectRatio) {
                        const ratio = img.naturalHeight / img.naturalWidth;
                        img.style.aspectRatio = `${img.naturalWidth} / ${img.naturalHeight}`;
                    }
                }
            } else {
                // Para imagens ainda carregando, adicionar listener
                img.addEventListener('load', function() {
                    if (this.naturalWidth && this.naturalHeight) {
                        if (!this.hasAttribute('width') && !this.hasAttribute('height')) {
                            const ratio = this.naturalHeight / this.naturalWidth;
                            this.style.aspectRatio = `${this.naturalWidth} / ${this.naturalHeight}`;
                        }
                    }
                }, { once: true });
            }
        });
    }
    
    // Função para corrigir elementos com opacity:0 que não foram animados
    function fixOpacityElements() {
        // Encontrar todos os elementos com style="opacity:0" e data-w-id
        const opacityElements = document.querySelectorAll('[data-w-id][style*="opacity:0"], [data-w-id][style*="opacity: 0"]');
        
        opacityElements.forEach(function(element) {
            // Garantir que o elemento reserve espaço mesmo invisível
            const computedStyle = window.getComputedStyle(element);
            const height = computedStyle.height;
            const minHeight = computedStyle.minHeight;
            
            // Se não tem altura mínima, adicionar baseado no conteúdo
            if (minHeight === '0px' || minHeight === 'auto') {
                // Tentar calcular altura baseada no conteúdo
                const rect = element.getBoundingClientRect();
                if (rect.height > 0) {
                    element.style.minHeight = rect.height + 'px';
                }
            }
            
            // Remover a classe de fallback se já existir
            element.classList.remove('opacity-fallback');
            
            // Aguardar um tempo para o Webflow animar, se não animar, forçar opacidade
            setTimeout(function() {
                // Verificar se o elemento ainda está com opacity:0
                const computedStyle = window.getComputedStyle(element);
                const opacity = parseFloat(computedStyle.opacity);
                
                // Se ainda estiver invisível após 2 segundos, forçar aparecer
                if (opacity === 0 || opacity < 0.01) {
                    element.style.opacity = '1';
                    element.style.visibility = 'visible';
                    element.style.transition = 'opacity 0.5s ease-in-out';
                }
            }, 2000);
        });
    }
    
    // Função para garantir que seções principais tenham altura mínima
    function ensureSectionHeights() {
        const sections = document.querySelectorAll('.hero-section, .section, .service-card, .service-card2');
        
        sections.forEach(function(section) {
            const rect = section.getBoundingClientRect();
            if (rect.height > 0) {
                const computedStyle = window.getComputedStyle(section);
                const minHeight = parseFloat(computedStyle.minHeight);
                
                // Se a altura real é maior que min-height, atualizar
                if (rect.height > minHeight) {
                    section.style.minHeight = rect.height + 'px';
                }
            }
        });
    }
    
    // Executar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            preventImageLayoutShift();
            fixOpacityElements();
            ensureSectionHeights();
            
            // Executar novamente após um delay
            setTimeout(function() {
                preventImageLayoutShift();
                fixOpacityElements();
                ensureSectionHeights();
            }, 1000);
        });
    } else {
        // DOM já está carregado
        preventImageLayoutShift();
        fixOpacityElements();
        ensureSectionHeights();
        setTimeout(function() {
            preventImageLayoutShift();
            fixOpacityElements();
            ensureSectionHeights();
        }, 1000);
    }
    
    // Executar após todas as imagens carregarem
    window.addEventListener('load', function() {
        preventImageLayoutShift();
        ensureSectionHeights();
    });
    
    // Fallback adicional: se após 3 segundos ainda houver elementos invisíveis, forçar
    setTimeout(function() {
        const remainingElements = document.querySelectorAll('[data-w-id][style*="opacity:0"], [data-w-id][style*="opacity: 0"]');
        remainingElements.forEach(function(element) {
            const computedStyle = window.getComputedStyle(element);
            const opacity = parseFloat(computedStyle.opacity);
            if (opacity === 0 || opacity < 0.01) {
                element.style.opacity = '1';
                element.style.visibility = 'visible';
                element.style.transition = 'opacity 0.5s ease-in-out';
            }
        });
    }, 3000);
})();
</script>

<!-- Accordion Fix Script -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando accordions...');
    
    // Aguardar um pouco para o Webflow carregar
    setTimeout(function() {
        // Verificar se o Webflow está carregado
        if (typeof window.Webflow === 'undefined') {
            console.log('Webflow não carregado, inicializando accordions manualmente...');
            
            // Inicializar accordions manualmente
            const accordions = document.querySelectorAll('.faq6_accordion');
            console.log('Encontrados', accordions.length, 'accordions');
            
            accordions.forEach(function(accordion, index) {
                const question = accordion.querySelector('.faq6_question');
                const answer = accordion.querySelector('.faq6_answer');
                
                if (question && answer) {
                    console.log('Configurando accordion', index + 1);
                    
                    // Adicionar indicador visual de que é clicável
                    question.style.cursor = 'pointer';
                    
                    question.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('Accordion clicado:', index + 1);
                        
                        const isOpen = answer.style.height !== '0px' && answer.style.height !== '';
                        
                        if (isOpen) {
                            // Fechar
                            console.log('Fechando accordion');
                            answer.style.height = '0px';
                            answer.style.overflow = 'hidden';
                            answer.style.opacity = '0';
                        } else {
                            // Abrir
                            console.log('Abrindo accordion');
                            answer.style.height = 'auto';
                            answer.style.overflow = 'visible';
                            answer.style.opacity = '1';
                        }
                    });
                }
            });
        } else {
            console.log('Webflow carregado, accordions devem funcionar automaticamente');
        }
    }, 1000);
});
</script> -->