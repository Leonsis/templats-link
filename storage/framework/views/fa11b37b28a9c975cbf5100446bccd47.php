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
    
    <style>
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 40px 0;
            
        }
        .card {
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<?php /**PATH C:\xampp\htdocs\templats-link\resources\views/main-Thema/inc/head.blade.php ENDPATH**/ ?>