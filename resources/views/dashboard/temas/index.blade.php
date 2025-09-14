@extends('dashboard.layouts.admin')

@section('title', 'Gerenciar Temas')

@section('content')
<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-palette"></i> Gerenciar Temas</h1>
    </div>

    <!-- Formulário de Upload -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-upload"></i> Instalar Novo Tema</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.temas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="nome_tema" class="form-label">Nome do Tema</label>
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
                                Use apenas letras, números, hífens e underscores
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="arquivo_zip" class="form-label">Arquivo ZIP dos Assets</label>
                            <input type="file" 
                                   class="form-control @error('arquivo_zip') is-invalid @enderror" 
                                   id="arquivo_zip" 
                                   name="arquivo_zip" 
                                   required>
                            @error('arquivo_zip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                CSS, JS, imagens, etc. (Máx: 10MB)
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="arquivo_paginas" class="form-label">Arquivo ZIP das Páginas</label>
                            <input type="file" 
                                   class="form-control @error('arquivo_paginas') is-invalid @enderror" 
                                   id="arquivo_paginas" 
                                   name="arquivo_paginas" 
                                   required>
                            @error('arquivo_paginas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Templates Blade (Máx: 10MB)
                            </small>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-admin">
                    <i class="fas fa-upload"></i> Instalar Tema
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de Temas Instalados -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Temas Instalados</h5>
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
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($temas as $tema)
                                <tr>
                                    <td>
                                        <strong>{{ $tema['nome'] }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $tema['arquivos'] }} arquivo(s)</span>
                                    </td>
                                    <td>
                                        @if($tema['tem_paginas'])
                                            <span class="badge bg-success">{{ $tema['arquivos_paginas'] }} página(s)</span>
                                        @else
                                            <span class="badge bg-warning">Sem páginas</span>
                                        @endif
                                    </td>
                                    <td>{{ $tema['tamanho'] }}</td>
                                    <td>{{ $tema['criado_em'] }}</td>
                                    <td>
                                        @if($tema['tem_paginas'])
                                            <a href="{{ route('dashboard.temas.preview', $tema['nome']) }}" 
                                               class="btn btn-sm btn-info me-2" 
                                               target="_blank">
                                                <i class="fas fa-eye"></i> Preview
                                            </a>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmarRemocao('{{ $tema['nome'] }}')">
                                            <i class="fas fa-trash"></i> Remover
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum tema instalado</h5>
                    <p class="text-muted">Faça upload de um tema usando o formulário acima.</p>
                </div>
            @endif
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
function confirmarRemocao(nomeTema) {
    document.getElementById('temaNome').textContent = nomeTema;
    document.getElementById('deleteForm').action = '{{ route("dashboard.temas.destroy", ":nomeTema") }}'.replace(':nomeTema', nomeTema);
    
    var modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
}
</script>
@endpush
