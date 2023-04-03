@component('mail::message')
# Reset Password

You are receiving this email because we received a password reset request for your account. If you did not request a password reset, no further action is required.

To reset your password, click the button below:

@component('mail::button', ['url' => $url])
    Reset Password
@endcomponent

If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:

{{ $url }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
