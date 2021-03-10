@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">Empresas</div>
    </div>
@endSection

@section('body')
    <apithy-company-menu :company-data="{{$company}}"></apithy-company-menu>
    <apithy-users-filter url="/users"
                         ref="users_filter"
                         :roles="{{$roles}}"
                         :auth_user="{{$auth_user}}">
    </apithy-users-filter>
    <br>
    <apithy-users-list ref="users_list" :auth-user="{{$auth_user}}" :shows-id="true"></apithy-users-list>
@endsection