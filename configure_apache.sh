#!/bin/bash

# Script para configurar Apache para Laravel
echo "Configurando Apache para Laravel..."

# Caminho do arquivo de configuração
CONF_FILE="/Applications/XAMPP/xamppfiles/htdocs/Templats-linkV2/templats-link.conf"
APACHE_CONF="/Applications/XAMPP/xamppfiles/etc/httpd.conf"

# Verificar se o arquivo de configuração existe
if [ ! -f "$CONF_FILE" ]; then
    echo "Erro: Arquivo de configuração não encontrado: $CONF_FILE"
    exit 1
fi

# Verificar se o Apache está configurado
if ! grep -q "templats-link.conf" "$APACHE_CONF"; then
    echo "Adicionando include para templats-link.conf..."
    echo "" >> "$APACHE_CONF"
    echo "# Include para Templats-linkV2" >> "$APACHE_CONF"
    echo "Include \"$CONF_FILE\"" >> "$APACHE_CONF"
    echo "Configuração adicionada ao Apache!"
else
    echo "Configuração já existe no Apache!"
fi

# Adicionar entrada no hosts
HOSTS_FILE="/etc/hosts"
if ! grep -q "templats-link.local" "$HOSTS_FILE"; then
    echo "Adicionando entrada no /etc/hosts..."
    echo "127.0.0.1 templats-link.local" | sudo tee -a "$HOSTS_FILE"
    echo "Entrada adicionada ao hosts!"
else
    echo "Entrada já existe no hosts!"
fi

echo "Configuração concluída!"
echo "Para aplicar as mudanças, reinicie o Apache:"
echo "sudo /Applications/XAMPP/xamppfiles/bin/httpd -k restart"
