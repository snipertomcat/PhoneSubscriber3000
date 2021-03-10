@extends('layouts.master')

@section('content')

    <section class="hero is-primary is-bold is-fullheight">
        <div class="hero-header">
            <div class="container">

            </div>
        </div>
        <div class="hero-body">
            <div class="container">
                @yield('body')
            </div>
        </div>
    </section>

@endsection
