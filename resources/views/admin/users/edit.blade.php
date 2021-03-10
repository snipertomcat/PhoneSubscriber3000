@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.users'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Usuarios</a>
        <i class="right chevron icon divider"></i>
        <div class="active section">{{$user->email}}</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">Editar</div>
    </div>
@endSection
@section('body')
    <apithy-owner-tabs-menu :items-data="[
        {label:'Gestionar', route_name:'users.index'},
        {label:'Crear o importar', route_name:'users.create'},
        {label:'Invitar', route_name:'invitations.index'},
        {label:'Usuarios Evento', route_name:'demo.invitations', env: 'demo'}
    ]">
    </apithy-owner-tabs-menu>
    <hr width="2">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="">
                <apithy-users-edit
                        :is_super="{{json_encode($is_super)}}"
                        :companies="{{json_encode($companies)}}"
                        :user="{{json_encode($user)}}"
                        :roles="{{json_encode($roles)}}"
                        :taxonomy_areas="{{json_encode($taxonomy_areas)}}"
                        :taxonomy_abilities="{{json_encode($taxonomy_abilities)}}"
                        :taxonomy_teams="{{json_encode($taxonomy_teams)}}"
                        :taxonomy_positions="{{json_encode($taxonomy_positions)}}"
                        :taxonomy_certifications="{{json_encode($taxonomy_certifications)}}"
                        :taxonomy_customs="{{json_encode($taxonomy_customs)}}">
                </apithy-users-edit>
            </div>
        </div>
    </div>
@endsection