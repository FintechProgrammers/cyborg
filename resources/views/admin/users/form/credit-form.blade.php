<form method="POST" action="{{ route('admin.users.fund.store', ['user' => $user->uuid]) }}">
    <h5 class="modal-title mb-3" id="funWalletTitle">Fund <span class="text-capitalize">{{ $type }}</span> Wallet</h5>
    @include('admin.users.form.wallet-form')
</form>
