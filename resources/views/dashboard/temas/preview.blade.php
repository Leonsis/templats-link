@extends('dashboard.layouts.admin')

@section('title', 'Preview do Tema: ' . $dadosTema['nome'])

@section('content')
<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-eye"></i> Preview do Tema: {{ $dadosTema['nome'] }}</h1>
        <a href="{{ route('dashboard.temas') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <!-- Navegação entre páginas -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Páginas Disponíveis</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($dadosTema['paginas_disponiveis'] as $pagina)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h6 class="card-title">{{ ucfirst($pagina) }}</h6>
                                <a href="{{ route('dashboard.temas.preview.page', [$dadosTema['nome'], $pagina]) }}" 
                                   class="btn btn-primary btn-sm" 
                                   target="_blank">
                                    <i class="fas fa-eye"></i> Visualizar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Preview da página principal -->
    <div class="admin-card">
        <div class="card-header">
                    <h5 class="mb-0">
                <i class="fas fa-desktop"></i> Preview: {{ ucfirst($dadosTema['pagina_principal']) }}
                    </h5>
                </div>
        <div class="card-body p-0">
            <div class="preview-container">
                <iframe src="{{ route('dashboard.temas.preview.page', [$dadosTema['nome'], $dadosTema['pagina_principal']]) }}" 
                        width="100%" 
                        height="800px" 
                        frameborder="0"
                        style="border: 1px solid #ddd; border-radius: 5px;">
                </iframe>
            </div>
                    </div>
                </div>

    <!-- Informações do tema -->
    <div class="admin-card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações do Tema</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nome do Tema:</strong> {{ $dadosTema['nome'] }}</p>
                    <p><strong>Páginas Disponíveis:</strong> {{ count($dadosTema['paginas_disponiveis']) }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Caminho dos Assets:</strong> {{ $dadosTema['assets_path'] }}</p>
                    <p><strong>Página Principal:</strong> {{ $dadosTema['pagina_principal'] }}</p>
                </div>
                </div>
            </div>
        </div>
    </div>

<style>
.preview-container {
    position: relative;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
}

.preview-container iframe {
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .preview-container iframe {
        height: 600px;
    }
}
</style>
@endsection
