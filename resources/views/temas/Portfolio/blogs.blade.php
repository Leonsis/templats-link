@extends('temas.Portfolio.layouts.app')

@section('html')
  <html data-wf-page="68ae0f4abe2cd95cb8d9c4d3" data-wf-site="68ae0f48be2cd95cb8d9c3c8" lang="pt-BR">
@endsection

@section('content')
<style>
  .therapy-post-card-title {
      max-width: unset !important;
  }
</style>
<div class="page-wrap">
  <section class="section top">
    <div class="banner-content-area">
      <div class="w-layout-blockcontainer container w-container">
        <div data-w-id="32f9da5e-db9a-38b1-7525-80f534e0296d" style="opacity:0" class="banner-title-wrap">
          <h1 class="banner-title">Nossos <br><span class="banner-title-sub-text">Blogs</span></h1>
        </div>
      </div>
    </div>
  </section>
  
  <section class="therapy-card-section" style="background: #ffffff;">
    <div class="w-layout-blockcontainer container w-container">
      <div data-w-id="de14bba1-44eb-6a95-77e4-ab80bcf6b8e0" style="opacity:0" class="therapy-card-section-title">
        <h2 class="section-title">Descubra nossos <br><span class="section-title-sub-text">artigos e conteúdos</span></h2>
      </div>
      
      @if(count($blogs) > 0)
        <div class="therapy-card-grid-wrap">
          <div class="w-layout-grid therapy-card-grid">
            @foreach ($blogs as $blog)
              <div data-w-id="de14bba1-44eb-6a95-77e4-ab80bcf6b8e{{ $loop->index + 1 }}" style="opacity:0" class="therapy-post-card">
                <a href="{{ route('blog.public.show', $blog['slug']) }}" class="therapy-post-image-wrap w-inline-block">
                  <img src="{{ $blog['image'] }}" loading="lazy" alt="{{ $blog['title'] }}" class="therapy-post-image">
                </a>
                <div class="therapy-post-title-wrap">
                  <a href="{{ route('blog.public.show', $blog['slug']) }}" class="therapy-post-card-title">{{ $blog['title'] }}</a>
                  @if(!empty($blog['excerpt']))
                    <p class="therapy-post-description">{{ $blog['excerpt'] }}</p>
                  @elseif(!empty($blog['description']))
                    <p class="therapy-post-description">{{ \Illuminate\Support\Str::limit($blog['description'], 120) }}</p>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @else
        <div class="section-title-wrap center-align" style="padding: 60px 0;">
          <p class="mb0" style="color: var(--font-color--content-color);">Nenhum blog encontrado no momento.</p>
        </div>
      @endif
    </div>
  </section>
</div>
@endsection