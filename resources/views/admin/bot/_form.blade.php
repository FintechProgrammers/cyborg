<div class="card">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Bot Name</label>
                        <input class="form-control" type="text" name="bot_name"
                            value="{{ isset($strategy) ? $strategy->bot_name : '' }}">
                        @error('bot_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Market</label>
                        <select name="market" class="form-control" id=""
                            {{ isset($strategy) ? 'disabled' : '' }}>
                            <option value="">--select--market--</option>
                            @foreach ($markets as $item)
                                <option value="{{ $item->id }}"
                                    {{ isset($strategy) && $item->id == $strategy->market_id ? 'Selected' : '' }}>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('market')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Market Type</label>
                        <select name="market_type" class="form-control" id="market_type"
                            {{ isset($strategy) ? 'disabled' : '' }}>
                            <option value="">--select--market--type--</option>
                            @foreach (\App\Models\Strategy::MARKETTYPE as $key=>$item)
                                <option value="{{ $item }}"
                                    {{ isset($strategy) && $item == $strategy->trade_type ? 'Selected' : '' }}>
                                        <span class="text-uppercase">{{ $key }}</span>
                                    </option>
                            @endforeach
                        </select>
                        @error('market_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="strategyMode"
                        style="display: {{ isset($strategy) && $strategy->strategy_modes == 'future' ? 'block' : 'none' }}">
                        <div class="mb-3">
                            <label for="example-text-input" class="form-label">Strategy Mode</label>
                            <select name="strategy_mode" class="form-control" id="strategy_mode"
                                {{ isset($strategy) ? 'disabled' : '' }}>
                                <option value="">--select--strategy--mode--</option>
                                @foreach (\App\Models\Strategy::STRATEGYMODE as $item)
                                    <option value="{{ $item }}"
                                        {{ isset($strategy) && $item == $strategy->strategy_modes ? 'Selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            @error('strategy_mode')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="example-text-input" class="form-label">Stop Loss (%)</label>
                            <input class="form-control" type="number" step="any" min="0" name="stop_loss"
                                value="{{ isset($strategy) ? $strategy->stop_loss : '' }}">
                            @error('stop_loss')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Take Profit (%)</label>
                        <input class="form-control" type="number" min="0" step="any" name="take_profit"
                            value="{{ isset($strategy) ? $strategy->take_profit : '' }}">
                        @error('take_profit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Minimum Capital (USDT)</label>
                        <input class="form-control" type="number" min="0" step="any" name="mimimum_capital"
                            value="{{ isset($strategy) ? $strategy->capital : '' }}">
                        @error('mimimum_capital')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <h5>Margin Settings</h5>
                    <div class="mb-3">
                        <label for="example-text-input" class="form-label">Margin Limit</label>
                        <input class="form-control" type="number" min="1" step="any"
                            id="{{ isset($strategy) ? '' : 'margin_limit' }}" name="margin_limit"
                            value="{{ isset($strategy) ? $strategy->margin_limit : '' }}"
                            {{ isset($strategy) ? 'readonly' : '' }}>
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
                                                    value="{{ $item }}"
                                                    {{ isset($strategy) ? 'readonly' : '' }}>
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
                                                    value="{{ $item }}"
                                                    {{ isset($strategy) ? 'readonly' : '' }}>
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
                    <button type="submit" class="btn btn-primary w-lg">Create Bot</button>
                </div>
            </div>
        </div>
    </div>
</div>
