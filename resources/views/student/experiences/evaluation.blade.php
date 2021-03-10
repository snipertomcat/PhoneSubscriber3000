@extends('layouts.platform')

@section('body')
    <apithy-experience-rating :experience="{{json_encode($experience)}}" :auth_user="{{json_encode($user)}}" origin="{{request('from')}}"></apithy-experience-rating>
@endsection
