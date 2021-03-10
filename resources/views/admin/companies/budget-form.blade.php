@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))

@section('body')
    @if(Auth::user()->isAdmin())
        <apithy-owner-tabs-menu :items-data="[]"></apithy-owner-tabs-menu>
    @endif
    <apithy-companies-budget-form :auth-user="{{$auth_user}}" :company="{{$company}}"></apithy-companies-budget-form>
    <br>
    <div class="container">
        <apithy-budget-list :budget-list="{{$budgets}}"></apithy-budget-list>
    </div>
@endsection