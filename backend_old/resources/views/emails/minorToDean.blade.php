<x-mail::message>
# Good day, {{$other_dep}}

We already done to adding our schedule.

--{{$department}}

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
