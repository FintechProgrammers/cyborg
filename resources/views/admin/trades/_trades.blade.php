<div class="card">
    <div class="card-body">
        <h6 class="make-text-bold mb-3">Filter the information your want.</h6>
        <div class="row align-items-end">
            {{-- @unless ($showUser)
                <div class="col-lg-3">
                    <div class="mb-3">
                        <label>User</label>
                        <input type="text" id="username" placeholder="Search by username" class="form-control">
                    </div>
                </div>
            @endunless --}}
            <input type="hidden" id="userId" value="{{ isset($user) ? $user->id : '' }}">
            <div class="col-lg-4">
                <x-exchange-component />
            </div>
            <div class="col-lg-4">
                <x-trade-type-component />
            </div>
            <div class="col-lg-4 mb-3">
                <button class="btn btn-primary btn-lg" id="filter">Filter</button>
                <button class="btn btn-light btn-lg lg-2" id="reset">Reset</button>
            </div>
        </div>
    </div>
</div>
<div id="trade-table">
    @include('admin.trades._trades_table')
</div>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
@push('scripts')
    <script>
        $('body').on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page').val(page);

            var username = $('#username').val();
            var exchange = $('#exchange').val();
            var trade_type = $('#trade_type').val();

            getData(page, username, exchange, trade_type)

        });

        $('#filter').click(function(e) {
            e.preventDefault();

            $('#hidden_page').val(1);

            var page = $('#hidden_page').val();

            var username = $('#username').val();
            var exchange = $('#exchange').val();
            var trade_type = $('#trade_type').val();

            getData(page, username, exchange, trade_type)

        })

        $('#reset').click(function(e) {
            e.preventDefault();

            location.reload()
        })

        function getData(page, username = null, exchange = null, trade_type = null) {

            tradeTable = $('#trade-table');

            $.ajax({
                url: `/admin/trades/filter?page=${page}`,
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    username: username,
                    exchange: exchange,
                    trade_type: trade_type,
                    user: $('#userId').val()
                },
                beforeSend: function() {
                    tradeTable.html(
                        `
                        <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                        </div>
                        `
                    );
                },
                success: function(result) {
                    setTimeout(() => {
                        tradeTable.empty().html(result);
                    }, 1000);
                },
                error: function(jqXHR, testStatus, error) {
                    displayMessage(
                        "Error occured while trying to sort and filter records.",
                        "error"
                    );
                },
                timeout: 8000,
            });
        }
    </script>
@endpush
