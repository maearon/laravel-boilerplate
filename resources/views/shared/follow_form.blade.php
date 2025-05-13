@if (Auth::check() && Auth::id() !== $user->id)
    <div class="mb-3">
        @if (Auth::user()->isFollowing($user))
            <form method="POST" action="{{ route('relationships.destroy') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="followed_id" value="{{ $user->id }}">
                <button type="submit" class="btn btn-sm btn-outline-primary">Unfollow</button>
            </form>
        @else
            <form method="POST" action="{{ route('relationships.store') }}">
                @csrf
                <input type="hidden" name="followed_id" value="{{ $user->id }}">
                <button type="submit" class="btn btn-sm btn-primary">Follow</button>
            </form>
        @endif
    </div>
@endif
