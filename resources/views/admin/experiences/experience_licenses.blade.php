@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title','Licences')
@push('styles')
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/2.0.46/css/materialdesignicons.min.css">
@endpush
@section('body')
    <apithy-experience-licenses :id="{{request('experience')}}" :user_id="{{auth()->user()->id}}"></apithy-experience-licenses>
@endsection