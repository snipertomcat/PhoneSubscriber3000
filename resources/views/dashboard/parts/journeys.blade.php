@if(Auth::user()->isSuper())
    <apithy-root-journeys-dashboard :journeys="{{json_encode($view['activity'])}}"></apithy-root-journeys-dashboard>
@elseif(Auth::user()->isAdmin())
    <apithy-admin-journeys-dashboard :journeys="{{json_encode($view['activity'])}}"></apithy-admin-journeys-dashboard>
@endif