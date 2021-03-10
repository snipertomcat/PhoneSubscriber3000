@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.profile'))

@section('body')
    <div data-tab="profile">
        <div class="profile-menu-wrapper">
            <div class="tabs is-boxed user-tabs container">
                <ul>
                    <li class="is-active">
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
                    <li>
                        <a href="{{ url('/profile/security') }}" class="item" data-tab="security">
                            <span class="icon is-small"><i class="fas fa-lock" aria-hidden="true"></i></span>
                            <span>Seguridad</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="ui bottom attached segment">
            @include('dashboard.profile_header')
            @if(!(Auth::user()->isSuper()))
                <apithy-student-analytics :data-analytics="{{ json_encode($data_analytics) }}"></apithy-student-analytics>
            @endif
        </div>
    </div>
@endsection

@section('body_backup')
    @include('dashboard.profile_header')
    @include('dashboard.profile_detail')
    @if(!(Auth::user()->isSuper() || Auth::user()->isAdmin()))
        <apithy-student-analytics :data-analytics="{{ json_encode($data_analytics) }}"></apithy-student-analytics>
    @endif
@endsection