@extends('admin.layouts.app')

@section('title', 'Edit Bot')

@section('content')
    <form action="{{ route('admin.bot.update',$strategy->uuid) }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('admin.bot._form')
    </form>
@endsection
@include('admin.bot._scripts')
