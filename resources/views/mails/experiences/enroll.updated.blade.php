@component('mail::message')
# Hola {{$user->name}}

{{ $message }}
**{{ $experience->title }}**
@component('mail::button', ['url' => $login_link])
Ir a la experiencia
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent