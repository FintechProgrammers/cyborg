@if (isset($totalAmount) && auth()->user()->hasRole('super admin'))
    <div>
        <div class="card card-h-100 bg-primary border-0">
            <!-- card body -->
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-6">
                        <span class="text-white mb-3 lh-1 d-block text-truncate">Total Amount</span>
                        <h4 class="mb-3 text-white">
                            <span class=" text-white">${{ number_format($totalAmount, 2) }}</span>
                        </h4>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>
@endif
<div class="table-responsive mb-3">
    <table class="table table-bordered dt-responsive nowrap w-100 table-hover">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Reference</th>
                @unless ($showUser)
                    <th>User</th>
                @endunless
                <th>Amount</th>
                <th>Type</th>
                <th>Action</th>
                <th>Status</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php
                $sno = 1;
            @endphp
            @forelse ($transactions as $item)
                <tr>
                    <td>{{ $sno++ }}</td>
                    <td>{{ $item->reference }}</td>
                    @unless ($showUser)
                        <th class="text-capitalize">{{ optional($item->user)->username }}</th>
                    @endunless
                    <td class="text-center">
                        {{ number_format($item->amount, 2) }}{{ $item->coin }}
                    </td>
                    <td class="text-center">
                        <span class="{{ $item->type()->class }}">
                            <span class="badge-label">{{ $item->type()->name }}</span>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="{{ $item->action()->class }}">
                            <span class="badge-label">{{ $item->action()->name }}</span>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="{{ $item->status()->class }}">
                            <span class="badge-label">{{ $item->status()->name }}</span>
                        </span>
                    </td>
                    <td>
                        {{ $item->created_at->format('jS F, Y H:i:s') }}
                    </td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#transactionDetails"
                            class="btn btn-primary btn-sm details"
                            data-url="{{ route('admin.transactions.show', $item->uuid) }}">Details</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        <x-no-data-component title="no transactions available" />
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div id="transaction-pag">
    {!! $transactions->links('pagination::bootstrap-5') !!}
</div>
