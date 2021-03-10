@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.register'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Usuarios</a>
        <i class="right chevron icon divider"></i>
        <div class="active section">Nuevo</div>
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
    <div class="row">
        <div class="col-12">
            <div class="">
                @if(Auth::user()->isAdmin())
                    <div class="container">
                        <apithy-users-create-import
                                :create-data="{{
                            json_encode([
                                'is_super' => $is_super,
                                'companies' => $companies,
                                'current_company' => $current_company,
                                'valid_domains' => $valid_domains,
                                'roles' => $roles,
                                'taxonomy_areas' => $taxonomy_areas,
                                'taxonomy_abilities' => $taxonomy_abilities,
                                'taxonomy_teams' => $taxonomy_teams,
                                'taxonomy_positions' => $taxonomy_positions,
                                'taxonomy_certifications' => $taxonomy_certifications,
                                'taxonomy_customs' => $taxonomy_customs
                            ])
                            }}"
                                :import-data="{{
                            json_encode([
                                'company_id' => $current_company,
                                'companies' => $companies
                            ])
                            }}">
                        </apithy-users-create-import>
                    </div>
                @else
                <apithy-users-form
                        :is_super="{{json_encode($is_super)}}"
                        :companies="{{json_encode($companies)}}"
                        :current_company="{{$current_company}}"
                        :valid_domains="{{json_encode($valid_domains)}}"
                        :roles="{{json_encode($roles)}}"
                        :taxonomy_areas="{{json_encode($taxonomy_areas)}}"
                        :taxonomy_abilities="{{json_encode($taxonomy_abilities)}}"
                        :taxonomy_teams="{{json_encode($taxonomy_teams)}}"
                        :taxonomy_positions="{{json_encode($taxonomy_positions)}}"
                        :taxonomy_certifications="{{json_encode($taxonomy_certifications)}}"
                        :taxonomy_customs="{{json_encode($taxonomy_customs)}}">
                </apithy-users-form>
                @endif
            </div>
        </div>
    </div>
@endsection