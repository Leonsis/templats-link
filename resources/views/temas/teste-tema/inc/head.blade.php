<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>{{ \App\Helpers\HeadHelper::getMetaTitle($currentPage ?? 'global') }}</title>
     <meta name="description" content="{{ \App\Helpers\HeadHelper::getMetaDescription($currentPage ?? 'global') }}">
     <meta name="keywords" content="{{ \App\Helpers\HeadHelper::getMetaKeywords($currentPage ?? 'global') }}">
     @if(\App\Helpers\HeadHelper::getFavicon($currentPage ?? 'global'))
        <link rel="shortcut icon" href="{{ \App\Helpers\HeadHelper::getFavicon($currentPage ?? 'global') }}" type="image/x-icon">
    <!--===== GTM HEAD =======-->
    @if(\App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global'))
        {!! \App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global') !!}
    @endif
    @else
        <link rel="shortcut icon" href="{{ asset('temas/teste-tema/assets/img/logo/fav-logo1.png') }}" type="image/x-icon">
    <!--===== GTM HEAD =======-->
    @if(\App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global'))
        {!! \App\Helpers\HeadHelper::getGtmHead($currentPage ?? 'global') !!}
    @endif
    @endif
</head>