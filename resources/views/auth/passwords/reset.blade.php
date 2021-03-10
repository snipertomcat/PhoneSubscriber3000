@extends('layouts.full_page')
@section('title', trans('messages.reset'))

@section('body')
    <reset-password token="{{$token}}">
        <template name="token">
            {{ csrf_field() }}
        </template>
    </reset-password>
@endsection
