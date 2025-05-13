<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('microposts.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <textarea class="form-control @error('content') is-invalid @enderror" name="content" placeholder="Compose new micropost..." rows="3" required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Post</button>
        </form>
    </div>
</div>
