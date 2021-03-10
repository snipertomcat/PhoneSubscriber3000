@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Empresas</a>
        <i class="right chevron icon divider"></i>
        <div class="section">Areas</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">Nuevo</div>
    </div>
@endSection

@section('body')
    @if(Auth::user()->isSuper()) )<apithy-company-menu :company-data="{{$company}}"></apithy-company-menu>@endif
    <apithy-areas-create :auth-user="{{$auth_user}}" :areas="{{$areas}}" :company="{{$company}}"></apithy-areas-create>
    {{--
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-borders no-shadows">
            <div class="ui segment">
                @include('partials.flash')
            </div>
            <div class="ui segment">
                <apithy-areaposition-form
                        url="{{ route("areas.store",[$company]) }}"
                        method="POST"
                        csrf="{{csrf_token()}}"
                        :parent="{{$areas}}"
                        current-area="{{$company_area->id}}"
                        current-company="{{$company->id}}"
                        cancel-url="{{ route("areas.index",[$company]) }}">
                </apithy-areaposition-form>
            </div>
        </div>
    </div>
    --}}
@endsection