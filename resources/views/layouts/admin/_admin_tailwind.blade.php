@extends('layouts.master_tailwind')

@section('title')
    @yield('title')
@endsection

@push('styles')
    <link rel="stylesheet" href="/css/navbar_icons.css">
@endpush

@section('content')
    @hasSection('header')
        @yield('header')
    @else
        {{-- Header --}}
        @if(Auth::user()->isSuper())
            <apithy-navbar :user="{{\Auth::user()}}" ref="adminNav"></apithy-navbar>
            {{--
            <div class="flex">
                <div class="w-2/12 px-3"><a href="{{route('profile')}}">Inicio</a></div>
                <div class="w-2/12 px-3"><a href="{{route('users.index')}}">Listado de Usuarios</a></div>
                <div class="w-2/12 px-3"><a href="{{route('companies.index')}}">Listado de Empresas</a></div>
                <div class="w-2/12 px-3"><a href="{{route('experiences.index')}}">Apithy store</a></div>
                <div class="w-2/12 px-3"><a href="{{route('experiences.create')}}">Nueva experiencia</a></div>
                <div class="w-2/12 px-3"><a href="{{route('logout')}}">Cerrar sesión</a></div>
            </div>
            --}}
        @else
            <div class="flex">
                <div class="w-2/12 px-3"><a href="/">Inicio</a></div>
                <div class="w-2/12 px-3"><a href="/login">Iniciar sesión</a></div>
                <div class="w-2/12 px-3"><a href="/signup">Registrar</a></div>
                <div class="w-2/12 px-3"><a href="/logout">Cerrar sesión</a></div>
            </div>
        @endif
        {{-- End header --}}
    @endif
    @yield('body')
@endsection
