@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.users'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="section">Usuarios</div>
        <i class="right chevron icon divider"></i>
        <div class="section">Importar</div>
    </div>
@endSection

@section('body')

    <apithy-users-import :company_id="{{request('company_id',0)}}"
                         :companies="{{ $companies }}"
                         :auth_user="{{ $auth_user }}"
                         v-cloak>
    </apithy-users-import>

@endsection