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
                                <option value="{{ $item->id }}" {{ isset($strategy) && $item->id == $strategy->market_id ? 'Selected':'' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('market')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Stop Loss (%)</label>
                        <input class="form-control" type="number" step="any" name="stop_loss"
                            value="{{ isset($strategy) ? $strategy->stop_loss : '' }}">
                        @error('stop_loss')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Take Profit (%)</label>
                        <input class="form-control" type="number" step="any" name="take_profit"
                            value="{{ isset($strategy) ? $strategy->take_profit : '' }}">
                        @error('take_profit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <h5>Margin Settings</h5>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Margin Limit</label>
                        <input class="form-control" type="number" min="1" step="any" id="margin_limit"
                            name="margin_limit" value="{{ isset($strategy) ? $strategy->margin_limit : '' }}">
                        @error('margin_limit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="marginBody" style="display: {{ isset($strategy) ? 'block' : 'none' }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5>Margin Ratio</h5>
                                <div id="marginRatio">
                                    @if (isset($strategy))
                                        @php
                                            $m_ratio = explode('|', $strategy->m_ration);
                                        @endphp
                                        @foreach ($m_ratio as $item)
                                            <div class="mb-3">
                                                <input class="form-control" type="number" name="margin_ratio[]"
                                                    value="{{ $item }}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                @error('margin_ratio')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <h5>Price Drop</h5>
                                <div id="priceDrop">
                                    @if (isset($strategy))
                                        @php
                                            $price_drop = explode('|', $strategy->price_drop);
                                        @endphp
                                        @foreach ($price_drop as $item)
                                            <div class="mb-3">
                                                <input class="form-control" type="number" name="price_drop[]"
                                                    value="{{ $item }}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
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
