@extends('admin.layouts.app')

@section('title', 'Edit Ads Banner')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>
                Currency Status :

                @if ($banner->enabled)
                    <span class="badge bg-success">
                        <span class="badge-label">Active</span>
                    </span>
                @else
                    <span class="badge bg-danger">
                        <span class="badge-label">Disabled</span>
                    </span>
                @endif
            </h5>
            @if ($banner->enabled)
                <button class="btn btn-danger enable-disable-btn"
                    data-url="{{ route('admin.banner.disable', $banner->uuid) }}">
                    <span class="spinner-border" role="status" style="display: none">
                        <span class="sr-only">Loading...</span>
                    </span>
                    <span id="text">Disable</span>
                </button>
            @else
                <button class="btn btn-success enable-disable-btn"
                    data-url="{{ route('admin.banner.enable', $banner->uuid) }}">
                    <span class="spinner-border" role="status" style="display: none">
                        <span class="sr-only">Loading...</span>
                    </span>
                    <span id="text">Enable</span>

                </button>
            @endif
        </div>
    </div>
    <form action="{{ route('admin.banner.edit', $banner->uuid) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="col-lg-6">
            <div class="card mb-3">
                <img class="card-img img-fluid" src="{{ $banner->file_url }}" alt="Card image">
            </div>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <div class="mb-3">
                                <label for="example-text-input" class="form-label">Photo</label>
                                <input class="form-control" type="file" name="photo" placeholder="Picture Name"
                                    id="example-text-input">
                                @error('photo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-lg">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        $('.enable-disable-btn').click(function(e) {
            e.preventDefault();

            const button = $(this)

            const url = $(this).data('url');

            const spinner = button.find('.spinner-border');
            const buttonText = button.find('#text')

            $.ajax({
                url: url,
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    buttonText.hide();
                    spinner.show();
                },
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

                        spinner.hide();
                        buttonText.show();

                        displayMessage(
                            result.message,
                            "error"
                        );

                    }

                },
                error: function(jqXHR, testStatus, error) {

                    console.log(jqXHR.responseText, testStatus, error);

                    spinner.hide();
                    buttonText.show();

                    // Handle other errors
                    displayMessage(
                        "Error occurred",
                        "error"
                    );


                },
                timeout: 8000,
            });
        })
    </script>
@endpush
