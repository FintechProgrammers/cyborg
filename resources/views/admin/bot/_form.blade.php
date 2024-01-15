<div class="card">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Martet</label>
                        <select name="market" class="form-control" id="">
                            <option value="">--select--market--</option>
                            @foreach ($markets as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('market')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Stop Loss (%)</label>
                        <input class="form-control" type="number" step="any" name="stop_loss">
                        @error('stop_loss')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Take Profit (%)</label>
                        <input class="form-control" type="number" step="any" name="take_profit">
                        @error('take_profit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <h5>Margin Settings</h5>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Margin Limit</label>
                        <input class="form-control" type="number" min="1" step="any" id="margin_limit"
                            name="margin_limit">
                        @error('margin_limit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="marginBody" style="display: none">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5>Margin Ratio</h5>
                                <div id="marginRatio"></div>
                                @error('margin_ratio')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <h5>Price Drop</h5>
                                <div id="priceDrop"></div>
                                @error('price_drop')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-lg">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
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
                                    <label for="example-text-input" class="form-label"></label>
                                    <input class="form-control" type="number" name="margin_ratio[]">
                                </div>
                `);
                    $('#marginRatio').append(marginRatio);
                }

                for (var i = 0; i < marginLimitValue; i++) {
                    var priceDrop = $(`
                            <div class="mb-3">
                                    <label for="example-text-input" class="form-label"></label>
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
