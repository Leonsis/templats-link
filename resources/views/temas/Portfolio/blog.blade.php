@extends('temas.Portfolio.layouts.app')

@section('content')
<style>
        .blog-header {
          margin-bottom: 1.5rem;
        }
        
        .blog-title {
          font-size: 2rem;
          font-weight: 700;
          margin-bottom: 0.5rem;
        }
        
        .blog-meta {
          font-size: 0.9rem;
          color: #555;
          display: flex;
          gap: 1rem;
        }
        
        .blog-image {
          margin: 2rem 0;
        }
        
        .cover-image {
          width: 100%;
          height: 400px;
          object-fit: cover;
          border-radius: 8px;
        }
        
        .blog-content {
          line-height: 1.6;
        }
        
        .blog-content h2 {
          margin-top: 1.5rem;
          font-size: 1.5rem;
        }

  </style>
  <div class="page-wrap" style="color: #000000 !important;">
      <section class="section top" style="background: #f8f8f8 !important;">
        <div class="container w-container">
          
          <!-- Título e Autor -->
          <div class="blog-header">
            <h1 class="blog-title">{{ $blog['title'] }}</h1>
            <div class="blog-meta">
              <span class="blog-author">{{ $blog['author'] ?? 'Autor' }}</span>
              <span class="blog-date">{{ $blog['date'] ?? date('d M Y') }}</span>
            </div>
          </div>
    
          <!-- Imagem Principal -->
          <div class="blog-image">
            <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}" class="cover-image" loading="lazy">
          </div>
    
          <!-- Conteúdo do Blog -->
          <div class="blog-content">    
            <!-- Conteúdo adicional, se houver -->
            @if(!empty($blog['content']))
            <div class="rich-text w-richtext">
              {!! $blog['content'] !!}
            </div>
            @endif
          </div>
    
        </div>
      </section>
    </div>
@endsection
