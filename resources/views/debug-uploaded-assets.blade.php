<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico - uploaded_assets()</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { color: #ffc107; }
        .info { color: #17a2b8; }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .test-image {
            max-width: 300px;
            border: 2px solid #ddd;
            margin: 10px 0;
        }
        .broken-image {
            border-color: #dc3545;
        }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico - uploaded_assets()</h1>
    
    @php
        $testPath = App\Helpers\ContentFormHelper::getCampo('prestacon', 'home', 'imagem_4', 'src');
    @endphp
    
    <div class="card">
        <h2>1. Valor do Banco de Dados</h2>
        <p><strong>Campo:</strong> <code>imagem_4</code></p>
        <p><strong>Valor retornado:</strong></p>
        <pre>{{ $testPath ?? '(null ou vazio)' }}</pre>
        @if(empty($testPath))
            <p class="error">⚠️ O campo está vazio ou não foi encontrado no banco de dados!</p>
        @endif
    </div>
    
    @if(!empty($testPath))
        @php
            $url = uploaded_assets($testPath);
            $physicalPath = storage_path('app/public/' . str_replace('storage/app/public/', '', $testPath));
            $publicPath = public_path('storage/' . str_replace('storage/app/public/', '', $testPath));
            $fileExistsPhysical = file_exists($physicalPath);
            $fileExistsPublic = file_exists($publicPath);
            $storageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url(str_replace('storage/app/public/', '', $testPath));
        @endphp
        
        <div class="card">
            <h2>2. Processamento da Função uploaded_assets()</h2>
            <p><strong>URL gerada pela função uploaded_assets():</strong></p>
            <pre>{{ $url }}</pre>
            @if(empty($url))
                <p class="error">❌ A função retornou uma string vazia!</p>
            @elseif(!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://'))
                <p class="warning">⚠️ A URL não é absoluta (não começa com http:// ou https://)</p>
            @else
                <p class="success">✅ URL absoluta gerada corretamente</p>
            @endif
            
            <p><strong>Storage::disk('public')->url():</strong></p>
            <pre>{{ $storageUrl }}</pre>
            
            <p><strong>asset('/storage/...'):</strong></p>
            <pre>{{ asset('/storage/' . str_replace('storage/app/public/', '', $testPath)) }}</pre>
            
            <p><strong>Comparação:</strong></p>
            <ul>
                <li>uploaded_assets(): <code>{{ $url }}</code></li>
                <li>Storage::url(): <code>{{ $storageUrl }}</code></li>
                <li>asset(): <code>{{ asset('/storage/' . str_replace('storage/app/public/', '', $testPath)) }}</code></li>
            </ul>
        </div>
        
        <div class="card">
            <h2>3. Verificação de Arquivos Físicos</h2>
            <p><strong>Caminho físico (storage/app/public):</strong></p>
            <pre>{{ $physicalPath }}</pre>
            <p class="{{ $fileExistsPhysical ? 'success' : 'error' }}">
                {{ $fileExistsPhysical ? '✅ Arquivo EXISTE' : '❌ Arquivo NÃO EXISTE' }}
            </p>
            
            <p><strong>Caminho público (public/storage):</strong></p>
            <pre>{{ $publicPath }}</pre>
            <p class="{{ $fileExistsPublic ? 'success' : 'error' }}">
                {{ $fileExistsPublic ? '✅ Arquivo EXISTE' : '❌ Arquivo NÃO EXISTE' }}
            </p>
            
            @if(!$fileExistsPhysical && !$fileExistsPublic)
                <p class="warning">⚠️ O arquivo não foi encontrado em nenhum dos locais!</p>
            @endif
        </div>
        
        <div class="card">
            <h2>4. Teste de Renderização da Imagem</h2>
            <p><strong>URL usada:</strong> <code>{{ $url }}</code></p>
            
            <p><strong>Teste de acesso direto:</strong></p>
            <p><a href="{{ $url }}" target="_blank" rel="noopener">🔗 Abrir URL diretamente no navegador</a></p>
            
            <p><strong>Renderização na página:</strong></p>
            <img src="{{ $url }}" 
                 alt="Teste de imagem" 
                 class="test-image {{ !$fileExistsPhysical && !$fileExistsPublic ? 'broken-image' : '' }}"
                 onerror="this.style.border='3px solid red'; this.alt='ERRO: Imagem não carregou!'; console.error('Erro ao carregar imagem:', '{{ $url }}');">
            
            <p class="info">Se a imagem não aparecer acima, verifique o console do navegador (F12) para erros 404.</p>
            
            <p><strong>Verificação de cabeçalhos HTTP:</strong></p>
            @php
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                curl_close($ch);
            @endphp
            <p><strong>Código HTTP:</strong> 
                <span class="{{ $httpCode == 200 ? 'success' : 'error' }}">
                    {{ $httpCode ?? 'N/A' }}
                </span>
            </p>
            <p><strong>Content-Type:</strong> <code>{{ $contentType ?? 'N/A' }}</code></p>
            @if($httpCode != 200)
                <p class="error">⚠️ A URL retornou código HTTP {{ $httpCode }}. Verifique se o arquivo está acessível via web.</p>
            @endif
        </div>
        
        <div class="card">
            <h2>5. Configurações do Sistema</h2>
            <p><strong>APP_URL:</strong> <code>{{ config('app.url') }}</code></p>
            <p><strong>APP_DEBUG:</strong> <code>{{ config('app.debug') ? 'true' : 'false' }}</code></p>
            <p><strong>APP_ENV:</strong> <code>{{ config('app.env') }}</code></p>
            
            <p><strong>Storage Disk 'public' URL:</strong></p>
            <pre>{{ config('filesystems.disks.public.url') }}</pre>
            
            <p><strong>Link simbólico existe?</strong></p>
            @php
                $symlinkPath = public_path('storage');
                $symlinkExists = is_link($symlinkPath) || (is_dir($symlinkPath) && file_exists($symlinkPath));
            @endphp
            <p class="{{ $symlinkExists ? 'success' : 'error' }}">
                {{ $symlinkExists ? '✅ Sim' : '❌ Não - Execute: php artisan storage:link' }}
            </p>
            <pre>{{ $symlinkPath }}</pre>
        </div>
        
        <div class="card">
            <h2>6. Teste de Acesso ao Arquivo</h2>
            <p><strong>Teste se o arquivo pode ser lido:</strong></p>
            @php
                $readablePhysical = $fileExistsPhysical ? (is_readable($physicalPath) ? '✅ Legível' : '❌ Não legível') : 'N/A (arquivo não existe)';
                $readablePublic = $fileExistsPublic ? (is_readable($publicPath) ? '✅ Legível' : '❌ Não legível') : 'N/A (arquivo não existe)';
            @endphp
            <p><strong>Arquivo físico legível:</strong> {{ $readablePhysical }}</p>
            <p><strong>Arquivo público legível:</strong> {{ $readablePublic }}</p>
            
            @if($fileExistsPhysical || $fileExistsPublic)
                <p><strong>Tamanho do arquivo:</strong></p>
                @if($fileExistsPhysical)
                    <p>Físico: {{ number_format(filesize($physicalPath) / 1024, 2) }} KB</p>
                @endif
                @if($fileExistsPublic)
                    <p>Público: {{ number_format(filesize($publicPath) / 1024, 2) }} KB</p>
                @endif
            @endif
        </div>
        
        <div class="card">
            <h2>7. Logs Recentes</h2>
            <p class="info">Verifique os logs em <code>storage/logs/laravel.log</code> para mais detalhes.</p>
            <p>Procure por entradas que começam com <code>uploaded_assets</code></p>
            @if(config('app.debug'))
                <p class="success">✅ APP_DEBUG está ativado - os logs devem estar sendo gerados</p>
            @else
                <p class="warning">⚠️ APP_DEBUG está desativado - ative para ver logs detalhados</p>
            @endif
        </div>
        
    @else
        <div class="card">
            <h2 class="error">⚠️ Não foi possível testar</h2>
            <p>O campo <code>imagem_4</code> não retornou nenhum valor do banco de dados.</p>
            <p>Verifique:</p>
            <ul>
                <li>Se o campo existe na tabela <code>content_forms</code></li>
                <li>Se o tema é <code>prestacon</code> e a página é <code>home</code></li>
                <li>Se o campo está ativo (<code>ativo = '1'</code>)</li>
            </ul>
        </div>
    @endif
    
    <div class="card">
        <h2>📋 Checklist de Verificação na Hospedagem</h2>
        <ul>
            <li>✅ <code>APP_URL</code> configurado corretamente no <code>.env</code></li>
            <li>✅ Link simbólico criado: <code>php artisan storage:link</code></li>
            <li>✅ Permissões corretas: <code>chmod -R 755 storage public/storage</code></li>
            <li>✅ Arquivos existem em <code>storage/app/public/temas/...</code></li>
            <li>✅ <code>APP_DEBUG=true</code> temporariamente para ver logs</li>
        </ul>
    </div>
</body>
</html>

