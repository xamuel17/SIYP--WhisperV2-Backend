@component('mail::message')
# Hello {{ $name }}

{{ strip_tags($message) }}


Regards,<br>
{{ config('app.name') }}
@endcomponent
