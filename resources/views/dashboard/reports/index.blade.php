@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', 'Dashboard')

@section('body')
    <apithy-menu-dashboard></apithy-menu-dashboard>

    <apithy-reports-dashboard :tags="{{json_encode($tags)}}"
                              :user="{{json_encode(Auth::user())}}"
    ></apithy-reports-dashboard>
@endsection
