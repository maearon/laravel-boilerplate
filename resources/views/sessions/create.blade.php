@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <h1>Log in</h1>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <p><a href="{{ route('password.email') }}">Forgot password?</a></p>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                    <label class="form-check-label" for="remember">Remember me on this computer</label>
                </div>

                <button type="submit" class="btn btn-primary">Log in</button>
            </form>

            <p class="mt-3">New user? <a href="{{ route('signup') }}">Sign up now!</a></p>
        </div>
    </div>
@endsection
