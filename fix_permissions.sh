#!/bin/bash

# Script para corrigir permissões de todos os arquivos no diretório htdocs
# Este script deve ser executado com sudo

echo "Corrigindo permissões de todos os arquivos e diretórios..."
echo ""

# Obter o diretório atual
CURRENT_DIR=$(pwd)
USER_NAME=$(whoami)

echo "Diretório: $CURRENT_DIR"
echo "Usuário: $USER_NAME"
echo ""

# Alterar proprietário de todos os arquivos e diretórios para o usuário atual
echo "Alterando proprietário de todos os arquivos e diretórios..."
sudo chown -R $USER_NAME:staff "$CURRENT_DIR"

# Dar permissões de leitura, escrita e execução ao proprietário
echo "Configurando permissões de arquivos..."
find "$CURRENT_DIR" -type f -exec sudo chmod 644 {} \;

# Dar permissões de leitura, escrita e execução ao proprietário para diretórios
echo "Configurando permissões de diretórios..."
find "$CURRENT_DIR" -type d -exec sudo chmod 755 {} \;

# Dar permissões de escrita ao proprietário em todos os arquivos
echo "Adicionando permissão de escrita ao proprietário..."
sudo chmod -R u+w "$CURRENT_DIR"

# Dar permissões especiais para diretórios que precisam de escrita (storage, cache, etc)
echo "Configurando permissões especiais para diretórios de sistema..."
if [ -d "$CURRENT_DIR/storage" ]; then
    sudo chmod -R 775 "$CURRENT_DIR/storage"
    echo "  - storage: 775"
fi

if [ -d "$CURRENT_DIR/bootstrap/cache" ]; then
    sudo chmod -R 775 "$CURRENT_DIR/bootstrap/cache"
    echo "  - bootstrap/cache: 775"
fi

if [ -d "$CURRENT_DIR/public" ]; then
    sudo chmod -R 755 "$CURRENT_DIR/public"
    echo "  - public: 755"
fi

echo ""
echo "✅ Permissões corrigidas com sucesso!"
echo ""
echo "Verificando arquivos que ainda pertencem a outros usuários..."
OTHER_OWNERS=$(find "$CURRENT_DIR" ! -user $USER_NAME 2>/dev/null | wc -l | tr -d ' ')
if [ "$OTHER_OWNERS" -gt 0 ]; then
    echo "⚠️  Ainda existem $OTHER_OWNERS arquivos/diretórios de outros proprietários"
    echo "   Execute este script novamente com sudo se necessário"
else
    echo "✅ Todos os arquivos pertencem ao usuário $USER_NAME"
fi
