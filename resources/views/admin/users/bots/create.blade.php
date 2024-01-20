<h4>Create Bot</h4>
<form method="POST" action="{{ route('admin.users.create.bot.store', $user->uuid) }}">
    @csrf
    @include('admin.users.bots._form')
</form>
