@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', 'Información de Pago')

@section('body')

    <div class="container" data-tab="profile">
        <div class="tabs is-boxed user-tabs">
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
                <li class="is-active">
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
        <div class="">
            <apithy-user-payment-information-form :user="{{ $user }}"></apithy-user-payment-information-form>
        </div>
    </div>
@endsection