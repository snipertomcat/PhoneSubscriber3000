@extends('layouts.master_tailwind')

@section('title')
  apithy, live the learning digital experience
@endsection

@section('seo')
<meta name="abstract" content="Live the learning digital experience" />
<link rel="canonical" href="https://www.apithy.com" />
<meta name="description" content="Somos un LXP que potencia las habilidades y conocimientos de tu equipo de trabajo, mediante cursos online adaptativos." />
<meta name="keywords" content="cursos en linea,cursos online,e-learning,E-Learning,E-learning,capacitacion online"/>
<meta property="og:title" content="Apithy" />
<meta property="og:image" content="https://www.apithy.com/apithy_logo_200x200-01.jpg" />
<meta property="og:description" content="Somos un LXP que potencia las habilidades y conocimientos de tu equipo de trabajo, mediante cursos online adaptativos." />
<meta property="og:site_name" content="Apithy" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://www.apithy.com" />


<script type="text/javascript" charset="UTF-8">
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
              "@type": "WebPage",
              "@id": "https://www.apithy.com/",
              "description": "Somos un LXP que potencia las habilidades y conocimientos de tu equipo de trabajo, mediante cursos online adaptativos.",
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

@section('content')
  <!-- <coming-soon></coming-soon> -->
  <main-page :experiences="{{json_encode($experiences)}}"></main-page>
@endsection
