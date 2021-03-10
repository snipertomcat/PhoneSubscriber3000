@extends('layouts.simple')

@section('title', trans('messages.welcome'))

@section('body')

    @component('layouts._one-third')
        <div class="box bg-transparency">
            @include('partials.logo')
            <div class="columns">
                <div class="column">
                    <a class="button is-primary is-fullwidth" href="{{ route($auth->user() ? 'dashboard' : 'login') }}">
                        <span class="icon">
                            <i class="fa fa-sign-in"></i>
                        </span>
                        <span>{{ trans('messages.access') }}</span>
                    </a>
                </div>
                <div class="column">
                    <a class="button is-primary is-fullwidth" href="{{ route('register') }}">
                        <span class="icon">
                            <i class="fa fa-pencil"></i>
                        </span>
                        <span>{{ trans('messages.register') }}</span>
                    </a>
                </div>
            </div>
        </div>
    @endcomponent

@endsection
