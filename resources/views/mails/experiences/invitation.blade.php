@component('mail::message')
#Hola {{$user->name}}

Te han invitado a unirte a la experiencia
**{{ $experience->title }}**
@component('mail::button', ['url' => $login_link])
Unirme a la experiencia
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent