@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="row">
        <aside class="col-md-4">
            <section class="user_info">
                <h1>
                    <img src="https://gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80" alt="{{ $user->name }}" class="gravatar">
                    {{ $user->name }}
                </h1>
            </section>
            <section class="stats">
                @include('shared.stats')
            </section>
        </aside>
        <div class="col-md-8">
            <h3>{{ $title }}</h3>
            
            @if ($users->count() > 0)
                <ul class="users follow">
                    @foreach ($users as $user)
                        @include('shared.user')
                    @endforeach
                </ul>
                <div class="pagination justify-content-center">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
