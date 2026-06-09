@extends('main-Thema.layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <h1 class="display-3 fw-bold mb-4">
                    <i class="fas fa-rocket text-warning me-3" style="font-size: 4rem;"></i>
                    Templats-link
                </h1>
                <p class="lead mb-4 fs-5">
                    A plataforma mais completa para gerenciamento de templates dinâmicos, 
                    desenvolvimento web e soluções digitais profissionais.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('sobre') }}" class="btn btn-warning btn-lg px-4">
                        <i class="fas fa-info-circle me-2"></i>Conheça o Sistema
                    </a>
                    <a href="{{ route('contato') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-envelope me-2"></i>Entre em Contato
                    </a>
                </div>
                <div class="row text-center mt-5">
                    <div class="col-4">
                        <h3 class="fw-bold text-warning">100+</h3>
                        <p class="mb-0">Templates</p>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold text-warning">50+</h3>
                        <p class="mb-0">Funcionalidades</p>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold text-warning">24/7</h3>
                        <p class="mb-0">Suporte</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="position-relative">
                    <i class="fas fa-rocket text-warning" style="font-size: 300px; opacity: 0.8; animation: rocketFloat 3s ease-in-out infinite;"></i>
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <i class="fas fa-star text-white" style="font-size: 100px; animation: starTwinkle 2s ease-in-out infinite;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- System Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Funcionalidades do Sistema</h2>
                <p class="lead text-muted">Descubra todas as capacidades da plataforma Templats-link</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-puzzle-piece text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Templates Dinâmicos</h5>
                        <p class="card-text text-muted">
                            Sistema completo de upload, instalação e gerenciamento de temas personalizados.
                        </p>
                        <ul class="list-unstyled text-start small">
                            <li><i class="fas fa-check text-success me-2"></i>Upload via ZIP</li>
                            <li><i class="fas fa-check text-success me-2"></i>Preview em tempo real</li>
                            <li><i class="fas fa-check text-success me-2"></i>Configurações automáticas</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-cogs text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Painel Administrativo</h5>
                        <p class="card-text text-muted">
                            Dashboard completo para gerenciar todos os aspectos do sistema.
                        </p>
                        <ul class="list-unstyled text-start small">
                            <li><i class="fas fa-check text-success me-2"></i>Gerenciamento de temas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Configurações SEO</li>
                            <li><i class="fas fa-check text-success me-2"></i>Analytics integrado</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-search text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">SEO Avançado</h5>
                        <p class="card-text text-muted">
                            Ferramentas completas para otimização e indexação nos motores de busca.
                        </p>
                        <ul class="list-unstyled text-start small">
                            <li><i class="fas fa-check text-success me-2"></i>Meta tags automáticas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Sitemap dinâmico</li>
                            <li><i class="fas fa-check text-success me-2"></i>Robots.txt automático</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-blog text-info" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Sistema de Blog</h5>
                        <p class="card-text text-muted">
                            CMS integrado para criação e gerenciamento de conteúdo.
                        </p>
                        <ul class="list-unstyled text-start small">
                            <li><i class="fas fa-check text-success me-2"></i>Editor WYSIWYG</li>
                            <li><i class="fas fa-check text-success me-2"></i>SEO otimizado</li>
                            <li><i class="fas fa-check text-success me-2"></i>Upload de imagens</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-users text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Captura de Leads</h5>
                        <p class="card-text text-muted">
                            Sistema completo de captura e gerenciamento de leads.
                        </p>
                        <ul class="list-unstyled text-start small">
                            <li><i class="fas fa-check text-success me-2"></i>Formulários inteligentes</li>
                            <li><i class="fas fa-check text-success me-2"></i>Notificações automáticas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Dashboard de leads</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-chart-line text-purple" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Analytics Integrado</h5>
                        <p class="card-text text-muted">
                            Monitoramento completo com Google Analytics e eventos customizados.
                        </p>
                        <ul class="list-unstyled text-start small">
                            <li><i class="fas fa-check text-success me-2"></i>Google Analytics 4</li>
                            <li><i class="fas fa-check text-success me-2"></i>Eventos personalizados</li>
                            <li><i class="fas fa-check text-success me-2"></i>Dashboard de métricas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technologies Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-6 fw-bold">Tecnologias Utilizadas</h2>
                <p class="lead text-muted">Construído com as melhores tecnologias do mercado</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="p-4">
                    <i class="fab fa-laravel text-danger" style="font-size: 4rem;"></i>
                    <h5 class="mt-3">Laravel 10</h5>
                    <p class="text-muted small">Framework PHP moderno e robusto</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="p-4">
                    <i class="fab fa-php text-primary" style="font-size: 4rem;"></i>
                    <h5 class="mt-3">PHP 8.1+</h5>
                    <p class="text-muted small">Linguagem de programação de alta performance</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="p-4">
                    <i class="fab fa-bootstrap text-purple" style="font-size: 4rem;"></i>
                    <h5 class="mt-3">Bootstrap 5</h5>
                    <p class="text-muted small">Framework CSS responsivo</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="p-4">
                    <i class="fab fa-js-square text-warning" style="font-size: 4rem;"></i>
                    <h5 class="mt-3">JavaScript ES6+</h5>
                    <p class="text-muted small">Interatividade e dinamismo</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">Por que escolher o Templats-link?</h2>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-rocket text-primary fs-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Implementação Rápida</h5>
                                <p class="text-muted">Sistema pronto para uso em minutos, sem necessidade de configurações complexas.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt text-success fs-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Segurança Avançada</h5>
                                <p class="text-muted">Headers de segurança, validações e proteções contra ataques comuns.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-mobile-alt text-warning fs-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Totalmente Responsivo</h5>
                                <p class="text-muted">Todos os templates são otimizados para dispositivos móveis e desktops.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-award text-warning" style="font-size: 200px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <h2 class="display-6 fw-bold mb-4">Pronto para revolucionar seu projeto?</h2>
                <p class="lead mb-4">
                    Experimente a plataforma mais completa para gerenciamento de templates dinâmicos.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('contato') }}" class="btn btn-warning btn-lg px-5">
                        <i class="fas fa-paper-plane me-2"></i>Começar Agora
                    </a>
                    <a href="{{ route('sobre') }}" class="btn btn-outline-light btn-lg px-5">
                        <i class="fas fa-info-circle me-2"></i>Saiba Mais
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
