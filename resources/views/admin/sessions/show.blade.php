@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))

@section('body')
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments">
            <div class="ui segment">
                <h5 class="ui header">
                    <i class="building icon"></i>{{ $company->short_name }} - {{ $company->name }}
                </h5>
                @include('partials.flash')
            </div>
            <div class="ui segment">
                @can('update', $company)
                    <a class="ui button primary icon"
                       href="{{ url(sprintf('/companies/%s/edit', $company->id)) }}">
                        {{ trans('messages.edit') }}
                        <i class="edit icon"></i>
                    </a>
                @endcan

                @can('delete', $company)
                    @include('partials.form.delete-button', ['action' => url('/companies', [$company->id]),'id'=>$company->id])
                @endcan

                @can('create', \Apithy\User::class)
                    <a href="{{url('/users/create')}}" class="ui small secondary labeled icon button">
                        <i class="user icon"></i> Agregar Usuario
                    </a>
                    <a href="{{url('/users/import?company_id='.$company->id)}}"
                       class="ui small green labeled icon button">
                        <i class="file excel icon"></i> Importar Usuarios
                    </a>
                @endCan

                @can('create', \Apithy\Invitation::class)
                    <a href="{{ url('/users/invitations')  }}" class="ui small grey labeled icon button">
                        <i class="address card icon"></i> Invitar Usuario
                    </a>
                @endCan

                <a href="{{ url('/companies/'.$company->id.'/areas')  }}"
                   class="ui small yellowli labeled icon button">
                    <i class="sitemap icon"></i> Areas
                </a>

                <a href="{{ url('/settings/companies/'.$company->id.'/invitations')  }}"
                   class="ui small yellow labeled icon button">
                    <i class="cogs icon"></i> Configuración
                </a>

            </div>
            <div class="ui segment">
                <sui-table class="definition">
                    <sui-table-body>
                        <sui-table-row>
                            <sui-table-cell class="two wide colum"><strong>{{ trans('messages.account_name') }}</strong>
                            </sui-table-cell>
                            <sui-table-cell>{{ $company->account_name }}</sui-table-cell>
                        </sui-table-row>
                        <sui-table-row>
                            <sui-table-cell><strong>{{ trans('messages.name') }}</strong></sui-table-cell>
                            <sui-table-cell>{{ $company->name }}</sui-table-cell>
                        </sui-table-row>
                        <sui-table-row>
                            <sui-table-cell><strong>{{ trans('messages.short_name') }}</strong></sui-table-cell>
                            <sui-table-cell>{{ $company->short_name }}</sui-table-cell>
                        </sui-table-row>
                        <sui-table-row>
                            <sui-table-cell><strong>{{ trans('messages.legal_name') }}</strong></sui-table-cell>
                            <sui-table-cell>{{ $company->legal_name }}</sui-table-cell>
                        </sui-table-row>
                        <sui-table-row>
                            <sui-table-cell><strong>{{ trans('messages.site') }}</strong></sui-table-cell>
                            <sui-table-cell>
                                <a href="{{ $company->site_url }}" target="_blank">{{ $company->site_url}}</a>
                            </sui-table-cell>
                        </sui-table-row>
                        <sui-table-row>
                            <sui-table-cell class="two wide colum"><strong>{{ trans('messages.country') }}</strong>
                            </sui-table-cell>
                            <sui-table-cell>{{ $company->country->name }}</sui-table-cell>
                        </sui-table-row>
                    </sui-table-body>
                </sui-table>
            </div>
            @if (!$company->users->isEmpty())
                @include('partials.list.user', ['users' => $company->users()->paginate(10), 'showRoles' => true])
            @endif

        </div>
    </div>
@endsection