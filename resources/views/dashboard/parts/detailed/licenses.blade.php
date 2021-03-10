@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', 'Dashboard')

@section('body')
    <div class="container">
    @if(Auth::user()->isSuper() || Auth::user()->isAdmin())
            <apithy-menu-dashboard></apithy-menu-dashboard>
            <apithy-admin-detail-licenses :licenses="{{json_encode($view['activity'])}}"></apithy-admin-detail-licenses>
        @else
        <!--apithy-student-dashboard></apithy-student-dashboard-->
        @endif
    </div>
@endsection

