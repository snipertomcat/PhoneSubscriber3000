@extends(Auth::user()->isSuper() ? 'layouts.dashboard' : 'layouts.platform',['no_owner_bar' => true])

@section('title', trans('messages.home'))

@section('body')
   <div class="">
      <home :user="{{Auth::user()}}"></home>
   </div>
@endsection