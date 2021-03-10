@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', 'Biografia')

@section('body')
    <div class="sixteen wide tablet fourteen wide computer column" data-tab="profile">
        <div class="ui top attached tabular menu">
            <a href="{{ url('/profile/edit') }}" class="item" data-tab="profile">
                <h5 class="ui header">
                    <i class="users icon"></i>Perfil
                </h5>
            </a>
            <a href="{{ url('/profile/biography') }}" class="item active" data-tab="biography">
                <h5 class="ui header">
                    <i class="icon file alternate"></i>Biografia
                </h5>
            </a>
            <a href="{{ url('/profile/payment-information') }}" class="item" data-tab="payment_info">
                <h5 class="ui header">
                    <i class="money bill alternate icon"></i>Informaci&oacute; de Pago
                </h5>
            </a>
            <a href="{{ url('/profile/transactions') }}" class="item" data-tab="payment_info">
                <h5 class="ui header">
                    <i class="money bill alternate icon"></i>Transacciones
                </h5>
            </a>
            <a href="{{ url('/profile/security') }}" class="item" data-tab="security">
                <h5 class="ui header">
                    <i class="address card icon"></i>Seguridad
                </h5>
            </a>
        </div>
        <div class="ui bottom attached segment">
            <form class="ui grid row form segment no-borders no-shadows" method="POST" action="{{ url('/profile/biography') }}">
                {{ csrf_field() }}
                <sui-form-fields class="row ui two fields">
                    <div class="ui field">
                        <label class="label">Historia Académica</label>
                        <div class="control is-expanded">
                            <div class="select is-fullwidth">
                                <textarea class="ui input" name="academic_history" rows="20">{{$user->academic_history}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="ui field">
                        <label class="label">Historia Laboral</label>
                        <div class="control is-expanded">
                            <div class="select is-fullwidth">
                                <textarea class="ui input" name="work_history" rows="20">{{$user->work_history}}</textarea>
                            </div>
                        </div>
                    </div>
                </sui-form-fields>
                <div class="row">
                    <div class="ui field">
                        <button class="ui labeled icon button primary one column">
                            <i class="save circle icon"></i>
                            {{ trans('messages.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection