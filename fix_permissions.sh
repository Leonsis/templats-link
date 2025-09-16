#!/bin/bash

# Script para corrigir permissões do Laravel no XAMPP
echo "Corrigindo permissões do Laravel..."

# Corrigir permissões da pasta storage
echo "Corrigindo permissões da pasta storage..."
chmod -R 777 storage/

# Corrigir permissões da pasta bootstrap/cache
echo "Corrigindo permissões da pasta bootstrap/cache..."
chmod -R 777 bootstrap/cache/

# Corrigir permissões da pasta config
echo "Corrigindo permissões da pasta config..."
chmod 777 config/

# Corrigir permissões do arquivo de configuração do tema
echo "Corrigindo permissões do arquivo de configuração do tema..."
if [ -f "config/tema_principal.php" ]; then
    chmod 666 config/tema_principal.php
fi

echo "Permissões corrigidas com sucesso!"
echo ""
echo "Agora você pode usar a funcionalidade de seleção de temas no painel administrativo."
