<sui-form action="{{ url('users') }}" method="GET">
    <sui-form-fields>
        {{--
        <div class="control">
            <div class="select">
                <select>
                    <option>{{ trans('messages.aplications_all') }}</option>
                    <option>With options</option>
                </select>
            </div>
        </div>
        --}}
        <sui-form-field>
            <div class="select">
                <select name="filter[roleIn][cstm]">
                    <option value="" {{ request('filter.roleIn.cstm') ? 'selected' : '' }}>{{ trans('messages.roles_all') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('filter.roleIn.cstm') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </sui-form-field>
        <sui-form-field>
            <div class="select">
                <select name="filter[activated]">
                    <option value="" {{ on_filter('activated', '') ? 'selected' : '' }}>{{ trans('messages.status_all') }}</option>
                    <option value="1" {{ on_filter('activated', '1') ? 'selected' : '' }}>{{ trans('messages.active') }}</option>
                    <option value="0" {{ on_filter('activated', '0') ? 'selected' : '' }}>{{ trans('messages.inactive') }}</option>
                </select>
            </div>
        </sui-form-field>
        <sui-form-field>
            <div class="select">
                <select name="filter[company_id]">
                    <option value="" {{ on_filter('company_id', '') ? 'selected' : '' }}>{{ trans('messages.companies_all') }}</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ on_filter('company_id', $company->id) ? 'selected' : '' }}> {{$company->name}}</option>
                    @endforeach
                </select>
            </div>
        </sui-form-field>
        <sui-form-field>
            <input class="input" type="text" name="search" value="{{ request('search', '') }}"
                   placeholder="{{ trans('messages.search') }}...">
        </sui-form-field>
        <sui-form-field>
            <sui-button type="submit" class="ui button secondary">{{ trans('messages.filter') }}</sui-button>
        </sui-form-field>
    </sui-form-fields>
</sui-form>