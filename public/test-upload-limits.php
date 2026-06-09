<?php
// Script para testar limites de upload
echo "<h2>🔧 Teste de Configurações de Upload</h2>";

echo "<h3>Configurações Atuais do PHP:</h3>";
echo "<ul>";
echo "<li><strong>upload_max_filesize:</strong> " . ini_get('upload_max_filesize') . "</li>";
echo "<li><strong>post_max_size:</strong> " . ini_get('post_max_size') . "</li>";
echo "<li><strong>memory_limit:</strong> " . ini_get('memory_limit') . "</li>";
echo "<li><strong>max_execution_time:</strong> " . ini_get('max_execution_time') . "</li>";
echo "<li><strong>max_input_time:</strong> " . ini_get('max_input_time') . "</li>";
echo "<li><strong>max_file_uploads:</strong> " . ini_get('max_file_uploads') . "</li>";
echo "</ul>";

echo "<h3>Teste de Upload:</h3>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    $file = $_FILES['test_file'];
    echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 5px;'>";
    echo "<h4>✅ Arquivo Recebido:</h4>";
    echo "<ul>";
    echo "<li><strong>Nome:</strong> " . htmlspecialchars($file['name']) . "</li>";
    echo "<li><strong>Tamanho:</strong> " . number_format($file['size'] / 1024 / 1024, 2) . " MB</li>";
    echo "<li><strong>Tipo:</strong> " . htmlspecialchars($file['type']) . "</li>";
    echo "<li><strong>Status:</strong> " . ($file['error'] === UPLOAD_ERR_OK ? "✅ Sucesso" : "❌ Erro: " . $file['error']) . "</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<form method='POST' enctype='multipart/form-data'>";
    echo "<p>Selecione um arquivo para testar o upload:</p>";
    echo "<input type='file' name='test_file' required>";
    echo "<br><br>";
    echo "<button type='submit' style='background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>Testar Upload</button>";
    echo "</form>";
}

echo "<hr>";
echo "<p><strong>💡 Dica:</strong> Se os limites ainda estiverem baixos, reinicie o Apache do XAMPP.</p>";
echo "<p><strong>🔗 Link para Dashboard:</strong> <a href='/dashboard/temas'>Ir para Dashboard de Temas</a></p>";
?>
