<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel Administrativo') - Templats Link</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: #2c3e50;
            --sidebar-text: #ecf0f1;
            --sidebar-hover: #34495e;
            --main-bg: #f8f9fa;
            --header-bg: #ffffff;
            --primary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--main-bg);
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--sidebar-bg) 0%, #34495e 100%);
            color: var(--sidebar-text);
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header h3 {
            color: #fff;
            font-weight: 600;
            margin: 0;
            font-size: 1.2rem;
        }

        .sidebar-header .subtitle {
            color: #bdc3c7;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background-color: var(--sidebar-hover);
            color: #fff;
            border-left-color: var(--primary-color);
            text-decoration: none;
        }

        .menu-item.active {
            background-color: var(--sidebar-hover);
            color: #fff;
            border-left-color: var(--primary-color);
        }

        .menu-item i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .menu-divider {
            height: 1px;
            background-color: rgba(255,255,255,0.1);
            margin: 1rem 0;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Header */
        .top-header {
            background-color: var(--header-bg);
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        /* Cards */
        .admin-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.3s ease;
        }

        .admin-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card.success {
            background: linear-gradient(135deg, var(--success-color) 0%, #2ecc71 100%);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e67e22 100%);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #c0392b 100%);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Buttons */
        .btn-admin {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-admin:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 1rem;
            }
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: #333;
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-shield-alt"></i> Admin Panel</h3>
            <div class="subtitle">Templats Link</div>
        </div>
        
        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            
            <a href="{{ route('dashboard.head') }}" class="menu-item {{ request()->routeIs('dashboard.head*') ? 'active' : '' }}">
                <i class="fas fa-heading"></i>
                Head
            </a>
            
            <a href="{{ route('dashboard.navbar') }}" class="menu-item {{ request()->routeIs('dashboard.navbar*') ? 'active' : '' }}">
                <i class="fas fa-bars"></i>
                Navbar/Footer
            </a>
            
            <div class="menu-divider"></div>
            
            <a href="{{ route('home') }}" class="menu-item">
                <i class="fas fa-home"></i>
                Site Principal
            </a>
            
            <div class="menu-divider"></div>
            
            <a href="{{ route('logout') }}" class="menu-item" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                Sair
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
            </div>
            
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->nome, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ Auth::user()->nome }}</div>
                        <small class="text-muted">{{ Auth::user()->tipo }}</small>
                    </div>
                </div>
                
                <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i>Sair
                </a>
            </div>
        </header>

        <!-- Content Area -->
        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        document.getElementById('mobileToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('mobileToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
