<sui-form action="{{ url('companies') }}">
    <sui-form-fields>
        <sui-form-field>
            <select class="ui dropdown search" name="filter[country_id]">
                <option value="" {{ on_filter('type', '') ? 'selected' : '' }}>{{ trans('messages.countries_all') }}</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ on_filter('country_id', $country->id) ? 'selected' : '' }}>{{ $country->name }}</option>
                @endforeach
            </select>

        </sui-form-field>
        <sui-form-field>
            <input class="input" type="text" name="search" value="{{ request('search', '') }}"
                   placeholder="{{ trans('messages.search') }}...">
        </sui-form-field>
        <sui-form-field class="control">
            <sui-button class="secondary labeled icon">{{ trans('messages.filter') }}
                <i class="filter icon"></i>
            </sui-button>
        </sui-form-field>
        @can('create', \Apithy\Company::class)
            <sui-form-field>
                <a class="ui button labeled icon primary" href="{{ route("companies.create") }}">
                    {{ trans('messages.create_new') }} {{ trans('messages.company') }}
                    <i class="building icon"></i>
                </a>
            </sui-form-field>
        @endcan
    </sui-form-fields>
</sui-form>