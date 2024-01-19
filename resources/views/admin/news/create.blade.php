@extends('admin.layouts.app')

@section('title', 'Create News')

@section('content')
    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('admin.news._form')
    </form>
@endsection
