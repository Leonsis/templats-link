@extends('dashboard.layouts.admin')

@section('title', 'Meta Tags - ' . $temaAtivo)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-tags me-2"></i>
                    Meta Tags - {{ ucfirst($temaAtivo) }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Configurações de Meta Tags
                    </h5>
                </div>
                <div class="card-body">
                    @if($configuracoes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Página</th>
                                        <th>Meta Title</th>
                                        <th>Meta Description</th>
                                        <th>Última Atualização</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($configuracoes as $config)
                                        <tr>
                                            <td>{{ $config->pagina }}</td>
                                            <td>{{ $config->meta_title ?: 'Não definido' }}</td>
                                            <td>{{ $config->meta_description ?: 'Não definido' }}</td>
                                            <td>{{ \App\Helpers\DateHelper::format($config->updated_at) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Nenhuma configuração encontrada.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
