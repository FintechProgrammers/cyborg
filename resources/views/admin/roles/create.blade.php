@extends('admin.layouts.app')

@section('title', 'Create Role')

@section('content')
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        @include('admin.roles._form')
    </form>
@endsection
