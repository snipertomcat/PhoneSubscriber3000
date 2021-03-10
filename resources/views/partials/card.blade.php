<div class="card" style="width: 256px;">
    <div class="card-image">
        <figure class="image is-square">
            <img src="{{ $auth->user()->avatarUrl() }}">
        </figure>
    </div>
    <div class="card-content">
        <div class="media">
            <div class="media-content">
                <p class="title is-4">{{ $auth->user()->full_name }}</p>
                <p class="subtitle is-6">{{ $auth->user()->email }}</p>
            </div>
        </div>
    </div>
    <footer class="card-footer">
        <a class="card-footer-item" href="{{ url('/profile/avatar') }}">{{ trans('messages.change_avatar') }}</a>
    </footer>
</div>