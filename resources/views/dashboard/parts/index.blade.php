@if(Auth::user()->isSuper())
    <apithy-root-summary-dashboard :summary="{{json_encode($view['activity'])}}"></apithy-root-summary-dashboard>
@elseif(Auth::user()->isAdmin())
    <apithy-admin-summary-dashboard :summary="{{json_encode($view['activity'])}}"></apithy-admin-summary-dashboard>
@endif