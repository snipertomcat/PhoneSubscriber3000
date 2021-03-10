@if(Auth::user()->isSuper())
    <apithy-root-experiences-dashboard :experiences="{{json_encode($view['activity'])}}"></apithy-root-experiences-dashboard>
@elseif(Auth::user()->isAdmin())
    <apithy-admin-experiences-dashboard></apithy-admin-experiences-dashboard>
@endif