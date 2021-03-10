@extends('layouts.website')

@section('title', trans('messages.welcome'))

@section('navbar')
    <nav class="header navbar navbar-expand-lg navbar-light container">
        <a class="navbar-brand" href="/">
            @if(env('APP_ENV')=='demo')
                <img class="my-0 mr-md-auto font-weight-normal no-stuck" width="100" src="/logo_demo_blanco.png">
                <img class="my-0 mr-md-auto font-weight-normal stuck" width="80px" src="/logo_demo_azul.png">
            @else
                <img class="my-0 mr-md-auto font-weight-normal no-stuck" width="100" src="/logo_beta_blanco.png">
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

    <div class="top-container">
        <div class="container">
            <div class="row">
                <div class="cloud_head_wrapper col-10 col-sm-12 col-md-6">
                    <div class="pricing-header py-3 pt-md-5 pb-md-4">
                        <h1 class="display-10">
                            Aprendizaje sin barreras
                        </h1>
                    </div>
                    <div class="form col-sm-12 no-pl">
                        @if(env('APP_ENV')!=='demo')
                            <a href="/signup" id="register_action"
                               class="btn user-register-button"/>
                            Comienza hoy ¡gratis!
                            </a>
                        @else
                        <a href="/login" id="register_action"
                           class="btn user-register-button"/>
                        Comienza hoy ¡gratis!
                        </a>
                        @endif
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 no-pl hidden-xs">
                    <img class="image-header" src="/images/resources/png/postal2.png"/>
                </div>
            </div>
        </div>
        <div class="wave-1"></div>
        <div class="wave-2"></div>
    </div>

    <div class="experience-wrapper">
    <div class="container">
        <div class="experience-cards">
            <h3>Nuestros favoritos:</h3>
            <div class="row">
                @foreach($experiences as $experience)
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="experience-card-item">
                        <div class="experience-card-image">
                            <img class="lazy image-responsive"  data-src="{{ $experience->full_path_cover }}">
                        </div>
                        <div class="experience-company">
                            <img class="image-responsive" src="{{ $experience->company_author->full_path_logo }}" />
                        </div>
                        <div class="experience-card-body">
                            <div class="experience-card-title">
                                <a>{{ $experience->title }}</a>
                            </div>
                            <div class="experience-card-summary">
                                <p>{{ $experience->summary }}</p>
                            </div>
                            <div class="experience-card-link">
                                <a href="{{ route('experience.preview',[$experience]) }}">Ver m&aacute;s</a>
                            </div>
                            <div class="experience-card-control">
                                <div class="experience-card-control-item pull-left block-inline">
                                    <a class="uppercase" href="{{ route('experience.viewer',[$experience]) }}">Comenzar</a>
                                </div>
                                <div class="experience-card-control-item pull-right block-inline">
                                    <span class="experience-card-price">{{ $experience->price>0 ? \Akaunting\Money\Money::USD($experience->price, true).'(MXN)' :'¡Gratis!' }}</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>

    <div class="apithy-features">
        <div class="">
            <img src="/images/resources/png/Instagram-outline.png" />
            <h2>
                Descubre apithy
            </h2>
             <div class="carrousel">
                <div>
                    <div class="carousel-item-text">
                        <span>Da rienda suelta a tu emoci&oacute;n por aprender o tu pasi&oacute;n por compartir conocimiento</span>
                    </div>
                    <div class="lottie" id="animation-1"></div>
                </div>
                <div>
                    <div class="carousel-item-text">
                        <span>¡Adiós a los cursos! Aprende como en la vida:<br/> Supera retos, acumula experiencias y crece a través del viaje</span>
                    </div>
                    <div class="lottie" id="animation-2"></div>
                </div>
                <div>
                    <div class="carousel-item-text">
                        <span>Contenidos digitales con alto engagement</span>
                    </div>
                    <div class="lottie" id="animation-3"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="advantage-info-section">
        <div class="container py-sm-5">
            <div class="row">
                <div class="advantage-item col-sm-4 mb-4 box-shadow">
                    <div class="text-center">
                        <img class="lazy img-responsive rounded" data-src="/images/resources/png/iconos/sinBarreras.png"  alt="...">
                    </div>
                    <div class="header-advantage">
                        <h4 class="font-weight-normal">Inclusivo</h4>
                    </div>
                    <div class="body">
                        <p>
                            ¿Trabajas fuera de una oficina? ¿quieres capacitar a operarios? Apithy lleva tu plan de capacitación a cualquier dispositivo, en cualquier lugar, sin límites de tiempo.
                        </p>
                    </div>
                </div>

                <div class="col-sm-8">
                    <div class="row">
                        <div class="advantage-item col-sm-5 mb-4 mr-sm-5 box-shadow">
                            <div class="text-center">
                                <img class="lazy img-responsive rounded"  data-src="/images/resources/png/microlearning.png"
                                     alt="...">
                            </div>
                            <div class="header-advantage">
                                <h4 class="font-weight-normal">Microlearning</h4>
                            </div>
                            <div class="body">
                                <p>
                                    Aprende lo que te apasiona, a tu ritmo y desde cualquier lugar.
                                </p>
                            </div>
                        </div>
                        <div class="advantage-item col-sm-5 mb-4 box-shadow">
                            <div class="text-center">
                                <img class="lazy img-responsive rounded" data-src="/images/resources/png/marketplace.png"
                                     alt="...">
                            </div>
                            <div class="header-advantage">
                                <h4 class="font-weight-normal">Marketplace</h4>
                            </div>
                            <div class="body">
                                <p>
                                    Experiencias digitales diseñadas por especialistas en la materia, líderes de opinión y empresas con amplia experiencia
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        @if(env('APP_ENV')!=='demo')
                            <a class="btn user-register-button" href="/signup">¡Visita nuestro Marketplace!</a>
                        @else
                            <a class="btn user-register-button" href="/login">¡Visita nuestro Marketplace!</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection