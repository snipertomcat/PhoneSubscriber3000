@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))

@section('body')
    <apithy-companies-form :auth-user="{{$auth_user}}" :countries="{{$countries}}"></apithy-companies-form>

    {{--
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-borders no-shadows">
            <div class="ui segment">
                @include('partials.flash')
            </div>
            <div class="ui segment">
                <sui-form size="standard" method="POST" action="{{ route("companies.store") }}">
                    {{ csrf_field() }}
                    @include('admin.companies.form')
                </sui-form>
            </div>
        </div>
    </div>
    --}}
@endsection