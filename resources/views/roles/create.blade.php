@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.roles'))

@section('body')

@endsection