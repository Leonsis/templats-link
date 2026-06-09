@extends('dashboard.layouts.admin')

@section('title', 'Editar Formulário de Conteúdo: ' . ucfirst($pagina))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.theme-pages.show', $pagina) }}" class="btn btn-outline-secondary btn-sm me-3">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="fas fa-edit me-2"></i>
                                Editar Formulário de Conteúdo: {{ $formulario->nome }}
                            </h5>
                            <small class="text-muted">Página: {{ ucfirst($pagina) }} | Tema: {{ $temaAtivo }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.theme-pages.content-form.update', [$pagina, $formulario->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="accordion-container">
                        @if(isset($secoes) && count($secoes) > 0)
                            @foreach($secoes as $secaoIndex => $secao)
                                @if($secao && is_array($secao) && isset($secao['secao']))
                                <div class="card mb-4">
                                    <div class="card-header bg-primary accordion-header" 
                                         data-bs-toggle="collapse" 
                                         data-bs-target="#secao-{{ $secaoIndex }}" 
                                         aria-expanded="false" 
                                         aria-controls="secao-{{ $secaoIndex }}"
                                         style="cursor: pointer;">
                                        <h6 class="mb-0 d-flex justify-content-between align-items-center" style="color: #1f293b;">
                                            <span>
                                                <i class="fas fa-section me-2"></i>
                                                Seção {{ $secao['secao'] ?? ($secaoIndex + 1) }}
                                            </span>
                                            <i class="fas fa-chevron-down accordion-arrow ms-2" style="transition: transform 0.3s ease;"></i>
                                        </h6>
                                    </div>
                                    <div id="secao-{{ $secaoIndex }}" 
                                         class="collapse card-body accordion-collapse"
                                         data-bs-parent=".accordion-container">
                                        @if(isset($secao['campos']) && count($secao['campos']) > 0)
                                            @foreach($secao['campos'] as $campoIndex => $campo)
                                                <div class="mb-4 p-3 border rounded">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h6 class="mb-0">
                                                            <i class="fas fa-{{ $campo['tipo'] === 'imagem' ? 'image' : ($campo['tipo'] === 'link' ? 'link' : 'text') }} me-2"></i>
                                                            {{ $campo['label'] }}
                                                        </h6>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" 
                                                                   type="checkbox" 
                                                                   name="{{ $campo['nome'] }}_ativo" 
                                                                   value="1" 
                                                                   id="ativo_{{ $campo['nome'] }}"
                                                                   {{ ($campo['ativo'] ?? '1') == '1' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="ativo_{{ $campo['nome'] }}">
                                                                Ativo
                                                            </label>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($campo['tipo'] === 'imagem')
                                                        <div class="mb-3">
                                                            <label for="{{ $campo['nome'] }}_upload" class="form-label">
                                                                <i class="fas fa-upload me-2"></i>Fazer Upload da Imagem
                                                            </label>
                                                            <input type="file" 
                                                                   class="form-control" 
                                                                   id="{{ $campo['nome'] }}_upload" 
                                                                   name="{{ $campo['nome'] }}_upload" 
                                                                   accept="image/*,image/svg+xml"
                                                                   onchange="previewImage(this, '{{ $campo['nome'] }}_preview')">
                                                            <div class="form-text">
                                                                Formatos aceitos: JPG, PNG, GIF, WEBP, SVG. Tamanho máximo: 5MB
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="{{ $campo['nome'] }}_alt" class="form-label">Texto Alternativo (alt)</label>
                                                            <input type="text" 
                                                                   class="form-control" 
                                                                   id="{{ $campo['nome'] }}_alt" 
                                                                   name="{{ $campo['nome'] }}_alt" 
                                                                   value="{{ $campo['alt'] ?? '' }}"
                                                                   placeholder="Digite o texto alternativo">
                                                        </div>
                                                        
                                                        <div class="mb-3" id="{{ $campo['nome'] }}_preview_container">
                                                            <label class="form-label">Preview:</label>
                                                            <div id="{{ $campo['nome'] }}_preview">
                                                                @if(!empty($campo['src']))
                                                                    <img src="{{ $campo['src'] }}" alt="{{ $campo['alt'] ?? '' }}" class="img-thumbnail" style="max-height: 200px; max-width: 100%;">
                                                                @else
                                                                    <p class="text-muted">Nenhuma imagem selecionada</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                    @elseif($campo['tipo'] === 'link')
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="{{ $campo['nome'] }}_href" class="form-label">URL do Link (href)</label>
                                                                <input type="text" 
                                                                       class="form-control" 
                                                                       id="{{ $campo['nome'] }}_href" 
                                                                       name="{{ $campo['nome'] }}_href" 
                                                                       value="{{ $campo['href'] ?? '' }}"
                                                                       placeholder="Digite a URL do link">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="{{ $campo['nome'] }}_texto" class="form-label">Texto do Link</label>
                                                                <input type="text" 
                                                                       class="form-control" 
                                                                       id="{{ $campo['nome'] }}_texto" 
                                                                       name="{{ $campo['nome'] }}_texto" 
                                                                       value="{{ $campo['texto'] ?? '' }}"
                                                                       placeholder="Digite o texto do link">
                                                            </div>
                                                        </div>
                                                        
                                                    @elseif($campo['tipo'] === 'paragrafo')
                                                        <div class="mb-3">
                                                            <label for="{{ $campo['nome'] }}" class="form-label">Conteúdo do Parágrafo</label>
                                                            <textarea class="form-control" 
                                                                      id="{{ $campo['nome'] }}" 
                                                                      name="{{ $campo['nome'] }}" 
                                                                      rows="4"
                                                                      placeholder="Digite o conteúdo do parágrafo">{{ $campo['valor'] ?? '' }}</textarea>
                                                        </div>
                                                        
                                                    @else
                                                        <div class="mb-3">
                                                            <label for="{{ $campo['nome'] }}" class="form-label">
                                                                @if($campo['tipo'] === 'titulo')
                                                                    Conteúdo do Título
                                                                @else
                                                                    Conteúdo do Texto
                                                                @endif
                                                            </label>
                                                            <input type="text" 
                                                                   class="form-control" 
                                                                   id="{{ $campo['nome'] }}" 
                                                                   name="{{ $campo['nome'] }}" 
                                                                   value="{{ $campo['valor'] ?? '' }}"
                                                                   placeholder="Digite o conteúdo">
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Nenhum campo encontrado nesta seção.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Nenhuma seção encontrada neste formulário.
                            </div>
                        @endif
                        
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                            <a href="{{ route('dashboard.theme-pages.show', $pagina) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.accordion-header {
    user-select: none;
    transition: background-color 0.2s ease;
}

.accordion-header:hover {
    background-color: rgba(0, 0, 0, 0.1) !important;
}

.accordion-arrow {
    transition: transform 0.3s ease;
}

.accordion-header[aria-expanded="false"] .accordion-arrow {
    transform: rotate(-90deg);
}

.accordion-header[aria-expanded="true"] .accordion-arrow {
    transform: rotate(0deg);
}

.accordion-collapse {
    transition: height 0.35s ease;
}

.card-header.bg-primary {
    color: white;
}
</style>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail" style="max-height: 200px; max-width: 100%;">';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = '<p class="text-muted">Nenhuma imagem selecionada</p>';
    }
}

// Inicializar acordeon quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    // Adicionar event listeners para atualizar a seta quando o acordeon abrir/fechar
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    accordionHeaders.forEach(header => {
        const targetId = header.getAttribute('data-bs-target');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
            // Usar evento do Bootstrap Collapse
            targetElement.addEventListener('show.bs.collapse', function() {
                header.setAttribute('aria-expanded', 'true');
            });
            
            targetElement.addEventListener('hide.bs.collapse', function() {
                header.setAttribute('aria-expanded', 'false');
            });
        }
    });
});
</script>
@endsection

