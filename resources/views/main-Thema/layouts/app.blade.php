<!DOCTYPE html>
<html lang="pt-BR">
@include('main-Thema.inc.head')
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

<body>
    <!-- Google Tag Manager (noscript) - Mocado -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    
    @include('main-Thema.inc.nav')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @include('main-Thema.inc.footer')

    @include('main-Thema.inc.scripts')
</body>
</html>
