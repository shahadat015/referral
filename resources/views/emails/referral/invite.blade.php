<x-mail::message>
# You have been invited to join our platform.

Click the link below to register!

<x-mail::button :url="$referralLink">
    Register Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
