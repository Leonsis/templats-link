<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $__env->make('temas.finazze1.inc.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
?>

<body>
    <!-- Google Tag Manager (noscript) -->
    <?php if(\App\Helpers\HeadHelper::getGtmBody($currentPage)): ?>
        <?php echo \App\Helpers\HeadHelper::getGtmBody($currentPage); ?>

    <?php endif; ?>
    
    <?php echo $__env->make('temas.finazze1.inc.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('temas.finazze1.inc.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('temas.finazze1.inc.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\templats-link\resources\views/temas/finazze1/layouts/app.blade.php ENDPATH**/ ?>