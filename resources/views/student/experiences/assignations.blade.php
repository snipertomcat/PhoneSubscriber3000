@extends('layouts.platform')

@section('title', 'Mis Asignaciones')
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">Mis Asignaciones</div>
    </div>
@endSection

@section('body')
    <apithy-experience-list url="{{ route('experience.assignations') }}"
                            :user="{{ $user }}"
                            :abilities_data="{{ $abilities }}"
                            :authors_data="[]"
                            :types_data="{{ json_encode($types) }}"
                            :experiences_data="{{$experiences->toJson()}}"
                            :init_load="false"
                            title_carrousel="Tus experiencias asignadas"
                            from="experiences">
    </apithy-experience-list>
@endsection