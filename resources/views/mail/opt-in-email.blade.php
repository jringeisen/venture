<x-mail::message email="{{$newsletterList->email}}">
# Hi,

Thank you for opting in to our newsletter, as promised here is your digital download of the planners.

<a href="{{ $temporaryUrl }}" download>Download Planner</a>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
