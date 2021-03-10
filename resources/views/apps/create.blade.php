@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.create_app'))

@section('body')
    <apithy-app-create inline-template v-cloak url="{{ url('oauth/clients') }}" move="{{ url('apps') }}">
        <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
            <div class="ui segments">
                <div class="ui segment">
                    <h5 class="ui header">
                        <i class="rocket icon"></i>Create App
                    </h5>
                </div>
                <div class="ui segment">
                    <sui-form class="ui form">
                        <sui-form-field class="eight wide">
                            <label class="label">{{ trans('messages.name') }}</label>
                            <input class="input" type="text" name="name" v-model="name">
                        </sui-form-field>
                        <sui-form-field class="eight wide">
                            <label class="label">{{ trans('messages.redirect_url') }}</label>
                            <input class="input" type="text" name="redirect" v-model="redirect">
                        </sui-form-field>
                        <sui-form-field class="eight wide">
                            <label class="label">{{ trans('messages.description') }}</label>
                            <textarea class="textarea" v-model="description"></textarea>
                        </sui-form-field>
                        <sui-form-field class="eight wide">
                            <label class="label">{{ trans('messages.project_manager') }}</label>
                            <input class="input" type="text" v-model="project_manager">
                        </sui-form-field>
                        <sui-button type="button" class="button is-primary" :class="{ 'is-loading': loading }"
                                    :disabled="!filled"
                                    @click="create">
                            {{ trans('messages.add') }}
                        </sui-button>
                    </sui-form>
                </div>
            </div>
        </div>
    </apithy-app-create>

@endsection
<script>
    import SuiForm from "semantic-ui-vue/src/collections/Form/Form";

    export default {
        components: {SuiForm}
    }
</script>