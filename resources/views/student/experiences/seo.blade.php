@section('seo')
    <meta name="abstract" content="{{$experience->title}}" />
    <link rel="canonical" href="{{url()->current()}}" />
    <meta name="description" content="{{$experience->description}}" />
    <meta name="keywords" content="cursos en linea,cursos online,e-learning,E-Learning,E-learning,capacitacion online"/>
    <meta property="og:title" content="{{$experience->title}}" />
    <meta property="og:image" content="{{$experience->full_path_cover}}" />
    <meta property="og:description" content="{{$experience->description}}" />
    <meta property="og:site_name" content="Apithy" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{url()->current()}}" />
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-81964203-1"></script>
    <!-- End Google Analytics -->
    <script type="text/javascript" charset="UTF-8">
        // Google Analytics
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('GA_ID') }}');

        // Facebook Pixel Code
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '463529894292255');
        fbq('track', 'PageView');

        // Clientify Tracking
        if (typeof trackerCode ==='undefined'){
            (function (d, w, u, o) {
                w[o] = w[o] || function () {
                    (w[o].q = w[o].q || []).push(arguments)
                };
                a = d.createElement('script'),
                    m = d.getElementsByTagName('script')[0];
                a.async = 1; a.src = u;
                m.parentNode.insertBefore(a, m)
            })(document, window, 'https://analytics.clientify.net/tracker.js', 'ana');
            ana('setTrackerUrl', 'https://analytics.clientify.net');
            ana('setTrackingCode', 'CF-4829-4829-QYD0F');
            ana('trackPageview');
        }
    </script>

    <noscript>
        <img height="1"
             width="1"
             style="display:none"
             alt="no-script"
             src="https://www.facebook.com/tr?id=463529894292255&ev=PageView&noscript=1"
        />
    </noscript>
@endsection

@section('schema')
    <script type="application/ld+json" charset="UTF-8">
    {
      "@context": "https://schema.org",
      "@graph": [
          {
              "@type": "Course",
              "@id": "{{url()->current()}}",
              "name":"{{$experience->title}}",
              "description": "{{$experience->description}}",
              "provider": {
                  "@type": "Organization",
                  "@id": "https://www.apithy.com/",
                  "name": "apithy, live the learning digital experience",
                  "url": "https://www.apithy.com/"
              },
              "author": {
                  "@type": "Organization",
                  "@id": "https://www.apithy.com/",
                  "name": "apithy, live the learning digital experience",
                  "url": "https://www.apithy.com/"
              },
              "isAccessibleForFree": "false"
          }
      ]
  }
</script>
@endsection
