<div class="card">
    <div class="card-body">
        <h6 class="make-text-bold mb-3">Filter the information your want.</h6>
        <div class="row align-items-end">
            <div class="col-lg-4">
                <div class="mb-3">
                    <label>Reference</label>
                    <input type="text" id="reference" placeholder="Transaction Reference" class="form-control">
                    <input type="hidden" id="userId" value="{{ isset($user) ? $user->id : '' }}">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label>Type</label>
                    <select name="" id="type" class="form-control">
                        <option value="">--select-transaction--type--</option>
                        <option value="debit">Debit</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label>Action</label>
                    <select name="" id="action" class="form-control">
                        <option value="">--select-action--</option>
                        <option value="withdrawal">Withdrawal</option>
                        <option value="deposit">Deposit</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="mb-3">
                    <label>Status</label>
                    <select name="" id="transaction_status" class="form-control">
                        <option value="">--select-status--</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Competed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label>Date</label>
                    <input type="text" id="search-date" name="daterange" class="form-control">
                </div>
            </div>
            <div class="col-lg-4 pt-4">
                <button class="btn btn-primary btn-lg" id="filter">Filter</button>
                <button class="btn btn-light btn-lg lg-2" id="reset">Reset</button>
            </div>

        </div>
    </div>
</div>
<div class="card">
    <div class="card-body" id="transactions-body">
        @include('admin.transactions._table')
    </div>
</div>
<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
@include('admin.partials._transaction_modal')
@push('scripts')
    <script>
        $('body').on('click', '.details', function(e) {
            e.preventDefault();

            var txBody = $('#transactionBody')

            var url = $(this).data('url')

            $.ajax({
                url: url,
                method: "GET",
                beforeSend: function() {
                    txBody.html(
                        `<div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>`
                    )
                },
                success: function(result) {
                    setTimeout(() => {
                        txBody.empty().html(result);
                    }, 1000);
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(jqXHR.responseText, testStatus, error);
                    displayMessage(
                        "Error occured while trying to get transactions records.",
                        "error"
                    );
                },
                timeout: 8000,
            });
        })

        $('body').on('click', '#approve', function(e) {
            e.preventDefault();

            const button = $(this)

            const url = button.data('url');

            const spinner = button.find('.spinner-border');
            const buttonText = button.find('#text')

            $.ajax({
                url: url,
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    buttonText.hide();
                    spinner.show();
                },
                success: function(result) {
                    if (result.success) {

                        displayMessage(
                            result.message,
                            "success"
                        );

                        setTimeout(() => {
                            location.reload();
                        }, 1000);

                    } else {

                        spinner.hide();
                        buttonText.show();

                        displayMessage(
                            result.message,
                            "error"
                        );
                    }
                },
                error: function(jqXHR, testStatus, error) {

                    console.log(jqXHR.responseText, testStatus, error);

                    spinner.hide();
                    buttonText.show();
                    // Handle other errors
                    displayMessage(
                        "Error occurred",
                        "error"
                    );

                },
                timeout: 8000,
            });
        })
    </script>
    <script>
        $('body').on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page').val(page);

            var ref = $('#reference').val();
            var status = $('#transaction_status').val();
            var action = $('#action').val();
            var type = $('#type').val();

            var date = $("#search-date").val();
            let [startDate, endDate] = date.split(" - ");


            getData(page, ref, status, type, action, startDate, endDate)

        });

        $('#filter').click(function(e) {
            e.preventDefault();

            $('#hidden_page').val(1);

            var page = $('#hidden_page').val();

            var ref = $('#reference').val();
            var status = $('#transaction_status').val();
            var action = $('#action').val();
            var type = $('#type').val();

            var date = $("#search-date").val();
            let [startDate, endDate] = date.split(" - ");

            getData(page, ref, status, type, action, startDate, endDate)

        })

        $('#reset').click(function(e) {
            e.preventDefault();

            location.reload()
        })

        function getData(page, reference = null, status = null, type = null, action = null, startDate = null, endDate =
            null) {

            txBody = $('#transactions-body');

            $.ajax({
                url: `/admin/transactions/filter?page=${page}`,
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    ref: reference,
                    date_from: startDate,
                    date_to: endDate,
                    status: status,
                    action: action,
                    type: type,
                    user: $('#userId').val()
                },
                beforeSend: function() {
                    txBody.html(
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
                        txBody.empty().html(result);
                    }, 1000);
                },
                error: function(jqXHR, testStatus, error) {
                    displayMessage(
                        "Error occured while trying to sort and filter transactions records.",
                        "error"
                    );
                },
                timeout: 8000,
            });
        }
    </script>
@endpush
