# 📊 Atualizações do Banco de Dados - Templats Link

## ✅ **Arquivo Atualizado:** `database/templats_link.sql`

### 📅 **Data da Atualização:** 21/09/2025 11:21:52

### 📏 **Tamanho do Arquivo:** 23,744 bytes

## 🔧 **Principais Atualizações:**

### 1. **Nova Coluna Adicionada:**

- ✅ **Tabela:** `head_configs`
- ✅ **Coluna:** `email_formulario` (varchar(255))
- ✅ **Propósito:** Armazenar email para recebimento de dados de formulários

### 2. **Dados Atualizados:**

- ✅ **Configurações dos temas** com email configurado
- ✅ **Leads capturados** através dos formulários
- ✅ **Rotas dinâmicas** dos temas
- ✅ **Tokens de acesso** do sistema

### 3. **Estrutura Completa Exportada:**

- ✅ **head_configs** - Configurações dos temas e páginas
- ✅ **leads** - Dados dos formulários capturados
- ✅ **rotas_dinamicas** - Rotas dos temas
- ✅ **personal_access_tokens** - Tokens de API

## 📧 **Email Configurado:**

- **Email Principal:** `caiolenni@gmail.com`
- **Configurado em:** Tema main-Thema e Finazze
- **Funcionalidade:** Recebe dados dos formulários de contato

## 🎯 **Leads Capturados:**

- **Total de Leads:** 6 registros
- **Origem:** Botões flutuantes e formulários
- **Dados Incluídos:** Nome, email, telefone, origem, data

## 🚀 **Como Usar o Arquivo:**

### **Para Restaurar o Banco:**

```sql
-- 1. Criar o banco
CREATE DATABASE templats_link;

-- 2. Importar o arquivo
mysql -u root -p templats_link < database/templats_link.sql
```

### **Para Backup:**

```bash
# O arquivo já está atualizado e pronto para uso
cp database/templats_link.sql backup/templats_link_$(date +%Y%m%d).sql
```

## 📋 **Verificações Realizadas:**

- ✅ Estrutura da tabela `head_configs` inclui `email_formulario`
- ✅ Dados de configuração incluem email configurado
- ✅ Leads capturados estão preservados
- ✅ Todas as tabelas exportadas corretamente
- ✅ Arquivo SQL válido e completo

## 🔄 **Próximos Passos:**

1. **Backup Regular:** Fazer backup do arquivo periodicamente
2. **Versionamento:** Manter histórico das atualizações
3. **Sincronização:** Atualizar em outros ambientes quando necessário

**Status:** ✅ **Banco de dados atualizado com sucesso!**
