<sui-sidebar class="ui sidebar vertical left menu overlay  borderless visible sidemenu inverted  blue"
             style="transition-duration: 0.1s; overflow: hidden; outline: none; cursor: -webkit-grab;"
             data-color="grey">
    <a class="item logo" href="/">
        <img src="{{ url('images/apithy.png') }}" alt="{{ $applicationName }}" width="50">
        <img src="{{ url('images/apithy_small.png') }}" alt="{{ $applicationName }}" class="displaynone" width="50">
    </a>
    <sui-accordion inverted >
        <br/>
        <a href="/dashboard" class="title item">
            <i class="tachometer alternate titleIcon icon"></i>
            {{ trans('messages.dashboard') }}
        </a>

        @can('showResourceMenu', \Apithy\User::class)
            <a class="title item">
                <i class="users titleIcon icon"></i>{{ trans('messages.users') }}
                <i class="dropdown icon"></i>
            </a>
            <div class="content">
                <a class="item transition" href="{{ url('/users') }}">
                    Listado
                </a>
                <a class="item transition" href="{{ url('/users/create') }}">
                    Nuevo
                </a>
                <a class="item transition" href="{{ url('/users/import') }}">
                    Importar
                </a>
            </div>
        @endcan

        @can('showResourceMenu', \Apithy\Company::class)
            <a class="title item">
                <i class="building titleIcon icon"></i>{{ trans('messages.companies') }}
                <i class="dropdown icon"></i>
            </a>
            <div class="content">
                <a class="item transition" href="{{ url('/companies') }}">
                    Listado
                </a>
                <a class="item transition" href="{{ url('/companies/create') }}">
                    Nuevo
                </a>
            </div>
        @elsecan('showResourceMenu', \Apithy\CompanyArea::class)
            @if(count($auth->user()->company)>0)
            <a class="title item" href="{{ route('companies.show',[$auth->user()->company[0]]) }}">
                <i class="building titleIcon icon"></i>
                {{ trans('messages.company') }}
            </a>
            <a class="title item">
                <i class="sitemap titleIcon icon"></i>{{ trans('messages.job_areas') }}
                <i class="dropdown icon"></i>
            </a>
            <div class="content">
                <a class="item transition" href="{{ route("areas.index",[$auth->user()->company[0]]) }}">
                    Listado
                </a>
                <a class="item transition" href="{{ route("areas.create",[$auth->user()->company[0]]) }}">
                    Nuevo
                </a>
            </div>
            @endif
        @endcan

        @can('showResourceMenu', \Apithy\Experience::class)
            <a class="title item">
                <i class="paper plane titleIcon icon"></i>{{ trans('messages.experiences') }}
                <i class="dropdown icon"></i>
            </a>
            <div class="content">
                <a class="item transition" href="{{ url('/experiences') }}">
                    Listado
                </a>
                <a class="item transition" href="{{ url('/experiences/create') }}">
                    Nuevo
                </a>
            </div>
        @endcan


        @can('showResourceMenu', \Apithy\Invitation::class)
            <a class="title item" href="{{ url('users/invitations') }}">
                <i class="address card titleIcon icon"></i>
                {{ trans('messages.invitations') }}
            </a>
        @endcan

        @can('showResourceMenu', \Apithy\App::class)
            <a href="{{ url('apps') }}" class="title item">
                <i class="rocket titleIcon icon"></i>{{ trans('messages.apps') }}
            </a>
        @endcan
    </sui-accordion>

    <div class="ui dropdown item displaynone scrolling" tabindex="0">
        <span class="">Dashboard</span>
        <i class="tachometer alternate icon"></i>
        <div class="menu" style="overflow: hidden; outline: none; cursor: -webkit-grab;" tabindex="-1">
            <div class="header">
                Dashboard
            </div>
            <div class="ui divider"></div>
            <a class="item" href="{{ url('/profile/edit') }}">
                Editar Perfil
            </a>
            <a class="item" href="{{ url('/profile/security') }}">
                Seguridad
            </a>
        </div>
    </div>
    @can('showResourceMenu', \Apithy\User::class)
    <div class="ui dropdown item displaynone scrolling" tabindex="0">
        <span class="">Usuarios</span>
        <i class="users icon"></i>
        <div class="menu" style="overflow: hidden; outline: none; cursor: -webkit-grab;" tabindex="-1">
            <div class="header">
                {{ trans('messages.users') }}
            </div>
            <div class="ui divider"></div>
            <a class="item" href="{{ url('/users') }}">
                Listado
            </a>
            <a class="item" href="{{ url('/users/create') }}">
                Nuevo
            </a>
            <a class="item" href="{{ url('/users/import') }}">
                Importar
            </a>
        </div>
    </div>
    @endcan
    @can('showResourceMenu', \Apithy\Company::class)
    <div class="ui dropdown item displaynone scrolling" tabindex="0">
        <span class="">Empresas</span>
        <i class="building icon"></i>
        <div class="menu" style="overflow: hidden; outline: none; cursor: -webkit-grab;" tabindex="-1">
            <div class="header">
                Empresas
            </div>
            <div class="ui divider"></div>
            <a class="item" href="{{ url('/companies') }}">
                Listado
            </a>
            <a class="item" href="{{ url('/companies/create') }}">
                Nuevo
            </a>
        </div>
    </div>
    @elsecan('showResourceMenu', \Apithy\CompanyArea::class)
        @if(count($auth->user()->company)>0)
        <div class="ui dropdown item displaynone scrolling" tabindex="0">
            <span class="">{{ trans('messages.company') }}</span>
            <i class="building icon"></i>
            <div class="menu" style="overflow: hidden; outline: none; cursor: -webkit-grab;" tabindex="-1">
                <div class="header">
                    Mi Empresa
                </div>
                <div class="ui divider"></div>
                <a class="item" href="{{ route('companies.show',[$auth->user()->company[0]]) }}">
                    Ver
                </a>
            </div>
        </div>
        <div class="ui dropdown item displaynone scrolling" tabindex="0">
            <span class="">{{ trans('messages.job_areas') }}</span>
            <i class="sitemap icon"></i>
            <div class="menu" style="overflow: hidden; outline: none; cursor: -webkit-grab;" tabindex="-1">
                <div class="header">
                    {{ trans('messages.job_areas') }}
                </div>
                <div class="ui divider"></div>
                <a class="item" href="{{ url("/companies/{$auth->user()->company[0]->id}/areas") }}">
                    Listado
                </a>
                <a class="item" href="{{ url("/companies/{$auth->user()->company[0]->id}/areas/create") }}">
                    Nuevo
                </a>
            </div>
        </div>
        @endif
    @endcan
    @can('showResourceMenu', \Apithy\Experience::class)
        <div class="ui dropdown item displaynone scrolling" tabindex="0">
            <span class="">Experiencias</span>
            <i class="paper plane icon"></i>
            <div class="menu" style="overflow: hidden; outline: none; cursor: -webkit-grab;" tabindex="-1">
                <div class="header">
                    Experiencias
                </div>
                <div class="ui divider"></div>
                <a class="item" href="{{ url('/experiences') }}">
                    Listado
                </a>
                <a class="item" href="{{ url('/experiences/create') }}">
                    Nuevo
                </a>
            </div>
        </div>
    @endcan
    @can('showResourceMenu', \Apithy\Invitation::class)
        <div class="ui dropdown item displaynone scrolling" tabindex="0">
            <span class="">Invitaciones</span>
            <i class="envelope icon"></i>
            <div class="menu" style="overflow: hidden; outline: none; cursor: -webkit-grab;" tabindex="-1">
                <div class="header">
                    Invitaciones
                </div>
                <div class="ui divider"></div>
                <a class="item" href="{{ url('/users/invitations') }}">
                    Listado
                </a>
            </div>
        </div>
    @endcan
</sui-sidebar>