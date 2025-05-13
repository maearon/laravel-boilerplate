<div class="stats">
    <a href="{{ route('users.following', $user) }}">
        <strong id="following" class="stat">{{ $user->following()->count() }}</strong> following
    </a>
    <a href="{{ route('users.followers', $user) }}">
        <strong id="followers" class="stat">{{ $user->followers()->count() }}</strong> followers
    </a>
</div>
