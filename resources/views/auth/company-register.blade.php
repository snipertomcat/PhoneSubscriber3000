@extends('layouts.website',['body_class' => 'white'])

@section('title', trans('messages.register'))

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
                @if(env('APP_ENV')!=='noregister')
                    <a class="btn user-login-button ml-auto" href="/login">Ingresa</a>
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
            @php
            $email = '';
            $phone = '';
             if (isset($invitation)) {
                if (Master::isPhone($invitation->contact)) {
                    $phone = $invitation->contact;
                } else {
                    $email = $invitation->contact;
                }
            }
            @endphp
            <apithy-company-user-invitation-register
                    :company="{{ json_encode($company) }}"
                    inv-type="{{ old('register_type') ?? request('register_type')}}"
                    token="{{ old('invitation_code') ?? request('invitation_code', '') }}"
                    email="{{ old('email') ?? $email }}"
                    phone="{{ old('phone') ?? $phone }}">
            </apithy-company-user-invitation-register>
        </div>
    </div>
@endsection

@push('end_scripts')
    <script src="{{ mix('/js/manifest.js','website') }}"></script>
    <script src="{{ mix('/js/vendor.js','website') }}"></script>
    <script src="{{ mix('/js/website.js','website') }}"></script>
@endpush
