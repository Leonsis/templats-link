@extends('main-Thema.layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Blog - ' . config('app.name'))
@section('meta_description', 'Confira as últimas postagens do nosso blog com dicas, tutoriais e novidades.')
@section('meta_keywords', 'blog, postagens, artigos, dicas, tutoriais')

@section('content')
<!-- Cabeçalho do Blog -->
<section class="py-5 bg-primary text-white mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold">Nosso Blog</h1>
                <p class="lead">Confira as últimas postagens e fique por dentro das novidades</p>
            </div>
        </div>
    </div>
</section>
<div class="container py-5">
    <div class="row">
        <div class="col-12">                        
            <!-- Listagem de Posts -->
            @if($posts->count() > 0)
                <div class="row g-4">
                    @foreach($posts as $post)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 shadow-sm border-0">
                                <!-- Imagem do Post -->
                                @if($post->imagem_apresentacao)
                                    <div class="card-img-top-container" style="height: 250px; overflow: hidden;">
                                        <img src="{{ $post->imagem_url }}" 
                                             alt="{{ $post->titulo }}" 
                                             class="card-img-top h-100 w-100" 
                                             style="object-fit: cover;">
                                    </div>
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 250px;">
                                        <i class="fas fa-image text-muted fa-3x"></i>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <!-- Título -->
                                    <h5 class="card-title fw-bold text-dark mb-3">
                                        {{ $post->titulo }}
                                    </h5>

                                    <!-- Meta Description (Resumo) -->
                                    <p class="card-text text-muted flex-grow-1">
                                        {{ $post->meta_description ? Str::limit($post->meta_description, 120) : Str::limit(strip_tags($post->conteudo), 120) }}
                                    </p>

                                    <!-- Autor e Data de Publicação -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $post->autor ?: 'Autor não informado' }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ $post->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>

                                    <!-- Botão para ler mais -->
                                    <a href="{{ route('blog.public.show', $post->slug) }}" 
                                       class="btn btn-primary btn-sm mt-auto">
                                        <i class="fas fa-arrow-right me-1"></i>
                                        Ler Mais
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                @if($posts->hasPages())
                    <div class="row mt-5">
                        <div class="col-12">
                            <nav aria-label="Navegação do blog">
                                {{ $posts->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                    </div>
                @endif
            @else
                <!-- Estado vazio -->
                <div class="text-center py-5">
                    <i class="fas fa-blog text-muted fa-4x mb-4"></i>
                    <h3 class="text-muted">Nenhuma postagem encontrada</h3>
                    <p class="text-muted">Ainda não há postagens publicadas.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Estilos adicionais -->
<style>
/* Estilos dos cards */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.card-img-top-container {
    position: relative;
    overflow: hidden;
}

.card-img-top {
    transition: transform 0.3s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-2px);
}
</style>
@endsection
