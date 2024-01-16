@push('scripts')
    <script>
        $('#margin_limit').keyup(function(e) {
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
                            <input class="form-control" type="number" name="margin_ratio[]">
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
    </script>
@endpush
