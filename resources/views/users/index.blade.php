@extends('layouts.app')

@section('title', 'All users')

@section('content')
    <h1>All users</h1>

    <div class="pagination justify-content-center mb-3">
        {{ $users->links() }}
    </div>

    <ul class="users">
        @foreach ($users as $user)
            @include('shared.user')
        @endforeach
    </ul>

    <div class="pagination justify-content-center mt-3">
        {{ $users->links() }}
    </div>
@endsection
