<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title><?php echo e(\App\Helpers\HeadHelper::getMetaTitle($currentPage ?? 'global', 'finazze')); ?></title>
     <meta name="description" content="<?php echo e(\App\Helpers\HeadHelper::getMetaDescription($currentPage ?? 'global', 'finazze')); ?>">
     <meta name="keywords" content="<?php echo e(\App\Helpers\HeadHelper::getMetaKeywords($currentPage ?? 'global', 'finazze')); ?>">

     <!--=====FAB ICON=======-->
    <?php if(\App\Helpers\HeadHelper::getFavicon($currentPage ?? 'global')): ?>
        <link rel="shortcut icon" href="<?php echo e(\App\Helpers\HeadHelper::getFavicon($currentPage ?? 'global')); ?>" type="image/x-icon">
    <!--===== GTM HEAD =======-->
    <?php if(\App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global')): ?>
        <?php echo \App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global'); ?>

    <?php endif; ?>
    <?php else: ?>
        <link rel="shortcut icon" href="<?php echo e(asset('temas/finazze/assets/img/logo/fav-logo1.png')); ?>" type="image/x-icon">
    <!--===== GTM HEAD =======-->
    <?php if(\App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global')): ?>
        <?php echo \App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global'); ?>

    <?php endif; ?>
    <?php endif; ?>

    <!--===== CSS LINK =======-->
    <link rel="stylesheet" href="<?php echo e(asset('temas/finazze/assets/css/plugins/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('temas/finazze/assets/css/plugins/aos.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('temas/finazze/assets/css/plugins/fontawesome.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('temas/finazze/assets/css/plugins/magnific-popup.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('temas/finazze/assets/css/plugins/owlcarousel.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('temas/finazze/assets/css/plugins/sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('temas/finazze/assets/css/plugins/slick-slider.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('temas/finazze/assets/css/plugins/nice-select.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('temas/finazze/assets/css/plugins/swiper-bundle.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('temas/finazze/assets/css/main.css')); ?>">

    <!--=====  JS SCRIPT LINK =======-->
    <script src="<?php echo e(asset('temas/finazze/assets/js/plugins/jquery-3-7-1.min.js')); ?>"></script>
</head><?php /**PATH C:\xampp\htdocs\templats-link\resources\views/temas/finazze/inc/head.blade.php ENDPATH**/ ?>