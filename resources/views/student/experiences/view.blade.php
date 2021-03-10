@extends('layouts.master_platform', $options)

@section('title', trans('messages.experiences'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('experiences.index')}}" class="section">Experiencias</a>
        <i class="right chevron icon divider"></i>
        <div class="active section">{{$experience->title}}</div>
    </div>
@endSection

@section('body')
    @guest()
        {{-- <apithy-public-experience-player :experience="{{$experience}}" :current_session="{{$session}}"></apithy-public-experience-player> --}}
        @if($experience->json_data == null)
            <apithy-public-experience-player :experience="{{$experience}}" :current_session="{{$session}}"></apithy-public-experience-player>
        @else
            <apithy-experience-container :experience-data="{{$experience}}" :current-session="{{$session}}" :auth-user="{{json_encode([])}}"></apithy-experience-container>
        @endif
    @else
        @if($experience->json_data == null)
        <apithy-experience-player :experience="{{$experience}}" :current_session="{{$session}}" :auth_user="{{$user}}"></apithy-experience-player>
        @else
        <apithy-experience-container :experience-data="{{$experience}}" :current-session="{{$session}}" :auth-user="{{$user}}"></apithy-experience-container>
        @endif
    @endguest
@endsection

@push('styles')
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/contents.apithy.com/Jumex/Demo_S2.3/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css"/>
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/css/magnific-popup.css"/>
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/css/owl.carousel.css"/>
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/css/mdb.min.css"/>
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/contents.apithy.com/Demo_R_7_prueba/jq-modal.css"/>
    <style>
        figure { margin: unset }
    </style>

    @if(isset($session->json_data['css']))
    @foreach($session->json_data['css'] as $css)
        <link rel="stylesheet" href="{{$css}}"/>
    @endforeach
    @endif
@endPush()

@push('head-script')
    <script type="text/javascript" defer src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/js/owl.carousel.js"></script>
    <script type="text/javascript" defer src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/js/jquery.magnific-popup.min.js"></script>
    <script type="text/javascript" defer src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/js/mdb.min.js"></script>
    <script type="text/javascript" defer src="https://s3-us-west-2.amazonaws.com/contents.apithy.com/Demo_R_7_prueba/jq-modal.js"></script>
    <script type="text/javascript" defer src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
@endPush()
