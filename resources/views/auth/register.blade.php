@extends('layouts.full_page')

@section('title', trans('messages.register'))

@section('body')
    <div class="container">
        @include('partials.logo')
        <hr>
        <div class="row justify-content-center">
            <div class="col-8">
                <apithy-register
                        v-cloak
                        mode="{{ old('register_type') ?? request('register_type', 'on_site')}}"
                        code="{{ old('invitation_code') ?? request('invitation_code', '') }}"
                        email="{{ old('email') ?? request('email', '') }}">
                </apithy-register>
            </div>
        </div>
    </div>
@endsection