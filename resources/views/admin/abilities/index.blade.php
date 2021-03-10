@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.abilities'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">{{ trans('messages.abilities') }}</div>
    </div>
@endSection

@section('body')
    <apithy-ability-list :ability-list="{{json_encode($abilities)}}"></apithy-ability-list>
@endsection