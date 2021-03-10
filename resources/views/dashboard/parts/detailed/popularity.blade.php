@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', 'Dashboard')

@section('body')
    <div class="container">
    @if(Auth::user()->isSuper() || Auth::user()->isAdmin())
        {{--
            <div class="row padding-tb-25">
                <div class="col-auto padding-lr-10">
                    <a href="/dashboard">
                        <span class="has-text-primary is-uppercase">{{ trans('messages.summary') }}</span>
                    </a>
                </div>
                <div class="col-auto padding-lr-10">
                    <a href="/dashboard/journeys">
                        <span class="has-text-primary is-uppercase">{{ trans('messages.journey') }}s</span>
                    </a>
                </div>
                <div class="col-auto padding-lr-10">
                    <a href="/dashboard/experiences">
                        <span class="has-text-primary is-uppercase">{{ trans('messages.experiences') }}</span>
                    </a>
                </div>
                <div class="col-auto padding-lr-10">
                    <a href="/dashboard/users">
                        <span class="has-text-primary is-uppercase">{{ trans('messages.users') }}</span>
                    </a>
                </div>
            </div>
            --}}
            <apithy-menu-dashboard></apithy-menu-dashboard>
            <apithy-admin-detail-popularity :popularity="{{json_encode($view['activity'])}}" :alternate="true"></apithy-admin-detail-popularity>
        @else
        <!--apithy-student-dashboard></apithy-student-dashboard-->
        @endif
    </div>
@endsection

