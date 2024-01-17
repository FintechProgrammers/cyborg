<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            @if ($type == 'main')
                <div>
                    <h5>Main Balance</h5>
                    <h6>{{ number_format(!empty($wallet) ? $wallet->balance : 0, 2) }} USDT</h6>
                </div>
            @else
                <div>
                    <h5>Fee Balance</h5>
                    <h6>{{ number_format(!empty($wallet) ? $wallet->fee : 0, 2) }} USDT</h6>
                </div>
            @endif
        </div>
    </div>
    <!-- end card body -->
</div>
<div class="mb-3">
    <label for="example-text-input" class="form-label">Amount</label>
    <input class="form-control" type="number" step="any" min="1" name="amount" id="amount">
    <div class="text-danger amount-error"></div>
</div>
<input type="hidden" value="{{ $type }}" id="type">
<div class="mb-3">
    <button class="btn btn-primary wallet-button">
        <span class="spinner-border" role="status" style="display: none">
            <span class="sr-only">Loading...</span>
        </span>
        <span id="text">Submit</span>
    </button>
</div>
