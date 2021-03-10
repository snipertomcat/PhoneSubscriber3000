@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.tags'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">{{ trans('messages.tags') }}</div>
    </div>
@endSection

@section('body')
    <div class="comtainer">
        <apithy-tag-list :tag-list="{{json_encode($tags)}}"></apithy-tag-list>
    </div>
@endsection