@extends('main-Thema.layouts.app')


@section('content')
<!-- Page Header -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold">Entre em Contato</h1>
                <p class="lead">Estamos aqui para ajudar com seu projeto</p>
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

        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            Envie sua Mensagem
                        </h3>
                        
                        <form action="{{ route('contato.enviar') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nome" class="form-label">Nome Completo *</label>
                                    <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                           id="nome" name="nome" value="{{ old('nome') }}" required>
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">E-mail *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="tel" class="form-control @error('telefone') is-invalid @enderror" 
                                       id="telefone" name="telefone" value="{{ old('telefone') }}">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="mensagem" class="form-label">Mensagem *</label>
                                <textarea class="form-control @error('mensagem') is-invalid @enderror" 
                                          id="mensagem" name="mensagem" rows="5" required>{{ old('mensagem') }}</textarea>
                                @error('mensagem')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                <p class="lead text-muted">Tire suas dúvidas mais comuns</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Quanto tempo leva para desenvolver um site?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                O tempo de desenvolvimento varia conforme a complexidade do projeto. Sites simples podem ser entregues em 1-2 semanas, enquanto projetos mais complexos podem levar de 4-8 semanas.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                Vocês oferecem suporte após a entrega?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sim! Oferecemos suporte técnico por 30 dias após a entrega do projeto, e também temos planos de manutenção contínua disponíveis.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Os sites são responsivos?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Todos os nossos projetos são desenvolvidos com design responsivo, garantindo uma excelente experiência em dispositivos móveis, tablets e desktops.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
