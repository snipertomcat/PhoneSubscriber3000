
<form action="{{ url('users') }}" method="GET">
    <sui-form-fields>
        <sui-form-field>
            <div class="select">
                <select class="ui search dropdown" name="filter[roleIn][cstm]">
                    <option value="" {{ request('filter.roleIn.cstm') ? 'selected' : '' }}>{{ trans('messages.roles_all') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('filter.roleIn.cstm') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </sui-form-field>
        @if( Auth::user()->isSuper())
            <sui-form-field>
                <div class="select">
                    <select class="ui search dropdown" name="filter[company_id]">
                        <option value="" {{ on_filter('company_id', '') ? 'selected' : '' }}>{{ trans('messages.companies_all') }}</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ on_filter('company_id', $company->id) ? 'selected' : '' }}>{{$company->name}}</option>
                        @endforeach
                    </select>
                </div>
            </sui-form-field>
        @endif
        <sui-form-field>
            <div class="select">
                <select class="ui search dropdown" name="filter[activated]">
                    <option value="" {{ on_filter('activated', '') ? 'selected' : '' }}>{{ trans('messages.status_all') }}</option>
                    <option value="1" {{ on_filter('activated', '1') ? 'selected' : '' }}>{{ trans('messages.active') }}</option>
                    <option value="0" {{ on_filter('activated', '0') ? 'selected' : '' }}>{{ trans('messages.inactive') }}</option>
                </select>
            </div>
        </sui-form-field>
        <sui-form-field>
            <input class="input" type="text" name="search" value="{{ request('search', '') }}"
                   placeholder="{{ trans('messages.search') }}...">
        </sui-form-field>
        <sui-form-field>
            <button type="submit" class="ui button secondary labeled icon button">
                {{ trans('messages.filter') }}
                <i class="filter icon"></i>
            </button>
        </sui-form-field>
        <br>
        @can('create', \Apithy\User::class)
            <sui-form-field>
                <a href="{{url('users/create')}}" class="ui button right floated small primary labeled icon button">
                    <i class="user icon"></i> Agregar usuario
                </a>
            </sui-form-field>

            <sui-form-field>
                <a href="{{url('/users/import')}}"
                   class="ui small green labeled icon button">
                    <i class="file excel icon"></i> Importar Usuarios
                </a>
            </sui-form-field>
        @endCan
    </sui-form-fields>
</form>