<h1>
    <img src="https://gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80" alt="{{ $user->name }}" class="gravatar">
    {{ $user->
