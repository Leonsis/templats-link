<head>
    <title>{{ \App\Helpers\HeadHelper::getMetaTitle($currentPage ?? 'global', 'Lumialto') }}</title>
    <meta name="description" content="{{ \App\Helpers\HeadHelper::getMetaDescription($currentPage ?? 'global', 'Lumialto') }}">
    <meta name="keywords" content="{{ \App\Helpers\HeadHelper::getMetaKeywords($currentPage ?? 'global', 'Lumialto') }}">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="Webflow" name="generator">
    <link href="{{ asset('temas/Lumialto/assets/css/normalize.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('temas/Lumialto/assets/css/webflow.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('temas/Lumialto/assets/css/lumiauto.webflow.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('temas/Lumialto/assets/css/flutuante.css') }}" rel="stylesheet" type="text/css"><!-- css dos CTAS flutuantes -->
    <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
    <link href="{{ asset('temas/Lumialto/assets/images/fav1.png') }}" rel="shortcut icon" type="image/x-icon">
    <link href="{{ asset('temas/Lumialto/assets/images/webclip.png') }}" rel="apple-touch-icon"><!--  Keep this css code to improve the font quality -->
    <style>
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            -o-font-smoothing: antialiased;
        }
    </style>
</head>