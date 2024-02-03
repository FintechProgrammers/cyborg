@extends('admin.layouts.app')

@section('title', 'Trades')

@section('content')
    <div class="card">
        <div class="card-body">
            @include('admin.trades._trades')
        </div>
    </div>
@endsection
