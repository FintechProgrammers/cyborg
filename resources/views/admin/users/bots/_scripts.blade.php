<script>
    $('#ceateBot').click(function(e) {
        e.preventDefault();

        const content = $('#botFormBody')

        const url = $(this).data('url');

        $.ajax({
            url: url,
            method: "GET",
            beforeSend: function() {
                content.html(
                    `<div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>`
                )
            },
            success: function(result) {
                setTimeout(() => {
                    content.empty().html(result);
                }, 1000);
            },
            error: function(jqXHR, testStatus, error) {

                $('#botForm').modal('hide')

                console.log(jqXHR.responseText, testStatus, error);
                // Handle other errors
                displayMessage(
                    "Error occurred",
                    "error"
                );

            },
            timeout: 8000,
        });
    })

    $('body').on('keyup', '#margin_limit', function(e) {
        e.preventDefault();

        // Get the value of margin_limit input
        var marginLimitValue = $(this).val();

        $('#marginRatio').empty();
        $('#priceDrop').empty();

        if (marginLimitValue > 0) {


            // Create two inputs
            for (var i = 0; i < marginLimitValue; i++) {
                var marginRatio = $(`
                <div class="mb-3">
                        <input class="form-control" type="number" name="m_ratio[]">
                    </div>
            `);
                $('#marginRatio').append(marginRatio);
            }

            for (var i = 0; i < marginLimitValue; i++) {
                var priceDrop = $(`
                <div class="mb-3">
                        <input class="form-control" type="number" name="price_drop[]">
                    </div>
                `);
                $('#priceDrop').append(priceDrop);
            }

            $('#marginBody').show()


        } else {
            $('#marginBody').hide()
        }
    })

    $('body').on('change', '#trade_type', function(e) {
        e.preventDefault();

        const market_type = $(this).val();

        const strategyMode = $('#strategyMode')

        if (market_type === "future") {
            strategyMode.show();
        } else {
            strategyMode.hide();
        }
    })

    $('body').on('click', '.bot-action', function(e) {
        e.preventDefault();

        const content = $('#botFormBody')

        const form = content.find('form');

        const url = form.attr('action');

        form.find('.text-danger').text('')

        const button = $(this)

        const spinner = button.find('.spinner-border');
        const buttonText = button.find('#text')

        $.ajax({
            url: url,
            method: "POST",
            data: form.serializeArray(),
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

                if (jqXHR.status == 422) {
                    const errors = jqXHR.responseJSON.errors;

                    // Display validation errors
                    $.each(errors, function(key, value) {
                        form.find('.' + key + '-error').html(value);
                    });
                } else {
                    // Handle other errors
                    displayMessage(
                        "Error occurred",
                        "error"
                    );
                }

            },
            timeout: 8000,
        });

    })

    $('body').on('click', '.bot-btn', function(e) {

        const button = $(this)

        const url = button.data('url');

        const spinner = button.find('.spinner-border');
        const buttonText = button.find('#text')

        $.ajax({
            url: url,
            method: "POST",
            data: {_token:"{{ csrf_token() }}"},
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
