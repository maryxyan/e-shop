<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ env('GOOGLE_ANALYTICS') }}');
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <title>DMG SHOP - Building Materials Shop</title>
    <meta name="description" content="Modern open-source e-commerce framework for free">
    <meta name="tags" content="modern, opensource, open-source, e-commerce, framework, free, laravel, php, php7, symfony, shop, shopping, responsive, fast, software, blade, cart, test driven, adminlte, storefront">
    <meta name="author" content="Jeff Simons Decena">
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header-top.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{ asset('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
    <script src="{{ asset('https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/LOGO.png')}}">
    <link rel="manifest" href="{{ asset('favicons/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/images/LOGO.png')}}">
    <meta name="theme-color" content="#ffffff">
    @yield('css')
    <meta property="og:url" content="{{ request()->url() }}"/>
    @yield('og')
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js') }}"></script>
</head>
<body>
<noscript>
    <p class="alert alert-danger">
        You need to turn on your javascript. Some functionality will not work if this is disabled.
        <a href="https://www.enable-javascript.com/" target="_blank">Read more</a>
    </p>
</noscript>
<section id="header-section">
    @include('layouts.front.header-top')
</section>
@yield('content')

@include('layouts.front.footer')

<script src="{{ asset('js/front.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@yield('js')
</body>
</html>