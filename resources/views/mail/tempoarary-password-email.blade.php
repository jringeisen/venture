<x-mail::message>
# Hi {{ $student->name }},

Here is your temporary password: <strong>{{ $temporary_password }}</strong>

Make sure to create a new one that is unique to you.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
