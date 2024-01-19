@extends('admin.layouts.app')

@section('title', 'Edit News')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>
                Currency Status :

                @if ($news->status == 'published')
                    <span class="badge bg-success">
                        <span class="badge-label">Active</span>
                    </span>
                @else
                    <span class="badge bg-danger">
                        <span class="badge-label">Disabled</span>
                    </span>
                @endif
            </h5>
            @if ($news->status == 'published')
                <button class="btn btn-danger enable-disable-btn" data-url="{{ route('admin.news.unpublish', $news->uuid) }}">
                    <span class="spinner-border" role="status" style="display: none">
                        <span class="sr-only">Loading...</span>
                    </span>
                    <span id="text">Disable</span>
                </button>
            @else
                <button class="btn btn-success enable-disable-btn"
                    data-url="{{ route('admin.news.publish', $news->uuid) }}">
                    <span class="spinner-border" role="status" style="display: none">
                        <span class="sr-only">Loading...</span>
                    </span>
                    <span id="text">Enable</span>

                </button>
            @endif
        </div>
    </div>
    <form action="{{ route('admin.news.update', $news->uuid) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.news._form')
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
