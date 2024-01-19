<form method="POST" action="{{ route('admin.users.debit.store', ['user' => $user->uuid]) }}">
    <h5 class="modal-title mb-3" id="funWalletTitle">Debit <span class="text-capitalize">{{ $type }}</span> Wallet</h5>
    @include('admin.users.form.wallet-form')
</form>
