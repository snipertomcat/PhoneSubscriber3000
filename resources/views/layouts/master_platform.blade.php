<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" name="viewport"/>
    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
    <link rel="stylesheet" href="{{ mix('/css/app_platform.css','platform') }}">

    <meta name="theme-color" content="#ffffff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')
    @yield('seo')
    <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/custom-icons.css">
    @stack('meta')
    <title>@hasSection('title')@yield('title') • {{ $applicationName }} @else {{ $applicationName }} @endif</title>
    <style>
        #anim{max-width: 1000px; margin: 0 auto;}

        [v-cloak] {
            display: none
        }
    </style>

    @stack('styles')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="/js/lottie.js"></script>
    @stack('head-script')
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-81964203-1"></script>

    <!-- End Google Analytics -->
    <script type="text/javascript" charset="UTF-8">
        // Google Analytics
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('GA_ID') }}');
    </script>

    @yield('schema')

    <script>
        document.domain='apithy.com';
    </script>

</head>
<body class="path-{{ getBodyClass() }} route-{{ Route::currentRouteName() }} @if(isset($body_class)){{$body_class}}@endif">
    <div id="app" v-cloak>
        @if(!isset($no_navbar))
            @if(\Auth::user() && Auth::user()->isAdmin)
                <div class="header-wrapper" style="height: 100px">
                    @include('layouts._student_nav')
                </div>
            @else
                <div class="header-wrapper">
                    @include('layouts._student_nav')
                </div>
            @endif
        @endif
        <div v-cloak>
        @if(\Auth::user() && \Auth::user()->isAdmin && isset($disable_owner_menu))
            <div class="owner" style="display: flex">
                <div class="page-content width-100">
                    @yield('body')
                </div>
            </div>
        @else
            <div class="student">
                <div class="page-content">
                    @yield('body')
                </div>
            </div>
        @endif
        </div>
        @if(!isset($no_footer))
        <apithy-footer></apithy-footer>
        {{--
        <footer class="border-top apithy-footer">
            <div class="nav_bottom full-width">
                <div class="row align-items-center justify-content-center full-height">
                    <div class="col-12 col-md-auto">
                        <nav class="my-2 social-nav">
                            <a href="#" class="p-2 socicon-facebook"></a>
                            <a href="#" class="p-2 socicon-twitter"></a>
                        </nav>
                    </div>
                    <div class="col-12 col-md-auto">
                        <span class="my-2 font-weight-normal date-footer">
                            Apithy&reg; CDMX 2018<br>
                            TODOS LOS DERECHOS RESERVADOS
                        </span>
                    </div>
                    <div class="col-12 col-md-3 text-center">
                        <a href="/aviso-de-privacidad" class="my-2 font-weight-normal date-footer">Aviso de privacidad</a>
                    </div>
                    <div class="col-12 col-md-2 text-right">
                        <a class="my-2 font-weight-normal date-footer">M&eacute;xico</a>
                    </div>

                </div>
            </div>
        </footer>
        --}}
        @endif
        <!--footer class="border-top">
            <div class="nav_bottom container">
                <div class="row">
                    <nav class="my-2 social-nav">
                        <a href="#" class="p-2 socicon-facebook"></a>
                        <a href="#" class="p-2 socicon-twitter"></a>
                    </nav>
                    <span class="my-2 mr-md-auto font-weight-normal date-footer">
                        Apithy&reg; CDMX 2018<br>
                        TODOS LOS DERECHOS RESERVADOS
                    </span>
                    <a href="/aviso-de-privacidad" class="my-2 mr-md-auto font-weight-normal date-footer">Aviso de privacidad</a>
                    <a class="my-2 ml-md-auto font-weight-normal date-footer">M&eacute;xico</a>

                </div>
            </div>
        </footer-->
        <vue-snotify></vue-snotify>
    </div>
@routes
@stack('scripts')
<script src="{{mix('/js/manifest.js','platform') }}"></script>
<script src="{{mix('/js/vendor.js','platform') }}"></script>
<script src="{{ mix('/js/app.js','platform') }}"></script>
@stack('end_scripts')
    <script src="/js/apithy-url-reload-validator.js"></script>
</body>
</html>
