# Templats-link

Sistema desenvolvido com Laravel para gerenciamento de conteúdo e rastreamento de leads de sites institucionais.  
O projeto permite administrar páginas, captar contatos de visitantes e adicionar novos templates para alterar completamente o estilo visual do site de forma dinâmica.

## Funcionalidades

- Gerenciamento de conteúdo institucional
- Rastreamento e gerenciamento de leads
- Cadastro e administração de templates
- Alteração dinâmica do layout do site
- Páginas institucionais:
  - Home
  - Sobre
  - Contato
- Formulário de contato com validação
- Estrutura organizada utilizando Blade Components


## Estrutura do Projeto

- **Home**: Página inicial com apresentação dos serviços
- **Sobre**: Informações sobre a empresa, missão e valores
- **Contato**: Formulário de contato com validação

## Tecnologias Utilizadas

- Laravel 10
- PHP 8.2+
- Blade Template Engine
- Bootstrap 5
- Font Awesome
- Docker

1. Clone o repositório
2. Execute `composer install`
3. Copie o arquivo `.env.example` para `.env`
4. Configure as variáveis de ambiente no arquivo `.env`
5. Execute `php artisan key:generate`
6. Configure o banco de dados (opcional)
7. Execute `php artisan serve` para iniciar o servidor

### Comandos para Instalação Manual

```bash
# Instalar dependências
composer install

# Copiar arquivo de configuração
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate

# Iniciar servidor de desenvolvimento
php artisan serve
```

O projeto estará disponível em `http://localhost:8000`

## 🐳 Instalação com Docker (Opcional)

### Pré-requisitos

- Docker Desktop instalado ([Download aqui](https://www.docker.com/products/docker-desktop))
- Docker Compose (já incluído no Docker Desktop) ou Docker Engine com Compose

### Passo a passo

1. Copie o arquivo de ambiente (se necessário):

```bash
cp .env.example .env
```

2. Inicie os serviços com Docker Compose:

```bash
docker-compose up -d --build
```

3. Acesse a aplicação em `http://localhost:8000`

### Comandos úteis

```bash
# Iniciar os containers
docker-compose up -d

# Parar e remover containers
docker-compose down

# Ver logs (em tempo real)
docker-compose logs -f app

# Executar comandos artisan dentro do container
docker-compose exec app php artisan migrate --force

# Abrir um shell no container da aplicação
docker-compose exec app bash
```

## Estrutura de Arquivos
```
templats-link/
├── app/
│   └── Http/
│       └── Controllers/
│           ├── HomeController.php
│           ├── SobreController.php
│           └── ContatoController.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── home.blade.php
│       ├── sobre.blade.php
│       └── contato.blade.php
├── routes/
│   └── web.php
├── public/
│   └── index.php
├── Dockerfile
├── docker-compose.yml
```
## Funcionalidades

- Design responsivo
- Navegação entre páginas
- Formulário de contato com validação
- Layout moderno com Bootstrap
- Ícones Font Awesome
- Estrutura MVC do Laravel