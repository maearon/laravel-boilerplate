<h1>Sample App</h1>

<p>Hi {{ $user->name }},</p>

<p>
    Welcome to the Sample App! Click on the link below to activate your account:
</p>

<p>
    <a href="{{ route('account.activate', $user->activation_digest) }}">Activate</a>
</p>

<p>
    If you did not sign up for this account, please ignore this email.
</p>
