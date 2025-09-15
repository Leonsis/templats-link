<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>{{ \App\Helpers\HeadHelper::getMetaTitle($currentPage ?? 'global') }}</title>
     <meta name="description" content="{{ \App\Helpers\HeadHelper::getMetaDescription($currentPage ?? 'global') }}">
     <meta name="keywords" content="{{ \App\Helpers\HeadHelper::getMetaKeywords($currentPage ?? 'global') }}">

     <!--=====FAB ICON=======-->
    @if(\App\Helpers\HeadHelper::getFavicon($currentPage ?? 'global'))
        <link rel="shortcut icon" href="{{ \App\Helpers\HeadHelper::getFavicon($currentPage ?? 'global') }}" type="image/x-icon">
    <!--===== GTM HEAD =======-->
    @if(\App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global'))
        {!! \App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global') !!}
    @endif
    @else
        <link rel="shortcut icon" href="{{ asset('temas/finazze1/assets/img/logo/fav-logo1.png') }}" type="image/x-icon">
    <!--===== GTM HEAD =======-->
    @if(\App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global'))
        {!! \App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global') !!}
    @endif
    @endif

    <!--===== CSS LINK =======-->
    <link rel="stylesheet" href="{{ asset('temas/finazze1/assets/css/plugins/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('temas/finazze1/assets/css/plugins/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('temas/finazze1/assets/css/plugins/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('temas/finazze1/assets/css/plugins/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('temas/finazze1/assets/css/plugins/owlcarousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('temas/finazze1/assets/css/plugins/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('temas/finazze1/assets/css/plugins/slick-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('temas/finazze1/assets/css/plugins/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('temas/finazze1/assets/css/plugins/swiper-bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('temas/finazze1/assets/css/main.css') }}">

    <!--=====  JS SCRIPT LINK =======-->
    <script src="{{ asset('temas/finazze1/assets/js/plugins/jquery-3-7-1.min.js') }}"></script>
</head>