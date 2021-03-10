@extends('layouts.website',['body_class' => 'white'])

@section('title', trans('messages.welcome'))

@section('navbar')
    <nav class="header navbar navbar-expand-lg navbar-light container">
        <a class="navbar-brand" href="/">
            @if(env('APP_ENV')=='demo')
                <img class="my-0 mr-md-auto font-weight-normal no-stuck" width="100" src="/logo_demo_azul.png">
                <img class="my-0 mr-md-auto font-weight-normal stuck" width="80px" src="/logo_demo_azul.png">
            @else
                <img class="my-0 mr-md-auto font-weight-normal no-stuck" width="100" src="/logo_beta_azul.png">
                <img class="my-0 mr-md-auto font-weight-normal stuck" width="80px" src="/logo_beta_azul.png">
            @endif
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03"
                aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor03">
            <ul class="navbar-nav form-inline mr-auto">
                <li><a class="p-2 capital" href="/empresas">Apithy en tu empresa</a></li>
                <li><a class="p-2 capital" href="/aliados">Convi&eacute;rte en instructor</a></li>
                <a class="btn user-login-button ml-auto" href="/login">Ingresa</a>
                @if(env('APP_ENV')!=='demo')
                    <a class="btn user-register-button-top" href="/signup">Reg&iacute;strate</a>
                @endif
            </ul>
        </div>
    </nav>
@endSection

@section('body')
    <div class="container">
        <div class="row">
            <h1 class="display-10">
                Evoluciona el desarrollo de capital humano.<br> Súmate al primer ecosistema digital de aprendizaje
            </h1>
            <div class="col-sm-7 no-pl">
                <div class="py-3 pt-md-5 pb-md-4">
                    <br>
                    <h3 class="display-8">
                        Apithy es el aliado que te permitirá escalar tu modelo de negocio a través de nuestro
                        potente marketplace de contenidos.
                    </h3>
                    <br>
                    <p class="lead">
                        Te ofrecemos una plataforma multi-dispositivo que amplía tu alcance en el terreno digital y
                        te permite optimizar tu modelo de atención.
                    </p>
                    <p>
                        Tenemos el modelo de negocio que necesitas para lograrlo, poco esfuerzo y alto rendimiento a
                        través de un mayor volumen.
                    </p>
                    <a class="btn user-register-button ml-auto mt-3" href="/signup">Reg&iacute;strate</a>
                    <p class="mt-3">
                        Deja que tu conocimiento incremente tus ingresos.
                    </p>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="py-3 pt-md-5 pb-md-4">
                <img class="img-fluid partner-image" width="80%" align="center"
                     src="/images/resources/svg/aliados.svg"/>
                </div>
            </div>
        </div>
    </div>
@endsection