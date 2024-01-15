@extends('admin.layouts.app')

@section('title', 'Edit Bot')

@section('content')
    <form action="{{ route('admin.bot.update',$bot->uuid) }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('admin.bot._form')
    </form>
@endsection
