@extends('layouts.platform')

@section('title', 'Mis Experiencias')
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">Mis Experiencias</div>
    </div>
@endSection

@section('body')
<apithy-experience-list url="{{ route('experience.enrolled') }}"
                        :user="{{ $user }}"
                        :abilities_data="{{ $abilities }}"
                        :types_data="{{ json_encode($types) }}"
                        from="enrolled">
</apithy-experience-list>
@endsection