@extends('dashboard.layouts.admin')

@section('title', 'Configurações do Head')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-heading me-2"></i>
                    Configurações do Head
                </h1>
            </div>

            <!-- Informações do Tema Ativo -->
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-palette me-2"></i>
                    <div>
                        <strong>Tema Ativo:</strong> {{ $temaAtivo }}
                        <small class="text-muted ms-2">As configurações serão aplicadas especificamente para este tema.</small>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Seção Favicon -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-star me-2"></i>
                                Favicon do Site
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Importante:</strong> O favicon será aplicado em todas as páginas do site. 
                                Use uma imagem WebP de até 2MB.
                            </div>
                            
                            <div class="alert alert-warning alert-static" data-static="true">
                                <i class="fas fa-code me-2"></i>
                                <strong>Tag para o código:</strong><br>
                                <code class="ms-2">\App\Helpers\HeadHelper::getFavicon()</code><br>
                                <small class="text-muted">Adicione as chaves duplas ao redor da tag</small>
                            </div>
                            
                            <form action="{{ route('dashboard.head.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="pagina" value="global">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Upload do Favicon -->
                                        <div class="mb-3">
                                            <label for="favicon_upload" class="form-label">
                                                <i class="fas fa-upload me-1"></i>
                                                Upload do Favicon
                                            </label>
                                            <div class="input-group">
                                                <input type="file" 
                                                       class="form-control" 
                                                       id="favicon_upload" 
                                                       name="favicon" 
                                                       accept=".webp"
                                                       onchange="previewFavicon(this)">
                                                <button type="button" 
                                                        class="btn btn-outline-secondary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#imageGalleryModal"
                                                        onclick="loadImageGallery()">
                                                    <i class="fas fa-images me-1"></i>
                                                    Ver Imagens
                                                </button>
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-image me-1"></i>
                                                Apenas arquivos WebP, máximo 2MB
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <!-- Preview do Favicon -->
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-eye me-1"></i>
                                                Preview do Favicon
                                            </label>
                                            <div class="favicon-preview border rounded p-3 text-center" style="min-height: 100px;">
                                                @php
                                                    $currentFavicon = $allConfigs->where('pagina', 'global')->first()->favicon ?? '';
                                                @endphp
                                                @if($currentFavicon)
                                                    <img src="{{ \App\Helpers\HeadHelper::getFavicon() }}" 
                                                         alt="Favicon atual" 
                                                         class="favicon-current" 
                                                         style="max-width: 32px; max-height: 32px;">
                                                    <div class="mt-2">
                                                        <small class="text-muted">Favicon atual</small>
                                                    </div>
                                                @else
                                                    <div class="text-muted">
                                                        <i class="fas fa-image fa-2x mb-2"></i>
                                                        <div>Nenhum favicon configurado</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botão Salvar Favicon -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-save me-2"></i>
                                        Salvar Favicon
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Páginas -->
            <div class="row">
                @foreach($paginas as $paginaKey => $paginaNome)
                    @php
                        $config = $allConfigs->where('pagina', $paginaKey)->first();
                    @endphp
                    
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-file-alt me-2"></i>
                                    {{ $paginaNome }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning alert-static" data-static="true">
                                    <i class="fas fa-code me-2"></i>
                                    <strong>Tags para o código:</strong><br>
                                    <code class="ms-2">\App\Helpers\HeadHelper::getMetaTitle('{{ $paginaKey }}')</code><br>
                                    <code class="ms-2">\App\Helpers\HeadHelper::getMetaDescription('{{ $paginaKey }}')</code><br>
                                    <code class="ms-2">\App\Helpers\HeadHelper::getMetaKeywords('{{ $paginaKey }}')</code><br>
                                    <small class="text-muted">Adicione as chaves duplas ao redor de cada tag</small>
                                </div>
                                
                                <form action="{{ route('dashboard.head.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="pagina" value="{{ $paginaKey }}">
                                    
                                    <!-- Meta Title -->
                                    <div class="mb-3">
                                        <label for="meta_title_{{ $paginaKey }}" class="form-label">
                                            <i class="fas fa-tag me-1"></i>
                                            Meta Title
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="meta_title_{{ $paginaKey }}" 
                                               name="meta_title" 
                                               value="{{ $config->meta_title ?? '' }}"
                                               maxlength="60"
                                               placeholder="Título da página (máx. 60 caracteres)">
                                        <div class="form-text">
                                            <span id="title_count_{{ $paginaKey }}">0</span>/60 caracteres
                                        </div>
                                    </div>

                                    <!-- Meta Description -->
                                    <div class="mb-3">
                                        <label for="meta_description_{{ $paginaKey }}" class="form-label">
                                            <i class="fas fa-align-left me-1"></i>
                                            Meta Description
                                        </label>
                                        <textarea class="form-control" 
                                                  id="meta_description_{{ $paginaKey }}" 
                                                  name="meta_description" 
                                                  rows="3"
                                                  maxlength="160"
                                                  placeholder="Descrição da página (máx. 160 caracteres)">{{ $config->meta_description ?? '' }}</textarea>
                                        <div class="form-text">
                                            <span id="desc_count_{{ $paginaKey }}">0</span>/160 caracteres
                                        </div>
                                    </div>

                                    <!-- Meta Keywords -->
                                    <div class="mb-3">
                                        <label for="meta_keywords_{{ $paginaKey }}" class="form-label">
                                            <i class="fas fa-key me-1"></i>
                                            Meta Keywords
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="meta_keywords_{{ $paginaKey }}" 
                                               name="meta_keywords" 
                                               value="{{ $config->meta_keywords ?? '' }}"
                                               maxlength="255"
                                               placeholder="Palavras-chave separadas por vírgula (máx. 255 caracteres)">
                                        <div class="form-text">
                                            <span id="keywords_count_{{ $paginaKey }}">0</span>/255 caracteres
                                        </div>
                                    </div>


                                    <!-- Botão Salvar -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>
                                            Salvar Configurações
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Seção Google Tag Manager Global -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="fab fa-google me-2"></i>
                                Google Tag Manager - Configuração Global
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Importante:</strong> O Google Tag Manager será aplicado em todas as páginas do site. 
                                Configure uma vez e será usado globalmente.
                            </div>
                            
                            <div class="alert alert-warning alert-static" data-static="true">
                                <i class="fas fa-code me-2"></i>
                                <strong>Tags para o código:</strong><br>
                                <code class="ms-2">\App\Helpers\HeadHelper::getGtmHead()</code><br>
                                <code class="ms-2">\App\Helpers\HeadHelper::getGtmBody()</code><br>
                                <small class="text-muted">Adicione as chaves duplas ao redor de cada tag</small>
                            </div>
                            
                            <form action="{{ route('dashboard.head.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="pagina" value="global">
                                
                                <!-- Google Tag Manager Head -->
                                <div class="mb-4">
                                    <label for="gtm_head_global" class="form-label">
                                        <i class="fab fa-google me-1"></i>
                                        Google Tag Manager (Head)
                                    </label>
                                    <textarea class="form-control" 
                                              id="gtm_head_global" 
                                              name="gtm_head" 
                                              rows="6"
                                              placeholder="Cole aqui o código completo do Google Tag Manager para o &lt;head&gt;&#10;&#10;Exemplo:&#10;&lt;script&gt;(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':&#10;new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],&#10;j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=&#10;'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);&#10;})(window,document,'script','dataLayer','GTM-XXXXXXX');&lt;/script&gt;">{{ $allConfigs->where('pagina', 'global')->first()->gtm_head ?? '' }}</textarea>
                                    <div class="form-text">
                                        <i class="fas fa-code me-1"></i>
                                        Código que será inserido no &lt;head&gt; de todas as páginas
                                    </div>
                                </div>

                                <!-- Google Tag Manager Body -->
                                <div class="mb-4">
                                    <label for="gtm_body_global" class="form-label">
                                        <i class="fab fa-google me-1"></i>
                                        Google Tag Manager (Body - noscript)
                                    </label>
                                    <textarea class="form-control" 
                                              id="gtm_body_global" 
                                              name="gtm_body" 
                                              rows="6"
                                              placeholder="Cole aqui o código noscript do Google Tag Manager para o &lt;body&gt;&#10;&#10;Exemplo:&#10;&lt;noscript&gt;&lt;iframe src='https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX'&#10;height='0' width='0' style='display:none;visibility:hidden'&gt;&lt;/iframe&gt;&lt;/noscript&gt;">{{ $allConfigs->where('pagina', 'global')->first()->gtm_body ?? '' }}</textarea>
                                    <div class="form-text">
                                        <i class="fas fa-code me-1"></i>
                                        Código que será inserido no início do &lt;body&gt; de todas as páginas
                                    </div>
                                </div>

                                <!-- Botão Salvar GTM -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="fas fa-save me-2"></i>
                                        Salvar Configurações do Google Tag Manager
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Informações Importantes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-lightbulb text-warning me-2"></i>Dicas de SEO:</h6>
                                    <ul class="list-unstyled">
                                        <li>• Meta Title: 50-60 caracteres</li>
                                        <li>• Meta Description: 150-160 caracteres</li>
                                        <li>• Use palavras-chave relevantes</li>
                                        <li>• Seja descritivo e atrativo</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-code text-success me-2"></i>Google Tag Manager:</h6>
                                    <ul class="list-unstyled">
                                        <li>• Head: Código completo do GTM</li>
                                        <li>• Body: Código noscript do GTM</li>
                                        <li>• Aplicado globalmente em todas as páginas</li>
                                        <li>• Configuração única e centralizada</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
// Inicializar tooltips e proteger avisos estáticos
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Proteger avisos estáticos de remoção
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

// Função para preview do favicon
function previewFavicon(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.querySelector('.favicon-preview');
            preview.innerHTML = `
                <img src="${e.target.result}" 
                     alt="Preview do favicon" 
                     style="max-width: 32px; max-height: 32px;">
                <div class="mt-2">
                    <small class="text-success">Preview do novo favicon</small>
                </div>
            `;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Contadores de caracteres para cada página
    @foreach($paginas as $paginaKey => $paginaNome)
        // Contador para Meta Title
        const titleInput{{ $loop->index }} = document.getElementById('meta_title_{{ $paginaKey }}');
        const titleCount{{ $loop->index }} = document.getElementById('title_count_{{ $paginaKey }}');
        
        titleInput{{ $loop->index }}.addEventListener('input', function() {
            titleCount{{ $loop->index }}.textContent = this.value.length;
            if (this.value.length > 60) {
                titleCount{{ $loop->index }}.style.color = 'red';
            } else if (this.value.length > 50) {
                titleCount{{ $loop->index }}.style.color = 'orange';
            } else {
                titleCount{{ $loop->index }}.style.color = 'green';
            }
        });
        
        // Contador para Meta Description
        const descInput{{ $loop->index }} = document.getElementById('meta_description_{{ $paginaKey }}');
        const descCount{{ $loop->index }} = document.getElementById('desc_count_{{ $paginaKey }}');
        
        descInput{{ $loop->index }}.addEventListener('input', function() {
            descCount{{ $loop->index }}.textContent = this.value.length;
            if (this.value.length > 160) {
                descCount{{ $loop->index }}.style.color = 'red';
            } else if (this.value.length > 150) {
                descCount{{ $loop->index }}.style.color = 'orange';
            } else {
                descCount{{ $loop->index }}.style.color = 'green';
            }
        });
        
        // Contador para Meta Keywords
        const keywordsInput{{ $loop->index }} = document.getElementById('meta_keywords_{{ $paginaKey }}');
        const keywordsCount{{ $loop->index }} = document.getElementById('keywords_count_{{ $paginaKey }}');
        
        keywordsInput{{ $loop->index }}.addEventListener('input', function() {
            keywordsCount{{ $loop->index }}.textContent = this.value.length;
            if (this.value.length > 255) {
                keywordsCount{{ $loop->index }}.style.color = 'red';
            } else {
                keywordsCount{{ $loop->index }}.style.color = 'green';
            }
        });
        
        // Inicializar contadores
        titleInput{{ $loop->index }}.dispatchEvent(new Event('input'));
        descInput{{ $loop->index }}.dispatchEvent(new Event('input'));
        keywordsInput{{ $loop->index }}.dispatchEvent(new Event('input'));
    @endforeach
});
</script>

<!-- Modal da Galeria de Imagens -->
<div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageGalleryModalLabel">
                    <i class="fas fa-images me-2"></i>
                    Galeria de Imagens
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   id="imageSearch" 
                                   placeholder="Buscar imagens..."
                                   onkeyup="filterImages()">
                        </div>
                    </div>
                </div>
                
                <div id="imageGallery" class="row">
                    <div class="col-12 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="mt-2">Carregando imagens...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Função para carregar a galeria de imagens
function loadImageGallery() {
    const gallery = document.getElementById('imageGallery');
    gallery.innerHTML = `
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2">Carregando imagens...</p>
        </div>
    `;
    
    fetch('{{ route("dashboard.head.images") }}')
        .then(response => response.json())
        .then(data => {
            displayImages(data);
        })
        .catch(error => {
            console.error('Erro ao carregar imagens:', error);
            gallery.innerHTML = `
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erro ao carregar imagens. Tente novamente.
                    </div>
                </div>
            `;
        });
}

// Função para exibir as imagens na galeria
function displayImages(images) {
    const gallery = document.getElementById('imageGallery');
    
    if (images.length === 0) {
        gallery.innerHTML = `
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Nenhuma imagem encontrada no sistema.
                </div>
            </div>
        `;
        return;
    }
    
    let html = '';
    images.forEach(image => {
        const typeClass = image.type === 'favicon' ? 'border-success' : 'border-primary';
        const typeIcon = image.type === 'favicon' ? 'fa-star' : 'fa-image';
        const typeLabel = image.type === 'favicon' ? 'Favicon' : 'Logo';
        
        html += `
            <div class="col-md-3 mb-3 image-item" data-type="${image.type}" data-filename="${image.filename}">
                <div class="card h-100 ${typeClass}">
                    <div class="card-body text-center p-2">
                        <img src="${image.url}" 
                             class="img-fluid rounded mb-2" 
                             style="max-height: 100px; object-fit: contain;"
                             alt="${image.filename}">
                        <h6 class="card-title small mb-1">${image.filename}</h6>
                        <p class="card-text small text-muted mb-2">
                            <i class="fas fa-weight me-1"></i>${formatFileSize(image.size)}<br>
                            <i class="fas fa-calendar me-1"></i>${formatDate(image.modified)}
                        </p>
                        <button type="button" 
                                class="btn btn-sm btn-primary" 
                                onclick="selectFaviconImage('${image.filename}')">
                            <i class="fas fa-check me-1"></i>
                            Selecionar
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    gallery.innerHTML = html;
}

// Função para filtrar imagens
function filterImages() {
    const searchTerm = document.getElementById('imageSearch').value.toLowerCase();
    const imageItems = document.querySelectorAll('.image-item');
    
    imageItems.forEach(item => {
        const filename = item.dataset.filename.toLowerCase();
        
        const matchesSearch = filename.includes(searchTerm);
        
        if (matchesSearch) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Função para selecionar uma imagem como favicon
function selectFaviconImage(filename) {
    // Simular seleção de arquivo
    const fileInput = document.getElementById('favicon_upload');
    
    // Criar um input hidden para armazenar o filename selecionado
    let hiddenInput = document.getElementById('favicon_filename');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'favicon_filename';
        hiddenInput.id = 'favicon_filename';
        fileInput.parentNode.appendChild(hiddenInput);
    }
    hiddenInput.value = filename;
    
    // Fechar o modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('imageGalleryModal'));
    modal.hide();
    
    // Mostrar mensagem de sucesso
    showAlert('success', `Favicon "${filename}" selecionado com sucesso!`);
}

// Função para formatar tamanho do arquivo
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Função para formatar data
function formatDate(timestamp) {
    const date = new Date(timestamp * 1000);
    return date.toLocaleDateString('pt-BR') + ' ' + date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
}

// Função para mostrar alertas
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Inserir no topo da página
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Remover automaticamente após 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endsection