@extends('main-Thema.layouts.app')


@section('content')
<!-- Page Header -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold">
                    <i class="fas fa-rocket text-warning me-3 rocket-logo"></i>
                    Sobre o Sistema
                </h1>
                <p class="lead">Conheça todas as funcionalidades e capacidades da plataforma Templats-link</p>
            </div>
        </div>
    </div>
</section>

<!-- System Overview -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">O que é o Templats-link?</h2>
                <p class="lead text-muted mb-4">
                    O Templats-link é uma plataforma revolucionária para gerenciamento de templates dinâmicos, 
                    desenvolvida com Laravel 10 e PHP 8.1+. Oferece uma solução completa para criação, 
                    instalação e gerenciamento de temas personalizados.
                </p>
                <p class="text-muted">
                    Nossa plataforma foi desenvolvida para ser a solução mais completa do mercado, 
                    combinando facilidade de uso com funcionalidades avançadas de desenvolvimento web.
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-cogs text-primary" style="font-size: 200px; opacity: 0.3;"></i>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-lg-6 order-lg-2">
                <h2 class="display-6 fw-bold mb-4">Funcionalidades Principais</h2>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-upload text-primary me-3"></i>
                            <div>
                                <h6 class="mb-1">Upload de Temas</h6>
                                <p class="text-muted small mb-0">Sistema completo de upload e instalação de temas via ZIP</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-eye text-success me-3"></i>
                            <div>
                                <h6 class="mb-1">Preview em Tempo Real</h6>
                                <p class="text-muted small mb-0">Visualização instantânea dos temas antes da ativação</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-search text-warning me-3"></i>
                            <div>
                                <h6 class="mb-1">SEO Automático</h6>
                                <p class="text-muted small mb-0">Geração automática de sitemap e robots.txt</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-1 text-center">
                <i class="fas fa-rocket text-success" style="font-size: 200px; opacity: 0.3;"></i>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">Recursos Avançados</h2>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-shield-alt text-success me-3"></i>
                        <strong>Segurança:</strong> Headers de segurança, validações e proteções avançadas
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-chart-line text-primary me-3"></i>
                        <strong>Analytics:</strong> Google Analytics 4 integrado com eventos customizados
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-blog text-warning me-3"></i>
                        <strong>CMS:</strong> Sistema de blog completo com editor WYSIWYG
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-users text-info me-3"></i>
                        <strong>Leads:</strong> Captura e gerenciamento inteligente de leads
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-star text-warning" style="font-size: 200px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Technical Specifications -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-6 fw-bold">Especificações Técnicas</h2>
                <p class="lead text-muted">Tecnologias e recursos utilizados na plataforma</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fab fa-laravel text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="card-title">Backend</h5>
                        <p class="card-text text-muted">
                            Laravel 10, PHP 8.1+, MySQL, Sistema de rotas dinâmicas e helpers personalizados.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fab fa-bootstrap text-purple" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="card-title">Frontend</h5>
                        <p class="card-text text-muted">
                            Bootstrap 5, JavaScript ES6+, Font Awesome, Design responsivo e moderno.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-cogs text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="card-title">Funcionalidades</h5>
                        <p class="card-text text-muted">
                            Upload de temas, SEO automático, Analytics, Blog CMS e captura de leads.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <h2 class="display-6 fw-bold mb-4">Pronto para experimentar?</h2>
                <p class="lead mb-4">
                    Descubra todas as funcionalidades da plataforma Templats-link e revolucione seu projeto web.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('contato') }}" class="btn btn-warning btn-lg px-5">
                        <i class="fas fa-rocket me-2"></i>Começar Agora
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg px-5">
                        <i class="fas fa-home me-2"></i>Voltar ao Início
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
