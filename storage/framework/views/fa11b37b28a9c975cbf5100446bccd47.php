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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Favicon -->
    <?php if(\App\Helpers\HeadHelper::getFavicon()): ?>
        <link rel="icon" type="image/webp" href="<?php echo e(\App\Helpers\HeadHelper::getFavicon()); ?>">
    <?php endif; ?>
    
    <!-- Meta Tags Dinâmicas -->
    <title><?php echo $__env->yieldContent('title', \App\Helpers\HeadHelper::getMetaTitle($currentPage)); ?></title>
    <meta name="description" content="<?php echo e(\App\Helpers\HeadHelper::getMetaDescription($currentPage)); ?>">
    <meta name="keywords" content="<?php echo e(\App\Helpers\HeadHelper::getMetaKeywords($currentPage)); ?>">
    
    <!-- Google Tag Manager (Head) -->
    <?php if(\App\Helpers\HeadHelper::getGtmHead($currentPage)): ?>
        <?php echo \App\Helpers\HeadHelper::getGtmHead($currentPage); ?>

    <?php endif; ?>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS Principal -->
    <link href="<?php echo e(asset('css/main.css')); ?>" rel="stylesheet">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<?php /**PATH C:\xampp\htdocs\templats-link\resources\views/main-Thema/inc/head.blade.php ENDPATH**/ ?>