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
    <tr>
        <th>Date:</th>
        <td> {{ $transaction->created_at->format('jS F, Y H:i:s') }}</td>
    </tr>
    <tr>
        <th>Naration:</th>
        <td>{{ $transaction->narration }}</td>
    </tr>
</table>
