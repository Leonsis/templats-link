<head>
    <meta charset="utf-8">
    <?php
        $hasBlogMeta = isset($blog) && is_array($blog) && isset($blog['meta_title']);
        // Gerar URL AMP
        $ampUrl = \App\Helpers\AMPHelper::getAMPUrl(request()->fullUrl());
    ?>
    <?php if($hasBlogMeta): ?>
        <title><?php echo e($blog['meta_title']); ?></title>
        <meta name="description" content="<?php echo e($blog['meta_description'] ?? ''); ?>">
        <meta name="keywords" content="<?php echo e($blog['meta_keywords'] ?? ''); ?>">
    <?php else: ?>
        <?php
            // Garantir que currentPage está definido corretamente
            $pageName = $currentPage ?? 'global';
            
            // Se ainda é 'global', tentar detectar do path da URL
            if ($pageName === 'global') {
                $path = trim(request()->path(), '/');
                if (!empty($path)) {
                    $pathParts = explode('/', $path);
                    $lastPart = end($pathParts);
                    
                    // Verificar se existe uma rota dinâmica para este path
                    $rotaDinamica = \DB::table('rotas_dinamicas')
                        ->where('tema', 'Portfolio')
                        ->where('rota', '/' . $lastPart)
                        ->where('ativo', 1)
                        ->first();
                    
                    if ($rotaDinamica) {
                        $pageName = $rotaDinamica->pagina;
                    } else {
                        $pageName = $lastPart;
                    }
                }
            }
        ?>
        <title><?php echo e(\App\Helpers\HeadHelper::getMetaTitle($pageName, 'Portfolio')); ?></title>
        <meta name="description" content="<?php echo e(\App\Helpers\HeadHelper::getMetaDescription($pageName, 'Portfolio')); ?>">
        <meta name="keywords" content="<?php echo e(\App\Helpers\HeadHelper::getMetaKeywords($pageName, 'Portfolio')); ?>">
    <?php endif; ?>
    
    <!-- URL Canônica (com www) - Normaliza páginas com conteúdo idêntico -->
    <?php
        $canonicalUrl = \App\Helpers\HeadHelper::getCanonicalUrl($currentPage ?? 'global', 'Portfolio');
    ?>
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    
    <!-- Link para versão AMP -->
    <link rel="amphtml" href="<?php echo e($ampUrl); ?>">
    <meta content="Sobre Nós" property="og:title">
    <meta content="Sobre Nós" property="twitter:title">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    
    <!-- Resource Hints para otimizar carregamento - PRIMEIRO: preconnect para o próprio domínio -->
    <?php
        $appUrl = parse_url(config('app.url'), PHP_URL_HOST);
        $currentDomain = request()->getHost();
    ?>
    <link rel="preconnect" href="https://<?php echo e($currentDomain); ?>" crossorigin>
    <link rel="dns-prefetch" href="https://<?php echo e($currentDomain); ?>">
    <link rel="dns-prefetch" href="https://d3e54v103j8qbb.cloudfront.net">
    <link rel="preconnect" href="https://d3e54v103j8qbb.cloudfront.net" crossorigin>
    
    <!-- CSS Crítico: Carregar com alta prioridade e sem encadeamento -->
    <?php
        // Usar versão minificada em produção, versão normal em desenvolvimento
        $normalizeCss = config('app.env') === 'production' 
            ? asset('temas/Portfolio/assets/css/normalize.min.css')
            : asset('temas/Portfolio/assets/css/normalize.css');
        $style = asset('temas/Portfolio/assets/css/style.css');        
    ?>
    
    <!-- Preload CSS crítico com fetchpriority para reduzir latência -->
    <link rel="preload" href="<?php echo e($normalizeCss); ?>" as="style" fetchpriority="high">
    <link rel="preload" href="<?php echo e($style); ?>" as="style" fetchpriority="high">            
    <!-- Preload da imagem do logo (LCP element) com máxima prioridade -->
    <?php
        $logoUrl = \App\Helpers\NavbarHelper::getLogo();
    ?>
    <?php if($logoUrl): ?>
    <link rel="preload" href="<?php echo e($logoUrl); ?>" as="image" fetchpriority="high" type="image/webp">
    <?php endif; ?>
    
    <!-- Carregar CSS de forma síncrona para evitar encadeamento (melhor para LCP) -->
    <link href="<?php echo e($normalizeCss); ?>" rel="stylesheet">
    <link href="<?php echo e($style); ?>" rel="stylesheet">
        
    <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
    <link href="<?php echo e(\App\Helpers\HeadHelper::getFavicon()); ?>" rel="shortcut icon" type="image/x-icon">
    <link href="<?php echo e(asset('temas/Portfolio/assets/images/webclip.png')); ?>" rel="apple-touch-icon"><!--  Keep this css code to improve the font quality -->
    <!---->
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "LocalBusiness",
          "name": "Prestacon Contabilidade",
          "image": "https://www.prestacon.com.br/imagens/logo.png",
          "logo": "https://www.prestacon.com.br/imagens/logo.png"
          "@id": "https://www.prestacon.com.br",
          "url": "https://www.prestacon.com.br",
          "telephone": "+55 61 3562-9800",
          "priceRange": "$$",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "QS 07 Rua 123, Loja 02",
            "addressLocality": "Taguatinga Sul",
            "addressRegion": "DF",
            "postalCode": "72015-100",
            "addressCountry": "BR"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": -15.8345,
            "longitude": -48.0570
          },
          "openingHoursSpecification": [
            {
              "@type": "OpeningHoursSpecification",
              "dayOfWeek": [
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday"
              ],
              "opens": "08:00",
              "closes": "18:00"
            }
          ],
          "sameAs": [
            "https://www.facebook.com/prestacon",
            "https://www.instagram.com/prestaconcontabilidade/",
            "https://br.linkedin.com/company/prestacon-contabilidade",
            "https://x.com/prestacon",
            "https://www.youtube.com/channel/UC5bw3vmYyb2v2KmaoFIR4dQ"
          ]
        }
    </script>
    <!-- Critical CSS inline para evitar render blocking e reduzir CLS -->
    <style>
        * { 
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            -o-font-smoothing: antialiased;
        }
        
        /* Prevenir Layout Shift - Reservar espaço para elementos principais */
        main {
            min-height: 400px; /* Altura mínima para prevenir shift inicial */
            display: block;
        }
        
        /* Prevenir shift de elementos com opacity:0 */
        [style*="opacity:0"] {
            visibility: hidden;
            min-height: 1px; /* Reservar espaço mínimo */
        }
        
        [data-w-id] {
            will-change: opacity, transform; /* Otimizar animações */
        }
        
        /* Reservar espaço para imagens - prevenir CLS */
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        
        /* Aspect ratio para imagens comuns */
        .cover-image {
            aspect-ratio: 16 / 9;
            object-fit: cover;
            width: 100%;
        }
        
        .service-icon,
        .service-icon2 {
            aspect-ratio: 1 / 1;
            object-fit: contain;
            width: 80px;
            height: 80px;
        }
        
        .primary-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        
        .faq-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }
        
        .star-icon {
            width: 20px;
            height: 20px;
        }
        
        .quote-image {
            width: 40px;
            height: 40px;
        }
        
        .reviewer-img img {
            aspect-ratio: 1 / 1;
            object-fit: cover;
            width: 60px;
            height: 60px;
        }
        
        /* Reservar espaço para hero section */
        .hero-section {
            min-height: 500px;
        }
        
        .hero-content {
            min-height: 200px;
        }
        
        .hero-cards {
            min-height: 150px;
        }
        
        /* Reservar espaço para seções */
        .section {
            min-height: 300px;
        }
        
        /* Reservar espaço para cards de serviço */
        .service-card,
        .service-card2 {
            min-height: 200px;
        }
        
        /* Reservar espaço para reviews */
        .review-item {
            min-height: 250px;
        }
        
        /* Reservar espaço para brand blocks */
        .brand-block {
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .brand-block img {
            max-width: 150px;
            max-height: 80px;
            object-fit: contain;
        }
        
        /* Prevenir shift de navegação */
        nav {
            min-height: 70px;
        }
        
        /* Prevenir shift de footer */
        footer {
            min-height: 200px;
        }
        
        /* Accordion Fix */
        .faq6_accordion {
            transition: all 0.3s ease;
        }
        
        .faq6_question {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .faq6_question:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .faq6_answer {
            transition: height 0.3s ease, opacity 0.3s ease;
            overflow: hidden;
        }
        
        .faq6_answer[style*="height: 0px"] {
            opacity: 0;
        }
        
        .faq6_answer:not([style*="height: 0px"]) {
            opacity: 1;
        }
        
        /* Fix para elementos com opacity:0 que não foram animados pelo Webflow */
        @keyframes fadeInFallback {
            to {
                opacity: 1 !important;
                visibility: visible !important;
            }
        }
        
        .opacity-fallback {
            animation: fadeInFallback 0.5s ease-in-out forwards;
        }
        
        /* Font loading - prevenir FOUT/FOIT */
        @font-face {
            font-family: 'System Font';
            font-display: swap;
        }
        
        /* Prevenir shift durante carregamento de fontes */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-display: swap;
        }
        
        /* Prevenir shift de conteúdo dinâmico */
        .w-dyn-list,
        .w-dyn-items {
            min-height: 100px;
        }
        
        /* Prevenir shift de sliders */
        .w-slider {
            min-height: 300px;
        }
        
        /* Prevenir shift de botões */
        .primary-button,
        .secondary-button {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Prevenir shift de headings */
        h1, h2, h3, h4, h5, h6 {
            min-height: 1.2em;
            line-height: 1.2;
        }
        
        /* Prevenir shift de parágrafos */
        p {
            min-height: 1.5em;
        }
        
        /* Container stability */
        .container,
        .w-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }    
    </style>
    
    <?php
        $gtmHead = \App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global');
    ?>
    <?php if($gtmHead): ?>
        <?php echo $gtmHead; ?>

    <?php endif; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head><?php /**PATH C:\xampp\htdocs\Templats-link-templats-link\resources\views/temas/Portfolio/inc/head.blade.php ENDPATH**/ ?>