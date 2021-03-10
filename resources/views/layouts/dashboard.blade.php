@extends('layouts.master')

@section('content')
    <div class="header-wrapper">
        @include('layouts._admin_nav')
    </div>
    <!--navbar-->
    <!--maincontent-->
    <div class="page-content">
        @yield('body')
    </div>
@endsection
