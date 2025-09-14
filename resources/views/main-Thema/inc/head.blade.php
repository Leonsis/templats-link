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
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    @if(\App\Helpers\HeadHelper::getFavicon())
        <link rel="icon" type="image/webp" href="{{ \App\Helpers\HeadHelper::getFavicon() }}">
    @endif
    
    <!-- Meta Tags Dinâmicas -->
    <title>@yield('title', \App\Helpers\HeadHelper::getMetaTitle($currentPage))</title>
    <meta name="description" content="{{ \App\Helpers\HeadHelper::getMetaDescription($currentPage) }}">
    <meta name="keywords" content="{{ \App\Helpers\HeadHelper::getMetaKeywords($currentPage) }}">
    
    <!-- Google Tag Manager (Head) -->
    @if(\App\Helpers\HeadHelper::getGtmHead($currentPage))
        {!! \App\Helpers\HeadHelper::getGtmHead($currentPage) !!}
    @endif
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS Principal -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
