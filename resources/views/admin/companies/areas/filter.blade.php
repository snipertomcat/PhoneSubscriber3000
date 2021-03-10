<sui-form action="{{ url("companies/$company->id/areas") }}">
    <sui-form-fields>
        <sui-form-field>
            <input class="input" type="text" name="search" value="{{ request('search', '') }}"
                   placeholder="{{ trans('messages.search') }}...">
        </sui-form-field>
        <sui-form-field class="control">
            <sui-button class="secondary labeled icon">{{ trans('messages.filter') }}
                <i class="filter icon"></i>
            </sui-button>
        </sui-form-field>
        @can('create', [\Apithy\CompanyArea::class,$company])
            <sui-form-field>
                <a class="ui button labeled icon primary" href="{{ route("areas.create",[$company]) }}">
                    {{ trans('messages.add_job_area') }}
                    <i class="plus icon"></i>
                </a>
            </sui-form-field>
        @endcan
    </sui-form-fields>
</sui-form>