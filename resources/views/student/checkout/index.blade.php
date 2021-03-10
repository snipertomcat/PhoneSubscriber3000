@extends('layouts.platform')

@section('title', trans('messages.checkout'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">Mis Experiencias</div>
    </div>
@endSection

@section('body')
    @if(count($cart->items))
    <apithy-cart-checkout :cart="{{ json_encode($cart) }}" :auth_user="{{ $user }}"></apithy-cart-checkout>
    @else
        <div class="container h-100 mt-5">
            <div class="row mr-0 ml-0 no-gutters align-items-center justify-content-center" style="height: 80% !important;">
                <div class="col-4">
                    <img src="/images/resources/png/cart.png" />
                    <h1 class="text-center">¡Tu carrito est&aacute; vac&iacute;o!</h1>
                    <p class="text-center">Visita el <a href="/experiences">Market Place</a> y encontrar&aacute;s experiencias<br> que te ayudar&aacute;n a crecer</p>
                </div>
            </div>
        </div>
    @endif
@endsection