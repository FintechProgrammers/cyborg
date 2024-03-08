<table class="table table-borderless">
    <tr>
        <th>Reference:</th>
        <td>{{ $transaction->reference }}</td>
    </tr>
    <tr>
        <th>Amount:</th>
        <td>{{ number_format($transaction->amount, 2) }} {{ $transaction->coin }}</td>
    </tr>
    <tr>
        <th>Type:</th>
        <td>
            <span class="{{ $transaction->type()->class }}">
                <span class="badge-label">{{ $transaction->type()->name }}</span>
            </span>
        </td>
    </tr>
    <tr>
        <th>Action:</th>
        <td>
            <span class="{{ $transaction->action()->class }}">
                <span class="badge-label">{{ $transaction->action()->name }}</span>
            </span>
        </td>
    </tr>
    <tr>
        <th>Status:</th>
        <td>
            <span class="{{ $transaction->status()->class }}">
                <span class="badge-label">{{ $transaction->status()->name }}</span>
            </span>
        </td>
    </tr>
    @if (!empty($transaction->address))
        <tr>
            <th>Address:</th>
            <td>
                {{ $transaction->address }}
            </td>
        </tr>
    @endif
    <tr>
        <th>Date:</th>
        <td> {{ $transaction->created_at->format('jS F, Y H:i:s') }}</td>
    </tr>
    <tr>
        <th>Naration:</th>
        <td>{{ $transaction->narration }}</td>
    </tr>
</table>
@if (
    $transaction->action == 'withdrawal' &&
        $transaction->status == 'pending' &&
        auth()->user()->can('approve transaction'))
    <button type="button" class="btn btn-primary approve-decline-btn"
        data-url="{{ route('admin.transactions.approve', $transaction->uuid) }}">
        <span class="spinner-border" role="status" style="display: none">
            <span class="sr-only">Loading...</span>
        </span>
        <span id="text">Approve Transaction</span>
    </button>
    <button type="button" class="btn btn-danger approve-decline-btn"
        data-url="{{ route('admin.transactions.decline', $transaction->uuid) }}">
        <span class="spinner-border" role="status" style="display: none">
            <span class="sr-only">Loading...</span>
        </span>
        <span id="text">Decline Transaction</span>
    </button>
@endif
