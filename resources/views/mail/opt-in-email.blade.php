<x-mail::message email="{{$newsletterList->email}}">
# Hi,

Thank you for opting in to our newsletter, as promised here is your digital download of the planners.

<x-mail::button :url="route('student-planner.download')">Download Planner</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
