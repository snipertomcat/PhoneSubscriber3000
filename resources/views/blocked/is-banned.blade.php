@extends('layouts.simple')

@section('title', trans('messages.banned'))

@section('body')

    @component('layouts._one-third')
        <div class="box">
            <b-message type="is-warning" has-icon>
                {{ trans('messages.user_is_banned_message') }}
            </b-message>
            <div class="text-center">
                <a href="/logout">Cerrar sesión</a>
            </div>
        </div>
    @endcomponent

@endsection
