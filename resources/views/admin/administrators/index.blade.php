@extends('admin.layouts.app')

@section('title', 'Administrators Management')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-end">
                <div class="page-title-right">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.administrators.create') }}" type="button"
                                class="btn btn-success waves-effect waves-light">Create Administrator</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 table-hover">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sno = 1;
                        @endphp
                        @forelse ($admins as $item)
                            <tr>
                                <td>{{ $sno++ }}</td>
                                <td class="text-capitalize">{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    @if (!in_array($item['name'], ['super admin', 'default']))
                                        <a href="{{ route('admin.administrators.show', $item->uuid) }}"
                                            class="btn btn-primary">
                                            Edit
                                        </a>
                                        <a href="#" class="btn btn-danger delete"
                                            data-url="{{ route('admin.administrators.delete', $item->uuid) }}">
                                            Delete
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            {{-- <tr>
                                <td colspan="8">
                                    <x-no-data-component title="no roles available" />
                                </td>
                            </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                                    "Error occurred while trying to delete banner",
                                    "error"
                                );
                            }

                        },
                        error: function(jqXHR, testStatus, error) {

                            console.log(jqXHR.responseText, testStatus, error);

                            if (jqXHR.status === 404) {
                                // Handle 404 error here
                                displayMessage(
                                    "Banner does not exist.",
                                    "error"
                                );
                            } else {
                                // Handle other errors
                                displayMessage(
                                    "Error occurred while trying to delete banner",
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
