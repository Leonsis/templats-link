@php
    // Detectar página atual baseada na rota
    $currentPage = 'global';
    if (request()->routeIs('home')) {
        $currentPage = 'home';
    } elseif (request()->routeIs('sobre')) {
        $currentPage = 'sobre';
    } elseif (request()->routeIs('contato')) {
        $currentPage = 'contato';
    } elseif (request()->routeIs('login')) {
        $currentPage = 'login';
    }
    
    // Dados mocados para main-Thema
    $dadosMocados = [
        'global' => [
            'title' => 'Templats Link - Templates e Desenvolvimento Web Profissional',
            'description' => 'Plataforma completa para templates, soluções web e desenvolvimento de sites profissionais. Templates modernos, responsivos e otimizados para SEO.',
            'keywords' => 'templates, desenvolvimento web, sites, laravel, php, html, css, javascript, bootstrap, responsivo, seo',
            'favicon' => 'uploads/favicons/favicon-main.webp'
        ],
        'home' => [
            'title' => 'Início - Templats Link | Templates e Desenvolvimento Web',
            'description' => 'Bem-vindo ao Templats Link! Encontre os melhores templates para seu projeto web. Desenvolvimento profissional, responsivo e otimizado.',
            'keywords' => 'templates, home, início, desenvolvimento web, sites profissionais, responsivo',
            'favicon' => 'uploads/favicons/favicon-main.webp'
        ],
        'sobre' => [
            'title' => 'Sobre Nós - Templats Link | Nossa História e Missão',
            'description' => 'Conheça a história do Templats Link. Somos uma empresa especializada em desenvolvimento web com mais de 5 anos de experiência.',
            'keywords' => 'sobre, empresa, história, missão, desenvolvimento web, experiência',
            'favicon' => 'uploads/favicons/favicon-main.webp'
        ],
        'contato' => [
            'title' => 'Contato - Templats Link | Entre em Contato Conosco',
            'description' => 'Entre em contato com o Templats Link. Estamos prontos para ajudar com seu projeto web. Fale conosco por telefone, email ou WhatsApp.',
            'keywords' => 'contato, telefone, email, whatsapp, suporte, desenvolvimento web',
            'favicon' => 'uploads/favicons/favicon-main.webp'
        ],
        'login' => [
            'title' => 'Login - Templats Link | Acesso ao Painel Administrativo',
            'description' => 'Faça login no painel administrativo do Templats Link. Acesso seguro e rápido para gerenciar seu site.',
            'keywords' => 'login, painel, administrativo, acesso, segurança',
            'favicon' => 'uploads/favicons/favicon-main.webp'
        ]
    ];
    
    $dadosAtuais = $dadosMocados[$currentPage] ?? $dadosMocados['global'];
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    @if($dadosAtuais['favicon'])
        <link rel="icon" type="image/webp" href="{{ route('favicon', ['filename' => basename($dadosAtuais['favicon'])]) }}">
    @endif
    
    <!-- Meta Tags Mocadas -->
    <title>@yield('title', $dadosAtuais['title'])</title>
    <meta name="description" content="{{ $dadosAtuais['description'] }}">
    <meta name="keywords" content="{{ $dadosAtuais['keywords'] }}">
    
    <!-- Google Tag Manager (Head) - Mocado -->
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-XXXXXXX');</script>
    <!-- End Google Tag Manager -->
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS Principal -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
