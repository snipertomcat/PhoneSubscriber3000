@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.avatar'))

@section('body')
    @component('dashboard._carded')

        <form method="POST" action="{{ url('/profile/avatar') }}" enctype="multipart/form-data">
            @include('partials.flash')
            {{ csrf_field() }}
            <div class="box">
                <div class="field">
                    <input type="file" name="avatar" accept="image/*" class="input">
                    @if($errors->has('avatar'))
                        <p class="help is-danger">{{ $errors->first('avatar') }}</p>
                    @endif
                </div>
            </div>
            <div class="box">
                <div class="field">
                    <p class="control">
                        <button type="submit" class="button is-primary">{{ trans('messages.upload_profile_avatar') }}</button>
                    </p>
                </div>
            </div>
        </form>

    @endcomponent
@endsection
