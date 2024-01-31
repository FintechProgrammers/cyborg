@extends('admin.layouts.app')

@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-4">
        <div class="card">
            <div class="card-header">
                Change Password
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update.password') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="">Current Password</label>
                        <input type="password" name="current_password" class="form-control" id="current_password"/>
                        @error('current_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" id="password"/>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password"/>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-8">
        <div class="card">
            <div class="card-header">
                Profile Information
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-lg-12">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $admin->name }}"/>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-lg-12">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $admin->email }}" readonly/>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush
