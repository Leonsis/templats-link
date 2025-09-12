<!DOCTYPE html>
<html lang="pt-BR">
@include('inc.head')
<body>
    @include('inc.nav')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @include('inc.footer')

    @include('inc.scripts')
</body>
</html>
