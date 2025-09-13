<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
            <?php if(\App\Helpers\HeadHelper::getLogo()): ?>
                <img src="<?php echo e(\App\Helpers\HeadHelper::getLogo()); ?>" alt="Logo" class="navbar-logo" style="height: 40px; max-width: 200px;">
            <?php else: ?>
                <i class="fas fa-link me-2"></i>Templats-link
            <?php endif; ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" href="<?php echo e(route('home')); ?>">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('sobre') ? 'active' : ''); ?>" href="<?php echo e(route('sobre')); ?>">
                        <i class="fas fa-info-circle me-1"></i>Sobre
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('contato') ? 'active' : ''); ?>" href="<?php echo e(route('contato')); ?>">
                        <i class="fas fa-envelope me-1"></i>Contato
                    </a>
                </li>
                <?php if(auth()->guard()->check()): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="nav-link btn btn-link text-white border-0 p-2">
                            <i class="fas fa-sign-out-alt me-1"></i>Sair
                        </button>
                    </form>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('login') ? 'active' : ''); ?>" href="<?php echo e(route('login')); ?>">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH C:\xampp\htdocs\templats-link\resources\views/main-Thema/inc/nav.blade.php ENDPATH**/ ?>