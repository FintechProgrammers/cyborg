<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <h5>Main Balance</h5>
                <h6>{{ number_format(!empty($wallet) ? $wallet->balance : 0, 2) }} USDT</h6>
            </div>
            <div>
                <button class="btn btn-primary take-wallet-action" data-bs-toggle="modal" data-bs-target="#funWallet"
                    data-url="{{ route('admin.users.fund', ['user' => $user->uuid, 'wallet' => 'main']) }}">Credit</button>
                <button class="btn btn-danger take-wallet-action" data-bs-toggle="modal" data-bs-target="#funWallet"
                    data-url="{{ route('admin.users.debit', ['user' => $user->uuid, 'wallet' => 'main']) }}">Debit</button>
            </div>
        </div>
    </div>
    <!-- end card body -->
</div>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <h5>Fee Balance</h5>
                <h6>{{ number_format(!empty($wallet) ? $wallet->fee : 0, 2) }} USDT</h6>
            </div>
            <div>
                <button class="btn btn-primary take-wallet-action" data-bs-toggle="modal" data-bs-target="#funWallet"
                    data-url="{{ route('admin.users.fund', ['user' => $user->uuid, 'wallet' => 'fee']) }}">Credit</button>
                <button class="btn btn-danger take-wallet-action" data-bs-toggle="modal" data-bs-target="#funWallet"
                    data-url="{{ route('admin.users.debit', ['user' => $user->uuid, 'wallet' => 'fee']) }}">Debit</button>
            </div>
        </div>
    </div>
    <!-- end card body -->
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Today Profit</h5>
                        <h6>{{ number_format($todayProfit, 2) }} USDT</h6>
                    </div>
                    {{-- <div>
                        <button class="btn btn-primary take-wallet-action" data-bs-toggle="modal" data-bs-target="#funWallet"
                            data-url="{{ route('admin.users.fund', ['user' => $user->uuid, 'wallet' => 'fee']) }}">Credit</button>
                        <button class="btn btn-danger take-wallet-action" data-bs-toggle="modal" data-bs-target="#funWallet"
                            data-url="{{ route('admin.users.debit', ['user' => $user->uuid, 'wallet' => 'fee']) }}">Debit</button>
                    </div> --}}
                </div>
            </div>
            <!-- end card body -->
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total Profit</h5>
                        <h6>{{ number_format($totalProfit, 2) }} USDT</h6>
                    </div>
                    {{-- <div>
                        <button class="btn btn-primary take-wallet-action" data-bs-toggle="modal" data-bs-target="#funWallet"
                            data-url="{{ route('admin.users.fund', ['user' => $user->uuid, 'wallet' => 'fee']) }}">Credit</button>
                        <button class="btn btn-danger take-wallet-action" data-bs-toggle="modal" data-bs-target="#funWallet"
                            data-url="{{ route('admin.users.debit', ['user' => $user->uuid, 'wallet' => 'fee']) }}">Debit</button>
                    </div> --}}
                </div>
            </div>
            <!-- end card body -->
        </div>
    </div>
</div>

