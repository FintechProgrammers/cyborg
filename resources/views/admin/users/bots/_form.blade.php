<div class="mb-3">
    <label for="example-text-input" class="form-label">Bot Name</label>
    <input class="form-control" type="text" id="bot_name" name="bot_name" value="{{ isset($bot) ? $bot->name : '' }}">
    <div class="text-danger bot_name-error"></div>
</div>
<div class="mb-3">
    <label for="example-text-input" class="form-label">Exchange</label>
    <select name="exchange" class="form-control" id="exchange" {{ isset($bot) ? 'disabled' : '' }}>
        <option value="">--select--exchange--</option>
        @foreach ($exchanges as $item)
            <option value="{{ $item->exchange->uuid }}"
                {{ isset($bot) && $item->id == $bot->exchange_id ? 'Selected' : '' }}>
                {{ $item->exchange->name }}</option>
        @endforeach
    </select>
    <div class="text-danger exchange-error"></div>
</div>
<div class="mb-3">
    <label for="example-text-input" class="form-label">Market</label>
    <select name="market" class="form-control" id="market" {{ isset($bot) ? 'disabled' : '' }}>
        <option value="">--select--market--</option>
        @foreach ($markets as $item)
            <option value="{{ $item->uuid }}" {{ isset($bot) && $item->id == $bot->market_id ? 'Selected' : '' }}>
                {{ $item->name }}</option>
        @endforeach
    </select>
    <div class="text-danger market-error"></div>
</div>
<div class="mb-3">
    <label for="example-text-input" class="form-label">Trade Type</label>
    <select name="trade_type" class="form-control" id="trade_type" {{ isset($bot) ? 'disabled' : '' }}>
        <option value="">--select--market--type--</option>
        @foreach (\App\Models\Strategy::MARKETTYPE as $key => $item)
            <option value="{{ $item }}" {{ isset($bot) && $item == $bot->trade_type ? 'Selected' : '' }}>
                <span class="text-uppercase">{{ $key }}</span>
            </option>
        @endforeach
    </select>
    <div class="text-danger trade_type-error"></div>
</div>
<div id="strategyMode" style="display: {{ isset($bot) && $bot->strategy_modes == 'future' ? 'block' : 'none' }}">
    <div class="mb-3">
        <label for="example-text-input" class="form-label">Strategy Mode</label>
        <select name="strategy_mode" class="form-control" id="strategy_mode" {{ isset($bot) ? 'disabled' : '' }}>
            <option value="">--select--strategy--mode--</option>
            @foreach (\App\Models\Strategy::STRATEGYMODE as $item)
                <option value="{{ $item }}"
                    {{ isset($bot) && $item == $bot->strategy_modes ? 'Selected' : '' }}>
                    {{ $item }}</option>
            @endforeach
        </select>
        <div class="text-danger strategy_mode-error"></div>
    </div>
    <div class="mb-3">
        <label for="example-text-input" class="form-label">Stop Loss (%)</label>
        <input class="form-control" type="number" step="any" id="stop_loss" name="stop_loss"
            value="{{ isset($bot) ? $bot->stop_loss : '' }}">
        <div class="text-danger stop_loss-error"></div>
    </div>
</div>
<div class="mb-3">
    <label for="example-text-input" class="form-label">Take Profit (%)</label>
    <input class="form-control" type="number" step="any" name="take_profit" id="take_profit"
        value="{{ isset($bot) ? $bot->take_profit : '' }}">
    <div class="text-danger take_profit-error"></div>
</div>
<div class="mb-3">
    <label for="example-text-input" class="form-label">First Buy</label>
    <input class="form-control" type="number" step="any" name="first_buy" id="first_buy"
        value="{{ isset($bot) ? $bot->first_buy : '' }}">
    <div class="text-danger first_buy-error"></div>
</div>
<div class="mb-3">
    <label for="example-text-input" class="form-label">Capital</label>
    <input class="form-control" type="number" step="any" name="capital" id="capital"
        value="{{ isset($bot) ? $bot->capital : '' }}">
    <div class="text-danger capital-error"></div>
</div>
<h5>Margin Settings</h5>
<div class="mb-3">
    <label for="example-text-input" class="form-label">Margin Limit</label>
    <input class="form-control" type="number" min="1" step="any" id="margin_limit"
        id="{{ isset($bot) ? '' : 'margin_limit' }}" name="margin_limit"
        value="{{ isset($bot) ? $bot->margin_limit : '' }}" {{ isset($bot) ? 'readonly' : '' }}>
    <div class="text-danger margin_limit-error"></div>
</div>
<div id="marginBody" style="display: {{ isset($bot) ? 'block' : 'none' }}">
    <div class="row">
        <div class="col-lg-6">
            <h5>Margin Ratio</h5>
            <div id="marginRatio">
                @if (isset($bot))
                    @php
                        $m_ratio = explode('|', $bot->m_ration);
                    @endphp
                    @foreach ($m_ratio as $item)
                        <div class="mb-3">
                            <input class="form-control" type="number" name="margin_ratio[]"
                                value="{{ $item }}" {{ isset($bot) ? 'readonly' : '' }}>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-danger m_ratio-error"></div>
        </div>
        <div class="col-lg-6">
            <h5>Price Drop</h5>
            <div id="priceDrop">
                @if (isset($bot))
                    @php
                        $price_drop = explode('|', $bot->price_drop);
                    @endphp
                    @foreach ($price_drop as $item)
                        <div class="mb-3">
                            <input class="form-control" type="number" name="price_drop[]"
                                value="{{ $item }}" {{ isset($bot) ? 'readonly' : '' }}>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-danger price_drop-error"></div>
        </div>
    </div>
</div>
<div class="mb-3">
    <button class="btn btn-primary bot-action">
        <span class="spinner-border" role="status" style="display: none">
            <span class="sr-only">Loading...</span>
        </span>
        <span id="text">Submit</span>
    </button>
</div>
