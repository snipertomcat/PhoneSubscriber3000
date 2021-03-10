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
                <div class="col-sm-12 col-md-4 no-pl image-block">
                    <img class="image-header" src="/images/resources/png/postal3.png"/>
                </div>
                <div class="cloud_head_wrapper col-10 col-sm-12 col-md-8">
                    <div class="form col-sm-12 no-pl">
                        <h2>
                            Apithy es el lugar donde convergen las emociones de un empleado, las necesidades de una
                            empresa por contar con el mejor talento y las oportunidades que nuestro ecosistema digital
                            te brinda.
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="wave-1"></div>
        <div class="wave-2"></div>
    </div>
    <div class="company-body-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-6">
                    <p class="company-summary">
                    Suma a tus proveedores de contenido en un espacio exclusivo para tu empresa o adquiere experiencias
                    digitales en nuestro Marketplace.
                    </p>

                    <h3 class="mt-5"><strong>¡Tú tienes el control!</strong></h3>
                </div>
                <div class="advantage-info-section col-sm-12 col-lg-6 mt-sm-5">
                    <div class="advantage-item row">
                        <div class="col-sm-3">
                            <img src="/images/resources/png/iconos/automatizaLaGestion.png" class="rounded"
                                 alt="...">
                        </div>
                        <div class="body col-sm-9">
                            <p>
                                Asigna los viajes a los que quieres embarcar a tu equipo y nosotros damos el
                                seguimiento
                                puntual
                                de su avance.
                            </p>
                        </div>
                    </div>
                    <div class="advantage-item row mt-5">
                        <div class="col-sm-3">
                            <img src="/images/resources/png/iconos/cadaQuienLoSuyo.png" class="rounded" alt="...">
                        </div>
                        <div class="body col-sm-9">
                            <p>
                                Tus colaboradores obtienen los viajes que necesitan y los consumen a su propio
                                ritmo. La
                                gestión
                                del conocimiento en tu empresa en sólo minutos.
                            </p>
                        </div>
                    </div>
                    <div class="advantage-item row mt-5">
                        <div class="col-sm-3">
                            <img src="/images/resources/png/iconos/sinBarreras.png" class="rounded" alt="...">
                        </div>
                        <div class="body col-sm-9">
                            <p>
                                Capacita a tu equipo desde cualquier dispositivo y en cualquier lugar. Lleva el
                                conocimiento
                                requerido por cada rol de tu organización.
                            </p>
                        </div>
                    </div>
                    <div class="advantage-item row mt-5">
                        <div class="col-sm-3">
                            <img src="/images/resources/png/iconos/aprendizajeSignificativo.png"
                                 class="rounded" alt="...">
                        </div>
                        <div class="body col-sm-9">
                            <p>
                                Genera experiencias digitales de aprendizaje, en la que el contenido y
                                la
                                ejercitación
                                promueven la adquisición de las competencias requeridas.
                            </p>
                        </div>
                    </div>
                    <div class="advantage-item row mt-5 mb-3">
                        <div class="col-sm-3">
                            <img src="/images/resources/png/iconos/optimizaTuPresupuesto.png"
                                 class="rounded" alt="...">
                        </div>
                        <div class="body col-sm-9">
                            <p>
                                El saber hacer y hacerlo apegado a procesos genera grandes ahorros a las
                                compañías.
                                Desarrolla a tu equipo de trabajo sin perder de vista el flujo de
                                operación.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection