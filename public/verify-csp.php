<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificar CSP Real</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f0f0f0; }
        .box { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        pre { background: #f8f8f8; padding: 15px; border-left: 4px solid #4CAF50; overflow-x: auto; }
        .error { border-left-color: #f44336; }
        .success { border-left-color: #4CAF50; }
        button { background: #2196F3; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0b7dda; }
    </style>
</head>
<body>
    <h1>🔍 Verificador de CSP Real</h1>
    
    <div class="box">
        <h2>Headers HTTP da Página Atual:</h2>
        <pre><?php
            // Capturar todos os headers da resposta
            $headers = headers_list();
            foreach ($headers as $header) {
                if (stripos($header, 'Content-Security-Policy') !== false) {
                    echo "<strong style='color: green;'>✓ </strong>" . htmlspecialchars($header) . "\n";
                } else {
                    echo htmlspecialchars($header) . "\n";
                }
            }
        ?></pre>
    </div>
    
    <div class="box">
        <h2>Verificar Domínios no CSP:</h2>
        <?php
            $csp = '';
            foreach (headers_list() as $header) {
                if (stripos($header, 'Content-Security-Policy:') !== false) {
                    $csp = str_replace('Content-Security-Policy: ', '', $header);
                    break;
                }
            }
            
            $domains_to_check = [
                'https://analytics.google.com',
                'https://www.google-analytics.com',
                'https://stats.g.doubleclick.net',
                'https://www.googleadservices.com',
                'https://www.googletagmanager.com',
            ];
            
            echo "<pre>";
            foreach ($domains_to_check as $domain) {
                $found = stripos($csp, $domain) !== false;
                $icon = $found ? '✓' : '✗';
                $color = $found ? 'green' : 'red';
                echo "<strong style='color: $color;'>$icon</strong> $domain\n";
            }
            echo "</pre>";
        ?>
    </div>
    
    <div class="box">
        <h2>Testar Conexão Analytics:</h2>
        <button onclick="testAnalytics()">Testar Agora</button>
        <div id="result" style="margin-top: 15px;"></div>
    </div>

    <script>
        function testAnalytics() {
            const result = document.getElementById('result');
            result.innerHTML = '<pre>Testando...</pre>';
            
            // Tentar conectar ao analytics.google.com
            fetch('https://analytics.google.com/g/collect', {
                method: 'POST',
                mode: 'no-cors'
            })
            .then(() => {
                result.innerHTML = '<pre class="success">✓ Conexão com analytics.google.com funcionando!</pre>';
            })
            .catch(error => {
                result.innerHTML = '<pre class="error">✗ Erro: ' + error.message + '</pre>';
            });
        }
        
        // Verificar CSP via JavaScript
        window.onload = function() {
            console.log('=== VERIFICAÇÃO CSP ===');
            console.log('URL da página:', window.location.href);
            
            // Fazer requisição HEAD para verificar headers
            fetch(window.location.href, { method: 'HEAD' })
                .then(response => {
                    const csp = response.headers.get('Content-Security-Policy');
                    console.log('CSP via fetch:', csp);
                    
                    if (csp) {
                        console.log('✓ analytics.google.com:', csp.includes('analytics.google.com') ? 'SIM' : 'NÃO');
                        console.log('✓ stats.g.doubleclick.net:', csp.includes('stats.g.doubleclick.net') ? 'SIM' : 'NÃO');
                    }
                })
                .catch(error => {
                    console.error('Erro ao verificar headers:', error);
                });
        };
    </script>
</body>
</html>
