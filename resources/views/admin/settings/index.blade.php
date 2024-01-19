@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf
                <h4>Withdrawal Settings</h4>
                <div class="row">
                    <div class="mb-3 col-lg-6">
                        <label class="form-label">Withdrawal Status
                            <span class="text-danger">
                                (Enable/Disable withdrawal)
                            </span>
                        </label>
                        <select class="form-select form-control" name="withdrawal_status">
                            <option value="">--select-status--</option>
                            @foreach (\App\Models\Settings::STATUS as $item)
                                <option value="{{ $item }}"
                                    {{ systemSettings()->withdrawal_status == $item ? 'Selected' : '' }}>{{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="form-label">Automatic Withdrawal
                            <span class="text-danger">
                                (Enable/Disable Automatic withdrawal)
                            </span></label>
                        <select class="form-select form-control" name="automatic_withdrawal">
                            <option value="">--select-status--</option>
                            @foreach (\App\Models\Settings::STATUS as $item)
                                <option value="{{ $item }}"
                                    {{ systemSettings()->automatic_withdrawal == $item ? 'Selected' : '' }}>{{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="example-search-input" class="form-label">Withdrawal Fee
                                <span class="text-danger">
                                    ( Recommend:Positive)
                                </span>
                            </label>
                            <input class="form-control" type="number" min="0" name="minimum_fee"
                                value="{{ systemSettings()->withdrawal_fee }}" step="any" placeholder="Withdrawal Fee"
                                id="example-search-input">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="example-search-input" class="form-label">Min Withdrawal Amount
                            </label>
                            <input class="form-control" type="number" min="0" name="minimum_withdrawal"
                                value="{{ systemSettings()->minimum_widthdrawal }}" step="any"
                                placeholder="Min Withdrawal Amt " id="example-search-input">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="example-search-input" class="form-label">Max Withdrawal Amount
                            </label>
                            <input class="form-control" type="number" min="0" name="maximum_withdrawal"
                                value="{{ systemSettings()->maximum_widthdrawal }}" step="any"
                                placeholder="Min Withdrawal Amt " id="example-search-input">
                        </div>
                    </div>
                </div>
                <h4>Trade Settings</h4>
                <div class="row">
                    <div class="mb-3 col-lg-6">
                        <label class="form-label">Trade Status
                            <span class="text-danger">
                                (Enable/Disable Trade)
                            </span>
                        </label>
                        <select class="form-select form-control" name="trading_status">
                            <option value="">--select-status--</option>
                            @foreach (\App\Models\Settings::STATUS as $item)
                                <option value="{{ $item }}"
                                    {{ systemSettings()->trade_status == $item ? 'Selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="example-search-input" class="form-label">Trading Fee</label>
                            <input class="form-control" type="number" min="0" name="trading_fee" step="any"
                                value="{{ systemSettings()->trade_fee }}" placeholder="Trading fee " id="example-search-input">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-lg">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
