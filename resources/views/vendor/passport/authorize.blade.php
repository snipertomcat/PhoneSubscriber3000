
@extends('layouts.authorize')

@section('title', 'autorizar')

@section('body')

<div class="columns is-centered">
    <div class="column is-half is-narrow">
        <nav class="panel">
            <p class="panel-heading is-primary">
                {{ trans('messages.title_authorization') }}
            </p>
            <div class="panel-block">
                <a><strong>{{ $client->name }}</strong> {{ trans('messages.legend') }}</a>

                <div class="columns">
                    <div class="column">
                        <form method="post" action="/oauth/authorize">
                            {{ csrf_field() }}

                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <button type="submit" class="button is-success">{{ trans('messages.btn_auth') }}</button>
                        </form>
                    </div>
                    <div class="column">
                        <form method="post" action="/oauth/authorize">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <input type="hidden" name="state" value="{{ $request->state }}">
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <button class="button is-danger">{{ trans('messages.cancel') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    <div>
</div>
@endsection