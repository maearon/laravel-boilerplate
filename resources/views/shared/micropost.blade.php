<li class="micropost mb-3">
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <div class="flex-grow-1">
                    <h5 class="card-title">{{ $micropost->user->name }}</h5>
                    <p class="card-text">{{ $micropost->content }}</p>
                    
                    @if ($micropost->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $micropost->image) }}" alt="Micropost image" class="img-fluid">
                        </div>
                    @endif
                    
                    <small class="text-muted">Posted {{ $micropost->created_at->diffForHumans() }}</small>
                </div>
                
                @if (Auth::check() && Auth::id() === $micropost->user_id)
                    <div>
                        <form method="POST" action="{{ route('microposts.destroy', $micropost) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</li>
