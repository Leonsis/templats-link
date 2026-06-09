<!doctype html>
<html ⚡ lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    
    @php
        $hasBlogMeta = isset($blog) && is_array($blog) && isset($blog['meta_title']);
        $canonicalUrl = $canonicalUrl ?? HeadHelper::getCanonicalUrl($currentPage ?? 'global', 'Portfolio');
        $title = $hasBlogMeta ? ($blog['meta_title'] ?? '') : HeadHelper::getMetaTitle($currentPage ?? 'global', 'Portfolio');
        $description = $hasBlogMeta ? ($blog['meta_description'] ?? '') : HeadHelper::getMetaDescription($currentPage ?? 'global', 'Portfolio');
        $image = $hasBlogMeta ? ($blog['image'] ?? '') : '';
    @endphp
    
    <title>{{ $title }}</title>
    <link rel="canonical" href="{{ $canonicalUrl }}">
    
    <meta name="description" content="{{ $description }}">
    
    @if($image)
    <meta property="og:image" content="{{ $image }}">
    @endif
    
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="website">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    @if($image)
    <meta name="twitter:image" content="{{ $image }}">
    @endif
    
    <!-- AMP Runtime -->
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    
    <!-- AMP Components -->
    <script async custom-element="amp-img" src="https://cdn.ampproject.org/v0/amp-img-0.1.js"></script>
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
    <script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
    <script async custom-element="amp-audio" src="https://cdn.ampproject.org/v0/amp-audio-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ HeadHelper::getFavicon() }}" type="image/x-icon">
    
    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "Prestacon Contabilidade",
      "image": "https://www.prestacon.com.br/imagens/logo.png",
      "logo": "https://www.prestacon.com.br/imagens/logo.png",
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
    
    <!-- Critical CSS inline para mobile-first -->
    <style amp-custom>
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
        }
        
        /* Mobile-first: base styles */
        .container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header */
        header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 15px;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            color: #333;
        }
        
        /* Main content */
        main {
            min-height: 60vh;
            padding: 2rem 0;
        }
        
        /* Footer */
        footer {
            background-color: #f8f9fa;
            padding: 2rem 15px;
            margin-top: 3rem;
            text-align: center;
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
            margin-bottom: 1rem;
            font-weight: 600;
            line-height: 1.2;
        }
        
        h1 { font-size: 2rem; }
        h2 { font-size: 1.75rem; }
        h3 { font-size: 1.5rem; }
        h4 { font-size: 1.25rem; }
        
        p {
            margin-bottom: 1rem;
        }
        
        /* Links */
        a {
            color: #007bff;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            font-size: 1rem;
            text-decoration: none;
        }
        
        .btn:hover {
            background-color: #0056b3;
            text-decoration: none;
        }
        
        /* Images */
        amp-img {
            max-width: 100%;
            height: auto;
        }
        
        /* Responsive: Tablet e Desktop */
        @media (min-width: 768px) {
            .container {
                max-width: 750px;
            }
            
            h1 { font-size: 2.5rem; }
            h2 { font-size: 2rem; }
        }
        
        @media (min-width: 992px) {
            .container {
                max-width: 970px;
            }
        }
        
        @media (min-width: 1200px) {
            .container {
                max-width: 1170px;
            }
        }
    </style>
    
    <!-- Google Analytics (se configurado) -->
    @php
        $gtmHead = HeadHelper::getGtmHead($currentPage ?? 'global');
    @endphp
    @if($gtmHead && str_contains($gtmHead, 'gtag'))
        <amp-analytics type="gtag" data-credentials="include">
            <script type="application/json">
            {
                "vars": {
                    "gtag_id": "{{ config('services.google.analytics_id', '') }}",
                    "config": {
                        "{{ config('services.google.analytics_id', '') }}": {
                            "groups": "default"
                        }
                    }
                }
            }
            </script>
        </amp-analytics>
    @endif
</head>
<body>
    <header>
        <nav>
            <a href="{{ $canonicalUrl }}" class="logo">Prestacon Contabilidade</a>
        </nav>
    </header>
    
    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Prestacon Contabilidade. Todos os direitos reservados.</p>
            <p><a href="{{ $canonicalUrl }}">Ver versão completa</a></p>
        </div>
    </footer>
</body>
</html>

