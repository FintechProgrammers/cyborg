@extends('admin.layouts.app')

@section('title', 'Advertising Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-end">
                <div class="page-title-right">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.banner.create') }}" type="button"
                                class="btn btn-success waves-effect waves-light">Create Ads</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @forelse ($banners as $item)
            <div class="col-lg-4">
                <div class="card">
                    <div class="d-flex justify-content-end p-2">
                        <div class="btn-group">
                            <a href="#" class="dropdown-toggle btn btn-primary btn-sm" data-bs-toggle="dropdown"
                                aria-expanded="true"><i class="mdi mdi-menu"></i></a>
                            <div class="dropdown-menu dropdownmenu-warning" data-popper-placement="bottom-start"
                                style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(0px, 39.2593px, 0px);">
                                <a class="dropdown-item" href="{{ route('admin.banner.edit', $item->uuid) }}">Edit</a>
                                <a class="dropdown-item delete" data-url="{{ route('admin.banner.delete', $item->uuid) }}"
                                    href="#">Delete</a>
                            </div>
                        </div>
                    </div>
                    <img class="card-img img-fluid" src="{{ $item->file_url }}" alt="Card image">
                </div>
            </div>
        @empty
            <x-no-data-component title="no ads created" />
        @endforelse
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
