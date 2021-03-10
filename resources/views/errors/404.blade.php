@extends('layouts.full_page')

@section('title','Página no encontrada')
@section('body')
    <div class="container h-100 mt-5">
        <div class="row align-items-center h-100">
            <div class="col-6 mx-auto">
                <h1>404 - P&aacute;gina no encontrada</h1>
                <span>Parece que tu gato escribi&oacute; una URL incorrecta</span>
                <br>
                <br>
                <a href="/experiencias">EXPLORAR APITHY</a>
            </div>
            <div class="col-6 mx-auto">
                <img src="/images/resources/png/404.png"/>
            </div>
        </div>
    </div>
@endsection
