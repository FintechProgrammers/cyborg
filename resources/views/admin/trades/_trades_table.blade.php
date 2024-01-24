<div class="table-responsive">
    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 table-hover">
        <thead>
            <tr>
                <th>ID</th>
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
                    <td>
                        {{ ucfirst($item->exchange->name) }}
                        @if ($item->is_profit)
                            <span class="badge bg-success">
                                <span class="badge-label">Profit</span>
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
                        {{ $item->profit }}
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
                {{-- <tr>
                    <td colspan="8">
                        <x-no-data-component title="no trades available" />
                    </td>
                </tr> --}}
            @endforelse
        </tbody>
    </table>

</div>
@include('admin.partials._transaction_modal')
@push('scripts')
    <script>
        $('body').on('click', '.details', function(e) {
            e.preventDefault();

            var txBody = $('#transactionBody')

            var url = $(this).data('url')

            $.ajax({
                url: url,
                method: "GET",
                beforeSend: function() {
                    txBody.html(
                        `<div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>`
                    )
                },
                success: function(result) {
                    setTimeout(() => {
                        txBody.empty().html(result);
                    }, 1000);
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(jqXHR.responseText, testStatus, error);
                    displayMessage(
                        "Error occured while trying to get transactions records.",
                        "error"
                    );
                },
                timeout: 8000,
            });
        })
    </script>
@endpush
