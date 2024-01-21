@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Expire Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sno = 1;
                    @endphp
                    @forelse ($users as $item)
                        <tr>
                            <td>{{ $sno++ }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->plan }}</td>
                            <td>{{ !empty($item->expiry_date) ? formatTime($item->expiry_date) : '' }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $item->uuid) }}" class="btn btn-primary">
                                    Show
                                </a>
                            </td>
                        </tr>
                    @empty
                        {{-- <tr>
                            <td colspan="6">
                                <x-no-data-component title="no users available." />
                            </td>
                        </tr> --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script></script>
@endpush
