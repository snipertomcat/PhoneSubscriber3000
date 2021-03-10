@extends('layouts.landing',['body_class' => 'white'])

@section('title', 'Apithy homepage')

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
        <apithy-homepage source="{{$host}}"></apithy-homepage>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="/js/magnific-popup/dist/magnific-popup.css">
    <link rel="stylesheet" type="text/css" href="/css/fullpage.min.css">
@endpush
@push('end_scripts')
    <script src="/js/magnific-popup/dist/jquery.magnific-popup.js"></script>
    <script src="/js/jquery.carouFredSel.js"></script>
    <script src="/js/fullpage.min.js"></script>
    <script src="{{mix('/js/manifest.js','website') }}"></script>
    <script src="{{mix('/js/vendor.js','website') }}"></script>
    <script data-turbolinks-suppress-warning src="{{ mix('/js/website.js','website') }}"></script>
    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <script src='https://vjs.zencdn.net/7.6.0/video.js'></script>
    <script type="text/javascript" src="/js/orientationChange.js"></script>
    <script>
        $(window).load(function(){
            let myPlayer = videojs('video-apithy');

            myPlayer.src({type: 'video/mp4', src: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/video-contents/Flyer_5.mp4'});

            $('.open-popup-link').magnificPopup({
                showCloseBtn:true,
                type:'inline',
                midClick: true,
                callbacks: {
                    open: function () {
                        myPlayer.ready(function() {
                            myPlayer.play();
                        });
                    },
                    close: function () {
                        myPlayer.ready(function() {
                            myPlayer.pause();
                        });
                    }
                }
            });
        });
    </script>



@endpush