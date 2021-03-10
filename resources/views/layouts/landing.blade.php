<!doctype html encoding="utf-8">
<head>
    <meta charset="utf-8">
    <meta content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;' name='viewport'/>
    <meta name="author" content="Apithy">
    <meta name="google-site-verification" content="2JVEwW4-BOr2K-pxCqM1cUf5rtsHxrAfCUtPY1nC1ws" />
    <link rel="shortcut icon" href="/images/favicon/favicon.icon" type="image/vnd.microsoft.icon" />
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('seo')

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-81964203-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('GA_ID') }}');
    </script>

@stack('init_styles')
    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/site.css" rel="stylesheet">
    <link href="/css/site_responsive.css" rel="stylesheet">
    <link rel="stylesheet" href="https://d1azc1qln24ryf.cloudfront.net/114779/Socicon/style-cf.css?9ukd8d">
    <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/theme-default.min.css"
          rel="stylesheet" type="text/css"/>
    <link async rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css" />
    <link rel="stylesheet" async href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/js/magnific-popup/dist/magnific-popup.css">
    <link href="https://vjs.zencdn.net/7.6.0/video-js.css" rel="stylesheet">

    @stack('end_styles')
    @yield('styles')
</head>


<body class="path-{{ getBodyClass() }} route-{{ Route::currentRouteName() }} @if(isset($body_class)){{$body_class}}@endif">

<div class="header-wrapper sticky-top ">
    @yield('navbar')
</div>
<span class="position-absolute trigger"><!-- hidden trigger to apply 'stuck' styles --></span>

<div class="page-content">
    @yield('body')
</div>

@stack('init_scripts')
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//code.jquery.com/jquery-latest.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.5.6/lottie.min.js" type="text/javascript"></script>



@stack('end_scripts')

<script>

    $(document).ready(function () {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')}});
    });

</script>
</body>
</html>