@component('partials.section')
    <div class="columns">
        <div class="column is-narrow-desktop">
            @include('partials.card')
        </div>
        <div class="column">
            {{ $slot }}
        </div>
    </div>
@endcomponent
