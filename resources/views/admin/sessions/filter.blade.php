<sui-form action="{{ route('sessions.index',[$experience]) }}">
    <sui-form-fields class="fields">
        <sui-form-field>
            <input class="input" type="text" name="search" value="{{ request('search', '') }}"
                   placeholder="{{ trans('messages.search') }}...">
        </sui-form-field>
        <sui-form-field class="control">
            <sui-button class="secondary labeled icon">{{ trans('messages.filter') }}
                <i class="filter icon"></i>
            </sui-button>
        </sui-form-field>
        @can('create', \Apithy\Experience::class)
            <sui-form-field>
                <a class="ui button labeled icon primary" href="{{ route("sessions.create",[$experience]) }}">
                    {{ trans('messages.create_new') }} {{ trans('messages.session') }}
                    <i class="plus icon"></i>
                </a>
            </sui-form-field>
        @endcan
    </sui-form-fields>
</sui-form>