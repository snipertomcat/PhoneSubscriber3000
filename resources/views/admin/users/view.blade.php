@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.users'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Usuarios</a>
        <i class="right chevron icon divider"></i>
        <div class="active section">{{$user->email}}</div>
    </div>
@endSection
@section('body')
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-borders no-shadows">
            <div class="ui segment">
                @include('partials.flash')

                    <sui-form class="ui grid" method="POST" action="{{ url('/users/'.$user->id) }}">
                        <div class="ui sixteen wide column">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}
                            <input type="hidden" name="register_type" value="admin">
                            <sui-form-fields class="two fields">
                                <sui-form-field {{ $errors->has('role_id') ? 'error' : '' }} >
                                    <select name="role_id"
                                            class="ui search dropdown twelve wide large screen">
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol->id }}" {{ $rol->id == old('role_id', isset($user->roles[0]) ? $user->roles[0]->id : null) ? 'selected' : '' }}>{{ $rol->name }}</option>
                                        @endforeach
                                    </select>
                                </sui-form-field>

                                <sui-form-field @if($errors->has('email')) error
                                                message="{{ $errors->first('email') }}" @endif>
                                    <sui-input placeholder="{{ trans('messages.email') }}" type="text" icon="envelope"
                                               name="email"
                                               value="{{ old('email', optional($user)->email) }}"></sui-input>
                                </sui-form-field>
                            </sui-form-fields>

                            <sui-form-fields class="two fields">
                                <sui-form-field @if($errors->has('name')) error
                                                message="{{ $errors->first('name') }}" @endif>
                                    <sui-input placeholder="{{ trans('messages.name') }}" type="text" icon="code"
                                               name="name"
                                               value="{{ old('name',optional($user)->name) }}"></sui-input>
                                </sui-form-field>
                                <sui-form-field @if($errors->has('surname')) error
                                                message="{{ $errors->first('surname') }}" @endif>
                                    <sui-input placeholder="{{ trans('messages.surname') }}" type="text" icon="code"
                                               name="surname"
                                               value="{{ old('surname',optional($user)->surname)}}"></sui-input>
                                </sui-form-field>
                            </sui-form-fields>

                            <apithy-select :companies="{{$companies}}"
                                           :company_id="{{$user->company->first()->id}}"
                                           :super="{{(int)Auth::user()->isSuper()}}"
                                           :admin="{{(int)Auth::user()->isAdmin()}}"
                                           :user="{{$user}}"
                            >
                            </apithy-select>

                            <sui-form-fields class="two fields">
                                <sui-form-field @if($errors->has('phone')) error
                                                message="{{ $errors->first('phone') }}" @endif>
                                    <sui-input placeholder="{{ trans('messages.contact_phone') }}" type="text"
                                               icon="phone"
                                               name="phone"
                                               value="{{ old('phone',optional($user)->phone) }}"></sui-input>
                                </sui-form-field>
                                <sui-form-field>
                                    <select value="1" name="activated"
                                            class="ui dropdown twelve wide large screen">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </sui-form-field>
                            </sui-form-fields>

                            <div class="ui stackable two column grid">
                                <div class="column">
                                    <button class="ui button primary labeled icon sixteen column" type="submit">
                                        {{ trans('messages.save_changes') }}
                                        <i class="edit icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </sui-form>
            </div>
        </div>
    </div>
@endsection