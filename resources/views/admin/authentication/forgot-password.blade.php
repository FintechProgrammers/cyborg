@extends('admin.authentication.layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="text-center">
        <h5 class="mb-0">Reset Password</h5>
        <p class="text-muted mt-2">Reset Password with {{ Config::get('app.name') }}.</p>
    </div>
    <form class="mt-4 pt-2" action="{{ route('admin.forgot.password.post') }}" method="post">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Submit</button>
        </div>
    </form>
    <div class="mt-5 text-center">
        <p class="text-muted mb-0">Remember It ? <a href="{{ route('admin.login') }}" class="text-primary fw-semibold"> Sign
                In </a> </p>
    </div>
@endsection
