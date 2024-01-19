@extends('admin.layouts.app')

@section('title', 'Tickets Management')

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 table-hover">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>User</th>
                        <th>Subject</th>
                        <th width="20%">Option</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sno = 1;
                    @endphp
                    @forelse ($tickets as $item)
                        <tr>
                            <td>{{ $sno++ }}</td>
                            <td class="text-capitalize">{{ $item->user->name }}</td>
                            <td class="text-capitalize">{{ $item->subject }}</td>
                            <td>
                                <a href="{{ route('admin.supports.show', $item->uuid) }}" class="btn btn-primary">
                                    Reply
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <x-no-data-component title="no tickets available" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
