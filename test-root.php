<?php
/**
 * Arquivo de teste para verificar se o sistema está funcionando da raiz
 */

echo "<h1>✅ Sistema Configurado com Sucesso!</h1>";
echo "<p><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</p>";
echo "<p><strong>Diretório atual:</strong> " . __DIR__ . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";

// Verificar se o Laravel está carregando
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "<p>✅ <strong>Composer autoload:</strong> Encontrado</p>";
} else {
    echo "<p>❌ <strong>Composer autoload:</strong> Não encontrado</p>";
}

if (file_exists(__DIR__ . '/bootstrap/app.php')) {
    echo "<p>✅ <strong>Laravel bootstrap:</strong> Encontrado</p>";
} else {
    echo "<p>❌ <strong>Laravel bootstrap:</strong> Não encontrado</p>";
}

echo "<hr>";
echo "<p><a href='/'>🔗 Acessar Sistema Principal</a></p>";
echo "<p><a href='/sobre'>📄 Página Sobre</a></p>";
echo "<p><a href='/contato'>📧 Página Contato</a></p>";
?>
