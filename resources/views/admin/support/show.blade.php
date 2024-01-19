@extends('admin.layouts.app')

@section('title', 'Tickets Details')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Subject: {{ $ticket->subject }}</h5>
        </div>
        <div class="card-body">
            <p>
                {{ $ticket->content }}
            </p>
            @if ($ticket->file_url)
                <img src="{{ $ticket->file_url }}" width="50%" height="250px" />
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Replies
        </div>
        <div class="chat-conversation p-3 px-2" data-simplebar>
            <ul class="list-unstyled mb-0">
                @foreach ($replies as $item)
                    {{-- <li class="chat-day-title">
                    <span class="title">Today</span>
                </li> --}}
                    @if ($item->user_id == Auth::guard('admin')->user()->id)
                        <li>
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content">
                                        {{-- <h5 class="conversation-name"><a href="#" class="user-name">Jennie Sherlock</a> <span
                                    class="time">10:00</span></h5> --}}
                                        <p class="mb-0">{{ $item->reply }}</p>
                                    </div>
                                </div>
                            </div>

                        </li>
                    @else
                        <li class="right">
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content">
                                        {{-- <h5 class="conversation-name"><a href="#" class="user-name">Shawn</a> <span
                                    class="time">10:02</span></h5> --}}
                                        <p class="mb-0">{{ $item->reply }}</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="p-3 border-top">
            <form action="{{ route('admin.supports.reply', $ticket->uuid) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="position-relative">
                            <input type="text" class="form-control border bg-light-subtle" name="message"
                                placeholder="Enter Message...">
                            @error('message')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary chat-send w-md waves-effect waves-light"><span
                                class="d-none d-sm-inline-block me-2">Send</span> <i
                                class="mdi mdi-send float-end"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
