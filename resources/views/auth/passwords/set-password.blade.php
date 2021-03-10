@extends('layouts.full_page')
@section('title', "Establecer contraseña")

@push('styles')
    <link href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css" rel="stylesheet" async/>
@endpush

@section('body')
    <apithy-set-password :db-token="'{{$token}}'"></apithy-set-password>
@endsection

@push('scripts')
    <script async src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>
@endpush
