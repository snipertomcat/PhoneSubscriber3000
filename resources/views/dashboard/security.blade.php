@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.security'))

@section('body')
    @can('changePassword', $auth->user())
        <div data-tab="profile">
            <div class="profile-menu-wrapper">
                <div class="tabs is-boxed user-tabs container">
                <ul>
                    <li>
                        <a href="{{ url('/profile') }}" data-tab="profile">
                            <span class="icon is-small"><i class="fas fa-address-card" aria-hidden="true"></i></span>
                            <span>Mi progreso</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profile/edit') }}" class="item" data-tab="biography">
                            <span class="icon is-small"><i class="fas fa-user" aria-hidden="true"></i></span>
                            <span>Mi perfil</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profile/payment-information') }}" class="item" data-tab="payment_info">
                            <span class="icon is-small"><i class="fas fa-credit-card" aria-hidden="true"></i></span>
                            <span>Informaci&oacute;n de pago</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profile/transactions') }}" class="item" data-tab="payment_info">
                            <span class="icon is-small"><i class="fas fa-money" aria-hidden="true"></i></span>
                            <span>Transacciones</span>
                        </a>
                    </li>
                    <li class="is-active">
                        <a href="{{ url('/profile/security') }}" class="item" data-tab="security">
                            <span class="icon is-small"><i class="fas fa-lock" aria-hidden="true"></i></span>
                            <span>Seguridad</span>
                        </a>
                    </li>
                </ul>
            </div>
            </div>
            <div class="container profile-content">
                @include('partials.students-flash')
                <apithy-security inline-template v-cloak
                                 :disable="{{ old('disable', 'true') == 'false' ? 'false' : 'false' }}">
                    <form class="col-md-4 block-centered" method="POST" action="{{ url('/profile/security') }}">
                        <h3>Cambiar Contrase&ntilde;a</h3>
                        <br>
                        {{ csrf_field() }}
                        <input type="hidden" name="disable" :value="toggle">

                        @if($auth->user()->registration_method != \Apithy\User::REGISTRATION_ADMIN &&
                        $auth->user()->registration_method != \Apithy\User::REGISTRATION_IMPORT_FILE)
                            <div class="field" @if($errors->has('password_current')) error @endif>
                                <b-input placeholder="{{ trans('messages.password_current') }}"
                                         type="password" 
                                         icon-pack="fas"
                                         name="password_current"
                                         expanded></b-input>
                            </div>
                        @endif

                        <div class="field" @if($errors->has('new_password')) error @endif>
                            <div class="control">
                                <b-input placeholder="{{ trans('messages.new_password') }}" type="password"
                                         icon="lock" icon-pack="fas" name="new_password"
                                         @if($auth->user()->registration_method != \Apithy\User::REGISTRATION_ADMIN):disabled="toggle"@endif></b-input>
                            </div>
                        </div>

                        <div class="field" @if($errors->has('new_password_confirmation')) error @endif>
                            <b-input placeholder="{{ trans('messages.new_password_confirmation') }}"
                                     type="password" icon="lock" icon-pack="fas" name="new_password_confirmation"
                                     @if($auth->user()->registration_method != \Apithy\User::REGISTRATION_ADMIN):disabled="toggle"@endif></b-input>
                        </div>

                        <div class="field">
                            <div class="control">
                                <button class="button is-primary">
                                     <span class="icon is-medium">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                    <span>
                                    {{ trans('messages.save') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </apithy-security>
            </div>
        </div>
    @endcan
@endsection