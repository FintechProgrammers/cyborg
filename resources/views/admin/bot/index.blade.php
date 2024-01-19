@extends('admin.layouts.app')

@section('title', 'Bot')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-end mb-3">
        <div class="page-title-right">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.bot.create') }}" type="button"
                        class="btn btn-success waves-effect waves-light">Create Bot</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bot Name</th>
                        <th>Market</th>
                        <th>Margin Limit</th>
                        <th>Margin Ratio</th>
                        <th>Price Drop</th>
                        <th>Stop Loss</th>
                        <th>Take Profit</th>
                        <th width="20%">Option</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sno = 1;
                    @endphp
                    @forelse ($stretegy as $item)
                        <tr>
                            <td>{{ $sno++ }}</td>
                            <td>{{ $item->bot_name }}</td>
                            <td>{{ $item->market->name }}</td>
                            <td class="text-center">{{ $item->margin_limit }}</td>
                            <td class="text-center">{{ $item->m_ration }}</td>
                            <td class="text-center">{{ $item->price_drop }}</td>
                            <td class="text-center">{{ $item->stop_loss }}%</td>
                            <td class="text-center">{{ $item->take_profit }}%</td>
                            <td>
                                <a href="{{ route('admin.bot.edit', $item->uuid) }}" class="btn btn-primary">
                                    Edit
                                </a>
                                <a href="#" class="btn btn-danger delete"
                                    data-url="{{ route('admin.bot.delete', $item->uuid) }}">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-no-data-component title="no bot available" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.delete').click(function(e) {
            e.preventDefault();

            const url = $(this).data('url');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        method: "DELETE",
                        success: function(result) {
                            if (result.success) {
                                displayMessage(
                                    result.message,
                                    "success"
                                );
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                displayMessage(
                                    "Error occurred while trying to delete bot",
                                    "error"
                                );
                            }

                        },
                        error: function(jqXHR, testStatus, error) {

                            console.log(jqXHR.responseText, testStatus, error);

                            if (jqXHR.status === 404) {
                                // Handle 404 error here
                                displayMessage(
                                    "News not found.",
                                    "error"
                                );
                            } else {
                                // Handle other errors
                                displayMessage(
                                    "Error occurred while trying to delete bot",
                                    "error"
                                );
                            }
                        },
                        timeout: 8000,
                    });
                }
            })
        })
    </script>
@endpush
