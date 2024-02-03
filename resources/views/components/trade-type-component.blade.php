<div class="mb-3">
    <label>Trade Type</label>
    <select name="traed_type" id="trade_type" class="form-control">
        <option value="">--select--trade_type--</option>
        @foreach ($tradeType as $item)
            <option value="{{ $item}}">{{ $item }}</option>
        @endforeach
    </select>
</div>
