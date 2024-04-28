<x-mail::message>
# Dear {{$user_name}},

Your leave application has been rejected.

Regards,<br>
{{ $approved_by_name }}<br>
{{ config('app.name') }}
</x-mail::message>
