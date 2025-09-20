@php
    // Detectar página atual baseada na rota
    $currentPage = 'global';
    
    // Rotas principais
    if (request()->routeIs('home')) {
        $currentPage = 'home';
    } elseif (request()->routeIs('sobre')) {
        $currentPage = 'sobre';
    } elseif (request()->routeIs('contato')) {
        $currentPage = 'contato';
    } elseif (request()->routeIs('login')) {
        $currentPage = 'login';
    }
    
    // Rotas dinâmicas do tema
    $routeName = request()->route() ? request()->route()->getName() : '';
    if (str_starts_with($routeName, 'tema.Finazze.')) {
        $currentPage = str_replace('tema.Finazze.', '', $routeName);
    }
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
@include('temas.Finazze.inc.head')

<body>
    <!-- Google Tag Manager (noscript) -->
    @if(\App\Helpers\HeadHelper::getGtmBody($currentPage))
        {!! \App\Helpers\HeadHelper::getGtmBody($currentPage) !!}
    @endif
    
    @include('temas.Finazze.inc.nav')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @include('temas.Finazze.inc.footer')

    @include('temas.Finazze.inc.scripts')
</body>
</html>