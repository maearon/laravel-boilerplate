<li class="mb-3">
    <div class="d-flex align-items-center">

        <img src="https://gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=50"
             alt="{{ $user->name }}"
             class="gravatar me-2">

        <div class="flex-grow-1">
            <a href="{{ route('users.show', $user) }}">{{ $user->name }}</a>
        </div>

        @if (Auth::check() && Auth::id() !== $user->id)
            <div>
                @include('shared.follow_form', ['user' => $user])
            </div>
        @endif

        @if (Auth::check() && Auth::user()->admin && Auth::id() !== $user->id)
            <div class="ms-2">
                <form method="POST" action="{{ route('users.destroy', $user) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('Are you sure?')">
                        @can('delete', $user)
                            @if(Auth::id() === $user->id)
                                🗑 Delete
                            @else
                                ⚠ Admin Delete
                            @endif
                        @endcan
                    </button>
                </form>
            </div>
        @endif

    </div>
</li>
