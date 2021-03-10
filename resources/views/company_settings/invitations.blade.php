@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.profile'))

@section('body')
    <div class="sixteen wide tablet two wide computer column">
        <div class="ui vertical secondary pointing fluid tabular menu">
            <a class="item active" data-tab="profile">
                Invitaciones
            </a>
            <a class="item" data-tab="security">
                Personalización
            </a>
        </div>
    </div>
    <div class="sixteen wide tablet fourteen wide computer column" data-tab="profile">
        <div class="ui segments">
            <div class="ui segment">
                <h5 class="ui header">
                    <i class="envelope icon"></i>Configuración - Invitaciones
                </h5>
                @include('partials.flash')
            </div>
            <form class="ui form segment" method="POST">
                {{ csrf_field() }}
                <sui-form-field>
                    <label class="label">Enviar solamente:</label>
                    <div class="ui left icon input">
                        <select name="settings[remainder_limit]" class="ui dropdown twelve wide large screen">
                            <option value="0" {{ ($settings['remainder_limit'] == 0) ? 'selected':'' }}>No enviar recordatorios</option>
                            <option value="1" {{ ($settings['remainder_limit'] == 1) ? 'selected':'' }}>1 recordatorio</option>
                            <option value="2" {{ ($settings['remainder_limit'] == 2) ? 'selected':'' }}>2 recordatorios</option>
                            <option value="3" {{ ($settings['remainder_limit'] == 3) ? 'selected':'' }}>3 recordatorios</option>
                        </select>
                    </div>
                </sui-form-field>
                <sui-form-field>
                    <label class="label">Enviar un recordatorio al usuario cada:</label>
                    <div class="ui left icon input">
                        <select name="settings[remainder_period]" class="ui dropdown twelve wide large screen">
                            <option value="12" {{ ($settings['remainder_period'] == 12) ? 'selected':'' }}>12 Horas</option>
                            <option value="24" {{ ($settings['remainder_period'] == 24) ? 'selected':'' }}>24 Horas</option>
                            <option value="36" {{ ($settings['remainder_period'] == 36) ? 'selected':'' }}>36 Horas</option>
                            <option value="48" {{ ($settings['remainder_period'] == 48) ? 'selected':'' }}>48 Horas</option>
                        </select>
                    </div>
                </sui-form-field>
                <sui-form-field>
                    <button type="submit" class="ui labeled button primary icon">
                        {{ trans('messages.save') }}
                        <i class="save icon"></i>
                    </button>
                    @include('partials.form.cancel-button', ['url' => '/companies'])
                </sui-form-field>
            </form>
        </div>
    </div>
@endsection