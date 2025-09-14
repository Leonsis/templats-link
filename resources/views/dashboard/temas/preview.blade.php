<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - {{ $dadosTema['nome'] }}</title>
    
    <!-- Bootstrap CSS para o header de preview -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .preview-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .preview-content {
            margin-top: 80px;
        }
        
        .preview-iframe {
            width: 100%;
            height: calc(100vh - 80px);
            border: none;
            background: white;
        }
        
        .page-selector {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
        }
        
        .page-selector:focus {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.3);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.25);
        }
        
        .page-selector option {
            background: #667eea;
            color: white;
        }
        
        .btn-preview {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
        }
        
        .btn-preview:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.4);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header de Preview -->
    <div class="preview-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h5 class="mb-0">
                        <i class="fas fa-eye me-2"></i>
                        Preview: <strong>{{ $dadosTema['nome'] }}</strong>
                    </h5>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <label class="form-label me-2 mb-0 text-white">Página:</label>
                        <select class="form-select page-selector" id="pageSelector" style="width: auto;">
                            @foreach($dadosTema['arquivos_disponiveis'] as $arquivo)
                                <option value="{{ $arquivo }}" 
                                        {{ $arquivo === $dadosTema['pagina_principal'] ? 'selected' : '' }}>
                                    {{ ucfirst($arquivo) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button type="button" class="btn btn-preview me-2" onclick="refreshPreview()">
                        <i class="fas fa-sync-alt"></i> Atualizar
                    </button>
                    <button type="button" class="btn btn-preview me-2" onclick="toggleFullscreen()">
                        <i class="fas fa-expand"></i> Tela Cheia
                    </button>
                    <a href="{{ route('dashboard.temas') }}" class="btn btn-preview">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo do Preview -->
    <div class="preview-content">
        <iframe id="previewFrame" 
                class="preview-iframe" 
                src="data:text/html;charset=utf-8,{{ urlencode($dadosTema['conteudo_html']) }}">
        </iframe>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Função para carregar uma página específica
        function loadPage(pageName) {
            const iframe = document.getElementById('previewFrame');
            
            // Fazer uma requisição AJAX para obter o conteúdo da página
            fetch(`{{ route('dashboard.temas.preview.page', [$dadosTema['nome'], ':pagina']) }}`.replace(':pagina', pageName))
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Página não encontrada');
                    }
                    return response.text();
                })
                .then(html => {
                    iframe.src = 'data:text/html;charset=utf-8,' + encodeURIComponent(html);
                })
                .catch(error => {
                    console.error('Erro ao carregar página:', error);
                    iframe.src = 'data:text/html;charset=utf-8,' + encodeURIComponent(`
                        <html>
                            <body style="padding: 20px; font-family: Arial, sans-serif;">
                                <h2>Erro ao carregar página</h2>
                                <p>Não foi possível carregar a página "${pageName}".</p>
                                <p><small>Erro: ${error.message}</small></p>
                            </body>
                        </html>
                    `);
                });
        }
        
        // Função para processar os caminhos dos assets no HTML
        function processHtmlAssets(html) {
            const assetsPath = '{{ $dadosTema['assets_path'] }}';
            
            // Substituir caminhos relativos dos assets
            html = html.replace(/href="assets\//g, `href="${assetsPath}/`);
            html = html.replace(/src="assets\//g, `src="${assetsPath}/`);
            html = html.replace(/url\(assets\//g, `url(${assetsPath}/`);
            html = html.replace(/url\("assets\//g, `url("${assetsPath}/`);
            html = html.replace(/url\('assets\//g, `url('${assetsPath}/`);
            
            return html;
        }
        
        // Função para atualizar o preview
        function refreshPreview() {
            const currentPage = document.getElementById('pageSelector').value;
            loadPage(currentPage);
        }
        
        // Função para alternar tela cheia
        function toggleFullscreen() {
            const iframe = document.getElementById('previewFrame');
            
            if (!document.fullscreenElement) {
                iframe.requestFullscreen().catch(err => {
                    console.log('Erro ao entrar em tela cheia:', err);
                });
            } else {
                document.exitFullscreen();
            }
        }
        
        // Event listener para mudança de página
        document.getElementById('pageSelector').addEventListener('change', function() {
            loadPage(this.value);
        });
        
        // Processar o HTML inicial
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = document.getElementById('pageSelector').value;
            loadPage(currentPage);
        });
        
        // Atalhos de teclado
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F5') {
                e.preventDefault();
                refreshPreview();
            } else if (e.key === 'F11') {
                e.preventDefault();
                toggleFullscreen();
            } else if (e.key === 'Escape') {
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                }
            }
        });
    </script>
</body>
</html>
