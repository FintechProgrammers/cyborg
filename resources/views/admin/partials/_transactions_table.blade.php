<div class="table-responsive">
    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 table-hover">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Reference</th>
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
                    <td colspan="8">
                        <x-no-data-component title="no bot created" />
                    </td>
                </tr>
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
