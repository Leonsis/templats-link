# Templats-link

Projeto Laravel com páginas home, sobre e contato usando Blade como template engine.

## Estrutura do Projeto

- **Home**: Página inicial com apresentação dos serviços
- **Sobre**: Informações sobre a empresa, missão e valores
- **Contato**: Formulário de contato com validação

## Tecnologias Utilizadas

- Laravel 10
- PHP 8.1+
- Blade Template Engine
- Bootstrap 5
- Font Awesome

## Instalação

1. Clone o repositório
2. Execute `composer install`
3. Copie o arquivo `.env.example` para `.env`
4. Configure as variáveis de ambiente no arquivo `.env`
5. Execute `php artisan key:generate`
6. Configure o banco de dados (opcional)
7. Execute `php artisan serve` para iniciar o servidor

## Comandos para Instalação

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
└── public/
    └── index.php
```

## Funcionalidades

- Design responsivo
- Navegação entre páginas
- Formulário de contato com validação
- Layout moderno com Bootstrap
- Ícones Font Awesome
- Estrutura MVC do Laravel

## Desenvolvimento

Para desenvolvimento local, use o comando:

```bash
php artisan serve
```

O projeto estará disponível em `http://localhost:8000`

## Licença

Este projeto está sob a licença MIT.
