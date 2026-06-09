@php
    // Se currentPage já foi passado nos dados, usar esse valor
    if (!isset($currentPage)) {
        $currentPage = 'global';
    }
    
    // Se ainda não foi definido, detectar página atual baseada na rota
    if ($currentPage === 'global') {
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
        if (str_starts_with($routeName, 'tema.Ampiezza.')) {
            $currentPage = str_replace('tema.Ampiezza.', '', $routeName);
        } elseif (str_starts_with($routeName, 'tema.prestacon.')) {
            $currentPage = str_replace('tema.prestacon.', '', $routeName);
        } elseif (str_starts_with($routeName, 'tema.Mental-ice.')) {
            $currentPage = str_replace('tema.Mental-ice.', '', $routeName);
        }
        
        // Se ainda não encontrou, tentar obter do path da URL
        if ($currentPage === 'global') {
            $path = trim(request()->path(), '/');
            if (empty($path) || $path === 'home') {
                $currentPage = 'home';
            } else {
                // Tentar mapear path para nome de página
                $pathParts = explode('/', $path);
                $lastPart = end($pathParts);
                
                // Verificar se existe uma rota dinâmica para este path
                $rotaDinamica = \DB::table('rotas_dinamicas')
                    ->where('tema', 'Portfolio')
                    ->where('rota', '/' . $lastPart)
                    ->where('ativo', 1)
                    ->first();
                
                if ($rotaDinamica) {
                    $currentPage = $rotaDinamica->pagina;
                } else {
                    // Usar o último segmento do path como nome da página
                    $currentPage = $lastPart;
                }
            }
        }
    }
@endphp

<!DOCTYPE html>

@yield('html')

@include('temas.Portfolio.inc.head')

<body>
    <!-- Google Tag Manager (noscript) -->
    @if(\App\Helpers\HeadHelper::getGtmBody($currentPage))
        {!! \App\Helpers\HeadHelper::getGtmBody($currentPage) !!}
    @endif
    
    @include('temas.Portfolio.inc.nav')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @include('temas.Portfolio.inc.footer')

    @include('temas.Portfolio.inc.scripts')
    @include('floatingButton.index')
</body>
</html>