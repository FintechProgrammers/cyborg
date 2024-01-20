<h4>Update Bot</h4>
<form method="POST" action="{{ route('admin.users.create.bot.store') }}">
    @csrf
    @include('admin.users.bots._form')
</form>
