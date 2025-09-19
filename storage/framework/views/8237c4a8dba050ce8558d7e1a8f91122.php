<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Painel Administrativo'); ?> - Templats Link</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS Administrativo -->
    <link href="<?php echo e(asset('css/admin.css')); ?>" rel="stylesheet">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-shield-alt"></i> Admin Panel</h3>
            <div class="subtitle">Templats Link</div>
        </div>
        
        <nav class="sidebar-menu">
            <a href="<?php echo e(route('dashboard')); ?>" class="menu-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            
            <?php
                $temaAtivo = \App\Helpers\ThemeHelper::getActiveTheme();
            ?>
            
            <?php if($temaAtivo !== 'main-Thema'): ?>
                <a href="<?php echo e(route('dashboard.head')); ?>" class="menu-item <?php echo e(request()->routeIs('dashboard.head*') ? 'active' : ''); ?>">
                    <i class="fas fa-heading"></i>
                    Head
                </a>
                
                <a href="<?php echo e(route('dashboard.navbar')); ?>" class="menu-item <?php echo e(request()->routeIs('dashboard.navbar*') ? 'active' : ''); ?>">
                    <i class="fas fa-bars"></i>
                    Navbar/Footer
                </a>
                
                <a href="<?php echo e(route('dashboard.theme-pages')); ?>" class="menu-item <?php echo e(request()->routeIs('dashboard.theme-pages*') ? 'active' : ''); ?>">
                    <i class="fas fa-file-alt"></i>
                    Páginas do Tema
                </a>
            <?php endif; ?>
            
            <a href="<?php echo e(route('dashboard.temas')); ?>" class="menu-item <?php echo e(request()->routeIs('dashboard.temas*') ? 'active' : ''); ?>">
                <i class="fas fa-palette"></i>
                Temas
            </a>
            
            <div class="menu-divider"></div>
            
            <a href="<?php echo e(route('home')); ?>" class="menu-item">
                <i class="fas fa-home"></i>
                Site Principal
            </a>
            
            <div class="menu-divider"></div>
            
            <a href="<?php echo e(route('logout')); ?>" class="menu-item" 
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
                <h4 class="mb-0"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h4>
            </div>
            
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo e(strtoupper(substr(Auth::user()->nome, 0, 1))); ?>

                    </div>
                    <div>
                        <div class="fw-bold"><?php echo e(Auth::user()->nome); ?></div>
                        <small class="text-muted"><?php echo e(Auth::user()->tipo); ?></small>
                    </div>
                </div>
                
                <a href="<?php echo e(route('logout')); ?>" class="btn btn-outline-danger btn-sm" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i>Sair
                </a>
            </div>
        </header>

        <!-- Content Area -->
        <main class="content-area">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
    </form>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        document.getElementById('mobileToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
            
            // Adicionar overlay quando sidebar está aberto
            if (sidebar.classList.contains('show')) {
                createOverlay();
            } else {
                removeOverlay();
            }
        });

        // Criar overlay para mobile
        function createOverlay() {
            if (!document.getElementById('sidebar-overlay')) {
                const overlay = document.createElement('div');
                overlay.id = 'sidebar-overlay';
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 1040;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                `;
                document.body.appendChild(overlay);
                
                // Animar overlay
                setTimeout(() => {
                    overlay.style.opacity = '1';
                }, 10);
                
                // Fechar sidebar ao clicar no overlay
                overlay.addEventListener('click', closeSidebar);
            }
        }

        // Remover overlay
        function removeOverlay() {
            const overlay = document.getElementById('sidebar-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
                setTimeout(() => {
                    if (overlay.parentNode) {
                        overlay.parentNode.removeChild(overlay);
                    }
                }, 300);
            }
        }

        // Fechar sidebar
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.remove('show');
            removeOverlay();
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('mobileToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                closeSidebar();
            }
        });

        // Fechar sidebar ao redimensionar para desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });

        // Fechar sidebar ao navegar
        document.querySelectorAll('.menu-item').forEach(function(item) {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    setTimeout(closeSidebar, 100);
                }
            });
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Melhorar acessibilidade
        document.addEventListener('keydown', function(e) {
            // Fechar sidebar com ESC
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        // Melhorar performance em mobile
        if (window.innerWidth <= 768) {
            // Lazy load de imagens
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        }

        // Melhorar formulários em mobile
        if (window.innerWidth <= 768) {
            // Evitar zoom em inputs
            const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], textarea');
            inputs.forEach(input => {
                if (input.style.fontSize !== '16px') {
                    input.style.fontSize = '16px';
                }
            });
        }
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/templats-link/resources/views/dashboard/layouts/admin.blade.php ENDPATH**/ ?>