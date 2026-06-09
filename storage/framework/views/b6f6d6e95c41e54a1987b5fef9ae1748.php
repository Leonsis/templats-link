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
    
    // Dados mocados para main-Thema
    $dadosMocados = [
        'global' => [
            'title' => 'Templats Link - Templates e Desenvolvimento Web Profissional',
            'description' => 'Plataforma completa para templates, soluções web e desenvolvimento de sites profissionais. Templates modernos, responsivos e otimizados para SEO.',
            'keywords' => 'templates, desenvolvimento web, sites, laravel, php, html, css, javascript, bootstrap, responsivo, seo',
            'favicon' => 'uploads/favicons/favicon-main.svg'
        ],
        'home' => [
            'title' => 'Início - Templats Link | Templates e Desenvolvimento Web',
            'description' => 'Bem-vindo ao Templats Link! Encontre os melhores templates para seu projeto web. Desenvolvimento profissional, responsivo e otimizado.',
            'keywords' => 'templates, home, início, desenvolvimento web, sites profissionais, responsivo',
            'favicon' => 'uploads/favicons/favicon-main.svg'
        ],
        'sobre' => [
            'title' => 'Sobre Nós - Templats Link | Nossa História e Missão',
            'description' => 'Conheça a história do Templats Link. Somos uma empresa especializada em desenvolvimento web com mais de 5 anos de experiência.',
            'keywords' => 'sobre, empresa, história, missão, desenvolvimento web, experiência',
            'favicon' => 'uploads/favicons/favicon-main.svg'
        ],
        'contato' => [
            'title' => 'Contato - Templats Link | Entre em Contato Conosco',
            'description' => 'Entre em contato com o Templats Link. Estamos prontos para ajudar com seu projeto web. Fale conosco por telefone, email ou WhatsApp.',
            'keywords' => 'contato, telefone, email, whatsapp, suporte, desenvolvimento web',
            'favicon' => 'uploads/favicons/favicon-main.svg'
        ],
        'login' => [
            'title' => 'Login - Templats Link | Acesso ao Painel Administrativo',
            'description' => 'Faça login no painel administrativo do Templats Link. Acesso seguro e rápido para gerenciar seu site.',
            'keywords' => 'login, painel, administrativo, acesso, segurança',
            'favicon' => 'uploads/favicons/favicon-main.svg'
        ]
    ];
    
    $dadosAtuais = $dadosMocados[$currentPage] ?? $dadosMocados['global'];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Favicon -->
    <?php if($dadosAtuais['favicon']): ?>
        <?php
            $faviconExt = pathinfo($dadosAtuais['favicon'], PATHINFO_EXTENSION);
            $faviconType = $faviconExt === 'svg' ? 'image/svg+xml' : ($faviconExt === 'webp' ? 'image/webp' : 'image/png');
        ?>
        <link rel="icon" type="<?php echo e($faviconType); ?>" href="<?php echo e(route('favicon', ['filename' => basename($dadosAtuais['favicon'])])); ?>">
    <?php endif; ?>
    
    <!-- Meta Tags Mocadas -->
    <title><?php echo $__env->yieldContent('title', $dadosAtuais['title']); ?></title>
    <meta name="description" content="<?php echo e($dadosAtuais['description']); ?>">
    <meta name="keywords" content="<?php echo e($dadosAtuais['keywords']); ?>">
    
    <!-- URL Canônica (com www) - Normaliza páginas com conteúdo idêntico -->
    <?php
        $canonicalUrl = \App\Helpers\HeadHelper::getCanonicalUrl($currentPage ?? 'global', 'main-Thema');
    ?>
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    
    <!-- Meta tag para impedir indexação da página de login -->
    <?php if($currentPage === 'login'): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>
    
    <!-- Google Tag Manager (Head) -->
    <?php
        $gtmHead = \App\Helpers\HeadHelper::getGtmHead($currentPage);
    ?>
    <?php if($gtmHead): ?>
        <?php echo $gtmHead; ?>

    <?php endif; ?>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS Principal -->
    <link href="<?php echo e(asset('css/main.css')); ?>" rel="stylesheet">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<?php /**PATH C:\xampp\htdocs\Templats-link-templats-link\resources\views/main-Thema/inc/head.blade.php ENDPATH**/ ?>