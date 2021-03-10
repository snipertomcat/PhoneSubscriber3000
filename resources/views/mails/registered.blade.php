@component('mail::message')
# Hola {{$user->name}}

Tu cuenta en Apithy ha sido creada, ingresa a la plataforma y finaliza tu perfil!
@component('mail::button', ['url' => $login_link])
Finalizar mi perfil
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent