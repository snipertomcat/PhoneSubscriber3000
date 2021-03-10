@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.experiences'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="section">Experiencias</div>
        <i class="right chevron icon divider"></i>
        <div class="section">{{$experience->title}}</div>
        <i class="right chevron icon divider"></i>

        <div class="section">Editar</div>
    </div>
@endSection

@section('body')
    <div class="container">
        <apithy-experiences-menu :experience="{{$experience}}"></apithy-experiences-menu>
        <hr width="2">
        <apithy-experiences-form
                :experience="{{$experience}}"
                :adventures_list="{{json_encode($adventures)}}"
                :tag_list="{{json_encode($all_tags)}}"
                :abilities_list="{{json_encode($abilities)}}"
                :author_list="{{json_encode($authors)}}"
                :partner_list="{{json_encode($partners)}}"
                :companies_list="{{$companies}}"
                mode="edit"
                v-cloak>
        </apithy-experiences-form>
    </div>
@endsection