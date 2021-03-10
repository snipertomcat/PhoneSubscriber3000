@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Empresas</a>
        <i class="right chevron icon divider"></i>
        <div class="section">{{$company->name}}</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">Editar</div>
    </div>
@endSection

@section('body')
    <apithy-company-menu :company-data="{{$company}}"></apithy-company-menu>
    <apithy-companies-edit :auth-user="{{$auth_user}}"
                           :countries="{{$countries}}"
                           :company-data="{{$company}}"
                           :start-editing="true">
    </apithy-companies-edit>
@endsection
