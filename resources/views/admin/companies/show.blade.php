@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Empresas</a>
        <i class="right chevron icon divider"></i>
        <div class="active section">{{ $company->name }}</div>
    </div>
@endSection

@section('body')
    @if(Auth::user()->isSuper())
        <apithy-company-menu :company-data="{{$company}}"></apithy-company-menu>
    @endif
    @if(Auth::user()->isAdmin())
        <apithy-owner-tabs-menu :items-data="[]"></apithy-owner-tabs-menu>
    @endif
    <apithy-companies-edit :auth-user="{{$auth_user}}"
                           :countries="{{$countries}}"
                           :company-data="{{$company}}">
    </apithy-companies-edit>
@endsection