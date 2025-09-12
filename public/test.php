<?php
echo "XAMPP está funcionando!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";

// Teste de permissões
echo "<br>Teste de permissões:<br>";
echo "Storage writable: " . (is_writable('../storage') ? 'Sim' : 'Não') . "<br>";
echo "Bootstrap cache writable: " . (is_writable('../bootstrap/cache') ? 'Sim' : 'Não') . "<br>";
?>
