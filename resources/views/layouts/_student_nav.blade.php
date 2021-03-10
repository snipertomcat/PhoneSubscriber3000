@if(\Auth::user() && \Auth::user()->isPartner())
    <apithy-partner-nav :user="{{\Auth::user()}}" ref="partnerNav"></apithy-partner-nav>
@elseif(\Auth::user())
    @if(\Auth::user()->isAdmin)
        <apithy-owner-menu :user="{{\Auth::user()->load('company')}}" ref="adminNav"></apithy-owner-menu>
    @else
        <apithy-plattform-menu :user="{{\Auth::user()->load('company')}}" ref="adminNav"></apithy-plattform-menu>
    @endif
@else
    <apithy-platform-nav :user="{}" ref="adminNav"></apithy-platform-nav>
@endif
@if(\Auth::user())
    <apithy-cart-notification ref="cart_notify"></apithy-cart-notification>
@endif