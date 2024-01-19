@extends('admin.layouts.app')

@section('title', 'Create Ads Banner')

@section('content')
    <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
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
