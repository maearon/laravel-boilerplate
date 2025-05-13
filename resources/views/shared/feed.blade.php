@if ($microposts->count() > 0)
    <ul class="list-unstyled">
        @foreach ($microposts as $micropost)
            @include('shared.micropost')
        @endforeach
    </ul>
@else
    <p>No microposts yet.</p>
@endif
