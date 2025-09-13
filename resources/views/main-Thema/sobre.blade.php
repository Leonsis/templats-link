@extends('main-Thema.layouts.app')


@section('content')
<!-- Page Header -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold">Sobre Nós</h1>
                <p class="lead">Conheça nossa história e missão</p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">Nossa História</h2>
                <p class="lead text-muted mb-4">
                    O Templats-link nasceu da paixão por criar soluções web inovadoras e acessíveis. 
                    Desde nossa fundação, temos o compromisso de oferecer templates e serviços de 
                    desenvolvimento web de alta qualidade.
                </p>
                <p class="text-muted">
                    Nossa equipe é formada por profissionais experientes e apaixonados por tecnologia, 
                    sempre em busca das melhores práticas e tecnologias mais recentes do mercado.
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-users" style="font-size: 200px; color: #6c757d; opacity: 0.3;"></i>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-lg-6 order-lg-2">
                <h2 class="display-6 fw-bold mb-4">Nossa Missão</h2>
                <p class="lead text-muted mb-4">
                    Democratizar o acesso a soluções web de qualidade, oferecendo templates modernos 
                    e serviços de desenvolvimento que atendam às necessidades de nossos clientes.
                </p>
                <p class="text-muted">
                    Acreditamos que toda empresa, independente do tamanho, merece ter uma presença 
                    digital profissional e eficiente.
                </p>
            </div>
            <div class="col-lg-6 order-lg-1 text-center">
                <i class="fas fa-bullseye" style="font-size: 200px; color: #6c757d; opacity: 0.3;"></i>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">Nossos Valores</h2>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <strong>Qualidade:</strong> Entregamos sempre o melhor resultado possível
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <strong>Inovação:</strong> Estamos sempre atualizados com as últimas tecnologias
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <strong>Compromisso:</strong> Cumprimos prazos e expectativas dos nossos clientes
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <strong>Suporte:</strong> Oferecemos suporte contínuo e personalizado
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-heart" style="font-size: 200px; color: #6c757d; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-6 fw-bold">Nossa Equipe</h2>
                <p class="lead text-muted">Profissionais dedicados ao seu sucesso</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-user-circle text-primary" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="card-title">Desenvolvedores</h5>
                        <p class="card-text text-muted">
                            Especialistas em Laravel, PHP, JavaScript e outras tecnologias modernas.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-paint-brush text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="card-title">Designers</h5>
                        <p class="card-text text-muted">
                            Criativos especializados em UX/UI e design responsivo.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-headset text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="card-title">Suporte</h5>
                        <p class="card-text text-muted">
                            Equipe de suporte sempre pronta para ajudar nossos clientes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <h2 class="display-6 fw-bold mb-4">Vamos trabalhar juntos?</h2>
                <p class="lead text-muted mb-4">
                    Entre em contato conosco e vamos conversar sobre seu projeto.
                </p>
                <a href="{{ route('contato') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-envelope me-2"></i>Entre em Contato
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
