<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <i class="fas fa-rocket text-warning me-2 rocket-logo" style="font-size: 1.5rem;"></i>
            <span class="fw-bold">Templats Link</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sobre') ? 'active' : '' }}" href="{{ route('sobre') }}">
                        <i class="fas fa-info-circle me-1"></i>Sobre
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contato') ? 'active' : '' }}" href="{{ route('contato') }}">
                        <i class="fas fa-envelope me-1"></i>Contato
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('blog.public*') ? 'active' : '' }}" href="{{ route('blog.public.index') }}">
                        <i class="fas fa-blog me-1"></i>Blog
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-white border-0 p-2">
                            <i class="fas fa-sign-out-alt me-1"></i>Sair
                        </button>
                    </form>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
