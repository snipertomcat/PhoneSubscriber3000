@extends('layouts.landing',['body_class' => 'white'])

@section('seo')
<title>Apithy live the learning digital experience. Expo Qro 2019</title>
<meta name="abstract" content="Live the learning digital experience" />
<link rel="canonical" href="//www.apithy.com/" />
<meta name="description" content="Creamos ambientes digitales adaptativos para formar el talento de tu empresa, mediante contenidos generados a la medida de tus necesidades. Conoce Apithy LXP en la Expo Qro 2019" />
<meta name="keywords" content="apithy / LXP / LMS / Learning / elearning / formación / capacitación / educación digital / edudigital / expo queretaro / universidad corporativa / plataforma" />
<meta property="og:title" content="Apithy" />
<meta property="og:image" content="https://www.apithy.com/logo_image.png" />
<meta property="og:description" content="Creamos ambientes digitales adaptativos para formar el talento de tu empresa, mediante contenidos generados a la medida de tus necesidades. Conoce Apithy LXP en la Expo Qro 2019." />
<meta property="og:site_name" content="Apithy" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.apithy.com/landing/qro-event-2019" />
@endSection

@section('body')
    <div id="app">
        {{ csrf_field() }}
        <apithy-landingpage source="{{$host}}"></apithy-landingpage>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="/css/fullpage.min.css">
@endpush
@push('end_scripts')
    <script src="/js/jquery.carouFredSel.js"></script>
    <script src="/js/fullpage.min.js"></script>
    <script src="{{mix('/js/manifest.js','website') }}"></script>
    <script src="/website/js/vendor.js"></script>
    <script data-turbolinks-suppress-warning src="{{ mix('/js/website.js','website') }}"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', '{{ env('GA_ID') }}', 'auto');
        ga('send', 'pageview');
    </script>
@endpush