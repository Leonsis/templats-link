<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $__env->make('main-Thema.inc.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
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
?>

<body>
    <!-- Google Tag Manager (noscript) -->
    <?php
        $gtmBody = \App\Helpers\HeadHelper::getGtmBody($currentPage);
    ?>
    <?php if($gtmBody): ?>
        <?php echo $gtmBody; ?>

    <?php endif; ?>
    <?php if(!$path): ?>
        <?php echo $__env->make('main-Thema.inc.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php if(!$path): ?>
        <?php echo $__env->make('main-Thema.inc.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('floatingButton.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    
    <?php echo $__env->make('main-Thema.inc.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Templats-link-templats-link\resources\views/main-Thema/layouts/app.blade.php ENDPATH**/ ?>