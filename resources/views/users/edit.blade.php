@extends('layouts.app')

@section('title', 'Edit user')

@section('content')
    <h1>Update your profile</h1>
    
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PATCH')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                    <small class="form-text text-muted">Leave blank if you don't want to change it</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmation</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                
                <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
            
            <div class="gravatar_edit mt-3">
                <a href="https://gravatar.com/emails" target="_blank" rel="noopener">Change Gravatar</a>
            </div>
        </div>
    </div>
@endsection
