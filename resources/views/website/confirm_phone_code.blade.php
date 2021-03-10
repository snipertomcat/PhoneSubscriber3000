@extends('layouts.website', ['body_class' => 'white'])

@section('title', trans('messages.welcome'))

@section('navbar')
    <nav class="header navbar navbar-expand-md navbar-light container">
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
        <!--
        <div class="collapse navbar-collapse" id="navbarColor03">
            <ul class="navbar-nav form-inline mr-auto">
                <li><a class="p-2" href="/">Producto</a></li>
                <li><a class="p-2" href="/aliados">Aliados</a></li>
                <li><a class="p-2" href="#">Precios</a></li>
                <a class="btn user-login-button ml-auto" href="/signup">Ingresa</a>
                @if(env('APP_ENV')!=='demo')
                    <a class="btn user-register-button-top" href="/signup">Reg&iacute;strate</a>
                @endif
            </ul>
        </div>
        -->
    </nav>
@endSection

@section('body')
    <div id="app">
    <div class="top-container">
        {{ csrf_field() }}
        <apithy-confirm-code
                phone="{{request('phone')}}"
                token="{{request('token')}}">
        </apithy-confirm-code>
    </div>
    </div>
@endsection

@push('end_scripts')
    <script src="/website/js/manifest.js"></script>
    <script src="/website/js/vendor.js"></script>
    <script data-turbolinks-suppress-warning src="/website/js/website.js"></script>
@endpush