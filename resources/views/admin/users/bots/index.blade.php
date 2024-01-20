<div class="card-header mb-3">
    <div class="d-flex justify-content-between">
        <h4 class="card-title mb-0">Bots</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#botForm" id="ceateBot"
            data-url="{{ route('admin.users.create.bot', $user->uuid) }}">Create Bot</button>
    </div>
</div>
@forelse ($bots as $item)
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0 me-3">
                        <img src="{{ asset('coin/' . $item->market->coin_image) }}" alt=""
                            class="img-thumbnail rounded-circle">
                    </div>
                    <div class="flex-grow-1">
                        <div>
                            <h5 class="font-size-14 mb-1">{{ $item->market->name }}</h5>
                            <p class="font-size-13 text-muted mb-0">{{ $item->exchange->name }}</p>
                        </div>
                    </div>
                </div>
                <div>

                </div>
                <div>
                    @if ($item->started)
                        <button class="btn btn-danger btn-sm bot-btn"
                            data-url="{{ route('admin.users.bot.stop', $item->uuid) }}">
                            <span class="spinner-border" role="status" style="display: none">
                                <span class="sr-only">Loading...</span>
                            </span>
                            <span id="text">Stop Bot</span>
                        </button>
                    @else
                        <button class="btn btn-success btn-sm bot-btn"
                            data-url="{{ route('admin.users.bot.start', $item->uuid) }}">
                            <span class="spinner-border" role="status" style="display: none">
                                <span class="sr-only">Loading...</span>
                            </span>
                            <span id="text">Start Bot</span>
                        </button>
                        <button class="btn btn-danger btn-sm bot-btn"
                            data-url="{{ route('admin.users.bot.delete', $item->uuid) }}">
                            <span class="spinner-border" role="status" style="display: none">
                                <span class="sr-only">Loading...</span>
                            </span>
                            <span id="text">Delete</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <x-no-data-component title="no bot created" />
@endforelse
