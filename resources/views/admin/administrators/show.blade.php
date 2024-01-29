@extends('admin.layouts.app')

@section('title', 'Edit Administrator')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.administrators.update',$admin->uuid) }}" method="POST">
                @csrf
                @include('admin.administrators._form')
            </form>
        </div>
    </div>
@endsection
