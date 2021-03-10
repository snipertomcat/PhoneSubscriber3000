@extends('layouts.platform')

@section('title', trans('messages.experiences'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('experiences.index')}}" class="section">Experiencias</a>
        <i class="right chevron icon divider"></i>
        <div class="active section">{{$experience->title}}</div>
    </div>
@endSection

@section('body')
    <apithy-experience-view :experience="{{$experience}}"
                            :auth_user="{{$user}}">
    </apithy-experience-view>
@endsection