@extends('admin.layouts.app')

@section('title', 'Transactions')

@section('content')
    <div class="card">
        <div class="card-body">
            @include('admin.partials._transactions_table')
        </div>
    </div>
@endsection
