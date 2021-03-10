@extends('layouts.website',['body_class' => 'white'])

@section('title', trans('messages.register'))

@section('navbar')
    <nav class="header navbar navbar-expand-md navbar-light container">
        <a class="navbar-brand" href="/">
            @if(env('APP_ENV')=='demo')
                <img class="my-0 mr-md-auto font-weight-normal no-stuck" width="100" src="/logo_demo_azul.png">
                <img class="my-0 mr-md-auto font-weight-normal stuck" width="80px" src="/logo_demo_azul.png">
            @else
                <img class="my-0 mr-md-auto font-weight-normal no-stuck" width="180" src="/logo_beta_azul.png">
                <img class="my-0 mr-md-auto font-weight-normal stuck" width="150" src="/logo_beta_azul.png">
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03"
                aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
@endSection

@section('body')
    <div id="app">
        <div class="top-container">
            {{ csrf_field() }}
            <apithy-company-user-register :company="{{json_encode($company)}}"></apithy-company-user-register>
        </div>
    </div>
@endsection

@push('end_scripts')
    <script src="{{ mix('/js/manifest.js','website') }}"></script>
    <script src="{{ mix('/js/vendor.js','website') }}"></script>
    <script src="{{ mix('/js/website.js','website') }}"></script>
@endpush