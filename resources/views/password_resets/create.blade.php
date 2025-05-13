@extends('layouts.app')

@section('title', 'Forgot password')

@section('content')
    <h1>Forgot password</h1>
    
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form method="POST" action="{{ route('password.email.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
