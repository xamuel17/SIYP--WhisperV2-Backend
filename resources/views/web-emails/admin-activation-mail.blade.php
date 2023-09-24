@component('mail::message')
# Hello {{ $name }}

You are receiving this mail because an account was setup for you on SIYP.

Click on the button below to complete setting up your account.

@component('mail::button', ['url' => $url])
Setup My Account
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
