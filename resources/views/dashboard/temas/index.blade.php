@extends('dashboard.layouts.admin')

@section('title', 'Gerenciar Temas')

@section('content')
<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-2"><i class="fas fa-palette text-primary"></i> Gerenciar Temas</h1>
            <p class="text-muted mb-0">Instale, configure e gerencie os temas do seu site</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#helpModal">
                <i class="fas fa-question-circle me-2"></i>Ajuda
            </button>
        </div>
    </div>

    <!-- Formulário de Upload -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-upload text-primary"></i> Instalar Novo Tema</h5>
            <small class="text-muted">Faça upload dos arquivos do tema para instalá-lo no sistema</small>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.temas.store') }}" method="POST" enctype="multipart/form-data" id="themeForm">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="nome_tema" class="form-label">
                                <i class="fas fa-tag me-1"></i>Nome do Tema
                            </label>
                            <input type="text" 
                                   class="form-control @error('nome_tema') is-invalid @enderror" 
                                   id="nome_tema" 
                                   name="nome_tema" 
                                   value="{{ old('nome_tema') }}"
                                   placeholder="Ex: tema-azul, meu-tema, etc."
                                   required>
                            @error('nome_tema')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Use apenas letras, números, hífens e underscores
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="arquivo_zip" class="form-label">
                                <i class="fas fa-file-archive me-1"></i>Arquivo ZIP dos Assets
                            </label>
                            <input type="file" 
                                   class="form-control @error('arquivo_zip') is-invalid @enderror" 
                                   id="arquivo_zip" 
                                   name="arquivo_zip" 
                                   accept=".zip"
                                   required>
                            @error('arquivo_zip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>CSS, JS, imagens, etc. (Máx: 10MB)
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="arquivo_paginas" class="form-label">
                                <i class="fas fa-file-code me-1"></i>Arquivo ZIP das Páginas
                            </label>
                            <input type="file" 
                                   class="form-control @error('arquivo_paginas') is-invalid @enderror" 
                                   id="arquivo_paginas" 
                                   name="arquivo_paginas"
                                   accept=".zip">
                            @error('arquivo_paginas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Páginas HTML/Blade (Máx: 10MB)
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Campos para código dos arquivos Blade -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo_head" class="form-label">Código do head.blade.php</label>
                            <textarea class="form-control @error('codigo_head') is-invalid @enderror" 
                                      id="codigo_head" 
                                      name="codigo_head" 
                                      rows="8" 
                                      placeholder="Cole aqui o código do arquivo head.blade.php">{{ old('codigo_head') }}</textarea>
                            @error('codigo_head')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Código HTML para o cabeçalho (meta tags, CSS, etc.)
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo_nav" class="form-label">Código do nav.blade.php</label>
                            <textarea class="form-control @error('codigo_nav') is-invalid @enderror" 
                                      id="codigo_nav" 
                                      name="codigo_nav" 
                                      rows="8" 
                                      placeholder="Cole aqui o código do arquivo nav.blade.php">{{ old('codigo_nav') }}</textarea>
                            @error('codigo_nav')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Código HTML para a navegação
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo_footer" class="form-label">Código do footer.blade.php</label>
                            <textarea class="form-control @error('codigo_footer') is-invalid @enderror" 
                                      id="codigo_footer" 
                                      name="codigo_footer" 
                                      rows="8" 
                                      placeholder="Cole aqui o código do arquivo footer.blade.php">{{ old('codigo_footer') }}</textarea>
                            @error('codigo_footer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Código HTML para o rodapé
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo_scripts" class="form-label">Código do scripts.blade.php</label>
                            <textarea class="form-control @error('codigo_scripts') is-invalid @enderror" 
                                      id="codigo_scripts" 
                                      name="codigo_scripts" 
                                      rows="8" 
                                      placeholder="Cole aqui o código do arquivo scripts.blade.php">{{ old('codigo_scripts') }}</textarea>
                            @error('codigo_scripts')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Código JavaScript e scripts
                            </small>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                        <i class="fas fa-undo me-2"></i>Limpar
                    </button>
                    <button type="submit" class="btn btn-primary btn-admin" id="submitBtn">
                        <i class="fas fa-upload me-2"></i>Instalar Tema
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Temas Instalados -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list text-primary"></i> Temas Instalados</h5>
            <small class="text-muted">Gerencie os temas instalados no sistema</small>
        </div>
        <div class="card-body">
            @if(count($temas) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Assets</th>
                                <th>Páginas</th>
                                <th>Tamanho</th>
                                <th>Instalado em</th>
                                <th>Preview</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($temas as $tema)
                                <tr>
                                    <td>
                                        <strong>{{ $tema['nome'] }}</strong>
                                        @if($tema['is_main'])
                                            <span class="badge bg-primary ms-2">
                                                <i class="fas fa-home"></i> Padrão
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $tema['arquivos'] }} arquivo(s)</span>
                                    </td>
                                    <td>
                                        @if($tema['tem_paginas'])
                                            <span class="badge bg-success">{{ $tema['arquivos_paginas'] }} página(s)</span>
                                        @else
                                            <span class="badge bg-secondary">Sem páginas</span>
                                        @endif
                                    </td>
                                    <td>{{ $tema['tamanho'] }}</td>
                                    <td>{{ $tema['criado_em'] }}</td>
                                    <td>
                                        @if($tema['tem_paginas'])
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info preview-btn" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#pagesAccordion{{ $loop->index }}"
                                                    aria-expanded="false" 
                                                    aria-controls="pagesAccordion{{ $loop->index }}"
                                                    title="Ver páginas do tema">
                                                <i class="fas fa-eye"></i>
                                                <span class="btn-text">Ver Páginas</span>
                                            </button>
                                        @else
                                            <span class="text-muted no-pages">Sem páginas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1 align-items-center">
                                            @if($tema['ativo'])
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Ativo
                                                </span>
                                            @else
                                                <form action="{{ route('dashboard.temas.select', $tema['nome']) }}" method="POST" class="d-inline" id="selectForm{{ $loop->index }}">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success select-theme-btn" 
                                                            data-tema="{{ $tema['nome'] }}"
                                                            data-index="{{ $loop->index }}"
                                                            onclick="return selectTheme('{{ $tema['nome'] }}', {{ $loop->index }})">
                                                        <i class="fas fa-star"></i> Selecionar
                                                    </button>
                                                </form>
                                            @endif
                                            @if(!$tema['is_main'])
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="confirmarRemocao('{{ $tema['nome'] }}')">
                                                    <i class="fas fa-trash"></i> Remover
                                                </button>
                                            @else
                                                <span class="badge bg-info">
                                                    <i class="fas fa-shield-alt"></i> Sistema
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @if($tema['tem_paginas'])
                                    <tr class="accordion-row">
                                        <td colspan="7" class="p-0">
                                            <div class="collapse" id="pagesAccordion{{ $loop->index }}">
                                                <div class="card card-body border-0 bg-light">
                                                    <div class="pages-buttons">
                                                        @foreach($tema['paginas_disponiveis'] as $pagina)
                                                            <a href="{{ route('dashboard.temas.preview.page', [$tema['nome'], $pagina]) }}" 
                                                               target="_blank"
                                                               class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                                                <i class="fas fa-file-alt me-2"></i>
                                                                {{ ucfirst($pagina) }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted mb-2">Nenhum tema instalado</h5>
                        <p class="text-muted mb-4">Faça upload de um tema usando o formulário acima para começar.</p>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#helpModal">
                            <i class="fas fa-question-circle me-2"></i>Como instalar um tema?
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Ajuda -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-question-circle text-primary me-2"></i>Como Instalar um Tema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-file-archive text-info me-2"></i>Arquivo ZIP dos Assets</h6>
                        <p class="text-muted">Este arquivo deve conter:</p>
                        <ul class="text-muted">
                            <li>Arquivos CSS</li>
                            <li>Arquivos JavaScript</li>
                            <li>Imagens e ícones</li>
                            <li>Fontes</li>
                            <li>Outros recursos estáticos</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-file-code text-success me-2"></i>Arquivo ZIP das Páginas</h6>
                        <p class="text-muted">Este arquivo deve conter:</p>
                        <ul class="text-muted">
                            <li>Arquivos HTML/Blade</li>
                            <li>Páginas como home, sobre, contato</li>
                            <li>Estrutura de pastas organizada</li>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i>Dica Importante</h6>
                    <p class="mb-0">Após a instalação, o sistema automaticamente linkará os formulários de configuração ao tema, permitindo personalizar logos, cores e textos diretamente do painel administrativo.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Remoção</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja remover o tema <strong id="temaNome"></strong>?</p>
                <p class="text-danger"><small>Esta ação não pode ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Remover</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Função para resetar o formulário
function resetForm() {
    document.getElementById('themeForm').reset();
    // Limpar previews de arquivos se existirem
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.value = '';
    });
}

// Função para confirmar remoção
function confirmarRemocao(nomeTema) {
    document.getElementById('temaNome').textContent = nomeTema;
    document.getElementById('deleteForm').action = '{{ route("dashboard.temas.destroy", ":nomeTema") }}'.replace(':nomeTema', nomeTema);
    
    var modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
}

// Função para selecionar tema com melhor tratamento de erros
function selectTheme(nomeTema, index) {
    if (!confirm('Deseja selecionar o tema "' + nomeTema + '" como tema principal?')) {
        return false;
    }
    
    const form = document.getElementById('selectForm' + index);
    const button = form.querySelector('.select-theme-btn');
    const originalText = button.innerHTML;
    
    // Mostrar loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Selecionando...';
    button.disabled = true;
    
    // Enviar formulário
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            return response.text();
        }
        throw new Error('Erro na requisição: ' + response.status);
    })
    .then(data => {
        // Sucesso - recarregar a página para mostrar mudanças
        window.location.reload();
    })
    .catch(error => {
        console.error('Erro ao selecionar tema:', error);
        alert('Erro ao selecionar o tema. Verifique o console para mais detalhes.');
        
        // Restaurar botão
        button.innerHTML = originalText;
        button.disabled = false;
    });
    
    return false; // Prevenir envio padrão do formulário
}

function linkarPaginas(nomeTema) {
    // Definir URLs das páginas de edição
    const paginas = {
        'home': '{{ route("dashboard.temas.home.edit") }}',
        'about': '{{ route("dashboard.temas.about.edit") }}',
        'contact': '{{ route("dashboard.temas.contact.edit") }}',
        'servico': '{{ route("dashboard.servico.index") }}'
    };
    
    // Abrir cada página em uma nova aba
    Object.keys(paginas).forEach(function(pagina) {
        window.open(paginas[pagina], '_blank');
    });
    
    // Mostrar mensagem de sucesso
    alert('Páginas abertas em novas abas para edição!');
}

// Inicializar funcionalidades
document.addEventListener('DOMContentLoaded', function() {
    // Garantir que todos os accordions funcionem
    var collapseElementList = [].slice.call(document.querySelectorAll('.collapse'));
    var collapseList = collapseElementList.map(function (collapseEl) {
        return new bootstrap.Collapse(collapseEl, {
            toggle: false
        });
    });
    
    // Adicionar loading ao formulário
    const form = document.getElementById('themeForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Instalando...';
            submitBtn.disabled = true;
        });
    }
    
    // Adicionar drag and drop para arquivos
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const label = this.previousElementSibling;
                if (label && label.classList.contains('form-label')) {
                    const icon = label.querySelector('i');
                    if (icon) {
                        icon.className = 'fas fa-check-circle text-success me-1';
                    }
                }
            }
        });
    });
    
    // Auto-hide alerts após 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Melhorar botões de preview
    const previewButtons = document.querySelectorAll('.preview-btn');
    previewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');
            const collapse = document.querySelector(target);
            
            if (collapse) {
                // Adicionar loading state
                const icon = this.querySelector('i');
                const originalClass = icon.className;
                icon.className = 'fas fa-spinner fa-spin';
                
                // Restaurar ícone após animação
                setTimeout(() => {
                    icon.className = originalClass;
                }, 500);
            }
        });
    });
    
    console.log('Admin Panel inicializado com sucesso!');
});
</script>
@endpush
