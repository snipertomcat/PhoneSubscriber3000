@component('mail::message')
# Hola {{$user->name}}

Te haz unido correctamente a la experiencia
**{{ $experience->title }}**
@component('mail::button', ['url' => $login_link])
Ir a la experiencia
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent