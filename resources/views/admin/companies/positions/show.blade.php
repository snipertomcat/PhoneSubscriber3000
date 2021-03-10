@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Empresas</a>
        <i class="right chevron icon divider"></i>
        <div class="section">Areas</div>
        <i class="right chevron icon divider"></i>
        <div class="section">{{$company_position->area->name}}</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">{{$company_position->name}}</div>
    </div>
@endSection
@section('body')
    @if(Auth::user()->isSuper()) )<apithy-company-menu :company-data="{{$company}}"></apithy-company-menu>@endif
    <br><br>
    <apithy-positions-view :auth-user="{{$auth_user}}"
                       :company="{{$company}}"
                       :position-data="{{$company_position}}"
                       :positions="{{json_encode($positions)}}">
    </apithy-positions-view>

    {{--
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-borders no-shadows">
            @include('partials.flash')
            <div class="ui segment">
                @can('update', [\Apithy\CompanyPosition::class,$company_position->area])
                    <a class="ui button primary icon"
                       href="{{ route("positions.edit", [$company_position->area->company,$company_position->area,$company_position]) }}">
                        {{ trans('messages.edit') }}
                        <i class="edit icon"></i>
                    </a>
               @endcan

                @can('delete', [\Apithy\CompanyPosition::class, $company_position->area])
                    @include(
                        'partials.form.delete-button',
                        ['action' => route("positions.destroy", [$company_position->area->company,$company_position->area,$company_position]),
                        'id'=>$company_position->id])
                @endcan
            </div>
            <div class="ui segment">
                <sui-table class="definition">
                    <sui-table-body>
                        <sui-table-row>
                            <sui-table-cell class="two wide colum"><strong>{{ trans('messages.name') }}</strong>
                            </sui-table-cell>
                            <sui-table-cell>{{ $company_position->name }}</sui-table-cell>
                        </sui-table-row>
                        <sui-table-row>
                            <sui-table-cell><strong>{{ trans('messages.short_name') }}</strong></sui-table-cell>
                            <sui-table-cell>{{ $company_position->short_name }}</sui-table-cell>
                        </sui-table-row>
                        <sui-table-row>
                            <sui-table-cell><strong>{{ trans('messages.description') }}</strong></sui-table-cell>
                            <sui-table-cell>{{ $company_position->description }}</sui-table-cell>
                        </sui-table-row>
                    </sui-table-body>
                </sui-table>

                <div class="ui segment">
                    <h5 class="ui header">
                        <i class="user icon"></i>{{ trans('messages.users') }}
                    </h5>
                    <sui-table>
                        <sui-table-header>
                            <sui-table-row>
                                <sui-table-header-cell>{{ trans('messages.name') }}</sui-table-header-cell>
                                <sui-table-header-cell>{{ trans('messages.email') }}</sui-table-header-cell>
                                <sui-table-header-cell>{{ trans('messages.company') }}</sui-table-header-cell>
                                <sui-table-header-cell>{{ trans('messages.role') }}</sui-table-header-cell>
                                <sui-table-header-cell>{{ trans('messages.created_at') }}</sui-table-header-cell>
                                <sui-table-header-cell>{{ trans('messages.status') }}</sui-table-header-cell>
                                <sui-table-header-cell></sui-table-header-cell>
                            </sui-table-row>
                        </sui-table-header>
                        <sui-table-body>
                            @foreach($position_users as $key => $user)
                                <sui-table-row>
                                    <sui-table-cell>{{ $user->full_name}}</sui-table-cell>
                                    <sui-table-cell>{{ $user->email }}</sui-table-cell>
                                    <sui-table-cell>{{ $user->hasCompany() ? $user->company[0]->name : 'Indefinido' }}</sui-table-cell>
                                    <sui-table-cell>
                                    <span class="ui label grey">
                                        {{ $user->hasRoles() ? $user->roles[0]->name : 'Indefinido' }}
                                    </span>
                                    </sui-table-cell>
                                    <sui-table-cell>{{ $user->created_at }}</sui-table-cell>
                                    <sui-table-cell class="is-narrow">
                                    <span class="ui label {{ $user->activated ? 'green' : 'red' }}">
                                        {{$user->activated ? 'Activo' : 'Inactivo'}}
                                    </span>
                                    </sui-table-cell>
                                    <sui-table-cell class="is-narrow">
                                        @can('view', [\Apithy\User::class, $user])
                                            <a class="ui black"
                                               href="{{ route("users.show",[$user]) }}">
                                                <div class="ui button primary">
                                                    <i class="eye icon"></i>
                                                    {{ trans('messages.show') }}
                                                </div>
                                            </a>
                                        @endcan
                                    </sui-table-cell>
                                </sui-table-row>
                            @endforeach
                        </sui-table-body>
                    </sui-table>
                </div>
            </div>
        </div>
    </div>
    --}}
@endsection