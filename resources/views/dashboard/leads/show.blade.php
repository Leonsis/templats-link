@extends('dashboard.layouts.admin')

@section('title', 'Detalhes do Lead')
@section('page-title', 'Detalhes do Lead')

@section('content')
<div class="content-area">
    <!-- Cabeçalho -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div class="flex-grow-1">
            <h1 class="mb-2"><i class="fas fa-user text-primary"></i> Detalhes do Lead</h1>
            <p class="text-muted mb-0">Informações completas do lead</p>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
            <a href="{{ route('dashboard.leads') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informações do Lead -->
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informações Pessoais</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nome Completo</label>
                            <p class="form-control-plaintext">{{ $lead->nome }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                <a href="mailto:{{ $lead->email }}" class="text-decoration-none">
                                    {{ $lead->email }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Telefone</label>
                            <p class="form-control-plaintext">
                                <a href="tel:{{ $lead->telefone }}" class="text-decoration-none">
                                    {{ $lead->telefone }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Origem</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $lead->origem)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Formulário -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informações do Formulário</h5>
                </div>
                <div class="card-body">
                    @php
                        $observacoes = $lead->observacoes ?? '';
                        $faturamento = '';
                        $areaAtuacao = '';
                        
                        // Extrair faturamento e área de atuação das observações
                        if (preg_match('/Faturamento:\s*([^,]+)/', $observacoes, $matches)) {
                            $faturamento = trim($matches[1]);
                        }
                        if (preg_match('/Área:\s*(.+)/', $observacoes, $matches)) {
                            $areaAtuacao = trim($matches[1]);
                        }
                    @endphp
                    
                    <div class="row">
                        @if($faturamento)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Faturamento</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-success fs-6">{{ $faturamento }}</span>
                            </p>
                        </div>
                        @endif
                        
                        @if($areaAtuacao)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Área de Atuação</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary fs-6">{{ $areaAtuacao }}</span>
                            </p>
                        </div>
                        @endif
                    </div>
                    
                    @if(!$faturamento && !$areaAtuacao)
                        <div class="text-center py-3">
                            <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Nenhuma informação adicional do formulário</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Observações Gerais -->
            @if($lead->observacoes && (strlen($lead->observacoes) > 100 || !preg_match('/Faturamento:|Área:/', $lead->observacoes)))
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Observações Gerais</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-light">
                        <p class="mb-0">{{ $lead->observacoes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Informações do Sistema -->
        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Informações do Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Data de Criação</label>
                        <p class="form-control-plaintext">
                            {{ $lead->created_at->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Última Atualização</label>
                        <p class="form-control-plaintext">
                            {{ $lead->updated_at->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ID do Lead</label>
                        <p class="form-control-plaintext">
                            <code>#{{ $lead->id }}</code>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Ações</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $lead->email }}" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>Enviar Email
                        </a>
                        <a href="tel:{{ $lead->telefone }}" class="btn btn-success">
                            <i class="fas fa-phone me-2"></i>Ligar
                        </a>
                        <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $lead->telefone) }}" 
                           class="btn btn-success" target="_blank">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                        <hr>
                        <form action="{{ route('dashboard.leads.destroy', $lead) }}" 
                              method="POST" 
                              onsubmit="return confirm('Tem certeza que deseja remover este lead? Esta ação não pode ser desfeita.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Remover Lead
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control-plaintext {
    padding: 0.375rem 0;
    margin-bottom: 0;
    line-height: 1.5;
    color: #212529;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

.alert-light {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #495057;
}
</style>
@endsection
