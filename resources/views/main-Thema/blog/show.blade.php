@extends('main-Thema.layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', ($post->meta_title ?: $post->titulo) . ' - ' . config('app.name'))
@section('meta_description', $post->meta_description ?: Str::limit(strip_tags($post->conteudo), 160))
@section('meta_keywords', $post->meta_keywords)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Conteúdo Principal -->
        <div class="col-lg-8">
            <article class="blog-post">
                <!-- Cabeçalho do Post -->
                <header class="mb-4">
                    <h1 class="display-5 fw-bold text-dark mb-3">{{ $post->titulo }}</h1>
                    
                    <!-- Meta informações -->
                    <div class="d-flex flex-wrap align-items-center text-muted mb-4">
                        <span class="me-4">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ $post->created_at->format('d/m/Y') }}
                        </span>
                        <span class="me-4">
                            <i class="fas fa-clock me-1"></i>
                            {{ $post->created_at->format('H:i') }}
                        </span>
                        <span>
                            <i class="fas fa-user me-1"></i>
                            {{ $post->autor ?: 'Autor não informado' }}
                        </span>
                    </div>
                </header>

                <!-- Imagem de Apresentação -->
                @if($post->imagem_apresentacao)
                    <div class="mb-4">
                        <img src="{{ $post->imagem_url }}" 
                             alt="{{ $post->titulo }}" 
                             class="img-fluid rounded shadow"
                             style="width: 100%; height: 400px; object-fit: cover;">
                    </div>
                @endif

                <!-- Conteúdo do Post -->
                <div class="blog-content">
                    {!! $post->conteudo !!}
                </div>

                <!-- Tags/Categorias (se implementado futuramente) -->
                @if($post->meta_keywords)
                    <div class="mt-4">
                        <h6 class="text-muted mb-2">Tags:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(explode(',', $post->meta_keywords) as $keyword)
                                <span class="badge bg-light text-dark border">{{ trim($keyword) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <!-- Posts Relacionados -->
                @if($relatedPosts->count() > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-newspaper me-2"></i>
                                Posts Relacionados
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($relatedPosts as $relatedPost)
                                <div class="border-bottom p-3 {{ $loop->last ? '' : 'border-bottom' }}">
                                    <div class="d-flex">
                                        @if($relatedPost->imagem_apresentacao)
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ $relatedPost->imagem_url }}" 
                                                     alt="{{ $relatedPost->titulo }}"
                                                     class="rounded" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="{{ route('blog.public.show', $relatedPost->slug) }}" 
                                                   class="text-decoration-none text-dark">
                                                    {{ Str::limit($relatedPost->titulo, 50) }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                {{ $relatedPost->created_at->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Card de Navegação -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="card-title">Gostou do conteúdo?</h6>
                        <p class="card-text text-muted small">
                            Confira outros posts do nosso blog
                        </p>
                        <a href="{{ route('blog.public.index') }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar ao Blog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos específicos para o conteúdo do blog -->
<style>
.blog-content {
    line-height: 1.8;
    font-size: 1.1rem;
}

.blog-content h1,
.blog-content h2,
.blog-content h3,
.blog-content h4,
.blog-content h5,
.blog-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #333;
}

.blog-content h2 {
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
}

.blog-content p {
    margin-bottom: 1.5rem;
}

.blog-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin: 1.5rem 0;
}

.blog-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #666;
}

.blog-content ul,
.blog-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.blog-content li {
    margin-bottom: 0.5rem;
}

.blog-content code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
}

.blog-content pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.blog-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
}

.blog-content th,
.blog-content td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    text-align: left;
}

.blog-content th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.sticky-top {
    position: sticky;
    top: 2rem;
}

.card {
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
</style>
@endsection
