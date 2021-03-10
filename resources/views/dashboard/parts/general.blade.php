@if(Auth::user()->isSuper())
    <apithy-admin-general-dashboard></apithy-admin-general-dashboard>
@elseif(Auth::user()->isAdmin())
    <apithy-admin-general-dashboard></apithy-admin-general-dashboard>
@endif