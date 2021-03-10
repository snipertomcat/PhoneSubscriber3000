@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.invitations'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">Invitaciones</div>
    </div>
@endSection

@section('body')
    @if(Auth::user()->isAdmin())
    <apithy-owner-tabs-menu :items-data="[
    {label:'Gestionar', route_name:'users.index'},
    {label:'Crear o importar', route_name:'users.create'},
    {label:'Invitar', route_name:'invitations.index'},
    {label:'Usuarios Evento', route_name:'demo.invitations', env: 'demo'}
    ]"></apithy-owner-tabs-menu>
    @endif
    <div class="container">
        <apithy-users-invitations :roles="{{ $roles }}"
                                  :companies="{{ $companies }}"
                                  :auth-user="{{ $auth_user }}"
                                  v-cloak>
        </apithy-users-invitations>
    </div>

@endsection