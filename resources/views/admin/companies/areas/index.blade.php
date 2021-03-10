@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Empresas</a>
        <i class="right chevron icon divider"></i>
        <div class="section">{{ $company->name }}</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">Areas</div>
    </div>
@endSection


@section('body')
    @if(Auth::user()->isSuper()) )<apithy-company-menu :company-data="{{$company}}"></apithy-company-menu>@endif
    <apithy-areas-list :auth-user="{{$auth_user}}" :company-data="{{$company}}"></apithy-areas-list>
@endsection