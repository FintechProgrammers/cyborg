<div class="card">
    {{-- <div class="card-header">
        <h5 class="card-title mb-0">About</h5>
    </div> --}}
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <h5>Main Balance</h5>
                <h6>{{ number_format(!empty($wallet) ? $wallet->balance : 0, 2) }} USDT</h6>
            </div>
            <div>
                <button class="btn btn-primary">Credit</button>
                <button class="btn btn-danger">Debit</button>
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
                <button class="btn btn-primary">Credit</button>
                <button class="btn btn-danger">Debit</button>
            </div>
        </div>
    </div>
    <!-- end card body -->
</div>
