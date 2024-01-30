<div class="table-responsive">
    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 table-hover">
        <thead>
            <tr>
                <th>S/N</th>
                <th>User</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sno = 1;
            @endphp
            @forelse ($rewards as $item)
                <tr>
                    <td>{{ $sno++ }}</td>
                    <td>
                        {{ !empty(ucfirst($item->invit))
                        ? $item->invit : '' }}
                    </td>
                    <td>
                        {{ $item->amount }} USDT
                    </td>
                    <td class="text-center">
                        {{ $item->description }}
                    </td>
                    <td>
                        {{ $item->created_at->format('jS F, Y H:i:s') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <x-no-data-component title="no rewards available" />
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@push('scripts')
@endpush
