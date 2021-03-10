<sui-form action="{{ url("companies/$company_area->company->id/areas/$company_area->id/positions") }}">
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
        @can('create', [\Apithy\CompanyPosition::class, $company_area])
            <sui-form-field>
                <a class="ui button labeled icon primary" href="{{ route("positions.create",[$company_area->company,$company_area]) }}">
                    {{ trans('messages.add_job_title') }}
                    <i class="plus icon"></i>
                </a>
            </sui-form-field>
        @endcan
    </sui-form-fields>
</sui-form>