@extends('layouts.app')

@section('title', $user->name)

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
            @if (Auth::check() && Auth::id() != $user->id)
                @include('shared.follow_form')
            @endif
            
            @if ($microposts->count() > 0)
                <h3>Microposts ({{ $microposts->total() }})</h3>
                <ol class="microposts">
                    @foreach ($microposts as $micropost)
                        @include('shared.micropost')
                    @endforeach
                </ol>
                <div class="pagination justify-content-center">
                    {{ $microposts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
