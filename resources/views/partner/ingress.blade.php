@extends('layouts.platform')

@section('title', 'Mis Ingresos')
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">Mis Ingresos</div>
    </div>
@endSection

@section('body')
    <apithy-partner-ingress></apithy-partner-ingress>
@endsection