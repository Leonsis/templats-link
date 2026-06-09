#!/bin/bash

# Script para iniciar o servidor Laravel
echo "Iniciando servidor Laravel para Templats-linkV2..."

# Navegar para o diretório do projeto
cd /Applications/XAMPP/xamppfiles/htdocs/Templats-linkV2

# Verificar se o Laravel está configurado
if [ ! -f "artisan" ]; then
    echo "Erro: Arquivo artisan não encontrado. Verifique se está no diretório correto."
    exit 1
fi

# Limpar cache
echo "Limpando cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Iniciar servidor
echo "Iniciando servidor Laravel..."
echo "Acesse: http://127.0.0.1:8000"
echo "Dashboard: http://127.0.0.1:8000/dashboard"
echo "Pressione Ctrl+C para parar o servidor"
echo ""

php artisan serve --host=127.0.0.1 --port=8000
