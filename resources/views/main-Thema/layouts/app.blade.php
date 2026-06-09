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
    $path = request()->routeIs('login');
@endphp

<body>
    <!-- Google Tag Manager (noscript) -->
    @php
        $gtmBody = \App\Helpers\HeadHelper::getGtmBody($currentPage);
    @endphp
    @if($gtmBody)
        {!! $gtmBody !!}
    @endif
    @if(!$path)
        @include('main-Thema.inc.nav')
    @endif
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @if(!$path)
        @include('main-Thema.inc.footer')
        @include('floatingButton.index')
    @endif
    
    @include('main-Thema.inc.scripts')
</body>
</html>
