@extends('layouts.platform')

@section('title', $experience->title)

@include('student.experiences.seo', ['experience' => $experience])

@section('body')
    @if(Auth::user())
        <apithy-experience-preview :experience="{{$experience}}" :auth_user="{{$user}}"></apithy-experience-preview>
    @else
        <apithy-experience-public-preview :experience="{{$experience}}"></apithy-experience-public-preview>
    @endif
@endsection
