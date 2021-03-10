    <div class="ui grid two columns">
        <div class="ui column">
            <sui-form action="{{ url('apps') }}">
                <sui-form-fields class="field is-grouped">
                    <sui-form-field>
                        <sui-input type="text" name="search" value="{{ request('search', '') }}" placeholder="{{ trans('messages.search_app') }}..."/>
                    </sui-form-field>
                    <sui-form-field class="control">
                        <sui-button type="submit" class="ui button primary">{{ trans('messages.filter') }}</sui-button>
                    </sui-form-field>
                </sui-form-fields>
            </sui-form>
        </div>
        <div class="column">
        <div class="ui right column">
            <a href="{{ url('apps/create') }}">
                <sui-button type="submit" icon="circle plus icon" class="ui right floated button primary four wide">{{ trans('messages.add_app') }}</sui-button>
            </a>
        </div>
        </div>  
    </div>