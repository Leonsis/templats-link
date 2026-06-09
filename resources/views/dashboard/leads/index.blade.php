@extends('dashboard.layouts.admin')

@section('title', 'Gerenciar Leads')
@section('page-title', 'Gerenciar Leads')

@section('content')
<div class="content-area">
    <!-- Cabeçalho -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div class="flex-grow-1">
            <h1 class="mb-2"><i class="fas fa-users text-primary"></i> Gerenciar Leads</h1>
            <p class="text-muted mb-0">Visualize e gerencie todos os leads capturados</p>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $totalLeads }}</h3>
                    <p class="mb-0">Total de Leads</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $leadsHoje }}</h3>
                    <p class="mb-0">Hoje</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $leadsSemana }}</h3>
                    <p class="mb-0">Esta Semana</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3>{{ $leadsMes }}</h3>
                    <p class="mb-0">Este Mês</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.leads') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nome, email ou telefone">
                </div>
                
                <div class="col-md-3">
                    <label for="origem" class="form-label">Origem</label>
                    <select class="form-select" id="origem" name="origem">
                        <option value="">Todas as origens</option>
                        @foreach($origens as $origem)
                            <option value="{{ $origem }}" {{ request('origem') == $origem ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $origem)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="sort_by" class="form-label">Ordenar por</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Data</option>
                        <option value="nome" {{ request('sort_by') == 'nome' ? 'selected' : '' }}>Nome</option>
                        <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="sort_order" class="form-label">Ordem</label>
                    <select class="form-select" id="sort_order" name="sort_order">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Desc</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Asc</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('dashboard.leads') }}" class="btn btn-outline-secondary">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Leads -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Lista de Leads ({{ $leads->total() }} encontrados)</h5>
        </div>
        <div class="card-body">
            @if($leads->count() > 0)
                <div class="table-responsive">
                    <table class="table leads table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-user me-1"></i> Nome</th>
                                <th><i class="fas fa-tag me-1"></i> Origem</th>
                                <th><i class="fas fa-calendar me-1"></i> Data</th>
                                <th><i class="fas fa-cog me-1"></i> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leads as $lead)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2">
                                            {{ strtoupper(substr($lead->nome, 0, 1)) }}
                                        </div>
                                        <strong>{{ $lead->nome }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $lead->origem)) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $lead->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.leads.show', $lead) }}"  class="btn btn-sm btn-outline-primary"  title="Ver detalhes" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('dashboard.leads.destroy', $lead) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja remover este lead?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Remover lead">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginação -->
                @if($leads->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Mostrando {{ $leads->firstItem() }} a {{ $leads->lastItem() }} de {{ $leads->total() }} resultados
                    </div>
                    <nav aria-label="Paginação de leads">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($leads->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Anterior</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $leads->previousPageUrl() }}" rel="prev">Anterior</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($leads->getUrlRange(1, $leads->lastPage()) as $page => $url)
                                @if ($page == $leads->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($leads->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $leads->nextPageUrl() }}" rel="next">Próximo</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Próximo</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-muted"></i>
                    </div>
                    <h6 class="text-muted">Nenhum lead encontrado</h6>
                    <p class="text-muted small">
                        @if(request()->hasAny(['search', 'origem', 'data_inicio', 'data_fim']))
                            Nenhum lead corresponde aos filtros aplicados.
                        @else
                            Os leads capturados através dos formulários aparecerão aqui.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.user-avatar {
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    width: 32px;
    height: 32px;
    font-size: 0.8rem;
    margin-right: 8px;
}

.observacoes-cell {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}
</style>
@endsection
