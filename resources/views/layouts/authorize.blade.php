@extends('layouts.master')

@section('content')

    <section class="hero is-white  is-fullheight">
        <div class="hero-header">
            <div class="container">
                @include('layouts._nav')
            </div>
        </div>
        <div class="hero-body">
            <div class="container">
                @yield('body')
            </div>
        </div>
    </section>

@endsection
