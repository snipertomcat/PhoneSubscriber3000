@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', 'Taxonomy')

@section('body')
    <apithy-admin-taxonomy type="{{$type}}"></apithy-admin-taxonomy>
@endsection