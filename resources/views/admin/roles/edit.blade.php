@extends('admin.layouts.app')

@section('title', 'Update Role')

@section('content')
    <form action="{{ route('admin.roles.update', $role->uuid) }}" method="POST">
        @csrf
        @include('admin.roles._form')
    </form>
@endsection
