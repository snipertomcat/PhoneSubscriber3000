@extends((Auth::user()->isSuper()) ? 'layouts.admin._admin_tailwind':'layouts.platform')

@section('title', trans('messages.experiences'))

@push('styles')
    <link rel="stylesheet" href="/css/experience_editor_icons.css">
@endpush

@section('header')

@endsection

@section('body')
    @if(Auth::user()->isSuper() || Auth::user()->isAdmin())
        <apithy-experience-editor :experience-data="{{$experience}}"></apithy-experience-editor>
    @endif
    {{--
    <div class="container">
        <hr width="2">
        <apithy-experiences-form
                :experience="{{$experience}}"
                :adventures_list="{{json_encode($adventures)}}"
                :tag_list="{{json_encode($all_tags)}}"
                :abilities_list="{{json_encode($abilities)}}"
                :author_list="{{json_encode($authors)}}"
                :partner_list="{{json_encode($partners)}}"
                :companies_list="{{json_encode($companies)}}"
                :users_data="{{$users}}"
                mode="create"
                v-cloak>
        </apithy-experiences-form>
    </div>
    --}}
@endsection
