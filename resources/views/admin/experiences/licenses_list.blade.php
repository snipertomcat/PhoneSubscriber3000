@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title','Licences')
@push('styles')
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/2.0.46/css/materialdesignicons.min.css">
@endpush
@section('body')
<div class="container">
    <apithy-licenses-list></apithy-licenses-list>
</div>
@endsection