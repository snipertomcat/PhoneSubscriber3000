@extends('layouts.platform')

@section('title', 'Mis Experiencias')
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">Mis Experiencias</div>
    </div>
@endSection

@section('body')
    <apithy-partner-experiences></apithy-partner-experiences>
@endsection