@extends('main-Thema.layouts.app')


@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Bem-vindo ao Templats-link</h1>
                <p class="lead mb-4">
                    Sua plataforma completa para templates, soluções web e desenvolvimento de sites profissionais.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('sobre') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-info-circle me-2"></i>Saiba Mais
                    </a>
                    <a href="{{ route('contato') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>Entre em Contato
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-rocket" style="font-size: 200px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Nossos Serviços</h2>
                <p class="lead text-muted">Oferecemos soluções completas para seu projeto web</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-palette text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Templates Modernos</h5>
                        <p class="card-text text-muted">
                            Templates responsivos e modernos para todos os tipos de projetos web.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-code text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Desenvolvimento Web</h5>
                        <p class="card-text text-muted">
                            Desenvolvimento de sites e aplicações web com as melhores tecnologias.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-mobile-alt text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Design Responsivo</h5>
                        <p class="card-text text-muted">
                            Todos os nossos projetos são totalmente responsivos e otimizados.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <h2 class="display-6 fw-bold mb-4">Pronto para começar seu projeto?</h2>
                <p class="lead text-muted mb-4">
                    Entre em contato conosco e vamos transformar sua ideia em realidade.
                </p>
                <a href="{{ route('contato') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane me-2"></i>Iniciar Projeto
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
