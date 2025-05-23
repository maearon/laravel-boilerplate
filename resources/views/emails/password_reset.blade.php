<h1>Password reset</h1>

<p>To reset your password click the link below:</p>

<p>
    <a href="{{ route('password.reset', $user->reset_digest) }}">Reset password</a>
</p>

<p>This link will expire in two hours.</p>

<p>
    If you did not request your password to be reset, please ignore this email and
    your password will stay as it is.
</p>
