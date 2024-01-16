@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm order-2 order-sm-1">
                    <div class="d-flex align-items-start mt-3 mt-sm-0">
                        <div class="flex-shrink-0">
                            <div class="avatar-xl me-3">
                                <img src="{{ $user->profile_picture }}" alt=""
                                    class="img-fluid rounded-circle d-block">
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div>
                                <h5 class="font-size-16 mb-1 text-capitalize">{{ $user->name }}</h5>
                                <p class="text-muted font-size-13"><strong>Plan:</strong>{{ $user->plan }}</p>

                                <div class="d-flex flex-wrap align-items-start gap-2 gap-lg-3 text-muted font-size-13">
                                    <div><i
                                            class="mdi mdi-circle-medium me-1 text-success align-middle"></i>{{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs-custom card-header-tabs border-top mt-4" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link px-3 active" data-bs-toggle="tab" href="#wallet" role="tab"
                        aria-selected="true">Wallet</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#transactions" role="tab" aria-selected="false"
                        tabindex="-1">Transactions</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#trade" role="tab" aria-selected="false"
                        tabindex="-1">Trades</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-9 col-lg-8">
            <div class="tab-content">
                <div class="tab-pane active" id="wallet" role="tabpanel">
                    @include('admin.users._wallet')
                    <!-- end card -->
                </div>
                <!-- end tab pane -->

                <div class="tab-pane" id="transactions" role="tabpanel">
                    @include('admin.users._transactions')
                    <!-- end card -->
                </div>
                <!-- end tab pane -->

                <div class="tab-pane" id="trade" role="tabpanel">
                    @include('admin.users._trades')
                    <!-- end card -->
                </div>
                <!-- end tab pane -->
            </div>
            <!-- end tab content -->
        </div>
        <!-- end col -->

        <div class="col-xl-3 col-lg-4">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Exchanges</h5>

                    <div class="list-group list-group-flush">
                        @forelse ($exchanges as $item)
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    {{-- <div class="avatar-sm flex-shrink-0 me-3">
                                        <img src="assets/images/users/avatar-1.jpg" alt=""
                                            class="img-thumbnail rounded-circle">
                                    </div> --}}
                                    <div class="flex-grow-1">
                                        <div>
                                            <h5 class="font-size-14 mb-1">{{ $item->exchange->name }}</h5>
                                            <p class="font-size-13 text-muted mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <h6 class="text-warning">No Exchange Available</h6>
                        @endforelse
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection
@push('scripts')
    <script></script>
@endpush
