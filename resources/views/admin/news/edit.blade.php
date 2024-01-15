@extends('admin.layouts.app')

@section('title','Edit News')

@section('content')
<form action="{{ route('admin.news.update',$news->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf

    @include('admin.news._form')
</form>
@endsection


