@extends('admin.authentication.layouts.app')

@section('title','PAssword Reset')

@section('content')
    <div class="text-center">
        <h5 class="mb-0">Reset Password</h5>
        <p class="text-muted mt-2">Set new password.</p>
    </div>
    <form class="mt-4 pt-2" action="{{ route('admin.login.submit') }}" method="post">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <label class="form-label" for="password">Password</label>
                </div>
            </div>

            <div class="input-group auth-pass-inputgroup">
                <input type="password" class="form-control" placeholder="Enter password" name="password"
                    aria-label="Password" aria-describedby="password-addon">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <button class="btn btn-light ms-0" type="button" id="password-addon">
                    <i class="mdi mdi-eye-outline"></i>
                </button>
            </div>

        </div>
        <div class="mb-3">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <label class="form-label" for="password">Confirm Password</label>
                </div>
            </div>

            <div class="input-group auth-pass-inputgroup">
                <input type="password" class="form-control" placeholder="Enter password" name="password_confirmation"
                    aria-label="Confirm Password" aria-describedby="confirm-password-addon">
                <button class="btn btn-light ms-0" type="button" id="confirm-password-addon">
                    <i class="mdi mdi-eye-outline"></i>
                </button>
            </div>

        </div>
        <div class="mb-3">
            <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Submit</button>
        </div>
    </form>
@endsection
