@extends('layouts.full_page')

@section('title','Acceso no autorizado')
@section('body')
    <div class="container h-100 mt-5">
        <div class="row align-items-center justify-content-center height-100">
            <div class="col-6 tex-center">
                <img src="/images/resources/png/cerrado.png" />
                <h1 class="text-center">¡Este camino esta cerrado!</h1>
                <p class="text-center">Esta sección debe visualizarse en un equipo de escritorio o equipo portatil.</p>
                <br>
                <a href="/logout"><span>{{ trans('messages.logout') }}</span></a>
            </div>
        </div>
    </div>
@endsection