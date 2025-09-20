@extends('dashboard.layouts.admin')

@section('title', 'Botões Flutuantes')

@section('page-title', 'Botões Flutuantes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-mobile-alt me-2"></i>
                    Configurações dos Botões Flutuantes
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

            <form action="{{ route('dashboard.floating-buttons.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Seção Configurações dos Botões -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-mobile-alt me-2"></i>
                                    Configurações dos Botões Flutuantes
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Importante:</strong> Configure os números de telefone e WhatsApp que aparecerão nos botões flutuantes do site.
                                </div>
                                
                                <div class="alert alert-warning alert-static" data-static="true">
                                    <i class="fas fa-code me-2"></i>
                                    <strong>Tag para o código:</strong><br>
                                    <code class="ms-2">
                                        @verbatim
                                            @include('floatingButton.index')
                                        @endverbatim
                                    </code>
                                    <br>
                                    <small class="text-muted">Esta tag deve ser colocada no footer do tema para exibir os botões flutuantes</small>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefone" class="form-label">
                                                <i class="fas fa-phone me-1"></i>
                                                Número do Telefone
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="telefone" 
                                                   name="telefone" 
                                                   value="{{ $configs->telefone ?? '' }}"
                                                   placeholder="+55 (11) 99999-9999">
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Número que aparecerá no botão de telefone flutuante. Use o formato internacional (+55).
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="whatsapp" class="form-label">
                                                <i class="fab fa-whatsapp me-1"></i>
                                                Número do WhatsApp
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="whatsapp" 
                                                   name="whatsapp" 
                                                   value="{{ $configs->whatsapp ?? '' }}"
                                                   placeholder="+55 (11) 99999-9999">
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Número que aparecerá no botão do WhatsApp flutuante. Use o formato internacional (+55).
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção Preview -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-eye me-2"></i>
                                    Preview dos Botões Flutuantes
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Preview:</strong> Visualização de como os botões aparecerão no site.
                                </div>
                                
                                <div class="d-flex justify-content-center">
                                    <div class="floating-buttons-preview">
                                        <!-- WhatsApp Button Preview -->
                                        <a href="#" class="floating-btn-preview whatsapp-btn-preview" title="Fale conosco no WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        
                                        <!-- Phone Button Preview -->
                                        <a href="#" class="floating-btn-preview phone-btn-preview" title="Ligue para nós">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="mt-3 text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-mobile-alt me-1"></i>
                                        Os botões aparecerão fixos no canto inferior direito do site
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botão Salvar -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                Salvar Configurações dos Botões Flutuantes
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Informações Adicionais -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Informações Importantes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-phone text-primary me-2"></i>Telefone:</h6>
                                    <ul class="list-unstyled">
                                        <li>• Use o formato internacional (+55)</li>
                                        <li>• Exemplo: +55 (11) 99999-9999</li>
                                        <li>• Campo opcional</li>
                                        <li>• Abrirá o aplicativo de telefone</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fab fa-whatsapp text-success me-2"></i>WhatsApp:</h6>
                                    <ul class="list-unstyled">
                                        <li>• Use o formato internacional (+55)</li>
                                        <li>• Exemplo: +55 (11) 99999-9999</li>
                                        <li>• Campo opcional</li>
                                        <li>• Abrirá o WhatsApp Web/App</li>
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

/* Preview dos botões flutuantes */
.floating-buttons-preview {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border: 2px dashed #dee2e6;
}

.floating-btn-preview {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    font-size: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    animation: pulse 2s infinite;
}

.floating-btn-preview:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    color: white;
    text-decoration: none;
}

.whatsapp-btn-preview {
    background: linear-gradient(135deg, #25D366, #128C7E);
}

.phone-btn-preview {
    background: linear-gradient(135deg, #007bff, #0056b3);
}

@keyframes pulse {
    0% {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    50% {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), 0 0 0 10px rgba(37, 211, 102, 0.1);
    }
    100% {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
}
</style>

<script>
// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Máscara para telefone
    const telefoneInputs = document.querySelectorAll('input[name="telefone"], input[name="whatsapp"]');
    telefoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length >= 7) {
                value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else if (value.length >= 3) {
                value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            }
            e.target.value = value;
        });
    });
});
</script>
@endsection
