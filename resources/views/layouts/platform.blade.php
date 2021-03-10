@extends('layouts.master_platform', ['disable_owmer_nemu' => true])

@section('content')
    @if(Auth::user())
        <div class="student">
            <div class="ui top fixed main menu">
                <a class="launch icon item">
                    <i class="content icon"></i>
                </a>
                <div class="item">
                    <img src="{{ url('logo_hoja_demo_blanco.png') }}" alt="{{ $applicationName }}" class="displaynone"
                         width="50">
                </div>
                <div class="item borderless section-title">
                    @yield('section-title','Apithy')
                </div>
                <div class="menu ">
                    <div class="ui dropdown item bordered">
                        <img class="ui mini circular image" src="{{ $auth->user()->avatarUrl() }}" alt="label-image"/>
                        <div class="menu">
                            <a class="item labeled icon" href="{{url('/profile')}}">
                                <i class="user circle icon"></i>
                                Perfil
                            </a>
                            <a class="item labeled icon" href="{{'/profile/edit'}}">
                                <i class="cogs circle icon"></i>
                                Configuraci&oacute;n
                            </a>
                            <div class="ui divider"></div>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </form>
                            <a class="item labeled icon"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit()">
                                <i class="sign out circle icon"></i>
                                {{ trans('messages.logout') }}
                            </a>
                            @impersonating
                            <a class="item labeled icon" href="{{ route('impersonate.leave') }}">
                                <i class="sign out circle icon"></i>
                                Regresar a mi cuenta
                            </a>
                            @endImpersonating
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui vertical inverted sidebar menu left overlay" id="toc">
                <a class="ui item logo" href="/">
                    <img src="{{ url('images/apithy.png') }}" alt="{{ $applicationName }}" class="displaynone" width="100">
                </a>
                <a class="item" href="{{route("profile")}}">
                    <b>Mi Perfil</b>
                    <i class="user icon"></i>
                </a>
                <a class="item" href="/introduction/new.html">
                    <b>Notificaciones</b>
                    <i class="envelope icon"></i>
                </a>
                <div class="item">
                    <div class="header">
                        <a href="{{route("experiences.index")}}">Experiencias</a>
                        <i class="paper plane icon right floated" style="float: right"></i>
                    </div>
                    <div class="menu">
                        <a class="item" href="{{route("experience.enrolled")}}">
                            - Mis experiencias
                        </a>

                        <a class="item" href="{{route("experience.assignations")}}">
                            - Mis asignaciones
                        </a>

                        <a class="item" href="{{route("profile.index")}}">
                            - Registro de actividades
                        </a>

                    </div>
                </div>

            </div>
            <div class="pusher">
                <div class="content-wrapper">
                    <div class="ui container page">
                        @yield('body')
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection