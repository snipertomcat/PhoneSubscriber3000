@extends('layouts.platform',['no_footer' => true])

@section('title', 'Getting started')

@section('body')
    @if(\Auth::user()->isAdmin())
    <apithy-getting-started-cards></apithy-getting-started-cards>
    @else
    <apithy-getting-started></apithy-getting-started>
    @endif
@endsection