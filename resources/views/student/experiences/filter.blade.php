<sui-form action="{{ url('experiences') }}">
    <sui-form-fields class="fields">
        <sui-form-field>
            <select class="ui dropdown search" name="filter[abilities]">
                <option value="" {{ on_filter('type', '') ? 'selected' : '' }}>
                    {{ trans('messages.abilities') }}
                </option>
                @foreach($abilities as $ability)
                    <option value="{{ $ability->id }}" {{ on_filter('ability_id', $ability->id) ? 'selected' : '' }}>
                        {{ $ability->title }}
                    </option>
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
    </sui-form-fields>
</sui-form>