@extends('layouts.full_page',['body_class' => 'white'])

@section('title', trans('messages.login'))

@push('styles')
    <style type="text/css">


        .path-company-login .page-content{
            background-color: white;
        }

        .width-100 {
            width: 100%;
        }
        .profile-avatar {
            overflow: hidden;
            box-shadow: 1px 1px 10px -3px rgba(0,0,0,.5);
            width: 120px;
            height: 120px;
        }
        .is-120x120 {
            width: 120px;
            height: 120px;
        }
        .company-cover {
            width: 70%;
            min-height: calc(100vh - 170px);
            position: absolute;
            background: url({{$company->full_path_cover}}) no-repeat center center;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            right: 0;
        }

        .company-login-color {
            background-color: white;
            width: 90%;
            height: calc(100vh - 170px);
            z-index: 1;
            position: absolute;
            transform: skewY(-49deg);
            transform-origin: left;
            right: -20%;
        }
    </style>
@endpush

@section('body')
    <div class="company-login-color"></div>
    <div class="login-bg">
        <div class="company-cover"></div>
    </div>
    <apithy-company-user-login
            :company="{{json_encode($company)}}"
            :form-errors="{{json_encode($errors)}}"
            :old-access="{{json_encode(old('access'))}}"
            :has-access-error="{{ json_encode($errors->has('access')) }}"
            :has-password-error="{{ json_encode($errors->has('password')) }}">
        <template slot="company-image">
            <div class="col-3 text-center pt-2">
                <div class="width-100">
                    <figure class="image image-center pointer profile-avatar">
                        <img src="{{$company->full_path_logo}}" alt="" class="is-120x120">
                    </figure>
                </div>
            </div>
            <br><br>
            <h1>Inicia sesi&oacute;n en {{$company->name}}</h1>
            <span>o </span><a href="{{ route('company.user.register') }}">crea una cuenta en {{$company->short_name}}.apithy.com</a>
            <br><br>
        </template>
        <template slot="partials-students-flash">
            @include('partials.students-flash')
        </template>
        <template slot="messages">
            @if (session('confirmation'))
                <article class="message is-success">
                    <div class="message-body">
                        <p>  {!! session('confirmation') !!} </p>
                    </div>
                </article>
            @endif

            @if (session('confirmation_sms'))
                <article class="message is-success">
                    <div class="message-body">
                        <p>  {!! session('confirmation_sms') !!} </p>
                    </div>
                </article>
            @endif

            @if (session('password_updated'))
                <article class="message is-success">
                    <div class="message-body">
                        <p>  {!! session('password_updated') !!} </p>
                    </div>
                </article>
            @endif
        </template>
        <template slot="token"> {{csrf_field()}} </template>
    </apithy-company-user-login>
@endsection
