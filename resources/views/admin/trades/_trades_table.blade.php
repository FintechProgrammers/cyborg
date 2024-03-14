<div class="table-responsive">
    <table class="table table-bordered dt-responsive nowrap w-100 table-hover">
        <thead>
            <tr>
                <th>ID</th>
                @unless ($showUser)
                    <th>User</th>
                @endunless
                <th>Exchange</th>
                <th>Market</th>
                <th>Trade Price</th>
                <th>Profit</th>
                <th>Quantity</th>
                <th>Trade Type</th>
                <th>Type</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sno = 1;
            @endphp
            @forelse ($trades as $item)
                <tr>
                    <td>{{ $sno++ }}</td>
                    @unless ($showUser)
                        <td class="text-capitalize">{{ !empty($item->user) ? $item->user->username : null }}</td>
                    @endunless
                    <td>
                        {{ ucfirst($item->exchange->name) }}
                        @if ($item->is_profit)
                            <span class="badge bg-success">
                                <span class="badge-label">Profit</span>
                            </span>
                        @endif

                        @if ($item->is_stoploss)
                            <span class="badge bg-danger">
                                <span class="badge-label">Stoploss</span>
                            </span>
                        @endif
                    </td>
                    <td>
                        {{ $item->market }}
                    </td>
                    <td class="text-center">
                        {{ $item->trade_price }}
                    </td>
                    <td class="text-center">
                        {{ number_format($item->profit, 3) }}
                    </td>
                    <td class="text-center">
                        {{ $item->quantity }}
                    </td>
                    <td class="text-center">
                        {{ ucfirst($item->trade_type) }}
                    </td>
                    <td>
                        {{ ucfirst($item->type) }}
                    </td>
                    <td>
                        {{ $item->created_at->format('jS F, Y H:i:s') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">
                        <x-no-data-component title="no trades available" />
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div>
    {!! $trades->links('pagination::bootstrap-5') !!}
</div>
