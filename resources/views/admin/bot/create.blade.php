@extends('admin.layouts.app')

@section('title', 'Create Bot')

@section('content')
    <form action="{{ route('admin.bot.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('admin.bot._form')
    </form>
@endsection
