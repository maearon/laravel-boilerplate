@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="center jumbotron">
        @if (Auth::check())
            <div class="row">
                <aside class="col-md-4">
                    <section class="user_info">
                        @include('shared.user_info', ['user' => Auth::user()])
                    </section>
                    <section class="stats">
                        @include('shared.stats', ['user' => Auth::user()])
                    </section>
                    <section class="micropost_form">
                        @include('shared.micropost_form')
                    </section>
                </aside>
                <div class="col-md-8">
                    <h3>Micropost Feed</h3>
                    @include('shared.feed')
                </div>
            </div>
        @else
            <h1>Welcome to the Sample App</h1>
            <h2>
                This is the home page for the
                <a href="https://www.laravel.com/">Laravel Tutorial</a>
                sample application.
            </h2>
            <p>
                <a class="btn btn-lg btn-primary" href="{{ route('signup') }}">Sign up now!</a>
            </p>
        @endif
    </div>
@endsection
