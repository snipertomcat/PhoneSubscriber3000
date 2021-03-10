@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="section">Experiencias</div>
        <i class="right chevron icon divider"></i>
        <div class="section">{{$experience->title}}</div>
        <i class="right chevron icon divider"></i>
        <div class="section">{{$session->title}}</div>
        <i class="right chevron icon divider"></i>
        <div class="section">Editar</div>
    </div>
@endSection

@section('body')
    <div class="container">
        <apithy-experiences-menu :experience="{{$experience}}" section-active="sessions"></apithy-experiences-menu>
        <hr width="2">
        <apithy-sessions-form
                mode="edit"
                :session="{{$session}}"
                :sessions_type_list="{{json_encode($type_list)}}"
                :experience="{{$experience}}"
                :author-list="{{json_encode($authors)}}"
                :partner-list="{{json_encode($partners)}}"
                :abilities-list="{{json_encode($abilities)}}"
                :tags-list="{{json_encode($tags)}}">
        </apithy-sessions-form>
    </div>
    {{--
    <div class="sixteen wide tablet two wide computer column">
        <div class="ui vertical secondary pointing fluid tabular menu">
            <a href="{{ route('experiences.update',[$experience]) }}" class="item" data-tab="Datos Generales">
                Datos Generales
            </a>
            <a href="{{ route('sessions.index',[$experience]) }}" class="item active" data-tab="Sesiones">
                Sesiones
            </a>
            <a href="{{ url('/profile/security') }}" class="item" data-tab="security">
                Configuracion
            </a>
        </div>
    </div>
    <div class="sixteen wide tablet fourteen wide computer column">
        <div class="ui segments no-borders no-shadows">
            @include('partials.flash')
                <apithy-sessions-info-form :session="{{$session}}" mode="create" :auth_user="{{Auth::user()}}" inline-template v-cloak>
                    <sui-form enctype="multipart/form-data"  method="POST" action="{{ route('sessions.update',[$experience,$session]) }}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        @include('admin.sessions.form')
                    </sui-form>
                </apithy-sessions-info-form>
        </div>
    </div>
    --}}
@endsection