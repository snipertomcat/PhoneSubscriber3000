@extends('layouts.platform')

@section('title', trans('messages.courses'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">Experiencias</div>
    </div>
@endSection

@section('body')
    @if(Auth::user()->isAdmin())
        <apithy-owner-tabs-menu :items-data="[
            {icon: 'icon-hand-air-balloon', label: '{{Auth::user()->company()->first()->name}}', amount: 0, route_name:'experiences.show', link: route('experiences.company.only')},
            {icon: 'icon-hand-air-balloon', label: 'Mis experiencias', amount: 0, route_name:'experience.student', link: route('experience.student')},
            {icon: 'icon-list-clipboard', label: 'Apithy store', amount: 0, route_name:'experiences.index', link: route('experiences.index')},
        {{--{icon: 'icon-market-cart', label: 'Compradas', amount: 0, route_name:'experience.buyed', link: route('experience.buyed', { use_for: 'personal_use' })},--}}
    ]"></apithy-owner-tabs-menu>
    @endif
    <apithy-marketplace :experiences="{{$experiences->toJson()}}" :user-data="{{json_encode($user)}}"></apithy-marketplace>
@endsection
