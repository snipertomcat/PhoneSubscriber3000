@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.users'))
@section('section-title')

    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Usuarios</a>
    </div>

 @endSection

@section('body')
    @if($auth_user->isSuper())
    <div>
        <div class="row">
            <apithy-users-filter url="/users"
                                 ref="users_filter"
                                 :roles="{{$roles}}"
                                 :companies="{{$companies}}"
                                 :auth_user="{{$auth_user}}"
                                 :statuses="{{$statuses}}"
                                 :filter-by-company="true">
            </apithy-users-filter>
        </div>
        <br>
        <div class="row">
            <apithy-users-list ref="users_list" :auth-user="{{$auth_user}}">
            </apithy-users-list>
        </div>
    </div>
    @else
    <apithy-owner-tabs-menu :items-data="[
        {label:'Gestionar', route_name:'users.index'},
        {label:'Crear o importar', route_name:'users.create'},
        {label:'Invitar', route_name:'invitations.index'},
        {label:'Usuarios Evento', route_name:'demo.invitations', env: 'demo'}
    ]">
    </apithy-owner-tabs-menu>
    <div class="container">
        <apithy-users-owner :auth-user="{{$auth_user}}"
                            :roles="{{$roles}}"
                            :taxonomy="{{json_encode($taxonomy)}}"
                            :statuses="{{$statuses}}">
        </apithy-users-owner>
    </div>
    @endif
@endsection