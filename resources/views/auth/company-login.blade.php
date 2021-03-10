@extends('layouts.full_page',['no_navbar' => true, 'no_footer' => true])

@section('body')
    <apithy-company-user-login
            :company="{{json_encode($company)}}"
            :form-errors="{{json_encode($errors)}}"
            :old-access="{{json_encode(old('access'))}}"
            :has-access-error="{{ json_encode($errors->has('access')) }}"
            :has-password-error="{{ json_encode($errors->has('password')) }}"
            :show_social_connect="{{json_encode($show_social_connect)}}"
            :show_register="{{json_encode($show_register)}}"
    >
        <template slot="partials-students-flash">
            @include('partials.students-flash')
        </template>
        <template slot="messages">
            @if (session('confirmation'))
                <article class="message is-success">
                    <div class="message-body">
                        <p>  {!! session('confirmation') !!} </p>
                    </div>
                </article>
            @endif

            @if (session('confirmation_sms'))
                <article class="message is-success">
                    <div class="message-body">
                        <p>  {!! session('confirmation_sms') !!} </p>
                    </div>
                </article>
            @endif
            <!--
            @if (session('password_updated'))
                <article class="message is-success">
                    <div class="message-body">
                        <p>  {!! session('password_updated') !!} </p>
                    </div>
                </article>
            @endif
            -->
        </template>
        <template slot="token"> {{csrf_field()}} </template>
    </apithy-company-user-login>
@endsection

@push('styles')
    <style type="text/css">
        p.help.is-danger {
            margin-left: 10px!important;
        }
    </style>
@endpush
