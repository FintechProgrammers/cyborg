@extends('admin.layouts.app')

@section('title', 'News')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-end mb-3">
        <div class="page-title-right">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.news.create') }}" type="button"
                        class="btn btn-success waves-effect waves-light">Create News</a>
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
                        <th>Title</th>
                        <th>Caption</th>
                        <th width="20%">Option</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sno = 1;
                    @endphp
                    @forelse ($news as $item)
                        <tr>
                            <td>{{ $sno++ }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ createCaption($item->content) }}</td>
                            <td>
                                <a href="{{ route('admin.news.edit', $item->uuid) }}" class="btn btn-primary">
                                    Edit
                                </a>
                                <a href="#" class="btn btn-danger delete"
                                    data-url="{{ route('admin.news.delete', $item->uuid) }}">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <x-no-data-component title="no news created" />
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
                                    "error"
                                );
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                displayMessage(
                                    "Error occurred while trying to delete news",
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
                                    "Error occurred while trying to delete news",
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
