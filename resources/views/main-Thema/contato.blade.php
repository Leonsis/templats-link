@extends('main-Thema.layouts.app')


@section('content')
<!-- Page Header -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold">
                    <i class="fas fa-rocket text-warning me-3 rocket-logo"></i>
                    Entre em Contato
                </h1>
                <p class="lead">Fale conosco sobre o sistema Templats-link e suas funcionalidades</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="py-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- System Info Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h3 class="fw-bold mb-3">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Sobre o Sistema Templats-link
                                </h3>
                                <p class="lead text-muted mb-3">
                                    Plataforma completa para gerenciamento de templates dinâmicos, 
                                    desenvolvida com Laravel 10 e PHP 8.1+.
                                </p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Upload de temas via ZIP</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>SEO automático</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Analytics integrado</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Sistema de blog</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center">
                                <i class="fas fa-cube text-primary" style="font-size: 150px; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            Envie sua Mensagem
                        </h3>
                        
                        <form method="POST" id="mainContactForm" action="{{ route('leads.store') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nome" class="form-label">Nome Completo *</label>                                    
                                    <input type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" placeholder="Nome" required>                                    
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">E-mail *</label>                                    
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" required>                                    
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>                                
                                <input type="tel" class="form-control @error('telefone') is-invalid @enderror" name="telefone" placeholder="Telefone" required>                
                            </div>                                                        
                            
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Mensagem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            Informações de Contato
                        </h5>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-envelope text-primary me-3"></i>
                                <div>
                                    <strong>E-mail</strong><br>
                                    <a href="mailto:contato@templats-link.com" class="text-decoration-none">
                                        contato@templats-link.com
                                    </a>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-phone text-primary me-3"></i>
                                <div>
                                    <strong>Telefone</strong><br>
                                    <a href="tel:+5511999999999" class="text-decoration-none">
                                        (11) 99999-9999
                                    </a>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-3"></i>
                                <div>
                                    <strong>Endereço</strong><br>
                                    São Paulo, SP - Brasil
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-primary me-3"></i>
                                <div>
                                    <strong>Horário de Atendimento</strong><br>
                                    Segunda a Sexta: 9h às 18h
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-share-alt me-2 text-primary"></i>
                            Redes Sociais
                        </h5>
                        
                        <div class="d-flex gap-3">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-6 fw-bold">Perguntas Frequentes</h2>
                <p class="lead text-muted">Dúvidas sobre o sistema Templats-link</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Como funciona o sistema de templates dinâmicos?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                O sistema permite upload de temas via arquivo ZIP, instalação automática de assets, 
                                preview em tempo real e ativação com um clique. Tudo é gerenciado através do painel administrativo.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                O sistema tem SEO automático?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sim! O sistema gera automaticamente sitemap.xml, robots.txt, meta tags de SEO 
                                e oferece configurações avançadas para otimização nos motores de busca.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Quais tecnologias são utilizadas?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                O sistema é construído com Laravel 10, PHP 8.1+, Bootstrap 5, JavaScript ES6+, 
                                MySQL e inclui integração com Google Analytics 4.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                O sistema inclui captura de leads?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sim! O sistema possui formulários inteligentes, notificações automáticas por email, 
                                dashboard de gerenciamento de leads e integração com analytics para tracking.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Capturar submit do formulário de contato principal
document.addEventListener('DOMContentLoaded', function() {
    const mainContactForm = document.getElementById('mainContactForm');
    
    if (mainContactForm) {
        mainContactForm.addEventListener('submit', function(e) {
            // Capturar dados do formulário
            const formData = {
                nome: mainContactForm.querySelector('input[name="nome"]').value,
                email: mainContactForm.querySelector('input[name="email"]').value,
                telefone: mainContactForm.querySelector('input[name="telefone"]').value
            };
            
            // Enviar evento de form_submit para analytics
            if (typeof captureFormSubmit === 'function') {
                captureFormSubmit(
                    'mainContactForm',
                    'Formulário de Contato Principal',
                    formData,
                    'contact_page'
                );
            }
            
            // Também enviar evento de lead capturado
            if (typeof captureContactForm === 'function') {
                captureContactForm(formData, 'contact_page');
            }
            
            console.log('Formulário principal de contato submetido:', formData);
        });
    }
});
</script>
@endsection
