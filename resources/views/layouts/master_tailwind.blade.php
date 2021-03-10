<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" name="viewport">
    <link rel=icon type=image/png href="images/favicon/app-logo-128x128.png">
    <link rel=icon type=image/png sizes=16x16 href="images/favicon/favicon-16x16.png">
    <link rel=icon type=image/png sizes=32x32 href="images/favicon/favicon-32x32.png">
    <link rel=icon type=image/png sizes=96x96 href="images/favicon/favicon-96x96.png">
    <link rel=icon type=image/ico href="images/favicon/favicon.ico">

    <link rel="stylesheet" href="{{ mix('/css/app_tailwind.css','tailwind') }}">
    <link rel="stylesheet" href="{{ mix('/css/main-page-icons.css', 'tailwind') }}">
    <link rel="stylesheet" href="{{ asset('/tailwind/css/main-page.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-81964203-1"></script>

    <!-- End Google Analytics -->
    <script type="text/javascript" charset="UTF-8">
        // Google Analytics
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('GA_ID') }}');
    </script>
    @yield('seo')
    <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Exo+2|Montserrat&display=swap"> -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito|Sarabun&display=swap">
    <style>
        [v-cloak] {display: none}
    </style>
    <style>
        img[src*="clientify"] {
            display: none;
        }
    </style>
    @stack('styles')

    @yield('schema')

    @hasSection('title') <title>@yield('title')</title> @else <title>{{ $applicationName }}</title> @endif

</head>
<body class="path-{{ getBodyClass() }} route-{{ Route::currentRouteName() }}">
    <div id="app" v-cloak>
        <div v-cloak>
            @yield('content')
        </div>
        @stack('modals')
    </div>
    @routes
    <script>
        let tree_info = {};
    </script>
    @stack('init_scripts')
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="{{ mix('/js/modernizr.js', 'tailwind') }}"></script>
    <script src="{{ mix('/js/manifest.js', 'tailwind') }}"></script>
    <script src="{{ mix('/js/vendor.js', 'tailwind') }}"></script>
    <script src="{{ mix('/js/app_tailwind.js', 'tailwind') }}"></script>
    @stack('end_scripts')

</body>
</html>
