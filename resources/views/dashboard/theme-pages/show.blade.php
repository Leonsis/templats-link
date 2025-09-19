@extends('dashboard.layouts.admin')

@section('title', 'Configurar Página: ' . ucfirst($pagina))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard.theme-pages') }}" class="btn btn-outline-secondary btn-sm me-3">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cog me-2"></i>
                                Configurar SEO: {{ ucfirst($pagina) }}
                            </h5>
                            <small class="text-muted">Tema: {{ $temaAtivo }} | Arquivo: {{ $pagina }}.blade.php</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.theme-pages.update', $pagina) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-12 col-lg-8">
                                <div class="mb-4">
                                    <label for="meta_title" class="form-label">
                                        <i class="fas fa-tag me-2"></i>Meta Title
                                        <span class="text-muted">(máximo 60 caracteres)</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('meta_title') is-invalid @enderror" 
                                           id="meta_title" 
                                           name="meta_title" 
                                           value="{{ old('meta_title', $configuracao->meta_title ?? '') }}"
                                           maxlength="60"
                                           placeholder="Digite o título da página para SEO">
                                    <div class="form-text">
                                        <span id="title-counter">0</span>/60 caracteres
                                    </div>
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="meta_description" class="form-label">
                                        <i class="fas fa-align-left me-2"></i>Meta Description
                                        <span class="text-muted">(máximo 160 caracteres)</span>
                                    </label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                              id="meta_description" 
                                              name="meta_description" 
                                              rows="3"
                                              maxlength="160"
                                              placeholder="Digite a descrição da página para SEO">{{ old('meta_description', $configuracao->meta_description ?? '') }}</textarea>
                                    <div class="form-text">
                                        <span id="description-counter">0</span>/160 caracteres
                                    </div>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="meta_keywords" class="form-label">
                                        <i class="fas fa-key me-2"></i>Meta Keywords
                                        <span class="text-muted">(máximo 255 caracteres)</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('meta_keywords') is-invalid @enderror" 
                                           id="meta_keywords" 
                                           name="meta_keywords" 
                                           value="{{ old('meta_keywords', $configuracao->meta_keywords ?? '') }}"
                                           maxlength="255"
                                           placeholder="Digite as palavras-chave separadas por vírgula">
                                    <div class="form-text">
                                        <span id="keywords-counter">0</span>/255 caracteres
                                    </div>
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12 col-lg-4 mt-4 mt-lg-0">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Dicas de SEO
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <h6 class="text-primary">Meta Title</h6>
                                            <ul class="list-unstyled small">
                                                <li>• Use 50-60 caracteres</li>
                                                <li>• Inclua palavras-chave principais</li>
                                                <li>• Seja descritivo e atrativo</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <h6 class="text-info">Meta Description</h6>
                                            <ul class="list-unstyled small">
                                                <li>• Use 150-160 caracteres</li>
                                                <li>• Resuma o conteúdo da página</li>
                                                <li>• Inclua call-to-action</li>
                                            </ul>
                                        </div>
                                        
                                        <div>
                                            <h6 class="text-warning">Meta Keywords</h6>
                                            <ul class="list-unstyled small">
                                                <li>• Separe por vírgulas</li>
                                                <li>• Use 5-10 palavras-chave</li>
                                                <li>• Seja específico e relevante</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                            <a href="{{ route('dashboard.theme-pages') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Configurar SEO
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Seção de Formulários de Conteúdo -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Formulários de Conteúdo
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Aviso sobre Requisitos -->
                    <div class="alert alert-warning alert-static" data-static="true">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Requisito para Formulários:</strong><br>
                        Para que seja possível a criação do formulário, cada seção tem que ter uma classe "sec" para que identifique cada seção.
                        <br><br>
                        <strong>Exemplo:</strong><br>
                        <code class="ms-2">&lt;div class="sec"&gt;...&lt;/div&gt;</code> ou <code class="ms-2">&lt;section class="sec"&gt;...&lt;/section&gt;</code>
                    </div>

                    <!-- Botão Criar Formulário de Conteúdo -->
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-success btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Criar Formulário de Conteúdo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contador de caracteres para Meta Title
    const titleInput = document.getElementById('meta_title');
    const titleCounter = document.getElementById('title-counter');
    
    titleInput.addEventListener('input', function() {
        const length = this.value.length;
        titleCounter.textContent = length;
        
        if (length > 60) {
            titleCounter.classList.add('text-danger');
        } else if (length > 50) {
            titleCounter.classList.add('text-warning');
        } else {
            titleCounter.classList.remove('text-danger', 'text-warning');
        }
    });
    
    // Inicializar contador
    titleInput.dispatchEvent(new Event('input'));
    
    // Contador de caracteres para Meta Description
    const descriptionInput = document.getElementById('meta_description');
    const descriptionCounter = document.getElementById('description-counter');
    
    descriptionInput.addEventListener('input', function() {
        const length = this.value.length;
        descriptionCounter.textContent = length;
        
        if (length > 160) {
            descriptionCounter.classList.add('text-danger');
        } else if (length > 150) {
            descriptionCounter.classList.add('text-warning');
        } else {
            descriptionCounter.classList.remove('text-danger', 'text-warning');
        }
    });
    
    // Inicializar contador
    descriptionInput.dispatchEvent(new Event('input'));
    
    // Contador de caracteres para Meta Keywords
    const keywordsInput = document.getElementById('meta_keywords');
    const keywordsCounter = document.getElementById('keywords-counter');
    
    keywordsInput.addEventListener('input', function() {
        const length = this.value.length;
        keywordsCounter.textContent = length;
        
        if (length > 255) {
            keywordsCounter.classList.add('text-danger');
        } else if (length > 200) {
            keywordsCounter.classList.add('text-warning');
        } else {
            keywordsCounter.classList.remove('text-danger', 'text-warning');
        }
    });
    
    // Inicializar contador
    keywordsInput.dispatchEvent(new Event('input'));
    
});
</script>

<style>
/* Proteger avisos estáticos */
.alert-static {
    position: relative !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>

<script>
// Proteger avisos estáticos de remoção
document.addEventListener('DOMContentLoaded', function() {
    const staticAlerts = document.querySelectorAll('.alert-static[data-static="true"]');
    staticAlerts.forEach(function(alert) {
        // Prevenir remoção via JavaScript
        const originalRemove = alert.remove;
        alert.remove = function() {
            console.log('Tentativa de remover aviso estático bloqueada');
            return false;
        };
        
        // Prevenir ocultação via display
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const target = mutation.target;
                    if (target.style.display === 'none' || target.style.visibility === 'hidden') {
                        target.style.display = 'block';
                        target.style.visibility = 'visible';
                    }
                }
            });
        });
        
        observer.observe(alert, { attributes: true, attributeFilter: ['style'] });
    });
});
</script>
@endsection
