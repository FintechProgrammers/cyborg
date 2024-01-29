@extends('admin.layouts.app')

@section('title', 'Add Administrator')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.administrators.store') }}" method="POST">
                @csrf
                @include('admin.administrators._form')
            </form>
        </div>
    </div>
@endsection
